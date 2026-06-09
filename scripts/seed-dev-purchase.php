#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Dev-only: insert fake leads and purchases so member login works without ClickBank INS.
 *
 * Usage (from project root):
 *   1. Set in .env: ALLOW_DEV_PURCHASE_SEED=1, DB_*, and optionally DEV_SEED_* emails.
 *   2. php scripts/migrate.php
 *   3. php scripts/seed-dev-purchase.php
 *
 * Log in at login.php with DEV_SEED_BUYER_EMAIL (default dev-buyer@example.com).
 */

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use App\Repository\ReadingDeliveryRepository;
use App\Services\ReadingDeliveryTrigger;

$projectRoot = dirname(__DIR__);

require $projectRoot . '/vendor/autoload.php';

$config = AppConfig::load($projectRoot);

$allow = $_ENV['ALLOW_DEV_PURCHASE_SEED'] ?? getenv('ALLOW_DEV_PURCHASE_SEED') ?: '';
if ($allow !== '1') {
    fwrite(STDERR, 'Refusing to run: set ALLOW_DEV_PURCHASE_SEED=1 in .env for dev seeding only.' . PHP_EOL);
    exit(1);
}

if (!$config->hasDatabaseConfig()) {
    fwrite(STDERR, 'Database not configured: set DB_NAME and DB_USER in .env.' . PHP_EOL);
    exit(1);
}

$buyerEmail = seedEnvString('DEV_SEED_BUYER_EMAIL', 'dev-buyer@example.com');
$noPurchaseEmail = seedEnvString('DEV_SEED_NO_PURCHASE_EMAIL', 'dev-nopurchase@example.com');
$refundedEmail = seedEnvString('DEV_SEED_REFUNDED_EMAIL', 'dev-refunded@example.com');

$pdo = DatabaseConnection::fromConfig($config);
$leads = new LeadRepository($pdo);
$purchases = new PurchaseRepository($pdo);

$receiptSmr1 = 'DEV-SEED-SMR1';
$receiptRefunded = 'DEV-SEED-REFUNDED';

$itemsSmr1 = [
    [
        'sku' => 'smr-1',
        'item' => 'smr-1',
        'productTitle' => 'Soul Mirror Reading',
        'quantity' => 1,
        'itemNo' => '1',
    ],
];

// Funnel + OTO (upsell-1.php uses cbitems=srp-1); unlocks Soul Ritual when MEMBER_RITUAL_SKU=srp-1.
$itemsBuyerWithRitual = array_merge($itemsSmr1, [
    [
        'sku' => 'srp-1',
        'item' => 'srp-1',
        'productTitle' => 'Soul Ritual Practice',
        'quantity' => 1,
        'itemNo' => 'srp-1',
    ],
]);

$rawInsApproved = [
    'receipt' => $receiptSmr1,
    'transactionType' => 'SALE',
    'lineItems' => $itemsBuyerWithRitual,
];

$rawInsRefunded = [
    'receipt' => $receiptRefunded,
    'transactionType' => 'RFND',
    'lineItems' => $itemsSmr1,
];

try {
    $buyerLeadId = seedFullLead(
        $leads,
        $buyerEmail,
        'Morgan Lee',
        '1988-03-22',
        'female',
        devSeedCards(1, 9, 19),
        devSeedReadingPayload('Gemini'),
    );
    $purchases->upsertByReceipt(
        $buyerLeadId,
        $receiptSmr1,
        'SALE',
        'approved',
        'USD',
        37.00,
        $itemsBuyerWithRitual,
        $rawInsApproved,
    );

    seedFullLead(
        $leads,
        $noPurchaseEmail,
        'Alex Kim',
        '1995-11-08',
        'nonbinary',
        devSeedCards(4, 14, 24),
        devSeedReadingPayload('Scorpio'),
    );

    $refundedLeadId = seedFullLead(
        $leads,
        $refundedEmail,
        'Jordan Hayes',
        '1992-07-01',
        'male',
        devSeedCards(7, 17, 27),
        devSeedReadingPayload('Cancer'),
    );
    $purchases->upsertByReceipt(
        $refundedLeadId,
        $receiptRefunded,
        'RFND',
        'refunded',
        'USD',
        37.00,
        $itemsSmr1,
        $rawInsRefunded,
    );
    $purchaseId = $purchases->findIdByReceipt($receiptSmr1);
    if ($purchaseId !== null && !(new ReadingDeliveryRepository($pdo))->hasCompletedDelivery($purchaseId)) {
        (new ReadingDeliveryTrigger($projectRoot))->queuePurchaseDelivery($purchaseId);
        echo '  Queued personalized PDF generation for purchase id ' . $purchaseId . PHP_EOL;
    }
} catch (Throwable $e) {
    fwrite(STDERR, 'Seed failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

echo 'Dev purchase seed completed.' . PHP_EOL;
echo '  Member access (smr-1 + srp-1 in items_json, status approved): ' . $buyerEmail . PHP_EOL;
echo '  No purchase (login should deny): ' . $noPurchaseEmail . PHP_EOL;
echo '  Refunded purchase (login should deny): ' . $refundedEmail . PHP_EOL;

/**
 * Upserts a funnel-complete lead (name, dob, gender, cards, reading) and returns its id.
 *
 * @param array<int,array<string,mixed>> $cards
 * @param array<string,mixed>|null $readingPayload
 */
function seedFullLead(
    LeadRepository $leads,
    string $email,
    string $name,
    string $dob,
    string $gender,
    array $cards,
    ?array $readingPayload = null,
): int {
    $leads->upsertLead($email, $name, $dob, $gender, $cards, $readingPayload);
    $id = $leads->findIdByEmail($email);
    if ($id === null) {
        throw new RuntimeException('Unable to resolve lead id after upsert for ' . $email);
    }

    return $id;
}

/**
 * @return list<array{id:int,name:string,slug:string,suit:string}>
 */
function devSeedCards(int $id1, int $id2, int $id3): array
{
    $deck = [
        1 => ['The Fool', 'the-fool', 'major'],
        4 => ['The Emperor', 'the-emperor', 'major'],
        7 => ['The Chariot', 'the-chariot', 'major'],
        9 => ['The Hermit', 'the-hermit', 'major'],
        14 => ['Temperance', 'temperance', 'major'],
        17 => ['The Star', 'the-star', 'major'],
        19 => ['The Sun', 'the-sun', 'major'],
        24 => ['Queen of Wands', 'queen-of-wands', 'wands'],
        27 => ['Knight of Cups', 'knight-of-cups', 'cups'],
    ];

    $out = [];
    foreach ([$id1, $id2, $id3] as $id) {
        if (!isset($deck[$id])) {
            throw new InvalidArgumentException('Unknown dev seed card id: ' . $id);
        }
        [$name, $slug, $suit] = $deck[$id];
        $out[] = ['id' => $id, 'name' => $name, 'slug' => $slug, 'suit' => $suit];
    }

    return $out;
}

/**
 * @return array{love:string,life:string,wealth:string,sun_sign:string}
 */
function devSeedReadingPayload(string $sunSign): array
{
    return [
        'love' => 'Dev seed: connection deepens when you speak plainly about what you need.',
        'life' => 'Dev seed: a small daily ritual steadies you through busy weeks.',
        'wealth' => 'Dev seed: one focused skill investment pays back within the quarter.',
        'sun_sign' => $sunSign,
    ];
}

/**
 * @param non-empty-string $default
 */
function seedEnvString(string $key, string $default): string
{
    $v = $_ENV[$key] ?? getenv($key);
    if (is_string($v) && trim($v) !== '') {
        return trim($v);
    }

    return $default;
}

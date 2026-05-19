#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Writes data/tarot-predictions.json with all 78 cards (placeholder text).
 * Run harvest-tarot-predictions.php to replace with AstrologyAPI copy.
 *
 * php scripts/generate-tarot-scaffold.php [output-path]
 */

$projectRoot = dirname(__DIR__);

require $projectRoot . '/vendor/autoload.php';

use App\Domain\TarotCardCatalog;

$outPath = $argv[1] ?? $projectRoot . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'tarot-predictions.json';

$cards = [];
foreach (TarotCardCatalog::all() as $card) {
    $id = (string) $card->frontendId;
    $cards[$id] = [
        'slug' => $card->slug,
        'name' => $card->name,
        'love' => sprintf('Love guidance for %s.', $card->name),
        'career' => sprintf('Life path guidance for %s.', $card->name),
        'finance' => sprintf('Wealth guidance for %s.', $card->name),
    ];
}

$payload = [
    'version' => 1,
    'idScheme' => 'frontend-fool-first',
    'source' => 'scaffold-placeholder',
    'generatedAt' => gmdate('c'),
    'cards' => $cards,
];

$dir = dirname($outPath);
if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
    fwrite(STDERR, "Cannot create directory: {$dir}\n");
    exit(1);
}

$json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
if (file_put_contents($outPath, $json . "\n") === false) {
    fwrite(STDERR, "Failed to write {$outPath}\n");
    exit(1);
}

echo 'Wrote ' . $outPath . ' (' . count($cards) . " cards)\n";

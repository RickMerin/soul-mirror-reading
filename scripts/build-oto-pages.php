<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$src = $root . '/../Downloads/soul-ritual-upsell-downsell-2026-04-23/upsell-oto1.html';
// Try Windows user Downloads path
if (!is_readable($src)) {
    $src = 'C:/Users/Rick Merin/Downloads/soul-ritual-upsell-downsell-2026-04-23/upsell-oto1.html';
}
$html = file_get_contents($src);
if ($html === false) {
    fwrite(STDERR, "Cannot read: $src\n");
    exit(1);
}

$html = str_replace('src="assets/', 'src="frontend/images/ups-downs/', $html);
if (strpos($html, '--gold-dim:') === false) {
    $html = str_replace(
        "      --white:         #ffffff;\n    }",
        "      --white:         #ffffff;\n      --gold-dim:      #8B6914;\n      --text-mid:      #6b5c82;\n    }",
        $html,
    );
}

$php = <<<'PHP'
<?php
declare(strict_types=1);

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

\App\Config\AppConfig::load($projectRoot);

$oto = $_ENV['UPSELL_OTO_CHECKOUT_URL'] ?? getenv('UPSELL_OTO_CHECKOUT_URL');
if (!is_string($oto) || trim($oto) === '') {
    $oto = $_ENV['MEMBER_OTO_CHECKOUT_URL'] ?? getenv('MEMBER_OTO_CHECKOUT_URL');
}
$otoCheckoutUrl = is_string($oto) && trim($oto) !== '' ? trim($oto) : '#';
$downsellPageUrl = 'downsell-1.php';

?>
PHP;

$html = str_replace('<head>', "<head>\n  <link rel=\"icon\" type=\"image/svg+xml\" href=\"favicon.svg\" />", $html, $c);
$html = preg_replace(
    '/<a href="#" class="cta-btn">\s*\n\s*Yes, I Want To Upgrade My Soul Mirror Journey\s*\n\s*<\/a>/m',
    "<a href=\"<?= htmlspecialchars(\$otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>\" class=\"cta-btn\">\n        Yes, I Want To Upgrade My Soul Mirror Journey\n      </a>",
    $html,
    1,
);
$html = str_replace(
    "<button class=\"cta-btn--secondary\" onclick=\"window.location.href='#no-thanks'\">",
    "<a class=\"cta-btn--secondary\" href=\"<?= htmlspecialchars(\$downsellPageUrl, ENT_QUOTES, 'UTF-8') ?>\">",
    $html,
);
$html = str_replace(
    "No thank you &mdash; I'll work through my block alone\n      </button>",
    "No thank you &mdash; I'll work through my block alone\n      </a>",
    $html,
);
$html = str_replace(
    '<a href="#offer" class="cta-btn" style="text-decoration:none; display:inline-block; margin-bottom:16px;">' . "\n" .
    '        Yes, I Want To Upgrade My Soul Mirror Journey' . "\n" . '      </a>',
    '<a href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, \'UTF-8\') ?>" class="cta-btn" style="text-decoration:none; display:inline-block; margin-bottom:16px;">' . "\n" .
    '        Yes, I Want To Upgrade My Soul Mirror Journey' . "\n" . '      </a>',
    $html,
);

file_put_contents($root . '/public/upsell-1.php', $php . $html);
echo "Wrote public/upsell-1.php\n";

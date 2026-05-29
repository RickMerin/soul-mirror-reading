<?php
declare(strict_types=1);

/**
 * Convert v4 review-annotated funnel HTML into public/{funnel}/sales.php.
 *
 * Usage:
 *   php scripts/build-funnel-sales-from-v4-html.php love  "/path/Sales-Page-Love-Funnel-v4-Review-Annotated.html"
 *   php scripts/build-funnel-sales-from-v4-html.php purpose "/path/Sales-Page-Purpose-Funnel-v4-Review-Annotated.html"
 */

if ($argc < 3) {
    fwrite(STDERR, "Usage: php build-funnel-sales-from-v4-html.php <love|purpose> <annotated-html-path>\n");
    exit(1);
}

$funnel = $argv[1];
$sourcePath = $argv[2];

$allowed = ['love' => 'smr-1-l', 'purpose' => 'smr-1-p'];
if (!isset($allowed[$funnel])) {
    fwrite(STDERR, "Funnel must be love or purpose.\n");
    exit(1);
}

$cbItem = $allowed[$funnel];
if (!is_readable($sourcePath)) {
    fwrite(STDERR, "Cannot read: {$sourcePath}\n");
    exit(1);
}

$html = file_get_contents($sourcePath);
if ($html === false) {
    fwrite(STDERR, "Failed to read source file.\n");
    exit(1);
}

// Remove review-mode banner.
$html = preg_replace(
    '/<div style="background:linear-gradient\(90deg, #1a0d2e.*?<\/div>\s*/s',
    '',
    $html,
    1
) ?? $html;

// Unwrap red annotation boxes: keep inner content, drop label + "v3 was" note.
$html = preg_replace_callback(
    '/<div style="border:3px dashed #cc0000;[^>]*>\s*'
    . '<div style="position:absolute[^>]*>.*?<\/div>\s*'
    . '(.*?)\s*'
    . '<div style="margin-top:10px; padding:8px 12px; background:#f8f8f8; border-left:3px solid #cc0000;[^>]*>.*?<\/div>\s*'
    . '<\/div>/s',
    static fn (array $m): string => trim($m[1]),
    $html
) ?? $html;

$checkoutUrl = "https://rebornf.pay.clickbank.net/?cbitems={$cbItem}&template=order-3&cbfid=63301&exitoffer=exit-1";
$checkoutPhp = '<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>';
$html = str_replace(
    'href="https://rebornf.pay.clickbank.net/?cbitems=smr-1&amp;template=order-3&amp;cbfid=63301&amp;exitoffer=exit-1"',
    'href="' . $checkoutPhp . '"',
    $html
);

// Extract body inner HTML (between <body> and </body>).
if (!preg_match('/<body[^>]*>(.*)<\/body>/s', $html, $bodyMatch)) {
    fwrite(STDERR, "Could not parse body from HTML.\n");
    exit(1);
}
$body = trim($bodyMatch[1]);

$root = dirname(__DIR__);
$cssPath = $root . '/public/assets/sales-v2-bundle.min.css';
$jsPath = $root . '/public/assets/sales-v2.min.js';
$cssVer = is_file($cssPath) ? (string) filemtime($cssPath) : (string) time();
$jsVer = is_file($jsPath) ? (string) filemtime($jsPath) : (string) time();

// Title + description from source head.
$title = 'Your Soul Mirror Reading. What the Cards Are Really Saying';
$description = 'See the one core belief, your Mirror Block, behind your Love, Life, and Wealth cards. Deep card work, clearing practice, and 90-day prompts delivered with your Soul Mirror Reading.';
if (preg_match('/<title>([^<]+)<\/title>/', $html, $t)) {
    $title = html_entity_decode(trim($t[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}
if (preg_match('/<meta name="description"\s+content="([^"]+)"/', $html, $d)) {
    $description = html_entity_decode(trim($d[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

$outDir = $root . '/public/' . $funnel;
if (!is_dir($outDir) && !mkdir($outDir, 0755, true) && !is_dir($outDir)) {
    fwrite(STDERR, "Could not create {$outDir}\n");
    exit(1);
}

$outPath = $outDir . '/sales.php';

$head = '<?php
declare(strict_types=1);
$cssPath = __DIR__ . "/../assets/sales-v2-bundle.min.css";
$cssVer = is_file($cssPath) ? filemtime($cssPath) : time();
$jsPath = __DIR__ . "/../assets/sales-v2.min.js";
$jsVer = is_file($jsPath) ? filemtime($jsPath) : time();
$checkoutUrl = ' . var_export($checkoutUrl, true) . ';
$metaDescription = ' . var_export($description, true) . ';
$pageTitle = ' . var_export($title, true) . ';
?>
<!DOCTYPE html>
<html lang="en" data-funnel-base="' . $funnel . '/">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES) ?>">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES) ?></title>
  <link rel="icon" type="image/svg+xml" href="../favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&amp;family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&amp;family=Crimson+Pro:wght@300;400;500;600&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/sales-v2-bundle.min.css?v=<?= htmlspecialchars((string) $cssVer, ENT_QUOTES) ?>">
  <script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "wq82rtc2gf");
  </script>
</head>

<body>

';

$foot = '
  <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
  <script defer src="../assets/sales-v2.min.js?v=<?= htmlspecialchars((string) $jsVer, ENT_QUOTES) ?>"></script>
</body>

</html>
';

// Strip trailing scripts/footer close from annotated export (we append our own).
$body = preg_replace(
    '/\s*<script defer src="https:\/\/cdn\.jsdelivr\.net\/npm\/gsap[^<]+<\/script>\s*'
    . '<script defer src="[^"]+sales-v2\.min\.js[^"]*"[^>]*><\/script>\s*$/s',
    '',
    $body
) ?? $body;

// Normalize middots to HTML entity like wealth page.
$body = str_replace('·', '&middot;', $body);

$php = $head . $body . $foot;

if (file_put_contents($outPath, $php) === false) {
    fwrite(STDERR, "Failed to write {$outPath}\n");
    exit(1);
}

// Verify no annotation residue.
$checks = [
    'border:3px dashed #cc0000' => 'annotation boxes',
    'v3 was:' => 'v3 comparison notes',
    'Review Mode' => 'review banner',
    'smr-1&amp;' => 'old checkout SKU in href',
    'smr-1&template' => 'old checkout SKU in href',
];
foreach ($checks as $needle => $label) {
    if (str_contains($php, $needle)) {
        fwrite(STDERR, "Warning: {$label} still present ({$needle})\n");
    }
}

echo "Wrote {$outPath} (cbitems={$cbItem})\n";

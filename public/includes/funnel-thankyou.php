<?php
declare(strict_types=1);

if (!isset($funnelBase)) {
    $funnelBase = '';
}
if (!isset($assetRoot)) {
    $assetRoot = '';
}

$cssPath = dirname(__DIR__) . '/assets/thankyou-bundle.min.css';
$cssVer = is_file($cssPath) ? filemtime($cssPath) : time();
$jsPath = dirname(__DIR__) . '/assets/thankyou.min.js';
$jsVer = is_file($jsPath) ? filemtime($jsPath) : time();
?>
<!DOCTYPE html>
<html lang="en"<?= $funnelBase !== '' ? ' data-funnel-base="' . htmlspecialchars($funnelBase, ENT_QUOTES) . '"' : '' ?>>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thank You — Personalized 3 Card Tarot Reading</title>
  <link rel="icon" type="image/svg+xml" href="<?= htmlspecialchars($assetRoot, ENT_QUOTES) ?>favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Crimson+Pro:ital,wght@0,300;0,400;1,300&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="<?= htmlspecialchars($assetRoot, ENT_QUOTES) ?>assets/thankyou-bundle.min.css?v=<?= htmlspecialchars((string) $cssVer, ENT_QUOTES) ?>">

  <!-- ─────────────────────────────────────────
    Microsoft Clarity
  ───────────────────────────────────────── -->
  <script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "wq82rtc2gf");
  </script>
</head>

<body>
  <div class="dream-bg" aria-hidden="true">
    <div class="milky-way"></div>
    <div class="dream-orb one"></div>
    <div class="dream-orb two"></div>
    <div class="dream-shooting" id="dreamShooting"></div>
    <div class="dream-sparkles" id="dreamSparkles"></div>
  </div>

  <div class="wrap">
    <section class="thankyou-card" aria-labelledby="thankyouHeading">
      <div class="eyebrow">3 Card Tarot Reading</div>
      <h1 id="thankyouHeading">Your reading is <em>on its way</em></h1>
      <p class="lead">Check your inbox for your personalized 3 Card Reading. If you do not see it within a few
        minutes, peek in your promotions or spam folder.</p>
      <p class="lead">One belief showed up across all three of your cards. Your reading names it, and shows you how to clear it.</p>

      <div class="countdown-shell">
        <div class="countdown-ring" role="img" aria-label="Redirect countdown timer">
          <svg viewBox="0 0 120 120" aria-hidden="true">
            <circle class="ring-track" cx="60" cy="60" r="48"></circle>
            <circle class="ring-progress" id="countdownRingProgress" cx="60" cy="60" r="48"></circle>
          </svg>
          <div class="countdown-value" aria-live="polite">
            <span id="redirectCountdown">10</span><span class="countdown-unit">s</span>
          </div>
        </div>
        <p class="redirect-note" id="redirectCopy">Redirecting to your next step in 10 seconds.</p>
      </div>
    </section>
  </div>

  <script defer src="<?= htmlspecialchars($assetRoot, ENT_QUOTES) ?>assets/thankyou.min.js?v=<?= htmlspecialchars((string) $jsVer, ENT_QUOTES) ?>"></script>
</body>

</html>
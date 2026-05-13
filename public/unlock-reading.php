<?php
declare(strict_types=1);

$projectRoot = dirname(__DIR__);
require_once $projectRoot . '/vendor/autoload.php';

use App\Config\AppConfig;

$unlockKitBootstrapJson = '{"embedScriptSrc":"","embedDataUid":"","formSubscribeVia":"api"}';
try {
    $unlockKitBootstrapJson = json_encode(
        AppConfig::load($projectRoot)->unlockReadingKitBootstrap(),
        JSON_THROW_ON_ERROR
            | JSON_UNESCAPED_SLASHES
            | JSON_HEX_TAG
            | JSON_HEX_AMP
            | JSON_HEX_APOS
            | JSON_HEX_QUOT,
    );
} catch (Throwable) {
    // Safe default: API path only; no embed bootstrap.
}

$cssPath = __DIR__ . '/assets/bundle.min.css';
$cssVer = is_file($cssPath) ? filemtime($cssPath) : time();
$jsPath = __DIR__ . '/assets/unlock-reading.min.js';
$jsVer = is_file($jsPath) ? filemtime($jsPath) : time();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Unlock Your Reading — Soul Mirror</title>
  <link rel="icon" type="image/svg+xml" href="favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Crimson+Pro:ital,wght@0,300;0,400;1,300&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="assets/bundle.min.css?v=<?= htmlspecialchars((string) $cssVer, ENT_QUOTES) ?>">
  <script>
    (function () {
      try {
        var K = 'soulMirrorReadingPick';
        function pickOk(raw) {
          try {
            var d = JSON.parse(raw);
            if (!d || d.v !== 1 || !Array.isArray(d.cards) || d.cards.length !== 3) return false;
            for (var i = 0; i < 3; i++) {
              var c = d.cards[i];
              if (!c || typeof c.slug !== 'string' || !c.slug) return false;
              if (typeof c.name !== 'string' || !c.name) return false;
              if (typeof c.suit !== 'string' || !c.suit) return false;
              var id = +c.id;
              if (id !== (id | 0) || id < 1 || id > 78) return false;
            }
            return true;
          } catch (e) {
            return false;
          }
        }
        document.documentElement.classList.add(
          pickOk(sessionStorage.getItem(K)) ? 'unlock-ok' : 'unlock-bad'
        );
      } catch (e) {
        document.documentElement.classList.add('unlock-bad');
      }
    })();
  </script>

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
  <script type="application/json" id="unlockKitConfig"><?= $unlockKitBootstrapJson ?></script>
  <div class="dream-bg" aria-hidden="true">
    <div class="dream-veil"></div>
    <div class="milky-way"></div>
    <div class="dream-orb one"></div>
    <div class="dream-orb two"></div>
    <div class="dream-orb three"></div>
    <div class="dream-shooting" id="dreamShooting"></div>
    <div class="dream-sparkles" id="dreamSparkles"></div>
  </div>

  <div class="unlock-blocked unlock-page" aria-labelledby="unlockBlockedTitle">
    <div class="unlock-blocked-inner">
      <p class="unlock-eyebrow">Soul Mirror Reading</p>
      <h1 id="unlockBlockedTitle">Choose your three cards first</h1>
      <p class="unlock-blocked-copy">The mirror only opens after you have drawn all three cards on the reading page.</p>
      <a class="unlock-back-btn" href="index.php">← Back to card reading</a>
    </div>
  </div>

  <main class="unlock-main unlock-page" aria-labelledby="unlockTitle">
    <div class="unlock-panel">
      <h1 id="unlockTitle">Your Reading Is Ready.</h1>
      <p class="form-sub">The mirror has seen what others have missed.<br>Enter your details now and we'll send your
        full reading directly to you.</p>

      <div class="chosen-recap" id="chosenRecap"></div>

      <div class="form-box">
        <p class="kit-embed-loading" id="kitEmbedLoading" aria-live="polite">Loading your reading form…</p>
        <div id="kit-form-embed-root" class="kit-form-embed-root"></div>
        <p class="form-privacy">Your reading arrives within minutes. Your information is never shared.</p>
        <div class="error-msg" id="unlockFormError" role="alert" aria-live="assertive"></div>
      </div>
    </div>
  </main>

  <footer class="site-footer wavy">
    <p class="site-footer-links">
      <a href="/privacy-policy">Privacy Policy</a> &nbsp;·&nbsp;
      <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;·&nbsp;
      <a href="mailto:support@soulmirrorreading.com">Contact Us</a> &nbsp;·&nbsp;
      <a href="/refund-return-policy">Refund &amp; Return Policy</a>
    </p>
    <p>Copyright © 2026 Soul Mirror Reading. All Right Reserved.</p>
  </footer>

  <script defer src="assets/unlock-reading.min.js?v=<?= htmlspecialchars((string) $jsVer, ENT_QUOTES) ?>"></script>
</body>

</html>

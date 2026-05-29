<?php
declare(strict_types=1);

if (!isset($funnelBase)) {
    $funnelBase = '';
}
if (!isset($assetRoot)) {
    $assetRoot = '';
}

$projectRoot = dirname(__DIR__, 2);require_once $projectRoot . '/vendor/autoload.php';

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

$cssPath = dirname(__DIR__) . '/assets/bundle.min.css';
$cssVer = is_file($cssPath) ? filemtime($cssPath) : time();
$jsPath = dirname(__DIR__) . '/assets/unlock-reading.min.js';
$jsVer = is_file($jsPath) ? filemtime($jsPath) : time();
?>
<!DOCTYPE html>
<html lang="en"<?= $funnelBase !== '' ? ' data-funnel-base="' . htmlspecialchars($funnelBase, ENT_QUOTES) . '"' : '' ?>>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Unlock Your Reading — Soul Mirror</title>
  <link rel="icon" type="image/svg+xml" href="<?= htmlspecialchars($assetRoot, ENT_QUOTES) ?>favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Crimson+Pro:ital,wght@0,300;0,400;1,300&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="<?= htmlspecialchars($assetRoot, ENT_QUOTES) ?>assets/bundle.min.css?v=<?= htmlspecialchars((string) $cssVer, ENT_QUOTES) ?>">  <script>
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
      <h1 id="unlockTitle">Your Reading Is Processing.</h1>
      <p class="form-sub">Where should we send your Mirror Block reading?<br>Add your details below. It lands within minutes.</p>

      <div class="chosen-recap" id="chosenRecap"></div>

      <div class="form-box">
        <form id="readingForm" novalidate>
          <div class="form-group">
            <label for="inputName">First Name</label>
            <input type="text" id="inputName" placeholder="Enter Your First Name..." required
              autocomplete="given-name" />
          </div>
          <div class="form-group">
            <label for="inputEmail">Your Email</label>
            <input type="email" id="inputEmail" placeholder="Enter Your Email Address..." required
              autocomplete="email" />
          </div>
          <div class="form-group">
            <label>Date of Birth</label>
            <div class="dob-row">
              <select id="inputDobMonth" required>
                <option value="" disabled selected>Month</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
              </select>
              <select id="inputDobDay" required>
                <option value="" disabled selected>Day</option>
              </select>
              <select id="inputDobYear" required>
                <option value="" disabled selected>Year</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="inputGender">Gender</label>
            <select id="inputGender" required>
              <option value="" disabled selected>Select...</option>
              <option value="Female">Female</option>
              <option value="Male">Male</option>
            </select>
          </div>
          <button type="submit" class="submit-btn is-invalid" id="submitBtn" disabled>
            Unlock My Reading &nbsp;→
          </button>
          <p class="form-privacy">By entering your email, you're joining our mailing list and agree to receive our daily newsletter. You can unsubscribe at any time. Your information is never shared.</p>
          <div class="error-msg" id="errorMsg" role="alert" aria-live="assertive"></div>
        </form>
      </div>
    </div>
  </main>

  <div id="kit-form-embed-root" class="kit-form-embed-root" aria-hidden="true"></div>

  <footer class="site-footer wavy">
    <p class="site-footer-links">
      <a href="/privacy-policy">Privacy Policy</a> &nbsp;·&nbsp;
      <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;·&nbsp;
      <a href="mailto:support@soulmirrorreading.com">Contact Us</a> &nbsp;·&nbsp;
      <a href="/refund-return-policy">Refund &amp; Return Policy</a>
    </p>
    <p>Copyright © 2026 Soul Mirror Reading. All Right Reserved.</p>
  </footer>

  <script defer src="<?= htmlspecialchars($assetRoot, ENT_QUOTES) ?>assets/unlock-reading.min.js?v=<?= htmlspecialchars((string) $jsVer, ENT_QUOTES) ?>"></script></body>

</html>

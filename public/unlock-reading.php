<?php
declare(strict_types=1);
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
</head>

<body>
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
      <span id="nameGreet" style="display:none;color:var(--gold);font-style:normal"></span>

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
          <p class="form-privacy">Your reading arrives within minutes. Your information is never shared.</p>
          <div class="error-msg" id="errorMsg" role="alert" aria-live="assertive"></div>
        </form>
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

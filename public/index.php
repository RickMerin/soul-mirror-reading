<?php
declare(strict_types=1);
$cssPath = __DIR__ . '/assets/bundle.min.css';
$cssVer = is_file($cssPath) ? filemtime($cssPath) : time();
$jsPath = __DIR__ . '/assets/home.min.js';
$jsVer = is_file($jsPath) ? filemtime($jsPath) : time();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Soul Mirror Reading — Free 3-Card Tarot Reading</title>
  <link rel="icon" type="image/svg+xml" href="favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Crimson+Pro:ital,wght@0,300;0,400;1,300&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="assets/bundle.min.css?v=<?= htmlspecialchars((string) $cssVer, ENT_QUOTES) ?>">
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

  <!-- ── HEADER ─────────────────────────────── -->
  <header class="site-header">
    <div class="header-eyebrow">☽ &nbsp; Soul Mirror Reading &nbsp; ☾</div>
    <h1>The Mirror Sees What<br><em>You've Been Too Afraid to Look At.</em></h1>
    <p class="header-sub">Pick 3 cards and discover what's truly keeping you stuck:<br>in love, in life, in the wealth
      you deserve.</p>
  </header>

  <!-- ── CARD PICKER ────────────────────────── -->
  <section class="picker-section" id="pickerSection">
    <div class="stage">
      <!-- Chosen slots -->
      <div class="chosen-zone" id="chosenZone">
        <div class="cs" id="cs0">
          <div class="cs-corner tl"></div>
          <div class="cs-corner tr"></div>
          <div class="cs-corner bl"></div>
          <div class="cs-corner br"></div>
          <div class="cs-border"></div>
          <div class="cs-inner">
            <div class="cs-num">I</div>
            <div class="cs-label-text">First</div>
          </div>
          <div class="placed-card" id="pc0"></div>
          <div class="cs-slot-label">Your Love</div>
        </div>
        <div class="cs" id="cs1" style="transform:translateY(-12px)">
          <div class="cs-corner tl"></div>
          <div class="cs-corner tr"></div>
          <div class="cs-corner bl"></div>
          <div class="cs-corner br"></div>
          <div class="cs-border"></div>
          <div class="cs-inner">
            <div class="cs-num">II</div>
            <div class="cs-label-text">Second</div>
          </div>
          <div class="placed-card" id="pc1"></div>
          <div class="cs-slot-label">Your Life</div>
        </div>
        <div class="cs" id="cs2">
          <div class="cs-corner tl"></div>
          <div class="cs-corner tr"></div>
          <div class="cs-corner bl"></div>
          <div class="cs-corner br"></div>
          <div class="cs-border"></div>
          <div class="cs-inner">
            <div class="cs-num">III</div>
            <div class="cs-label-text">Third</div>
          </div>
          <div class="placed-card" id="pc2"></div>
          <div class="cs-slot-label">Your Wealth</div>
        </div>
      </div>

      <!-- Divider -->
      <div class="header-divider picker-divider" id="pickerDivider" style="margin: 24px 0 28px;"><span
          id="cardsRemainingText">Choose your cards below · 3 more</span></div>

      <!-- Deck -->
      <p class="deck-instruction" id="deckInstruction">Press the button below to reveal your cards</p>
      <div class="deck-wrap">
        <div class="deck-arc" id="deckArc">
          <div class="shuffle-prompt" id="shufflePrompt">
            <div class="shuffle-prompt-text">Click to Shuffle Your Cards</div>
          </div>
        </div>
        <div class="prompt-wrap">
          <div class="sm-prompt" id="smPrompt">
            Take a breath.<br>
            Think of what feels unresolved right now.<br>
            Hold it in your heart — and <em>choose your first card.</em>
          </div>
        </div>
      </div>
    </div>

  </section>

  <!-- ── SUCCESS ─────────────────────────────── -->
  <section class="success-section" id="successSection">
    <div class="success-icon">✦</div>
    <h2>Your Reading Is On Its Way</h2>
    <p>The mirror has reflected your truth.<br>Check your inbox — your Soul Mirror Reading has been sent to you.</p>
  </section>

  <footer class="site-footer wavy">
    <p class="site-footer-links">
      <a href="/privacy-policy">Privacy Policy</a> &nbsp;·&nbsp;
      <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;·&nbsp;
      <a href="mailto:support@divinegracegift.com">Contact Us</a> &nbsp;·&nbsp;
      <a href="/refund-return-policy">Refund &amp; Return Policy</a>
    </p>
    <p>Copyright © 2026 Divine Grace Gift. All Right Reserved.</p>
  </footer>

  <script defer src="assets/home.min.js?v=<?= htmlspecialchars((string) $jsVer, ENT_QUOTES) ?>"></script>
</body>

</html>
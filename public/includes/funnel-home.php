<?php
declare(strict_types=1);

if (!isset($funnelBase)) {
    $funnelBase = '';
}
if (!isset($assetRoot)) {
    $assetRoot = '';
}

$cssPath = dirname(__DIR__) . '/assets/bundle.min.css';
$cssVer = is_file($cssPath) ? filemtime($cssPath) : time();
$jsPath = dirname(__DIR__) . '/assets/home.min.js';
$jsVer = is_file($jsPath) ? filemtime($jsPath) : time();
?>
<!DOCTYPE html>
<html lang="en"<?= $funnelBase !== '' ? ' data-funnel-base="' . htmlspecialchars($funnelBase, ENT_QUOTES) . '"' : '' ?>>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Soul Mirror Reading — Free 3-Card Tarot Reading</title>
  <link rel="icon" type="image/svg+xml" href="<?= htmlspecialchars($assetRoot, ENT_QUOTES) ?>favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Crimson+Pro:ital,wght@0,300;0,400;1,300&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="<?= htmlspecialchars($assetRoot, ENT_QUOTES) ?>assets/bundle.min.css?v=<?= htmlspecialchars((string) $cssVer, ENT_QUOTES) ?>">

  <!-- ─────────────────────────────────────────
       Soul Mirror Reading – UX polish overrides
       Added 2026-04-29
       ───────────────────────────────────────── -->
  <style>
    /* ── Pre-shuffle hidden bubble + reveal animation ── */
    #smPrompt {
      opacity: 0;
      transform: translateY(8px) scale(.96);
      transition: opacity .5s ease, transform .5s ease;
      pointer-events: none;
    }
    #smPrompt.is-visible {
      opacity: 1;
      pointer-events: auto;
      position: relative;
      padding: 12px 18px;
      max-width: 360px;
      font-size: clamp(.95rem, 1.5vw, 1.05rem);
      line-height: 1.45;
      border-radius: 16px;
      background: linear-gradient(180deg, rgba(58,47,160,.55) 0%, rgba(34,26,102,.65) 100%);
      border: 1px solid rgba(212,175,55,.45);
      box-shadow:
        0 0 0 1px rgba(255,255,255,.04) inset,
        0 12px 40px rgba(10,5,40,.45),
        0 0 24px rgba(212,175,55,.18);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      animation: smPromptIn .55s ease forwards, smPromptPulse 2.4s ease-in-out 0.55s infinite;
    }
    #smPrompt.is-visible::before {
      content: "";
      position: absolute;
      top: -7px; left: 50%;
      width: 13px; height: 13px;
      transform: translateX(-50%) rotate(45deg);
      background: linear-gradient(135deg, rgba(58,47,160,.85) 0%, rgba(34,26,102,.85) 100%);
      border-left: 1px solid rgba(212,175,55,.45);
      border-top:  1px solid rgba(212,175,55,.45);
      border-radius: 4px;
    }
    @keyframes smPromptIn {
      from { transform: translateY(8px) scale(.96); }
      to   { transform: translateY(0)   scale(1); }
    }
    @keyframes smPromptPulse {
      0%, 100% {
        box-shadow:
          0 0 0 1px rgba(255,255,255,.04) inset,
          0 12px 40px rgba(10,5,40,.45),
          0 0 18px rgba(212,175,55,.18);
        transform: scale(1);
      }
      50% {
        box-shadow:
          0 0 0 1px rgba(255,255,255,.06) inset,
          0 14px 50px rgba(10,5,40,.55),
          0 0 38px rgba(212,175,55,.45);
        transform: scale(1.025);
      }
    }

    /* ── Bigger, gold-bordered, pulsing shuffle CTA ── */
    .shuffle-prompt-text {
      border: 2px solid rgba(212,175,55,.65) !important;
      padding: 16px 36px !important;
      font-size: 14px !important;
      letter-spacing: .22em !important;
      animation: shuffleGlow 2.2s ease-in-out infinite;
    }
    .shuffle-prompt:hover .shuffle-prompt-text {
      border-color: rgba(212,175,55,.95) !important;
      animation: none;
      box-shadow:
        0 10px 36px rgba(59,31,110,.7),
        inset 0 1px rgba(255,255,255,.25),
        0 0 0 6px rgba(212,175,55,.22),
        0 0 32px rgba(212,175,55,.5) !important;
      transform: translateY(-2px) scale(1.02);
    }
    @keyframes shuffleGlow {
      0%, 100% {
        box-shadow:
          0 6px 24px rgba(59,31,110,.5),
          inset 0 1px rgba(255,255,255,.18),
          0 0 0 4px rgba(212,175,55,.12),
          0 0 14px rgba(212,175,55,.18);
        transform: scale(1);
        border-color: rgba(212,175,55,.6);
      }
      50% {
        box-shadow:
          0 10px 38px rgba(59,31,110,.7),
          inset 0 1px rgba(255,255,255,.28),
          0 0 0 7px rgba(212,175,55,.22),
          0 0 36px rgba(212,175,55,.55);
        transform: scale(1.035);
        border-color: rgba(212,175,55,.95);
      }
    }

    /* ── Smaller chosen-card slots ── */
    .cs { width: 128px; height: 212px; margin: 0 10px; }

    /* ── Deck arc: more headroom so rotated edge cards aren't visually clipped ── */
    .deck-wrap { padding-top: 28px; padding-left: 24px; padding-right: 24px; }
    .deck-arc  { overflow: visible; }

    /* Medium desktop / laptop — fan is widest here, scale slightly */
    @media (min-width: 641px) and (max-width: 1100px) {
      .deck-arc { transform: scale(.92); transform-origin: center bottom; }
    }

    /* ── Mobile optimization (≤640px) ── */
    @media (max-width: 640px) {
      .cs { width: 96px; height: 159px; margin: 0 6px; }
      .cs-num { font-size: 22px; }
      .cs-label-text { font-size: 10px; letter-spacing: .18em; }

      #smPrompt.is-visible {
        padding: 10px 14px;
        max-width: calc(100vw - 40px);
        font-size: .92rem;
        line-height: 1.4;
        border-radius: 14px;
      }
      #smPrompt.is-visible::before { width: 11px; height: 11px; top: -6px; }

      .site-header { padding-left: 16px; padding-right: 16px; }
      .header-eyebrow { letter-spacing: .25em; font-size: 11px; padding: 6px 14px; }
      .header-sub { font-size: 1rem; line-height: 1.5; }

      .shuffle-prompt { max-width: calc(100vw - 32px); }
      .shuffle-prompt-text {
        font-size: 12px !important;
        letter-spacing: .16em !important;
        padding: 13px 22px !important;
      }

      .deck-wrap, .deck-arc { max-width: 100vw; }
      .deck-arc { transform: scale(.82); transform-origin: center top; margin: 0 auto; }
      .stage { padding-left: 8px; padding-right: 8px; }

      .site-footer-links a { display: inline-block; padding: 4px 2px; }
    }

    /* ── Extra-small phones (≤380px) ── */
    @media (max-width: 380px) {
      .cs { width: 88px; height: 146px; margin: 0 4px; }
      .cs-num { font-size: 20px; }
      #smPrompt.is-visible { font-size: .88rem; padding: 9px 12px; }
      .shuffle-prompt-text {
        font-size: 16px !important;
        letter-spacing: .12em !important;
        padding: 12px 18px !important;
      }
      .deck-arc { transform: scale(.74); }
    }

    /* ── Reduced-motion users ── */
    @media (prefers-reduced-motion: reduce) {
      #smPrompt.is-visible { animation: smPromptIn .55s ease forwards; }
      .shuffle-prompt-text { animation: none; }
    }
  </style>

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
    <p class="header-sub">Pick 3 cards. Your free reading begins to reveal the shape of the one belief<br>quietly running your love, your life, and your wealth at once.</p>
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
      <div class="header-divider picker-divider" id="pickerDivider"><span
          id="cardsRemainingText">Choose your cards below · 3 more</span></div>

      <!-- Deck -->
      <p class="deck-instruction" id="deckInstruction">Press the button below to reveal your cards</p>
      <div class="deck-wrap">
        <div class="deck-arc" id="deckArc">
          <div class="shuffle-prompt" id="shufflePrompt">
            <div class="shuffle-prompt-text">Take A Deep Breath &amp;<br>Shuffle Your Cards</div>
          </div>
        </div>
        <div class="prompt-wrap">
          <div class="sm-prompt" id="smPrompt">
            While you shuffle, the deck is searching for the one belief showing up in your love, your money, and your purpose at the same time.<br>
            Hold what feels unresolved. <em>Choose your first card.</em>
          </div>
        </div>
      </div>
    </div>

  </section>

  <!-- ── SUCCESS ─────────────────────────────── -->
  <section class="success-section" id="successSection">
    <div class="success-icon">✦</div>
    <h2>Your 3 Cards Are On Their Way</h2>
    <p>Check your inbox in the next 2 minutes for your 3-card preview. Read it slowly.<br>It's the doorway in. Your full Soul Mirror Reading is what walks you through it.</p>
  </section>

  <footer class="site-footer wavy">
    <p class="site-footer-links">
      <a href="/privacy-policy">Privacy Policy</a> &nbsp;·&nbsp;
      <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;·&nbsp;
      <a href="mailto:support@soulmirrorreading.com">Contact Us</a> &nbsp;·&nbsp;
      <a href="/refund-return-policy">Refund &amp; Return Policy</a>
    </p>
    <p>Copyright © 2026 Soul Mirror Reading. All Right Reserved.</p>
  </footer>

  <script defer src="<?= htmlspecialchars($assetRoot, ENT_QUOTES) ?>assets/home.min.js?v=<?= htmlspecialchars((string) $jsVer, ENT_QUOTES) ?>"></script>

  <!-- ─────────────────────────────────────────
       Reveal #smPrompt only after first shuffle click
       Added 2026-04-29
       ───────────────────────────────────────── -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var s = document.getElementById('shufflePrompt');
      var p = document.getElementById('smPrompt');
      if (s && p) {
        s.addEventListener('click', function () {
          p.classList.add('is-visible');
        }, { once: true });
      }
    });
  </script>

  <?php if (in_array(($funnelBase ?? ''), ['wealth-v2/', 'love/', 'love-v2/'], true)): ?>
  <!-- wealth-v2 + love + love-v2: hide the page header (eyebrow + headline + subhead) once the visitor picks their first card -->
  <script>
    (function () {
      var rt = document.getElementById('cardsRemainingText');
      var hdr = document.querySelector('.site-header');
      if (!rt || !hdr) return;
      var obs = new MutationObserver(function () {
        var m = (rt.textContent || '').match(/(\d+)\s+more/);
        if (m && parseInt(m[1], 10) < 3) {        // at least one card chosen
          hdr.style.display = 'none';
          obs.disconnect();
        }
      });
      obs.observe(rt, { childList: true, characterData: true, subtree: true });
    })();
  </script>
  <?php endif; ?>
</body>

</html>
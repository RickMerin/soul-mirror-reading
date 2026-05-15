<?php
declare(strict_types=1);
$cssPath = __DIR__ . '/../assets/sales-v2-bundle.min.css';
$cssVer = is_file($cssPath) ? filemtime($cssPath) : time();
$jsPath = __DIR__ . '/../assets/sales-v2.min.js';
$jsVer = is_file($jsPath) ? filemtime($jsPath) : time();
$checkoutUrl = 'https://rebornf.pay.clickbank.net/?cbitems=smr-1&template=order-3&cbfid=63301&exitoffer=exit-1';
?>
<!DOCTYPE html>
<html lang="en" data-funnel-base="v2/">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <meta name="description"
    content="See the one core belief—your Mirror Block—behind your Love, Life, and Wealth cards. Deep card work, clearing practice, and 90-day prompts delivered with your Soul Mirror Reading.">
  <title>Your Soul Mirror Reading — What the Cards Are Really Saying</title>
  <link rel="icon" type="image/svg+xml" href="../favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Crimson+Pro:wght@300;400;500;600&display=swap" rel="stylesheet">
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
  <div class="dream-bg" aria-hidden="true">
    <div class="dream-veil"></div>
    <div class="milky-way"></div>
    <div class="dream-orb one"></div>
    <div class="dream-orb two"></div>
    <div class="dream-orb three"></div>
    <div class="dream-shooting" id="dreamShooting"></div>
    <div class="dream-sparkles" id="dreamSparkles"></div>
  </div>

  <main id="main-content">
<!-- TOP NOTICE BAR -->
<div class="topnotice">
  <strong><span class="firstname">Friend</span>, Your Soul Tarot Reading Is On Its Way:</strong><span class="notice-dot">·</span>There Is a 4th Card You Did Not See.<span class="notice-dot">·</span><strong>Read This Before It Lands.</strong>
</div>

<!-- ═══════════════════════════════════════════════
     HERO + VSL
     ═══════════════════════════════════════════════ -->
<section class="vsl-section">
  <div class="wrap">
    <h1 class="vsl-headline"><span class="firstname">Friend</span>, One Card Fell Out Before<br><em>Your Reading Even Began</em></h1>
    <p class="vsl-sub">Face-up. Uninvited. A direct message from the universe that refuses to be ignored. Yours revealed the one belief running silently behind all three of your mirrors. <strong>Your Mirror Block.</strong></p>
    <p class="vsl-sub" style="margin-top:-8px;"><em>The message came through. Watch now.<br>This is the part most people never get told.</em></p>

    <div class="video-frame">
      <vturb-smartplayer id="vid-6a03e48213e119642182af7b"
        style="display: block; margin: 0 auto; width: 100%;"
        data-autoplay="false" autoplay="false"></vturb-smartplayer>
      <script type="text/javascript">
        var s = document.createElement("script");
        s.src = "https://scripts.converteai.net/6fa5f75c-723e-4301-a459-76c14edde081/players/6a03e48213e119642182af7b/v4/player.js";
        s.async = !0;
        document.head.appendChild(s);
      </script>
    </div>

  </div>
</section>

<!-- ═══════════════════════════════════════════════
     CTA #1 — RIGHT AFTER VSL (NEW)
     ═══════════════════════════════════════════════ -->
<section style="padding: 20px 24px 60px; position:relative;">
  <div class="cta-block">
    <div class="small-headline">Ready to See Your <em>Mirror Block?</em></div>
    <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">See My Mirror Block</a>
    <div class="cta-trust-row">
      <span>🔒 Secure Checkout</span>
      <span>⚡ Instant Access</span>
      <span>🌙 90-Day Guarantee</span>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     3-CARD BREAKDOWN (existing, now personalized + headline updated)
     ═══════════════════════════════════════════════ -->
<section class="section">
  <div class="wrap">
    <h2><span class="firstname">Friend</span>, These Are the Cards<br><em>You Chose Today</em></h2>
    <p style="text-align:center; max-width:560px; margin:0 auto 24px;">Three cards. Three mirrors. One hidden belief running all of them. This is what your reading has been pointing to.</p>

    <div class="three-cards">
      <div class="card-mirror" data-card-slot="love">
        <div class="mirror-pair">
          <img src="../cards/mirror-love.png" alt="Love Mirror" class="mirror-img">
          <div class="pair-plus">+</div>
          <div class="card-wireframe" data-card-image="love">
            <div class="wf-content">
              <div class="wf-icon">✦</div>
              <div class="wf-label">Your Card</div>
            </div>
          </div>
        </div>
        <h4>Mirror One · Love</h4>
        <div class="card-name-label" data-card-name="love"></div>
        <p>Where you pull back right before connection becomes real. The pattern that keeps love feeling just slightly out of reach.</p>
      </div>
      <div class="card-mirror" data-card-slot="life">
        <div class="mirror-pair">
          <img src="../cards/mirror-life.png" alt="Life Mirror" class="mirror-img">
          <div class="pair-plus">+</div>
          <div class="card-wireframe" data-card-image="life">
            <div class="wf-content">
              <div class="wf-icon">✦</div>
              <div class="wf-label">Your Card</div>
            </div>
          </div>
        </div>
        <h4>Mirror Two · Life</h4>
        <div class="card-name-label" data-card-name="life"></div>
        <p>Where your energy leaks and your choices keep looping. The place you feel most stuck, showing you the exact map you have been following.</p>
      </div>
      <div class="card-mirror" data-card-slot="wealth">
        <div class="mirror-pair">
          <img src="../cards/mirror-wealth.png" alt="Wealth Mirror" class="mirror-img">
          <div class="pair-plus">+</div>
          <div class="card-wireframe" data-card-image="wealth">
            <div class="wf-content">
              <div class="wf-icon">✦</div>
              <div class="wf-label">Your Card</div>
            </div>
          </div>
        </div>
        <h4>Mirror Three · Wealth</h4>
        <div class="card-name-label" data-card-name="wealth"></div>
        <p>What you believe you are allowed to have. The inherited story about deserving that has been setting your ceiling without your knowledge.</p>
      </div>
      <div class="card-mirror featured">
        <img src="../cards/mirror-block.png" alt="Mirror Block" class="mirror-img" style="max-width:140px;">
        <h4>The Hidden Layer · Mirror Block</h4>
        <p>The one belief running all three. Until it is named clearly, every reading you ever get will point to the same wall.</p>
      </div>
    </div>

    <p style="text-align:center; margin-top:24px; font-size:18px;"><strong>Your Soul Mirror Reading names it exactly. Not a general theme. Yours.</strong></p>

    <!-- CTA after 3-card breakdown -->
    <div style="text-align:center; margin-top:40px;">
      <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">See My Mirror Block</a>
      <div class="cta-trust-row" style="margin-top:16px;">
        <span>🔒 Secure Checkout</span>
        <span>⚡ Instant Access</span>
        <span>🌙 90-Day Guarantee</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     DIAGNOSTIC PROOF (NEW v2)
     "The Reading Is a Diagnostic. Not a Guess."
     Shows the 4 possible outcomes + the 22,100 combinations math
     ═══════════════════════════════════════════════ -->
<section class="section" style="position:relative;">
  <div class="wrap">
    <h2>This Is a <em>Diagnostic.</em> Not a Guess.</h2>

    <p style="text-align:center; max-width:600px; margin:0 auto 36px;">Your three cards have been pointing at one specific belief, running underneath your love, your money, and your purpose at the same time.</p>

    <!-- User's 3 cards as the "input" -->
    <div style="max-width:480px; margin:28px auto 20px;">
      <div style="text-align:center; font-family:'Cinzel',sans-serif; font-size:11px; letter-spacing:0.25em; color:var(--gold); text-transform:uppercase; margin-bottom:16px;">Your Combination</div>
      <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:14px;">
        <div style="text-align:center;">
          <div class="card-wireframe card-wireframe-sm" data-card-image="love">
            <div class="wf-content">
              <div class="wf-icon">✦</div>
              <div class="wf-label">Love</div>
            </div>
          </div>
          <div class="card-name-label" data-card-name="love" style="font-family:'Cormorant Garamond',serif; font-style:italic; font-size:14px; color:var(--gold-light); margin-top:8px;"></div>
        </div>
        <div style="text-align:center;">
          <div class="card-wireframe card-wireframe-sm" data-card-image="life">
            <div class="wf-content">
              <div class="wf-icon">✦</div>
              <div class="wf-label">Life</div>
            </div>
          </div>
          <div class="card-name-label" data-card-name="life" style="font-family:'Cormorant Garamond',serif; font-style:italic; font-size:14px; color:var(--gold-light); margin-top:8px;"></div>
        </div>
        <div style="text-align:center;">
          <div class="card-wireframe card-wireframe-sm" data-card-image="wealth">
            <div class="wf-content">
              <div class="wf-icon">✦</div>
              <div class="wf-label">Wealth</div>
            </div>
          </div>
          <div class="card-name-label" data-card-name="wealth" style="font-family:'Cormorant Garamond',serif; font-style:italic; font-size:14px; color:var(--gold-light); margin-top:8px;"></div>
        </div>
      </div>
    </div>

    <!-- Down arrow / decoding indicator -->
    <div style="text-align:center; margin:24px auto 8px;">
      <div style="font-family:'Cinzel',sans-serif; font-size:11px; letter-spacing:0.25em; color:var(--gold); text-transform:uppercase; margin-bottom:8px;">Decodes To</div>
      <div style="color:var(--gold); font-size:24px; line-height:1;">↓</div>
    </div>

    <!-- The decoded block (blurred until hover/tap) -->
    <div class="block-reveal-wrapper" style="max-width:560px; margin:16px auto 0; position:relative; cursor:pointer;">
      <div class="block-reveal-content" style="background: linear-gradient(180deg, rgba(45,27,105,0.6), rgba(30,13,64,0.9)); border: 1px solid var(--gold); border-radius: 14px; padding: 28px 26px; box-shadow: 0 12px 32px rgba(0,0,0,0.3); text-align: center;">
        <div style="font-family:'Cinzel',sans-serif; font-size:10px; letter-spacing:0.22em; color:var(--gold); text-transform:uppercase; margin-bottom:10px;">Your Mirror Block</div>
        <h4 data-mirror-block-name style="font-family:'Cormorant Garamond',serif; font-size:26px; color:#fff; font-weight:600; margin-bottom:14px; line-height:1.25;">The Not Yet Ready Block</h4>
        <p data-mirror-block-summary style="font-size:16px; color:rgba(255,255,255,0.85); line-height:1.7; margin:0; font-style:italic;">Permanent preparation. Success is always coming, never arriving.</p>
      </div>
      <!-- Mirror crack SVG overlay -->
      <svg class="mirror-crack" viewBox="0 0 400 200" preserveAspectRatio="none" aria-hidden="true">
        <g stroke="rgba(255,240,200,0.95)" stroke-width="2.2" fill="none" stroke-linecap="round">
          <!-- Main radial cracks from center -->
          <line x1="200" y1="100" x2="150" y2="20" />
          <line x1="200" y1="100" x2="220" y2="0" />
          <line x1="200" y1="100" x2="260" y2="15" />
          <line x1="200" y1="100" x2="320" y2="55" />
          <line x1="200" y1="100" x2="370" y2="85" />
          <line x1="200" y1="100" x2="395" y2="135" />
          <line x1="200" y1="100" x2="345" y2="180" />
          <line x1="200" y1="100" x2="270" y2="200" />
          <line x1="200" y1="100" x2="170" y2="200" />
          <line x1="200" y1="100" x2="85" y2="190" />
          <line x1="200" y1="100" x2="20" y2="155" />
          <line x1="200" y1="100" x2="5" y2="105" />
          <line x1="200" y1="100" x2="35" y2="50" />
          <line x1="200" y1="100" x2="100" y2="15" />
          <!-- Branch cracks -->
          <line x1="150" y1="20" x2="125" y2="0" />
          <line x1="150" y1="20" x2="180" y2="50" />
          <line x1="260" y1="15" x2="285" y2="0" />
          <line x1="260" y1="15" x2="240" y2="35" />
          <line x1="320" y1="55" x2="365" y2="45" />
          <line x1="320" y1="55" x2="300" y2="80" />
          <line x1="370" y1="85" x2="395" y2="70" />
          <line x1="345" y1="180" x2="360" y2="200" />
          <line x1="345" y1="180" x2="315" y2="170" />
          <line x1="85" y1="190" x2="65" y2="200" />
          <line x1="85" y1="190" x2="115" y2="170" />
          <line x1="20" y1="155" x2="0" y2="170" />
          <line x1="35" y1="50" x2="15" y2="35" />
          <line x1="35" y1="50" x2="60" y2="75" />
          <line x1="100" y1="15" x2="80" y2="0" />
          <!-- Hairline secondary cracks for density -->
          <line x1="180" y1="50" x2="200" y2="65" stroke-width="1.2" stroke="rgba(232,201,122,0.7)" />
          <line x1="240" y1="35" x2="220" y2="50" stroke-width="1.2" stroke="rgba(232,201,122,0.7)" />
          <line x1="300" y1="80" x2="280" y2="95" stroke-width="1.2" stroke="rgba(232,201,122,0.7)" />
          <line x1="115" y1="170" x2="135" y2="155" stroke-width="1.2" stroke="rgba(232,201,122,0.7)" />
          <line x1="315" y1="170" x2="295" y2="155" stroke-width="1.2" stroke="rgba(232,201,122,0.7)" />
        </g>
      </svg>
      <!-- Impact flash overlay -->
      <div class="mirror-flash" aria-hidden="true"></div>
      <div class="reveal-hint" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background: linear-gradient(135deg, rgba(212,175,55,0.25), rgba(212,175,55,0.1)); color: var(--gold-light); font-family: 'Cinzel', sans-serif; font-size: 11px; letter-spacing: 0.22em; padding: 12px 22px; border-radius: 30px; border: 1px solid rgba(212,175,55,0.55); text-transform: uppercase; backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); transition: opacity 0.4s ease; white-space: nowrap; z-index: 4; pointer-events: none; box-shadow: 0 6px 20px rgba(212,175,55,0.2);">
        ✦ Tap to Break the Mirror
      </div>
    </div>

    <p style="text-align:center; max-width:600px; margin:32px auto 0; font-style:italic; color:rgba(255,255,255,0.75); font-size:15px;">Your reading walks you through what this pattern has been doing in your love, your money, and your purpose, and gives you the practice to begin clearing it.</p>

    <!-- CTA after diagnostic proof -->
    <div style="text-align:center; margin-top:40px;">
      <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">See My Mirror Block</a>
      <div class="cta-trust-row" style="margin-top:16px;">
        <span>🔒 Secure Checkout</span>
        <span>⚡ Instant Access</span>
        <span>🌙 90-Day Guarantee</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     REBECCA TESTIMONIAL (PLANNED ABOVE INSIDE)
     ═══════════════════════════════════════════════ -->
<section style="padding: 0 0 20px;">
  <div class="wrap">
    <div class="testi-card">
      <div class="testi-avatar-row">
        <picture>
          <source type="image/webp" srcset="../frontend/images/sales/rebecca-hartley.webp">
          <img class="testi-avatar" src="../frontend/images/sales/rebecca-hartley.png" alt="Rebecca Hartley" decoding="async" loading="lazy">
        </picture>
        <div>
          <div class="testi-name">Rebecca Hartley</div>
          <div class="testi-meta">47 · Graphic designer</div>
        </div>
      </div>
      <div class="testi-stars">★ ★ ★ ★ ★</div>
      <p class="testi-body">"I almost closed the tab. After ten years of courses, therapy, and readings that all said the same thing in slightly different words, I expected this to be more of the same. It was not. The Mirror Block Luna named in mine was something I had been circling in therapy for six years without ever finding the right words for. I read it three times in two days. Two weeks later I had a conversation with my mother that I had been avoiding for nineteen years. I am not saying the reading caused that. I am saying it let me finally see the wall I had been pretending was not there."</p>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     INSIDE YOUR SOUL MIRROR READING
     ═══════════════════════════════════════════════ -->
<section class="section">
  <div class="wrap">
    <div class="vip-box">
      <h2>Inside Your <em>Soul Mirror Reading</em></h2>
      <img src="../frontend/images/sales/soul-mirror-reading.png" alt="Soul Mirror Reading" class="vip-mockup">
      <div class="vip-valued">Valued at <span class="amount">$197</span></div>
      <ul class="vip-list">
        <li><span><strong>Your Mirror Block Identified.</strong> The one core belief running Love, Life, and Wealth at the same time.</span></li>
        <li><span><strong>Love Mirror Deep-Dive.</strong> What your Love card means in context of all three cards.</span></li>
        <li><span><strong>Life Mirror Deep-Dive.</strong> Where your energy is leaking and what it would take to shift it.</span></li>
        <li><span><strong>Wealth Mirror Deep-Dive.</strong> The inherited story about deserving that has been setting your ceiling.</span></li>
        <li><span><strong>Mirror Block Clearing Practice.</strong> Seven questions, ten minutes, designed to begin the release.</span></li>
        <li><span><strong>Reversed Card Companion.</strong> Nuanced interpretation if any of your cards appeared reversed.</span></li>
        <li><span><strong>90-Day Mirror Check-In Prompts.</strong> Twelve weekly questions to keep the clarity working.</span></li>
      </ul>

      <!-- CTA inside the VIP box -->
      <div style="text-align:center; margin-top:32px;">
        <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">See My Mirror Block</a>
        <div class="cta-trust-row" style="margin-top:14px;">
          <span>🔒 Secure Checkout</span>
          <span>⚡ Instant Access</span>
          <span>🌙 90-Day Guarantee</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     BONUSES (COMPRESSED GRID — NEW)
     ═══════════════════════════════════════════════ -->
<section class="section" style="position:relative;">
  <div class="wrap">
    <h2>4 Bonuses Included <em>Free</em></h2>
    <p style="text-align:center; max-width:520px; margin:0 auto 8px;">Available only on this page, when you claim your Soul Mirror Reading now.</p>

    <div class="bonus-grid">
      <div class="bonus-card-compact">
        <span class="free-badge">FREE</span>
        <img src="../frontend/images/sales/mirror-block-companion-guide.png" alt="Companion Guide">
        <div class="bonus-num">Bonus 1</div>
        <h4>Mirror Block Companion Guide</h4>
        <div class="value">Valued at $67</div>
      </div>
      <div class="bonus-card-compact">
        <span class="free-badge">FREE</span>
        <img src="../frontend/images/sales/21-days-shift-tracker.png" alt="Shift Tracker">
        <div class="bonus-num">Bonus 2</div>
        <h4>21-Day Shift Tracker</h4>
        <div class="value">Valued at $47</div>
      </div>
      <div class="bonus-card-compact">
        <span class="free-badge">FREE</span>
        <img src="../frontend/images/sales/root-cause-reading-guide.png" alt="Root Cause Guide">
        <div class="bonus-num">Bonus 3</div>
        <h4>Root Cause Reading Guide</h4>
        <div class="value">Valued at $47</div>
      </div>
      <div class="bonus-card-compact">
        <span class="free-badge">FREE</span>
        <img src="../frontend/images/sales/mirror-clarity-meditation.png" alt="Clarity Meditation">
        <div class="bonus-num">Bonus 4</div>
        <h4>Mirror Clarity Meditation</h4>
        <div class="value">Valued at $37</div>
      </div>
    </div>

    <div class="bonus-total">
      <div class="label">Total Bonus Value</div>
      <div class="amount">$198 Yours Free Today</div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     CONSOLIDATED SCARCITY + COUNTDOWN + PRICE ANCHOR (ALS-style)
     Replaces: Daily Cap + Objection Killer + standalone Countdown
     ═══════════════════════════════════════════════ -->
<section style="padding: 60px 24px 40px; text-align:center; position:relative;">
  <div class="wrap">
    <h2 style="margin-bottom:18px;"><span class="firstname">Friend</span>, Your Soul Mirror Reading<br>Is Ready.<br><em>But We Cap Daily Readings.</em></h2>

    <p style="max-width:560px; margin:0 auto 14px; color:rgba(255,255,255,0.85); line-height:1.7;">Luna writes every reading by hand from your specific 3-card combination. To maintain quality, only a limited number of readings are completed each day.</p>

    <p style="max-width:560px; margin:0 auto 32px; color:rgba(255,255,255,0.7); font-style:italic;">After today's batch closes, the next available slot is tomorrow.</p>

    <!-- Countdown box -->
    <div style="display:inline-block; background: linear-gradient(180deg, rgba(45,27,105,0.7), rgba(30,13,64,0.9)); border: 1px solid rgba(212,175,55,0.4); border-radius: 16px; padding: 20px 28px; margin: 0 auto 36px; box-shadow: 0 12px 32px rgba(0,0,0,0.4);">
      <div style="font-family:'Cinzel',sans-serif; font-size:11px; letter-spacing:0.25em; color:rgba(232,201,122,0.75); text-transform:uppercase; margin-bottom:14px;">Today's Batch Closes In:</div>
      <div style="display:flex; align-items:center; justify-content:center; gap:14px;">
        <div style="text-align:center;">
          <div id="countdownMinutes" style="font-family:'Cormorant Garamond',serif; font-size:48px; font-weight:600; color:var(--gold-light); line-height:1; min-width:80px; padding:8px 14px; background: rgba(255,255,255,0.04); border-radius: 10px;">25</div>
          <div style="font-family:'Cinzel',sans-serif; font-size:9px; letter-spacing:0.22em; color:rgba(232,201,122,0.6); margin-top:6px; text-transform:uppercase;">Minutes</div>
        </div>
        <div style="font-family:'Cormorant Garamond',serif; font-size:32px; color:var(--gold-light); margin: 0 4px; font-weight:300;">:</div>
        <div style="text-align:center;">
          <div id="countdownSeconds" style="font-family:'Cormorant Garamond',serif; font-size:48px; font-weight:600; color:var(--gold-light); line-height:1; min-width:80px; padding:8px 14px; background: rgba(255,255,255,0.04); border-radius: 10px;">00</div>
          <div style="font-family:'Cinzel',sans-serif; font-size:9px; letter-spacing:0.22em; color:rgba(232,201,122,0.6); margin-top:6px; text-transform:uppercase;">Seconds</div>
        </div>
      </div>
    </div>

    <p style="max-width:560px; margin:0 auto 24px; color:rgba(255,255,255,0.85); line-height:1.7;">Luna's private readings are <strong style="color:var(--gold-light); text-decoration:line-through; text-decoration-color: rgba(212,175,55,0.5);">$395</strong>. Your complete package, with all 4 bonus materials, is just <strong style="color:var(--gold-light); font-size:18px;">$37</strong>.</p>

    <div style="text-align:center; margin-top:24px;">
      <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">See My Mirror Block · $37 →</a>
      <div class="cta-trust-row" style="margin-top:16px;">
        <span>🌙 90-Day Money-Back Guarantee</span>
        <span>⚡ Delivered in 12 to 24 Hours</span>
        <span>🔒 Secure Checkout</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     PRICING BLOCK WITH INLINE GUARANTEE (NEW)
     ═══════════════════════════════════════════════ -->
<section style="padding: 0 24px 60px; position:relative;">
  <div class="pricing-block">
    <h2>Everything Below.</h2>

    <div style="margin: 24px 0 12px; text-align:center;">
      <div style="font-family:'Cinzel',sans-serif; font-size:13px; letter-spacing:0.2em; color:rgba(255,255,255,0.6); text-transform:uppercase; margin-bottom:4px;">
        Usual Price: <span style="text-decoration:line-through; text-decoration-color:var(--gold); color:rgba(255,255,255,0.5);">$395</span>
      </div>
      <div style="font-family:'Cinzel',sans-serif; font-size:13px; letter-spacing:0.2em; color:var(--gold); text-transform:uppercase; margin-bottom:14px;">
        Today Only:
      </div>
      <div style="line-height:1;">
        <span class="price-currency">$</span><span class="price-new">37</span>
      </div>
    </div>
    <p class="price-note">One-time payment · Delivered within 12 to 24 hours</p>

    <ul class="vip-list" style="max-width:440px;">
      <li><span>Your personalised Soul Mirror Reading. <em>$197 value</em></span></li>
      <li><span>Mirror Block identification + all 3 deep-dives</span></li>
      <li><span>Mirror Block Clearing Practice + 90-day prompts</span></li>
      <li><span><strong>Bonus 1.</strong> Companion Guide ($67)</span></li>
      <li><span><strong>Bonus 2.</strong> 21-Day Shift Tracker ($47)</span></li>
      <li><span><strong>Bonus 3.</strong> Root Cause Guide ($47)</span></li>
      <li><span><strong>Bonus 4.</strong> Clarity Meditation ($37)</span></li>
    </ul>

    <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">See My Mirror Block</a>

    <div class="pricing-inline-guarantee">
      <img src="../cards/guarantee-badge.png" alt="90-Day Guarantee">
      <div><strong>90-Day Money Back Guarantee.</strong><br>If it does not show you something real, refund. No questions.</div>
    </div>

    <div class="trust-badge-row">
      <span>🔒 Secure Checkout</span>
      <span>⚡ Instant Access</span>
      <span>★★★★★ 4,800+ Readings</span>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     TESTIMONIALS CLUSTER
     ═══════════════════════════════════════════════ -->
<section class="section">
  <div class="wrap">
    <h2>What 4,800 Readings <em>Have Looked Like</em></h2>

    <div class="testi-card">
      <div class="testi-avatar-row">
        <img class="testi-avatar" src="../frontend/images/frontend/testimonial-diane-r.png" alt="Diane R.">
        <div>
          <div class="testi-name">Diane R.</div>
          <div class="testi-meta">54 · Retired teacher</div>
        </div>
      </div>
      <div class="testi-stars">★ ★ ★ ★ ★</div>
      <p class="testi-body">"I have had tarot readings before and always felt something was missing. This report gave me the missing piece. I could actually see the one thread running through all three areas of my life. I cried reading it, in the best possible way."</p>
    </div>

    <div class="testi-card">
      <div class="testi-avatar-row">
        <img class="testi-avatar" src="../frontend/images/frontend/testimonial-james-h.png" alt="James H.">
        <div>
          <div class="testi-name">James H.</div>
          <div class="testi-meta">48 · Business owner</div>
        </div>
      </div>
      <div class="testi-stars">★ ★ ★ ★ ★</div>
      <p class="testi-body">"I was skeptical. I'm a practical person. I just wanted to see what the cards said. But the Mirror Block explanation stopped me cold. It described something I have never been able to name. Whatever this is, it works."</p>
    </div>

    <div class="testi-card">
      <div class="testi-avatar-row">
        <img class="testi-avatar" src="../frontend/images/frontend/testimonial-carolyn-m.png" alt="Carolyn M.">
        <div>
          <div class="testi-name">Carolyn M.</div>
          <div class="testi-meta">61 · Holistic practitioner</div>
        </div>
      </div>
      <div class="testi-stars">★ ★ ★ ★ ★</div>
      <p class="testi-body">"Three cards. One pattern. I have spent years in therapy trying to understand why the same things kept happening in love, at work, with money. This report showed me in 20 minutes. The clearing practice alone is worth ten times what I paid."</p>
    </div>

    <!-- CTA after testimonials cluster -->
    <div style="text-align:center; margin-top:40px;">
      <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">See My Mirror Block</a>
      <div class="cta-trust-row" style="margin-top:16px;">
        <span>🔒 Secure Checkout</span>
        <span>⚡ Instant Access</span>
        <span>🌙 90-Day Guarantee</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     TWO PATHS FROM HERE (NEW)
     ═══════════════════════════════════════════════ -->
<section class="section" style="position:relative;">
  <div class="wrap">
    <h2>Two Paths <em>From Here</em></h2>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:32px;">
      <!-- Path A -->
      <div style="background: linear-gradient(180deg, rgba(45,27,105,0.6), rgba(30,13,64,0.85)); border: 1px solid rgba(212,175,55,0.45); border-radius: 14px; padding: 28px 22px; backdrop-filter: blur(4px);">
        <div style="font-family:'Cinzel',sans-serif; font-size:11px; letter-spacing:0.22em; color:var(--gold); text-transform:uppercase; margin-bottom:14px;">Path A</div>
        <h3 style="font-family:'Cormorant Garamond',serif; font-size:22px; color:#fff; font-weight:600; line-height:1.3; margin-bottom:14px;">You <em style="color:var(--gold-light); font-style:italic;">See It Clearly</em></h3>
        <p style="font-size:15px; color:rgba(255,255,255,0.85); line-height:1.7; margin:0;">Within 24 hours, your Soul Mirror Reading lands in your inbox. You read it once on your couch. The next morning you read it again, slower. You see the single belief that has been running underneath every choice in love, money, and purpose. The work begins from there. Most people tell me it takes 21 days before they notice they have stopped doing the thing they have been doing for years.</p>
      </div>
      <!-- Path B -->
      <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.12); border-radius: 14px; padding: 28px 22px; backdrop-filter: blur(4px);">
        <div style="font-family:'Cinzel',sans-serif; font-size:11px; letter-spacing:0.22em; color:rgba(255,255,255,0.5); text-transform:uppercase; margin-bottom:14px;">Path B</div>
        <h3 style="font-family:'Cormorant Garamond',serif; font-size:22px; color:rgba(255,255,255,0.7); font-weight:600; line-height:1.3; margin-bottom:14px;">You <em style="color:rgba(255,255,255,0.55); font-style:italic;">Don't.</em></h3>
        <p style="font-size:15px; color:rgba(255,255,255,0.6); line-height:1.7; margin:0;">You close this page. You finish your tea. The pattern keeps running. Six months from now, you are back here. Or somewhere else, looking at the same wall in different paint. The cards you chose minutes ago will not carry the same precision a week from now. The window narrows quietly.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     FAQ (PROCESS FOLDED IN)
     ═══════════════════════════════════════════════ -->
<section class="faq-section">
  <div class="wrap">
    <h2>The <em>Honest</em> Answers</h2>

    <div class="faq" style="margin-top:32px;">
      <details>
        <summary>What is a Mirror Block exactly?</summary>
        <p class="faq-a">A Mirror Block is a core belief, usually formed early in life and often inherited from someone else, that shows up identically across your love life, your daily experience, and your relationship with money and abundance. It is not a flaw and it is not permanent. But it is specific, and once you can see it clearly, it loses most of its power.</p>
      </details>
      <details>
        <summary>How is this different from the reading I will receive in my email?</summary>
        <p class="faq-a">The email reading shows you what each card says. The Soul Mirror Reading shows you what your three cards mean together. The pattern underneath them, the belief connecting them, and what to do with that information.</p>
      </details>
      <details>
        <summary>What happens after I order?</summary>
        <p class="faq-a">Your reading takes 12 to 24 hours to complete, hand-written by Luna for your specific card combination. You will receive it as a PDF in your inbox. Use the Clearing Practice once. Seven questions, ten minutes. The shift begins there.</p>
      </details>
      <details>
        <summary>What if I am not satisfied?</summary>
        <p class="faq-a">You are covered by our 90-day guarantee. If the report does not give you something genuinely useful, email us and we will refund you completely. We would rather you keep the money than feel like the mirror did not show you anything real.</p>
      </details>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════
     FINAL CTA
     ═══════════════════════════════════════════════ -->
<section style="padding: 0 24px 60px;">
  <div class="final-cta">
    <h2><span class="firstname">Friend</span>, Your Cards Are Drawn.<br><em>Your Mirror Block Is Already Named.</em></h2>
    <p style="max-width:560px; margin:18px auto 24px; color:rgba(255,255,255,0.85);">The pattern is already there. The only question is whether you are ready to see it clearly.</p>

    <div style="margin:28px auto 12px;">
      <img src="../frontend/images/sales/bundle-product-image.png" alt="Soul Mirror Reading complete bundle" style="max-width:420px; width:100%; height:auto; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.4));">
    </div>

    <div style="margin:24px 0;">
      <div style="font-family:'Cinzel',sans-serif; font-size:11px; letter-spacing:0.22em; color:var(--gold); text-transform:uppercase; margin-bottom:6px;">Total Value $395 · Today</div>
      <div style="font-family:'Cormorant Garamond',serif; font-size:48px; color:var(--gold-light); font-weight:600;">$37</div>
    </div>

    <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">See My Mirror Block</a>
    <p style="margin-top:18px; font-size:13px; color:rgba(255,255,255,0.6);">Instant access · 90-day guarantee · Secure checkout</p>
  </div>
</section>

  </main>

  <footer class="site-footer js-reveal">
    <div class="footer-legal-copy">
      <p>ClickBank is the retailer of products on this site. CLICKBANK® is a registered trademark of Click Sales, Inc.,
        a Delaware corporation located at 1444 S. Entertainment Ave., Suite 410 Boise, ID 83709, USA and used by
        permission. ClickBank's role as retailer does not constitute an endorsement, approval or review of these
        products or any claim, statement or opinion used in promotion of these products.</p>
      <p>For Product Support, please contact the vendor: <a href="mailto:support@soulmirrorreading.com">HERE</a></p>
      <p>For Order Support, please contact ClickBank: <a href="https://www.clkbank.com/" target="_blank"
          rel="noopener">HERE</a> or 1-800-390-6035</p>
      <p class="footer-links">
        <a href="/privacy-policy">Privacy Policy</a> &nbsp;·&nbsp;
        <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;·&nbsp;
        <a href="mailto:support@soulmirrorreading.com">Contact Us</a> &nbsp;·&nbsp;
        <a href="/refund-return-policy">Refund &amp; Return Policy</a>
      </p>
      <p>Copyright © 2026 Soul Mirror Reading. All Right Reserved.</p>
    </div>
  </footer>

  <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
  <script defer src="../assets/sales-v2.min.js?v=<?= htmlspecialchars((string) $jsVer, ENT_QUOTES) ?>"></script>
</body>

</html>

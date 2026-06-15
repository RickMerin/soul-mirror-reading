<?php
/**
 * Soul Mirror Reading , Downsell 1 (REDESIGN, wealth-v2 design system)
 * Offer:    Soul Ritual Practice , $97 declined -> $67 one-time downsell
 * ClickBank: item srp-1-ds   accept cbur=a   decline cbur=d
 *
 * Deploy as: /downsell-1.php  (site root , root-relative asset paths assume root)
 *
 * Design system borrowed from /wealth-v2/sales.php:
 *   - assets/sales-v2-bundle.min.css  (dream-bg, .cta, .testi-card, .vip-box,
 *     .bonus-grid/.bonus-card-compact, .pricing-block, .final-cta, .faq-section,
 *     .topnotice, .site-footer, the gold/violet :root palette)
 *   - assets/sales-v2.min.js  (dream-bg sparkle + shooting-star animation,
 *     .firstname personalization from ?first_name=, GSAP .js-reveal fade-ins,
 *     CTA smooth-scroll, the 25-min rolling #countdownMinutes/#countdownSeconds timer)
 *
 * Personalization: .firstname spans auto-fill from the ?first_name= query param
 * passed through the ClickBank upsell flow; fall back word is "Friend".
 */
?>
<!DOCTYPE html>
<html lang="en" data-funnel-base="">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <meta name="description" content="One more thing from Luna. The complete Soul Ritual Practice , same three Rituals, the Workbook, and all three bonuses , at a one-time price you will not see again.">
  <meta name="robots" content="noindex, nofollow">
  <title>Wait, One More Thing From Luna</title>
  <link rel="icon" type="image/svg+xml" href="favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&amp;family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&amp;family=Crimson+Pro:wght@300;400;500;600&amp;display=swap" rel="stylesheet">
  <!-- Shared wealth-v2 design system (component classes, dream-bg, palette) -->
  <link rel="stylesheet" href="assets/sales-v2-bundle.min.css">

  <!-- Microsoft Clarity (same project tag as the rest of the funnel) -->
  <script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "wq82rtc2gf");
  </script>

  <!-- Page-level overrides, mirrors wealth-v2 #tsl-style:
       Inter for body copy, Cormorant for display, dimmed cosmic bg. -->
  <style id="ds-style">
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    body { background:#0b0718 !important; }
    .dream-bg { filter:brightness(0.5) saturate(1.05) !important; }

    /* Inter for everything readable; Cormorant for headlines (matches wealth-v2). */
    body,p,li,.vsl-sub,.testi-body,.testi-name,.faq-q,.faq-a,
    .vip-list,.vip-list li,.vip-list li span,.price-note,.cta,
    .cta-trust-row,.cta-trust-row span,.topnotice,.topnotice strong,
    .ds-lede,.ds-sig,.part-col__title,.part-col__status,.value-line__name {
      font-family:'Inter',system-ui,sans-serif !important;
    }
    h1,h2,h3,h4,h5,.vsl-headline,.vsl-headline em { font-family:'Cormorant Garamond',Georgia,serif !important; }

    /* ── Luna note / hero ── */
    .ds-eyebrow{
      text-align:center; letter-spacing:.32em; color:var(--gold);
      font-family:'Cinzel',sans-serif; font-size:11px; text-transform:uppercase;
      margin-bottom:16px; display:block;
    }
    .luna-hero{
      display:block; width:128px; height:128px; object-fit:cover;
      margin:0 auto 12px; border-radius:50%;
      border:2px solid rgba(212,175,55,.6); box-shadow:0 12px 36px rgba(0,0,0,.55);
      background:#160c34;
    }
    .luna-cap{
      text-align:center; font-family:'Cormorant Garamond',serif; font-style:italic;
      font-size:16px; color:#cdb98c; letter-spacing:.02em; margin:0 0 26px;
    }
    .ds-lede{
      max-width:560px; margin:0 auto; color:rgba(255,255,255,.86);
      font-size:18px; line-height:1.7; text-align:center;
    }
    .ds-lede strong{ color:var(--gold-light); font-weight:600; }

    /* ── "What you have / what you still need" two-part frame ── */
    .part-row{
      display:grid; grid-template-columns:1fr auto 1fr; gap:16px;
      align-items:stretch; max-width:600px; margin:32px auto 0;
    }
    .part-col{
      backdrop-filter:blur(4px);
      background:linear-gradient(180deg,rgba(45,27,105,.55),rgba(30,13,64,.85));
      border:1px solid rgba(212,175,55,.35); border-radius:14px;
      padding:22px 18px; text-align:center;
    }
    .part-col--done{ border-color:rgba(34,184,74,.45); background:linear-gradient(180deg,rgba(26,138,58,.18),rgba(30,13,64,.85)); }
    /* Part Two glow-up */
    .part-col--next{
      border-color:rgba(212,175,55,.85);
      background:linear-gradient(180deg,rgba(74,52,140,.5),rgba(38,18,78,.92));
      position:relative;
      animation:partGlow 2.6s ease-in-out infinite;
    }
    @keyframes partGlow{
      0%,100%{ box-shadow:0 0 16px rgba(212,175,55,.25), inset 0 0 14px rgba(212,175,55,.05); }
      50%{ box-shadow:0 0 32px rgba(212,175,55,.55), inset 0 0 20px rgba(212,175,55,.12); }
    }
    .part-col--next .part-col__num{ color:var(--gold-light); }
    .part-col__badge{
      position:absolute; top:-10px; left:50%; transform:translateX(-50%);
      background:linear-gradient(135deg,#e8c97a 0%,#b8941f 100%);
      color:#2a1252; font-family:'Cinzel',sans-serif; font-size:9px; font-weight:700;
      letter-spacing:.14em; text-transform:uppercase; white-space:nowrap;
      padding:4px 12px; border-radius:11px; box-shadow:0 3px 10px rgba(212,175,55,.45);
    }
    .part-col__price{ font-size:13px; color:rgba(255,255,255,.6); margin-top:2px; }
    .part-col__price strong{ font-family:'Cormorant Garamond',serif; color:var(--gold-light); font-size:21px; font-weight:600; letter-spacing:.01em; }
    .part-col__num{
      font-family:'Cinzel',sans-serif; font-size:10px; font-weight:600;
      letter-spacing:.22em; text-transform:uppercase; color:var(--gold);
      display:block; margin-bottom:8px;
    }
    .part-col--done .part-col__num{ color:#5fe08a; }
    .part-col__title{
      font-family:'Cormorant Garamond',serif !important; font-size:19px; font-weight:600;
      color:#fff; line-height:1.25; margin-bottom:8px;
    }
    .part-col__status{ font-size:13px; color:var(--text-muted); font-style:italic; }
    .part-col__status--done{ color:#5fe08a; font-style:normal; font-weight:500; }
    .part-arrow{ display:flex; align-items:center; justify-content:center; color:var(--gold); font-size:22px; }
    @media (width<=600px){
      .part-row{ grid-template-columns:1fr; gap:12px; }
      .part-arrow{ transform:rotate(90deg); }
    }

    /* ── Luna bridge note ── */
    .ds-note{ max-width:600px; margin:0 auto; }
    .ds-note p{ font-size:18px; line-height:1.75; color:rgba(255,255,255,.86); }
    .ds-note p strong{ color:var(--gold-light); }
    .ds-sig{
      margin-top:24px; padding-top:18px; border-top:1px solid rgba(212,175,55,.25);
      text-align:center; font-family:'Cormorant Garamond',serif !important; font-style:italic;
      font-size:16px; color:var(--text-muted);
    }
    .ds-sig strong{ color:var(--gold-light); font-style:normal; }

    /* ── Coupon device on the price block ── */
    .ds-coupon{
      display:inline-block; position:relative;
      background:linear-gradient(135deg,rgba(212,175,55,.16) 0%,rgba(212,175,55,.07) 100%);
      border:1.5px dashed rgba(232,201,122,.6); border-radius:10px;
      padding:14px 30px; margin:0 auto 24px; max-width:440px;
    }
    .ds-coupon::before,.ds-coupon::after{
      content:''; position:absolute; top:50%; width:16px; height:16px;
      border-radius:50%; background:var(--violet); transform:translateY(-50%);
    }
    .ds-coupon::before{ left:-9px; } .ds-coupon::after{ right:-9px; }
    .ds-coupon__label{
      font-family:'Cinzel',sans-serif; font-size:9px; letter-spacing:.3em;
      text-transform:uppercase; color:var(--gold); display:block; margin-bottom:6px;
    }
    .ds-coupon__amount{
      font-family:'Cormorant Garamond',serif; font-size:24px; font-weight:600;
      color:var(--gold-light); display:block; letter-spacing:.02em;
    }
    .ds-coupon__code{
      font-family:'Cinzel',sans-serif; font-size:10px; letter-spacing:.16em;
      color:rgba(255,255,255,.6); display:block; margin-top:6px;
    }
    .ds-coupon__code span{
      color:var(--gold-light); background:rgba(212,175,55,.12);
      border:1px solid rgba(232,201,122,.3); padding:2px 8px; border-radius:3px;
      margin-left:6px; letter-spacing:.14em;
    }

    /* Value stack lines inside the pricing block (strike-through anchors) */
    .value-stack{ text-align:left; max-width:460px; margin:0 auto 26px; }
    .value-line{
      display:flex; justify-content:space-between; align-items:baseline; gap:16px;
      padding:10px 0; border-bottom:1px solid rgba(212,175,55,.15);
    }
    .value-line:last-child{ border-bottom:none; }
    .value-line__name{ font-size:15px; color:var(--text); line-height:1.4; }
    .value-line__name strong{ color:var(--gold-light); }
    .value-line__price{
      font-family:'Cinzel',sans-serif; font-size:13px; color:var(--gold-light);
      flex-shrink:0; text-decoration:line-through; opacity:.7;
    }
    .value-line--total{ margin-top:6px; padding-top:14px; border-top:1px solid rgba(212,175,55,.4); border-bottom:none; }
    .value-line--total .value-line__name{ font-family:'Cormorant Garamond',serif !important; font-size:18px; font-weight:600; color:#fff; }
    .value-line--total .value-line__price{ font-size:15px; text-decoration:none; opacity:1; }

    /* Was -> Now price row inside pricing block */
    .ds-price-was{
      font-family:'Cormorant Garamond',serif; font-size:26px;
      color:rgba(255,255,255,.4); text-decoration:line-through;
      text-decoration-color:var(--gold); margin-right:14px;
    }

    /* Soft "no thanks" decline link (consistent with wealth-v2 muted styling) */
    .ds-decline{
      display:block; margin:18px auto 0; max-width:460px; text-align:center;
      background:transparent; border:none; cursor:pointer;
      font-family:'Inter',system-ui,sans-serif; font-size:14px; font-weight:300;
      color:rgba(255,255,255,.5); text-decoration:underline; text-underline-offset:3px;
    }
    .ds-decline:hover{ color:rgba(255,255,255,.78); }

    /* Reassurance row under the accept CTA */
    .ds-reassure{ margin-top:18px; font-size:14px; color:rgba(255,255,255,.55); line-height:1.6; }

    /* Countdown sublabel tweak */
    .countdown-bar{ margin:28px auto 0; }
  </style>
</head>

<body>

  <!-- ── Animated cosmic background (driven by sales-v2.min.js) ── -->
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

  <!-- ── TOP NOTICE BAR (downsell red) ── -->
  <div class="topnotice" style="background:#9b1c1c;border-bottom:1px solid #c0392b;">
    <strong>WAIT</strong><span class="notice-dot">&middot;</span><strong>I Just Took <span style="color:#E8C97A;">$30 Off</span> the Soul Ritual Practice</strong><span class="notice-dot">&middot;</span><strong>This Lower Price Won't Be Shown Again</strong>
  </div>

    <!-- ═══════════════════════════════════════════
         HERO , Luna's final note, acknowledge the decline
         ═══════════════════════════════════════════ -->
    <section class="vsl-section">
      <div class="wrap">
        <h1 class="vsl-headline"><span class="firstname">Friend</span>, I'm Not Letting You Leave<br><em>Without the Second Half.</em></h1>

        <p class="ds-lede">$97 wasn't right for you today, and that's okay. But the practice your reading named is what loosens the ceiling on your money, the figure your income keeps returning to no matter how hard you work. Same complete practice, <strong>$67, one time only.</strong></p>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════
         WHAT YOU HAVE / WHAT YOU STILL NEED
         ═══════════════════════════════════════════ -->
    <section class="section" style="padding-top:24px;">
      <div class="wrap">
        <span class="section-eyebrow">What You Already Have &nbsp;&middot;&nbsp; What You Still Need</span>
        <p style="text-align:center; max-width:560px; margin:0 auto;">You have <strong>Part One</strong>. This page is still holding <strong>Part Two</strong> for you, at a price it will not show again.</p>

        <div class="part-row">
          <div class="part-col part-col--done">
            <span class="part-col__num">Part One</span>
            <p class="part-col__title">The Soul Mirror Reading</p>
            <p class="part-col__status part-col__status--done">&#10003; You just claimed this</p>
          </div>
          <div class="part-arrow">&rarr;</div>
          <div class="part-col part-col--next">
            <span class="part-col__num">Part Two</span>
            <p class="part-col__title">The Soul Ritual Practice</p>
            <p class="part-col__price">Now <strong>$67</strong> &middot; one time only</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════
         BRIDGE , seeing is not the same as clearing
         ═══════════════════════════════════════════ -->
    <section class="section" style="padding-top:0;">
      <div class="wrap">
        <div class="ds-note">
          <p>Your reading shows you the ceiling on your money. That matters enormously, <span class="firstname">Friend</span>.</p>
          <p>But I have sat with thousands of people who could see their Wealth Block exactly, the precise ceiling on their money, and still watched it hold their income at the same figure for another decade. Not because they weren't trying. <strong>Because seeing it is not the same as clearing it.</strong></p>
          <p>The Soul Ritual Practice is the fast track that does the clearing, the accelerated ritual that goes straight to the layer where the ceiling was set, instead of the slow years of reading and journaling. The same three Rituals, the same Workbook, the same three bonuses I just showed you. Nothing has been stripped out. The only thing that changed is the price, and the fact that this page will not be offered to you again.</p>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════
         EARLY OFFER / CTA (relocated above the pain section)
         ═══════════════════════════════════════════ -->
    <section style="padding:8px 24px 16px;">
      <div class="pricing-block" id="early-offer">
        <span class="section-eyebrow" style="margin-bottom:14px;">One-Time Offer &middot; Disappears When You Leave</span>
        <h2>Complete Your Journey<br><em>for $67</em></h2>
        <p class="price-note" style="margin:6px auto 0; max-width:460px;">The fast-track ritual that clears the ceiling on your money.</p>

        <div class="ds-coupon" style="margin-top:18px;">
          <span class="ds-coupon__label">&#10022; &nbsp; One-Time Discount Applied &nbsp; &#10022;</span>
          <span class="ds-coupon__amount">&minus; $30.00 OFF</span>
          <span class="ds-coupon__code">Code <span>EXTRA30</span></span>
        </div>

        <div style="margin:6px 0 2px; text-align:center;">
          <div style="font-family:'Cinzel',sans-serif; font-size:12px; letter-spacing:.2em; color:var(--gold); text-transform:uppercase; margin-bottom:10px;">Total Value <span style="text-decoration:line-through; color:rgba(255,255,255,.5);">$449</span></div>
          <div style="font-family:'Cinzel',sans-serif; font-size:12px; letter-spacing:.2em; color:var(--gold); text-transform:uppercase; margin-bottom:8px;">Your Price, One Time Only</div>
          <div class="price-row">
            <span class="ds-price-was">$197</span>
            <span class="ds-price-was">$97</span>
            <span class="price-currency">$</span><span class="price-new">67</span>
          </div>
        </div>
        <p class="price-note">One payment &middot; Instant download &middot; No subscription</p>

        <a href="https://rebornf.pay.clickbank.net/?cbitems=srp-1-ds&amp;cbur=a" class="cta" style="margin-top:22px;">Yes. Upgrade My Order Now!</a>
        <div class="cta-trust-row" style="margin-top:16px;">
          <span>&#128274; Secure Checkout</span>
          <span>&#9889; Delivered Immediately</span>
          <span>&#127769; 90-Day Guarantee</span>
        </div>
        <a class="ds-decline" href="https://rebornf.pay.clickbank.net/?cbitems=srp-1-ds&amp;cbur=d">No thank you, take me to my reading only</a>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════
         PAIN , you already know this is you
         ═══════════════════════════════════════════ -->
    <section class="section">
      <div class="wrap">
        <h2><span class="firstname">Friend</span>, You Work Harder Every Year.<br><em>And Your Income Keeps Landing on the Same Number.</em></h2>

        <div class="vip-box" style="text-align:left;">
          <ul class="vip-list" style="max-width:560px;">
            <li><span>You know your number. The figure your income quietly returns to, year after year. You have worked, learned, and pushed past it, and still you land right back on it. <em>That ceiling is not bad luck. It is your Wealth Block, holding the line.</em></span></li>
            <li><span>Money comes close. A raise, a big client, a yes you can almost taste. Then something unseen pulls it back and the door closes again. You have felt that flinch the moment money gets real, and quietly braced for it to leave.</span></li>
            <li><span>You undercharge. You round the quote down before you say it out loud, you talk yourself out of asking for more, you call settling for less being realistic. <strong>You are not broken. You have been working on the wrong layer of your wealth.</strong></span></li>
          </ul>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════
         TESTIMONIALS , people who almost said no twice
         ═══════════════════════════════════════════ -->
    <section class="section" style="padding-top:0;">
      <div class="wrap">
        <span class="section-eyebrow">From People Who Almost Said No Twice</span>
        <h2>They Clicked Away Once.<br><em>This Page Brought Them Back.</em></h2>

        <div class="testi-card">
          <div class="testi-avatar-row">
            <img class="testi-avatar" src="frontend/images/downsell/testimonial-jennifer-l.png" alt="Jennifer L." decoding="async" loading="lazy">
            <div>
              <div class="testi-name">Jennifer L.</div>
              <div class="testi-meta">51 &middot; Denver, US</div>
            </div>
          </div>
          <div class="testi-stars">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
          <p class="testi-body">"I did Ritual One, the Root Witnessing, and finally saw the exact moment my Wealth Block was set. A client who had stalled on paying me for months sent the full $3,200 the week after, no chasing from me. What surprised me more was that it stayed. Normally a sum like that finds a reason to leave by the end of the month. This time it just sat there and was mine."</p>
        </div>

        <div class="testi-card">
          <div class="testi-avatar-row">
            <img class="testi-avatar" src="frontend/images/downsell/testimonial-marcus-t.png" alt="Marcus T." decoding="async" loading="lazy">
            <div>
              <div class="testi-name">Marcus T.</div>
              <div class="testi-meta">46 &middot; Austin, US</div>
            </div>
          </div>
          <div class="testi-stars">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
          <p class="testi-body">"Full disclosure, I clicked away on the first offer and almost did again here. What pulled me back was Ritual Two, the Pattern Interruption, which caught the exact thing I do the second a quote leaves my hands: I talk my own number down before anyone else can. My usual rate was $1,800. I sent the next proposal at $2,600, did not flinch, and they paid it. That was the Wealth Block letting go in real time, and I have not gone back down since."</p>
        </div>

        <div class="testi-card">
          <div class="testi-avatar-row">
            <img class="testi-avatar" src="frontend/images/downsell/testimonial-elena-m.png" alt="Elena M." decoding="async" loading="lazy">
            <div>
              <div class="testi-name">Elena M.</div>
              <div class="testi-meta">43 &middot; Barcelona, ES</div>
            </div>
          </div>
          <div class="testi-stars">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
          <p class="testi-body">"For years every good month quietly undid itself. I would earn well, then pull the next one back down to my usual figure without noticing. Ritual Three, the New Imprint, was where I wrote a higher ceiling into my body so more money felt safe to keep. Four months on I have held just over 4,000 euros that in the old pattern would have leaked away by now. Same income I have always had. It just stays now."</p>
        </div>

        <p style="text-align:center; max-width:560px; margin:22px auto 0; font-family:'Cormorant Garamond',serif; font-style:italic; font-size:14px; color:rgba(255,255,255,.45);">Individual results vary and are not typical. The Soul Ritual Practice is for insight and self-reflection. It is not financial advice.</p>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════
         WHAT YOU RECEIVE , VIP box + product image + bonuses
         ═══════════════════════════════════════════ -->
    <section class="section" style="padding-top:0;">
      <div class="wrap">
        <div class="vip-box">
          <h2>The Complete <em>Soul Ritual Practice</em></h2>
          <img src="frontend/images/ups-downs/upsell1-package.png" alt="The complete Soul Ritual Practice, the three Clearing Rituals, the Workbook, and three bonuses" class="vip-mockup" style="max-width:340px;">
          <div class="vip-valued">Total Value <span class="amount">$449</span></div>
          <ul class="vip-list">
            <li><span><strong>The Mirror Block Clearing Rituals.</strong> A 3-part protocol that interrupts the block where it actually lives.</span></li>
            <li><span><strong>The Mirror Block Workbook.</strong> 45 pages that walk you through the clearing, step by step.</span></li>
            <li><span><strong>Bonus 1. Soul Ritual Audio Companion.</strong> The full practice, guided in Luna's voice.</span></li>
            <li><span><strong>Bonus 2. The Wealth Alert Protocol.</strong> Catch the block the moment it tries to pull money back.</span></li>
            <li><span><strong>Bonus 3. The Love Harmony Audio.</strong> The same clearing, turned toward connection.</span></li>
          </ul>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════
         BONUS GRID , reinforce that nothing is stripped
         ═══════════════════════════════════════════ -->
    <section class="section" style="padding-top:0;">
      <div class="wrap">
        <h2>Every Bonus Still <em>Included</em></h2>
        <p style="text-align:center; max-width:520px; margin:0 auto 8px;">The $67 version is not a lighter version. You get the full stack, exactly as it was at $97.</p>

        <div class="bonus-grid" style="grid-template-columns:repeat(3,1fr);">
          <div class="bonus-card-compact">
            <span class="free-badge">INCLUDED</span>
            <div class="bonus-num">Bonus 1</div>
            <h4>Soul Ritual Audio Companion</h4>
            <div class="value">Valued at $67</div>
          </div>
          <div class="bonus-card-compact">
            <span class="free-badge">INCLUDED</span>
            <div class="bonus-num">Bonus 2</div>
            <h4>The Wealth Alert Protocol</h4>
            <div class="value">Valued at $47</div>
          </div>
          <div class="bonus-card-compact">
            <span class="free-badge">INCLUDED</span>
            <div class="bonus-num">Bonus 3</div>
            <h4>The Love Harmony Audio</h4>
            <div class="value">Valued at $37</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════
         PRICING BLOCK , the offer, coupon, was->now, accept + decline
         ═══════════════════════════════════════════ -->
    <section style="padding:0 24px 56px; position:relative;" id="offer">
      <div class="pricing-block">
        <span class="section-eyebrow" style="margin-bottom:18px;">One-Time Offer &middot; Disappears When You Leave</span>
        <h2>Complete Your Journey<br><em>for $67</em></h2>
        <p class="price-note" style="margin-bottom:24px;">Same complete practice. Same three bonuses. The only thing lower is the price.</p>

        <div class="value-stack">
          <div class="value-line">
            <span class="value-line__name">The Mirror Block Clearing Rituals, a 3-part protocol</span>
            <span class="value-line__price">$201</span>
          </div>
          <div class="value-line">
            <span class="value-line__name">The Mirror Block Workbook, 45 pages</span>
            <span class="value-line__price">$97</span>
          </div>
          <div class="value-line">
            <span class="value-line__name"><strong>Bonus 1.</strong> Soul Ritual Audio Companion</span>
            <span class="value-line__price">$67</span>
          </div>
          <div class="value-line">
            <span class="value-line__name"><strong>Bonus 2.</strong> The Wealth Alert Protocol</span>
            <span class="value-line__price">$47</span>
          </div>
          <div class="value-line">
            <span class="value-line__name"><strong>Bonus 3.</strong> The Love Harmony Audio</span>
            <span class="value-line__price">$37</span>
          </div>
          <div class="value-line value-line--total">
            <span class="value-line__name">Total Value</span>
            <span class="value-line__price">$449</span>
          </div>
        </div>

        <div class="ds-coupon">
          <span class="ds-coupon__label">&#10022; &nbsp; One-Time Discount Applied &nbsp; &#10022;</span>
          <span class="ds-coupon__amount">&minus; $30.00 OFF</span>
          <span class="ds-coupon__code">Code <span>EXTRA30</span></span>
        </div>

        <div style="margin:8px 0 6px; text-align:center;">
          <div style="font-family:'Cinzel',sans-serif; font-size:12px; letter-spacing:.2em; color:var(--gold); text-transform:uppercase; margin-bottom:10px;">Your Price, One Time Only</div>
          <div class="price-row">
            <span class="ds-price-was">$197</span>
            <span class="ds-price-was">$97</span>
            <span class="price-currency">$</span><span class="price-new">67</span>
          </div>
        </div>
        <p class="price-note">One payment &middot; Instant download &middot; No subscription</p>

        <!-- Rolling 25-min timer rendered by sales-v2.min.js (#countdownMinutes/#countdownSeconds) -->
        <div class="countdown-bar">
          <div class="countdown-label">This One-Time Price Closes In</div>
          <div class="countdown-time"><span id="countdownMinutes">25</span>:<span id="countdownSeconds">00</span></div>
          <div class="countdown-sublabel">When this page closes, the $67 offer closes with it.</div>
        </div>

        <a href="https://rebornf.pay.clickbank.net/?cbitems=srp-1-ds&amp;cbur=a" class="cta" style="margin-top:28px;">Yes. Upgrade My Order Now!</a>
        <div class="cta-trust-row" style="margin-top:16px;">
          <span>&#128274; Secure Checkout</span>
          <span>&#9889; Delivered Immediately</span>
          <span>&#127769; 90-Day Guarantee</span>
        </div>

        <p class="ds-reassure">Secure checkout via ClickBank &middot; Everything delivered immediately</p>

        <a class="ds-decline" href="https://rebornf.pay.clickbank.net/?cbitems=srp-1-ds&amp;cbur=d">No thank you, take me to my reading only</a>

        <div class="pricing-inline-guarantee">
          <img src="cards/guarantee-badge.png" alt="90-Day Guarantee">
          <div><strong>90-Day Money-Back Guarantee.</strong><br>Work through the practice. If you do not feel a genuine shift within 90 days, reply and I refund every penny of your $67. You keep everything. The risk is entirely mine.</div>
        </div>

        <div class="trust-badge-row">
          <span>&#128274; Secure Checkout</span>
          <span>&#9889; Delivered Immediately</span>
          <span>&#9733;&#9733;&#9733;&#9733;&#9733; 4,800+ Readings</span>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════
         FAQ , kills the two downsell objections
         ═══════════════════════════════════════════ -->
    <!-- FAQ section removed per request -->

    <!-- ═══════════════════════════════════════════
         FINAL CTA
         ═══════════════════════════════════════════ -->
    <!-- Final CTA section removed; offer relocated to the early CTA above the pain section -->


  </main>

  <!-- ═══ FOOTER (shared legal block, matches wealth-v2) ═══ -->
  <footer class="site-footer js-reveal">
    <div class="footer-legal-copy">
      <p>ClickBank is the retailer of products on this site. CLICKBANK&reg; is a registered trademark of Click Sales, Inc.,
        a Delaware corporation located at 1444 S. Entertainment Ave., Suite 410 Boise, ID 83709, USA and used by
        permission. ClickBank's role as retailer does not constitute an endorsement, approval or review of these
        products or any claim, statement or opinion used in promotion of these products.</p>
      <p>Individual results vary and are not typical. A Soul Mirror Reading and the Soul Ritual Practice are for insight
        and self-reflection. They are not financial, medical, or psychological advice and do not guarantee any specific outcome.</p>
      <p>For Product Support, please contact the vendor: <a href="mailto:support@soulmirrorreading.com">HERE</a></p>
      <p>For Order Support, please contact ClickBank: <a href="https://www.clkbank.com/" target="_blank" rel="noopener">HERE</a> or 1-800-390-6035</p>
      <p class="footer-links">
        <a href="/privacy-policy">Privacy Policy</a> &nbsp;&middot;&nbsp;
        <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;&middot;&nbsp;
        <a href="mailto:support@soulmirrorreading.com">Contact Us</a> &nbsp;&middot;&nbsp;
        <a href="/refund-return-policy">Refund &amp; Return Policy</a>
      </p>
      <p>Copyright &copy; 2026 Soul Mirror Reading. All Rights Reserved.</p>
    </div>
  </footer>

  <!-- GSAP (for .js-reveal) + shared funnel JS (dream-bg, .firstname, countdown, CTA scroll) -->
  <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
  <script defer src="assets/sales-v2.min.js"></script>

</body>

</html>

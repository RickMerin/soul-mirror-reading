<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/svg+xml" href="favicon.svg" />
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Downsell 2 - Wealth Clarity Ritual v2 (wealth-v2 cosmic system) -->
  <title>Wait, One More Thing From Luna</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500;1,600&family=Crimson+Pro:wght@300;400;500;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    :root{
      --violet:#2d1b69;--violet-mid:#3b1f6e;--violet-deep:#1e0d40;--violet-darkest:#0e0820;
      --gold:#d4af37;--gold-light:#e8c97a;--gold-bright:#f0d38a;
      --cream:#fefcf8;--cream-warm:#fff8e1;
      --text:#fffafff5;--text-muted:#d8d2eb;
    }
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:smooth}
    body{background:#0a0716;color:var(--text);font-family:'Inter',system-ui,-apple-system,sans-serif;font-size:18px;line-height:1.78;overflow-x:hidden}

    /* COSMIC BACKGROUND (matches wealth-v2 sales page) */
    .dream-bg{z-index:0;pointer-events:none;position:fixed;inset:0;overflow:hidden;filter:brightness(.55) saturate(1.05);
      background:radial-gradient(circle at 12% 15%,#3a2fa0cc 0%,#0000 40%),radial-gradient(circle at 85% 10%,#221a66cc 0%,#0000 42%),linear-gradient(#161433 0%,#24205a 44%,#35307a 100%)}
    .dream-veil{position:absolute;inset:-8%;filter:blur(2px);animation:veilPulse 12s ease-in-out infinite;
      background:radial-gradient(50% 35% at 50% 80%,#fff3,#0000 100%),radial-gradient(45% 38% at 20% 35%,#e5cdff47,#0000 100%),radial-gradient(42% 32% at 78% 30%,#c3a0ff33,#0000 100%)}
    .milky-way{position:absolute;inset:-18%;filter:blur(2px);opacity:.8;mix-blend-mode:screen;transform:rotate(-24deg);animation:milkyDrift 90s linear infinite;
      background:radial-gradient(40% 18% at 52% 45%,#f5f4ff4d 0%,#f5f4ff14 42%,#f5f4ff00 72%),radial-gradient(48% 22% at 48% 50%,#c2b8ff33 0%,#c2b8ff0f 50%,#c2b8ff00 74%)}
    .dream-orb{position:absolute;width:36vmax;height:36vmax;border-radius:50%;filter:blur(44px);opacity:.46;mix-blend-mode:screen;animation:orbFloat 14s ease-in-out infinite}
    .dream-orb.one{background:radial-gradient(circle,#8a59ffbf 0%,#8a59ff00 70%);top:-12vmax;left:-8vmax}
    .dream-orb.two{background:radial-gradient(circle,#5c36cead 0%,#5c36ce00 72%);animation-duration:18s;animation-delay:-4s;top:22vh;right:-10vmax}
    .dream-orb.three{background:radial-gradient(circle,#ecca8170 0%,#ecca8100 72%);animation-duration:16s;animation-delay:-2s;bottom:-12vmax;left:20vw}
    @keyframes veilPulse{0%,100%{opacity:.85;transform:scale(1)}50%{opacity:1;transform:scale(1.04)}}
    @keyframes milkyDrift{0%{transform:rotate(-24deg) translateX(-3%)}100%{transform:rotate(-24deg) translateX(3%)}}
    @keyframes orbFloat{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(2vmax,-2vmax,0)}}

    main{position:relative;z-index:1}
    .wrap{max-width:720px;margin:0 auto;padding:0 24px}
    .section{padding:54px 0}
    .section--tight{padding:34px 0}
    .center{text-align:center}

    h1,h2,h3,h4{font-family:'Cormorant Garamond',Georgia,serif;color:#fff;line-height:1.18}
    h1{font-size:clamp(28px,6vw,42px);font-weight:600;margin-bottom:16px}
    h1 em,h2 em,h3 em{color:var(--gold-light);font-style:italic}
    h2{text-align:center;font-size:clamp(26px,4.4vw,38px);font-weight:600;margin-bottom:20px}
    .eyebrow{font-family:'Cinzel',sans-serif;font-size:12px;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);display:block;text-align:center;margin-bottom:14px;font-weight:600}
    .subhead{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:clamp(18px,2.6vw,22px);color:var(--text-muted);text-align:center;max-width:580px;margin:0 auto;line-height:1.55}
    .body-copy{max-width:620px;margin:0 auto;font-size:17px;line-height:1.8;color:var(--text)}
    .body-copy p+p{margin-top:18px}
    .body-copy strong{color:var(--gold-light)}

    .topnotice{position:relative;z-index:2;background:#9b1c1c;border-bottom:1px solid #c0392b;color:#ffd9d2;text-align:center;padding:12px 18px;font-family:'Cinzel',sans-serif;font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase}

    .hero-img{display:block;width:100%;max-width:600px;margin:26px auto 0;border-radius:14px;border:1px solid rgba(212,175,55,.55);box-shadow:0 16px 44px rgba(0,0,0,.55);background:#160c34}

    .cta{background:linear-gradient(180deg,var(--gold-bright) 0%,var(--gold) 100%);color:#1a0d2e;letter-spacing:.16em;text-transform:uppercase;cursor:pointer;text-shadow:0 1px #fff3;border:none;border-radius:50px;padding:20px 40px;font-family:'Cinzel',sans-serif;font-size:clamp(13px,2.2vw,15px);font-weight:700;text-decoration:none;display:inline-block;width:100%;max-width:520px;text-align:center;box-shadow:0 12px 32px #d4af3766,inset 0 1px #fff6;transition:transform .15s,box-shadow .2s}
    .cta:hover{transform:translateY(-2px);box-shadow:0 16px 40px #d4af3780}
    .cta-decline{display:block;width:100%;margin-top:16px;background:none;border:none;cursor:pointer;text-align:center;color:#cdc6e0;opacity:.7;font-family:'Inter',sans-serif;font-size:14px;text-decoration:underline;text-underline-offset:3px}
    .cta-decline:hover{opacity:1}
    .cta-fine{text-align:center;color:#bdb4d6;font-size:13px;margin-top:14px;line-height:1.6}

    .card{backdrop-filter:blur(4px);background:linear-gradient(#2d1b6980,#1e0d40d9);border:1px solid #d4af3759;border-radius:14px;padding:26px 24px}

    /* PART FRAME */
    .part-grid{display:grid;grid-template-columns:1fr auto 1fr;gap:16px;align-items:stretch;max-width:620px;margin:28px auto 0}
    .part-col{padding:20px 16px;border:1px solid #d4af3740;border-radius:12px;background:#1e0d4099;text-align:center}
    .part-col--done{border-color:#d4af3799;background:#2d1b6966}
    .part-num{font-family:'Cinzel',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:var(--gold);display:block;margin-bottom:8px}
    .part-title{font-family:'Cormorant Garamond',serif;font-size:19px;font-weight:600;color:#fff;margin-bottom:6px}
    .part-status{font-size:13px;color:var(--text-muted);font-style:italic}
    .part-status--done{color:#7ee0a0;font-style:normal}
    .part-arrow{display:flex;align-items:center;justify-content:center;color:var(--gold);font-size:22px}
    .part-col--active{border-color:var(--gold);background:#2d1b69b3;animation:needsGlow 2.2s ease-in-out infinite}
    .part-col--active .part-status{color:var(--gold-light);font-style:normal}
    @keyframes needsGlow{0%,100%{box-shadow:0 0 16px rgba(212,175,55,.3),inset 0 0 14px rgba(212,175,55,.1)}50%{box-shadow:0 0 34px rgba(212,175,55,.62),inset 0 0 22px rgba(212,175,55,.2)}}
    @media (prefers-reduced-motion:reduce){.part-col--active{animation:none;box-shadow:0 0 22px rgba(212,175,55,.45)}}

    .bridge-sig{margin-top:22px;padding-top:18px;border-top:1px solid #d4af3733;text-align:center;font-family:'Cormorant Garamond',serif;font-style:italic;font-size:18px;color:var(--gold-light)}

    /* PAIN */
    .pain-list{display:flex;flex-direction:column;gap:14px;max-width:620px;margin:0 auto}
    .pain-item{border-bottom:1px solid #d4af3726;padding:16px 0;font-size:17px;line-height:1.7;color:var(--text)}
    .pain-item:last-child{border-bottom:none}
    .pain-item em{color:var(--gold-light);font-style:italic}

    /* TESTIMONIALS */
    .testi{backdrop-filter:blur(4px);background:#ffffff0f;border:1px solid #d4af374d;border-radius:14px;max-width:620px;margin:0 auto 16px;padding:26px 26px}
    .testi__row{display:flex;align-items:center;gap:14px;margin-bottom:14px}
    .testi__avatar{width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid #d4af3780;flex-shrink:0}
    .testi__name{font-family:'Cinzel',sans-serif;font-size:14px;font-weight:700;color:#fff;letter-spacing:.04em}
    .testi__meta{font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.08em;color:var(--gold-light);margin-top:3px}
    .testi__stars{color:var(--gold);font-size:14px;letter-spacing:2px;margin-bottom:8px}
    .testi__body{font-size:16px;font-style:italic;line-height:1.7;color:var(--text)}

    /* OFFER (vip-box) */
    .offer-box{background:linear-gradient(180deg,var(--violet-mid) 0%,var(--violet) 100%);color:#fff;text-align:center;border:1px solid #d4af374d;border-radius:16px;padding:34px 28px;position:relative;overflow:hidden;box-shadow:0 12px 40px #0006}
    .offer-box::before{content:"";pointer-events:none;background:radial-gradient(at 50% 0,#d4af372e 0%,#0000 60%);position:absolute;inset:0}
    .offer-box>*{position:relative;z-index:1}
    .offer-label{font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:var(--gold-light);margin-bottom:12px;display:block}

    .vlist{text-align:left;max-width:480px;margin:0 auto;padding:0;list-style:none}
    .vlist li{color:var(--text);border-bottom:1px solid #d4af3726;display:flex;justify-content:space-between;align-items:baseline;gap:14px;padding:12px 0;font-size:16px;line-height:1.45}
    .vlist li:last-child{border-bottom:none}
    .vlist .vs-name{display:flex;gap:9px}
    .vlist .vs-name::before{content:"✦";color:var(--gold);flex-shrink:0;margin-top:2px;font-size:13px}
    .vlist .vs-price{font-family:'Cinzel',sans-serif;font-size:13px;color:var(--gold-light);opacity:.7;text-decoration:line-through;flex-shrink:0;white-space:nowrap}
    .vlist strong{color:var(--gold-light)}
    .vlist .vs-total{margin-top:6px;padding-top:14px;border-top:1px solid #d4af3759;border-bottom:none}
    .vlist .vs-total .vs-name{font-family:'Cormorant Garamond',serif;font-size:19px;font-weight:600;color:#fff}
    .vlist .vs-total .vs-name::before{content:""}
    .vlist .vs-total .vs-price{font-size:15px;text-decoration:none;opacity:1}

    .coupon{display:inline-block;position:relative;background:linear-gradient(135deg,rgba(212,175,55,.15),rgba(212,175,55,.07));border:1.5px dashed rgba(232,201,122,.6);border-radius:8px;padding:14px 28px;margin:24px auto 4px;max-width:440px}
    .coupon::before,.coupon::after{content:"";position:absolute;top:50%;width:16px;height:16px;border-radius:50%;background:#1e0d40;transform:translateY(-50%)}
    .coupon::before{left:-9px}.coupon::after{right:-9px}
    .coupon__label{font-family:'Cinzel',sans-serif;font-size:9px;letter-spacing:.28em;text-transform:uppercase;color:var(--gold);display:block;margin-bottom:6px}
    .coupon__amount{font-family:'Cinzel',sans-serif;font-size:21px;font-weight:700;color:var(--gold-light);display:block}
    .coupon__code{font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.14em;color:#cdc6e0;display:block;margin-top:6px}
    .coupon__code span{color:var(--gold-light);background:rgba(212,175,55,.12);border:1px solid rgba(232,201,122,.3);padding:2px 8px;border-radius:3px;margin-left:6px}

    .pricing{margin:22px 0 10px}
    .price-label{font-family:'Cinzel',sans-serif;font-size:12px;letter-spacing:.16em;text-transform:uppercase;color:var(--gold);display:block;margin-bottom:8px}
    .price-row{display:flex;justify-content:center;align-items:center;gap:16px;margin:6px 0 12px}
    .price-old{color:#ffffff73;text-decoration:line-through;text-decoration-color:var(--gold);font-family:'Cinzel',sans-serif;font-size:24px}
    .price-arrow{color:var(--gold-light);font-size:22px}
    .price-new{color:var(--gold-light);text-shadow:0 0 24px #d4af3759;font-family:'Cormorant Garamond',serif;font-size:clamp(58px,12vw,72px);font-weight:600;line-height:1}
    .price-new .cur{vertical-align:super;font-size:.42em;margin-right:3px;font-weight:500}
    .price-note{color:#fff9;font-size:14px;font-style:italic;line-height:1.65;max-width:520px;margin:0 auto}

    .lineup-img{width:100%;max-width:420px;display:block;margin:0 auto;filter:drop-shadow(0 18px 36px rgba(0,0,0,.45))}

    .guarantee{max-width:620px;margin:0 auto;text-align:center;border:1px solid #d4af3759;border-radius:16px;background:#1e0d4080;backdrop-filter:blur(4px);padding:36px 30px}
    .guarantee img{width:120px;height:auto;margin:0 auto 14px;display:block;filter:drop-shadow(0 10px 20px #d4af3740)}
    .guarantee p{font-size:17px;line-height:1.7;color:var(--text-muted);max-width:540px;margin:0 auto}

    .footer{position:relative;z-index:1;background:#0e0820;border-top:1px solid #d4af3726;padding:26px 24px;text-align:center}
    .footer p{font-size:12px;color:#9a93b3;line-height:1.6;max-width:720px;margin:0 auto 6px}
    .footer a{color:#b7afd0;text-decoration:underline;text-underline-offset:2px}

    @media (max-width:640px){
      .section{padding:42px 0}
      .wrap{padding:0 18px}
      .part-grid{grid-template-columns:1fr;gap:12px}
      .part-arrow{transform:rotate(90deg);padding:2px 0}
      .offer-box{padding:28px 20px}
      .topnotice{font-size:10.5px;letter-spacing:.08em}
    }
  </style>

  <!-- Microsoft Clarity -->
  <script type="text/javascript">
    (function(c,l,a,r,i,t,y){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
    t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
    y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);})(window,document,"clarity","script","wq82rtc2gf");
  </script>
</head>
<body>

  <!-- NOTICE BAR -->
  <div class="topnotice">Wait, Before You Go, A One-Time Offer</div>

  <!-- COSMIC BACKGROUND -->
  <div class="dream-bg" aria-hidden="true">
    <div class="dream-veil"></div>
    <div class="milky-way"></div>
    <div class="dream-orb one"></div>
    <div class="dream-orb two"></div>
    <div class="dream-orb three"></div>
  </div>

  <main>

    <!-- HERO -->
    <section class="section center">
      <div class="wrap">
        <h1>I Am Not Letting You Leave<br /><em>With Two Mirrors Still Clouded.</em></h1>
        <p class="subhead">$67 was not right today. Fine. Same rituals, lower price. I would rather meet you here than watch you wait months for your love work to reach wealth and purpose on its own.</p>
        <img class="hero-img" src="https://soulmirrorreading.com/frontend/images/upsell2/wcr-hero.webp" alt="Two antique mirrors clearing to gold light, one for wealth and one for purpose" />
      </div>
    </section>

    <!-- PART FRAME -->
    <section class="section--tight center">
      <div class="wrap">
        <span class="eyebrow">✦ &nbsp; The Mirror You Cleared. The Two You Have Not. &nbsp; ✦</span>
        <p class="subhead">Your love work will reach <strong style="color:var(--gold-light);font-style:normal;">wealth and purpose</strong> in time. This page is the shortcut, at a price I do not usually show.</p>
        <div class="part-grid">
          <div class="part-col part-col--done">
            <span class="part-num">Mirror One</span>
            <p class="part-title">Love</p>
            <p class="part-status part-status--done">✓ The work you came in for</p>
          </div>
          <div class="part-arrow">→</div>
          <div class="part-col part-col--active">
            <span class="part-num">Mirrors Two &amp; Three</span>
            <p class="part-title">Wealth &amp; Purpose</p>
            <p class="part-status">Still needs clearing &middot; Now $47</p>
          </div>
        </div>
      </div>
    </section>

    <!-- BRIDGE -->
    <section class="section">
      <div class="wrap">
        <div class="card" style="max-width:640px;margin:0 auto;">
          <div class="body-copy">
            <p>Clearing the love mirror matters. But it was never the only mirror the block was hiding in.</p>
            <p>The love work reaches wealth and purpose in the end, because it is one root. But the end can be another decade of the same pattern capping what you earn and shrinking the work you were meant to do. <strong>This is the same complete clearing for the other two: three movements, three bonuses. Only the price changed, and this page will not be offered again.</strong></p>
          </div>
          <p class="bridge-sig"><strong style="color:#fff;font-style:normal;">Luna Ross</strong></p>
        </div>
      </div>
    </section>

    <!-- PAIN -->
    <section class="section">
      <div class="wrap">
        <span class="eyebrow">You Already Know This Is You</span>
        <h2>You Fixed the Love Story.<br /><em>The Ceiling Never Moved.</em></h2>
        <div class="pain-list" style="margin-top:24px;">
          <div class="pain-item">The money climbs to a certain line, then quietly slides back down. <em>You stopped noticing you do it.</em></div>
          <div class="pain-item">You feel the pull toward the work that is truly yours, and file it under "someday" one more time.</div>
          <div class="pain-item">You are not bad with money. You are not lazy about your calling. <em>It is one block, still running in two mirrors you never cleared.</em></div>
        </div>
      </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="section">
      <div class="wrap">
        <span class="eyebrow">✦ From People Who Almost Said No Twice ✦</span>
        <h2>They Clicked Away Once.<br /><em>This Page Brought Them Back.</em></h2>
        <div style="margin-top:26px;">
          <div class="testi">
            <div class="testi__row">
              <img class="testi__avatar" src="https://soulmirrorreading.com/frontend/images/upsell2/testimonial-jennifer-l.webp" alt="Jennifer L." />
              <div><div class="testi__name">Jennifer L.</div><div class="testi__meta">51 &middot; Denver, US</div></div>
            </div>
            <div class="testi__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="testi__body">"I almost said no at $67. When the price dropped I almost said no again, thinking it was a lesser version. It is not. Same ritual. A client raised her offer for me before I had told anyone I was doing this work."</p>
          </div>
          <div class="testi">
            <div class="testi__row">
              <img class="testi__avatar" src="https://soulmirrorreading.com/frontend/images/upsell2/testimonial-marcus-t.webp" alt="Marcus T." />
              <div><div class="testi__name">Marcus T.</div><div class="testi__meta">46 &middot; Austin, US</div></div>
            </div>
            <div class="testi__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="testi__body">"I was the definition of sceptical. I clicked 'no thanks' the first time. Something made me reconsider here. Three weeks later I launched the work I had sat on for years, and the first invoice landed bigger than I would have dared to ask. I just know it worked."</p>
          </div>
          <div class="testi">
            <div class="testi__row">
              <img class="testi__avatar" src="https://soulmirrorreading.com/frontend/images/upsell2/testimonial-elena-m.webp" alt="Elena M." />
              <div><div class="testi__name">Elena M.</div><div class="testi__meta">43 &middot; Barcelona, ES</div></div>
            </div>
            <div class="testi__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="testi__body">"I have too many courses sitting unfinished. This is the only one I finished in a single weekend, because it is a practice, not a course. The Pattern in Wealth movement felt like being seen."</p>
          </div>
        </div>
      </div>
    </section>

    <!-- OFFER / PRICE DROP -->
    <section class="section" id="offer">
      <div class="wrap" style="max-width:620px;">
        <div class="offer-box">
          <span class="offer-label">One-Time Offer, Disappears When You Leave</span>
          <h2 style="margin-bottom:10px;">Clear the Other Two Mirrors for $47</h2>
          <p style="color:#cfc7e6;font-style:italic;font-family:'Cormorant Garamond',serif;font-size:20px;margin-bottom:24px;">The same rituals that speed up wealth and purpose. Same three bonuses. The only thing lower is the price.</p>
          <img class="lineup-img" src="https://soulmirrorreading.com/frontend/images/upsell2/wcr-package.webp" alt="The complete Wealth Clarity Ritual bundle, the 3-movement practice and 3 bonuses" style="margin-bottom:26px;" />

          <ul class="vlist">
            <li><span class="vs-name">The Wealth Clarity Ritual, a 3-Movement Practice</span><span class="vs-price">$147</span></li>
            <li><span class="vs-name"><strong>Bonus 1</strong>&nbsp;&middot; The Purpose Alignment Ritual</span><span class="vs-price">$67</span></li>
            <li><span class="vs-name"><strong>Bonus 2</strong>&nbsp;&middot; The Open-to-Receiving Audio</span><span class="vs-price">$47</span></li>
            <li><span class="vs-name"><strong>Bonus 3</strong>&nbsp;&middot; The Daily Clarity Practice</span><span class="vs-price">$29</span></li>
            <li class="vs-total"><span class="vs-name">Total Value</span><span class="vs-price">$290</span></li>
          </ul>

          <div class="coupon">
            <span class="coupon__label">✦ &nbsp; One-Time Discount Applied &nbsp; ✦</span>
            <span class="coupon__amount">&minus; $20.00 OFF</span>
            <span class="coupon__code">Code <span>EXTRA20</span></span>
          </div>

          <div class="pricing">
            <span class="price-label">Your Price, One Time Only</span>
            <div class="price-row">
              <span class="price-old">$67</span>
              <span class="price-arrow">→</span>
              <span class="price-new"><span class="cur">$</span>47</span>
            </div>
            <p class="price-note">One payment. Instant download. No subscription.</p>
          </div>

          <a class="cta" href="https://rebornf.pay.clickbank.net/?cbitems=wcr-1-ds&amp;cbur=a">Yes, Upgrade My Order Now.</a>
          <p class="cta-fine">Secure checkout via ClickBank, everything delivered immediately.</p>
          <a class="cta-decline" href="https://rebornf.pay.clickbank.net/?cbitems=wcr-1-ds&amp;cbur=d">No thank you, continue to my reading</a>
        </div>
      </div>
    </section>

    <!-- GUARANTEE -->
    <section class="section">
      <div class="wrap">
        <div class="guarantee">
          <img src="https://soulmirrorreading.com/frontend/images/upsell2/guarantee-badge.webp" alt="90 Day Mirror Guarantee" />
          <h2 style="font-size:clamp(19px,2.8vw,24px);margin-bottom:14px;">90-Day Money-Back Guarantee</h2>
          <p>Work through all three movements. If your relationship with money and your sense of purpose feel no different within 90 days, email us for a full refund of your $47. No questions, no explanation. The risk is entirely mine.</p>
        </div>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <footer class="footer">
    <p>ClickBank is the retailer of products on this site. CLICKBANK&reg; is a registered trademark of Click Sales, Inc., a Delaware corporation located at 1444 S. Entertainment Ave., Suite 410 Boise, ID 83709, USA and used by permission. ClickBank's role as retailer does not constitute an endorsement, approval or review of these products or any claim, statement or opinion used in promotion of these products.</p>
    <p>For Product Support, please contact the vendor: <a href="mailto:support@soulmirrorreading.com">HERE</a>. For Order Support, please contact ClickBank: <a href="https://www.clkbank.com/" target="_blank" rel="noopener">HERE</a> or 1-800-390-6035</p>
    <p><a href="/privacy-policy">Privacy Policy</a> &nbsp;&middot;&nbsp; <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;&middot;&nbsp; <a href="mailto:support@soulmirrorreading.com">Contact Us</a> &nbsp;&middot;&nbsp; <a href="/refund-return-policy">Refund &amp; Return Policy</a></p>
    <p>The content and products on this site are for guidance and reflection. They are not financial, medical, or professional advice. Results vary.</p>
    <p>&copy; 2026 Soul Mirror Reading, A Luna Ross Brand. All Rights Reserved.</p>
  </footer>

</body>
</html>

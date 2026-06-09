<?php
declare(strict_types=1);

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

\App\Config\AppConfig::load($projectRoot);


$otoCheckoutUrl = 'https://rebornf.pay.clickbank.net/?cbitems=srp-1&cbur=a';
$downsellPageUrl = 'https://rebornf.pay.clickbank.net/?cbitems=srp-1&cbur=d';

?><!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/svg+xml" href="favicon.svg" />
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Upsell 1 - Soul Ritual Practice v2 (wealth-v2 cosmic system) -->
  <title>Complete Your Soul Mirror Reading, The Soul Ritual Practice From Luna Ross</title>
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
    .wrap{max-width:760px;margin:0 auto;padding:0 24px}
    .section{padding:56px 0}
    .section--tight{padding:34px 0}
    .center{text-align:center}

    /* TYPE */
    h1,h2,h3,h4{font-family:'Cormorant Garamond',Georgia,serif;color:#fff;line-height:1.18}
    h1{font-size:clamp(28px,6vw,44px);font-weight:600;margin-bottom:16px}
    h1 em,h2 em,h3 em{color:var(--gold-light);font-style:italic}
    h2{text-align:center;font-size:clamp(27px,4.5vw,40px);font-weight:600;margin-bottom:22px}
    h3{font-size:22px;font-weight:600;margin-bottom:8px}
    .eyebrow{font-family:'Cinzel',sans-serif;font-size:12px;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);display:block;text-align:center;margin-bottom:14px;font-weight:600}
    .subhead{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:clamp(18px,2.6vw,22px);color:var(--text-muted);text-align:center;max-width:600px;margin:0 auto 8px;line-height:1.55}
    .lead{text-align:center;max-width:580px;margin:0 auto 26px;color:var(--text-muted);font-size:17px}
    .body-copy{max-width:620px;margin:0 auto;font-size:17px;line-height:1.8;color:var(--text)}
    .body-copy p+p{margin-top:18px}
    .body-copy strong{color:var(--gold-light)}
    .gold-rule{border:none;height:1px;background:linear-gradient(90deg,transparent,var(--gold),transparent);max-width:340px;margin:28px auto}

    /* NOTICE BAR */
    .topnotice{position:relative;z-index:2;background:#9b1c1c;border-bottom:1px solid #c0392b;color:#f0dca8;text-align:center;padding:13px 18px;font-family:'Inter',system-ui,sans-serif;font-size:12.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;line-height:1.5}
    .topnotice .dot{color:#f3b0a0;margin:0 9px}

    /* HERO */
    .hero-img{display:block;width:100%;max-width:600px;margin:26px auto 0;border-radius:14px;border:1px solid rgba(212,175,55,.55);box-shadow:0 16px 44px rgba(0,0,0,.55);background:#160c34}

    /* CTA */
    .cta{background:linear-gradient(180deg,var(--gold-bright) 0%,var(--gold) 100%);color:#1a0d2e;letter-spacing:.16em;text-transform:uppercase;cursor:pointer;text-shadow:0 1px #fff3;border:none;border-radius:50px;padding:20px 40px;font-family:'Cinzel',sans-serif;font-size:clamp(13px,2.2vw,15px);font-weight:700;text-decoration:none;display:inline-block;width:100%;max-width:520px;text-align:center;box-shadow:0 12px 32px #d4af3766,inset 0 1px #fff6;transition:transform .15s,box-shadow .2s}
    .cta:hover{transform:translateY(-2px);box-shadow:0 16px 40px #d4af3780}
    .cta-decline{display:block;width:100%;margin-top:16px;background:none;border:none;cursor:pointer;text-align:center;color:#cdc6e0;opacity:.7;font-family:'Inter',sans-serif;font-size:14px;text-decoration:underline;text-underline-offset:3px}
    .cta-decline:hover{opacity:1}
    .cta-fine{text-align:center;color:#bdb4d6;font-size:13px;margin-top:12px;line-height:1.6}

    /* GLASS CARD (matches .card-mirror) */
    .card{backdrop-filter:blur(4px);background:linear-gradient(#2d1b6980,#1e0d40d9);border:1px solid #d4af3759;border-radius:14px;padding:26px 24px}

    /* EARLY OFFER + MAIN OFFER (matches .vip-box) */
    .offer-box{background:linear-gradient(180deg,var(--violet-mid) 0%,var(--violet) 100%);color:#fff;text-align:center;border:1px solid #d4af374d;border-radius:16px;padding:34px 28px;position:relative;overflow:hidden;box-shadow:0 12px 40px #0006}
    .offer-box::before{content:"";pointer-events:none;background:radial-gradient(at 50% 0,#d4af372e 0%,#0000 60%);position:absolute;inset:0}
    .offer-box>*{position:relative;z-index:1}
    .offer-label{font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-light);margin-bottom:12px;display:block}

    /* VALUE LIST */
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

    /* COUPON */
    .coupon{display:inline-block;position:relative;background:linear-gradient(135deg,rgba(212,175,55,.15),rgba(212,175,55,.07));border:1.5px dashed rgba(232,201,122,.6);border-radius:8px;padding:14px 28px;margin:24px auto 4px;max-width:440px}
    .coupon::before,.coupon::after{content:"";position:absolute;top:50%;width:16px;height:16px;border-radius:50%;background:#1e0d40;transform:translateY(-50%)}
    .coupon::before{left:-9px}.coupon::after{right:-9px}
    .coupon__label{font-family:'Cinzel',sans-serif;font-size:9px;letter-spacing:.28em;text-transform:uppercase;color:var(--gold);display:block;margin-bottom:6px}
    .coupon__amount{font-family:'Cinzel',sans-serif;font-size:21px;font-weight:700;color:var(--gold-light);display:block}
    .coupon__code{font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.14em;color:#cdc6e0;display:block;margin-top:6px}
    .coupon__code span{color:var(--gold-light);background:rgba(212,175,55,.12);border:1px solid rgba(232,201,122,.3);padding:2px 8px;border-radius:3px;margin-left:6px}

    /* PRICING (matches .pricing-block) */
    .pricing{margin:22px 0 10px}
    .price-label{font-family:'Cinzel',sans-serif;font-size:12px;letter-spacing:.16em;text-transform:uppercase;color:var(--gold);display:block;margin-bottom:8px}
    .price-row{display:flex;justify-content:center;align-items:baseline;gap:18px;margin:6px 0 12px}
    .price-old{color:#ffffff73;text-decoration:line-through;text-decoration-color:var(--gold);font-family:'Cinzel',sans-serif;font-size:24px}
    .price-new{color:var(--gold-light);text-shadow:0 0 24px #d4af3759;font-family:'Cormorant Garamond',serif;font-size:clamp(58px,12vw,72px);font-weight:600;line-height:1}
    .price-new .cur{vertical-align:super;font-size:.42em;margin-right:3px;font-weight:500}
    .price-note{color:#fff9;font-size:14px;font-style:italic;line-height:1.65;max-width:520px;margin:0 auto}

    .founding-blink{animation:foundingBlink 1.05s ease-in-out infinite;color:#E8C97A;font-weight:600}
    @keyframes foundingBlink{0%,100%{opacity:1}50%{opacity:.35}}

    /* PART GRID (journey) */
    .part-grid{display:grid;grid-template-columns:1fr auto 1fr;gap:16px;align-items:stretch;max-width:620px;margin:30px auto 0}
    .part-col{padding:20px 16px;border:1px solid #d4af3740;border-radius:12px;background:#1e0d4099;text-align:center}
    .part-col--done{border-color:#d4af3799;background:#2d1b6966}
    .part-num{font-family:'Cinzel',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:var(--gold);display:block;margin-bottom:8px}
    .part-title{font-family:'Cormorant Garamond',serif;font-size:19px;font-weight:600;color:#fff;margin-bottom:6px}
    .part-status{font-size:13px;color:var(--text-muted);font-style:italic}
    .part-status--done{color:#7ee0a0;font-style:normal}
    .part-arrow{display:flex;align-items:center;justify-content:center;color:var(--gold);font-size:22px}

    /* MOVEMENT / BONUS CARDS */
    .stack{display:flex;flex-direction:column;gap:16px;max-width:620px;margin:0 auto}
    .mv{display:flex;gap:20px;align-items:flex-start}
    .mv__num{font-family:'Cinzel',sans-serif;font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:var(--gold);font-weight:600;min-width:118px;flex-shrink:0;padding-top:2px}
    .mv__body p{font-size:16px;line-height:1.7;color:var(--text-muted)}
    .bonus{position:relative}
    .bonus__badge{position:absolute;top:-12px;left:22px;background:linear-gradient(135deg,#d4af37,#b8941f);color:#1a0d40;font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.1em;text-transform:uppercase;font-weight:700;padding:5px 14px;border-radius:14px;box-shadow:0 3px 10px #d4af374d}
    .bonus h3{color:var(--gold-light);font-size:20px;margin:6px 0 8px}
    .bonus p{font-size:16px;line-height:1.65;color:var(--text-muted)}
    .bonus .val{font-family:'Cinzel',sans-serif;font-size:13px;letter-spacing:.05em;color:var(--gold-light);margin-top:10px;display:block}

    /* LUNA */
    .luna-card{max-width:680px;margin:0 auto}
    .luna-card .body-copy{max-width:none}
    .luna-sig{margin-top:24px;padding-top:20px;border-top:1px solid #d4af3733;font-family:'Cormorant Garamond',serif;font-style:italic;font-size:18px;color:var(--gold-light)}

    /* TESTIMONIALS (matches .testi-card) */
    .testi{backdrop-filter:blur(4px);background:#ffffff0f;border:1px solid #d4af374d;border-radius:14px;max-width:620px;margin:0 auto 16px;padding:26px 26px}
    .testi__row{display:flex;align-items:center;gap:14px;margin-bottom:14px}
    .testi__avatar{width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid #d4af3780;flex-shrink:0}
    .testi__name{font-family:'Cinzel',sans-serif;font-size:14px;font-weight:700;color:#fff;letter-spacing:.04em}
    .testi__meta{font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.08em;color:var(--gold-light);margin-top:3px}
    .testi__stars{color:var(--gold);font-size:14px;letter-spacing:2px;margin-bottom:8px}
    .testi__body{font-size:16px;font-style:italic;line-height:1.7;color:var(--text)}

    /* GUARANTEE */
    .guarantee{max-width:620px;margin:0 auto;text-align:center;border:1px solid #d4af3759;border-radius:16px;background:#1e0d4080;backdrop-filter:blur(4px);padding:38px 32px}
    .guarantee img{width:120px;height:auto;margin:0 auto 14px;display:block;filter:drop-shadow(0 10px 20px #d4af3740)}
    .guarantee p{font-size:17px;line-height:1.7;color:var(--text-muted);max-width:540px;margin:0 auto}

    /* FAQ (matches .faq details) */
    .faq{max-width:680px;margin:0 auto}
    .faq details{border-bottom:1px solid #d4af3740;padding:18px 4px}
    .faq summary{color:#fff;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:16px;font-family:'Cormorant Garamond',serif;font-size:19px;font-weight:600;list-style:none}
    .faq summary::-webkit-details-marker{display:none}
    .faq summary::after{content:"+";color:var(--gold);font-size:24px;flex-shrink:0}
    .faq details[open] summary::after{content:"\2212"}
    .faq__a{margin-top:12px;font-size:16px;line-height:1.7;color:var(--text-muted)}

    /* FOOTER */
    .footer{position:relative;z-index:1;background:#0e0820;border-top:1px solid #d4af3726;padding:28px 24px;text-align:center}
    .footer p{font-size:12px;color:#9a93b3;line-height:1.6;max-width:760px;margin:0 auto 8px}
    .footer a{color:#b7afd0;text-decoration:underline;text-underline-offset:2px}

    @media (max-width:640px){
      .section{padding:44px 0}
      .wrap{padding:0 18px}
      .part-grid{grid-template-columns:1fr;gap:12px}
      .part-arrow{transform:rotate(90deg);padding:2px 0}
      .mv{flex-direction:column;gap:8px}
      .mv__num{min-width:auto}
      .offer-box{padding:28px 20px}
      .topnotice{font-size:10.5px;letter-spacing:.06em}
      .topnotice .dot{margin:0 6px}
    }
    .offer-box .hero-img{border:none !important;box-shadow:none !important;background:transparent !important}
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
  <div class="topnotice">
    <strong>Hold On, This Is Just for You</strong><span class="dot">&middot;</span><strong>A Members-Only Upgrade Just Unlocked, $100 Off</strong><span class="dot">&middot;</span><strong>Before Your Reading Lands</strong>
  </div>

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
        <h1>The Reading Named the Pattern Capping Your Money.<br /><em>This Is How You Clear It.</em></h1>
        <p class="subhead">The Soul Ritual Practice is the fast track to clearing it: the exact rituals, in the right order, for the precise Wealth Block your three cards revealed. You are seconds from holding both halves of the work.</p>
        <video class="hero-img" autoplay loop muted playsinline poster="frontend/images/upsell/upsell-hero.jpg">
          <source src="frontend/images/upsell/upsell-hero-motion.mp4" type="video/mp4" />
          <img class="hero-img" src="frontend/images/upsell/upsell-hero.jpg" alt="Hands performing a clearing ritual over a gold filigree mirror with rising smoke, deep violet and gold" />
        </video>
      </div>
    </section>

    <!-- EARLY OFFER -->
    <section class="section--tight">
      <div class="wrap" style="max-width:600px;">
        <div class="offer-box">
          <span class="offer-label">The Fast Track to Clear Your Wealth Block</span>
          <h2 style="font-size:30px;margin-bottom:12px;">Add The Soul Ritual Practice</h2>
          <p style="color:#e9e2f2;font-size:16px;line-height:1.6;margin:0 auto 18px;max-width:480px;">One payment. Instant download. Yours to keep for life. The reading lands in your inbox within 24 hours; this is ready the moment you confirm.</p>
          <ul class="vlist" style="margin-bottom:20px;">
            <li><span class="vs-name">The Mirror Block Clearing Rituals, a 3-part protocol. All four block-type versions are inside, so whichever one your reading reveals is ready the moment it lands.</span></li>
            <li><span class="vs-name">The Mirror Block Workbook, 45 pages to deepen and hold the work.</span></li>
            <li><span class="vs-name">3 free bonuses: the Audio Companion, the Wealth Alert Protocol, the Love Harmony Audio.</span></li>
            <li><span class="vs-name">Yours to keep for life. Instant download.</span></li>
          </ul>
          <div class="pricing" style="margin:0;">
            <p style="color:#cfc7e6;font-size:14px;margin-bottom:4px;">$449 value &middot; <span class="founding-blink">$100 founding discount applied</span></p>
            <div class="price-row" style="margin:4px 0 8px;">
              <span class="price-old" style="font-size:20px;">$197</span>
              <span class="price-new" style="font-size:46px;"><span class="cur">$</span>97</span>
            </div>
            <p style="color:#cdb98c;font-size:13px;margin:0 auto 18px;">Founding member rate, this page only. One payment, no subscription.</p>
          </div>
          <a class="cta" href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>">Yes, Upgrade My Order Now</a>
          <p class="cta-fine">This adds to the order you just placed. No card to re-enter. Backed by a 90-day money-back guarantee.</p>
          <a class="cta-decline" href="<?= htmlspecialchars($downsellPageUrl, ENT_QUOTES, 'UTF-8') ?>">No thank you, continue to my reading &rarr;</a>
        </div>
      </div>
    </section>

    <!-- AGITATION -->
    <section class="section">
      <div class="wrap">
        <h2>You Have Named This Pattern Before.<br /><em>It Still Decides What You Earn.</em></h2>
        <div class="body-copy">
          <p>Here is the part almost no one says out loud.</p>
          <p>You have read the books. Done the journaling. Sat through the courses, the therapy, the manifesting. At some point you saw the money pattern clearly and told yourself, <strong>"Now that I see it, I can change it."</strong></p>
          <p>And then it came back. The opportunity appeared, and something in you hesitated. The rate was yours to raise, and you left it where it was. The money came in, and somehow it did not stay. <strong>Same ceiling. Different month.</strong></p>
        </div>
        <div class="card" style="max-width:620px;margin:26px auto;border-left:3px solid var(--gold);">
          <p style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:20px;color:var(--gold-light);line-height:1.55;margin:0;">"You did not name it wrong. You named it. But naming a ceiling has never once lowered it, and every month it stays named and unmoved, it quietly resets your income again."</p>
        </div>
        <div class="body-copy">
          <p>That is the gap between a reading that names your Wealth Block and the work that actually dissolves it. <strong>The Soul Ritual Practice is built to close that gap, at the layer where the ceiling was set.</strong></p>
        </div>
      </div>
    </section>

    <!-- MECHANISM / THREE RITUALS -->
    <section class="section">
      <div class="wrap">
        <h2>Three Written Rituals,<br /><em>Calibrated to Your Wealth Block</em></h2>
        <p class="subhead">Not a general healing. The exact protocol for the precise ceiling your cards revealed.</p>
        <div class="body-copy" style="margin-top:22px;">
          <p>You have read, journaled, and manifested, and the ceiling never really moved, because none of that reaches the layer your Wealth Block actually lives on. <strong>The Soul Ritual Practice is the fast track:</strong> it goes straight to that layer and clears it, in three stages, each written for your exact block type.</p>
        </div>
        <hr class="gold-rule" />
        <div class="stack">
          <div class="card mv">
            <div class="mv__num">Ritual One</div>
            <div class="mv__body"><h3>The Root Witnessing</h3><p>Traces your Wealth Block to the exact moment it was set, not the story you tell about money, the moment underneath it. This is precision work, not journaling. Most people say this ritual alone feels like something loosening on the first read.</p></div>
          </div>
          <div class="card mv">
            <div class="mv__num">Ritual Two</div>
            <div class="mv__body"><h3>The Pattern Interruption</h3><p>The ceiling shows itself most clearly the instant money gets close: the moment you are about to receive, quote, ask, or invest. This is the exact sequence for catching it there and interrupting it in real time, with the specific move for your block type.</p></div>
          </div>
          <div class="card mv">
            <div class="mv__num">Ritual Three</div>
            <div class="mv__body"><h3>The New Imprint</h3><p>Clearing the old ceiling leaves an empty space. What fills it decides whether the change holds or quietly resets. This final ritual writes a new ceiling at the body level, the imprint that makes more money feel safe to keep. This is what makes the shift structural, not a good week.</p></div>
          </div>
        </div>
      </div>
    </section>

    <!-- LUNA -->
    <section class="section">
      <div class="wrap">
        <h2>I Carried This Ceiling <em>for Years Before I Cleared It</em></h2>
        <div class="card luna-card">
          <div class="body-copy">
            <p>Eleven years ago I was exactly where you are. I had done the reading. I understood my money pattern perfectly. I could trace it, name it, explain it. <strong>And I watched it cap me anyway, year after year.</strong></p>
            <p>What finally moved it was not another reading. It was meeting the pattern at the layer where it was set, not explained, not budgeted around, but <em style="color:var(--gold-light);">witnessed and rewritten</em> in the body. I spent the years after distilling that into a written protocol anyone could follow alone, specific to each Wealth Block type, no practitioner and no scheduling required.</p>
            <p>I have now walked thousands of people through this. <strong>The ceiling that would not move through understanding moved when they did the right work, at the right layer, in the right order.</strong> Your reading names your block. This is how I walk you through clearing it, the same sequence I would use if you were sitting across from me.</p>
          </div>
          <p class="luna-sig">With love and with clarity,<br /><strong style="color:#fff;font-style:normal;">Luna Ross</strong> &middot; 11 years, 4,800+ readings</p>
        </div>
      </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="section">
      <div class="wrap">
        <span class="eyebrow">✦ From People Who Almost Clicked Away ✦</span>
        <h2>They Nearly Said No.<br /><em>Then the Ceiling Moved.</em></h2>
        <div style="margin-top:26px;">
          <div class="testi">
            <div class="testi__row">
              <img class="testi__avatar" src="frontend/images/upsell/testimonial-margaret-v.png" alt="Margaret V." />
              <div><div class="testi__name">Margaret V.</div><div class="testi__meta">52 &middot; Edinburgh, UK</div></div>
            </div>
            <div class="testi__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="testi__body">"I almost closed this page. I told myself I had done enough inner work to last a lifetime. Something made me stay. Four months on, I finally raised my rates after years of stalling, and the client said yes without blinking. The number I could never get past is not the ceiling anymore."</p>
          </div>
          <div class="testi">
            <div class="testi__row">
              <img class="testi__avatar" src="frontend/images/upsell/testimonial-david-r.png" alt="David R." />
              <div><div class="testi__name">David R.</div><div class="testi__meta">49 &middot; Manchester, UK</div></div>
            </div>
            <div class="testi__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="testi__body">"I will be honest, I bought it more out of curiosity than belief. I have done the courses. What I did not expect was Ritual Two catching me in the exact half-second I always talk myself down on price. I held my number on a proposal last month that I would have slashed a year ago. It stuck. It paid for itself the first time I used it."</p>
          </div>
          <div class="testi">
            <div class="testi__row">
              <img class="testi__avatar" src="frontend/images/upsell/testimonial-sophia-k.png" alt="Sophia K." />
              <div><div class="testi__name">Sophia K.</div><div class="testi__meta">47 &middot; Melbourne, AU</div></div>
            </div>
            <div class="testi__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="testi__body">"I actually did click away. Then I came back the next day and bought it. The Root Witnessing showed me where my whole 'money never stays' story started, and I cried, not from pain, from relief. For the first time in my life a good month did not quietly undo itself the next."</p>
          </div>
        </div>
        <p style="text-align:center;max-width:620px;margin:18px auto 0;font-size:13px;color:#9a93b3;font-style:italic;">Individual results vary and are not typical. The Soul Ritual Practice is a self-guided practice, not financial advice.</p>
      </div>
    </section>

    <!-- URGENCY -->
    <section class="section--tight center">
      <div class="wrap" style="max-width:640px;">
        <h2 style="font-size:clamp(20px,3.4vw,26px);">Founding Member Price, This Page Only</h2>
        <p style="color:var(--text-muted);font-size:16px;line-height:1.7;max-width:560px;margin:0 auto;">Two things are true right now. The $97 founding rate is shown once, here, then returns to its $197 public price. And the ceiling does not pause while you decide. Left alone, it resets your income again next month, the same way it has every month so far. The page closing is the small loss. Another year at the same ceiling is the real one.</p>
      </div>
    </section>

    <!-- MAIN OFFER -->
    <section class="section" id="offer">
      <div class="wrap" style="max-width:620px;">
        <div class="offer-box">
          <span class="offer-label">Complete Your Wealth Work</span>
          <h2 style="margin-bottom:10px;">Add The Soul Ritual Practice</h2>
          <p style="color:#cfc7e6;font-style:italic;font-family:'Cormorant Garamond',serif;font-size:20px;margin-bottom:24px;">The diagnosis is on its way. This is the treatment. Yours to keep for life.</p>
          <img class="hero-img" src="frontend/images/ups-downs/upsell1-package.png" alt="The Complete Soul Ritual Practice, all products together" style="max-width:420px;margin:0 auto 26px;" />

          <ul class="vlist">
            <li><span class="vs-name">The Mirror Block Clearing Rituals, a 3-Part Protocol</span><span class="vs-price">$201</span></li>
            <li><span class="vs-name">The Mirror Block Workbook, 45 Pages</span><span class="vs-price">$97</span></li>
            <li><span class="vs-name"><strong>Bonus 1</strong>&nbsp;&middot; Soul Ritual Audio Companion</span><span class="vs-price">$67</span></li>
            <li><span class="vs-name"><strong>Bonus 2</strong>&nbsp;&middot; The Wealth Alert Protocol</span><span class="vs-price">$47</span></li>
            <li><span class="vs-name"><strong>Bonus 3</strong>&nbsp;&middot; The Love Harmony Audio</span><span class="vs-price">$37</span></li>
            <li class="vs-total"><span class="vs-name">Total Value</span><span class="vs-price">$449</span></li>
          </ul>

          <div class="coupon">
            <span class="coupon__label">✦ &nbsp; Founding Member Coupon Applied &nbsp; ✦</span>
            <span class="coupon__amount">- $100.00 OFF</span>
            <span class="coupon__code">Code <span>FOUNDING100</span></span>
          </div>

          <div class="pricing">
            <span class="price-label">Founding Member Price Today</span>
            <div class="price-row">
              <span class="price-old">$197</span>
              <span class="price-new"><span class="cur">$</span>97</span>
            </div>
            <p class="price-note">Worked through with me one-to-one, this sequence runs $197, which is the public price the moment this launch ends. The only reason it is $97 today is that founding members who take it early help me prove it works at scale. You are paying the founding rate for being early, not buying a lesser version. It is the identical practice, complete. One payment, no subscription.</p>
          </div>

          <a class="cta" href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>">Yes, Upgrade My Order Now</a>
          <p class="cta-fine">Charged to the order you just placed. No card to re-enter. Backed by the 90-day guarantee below.</p>
          <a class="cta-decline" href="<?= htmlspecialchars($downsellPageUrl, ENT_QUOTES, 'UTF-8') ?>">No thank you, continue to my reading &rarr;</a>
        </div>
      </div>
    </section>

    <!-- GUARANTEE -->
    <section class="section">
      <div class="wrap">
        <div class="guarantee">
          <img src="frontend/images/ups-downs/img-bb865578d0.png" alt="90-Day Guarantee Badge" />
          <h2 style="font-size:clamp(20px,3vw,26px);margin-bottom:14px;">The Soul Ritual Practice, 90-Day Guarantee</h2>
          <p>Open the three rituals, follow them in order, and give them a real 90 days. If at any point in those 90 days your money ceiling has not budged, reply and I refund every cent of your $97. No questionnaire, no proof, no explaining yourself; your word is enough. You keep the rituals, the workbook, and all three bonuses no matter what. The only way you lose here is by never opening them.</p>
        </div>
      </div>
    </section>

    <!-- FAQ -->
    <section class="section">
      <div class="wrap">
        <h2>Questions About the Practice</h2>
        <div class="faq" style="margin-top:24px;">
          <details>
            <summary>I have not seen my reading yet. Am I buying blind?</summary>
            <div class="faq__a">No, and this is the part most people get backwards. All four block-type versions live inside the practice. Your reading has already identified which one is yours, so the moment it lands, the exact protocol for your ceiling is sitting ready to open, no waiting, nothing to request. Buying now simply means the treatment is already in your hands the instant the diagnosis arrives, instead of days later. And if it ever feels like it was not yours, the 90-day guarantee makes the whole thing free to walk away from. You genuinely cannot get this wrong by acting now.</div>
          </details>
          <details>
            <summary>How is this different from the reading I just bought?</summary>
            <div class="faq__a">Your reading is the diagnosis. It names the ceiling and shows you where it lives. The Soul Ritual Practice is the treatment: the written protocol that clears it at the layer where it was set. The reading is Part One. This is Part Two. They are built to work as one.</div>
          </details>
          <details>
            <summary>What if I am not "spiritual enough" for this?</summary>
            <div class="faq__a">You do not need to believe in any of it. These are written steps you read and follow: no chanting, no altar, no faith required. They work on body memory and the nervous system, the same way a slow breath drops your shoulders whether you believe in it or not. Plenty of people start out of pure curiosity and feel something shift anyway. Skeptical is fine. Willing to read three pages is all it takes.</div>
          </details>
          <details>
            <summary>How does access work?</summary>
            <div class="faq__a">The moment your payment confirms, everything downloads instantly: the three Clearing Rituals, the 45-page Workbook, and all three bonuses. Yours to keep for life. No subscription, no expiry. It is charged to the order you just placed, so there is no card to re-enter.</div>
          </details>
          <details>
            <summary>I have already done so much inner work. Why would this be any different?</summary>
            <div class="faq__a">Because everything you have done so far worked at the level of understanding: books, journaling, courses, talking it through. That is real, and it is also why the ceiling kept coming back. Understanding a pattern has never once dissolved it. This is the one piece that works at the layer where the block was actually set, in the body, not the mind. It is not more of what you have tried. It is the step the rest of it was missing.</div>
          </details>
        </div>
      </div>
    </section>

    <!-- FINAL CTA -->
    <section class="section center" id="no-thanks">
      <div class="wrap" style="max-width:620px;">
        <h2>You Have the Diagnosis.<br /><em>Take the Treatment With You.</em></h2>
        <p style="color:var(--text-muted);font-size:18px;line-height:1.7;max-width:580px;margin:0 auto 30px;">The reading names the ceiling on your money. The Soul Ritual Practice is how you lift it. Add it now and all four block-type protocols sit waiting, so the instant your reading names yours, the exact clearing work is already in your hands. These are the two halves of one piece of work, and you have come too far to keep only half.</p>
        <a class="cta" href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>">Yes, Upgrade My Order Now</a>
        <a class="cta-decline" href="<?= htmlspecialchars($downsellPageUrl, ENT_QUOTES, 'UTF-8') ?>">No thank you, continue to my reading &rarr;</a>
        <p class="cta-fine">Your reading arrives within 24 hours either way.</p>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <footer class="footer">
    <p>ClickBank is the retailer of products on this site. CLICKBANK&reg; is a registered trademark of Click Sales, Inc., a Delaware corporation located at 1444 S. Entertainment Ave., Suite 410 Boise, ID 83709, USA and used by permission. ClickBank's role as retailer does not constitute an endorsement, approval or review of these products or any claim, statement or opinion used in promotion of these products.</p>
    <p>For Product Support, please contact the vendor: <a href="mailto:support@soulmirrorreading.com">HERE</a>. For Order Support, please contact ClickBank: <a href="https://www.clkbank.com/" target="_blank" rel="noopener">HERE</a> or 1-800-390-6035</p>
    <p><a href="/privacy-policy">Privacy Policy</a> &nbsp;&middot;&nbsp; <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;&middot;&nbsp; <a href="mailto:support@soulmirrorreading.com">Contact Us</a> &nbsp;&middot;&nbsp; <a href="/refund-return-policy">Refund &amp; Return Policy</a></p>
    <p>The Soul Ritual Practice is a self-guided written practice. Results may vary and are not typical. This is not financial advice and is not a substitute for licensed mental health care.</p>
    <p>&copy; 2026 Soul Mirror Reading, A Luna Ross Brand. All Rights Reserved.</p>
  </footer>

</body>
</html>

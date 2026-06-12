<?php
declare(strict_types=1);

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

\App\Config\AppConfig::load($projectRoot);


$otoCheckoutUrl = 'https://rebornf.pay.clickbank.net/?cbitems=mm-1&cbur=a';
$downsellPageUrl = 'https://rebornf.pay.clickbank.net/?cbitems=mm-1&cbur=d';

?><!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/svg+xml" href="https://soulmirrorreading.com/favicon.svg" />
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Upsell 3 (OTO3) - The Mirror Meditations, 6 Luna-voiced guided sessions to hold the clearing (wealth-v2 cosmic system) -->
  <!-- TODO: create ClickBank one-time items mm-1 ($47) + mm-1-ds ($27); wire audio delivery URLs (AWS) -->
  <title>The Mirror Meditations, In Luna's Own Voice</title>
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
    *{min-width:0}
    html{scroll-behavior:smooth}
    body{background:#0a0716;color:var(--text);font-family:'Inter',system-ui,-apple-system,sans-serif;font-size:18px;line-height:1.78;overflow-x:clip}
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

    h1,h2,h3,h4{font-family:'Cormorant Garamond',Georgia,serif;color:#fff;line-height:1.18;text-wrap:balance}
    h1{font-size:clamp(28px,6vw,44px);font-weight:600;margin-bottom:16px}
    h1 em,h2 em,h3 em{color:var(--gold-light);font-style:italic}
    h2{text-align:center;font-size:clamp(27px,4.5vw,40px);font-weight:600;margin-bottom:22px}
    h3{font-size:22px;font-weight:600;margin-bottom:8px}
    .eyebrow{font-family:'Cinzel',sans-serif;font-size:12px;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);display:block;text-align:center;margin-bottom:14px;font-weight:600}
    .subhead{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:clamp(18px,2.6vw,22px);color:var(--text-muted);text-align:center;max-width:600px;margin:0 auto 8px;line-height:1.55}
    .hero-sub{font-size:clamp(19px,4.6vw,22px);line-height:1.68;color:#e7e2f4;max-width:360px;margin:20px auto 0}
    .lead{text-align:center;max-width:580px;margin:0 auto 26px;color:var(--text-muted);font-size:17px}
    .body-copy{max-width:620px;margin:0 auto;font-size:17px;line-height:1.8;color:var(--text)}
    .body-copy p+p{margin-top:18px}
    .body-copy strong{color:var(--gold-light)}
    .gold-rule{border:none;height:1px;background:linear-gradient(90deg,transparent,var(--gold),transparent);max-width:340px;margin:28px auto}

    .topnotice{position:relative;z-index:2;background:#9b1c1c;border-bottom:1px solid #c0392b;color:#f0dca8;text-align:center;padding:13px 18px;font-family:'Inter',system-ui,sans-serif;font-size:12.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;line-height:1.5}
    .topnotice .dot{color:#f3b0a0;margin:0 9px}

    .cta{background:linear-gradient(180deg,var(--gold-bright) 0%,var(--gold) 100%);color:#1a0d2e;letter-spacing:.16em;text-transform:uppercase;cursor:pointer;text-shadow:0 1px #fff3;border:none;border-radius:50px;padding:20px 40px;font-family:'Cinzel',sans-serif;font-size:clamp(13px,2.2vw,15px);font-weight:700;text-decoration:none;display:inline-block;width:100%;max-width:520px;text-align:center;box-shadow:0 12px 32px #d4af3766,inset 0 1px #fff6;transition:transform .12s cubic-bezier(.34,1.56,.64,1),box-shadow .2s,filter .15s}
    .cta:active{transform:translateY(1px) scale(.985)}
    @media (hover:none){.cta:hover{transform:none}}
    .cta:hover{transform:translateY(-2px);box-shadow:0 16px 40px #d4af3780}
    .cta-decline{display:block;width:100%;margin-top:16px;background:none;border:none;cursor:pointer;text-align:center;color:#cdc6e0;opacity:.7;font-family:'Inter',sans-serif;font-size:14px;text-decoration:underline;text-underline-offset:3px}
    .cta-decline:hover{opacity:1}
    .cta-fine{text-align:center;color:#bdb4d6;font-size:13px;margin-top:12px;line-height:1.6}

    .card{backdrop-filter:blur(4px);background:linear-gradient(#2d1b6980,#1e0d40d9);border:1px solid #d4af3759;border-radius:14px;padding:26px 24px}

    .offer-box{background:linear-gradient(180deg,var(--violet-mid) 0%,var(--violet) 100%);color:#fff;text-align:center;border:1px solid #d4af374d;border-radius:16px;padding:34px 28px;position:relative;overflow:hidden;box-shadow:0 12px 40px #0006}
    .offer-box::before{content:"";pointer-events:none;background:radial-gradient(at 50% 0,#d4af372e 0%,#0000 60%);position:absolute;inset:0}
    .offer-box>*{position:relative;z-index:1}
    .offer-label{font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-light);margin-bottom:12px;display:block}

    .vlist{text-align:left;max-width:500px;margin:0 auto;padding:0;list-style:none}
    .vlist li{color:var(--text);border-bottom:1px solid #d4af3726;display:flex;justify-content:space-between;align-items:baseline;gap:14px;padding:12px 0;font-size:16px;line-height:1.45}
    .vlist li:last-child{border-bottom:none}
    .vlist .vs-name{display:block;position:relative;padding-left:22px}
    .vlist .vs-name::before{content:"✦";color:var(--gold);position:absolute;left:0;top:1px;font-size:13px}
    .vlist .vs-price{font-family:'Cinzel',sans-serif;font-size:13px;color:var(--gold-light);opacity:.7;text-decoration:line-through;flex-shrink:0;white-space:nowrap}
    .vlist strong{color:var(--gold-light)}
    .vlist .vs-total{margin-top:6px;padding-top:14px;border-top:1px solid #d4af3759;border-bottom:none}
    .vlist .vs-total .vs-name{font-family:'Cormorant Garamond',serif;font-size:19px;font-weight:600;color:#fff}
    .vlist .vs-total .vs-name::before{content:""}
    .vlist .vs-total .vs-price{font-size:15px;text-decoration:none;opacity:1}

    .pricing{margin:22px 0 10px}
    .price-label{font-family:'Cinzel',sans-serif;font-size:12px;letter-spacing:.16em;text-transform:uppercase;color:var(--gold);display:block;margin-bottom:8px}
    .price-row{display:flex;justify-content:center;align-items:baseline;gap:18px;margin:6px 0 12px}
    .price-old{color:#ffffff73;text-decoration:line-through;text-decoration-color:var(--gold);font-family:'Cinzel',sans-serif;font-size:24px}
    .price-new{color:var(--gold-light);text-shadow:0 0 24px #d4af3759;font-family:'Cormorant Garamond',serif;font-size:clamp(58px,12vw,72px);font-weight:600;line-height:1}
    .price-new .cur{vertical-align:super;font-size:.42em;margin-right:3px;font-weight:500}
    .price-note{color:#fff9;font-size:14px;font-style:italic;line-height:1.65;max-width:520px;margin:0 auto}
    .founding-blink{animation:foundingBlink 1.05s ease-in-out infinite;color:#E8C97A;font-weight:600}
    @keyframes foundingBlink{0%,100%{opacity:1}50%{opacity:.35}}

    /* RITUALS -> MEDITATIONS journey */
    .part-grid{display:grid;grid-template-columns:1fr auto 1fr;gap:16px;align-items:stretch;max-width:560px;margin:30px auto 0}
    .part-col{padding:22px 16px;border:1px solid #d4af3740;border-radius:12px;background:#1e0d4099;text-align:center}
    .part-col--done{border-color:#7ee0a04d;background:#1e0d40b8}
    .part-num{font-family:'Cinzel',sans-serif;font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:var(--gold);display:block;margin-bottom:8px}
    .part-title{font-family:'Cormorant Garamond',serif;font-size:19px;font-weight:600;color:#fff;margin-bottom:6px}
    .part-status{font-size:13px;color:var(--text-muted);font-style:italic}
    .part-status--done{color:#7ee0a0;font-style:normal}
    .part-arrow{display:flex;align-items:center;justify-content:center;color:var(--gold);font-size:22px}
    .part-ico{width:48px;height:48px;margin:0 auto 14px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:1px solid #ffffff14}
    .part-ico svg{width:24px;height:24px;display:block}
    .part-ico--done{background:#7ee0a01a;border-color:#7ee0a045;color:#7ee0a0}
    .part-ico--active{background:radial-gradient(circle at 50% 32%,#d4af3736,#2d1b69cc);border-color:#d4af3773;color:var(--gold-light);box-shadow:0 0 20px #d4af3736,inset 0 1px 0 #ffffff1f}
    .part-col--active{border-color:var(--gold);background:#2d1b69b3;animation:needsGlow 2.2s ease-in-out infinite}
    .part-col--active .part-status{color:var(--gold-light);font-style:normal}
    @keyframes needsGlow{0%,100%{box-shadow:0 0 16px rgba(212,175,55,.3),inset 0 0 14px rgba(212,175,55,.1)}50%{box-shadow:0 0 34px rgba(212,175,55,.62),inset 0 0 22px rgba(212,175,55,.2)}}

    /* TRACK GRID: dawn-to-dusk play console */
    .mirror{margin:0 auto 18px;max-width:600px;border:1px solid #d4af3740;border-radius:18px;background:linear-gradient(180deg,#241141cc 0%,#160a30e6 100%);backdrop-filter:blur(6px);overflow:hidden;box-shadow:0 14px 40px #0005,inset 0 1px 0 #ffffff0d;transition:border-color .25s ease,box-shadow .25s ease}
    .mirror:hover{border-color:#d4af3773;box-shadow:0 18px 52px #0007,0 0 0 1px #d4af3722,inset 0 1px 0 #ffffff14}
    .mirror__head{position:relative;font-family:'Cinzel',sans-serif;font-size:13px;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);text-align:center;padding:16px 56px 14px;border-bottom:1px solid #d4af3726}
    .mirror__head::after{content:"";position:absolute;left:50%;bottom:-1px;transform:translateX(-50%);width:64px;height:1px;background:linear-gradient(90deg,transparent,var(--gold),transparent)}
    .mirror__num{position:absolute;left:16px;top:50%;transform:translateY(-50%);width:26px;height:26px;border:1px solid #d4af3759;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;color:var(--gold-light);background:#d4af3712}
    .mirror__row{display:grid;grid-template-columns:1fr 1fr;gap:0;position:relative}
    .mirror__row::before{content:"";position:absolute;top:14px;bottom:14px;left:50%;width:1px;background:linear-gradient(180deg,transparent,#d4af3733 18%,#d4af3733 82%,transparent)}

    .trk{position:relative;padding:18px 17px;display:flex;gap:13px;align-items:flex-start;cursor:default;transition:background .25s ease}
    .trk--morning{background:linear-gradient(180deg,#3a2a0c1f 0%,transparent 70%)}
    .trk--night{background:linear-gradient(180deg,#150a3526 0%,transparent 70%)}
    .trk--morning:hover{background:linear-gradient(180deg,#5a3f0f30 0%,#3a2a0c10 75%)}
    .trk--night:hover{background:linear-gradient(180deg,#24145240 0%,#150a3514 75%)}

    .trk__play{position:relative;flex-shrink:0;width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:transform .2s cubic-bezier(.34,1.56,.64,1),box-shadow .25s ease}
    .trk--morning .trk__play{background:radial-gradient(circle at 35% 30%,#f4dca0,#d4af37);box-shadow:0 4px 14px #d4af3759,inset 0 1px 0 #fff7}
    .trk--night .trk__play{background:radial-gradient(circle at 35% 30%,#3a2a6e,#1a0f3c);border:1px solid #d4af3766;box-shadow:0 4px 14px #0006,inset 0 1px 0 #ffffff1a}
    .trk:hover .trk__play{transform:scale(1.07)}
    .trk--morning:hover .trk__play{box-shadow:0 6px 20px #d4af3780,inset 0 1px 0 #fff8}
    .trk--night:hover .trk__play{box-shadow:0 6px 20px #d4af3740,0 0 0 1px #d4af3759,inset 0 1px 0 #ffffff26}
    .trk__play svg{width:20px;height:20px;display:block}
    .trk--morning .trk__play svg{fill:#3a2407}
    .trk--night .trk__play svg{fill:var(--gold-light)}
    .trk__tri{position:absolute;right:-3px;bottom:-3px;width:15px;height:15px;border-radius:50%;background:#0e0820;border:1px solid #d4af3759;display:flex;align-items:center;justify-content:center;z-index:1}
    .trk__tri svg{width:6px;height:6px;fill:var(--gold-light)}

    .trk__body{min-width:0}
    .trk__when{font-family:'Cinzel',sans-serif;font-size:10px;letter-spacing:.16em;text-transform:uppercase;display:flex;align-items:center;gap:7px;margin-bottom:4px}
    .trk--morning .trk__when{color:var(--gold-light)}
    .trk--night .trk__when{color:#b9a7e0}
    .trk__when .dot{width:3px;height:3px;border-radius:50%;background:currentColor;opacity:.55}
    .trk__min{font-family:'Cinzel',sans-serif;letter-spacing:.08em;opacity:.7}
    .trk__name{font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:600;color:#fff;line-height:1.18;margin-bottom:5px}
    .trk__desc{font-size:13.5px;line-height:1.5;color:var(--text-muted)}
    @media(max-width:560px){
      .mirror__row{grid-template-columns:1fr}
      .mirror__row::before{display:none}
      .trk{border-bottom:1px solid #d4af3720}
      .trk--night{border-bottom:none}
      .trk__play{width:40px;height:40px}
    }

    .stack{display:flex;flex-direction:column;gap:16px;max-width:620px;margin:0 auto}
    .mv{display:flex;gap:20px;align-items:flex-start}
    .mv__num{font-family:'Cinzel',sans-serif;font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:var(--gold);font-weight:600;min-width:130px;flex-shrink:0;padding-top:2px}
    .mv__body p{font-size:16px;line-height:1.7;color:var(--text-muted)}

    .luna-sig{margin-top:24px;padding-top:20px;border-top:1px solid #d4af3733;font-family:'Cormorant Garamond',serif;font-style:italic;font-size:18px;color:var(--gold-light);text-align:center}

    .testi{backdrop-filter:blur(4px);background:#ffffff0f;border:1px solid #d4af374d;border-radius:14px;max-width:620px;margin:0 auto 16px;padding:26px 26px}
    .testi__row{display:flex;align-items:center;gap:14px;margin-bottom:14px}
    .testi__avatar{width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid #d4af3780;flex-shrink:0}
    .testi__name{font-family:'Cinzel',sans-serif;font-size:14px;font-weight:700;color:#fff;letter-spacing:.04em}
    .testi__meta{font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.08em;color:var(--gold-light);margin-top:3px}
    .testi__stars{color:var(--gold);font-size:14px;letter-spacing:2px;margin-bottom:8px}
    .testi__body{font-size:16px;font-style:italic;line-height:1.7;color:var(--text)}

    .founding-panel{border:1.5px solid #d4af3799;border-radius:14px;background:linear-gradient(135deg,rgba(212,175,55,.13),rgba(212,175,55,.05));max-width:480px;margin:24px auto 6px;padding:22px 26px;text-align:center}
    .founding-panel .founding-head{font-family:'Cinzel',sans-serif;font-size:12px;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);display:block;margin-bottom:10px;font-weight:700}
    .founding-panel p{font-size:15px;line-height:1.65;color:#e9e2f2;margin:0}
    .founding-panel strong{color:var(--gold-light)}

    .footer{position:relative;z-index:1;background:#0e0820;border-top:1px solid #d4af3726;padding:28px 24px;text-align:center}
    .footer p{font-size:12px;color:#9a93b3;line-height:1.6;max-width:760px;margin:0 auto 8px}
    .footer a{color:#b7afd0;text-decoration:underline;text-underline-offset:2px}

    .sticky-cta{position:fixed;left:0;right:0;bottom:0;z-index:40;display:none;padding:10px 14px calc(10px + env(safe-area-inset-bottom));background:linear-gradient(180deg,#1e0d40e6,#0e0820f2);border-top:1px solid #d4af3759;backdrop-filter:blur(6px)}
    .sticky-cta a{display:block;width:100%;max-width:520px;margin:0 auto;border-radius:50px;padding:14px 20px;font-family:'Cinzel',sans-serif;font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;text-align:center;text-decoration:none;color:#1a0d2e;background:linear-gradient(180deg,var(--gold-bright),var(--gold));box-shadow:0 8px 22px #d4af3766}
    @media(max-width:640px){.sticky-cta.show{display:block}}

    @media (max-width:640px){
      .section{padding:40px 0}.section--tight{padding:24px 0}.wrap{padding:0 20px}
      .part-grid{grid-template-columns:1fr;gap:12px}.part-arrow{transform:rotate(90deg);padding:2px 0}
      .mv{flex-direction:column;gap:8px}.mv__num{min-width:auto}
      .offer-box{padding:28px 20px}
      .topnotice{font-size:11px;letter-spacing:.04em;padding:11px 14px;white-space:normal}
      .cta{padding:18px 22px;letter-spacing:.07em;font-size:14px;line-height:1.2}
      .subhead,.lead,.guarantee p{max-width:340px}
      .cta-decline{margin-top:20px;min-height:44px;padding:12px 8px;line-height:1.4}
    }
  </style>
</head>
<body>

  <!-- 1. NOTICE BAR -->
  <div class="topnotice">
    <strong>One Last Thing</strong> <span class="dot">&middot;</span> <strong>Members-Only</strong> <span class="dot">&middot;</span> <strong>You Will Not See This Page Again</strong>
  </div>

  <div class="dream-bg" aria-hidden="true">
    <div class="dream-veil"></div><div class="milky-way"></div>
    <div class="dream-orb one"></div><div class="dream-orb two"></div><div class="dream-orb three"></div>
  </div>

  <main>

    <!-- 2. HERO -->
    <section class="section center">
      <div class="wrap">
        <div style="text-align:center;margin:0 auto 22px;">
          <img src="frontend/images/upsell3/luna-avatar.webp" alt="Luna Ross" onerror="this.style.visibility='hidden'" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #d4af37;box-shadow:0 0 0 6px rgba(212,175,55,0.12),0 10px 34px rgba(0,0,0,0.5);">
        </div>
        <span class="eyebrow">Recorded In Luna's Own Voice</span>
        <h1>You Did the Hard Part. Now Let's Make It <em>Unbreakable.</em></h1>
        <p class="subhead hero-sub">Your rituals cleared the block. These wall it in, morning and night, until the clearing is fortified and the old pattern cannot find one crack to slip back through.</p>
      </div>
    </section>

    <!-- 3. THE PROBLEM -->
    <section class="section section--tight">
      <div class="wrap">
        <div class="body-copy">
          <p>You did the clearing. You moved the thing that held you for years. That was the hard part, and it is done.</p>
          <p>But I have watched this too often. The old voice waits for a tired morning, a tight week, a moment when money, love, or work gets close, and tries to slip back in. <strong>The clearing does not fail. It just needs walls around it, so the old pattern meets stone where it once found an open door.</strong></p>
        </div>
        <div class="part-grid">
          <div class="part-col part-col--done">
            <div class="part-ico part-ico--done"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></div>
            <span class="part-num">Your Rituals</span>
            <div class="part-title">The Block, Cleared</div>
            <div class="part-status part-status--done">&#10003; Done</div>
          </div>
          <div class="part-arrow">&#10230;</div>
          <div class="part-col part-col--active">
            <div class="part-ico part-ico--active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.5l7.5 2.8v6.2c0 4.8-3.3 8-7.5 10.5-4.2-2.5-7.5-5.7-7.5-10.5V5.3z"/><path d="M9 11.8l2.1 2.1 4-4.1"/></svg></div>
            <span class="part-num">The Meditations</span>
            <div class="part-title">Seals It In</div>
            <div class="part-status">Stronger every day</div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. INTRODUCE THE PRODUCT -->
    <section class="section">
      <div class="wrap">
        <img src="frontend/images/upsell3/mirror-meditations-hero.webp" alt="The Mirror Meditations, guided sessions in Luna's voice" onerror="this.style.display='none'" style="display:block;width:100%;max-width:600px;margin:0 auto 30px;border-radius:14px;border:1px solid rgba(212,175,55,0.35);box-shadow:0 14px 44px rgba(0,0,0,0.5);">
        <h2>The Mirror Meditations</h2>
        <p class="subhead">Six guided sessions in my own voice. One to open each morning, one to settle each night.</p>
        <div class="body-copy" style="margin-top:24px;">
          <p>I recorded these myself, slowly and quietly, the way I would sit with you if you were here. Six short sessions, two for each of your three mirrors: <strong>money, love, and purpose.</strong> A morning session to start the day already clear, and a night session to let the new imprint settle deeper while you sleep.</p>
          <p>You do not have to think, or journal, or get anything right. You lie down, you press play, and you let my voice fortify what the rituals began.</p>
        </div>
      </div>
    </section>

    <!-- EARLY OFFER -->
    <section class="section section--tight" id="offer-early">
      <div class="wrap" style="max-width:540px;">
        <div class="offer-box" style="box-shadow:0 6px 20px #0004;border-color:#d4af3733;">
          <h2 style="margin-bottom:14px;">Add The Mirror Meditations</h2>
          <p style="color:#e9e2f2;font-size:17px;line-height:1.6;max-width:430px;margin:0 auto 20px;">Six guided sessions in Luna's voice, <strong style="color:var(--gold-light);">a $144 value</strong>, yours to keep for life.</p>
          <div class="price-row" style="margin-bottom:2px;">
            <span class="price-old">$144</span>
            <span class="price-new"><span class="cur">$</span>47</span>
          </div>
          <p class="price-note" style="margin:0 auto 22px;">One payment. Instant access. <strong style="color:var(--gold-light);">Yours to keep for life.</strong></p>
          <div style="text-align:center;margin:2px auto 18px;"><span style="display:inline-block;font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-light);border:1px solid #d4af3759;border-radius:50px;padding:7px 18px;">&#10003;&nbsp; 90-Day Money-Back Guarantee</span></div>
          <a class="cta" href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>">Yes, Upgrade My Order Now</a>
          <a class="cta-decline" href="<?= htmlspecialchars($downsellPageUrl, ENT_QUOTES, 'UTF-8') ?>">No thank you, continue without them.</a>
        </div>
      </div>
    </section>

    <!-- 5. WHAT IS INSIDE (the 6 tracks) -->
    <section class="section">
      <div class="wrap">
        <h2>Your Six Sessions</h2>
        <p class="lead">Two for each mirror. Open the day, then settle the night.</p>

        <svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>
          <symbol id="ico-sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4.4"/><g><rect x="11" y="1.2" width="2" height="3.4" rx="1"/><rect x="11" y="19.4" width="2" height="3.4" rx="1"/><rect x="1.2" y="11" width="3.4" height="2" rx="1"/><rect x="19.4" y="11" width="3.4" height="2" rx="1"/><rect x="3.6" y="3.6" width="2" height="3.4" rx="1" transform="rotate(-45 4.6 5.3)"/><rect x="18.4" y="17" width="2" height="3.4" rx="1" transform="rotate(-45 19.4 18.7)"/><rect x="17" y="3.6" width="2" height="3.4" rx="1" transform="rotate(45 18 5.3)"/><rect x="3.6" y="17" width="2" height="3.4" rx="1" transform="rotate(45 4.6 18.7)"/></g></symbol>
          <symbol id="ico-moon" viewBox="0 0 24 24"><path d="M15.2 3.1a8.4 8.4 0 1 0 5.9 12.2A6.6 6.6 0 0 1 15.2 3.1z"/><circle cx="18.4" cy="6.1" r="1"/><circle cx="20" cy="9.8" r=".7"/><circle cx="16.3" cy="9" r=".6"/></symbol>
          <symbol id="ico-play" viewBox="0 0 12 12"><path d="M3 2.2v7.6L9.5 6z"/></symbol>
        </defs></svg>

        <div class="mirror">
          <div class="mirror__head"><span class="mirror__num">1</span>The Money Mirror</div>
          <div class="mirror__row">
            <div class="trk trk--morning"><span class="trk__play"><svg><use href="#ico-sun"/></svg><span class="trk__tri"><svg><use href="#ico-play"/></svg></span></span><div class="trk__body"><span class="trk__when">Morning</span><div class="trk__name">Open to Receive</div><div class="trk__desc">Begin the day open to money, before the old ceiling can take hold.</div></div></div>
            <div class="trk trk--night"><span class="trk__play"><svg><use href="#ico-moon"/></svg><span class="trk__tri"><svg><use href="#ico-play"/></svg></span></span><div class="trk__body"><span class="trk__when">Night</span><div class="trk__name">Release the Ceiling</div><div class="trk__desc">Let the day's money fear go, and let the new imprint set as you sleep.</div></div></div>
          </div>
        </div>

        <div class="mirror">
          <div class="mirror__head"><span class="mirror__num">2</span>The Love Mirror</div>
          <div class="mirror__row">
            <div class="trk trk--morning"><span class="trk__play"><svg><use href="#ico-sun"/></svg><span class="trk__tri"><svg><use href="#ico-play"/></svg></span></span><div class="trk__body"><span class="trk__when">Morning</span><div class="trk__name">Open Heart</div><div class="trk__desc">Lower the guard and let love in, just for today.</div></div></div>
            <div class="trk trk--night"><span class="trk__play"><svg><use href="#ico-moon"/></svg><span class="trk__tri"><svg><use href="#ico-play"/></svg></span></span><div class="trk__body"><span class="trk__when">Night</span><div class="trk__name">Lay Down the Watch</div><div class="trk__desc">Release the bracing and the watching for cracks, and finally rest.</div></div></div>
          </div>
        </div>

        <div class="mirror">
          <div class="mirror__head"><span class="mirror__num">3</span>The Purpose Mirror</div>
          <div class="mirror__row">
            <div class="trk trk--morning"><span class="trk__play"><svg><use href="#ico-sun"/></svg><span class="trk__tri"><svg><use href="#ico-play"/></svg></span></span><div class="trk__body"><span class="trk__when">Morning</span><div class="trk__name">Begin</div><div class="trk__desc">Quiet the "not ready yet" voice and take one real step.</div></div></div>
            <div class="trk trk--night"><span class="trk__play"><svg><use href="#ico-moon"/></svg><span class="trk__tri"><svg><use href="#ico-play"/></svg></span></span><div class="trk__body"><span class="trk__when">Night</span><div class="trk__name">Lay It Down</div><div class="trk__desc">Release the day's doubt, and let clarity form overnight.</div></div></div>
          </div>
        </div>

        <p class="subhead" style="margin:24px auto 0;max-width:560px;">Clear it once with the rituals. Then every morning open it, and every night let it set, stone laid on stone, until the clearing is not something you hold. It is a wall nothing can move, and it is simply who you are.</p>
      </div>
    </section>

    <!-- 6. HOW IT WORKS -->
    <section class="section">
      <div class="wrap">
        <h2>As Simple As Pressing Play.</h2>
        <hr class="gold-rule" style="margin:14px auto 26px;" />
        <div class="stack">
          <div class="card mv"><div class="mv__num">In the Morning</div><div class="mv__body"><p>Before the day starts, play that mirror's morning session. A few quiet minutes, and you begin the day already clear instead of bracing.</p></div></div>
          <div class="card mv"><div class="mv__num">At Night</div><div class="mv__body"><p>Lying in bed, press play on the night session and let it carry you down. Your body integrates the clearing while you sleep.</p></div></div>
          <div class="card mv"><div class="mv__num">Yours For Life</div><div class="mv__body"><p>Download them, keep them, return to whichever mirror is loud that week. No app, no limit, no monthly fee.</p></div></div>
        </div>
      </div>
    </section>

    <!-- 8. CLOSE + GUARANTEE -->
    <section class="section center">
      <div class="wrap" style="max-width:620px;">
        <h2>Don't Let the Clearing <em>Quietly Reset.</em></h2>
        <p style="color:var(--text-muted);font-size:18px;line-height:1.7;max-width:580px;margin:0 auto 22px;">You moved something most people never reach. These sessions are how you build the wall around it, morning and night, until it is sealed in for good. A few quiet minutes, in my voice, and the old pattern never finds its way back in.</p>
        <div style="max-width:620px;margin:30px auto 0;text-align:center;border:1px solid #d4af3759;border-radius:16px;background:#1e0d4080;backdrop-filter:blur(4px);padding:38px 32px;">
          <img src="https://soulmirrorreading.com/frontend/images/upsell2/guarantee-badge.webp" alt="90-Day Guarantee Badge" onerror="this.style.display='none'" style="width:120px;height:auto;margin:0 auto 14px;display:block;filter:drop-shadow(0 10px 20px #d4af3740);" />
          <h2 style="font-size:clamp(20px,3vw,26px);margin-bottom:14px;">The Mirror Meditations, 90-Day Guarantee</h2>
          <p style="font-size:17px;line-height:1.7;color:var(--text-muted);max-width:540px;margin:0 auto;">Play the sessions for a real 90 days, morning and night. If the old pattern creeps back and nothing feels more sealed, reply and I refund every cent of your $47. No questionnaire, no proof. You keep all six sessions and the bonus, no matter what.</p>
        </div>
      </div>
    </section>

    <!-- 9. MAIN OFFER -->
    <section class="section" id="offer">
      <div class="wrap" style="max-width:620px;">
        <div class="offer-box" style="border-width:1.5px;box-shadow:0 18px 56px #0008,0 0 0 1px #d4af3726;">
          <span class="offer-label">The Fast Track to Holding Your Clearing</span>
          <h2 style="margin-bottom:10px;">The Mirror Meditations</h2>
          <p style="color:#cfc7e6;font-style:italic;font-family:'Cormorant Garamond',serif;font-size:20px;margin-bottom:22px;">Six guided sessions in Luna's own voice. Yours to keep for life.</p>

          <ul class="vlist">
            <li><span class="vs-name">Money Mirror, <strong>Open to Receive</strong> (morning) + <strong>Release the Ceiling</strong> (night)</span><span class="vs-price">$40</span></li>
            <li><span class="vs-name">Love Mirror, <strong>Open Heart</strong> (morning) + <strong>Lay Down the Watch</strong> (night)</span><span class="vs-price">$40</span></li>
            <li><span class="vs-name">Purpose Mirror, <strong>Begin</strong> (morning) + <strong>Lay It Down</strong> (night)</span><span class="vs-price">$40</span></li>
            <li><span class="vs-name"><strong>Bonus:</strong> The 60-Second Reset, an emergency clearing for when the pattern flares mid-day</span><span class="vs-price">$24</span></li>
            <li class="vs-total"><span class="vs-name">Total Value</span><span class="vs-price">$144</span></li>
          </ul>

          <div class="founding-panel">
            <span class="founding-head">✦ &nbsp; Members-Only Price &nbsp; ✦</span>
            <p>Available on this page only, as part of the order you just placed. <strong>One payment, yours to keep for life.</strong></p>
          </div>

          <div class="pricing">
            <span class="price-label">Today Only</span>
            <div class="price-row">
              <span class="price-old">$144</span>
              <span class="price-new"><span class="cur">$</span>47</span>
            </div>
            <p class="price-note">One payment. Instant access. Yours to keep for life.</p>
          </div>

          <div style="text-align:center;margin:2px auto 18px;"><span style="display:inline-block;font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-light);border:1px solid #d4af3759;border-radius:50px;padding:7px 18px;">&#10003;&nbsp; 90-Day Money-Back Guarantee</span></div>

          <a class="cta" href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>">Yes, Upgrade My Order Now</a>
          <a class="cta-decline" href="<?= htmlspecialchars($downsellPageUrl, ENT_QUOTES, 'UTF-8') ?>">No thank you, complete my order without them.</a>
          <p style="text-align:center;margin-top:14px;font-size:12px;color:#9a93b3;letter-spacing:.04em;">&#128274; This adds to the order you just placed. No card to re-enter. Secure checkout by ClickBank.</p>
        </div>
      </div>
    </section>

    <div class="sticky-cta" id="stickyCta"><a href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>">Yes, Upgrade My Order Now &nbsp;&middot;&nbsp; $47</a></div>
  </main>

  <footer class="footer">
    <p>ClickBank is the retailer of products on this site. CLICKBANK&reg; is a registered trademark of Click Sales, Inc., a Delaware corporation located at 1444 S. Entertainment Ave., Suite 410 Boise, ID 83709, USA and used by permission. ClickBank's role as retailer does not constitute an endorsement, approval or review of these products or any claim, statement or opinion used in promotion of these products.</p>
    <p>For Product Support, please contact the vendor: <a href="mailto:support@soulmirrorreading.com">HERE</a>. For Order Support, please contact ClickBank: <a href="https://www.clkbank.com/" target="_blank" rel="noopener">HERE</a> or 1-800-390-6035</p>
    <p><a href="/privacy-policy">Privacy Policy</a> &nbsp;&middot;&nbsp; <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;&middot;&nbsp; <a href="mailto:support@soulmirrorreading.com">Contact Us</a> &nbsp;&middot;&nbsp; <a href="/refund-return-policy">Refund &amp; Return Policy</a></p>
    <p>The Mirror Meditations are guided audio sessions for guidance and reflection. They are not financial, medical, or professional advice. Results vary.</p>
    <p>&copy; 2026 Soul Mirror Reading, A Luna Ross Brand. All Rights Reserved.</p>
  </footer>

  <script>(function(){var ec=document.getElementById('offer-early'),sc=document.getElementById('stickyCta');if(!ec||!sc)return;addEventListener('scroll',function(){sc.classList.toggle('show',ec.getBoundingClientRect().bottom<0)},{passive:true});})();</script>
</body>
</html>

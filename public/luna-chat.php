<?php
declare(strict_types=1);

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

\App\Config\AppConfig::load($projectRoot);


$otoCheckoutUrl = 'https://rebornf.pay.clickbank.net/?cbitems=ic-1&template=1on1';
$downsellPageUrl = 'https://rebornf.pay.clickbank.net/?cbitems=ic-1&template=1on1';

?><!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/svg+xml" href="https://soulmirrorreading.com/favicon.svg" />
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Upsell 3 (OTO3) - The Inner Circle, unlimited private 1-1 sessions membership (wealth-v2 cosmic system) -->
  <!-- ic-1 ClickBank recurring item is LIVE/Active: $37 first month, then $19/month rebill -->
  <!-- TODO: wire $otoCheckoutUrl / Thank-You-URL -> inner-circle.php access page -->
  <title>The Inner Circle, Your Own Private Line to Luna Ross</title>
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
    h1,h2,h3,h4{font-family:'Cormorant Garamond',Georgia,serif;color:#fff;line-height:1.18;text-wrap:balance}
    h1{font-size:clamp(28px,6vw,44px);font-weight:600;margin-bottom:16px}
    h1 em,h2 em,h3 em{color:var(--gold-light);font-style:italic}
    h2{text-align:center;font-size:clamp(27px,4.5vw,40px);font-weight:600;margin-bottom:22px}
    h3{font-size:22px;font-weight:600;margin-bottom:8px}
    .eyebrow{font-family:'Cinzel',sans-serif;font-size:12px;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);display:block;text-align:center;margin-bottom:14px;font-weight:600}
    .subhead{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:clamp(18px,2.6vw,22px);color:var(--text-muted);text-align:center;max-width:600px;margin:0 auto 8px;line-height:1.55}
    .hero-sub{font-size:clamp(19px,4.6vw,22px);line-height:1.68;color:#e7e2f4;max-width:340px;margin:20px auto 0}
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
    .cta{background:linear-gradient(180deg,var(--gold-bright) 0%,var(--gold) 100%);color:#1a0d2e;letter-spacing:.16em;text-transform:uppercase;cursor:pointer;text-shadow:0 1px #fff3;border:none;border-radius:50px;padding:20px 40px;font-family:'Cinzel',sans-serif;font-size:clamp(13px,2.2vw,15px);font-weight:700;text-decoration:none;display:inline-block;width:100%;max-width:520px;text-align:center;box-shadow:0 12px 32px #d4af3766,inset 0 1px #fff6;transition:transform .12s cubic-bezier(.34,1.56,.64,1),box-shadow .2s,filter .15s}
    .cta:active{transform:translateY(1px) scale(.985);box-shadow:0 6px 18px #d4af3759,inset 0 1px #fff6;filter:brightness(1.04)}
    .cta:focus-visible{outline:none;box-shadow:0 12px 32px #d4af3766,inset 0 1px #fff6,0 0 0 3px #0a0716,0 0 0 6px var(--gold-light)}
    .cta-decline:focus-visible,.footer a:focus-visible{outline:2px solid var(--gold-light);outline-offset:3px;border-radius:3px;opacity:1}
    @media (hover:none){.cta:hover{transform:none}}
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
    .vlist .vs-name{display:block;position:relative;padding-left:22px}
    .vlist .vs-name::before{content:"✦";color:var(--gold);position:absolute;left:0;top:1px;font-size:13px}
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
    .part-col--active{border-color:var(--gold);background:#2d1b69b3;animation:needsGlow 2.2s ease-in-out infinite}
    .part-col--active .part-status{color:var(--gold-light);font-style:normal}
    @keyframes needsGlow{0%,100%{box-shadow:0 0 16px rgba(212,175,55,.3),inset 0 0 14px rgba(212,175,55,.1)}50%{box-shadow:0 0 34px rgba(212,175,55,.62),inset 0 0 22px rgba(212,175,55,.2)}}
    @media (prefers-reduced-motion:reduce){.part-col--active{animation:none;box-shadow:0 0 22px rgba(212,175,55,.45)}}

    /* MOVEMENT / BONUS CARDS */
    .stack{display:flex;flex-direction:column;gap:16px;max-width:620px;margin:0 auto}
    .mv{display:flex;gap:20px;align-items:flex-start}
    .mv__num{font-family:'Cinzel',sans-serif;font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:var(--gold);font-weight:600;min-width:118px;flex-shrink:0;padding-top:2px}
    .mv__body p{font-size:16px;line-height:1.7;color:var(--text-muted)}
    .bonus{position:relative;display:flex;gap:20px;align-items:center}
    .bonus-img{width:130px;flex-shrink:0;border-radius:10px;display:block;filter:drop-shadow(0 10px 22px rgba(0,0,0,.45))}
    .bonus__content{flex:1}
    .bonus__badge{position:absolute;top:-12px;left:22px;background:linear-gradient(135deg,#d4af37,#b8941f);color:#1a0d40;font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.1em;text-transform:uppercase;font-weight:700;padding:5px 14px;border-radius:14px;box-shadow:0 3px 10px #d4af374d}
    .bonus h3{color:var(--gold-light);font-size:20px;margin:6px 0 8px}
    .bonus p{font-size:16px;line-height:1.65;color:var(--text-muted)}
    .bonus .val{font-family:'Cinzel',sans-serif;font-size:13px;letter-spacing:.05em;color:var(--gold-light);margin-top:10px;display:block}
    @media(max-width:640px){.bonus{flex-direction:column;text-align:center}.bonus-img{width:160px}.bonus__badge{left:50%;transform:translateX(-50%)}}

    /* LUNA */
    .luna-card{max-width:680px;margin:0 auto}
    .luna-card .body-copy{max-width:none}
    .luna-sig{margin-top:24px;padding-top:20px;border-top:1px solid #d4af3733;font-family:'Cormorant Garamond',serif;font-style:italic;font-size:18px;color:var(--gold-light)}

    /* TESTIMONIALS (matches .testi-card) */
    .testi{backdrop-filter:blur(4px);background:#ffffff0f;border:1px solid #d4af374d;border-radius:14px;max-width:620px;margin:0 auto 16px;padding:26px 26px}
    .testi__row{display:flex;align-items:center;gap:14px;margin-bottom:14px}
    .testi__avatar{width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid #d4af3780;flex-shrink:0}
    .testi__mono{width:56px;height:56px;flex-shrink:0;border-radius:50%;border:2px solid #d4af3780;background:radial-gradient(circle at 50% 38%,#3b1f6e,#1e0d40);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:24px;color:var(--gold-light)}
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
      .wrap{padding:0 20px}
      .subhead,.lead,.body-copy,.price-note,.cta-fine,.ask-list li,.testi__body,.guarantee p,.founding-panel p,.offer-box p,h1,h2{overflow-wrap:break-word}
      .subhead,.lead,.guarantee p{max-width:340px}
      h1,h2{letter-spacing:0}
      .topnotice{font-size:11px;letter-spacing:.04em;padding:11px 14px;line-height:1.5;white-space:normal}
      .topnotice strong{white-space:nowrap}
      .topnotice .dot{margin:0 5px}
      .cta{padding:18px 22px;letter-spacing:.07em;font-size:14px;line-height:1.2}
      .section{padding:36px 0}
      .section--tight{padding:24px 0}
      .ask-list li{padding:14px 16px 14px 34px;font-size:16px;line-height:1.5}
      .ask-list li::before{left:13px;top:15px}
      .eyebrow{letter-spacing:.14em;font-size:11px}
      .cta-decline{display:block;width:100%;margin-top:22px;min-height:44px;padding:12px 8px;line-height:1.4}
    }
    @media (max-width:400px){.topnotice{font-size:10px}}

    /* OTO3 additions: ask-list + founding panel (cosmic system, same tokens) */
    .ask-list{list-style:none;margin:0 auto;padding:0;max-width:600px}
    .ask-list li{position:relative;padding:16px 18px 16px 38px;margin-bottom:12px;border:1px solid #d4af3733;border-radius:12px;background:#1e0d4080;font-family:'Cormorant Garamond',serif;font-style:italic;font-size:18px;line-height:1.55;color:var(--text)}
    .ask-list li::before{content:"✦";position:absolute;left:15px;top:16px;color:var(--gold);font-style:normal;font-size:13px}
    .founding-panel{border:1.5px solid #d4af3799;border-radius:14px;background:linear-gradient(135deg,rgba(212,175,55,.13),rgba(212,175,55,.05));max-width:480px;margin:24px auto 6px;padding:22px 26px;text-align:center}
    .founding-panel .founding-head{font-family:'Cinzel',sans-serif;font-size:12px;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);display:block;margin-bottom:10px;font-weight:700}
    .founding-panel p{font-size:15px;line-height:1.65;color:#e9e2f2;margin:0}
    .founding-panel strong{color:var(--gold-light)}

    /* STICKY BUY BAR (mobile only) */
    .sticky-cta{position:fixed;left:0;right:0;bottom:0;z-index:40;display:none;padding:10px 14px calc(10px + env(safe-area-inset-bottom));background:linear-gradient(180deg,#1e0d40e6,#0e0820f2);border-top:1px solid #d4af3759;backdrop-filter:blur(6px)}
    .sticky-cta a{display:block;width:100%;max-width:520px;margin:0 auto;border-radius:50px;padding:14px 20px;font-family:'Cinzel',sans-serif;font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;text-align:center;text-decoration:none;color:#1a0d2e;background:linear-gradient(180deg,var(--gold-bright),var(--gold));box-shadow:0 8px 22px #d4af3766}
    @media(max-width:640px){.sticky-cta.show{display:block}}
  </style>

  <!-- Microsoft Clarity -->
  <script type="text/javascript">
    (function(c,l,a,r,i,t,y){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
    t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
    y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);})(window,document,"clarity","script","wq82rtc2gf");
  </script>
</head>
<body>

  <!-- COSMIC BACKGROUND -->
  <div class="dream-bg" aria-hidden="true">
    <div class="dream-veil"></div>
    <div class="milky-way"></div>
    <div class="dream-orb one"></div>
    <div class="dream-orb two"></div>
    <div class="dream-orb three"></div>
  </div>

  <main>

    <!-- 2. HERO -->
    <section class="section center">
      <div class="wrap">
        <div style="text-align:center;margin:0 auto 24px;">
          <img src="frontend/images/inner-circle/luna-avatar.webp" width="130" height="130" decoding="async" alt="Luna Ross" onerror="this.style.visibility='hidden'" style="width:130px;height:130px;border-radius:50%;object-fit:cover;border:3px solid #d4af37;box-shadow:0 0 0 6px rgba(212,175,55,0.12),0 10px 34px rgba(0,0,0,0.5);">
        </div>
        <span class="eyebrow">A Private Invitation From Luna</span>
        <h1>Every Day, Something Tests You. And You Face It <em>Alone.</em></h1>
        <p class="subhead hero-sub">A decision lands on you at 2am and there's no one to tell. From today, you reach for me instead. A private 1-1 session with me, any day you need it.</p>
      </div>
    </section>

    <!-- 3. INTRODUCE LUNA'S GUIDE -->
    <section class="section">
      <div class="wrap">
        <img src="frontend/images/inner-circle/inner-circle-intro.webp?v=2" width="1800" height="1208" decoding="async" alt="The Inner Circle, Luna one message away" style="display:block;width:100%;height:auto;max-width:560px;margin:4px auto 30px;border-radius:14px;border:1px solid rgba(212,175,55,0.35);box-shadow:0 14px 44px rgba(0,0,0,0.5);">
        <h2>The Inner Circle</h2>
        <p class="subhead">Unlimited private 1-1 sessions with Luna. Anytime, about anything.</p>
        <div class="body-copy" style="margin-top:26px;">
          <p>Every time you reach out, it is a private session: just you and me, about your reading, your block, and the moment you are actually in. You message me, and within the hour I reply, personally, considered, and only ever about you. A single private session with a reader of my experience is worth <strong>$120</strong>. Inside The Inner Circle, you have them whenever you need one, with no limit.</p>
        </div>
      </div>
    </section>

    <!-- EARLY OFFER (right after intro) -->
    <section class="section section--tight" id="offer-early">
      <div class="wrap" style="max-width:540px;">
        <div class="offer-box" style="text-align:center;box-shadow:0 6px 20px #0004;border-color:#d4af3733;">
          <h2 style="margin-bottom:14px;">Begin Your Membership</h2>
          <p style="color:#e9e2f2;font-size:17px;line-height:1.6;max-width:430px;margin:0 auto 20px;">Unlimited private 1-1 sessions with Luna, <strong style="color:var(--gold-light);">each worth $120</strong>, yours whenever you need one.</p>
          <div class="price-row" style="justify-content:center;margin-bottom:2px;">
            <span class="price-old">$120 per session</span>
            <span class="price-new"><span class="cur">$</span>37</span>
          </div>
          <p class="price-note" style="margin:0 auto 22px;">for your first month, then <strong style="color:var(--gold-light);">$19/month</strong> for unlimited sessions. Cancel anytime in one click.</p>
          <div style="text-align:center;margin:2px auto 18px;"><span style="display:inline-block;font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-light);border:1px solid #d4af3759;border-radius:50px;padding:7px 18px;">&#10003;&nbsp; 90-Day Money-Back Guarantee</span></div>
          <a class="cta" href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>">Yes, I Want To Talk To Luna</a>
        </div>
      </div>
    </section>

    <!-- 4. WHAT YOU CAN ASK (THE STAR) -->
    <section class="section">
      <div class="wrap">
        <h2>In Every Session, Ask Me <em>Anything.</em></h2>
        <p class="lead">There is no limit and no meter. No question too small, none too big. Bring me as much as you want, as often as you want. Whatever you would normally carry alone, bring it to a session:</p>
        <ul class="ask-list">
          <li>"Should I ask for the raise, or is that the old fear talking?"</li>
          <li>"Is this opportunity genuinely too much, or is that my Wealth Block?"</li>
          <li>"He hasn't replied. What is my Love card trying to tell me?"</li>
          <li>"I drew the Tower this morning. What does it mean for me?"</li>
          <li>"I have a decision by Friday. Help me see which choice is fear and which is me."</li>
          <li>"Something good is happening and I can feel myself bracing for it to end. How do I stop?"</li>
          <li>"What does this week hold for my money?"</li>
          <li>"I can't sleep and my mind won't stop. Talk me through it."</li>
          <li>"I have no one to really talk to about any of this. Can I just tell you what's going on, and will you listen?"</li>
          <li>"The doubt is creeping back after the ritual. Is that normal?"</li>
        </ul>
        <p class="subhead" style="margin:26px auto 0;max-width:580px;">Money. Love. A card. A choice. A fear at 2am. If it is on your mind, it belongs in a session, and <strong style="color:var(--gold-light);font-style:normal;">you will never run out of questions you are allowed to ask.</strong></p>
      </div>
    </section>

    <!-- 5. HOW IT WORKS -->
    <section class="section">
      <div class="wrap">
        <h2>As Simple As Texting a Friend.</h2>
        <hr class="gold-rule" style="margin:14px auto 26px;" />
        <div class="stack">
          <div class="card mv">
            <div class="mv__num">Open It</div>
            <div class="mv__body"><p>A private space on your phone, yours the moment you join. No app store, no setup.</p></div>
          </div>
          <div class="card mv">
            <div class="mv__num">Start a Session</div>
            <div class="mv__body"><p>Bring me whatever is on your mind. Type it like you would say it out loud. Nothing is too small.</p></div>
          </div>
          <div class="card mv">
            <div class="mv__num">I Reply Within the Hour</div>
            <div class="mv__body"><p>Considered, personal, and about you. A real session, never a vending-machine reply.</p></div>
          </div>
        </div>
      </div>
    </section>

    <!-- 6. PROOF -->
    <section class="section">
      <div class="wrap">
        <h2>From Members Who Stopped <em>Facing It Alone.</em></h2>
        <div style="margin-top:26px;">
          <div class="testi">
            <div class="testi__row"><img class="testi__avatar" src="frontend/images/inner-circle/member-rachel.webp" width="56" height="56" decoding="async" loading="lazy" alt="Rachel M." onerror="this.style.display='none'"><div><div class="testi__name">Rachel M.</div><div class="testi__meta">Inner Circle Member</div></div></div>
            <div class="testi__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="testi__body">"I asked Luna whether to send the email or wait. She told me exactly which fear was talking. I sent it. They said yes. One session, ten minutes, the raise I had avoided for a year."</p>
          </div>
          <div class="testi">
            <div class="testi__row"><img class="testi__avatar" src="frontend/images/inner-circle/member-sophie.webp" width="56" height="56" decoding="async" loading="lazy" alt="Sophie T." onerror="this.style.display='none'"><div><div class="testi__name">Sophie T.</div><div class="testi__meta">Inner Circle Member</div></div></div>
            <div class="testi__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="testi__body">"I almost backed out of an opportunity because it felt like too much. I asked Luna first. I did not back out. That one session was worth the whole year to me."</p>
          </div>
        </div>
        <p style="text-align:center;max-width:620px;margin:18px auto 0;font-size:13px;color:#9a93b3;font-style:italic;">Individual experiences. Results vary. For guidance and reflection, not financial, medical, or professional advice.</p>
      </div>
    </section>

    <!-- 7a. CLOSE + GUARANTEE LEAD-IN (no button) -->
    <section class="section center">
      <div class="wrap" style="max-width:620px;">
        <h2>The Next Time the Pattern Tests You, <em>Don't Face It Alone.</em></h2>
        <p style="color:var(--text-muted);font-size:18px;line-height:1.7;max-width:580px;margin:0 auto 22px;">The block will test you again. That is not a maybe. Next time, do not face it with the old voice. Sit down with me instead, any day you need me.</p>
        <hr class="gold-rule" style="margin:10px auto 22px;">
        <p style="color:var(--text-muted);font-size:18px;line-height:1.7;max-width:580px;margin:0 auto;"><strong style="color:var(--gold-light);">And you risk nothing.</strong> Your membership is protected by my full 90-day money-back guarantee. If it is not for you, tell my team any time in the first 90 days and I will refund every payment, no questions asked, and you can cancel in one click anytime.</p>
      </div>
    </section>

    <!-- 7. OFFER BOX -->
    <section class="section" id="offer">
      <div class="wrap" style="max-width:620px;">
        <div class="offer-box" style="border-width:1.5px;box-shadow:0 18px 56px #0008,0 0 0 1px #d4af3726;">
          <h2 style="margin-bottom:10px;">The Inner Circle</h2>
          <p style="color:#cfc7e6;font-style:italic;font-family:'Cormorant Garamond',serif;font-size:20px;margin-bottom:24px;">Unlimited private 1-1 sessions with Luna, personalized to your reading.</p>

          <ul class="vlist">
            <li><span class="vs-name">Unlimited private 1-1 sessions with Luna, each worth <strong>$120</strong> on its own</span></li>
            <li><span class="vs-name">A thoughtful, personal reply within the hour, every time</span></li>
            <li><span class="vs-name">Guidance that knows your reading and your specific Wealth Block</span></li>
            <li><span class="vs-name">A private space on your phone, day and night, including the 2am moments</span></li>
            <li><span class="vs-name">Bring me as much as you want, as often as you want, no limit</span></li>
          </ul>

          <p style="font-family:'Cormorant Garamond',serif;font-size:21px;line-height:1.45;color:#fff;max-width:460px;margin:24px auto 0;">One private session with Luna is worth <strong style="color:var(--gold-light);">$120</strong>. Inside The Inner Circle, your sessions are <strong style="color:var(--gold-light);">unlimited</strong>.</p>

          <div class="founding-panel">
            <span class="founding-head">✦ &nbsp; Founding Member Invitation &nbsp; ✦</span>
            <p>Your <strong>first month is $37</strong>. After that it is <strong>$19/month for unlimited sessions</strong>, your founding rate locked for as long as you stay.</p>
          </div>

          <div class="pricing">
            <span class="price-label">Founding Member Price</span>
            <div class="price-row">
              <span class="price-old">$120 per session</span>
              <span class="price-new"><span class="cur">$</span>37</span>
            </div>
            <p class="price-note">for your first month, then $19/month for unlimited sessions, your founding rate locked</p>
          </div>

          <p class="cta-fine" style="max-width:520px;margin:8px auto 18px;">A monthly membership. Your first month is $37, then you are billed $19/month for unlimited sessions.</p>
          <div style="text-align:center;margin:2px auto 18px;"><span style="display:inline-block;font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-light);border:1px solid #d4af3759;border-radius:50px;padding:7px 18px;">&#10003;&nbsp; 90-Day Money-Back Guarantee</span></div>

          <a class="cta" href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>">Yes, I Want To Talk To Luna</a>
          <p style="text-align:center;margin-top:14px;font-size:12px;color:#9a93b3;letter-spacing:.04em;">&#128274; Secure checkout. Billing handled by ClickBank, the trusted retailer for this product.</p>
        </div>
      </div>
    </section>

    <div class="sticky-cta" id="stickyCta"><a href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>">Keep Luna One Message Away &nbsp;&middot;&nbsp; $37</a></div>
  </main>

  <!-- FOOTER -->
  <footer class="footer">
    <p>ClickBank is the retailer of products on this site. CLICKBANK&reg; is a registered trademark of Click Sales, Inc., a Delaware corporation located at 1444 S. Entertainment Ave., Suite 410 Boise, ID 83709, USA and used by permission. ClickBank's role as retailer does not constitute an endorsement, approval or review of these products or any claim, statement or opinion used in promotion of these products.</p>
    <p>For Product Support, please contact the vendor: <a href="mailto:support@soulmirrorreading.com">HERE</a>. For Order Support, please contact ClickBank: <a href="https://www.clkbank.com/" target="_blank" rel="noopener">HERE</a> or 1-800-390-6035</p>
    <p><a href="/privacy-policy">Privacy Policy</a> &nbsp;&middot;&nbsp; <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;&middot;&nbsp; <a href="mailto:support@soulmirrorreading.com">Contact Us</a> &nbsp;&middot;&nbsp; <a href="/refund-return-policy">Refund &amp; Return Policy</a></p>
    <p>The Inner Circle is an ongoing membership providing guidance and reflection. It is Luna's trained AI guide, not personal correspondence, and is not financial, medical, or professional advice. Results vary. You can cancel anytime.</p>
    <p>&copy; 2026 Soul Mirror Reading, A Luna Ross Brand. All Rights Reserved.</p>
  </footer>

  <script>(function(){var ec=document.getElementById('offer-early'),sc=document.getElementById('stickyCta');if(!ec||!sc)return;addEventListener('scroll',function(){sc.classList.toggle('show',ec.getBoundingClientRect().bottom<0)},{passive:true});})();</script>
</body>
</html>

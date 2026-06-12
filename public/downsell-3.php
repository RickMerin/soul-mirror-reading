<?php
declare(strict_types=1);

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

\App\Config\AppConfig::load($projectRoot);


$otoCheckoutUrl = 'https://rebornf.pay.clickbank.net/?cbitems=mm-1-ds&cbur=a';
$downsellPageUrl = 'https://rebornf.pay.clickbank.net/?cbitems=mm-1-ds&cbur=d';

?><!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/svg+xml" href="https://soulmirrorreading.com/favicon.svg" />
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Downsell 3 (DS3) - The Mirror Meditations at $27 (wealth-v2 cosmic system) -->
  <!-- TODO: create ClickBank one-time item mm-1-ds: $27, then wire these URLs -->
  <title>The Mirror Meditations, A Final Offer</title>
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
    .section{padding:50px 0}
    .section--tight{padding:30px 0}
    .center{text-align:center}

    h1,h2,h3,h4{font-family:'Cormorant Garamond',Georgia,serif;color:#fff;line-height:1.18;text-wrap:balance}
    h1{font-size:clamp(28px,6vw,44px);font-weight:600;margin-bottom:16px}
    h1 em,h2 em,h3 em{color:var(--gold-light);font-style:italic}
    h2{text-align:center;font-size:clamp(27px,4.5vw,40px);font-weight:600;margin-bottom:22px}
    .eyebrow{font-family:'Cinzel',sans-serif;font-size:12px;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);display:block;text-align:center;margin-bottom:14px;font-weight:600}
    .subhead{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:clamp(18px,2.6vw,22px);color:var(--text-muted);text-align:center;max-width:600px;margin:0 auto 8px;line-height:1.55}
    .hero-sub{font-size:clamp(18px,4.4vw,21px);line-height:1.62;color:#e7e2f4;max-width:430px;margin:20px auto 0}
    .lead{text-align:center;max-width:580px;margin:0 auto 26px;color:var(--text-muted);font-size:17px}

    .topnotice{position:relative;z-index:2;background:#9b1c1c;border-bottom:1px solid #c0392b;color:#f0dca8;text-align:center;padding:13px 18px;font-family:'Inter',system-ui,sans-serif;font-size:12.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;line-height:1.5}
    .topnotice .dot{color:#f3b0a0;margin:0 8px}

    .cta{background:linear-gradient(180deg,var(--gold-bright) 0%,var(--gold) 100%);color:#1a0d2e;letter-spacing:.16em;text-transform:uppercase;cursor:pointer;text-shadow:0 1px #fff3;border:none;border-radius:50px;padding:20px 40px;font-family:'Cinzel',sans-serif;font-size:clamp(13px,2.2vw,15px);font-weight:700;text-decoration:none;display:inline-block;width:100%;max-width:520px;text-align:center;box-shadow:0 12px 32px #d4af3766,inset 0 1px #fff6;transition:transform .12s cubic-bezier(.34,1.56,.64,1),box-shadow .2s,filter .15s}
    .cta:active{transform:translateY(1px) scale(.985)}
    @media (hover:none){.cta:hover{transform:none}}
    .cta:hover{transform:translateY(-2px);box-shadow:0 16px 40px #d4af3780}
    .cta-decline{display:block;width:100%;margin-top:16px;background:none;border:none;cursor:pointer;text-align:center;color:#cdc6e0;opacity:.7;font-family:'Inter',sans-serif;font-size:14px;text-decoration:underline;text-underline-offset:3px}
    .cta-decline:hover{opacity:1}

    .offer-box{background:linear-gradient(180deg,var(--violet-mid) 0%,var(--violet) 100%);color:#fff;text-align:center;border:1px solid #d4af374d;border-radius:16px;padding:34px 28px;position:relative;overflow:hidden;box-shadow:0 12px 40px #0006}
    .offer-box::before{content:"";pointer-events:none;background:radial-gradient(at 50% 0,#d4af372e 0%,#0000 60%);position:absolute;inset:0}
    .offer-box>*{position:relative;z-index:1}
    .offer-label{font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-light);margin-bottom:12px;display:block}

    .pricing{margin:8px 0 10px}
    .price-label{font-family:'Cinzel',sans-serif;font-size:12px;letter-spacing:.16em;text-transform:uppercase;color:var(--gold);display:block;margin-bottom:8px}
    .price-row{display:flex;justify-content:center;align-items:baseline;gap:18px;margin:6px 0 12px}
    .price-old{color:#ffffff73;text-decoration:line-through;text-decoration-color:var(--gold);font-family:'Cinzel',sans-serif;font-size:24px}
    .price-new{color:var(--gold-light);text-shadow:0 0 24px #d4af3759;font-family:'Cormorant Garamond',serif;font-size:clamp(58px,12vw,72px);font-weight:600;line-height:1}
    .price-new .cur{vertical-align:super;font-size:.42em;margin-right:3px;font-weight:500}
    .price-note{color:#fff9;font-size:14px;font-style:italic;line-height:1.65;max-width:520px;margin:0 auto}

    .pricedrop{border:1.5px solid #d4af3799;border-radius:14px;background:linear-gradient(135deg,rgba(212,175,55,.14),rgba(212,175,55,.05));max-width:460px;margin:0 auto 8px;padding:18px 22px;text-align:center}
    .pricedrop .pd-head{font-family:'Cinzel',sans-serif;font-size:12px;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);display:block;margin-bottom:8px;font-weight:700}
    .pricedrop p{font-size:15px;line-height:1.6;color:#e9e2f2;margin:0}
    .pricedrop strong{color:var(--gold-light)}

    /* TRACK GRID: dawn-to-dusk play console (compact) */
    .mirror{margin:0 auto 14px;max-width:600px;border:1px solid #d4af3740;border-radius:16px;background:linear-gradient(180deg,#241141cc 0%,#160a30e6 100%);backdrop-filter:blur(6px);overflow:hidden;box-shadow:0 12px 34px #0005,inset 0 1px 0 #ffffff0d;transition:border-color .25s ease,box-shadow .25s ease}
    .mirror:hover{border-color:#d4af3773;box-shadow:0 16px 46px #0006,0 0 0 1px #d4af3722,inset 0 1px 0 #ffffff14}
    .mirror__head{position:relative;font-family:'Cinzel',sans-serif;font-size:12px;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);text-align:center;padding:13px 52px 11px;border-bottom:1px solid #d4af3726}
    .mirror__head::after{content:"";position:absolute;left:50%;bottom:-1px;transform:translateX(-50%);width:56px;height:1px;background:linear-gradient(90deg,transparent,var(--gold),transparent)}
    .mirror__num{position:absolute;left:14px;top:50%;transform:translateY(-50%);width:24px;height:24px;border:1px solid #d4af3759;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;color:var(--gold-light);background:#d4af3712}
    .mirror__row{display:grid;grid-template-columns:1fr 1fr;gap:0;position:relative}
    .mirror__row::before{content:"";position:absolute;top:12px;bottom:12px;left:50%;width:1px;background:linear-gradient(180deg,transparent,#d4af3733 18%,#d4af3733 82%,transparent)}

    .trk{position:relative;padding:15px 16px;display:flex;gap:11px;align-items:center;transition:background .25s ease}
    .trk--morning{background:linear-gradient(180deg,#3a2a0c1f 0%,transparent 70%)}
    .trk--night{background:linear-gradient(180deg,#150a3526 0%,transparent 70%)}
    .trk--morning:hover{background:linear-gradient(180deg,#5a3f0f30 0%,#3a2a0c10 75%)}
    .trk--night:hover{background:linear-gradient(180deg,#24145240 0%,#150a3514 75%)}

    .trk__play{position:relative;flex-shrink:0;width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:transform .2s cubic-bezier(.34,1.56,.64,1),box-shadow .25s ease}
    .trk--morning .trk__play{background:radial-gradient(circle at 35% 30%,#f4dca0,#d4af37);box-shadow:0 4px 12px #d4af3759,inset 0 1px 0 #fff7}
    .trk--night .trk__play{background:radial-gradient(circle at 35% 30%,#3a2a6e,#1a0f3c);border:1px solid #d4af3766;box-shadow:0 4px 12px #0006,inset 0 1px 0 #ffffff1a}
    .trk:hover .trk__play{transform:scale(1.07)}
    .trk__play svg{width:18px;height:18px;display:block}
    .trk--morning .trk__play svg{fill:#3a2407}
    .trk--night .trk__play svg{fill:var(--gold-light)}
    .trk__tri{position:absolute;right:-3px;bottom:-3px;width:14px;height:14px;border-radius:50%;background:#0e0820;border:1px solid #d4af3759;display:flex;align-items:center;justify-content:center;z-index:1}
    .trk__tri svg{width:5px;height:5px;fill:var(--gold-light)}

    .trk__body{min-width:0}
    .trk__when{font-family:'Cinzel',sans-serif;font-size:10px;letter-spacing:.16em;text-transform:uppercase;display:block;margin-bottom:2px}
    .trk--morning .trk__when{color:var(--gold-light)}
    .trk--night .trk__when{color:#b9a7e0}
    .trk__name{font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:600;color:#fff;line-height:1.18}
    @media(max-width:560px){
      .mirror__row{grid-template-columns:1fr}
      .mirror__row::before{display:none}
      .trk{border-bottom:1px solid #d4af3720}
      .trk--night{border-bottom:none}
    }

    .footer{position:relative;z-index:1;background:#0e0820;border-top:1px solid #d4af3726;padding:28px 24px;text-align:center}
    .footer p{font-size:12px;color:#9a93b3;line-height:1.6;max-width:760px;margin:0 auto 8px}
    .footer a{color:#b7afd0;text-decoration:underline;text-underline-offset:2px}

    @media (max-width:640px){
      .section{padding:38px 0}.wrap{padding:0 20px}
      .offer-box{padding:28px 20px}
      .topnotice{font-size:11px;letter-spacing:.03em;padding:11px 14px;white-space:normal}
      .cta{padding:18px 22px;letter-spacing:.07em;font-size:14px;line-height:1.2}
      .hero-sub,.subhead,.lead{max-width:360px}
      .cta-decline{margin-top:20px;min-height:44px;padding:12px 8px;line-height:1.4}
    }
  </style>
</head>
<body>

  <!-- PRICE-DROP BANNER -->
  <div class="topnotice">
    <strong>Wait, I Just Took $20 Off</strong> <span class="dot">&middot;</span> <strong>This Lower Price Won't Be Shown Again</strong>
  </div>

  <div class="dream-bg" aria-hidden="true">
    <div class="dream-veil"></div><div class="milky-way"></div>
    <div class="dream-orb one"></div><div class="dream-orb two"></div><div class="dream-orb three"></div>
  </div>

  <main>

    <!-- HERO -->
    <section class="section center">
      <div class="wrap">
        <div style="text-align:center;margin:0 auto 20px;">
          <img src="frontend/images/upsell3/luna-avatar.webp" alt="Luna Ross" onerror="this.style.visibility='hidden'" style="width:110px;height:110px;border-radius:50%;object-fit:cover;border:3px solid #d4af37;box-shadow:0 0 0 6px rgba(212,175,55,0.12),0 10px 34px rgba(0,0,0,0.5);">
        </div>
        <span class="eyebrow">One Moment, Before You Go</span>
        <h1>Let Me Make This <em>Easy.</em></h1>
        <p class="subhead hero-sub">The meditations weren't a yes a moment ago, and I understand. So let me take $20 off and give you the same six sessions for less than the price of one. This is the last time you will see them.</p>
      </div>
    </section>

    <!-- QUICK RECAP -->
    <section class="section section--tight">
      <div class="wrap">
        <img src="frontend/images/upsell3/mirror-meditations-hero.webp" alt="The Mirror Meditations, guided sessions in Luna's voice" onerror="this.style.display='none'" style="display:block;width:100%;max-width:560px;margin:0 auto 28px;border-radius:14px;border:1px solid rgba(212,175,55,0.35);box-shadow:0 14px 44px rgba(0,0,0,0.5);">
        <h2>The Same Six Sessions</h2>
        <p class="lead">In my own voice. One to open each morning, one to settle each night, for every mirror.</p>

        <svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>
          <symbol id="ico-sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4.4"/><g><rect x="11" y="1.2" width="2" height="3.4" rx="1"/><rect x="11" y="19.4" width="2" height="3.4" rx="1"/><rect x="1.2" y="11" width="3.4" height="2" rx="1"/><rect x="19.4" y="11" width="3.4" height="2" rx="1"/><rect x="3.6" y="3.6" width="2" height="3.4" rx="1" transform="rotate(-45 4.6 5.3)"/><rect x="18.4" y="17" width="2" height="3.4" rx="1" transform="rotate(-45 19.4 18.7)"/><rect x="17" y="3.6" width="2" height="3.4" rx="1" transform="rotate(45 18 5.3)"/><rect x="3.6" y="17" width="2" height="3.4" rx="1" transform="rotate(45 4.6 18.7)"/></g></symbol>
          <symbol id="ico-moon" viewBox="0 0 24 24"><path d="M15.2 3.1a8.4 8.4 0 1 0 5.9 12.2A6.6 6.6 0 0 1 15.2 3.1z"/><circle cx="18.4" cy="6.1" r="1"/><circle cx="20" cy="9.8" r=".7"/><circle cx="16.3" cy="9" r=".6"/></symbol>
          <symbol id="ico-play" viewBox="0 0 12 12"><path d="M3 2.2v7.6L9.5 6z"/></symbol>
        </defs></svg>

        <div class="mirror">
          <div class="mirror__head"><span class="mirror__num">1</span>The Money Mirror</div>
          <div class="mirror__row">
            <div class="trk trk--morning"><span class="trk__play"><svg><use href="#ico-sun"/></svg><span class="trk__tri"><svg><use href="#ico-play"/></svg></span></span><div class="trk__body"><span class="trk__when">Morning</span><div class="trk__name">Open to Receive</div></div></div>
            <div class="trk trk--night"><span class="trk__play"><svg><use href="#ico-moon"/></svg><span class="trk__tri"><svg><use href="#ico-play"/></svg></span></span><div class="trk__body"><span class="trk__when">Night</span><div class="trk__name">Release the Ceiling</div></div></div>
          </div>
        </div>
        <div class="mirror">
          <div class="mirror__head"><span class="mirror__num">2</span>The Love Mirror</div>
          <div class="mirror__row">
            <div class="trk trk--morning"><span class="trk__play"><svg><use href="#ico-sun"/></svg><span class="trk__tri"><svg><use href="#ico-play"/></svg></span></span><div class="trk__body"><span class="trk__when">Morning</span><div class="trk__name">Open Heart</div></div></div>
            <div class="trk trk--night"><span class="trk__play"><svg><use href="#ico-moon"/></svg><span class="trk__tri"><svg><use href="#ico-play"/></svg></span></span><div class="trk__body"><span class="trk__when">Night</span><div class="trk__name">Lay Down the Watch</div></div></div>
          </div>
        </div>
        <div class="mirror">
          <div class="mirror__head"><span class="mirror__num">3</span>The Purpose Mirror</div>
          <div class="mirror__row">
            <div class="trk trk--morning"><span class="trk__play"><svg><use href="#ico-sun"/></svg><span class="trk__tri"><svg><use href="#ico-play"/></svg></span></span><div class="trk__body"><span class="trk__when">Morning</span><div class="trk__name">Begin</div></div></div>
            <div class="trk trk--night"><span class="trk__play"><svg><use href="#ico-moon"/></svg><span class="trk__tri"><svg><use href="#ico-play"/></svg></span></span><div class="trk__body"><span class="trk__when">Night</span><div class="trk__name">Lay It Down</div></div></div>
          </div>
        </div>

        <p class="subhead" style="margin:20px auto 0;max-width:540px;">The rituals cleared your block. These wall it in, a few minutes morning and night, so the old pattern cannot creep back.</p>
      </div>
    </section>

    <!-- GUARANTEE -->
    <section class="section section--tight">
      <div class="wrap">
        <div style="max-width:620px;margin:0 auto;text-align:center;border:1px solid #d4af3759;border-radius:16px;background:#1e0d4080;backdrop-filter:blur(4px);padding:36px 30px;">
          <img src="https://soulmirrorreading.com/frontend/images/upsell2/guarantee-badge.webp" alt="90-Day Guarantee Badge" onerror="this.style.display='none'" style="width:112px;height:auto;margin:0 auto 14px;display:block;filter:drop-shadow(0 10px 20px #d4af3740);" />
          <h2 style="font-size:clamp(20px,3vw,26px);margin-bottom:14px;">The Mirror Meditations, 90-Day Guarantee</h2>
          <p style="font-size:16px;line-height:1.7;color:var(--text-muted);max-width:540px;margin:0 auto;">Play the sessions for a real 90 days, morning and night. If the old pattern creeps back and nothing feels more sealed, reply and I refund every cent of your $27. No questionnaire, no proof. You keep all six sessions and the bonus, no matter what.</p>
        </div>
      </div>
    </section>

    <!-- OFFER -->
    <section class="section" id="offer">
      <div class="wrap" style="max-width:560px;">
        <div class="offer-box" style="border-width:1.5px;box-shadow:0 18px 56px #0008,0 0 0 1px #d4af3726;">
          <span class="offer-label">Final, Lowest Price</span>
          <h2 style="margin-bottom:8px;">The Mirror Meditations</h2>
          <p style="color:#cfc7e6;font-style:italic;font-family:'Cormorant Garamond',serif;font-size:19px;margin-bottom:18px;">Six guided sessions in Luna's voice, plus the 60-Second Reset. Yours to keep for life.</p>

          <div class="pricedrop">
            <span class="pd-head">✦ &nbsp; $20 Off, This Page Only &nbsp; ✦</span>
            <p>You just passed on these at $47. Take them now for <strong>$27</strong>, one payment, yours to keep for life. This price will not be offered again.</p>
          </div>

          <div class="pricing">
            <span class="price-label">Your Final Price</span>
            <div class="price-row">
              <span class="price-old">$47</span>
              <span class="price-new"><span class="cur">$</span>27</span>
            </div>
            <p class="price-note">One payment. Instant access. Yours to keep for life. ($144 value)</p>
          </div>

          <div style="text-align:center;margin:2px auto 18px;"><span style="display:inline-block;font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-light);border:1px solid #d4af3759;border-radius:50px;padding:7px 18px;">&#10003;&nbsp; 90-Day Money-Back Guarantee</span></div>

          <a class="cta" href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>">Yes, Upgrade My Order Now</a>
          <a class="cta-decline" href="<?= htmlspecialchars($downsellPageUrl, ENT_QUOTES, 'UTF-8') ?>">No thank you, complete my order without them.</a>
          <p style="text-align:center;margin-top:14px;font-size:12px;color:#9a93b3;letter-spacing:.04em;">&#128274; This adds to the order you just placed. No card to re-enter. Secure checkout by ClickBank.</p>
        </div>
      </div>
    </section>

  </main>

  <footer class="footer">
    <p>ClickBank is the retailer of products on this site. CLICKBANK&reg; is a registered trademark of Click Sales, Inc., a Delaware corporation located at 1444 S. Entertainment Ave., Suite 410 Boise, ID 83709, USA and used by permission. ClickBank's role as retailer does not constitute an endorsement, approval or review of these products or any claim, statement or opinion used in promotion of these products.</p>
    <p>For Product Support, please contact the vendor: <a href="mailto:support@soulmirrorreading.com">HERE</a>. For Order Support, please contact ClickBank: <a href="https://www.clkbank.com/" target="_blank" rel="noopener">HERE</a> or 1-800-390-6035</p>
    <p><a href="/privacy-policy">Privacy Policy</a> &nbsp;&middot;&nbsp; <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;&middot;&nbsp; <a href="mailto:support@soulmirrorreading.com">Contact Us</a> &nbsp;&middot;&nbsp; <a href="/refund-return-policy">Refund &amp; Return Policy</a></p>
    <p>The Mirror Meditations are guided audio sessions for guidance and reflection. They are not financial, medical, or professional advice. Results vary.</p>
    <p>&copy; 2026 Soul Mirror Reading, A Luna Ross Brand. All Rights Reserved.</p>
  </footer>

</body>
</html>

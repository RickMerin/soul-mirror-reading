<?php
declare(strict_types=1);
// Standalone affiliate (JV) recruitment page for Soul Mirror Reading.
// Served at soulmirrorreading.com/affiliate.php (clean URL /affiliate via .htaccess).
// Self-contained: inline CSS + inline vanilla JS, matches the cosmic funnel brand.
?><!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/svg+xml" href="https://soulmirrorreading.com/favicon.svg" />
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="robots" content="noindex, follow" />
  <title>Affiliate Program &middot; Soul Mirror Reading</title>
  <meta name="description" content="Promote Soul Mirror Reading on ClickBank. Free-reading hook, a full funnel with upsells, a 90-day guarantee, ready-to-send email swipes, and a one-click hoplink generator." />
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

    /* COSMIC BACKGROUND (matches funnel pages) */
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
    @media (prefers-reduced-motion:reduce){.dream-veil,.milky-way,.dream-orb{animation:none}}

    main{position:relative;z-index:1}
    .wrap{max-width:860px;margin:0 auto;padding:0 22px}
    .section{padding:52px 0}
    .section--tight{padding:30px 0}
    .center{text-align:center}

    /* TYPE */
    h1,h2,h3,h4{font-family:'Cormorant Garamond',Georgia,serif;color:#fff;line-height:1.18;text-wrap:balance}
    h1{font-size:clamp(30px,6vw,48px);font-weight:600;margin-bottom:16px}
    h1 em,h2 em,h3 em{color:var(--gold-light);font-style:italic}
    h2{font-size:clamp(26px,4.4vw,38px);font-weight:600;margin-bottom:16px}
    h3{font-size:21px;font-weight:600;margin-bottom:8px;color:#fff}
    .eyebrow{font-family:'Cinzel',sans-serif;font-size:12px;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);display:block;margin-bottom:14px;font-weight:600}
    .subhead{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:clamp(18px,2.6vw,23px);color:var(--text-muted);max-width:640px;margin:0 auto 8px;line-height:1.55}
    .lead{max-width:640px;margin:0 auto;color:var(--text-muted);font-size:17px}
    .body-copy{max-width:680px;margin:0 auto;font-size:17px;line-height:1.8;color:var(--text)}
    .body-copy p+p{margin-top:16px}
    .body-copy strong{color:var(--gold-light)}
    .gold-rule{border:none;height:1px;background:linear-gradient(90deg,transparent,var(--gold),transparent);max-width:340px;margin:30px auto}

    /* HERO */
    .hero-img{display:block;width:100%;max-width:240px;height:auto;margin:26px auto 0;border-radius:12px;border:1px solid rgba(212,175,55,.5);box-shadow:0 12px 30px rgba(0,0,0,.5);background:#160c34}

    /* FACT CHIPS */
    .chips{display:flex;flex-wrap:wrap;justify-content:center;gap:10px;margin:26px auto 0;max-width:720px}
    .chip{font-family:'Inter',sans-serif;font-size:13px;font-weight:600;letter-spacing:.02em;color:#f3ecd8;background:linear-gradient(#2d1b6980,#1e0d40d9);border:1px solid #d4af3759;border-radius:40px;padding:9px 16px}
    .chip b{color:var(--gold-light)}

    /* STICKY NAV */
    .navbar{position:sticky;top:0;z-index:6;background:#0e0820e6;backdrop-filter:blur(9px);border-top:1px solid #d4af3722;border-bottom:1px solid #d4af3733}
    .navbar-inner{display:flex;flex-wrap:wrap;justify-content:center;gap:6px;max-width:860px;margin:0 auto;padding:10px 14px}
    .navbar a{font-family:'Cinzel',sans-serif;font-size:12px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#d8d2eb;text-decoration:none;padding:9px 16px;border-radius:40px;border:1px solid transparent;transition:color .15s,border-color .15s,background .15s}
    .navbar a:hover,.navbar a:focus-visible{color:var(--gold-light);border-color:#d4af3759;background:#d4af3712;outline:none}

    /* GLASS PANEL */
    .panel{backdrop-filter:blur(4px);background:linear-gradient(#2d1b6980,#1e0d40d9);border:1px solid #d4af3759;border-radius:16px;padding:30px 26px;box-shadow:0 12px 40px #0006}
    .panel+.panel{margin-top:22px}

    /* BULLET LIST */
    .why{list-style:none;display:grid;gap:14px;max-width:700px;margin:20px auto 0}
    .why li{position:relative;padding-left:36px;font-size:16.5px;line-height:1.7;color:var(--text)}
    .why li::before{content:"\2726";position:absolute;left:6px;top:1px;color:var(--gold);font-size:16px}
    .why li b{color:var(--gold-light)}

    /* FUNNEL FLOW */
    .flow{display:flex;flex-wrap:wrap;align-items:stretch;justify-content:center;gap:10px;max-width:760px;margin:24px auto 0}
    .flow-step{flex:1 1 150px;background:#1e0d40b3;border:1px solid #d4af3740;border-radius:12px;padding:16px 14px;text-align:center}
    .flow-step .fs-k{display:block;font-family:'Cinzel',sans-serif;font-size:10.5px;letter-spacing:.16em;text-transform:uppercase;color:var(--gold);margin-bottom:6px}
    .flow-step .fs-t{display:block;font-size:15px;color:#fff;font-weight:600}
    .flow-step .fs-p{display:block;font-size:13px;line-height:1.5;color:var(--text-muted);margin-top:5px}

    /* LINK GENERATOR */
    .field-row{display:grid;gap:16px;max-width:560px;margin:0 auto}
    .field label{display:block;font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:var(--gold-light);margin-bottom:7px}
    .field .hint{font-family:'Inter',sans-serif;font-size:12.5px;color:#bdb4d6;letter-spacing:0;text-transform:none;margin-top:6px}
    .field input{width:100%;background:#0c0722;color:#fff;border:1px solid #d4af3759;border-radius:10px;padding:14px 16px;font-family:'Inter',sans-serif;font-size:16px}
    .field input::placeholder{color:#8a83a6}
    .field input:focus{outline:none;border-color:var(--gold-light);box-shadow:0 0 0 3px #d4af3733}

    .btn{background:linear-gradient(180deg,var(--gold-bright) 0%,var(--gold) 100%);color:#1a0d2e;letter-spacing:.14em;text-transform:uppercase;cursor:pointer;text-shadow:0 1px #fff3;border:none;border-radius:44px;padding:16px 34px;font-family:'Cinzel',sans-serif;font-size:14px;font-weight:700;text-decoration:none;display:inline-block;text-align:center;box-shadow:0 12px 32px #d4af3766,inset 0 1px #fff6;transition:transform .12s cubic-bezier(.34,1.56,.64,1),box-shadow .2s,filter .15s}
    .btn:hover{transform:translateY(-2px);box-shadow:0 16px 40px #d4af3780}
    .btn:active{transform:translateY(1px) scale(.985)}
    .btn:focus-visible{outline:none;box-shadow:0 12px 32px #d4af3766,inset 0 1px #fff6,0 0 0 3px #0a0716,0 0 0 6px var(--gold-light)}
    .btn:disabled{cursor:not-allowed;opacity:.42;filter:grayscale(.3);transform:none;box-shadow:none}
    .gen-actions{text-align:center;margin-top:22px}

    .result{margin:24px auto 0;max-width:620px;display:none}
    .result.show{display:block}
    .codebox{display:flex;align-items:center;gap:12px;background:#080517;border:1px solid #d4af3766;border-radius:12px;padding:14px 16px}
    .codebox code{flex:1;font-family:'Inter',ui-monospace,SFMono-Regular,Menlo,monospace;font-size:14px;color:var(--gold-light);word-break:break-all;line-height:1.55;text-align:left}
    .copy-btn{flex:none;background:transparent;color:var(--gold-light);border:1px solid #d4af3773;border-radius:8px;padding:9px 15px;font-family:'Cinzel',sans-serif;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;transition:background .15s,color .15s}
    .copy-btn:hover{background:#d4af371f}
    .copy-btn.copied{background:var(--gold);color:#1a0d2e;border-color:var(--gold)}
    .angle-label{display:block;font-family:'Cinzel',sans-serif;font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--gold-light);margin-bottom:7px;text-align:left}

    /* EMAIL SWIPES */
    .swipe{background:linear-gradient(#2d1b6980,#1e0d40d9);border:1px solid #d4af3759;border-radius:16px;padding:24px 22px;margin-top:22px;box-shadow:0 10px 34px #0005;text-align:left}
    .swipe-head{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:16px}
    .swipe-label{font-family:'Cinzel',sans-serif;font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:var(--gold)}
    .swipe-field{margin-bottom:14px}
    .field-tag{display:block;font-family:'Cinzel',sans-serif;font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:#b8afd4;margin-bottom:5px}
    .swipe-subject{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:20px;color:#fff}
    .swipe-body{font-size:16px;line-height:1.72;color:var(--text)}
    .swipe-body p{margin:0 0 12px}
    .swipe-body p:last-child{margin-bottom:0}
    .swipe-body .link-token{color:var(--gold-light);font-weight:600;background:#d4af3714;border:1px dashed #d4af3766;border-radius:5px;padding:1px 6px}

    .note{max-width:700px;margin:0 auto;background:#1e0d4066;border:1px solid #d4af3740;border-left:3px solid var(--gold);border-radius:10px;padding:16px 18px;font-size:14.5px;line-height:1.66;color:#e2ddf0}
    .note b{color:var(--gold-light)}

    /* RULES */
    .rules{list-style:none;display:grid;gap:11px;max-width:700px;margin:18px auto 0}
    .rules li{position:relative;padding-left:30px;font-size:15.5px;line-height:1.65;color:var(--text)}
    .rules li::before{content:"\2713";position:absolute;left:4px;top:0;color:var(--gold);font-weight:700}
    .rules li b{color:var(--gold-light)}

    /* SUPPORT */
    .support-box{max-width:640px;margin:0 auto;text-align:center}
    .support-box a{color:var(--gold-light);text-decoration:underline;text-underline-offset:2px}

    /* FOOTER */
    .footer{position:relative;z-index:1;background:#0e0820;border-top:1px solid #d4af3726;padding:28px 24px;text-align:center;margin-top:20px}
    .footer p{font-size:12px;color:#9a93b3;line-height:1.6;max-width:760px;margin:0 auto 8px}
    .footer a{color:#b7afd0;text-decoration:underline;text-underline-offset:2px}
    .footer a:focus-visible{outline:2px solid var(--gold-light);outline-offset:3px;border-radius:3px}

    @media (max-width:640px){
      .section{padding:42px 0}
      .hero-img{max-width:180px}
      .navbar a{padding:8px 12px;font-size:11px;letter-spacing:.08em}
      .codebox{flex-direction:column;align-items:stretch}
      .copy-btn{width:100%;padding:11px}
      .swipe-head{flex-direction:column;align-items:flex-start;gap:10px}
      .swipe-head .copy-btn{width:100%}
    }
  </style>
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

    <!-- HERO -->
    <section class="section center">
      <div class="wrap">
        <span class="eyebrow">Affiliate Partners &middot; ClickBank Vendor rebornf</span>
        <h1>Promote <em>Soul Mirror Reading</em></h1>
        <p class="subhead">A warm, believable offer for spiritual audiences, built on a free reading that gives cold traffic real value before you ask for anything.</p>
        <img class="hero-img" src="https://soulmirrorreading.com/frontend/images/sales/soul-mirror-reading.webp" width="900" height="900" decoding="async" alt="Soul Mirror Reading product" onerror="this.style.display='none'" />
        <div class="chips">
          <span class="chip"><b>$37</b> front-end reading</span>
          <span class="chip">Full funnel: <b>3 upsells</b> + membership</span>
          <span class="chip"><b>90-day</b> money-back guarantee</span>
          <span class="chip">Runs on <b>ClickBank</b></span>
        </div>
      </div>
    </section>

    <!-- STICKY NAV -->
    <nav class="navbar" aria-label="Affiliate sections">
      <div class="navbar-inner">
        <a href="#about">About The Offer</a>
        <a href="#generator">Link Generator</a>
        <a href="#swipes">Email Swipes</a>
      </div>
    </nav>

    <!-- ABOUT THE OFFER -->
    <section class="section" id="about">
      <div class="wrap">
        <span class="eyebrow center" style="display:block">About The Offer</span>
        <h2 class="center">A Fresh Mechanism, Not Another Generic Tarot Pitch</h2>
        <div class="body-copy" style="margin-top:8px">
          <p>Soul Mirror Reading is a personalized 3-card tarot reading delivered by <strong>Luna Ross</strong>, a warm and gifted reader persona your audience connects with. The reading reveals the visitor's <strong>Mirror Block</strong>: the one hidden pattern quietly blocking their money, love, and purpose at the same time.</p>
          <p>There are four Mirror Block types, and most people carry the same one for their whole life without ever naming it. That single idea gives you a genuinely different angle to write around, instead of leaning on the same tired psychic hooks the whole niche uses.</p>
        </div>

        <!-- FUNNEL FLOW -->
        <div class="flow" aria-label="Funnel flow">
          <div class="flow-step"><span class="fs-k">Hook</span><span class="fs-t">Free Reading</span><span class="fs-p">Card-picker opt-in</span></div>
          <div class="flow-step"><span class="fs-k">Front End</span><span class="fs-t">$37 Reading</span><span class="fs-p">The full Mirror Block reveal</span></div>
          <div class="flow-step"><span class="fs-k">Upsells</span><span class="fs-t">3 OTOs</span><span class="fs-p">Soul Ritual, Clarity Ritual, Mirror Meditations, each with a downsell</span></div>
        </div>

        <hr class="gold-rule" />

        <h3 class="center" style="font-size:24px">Why Affiliates Love This Offer</h3>
        <ul class="why">
          <li><b>A free reading as the hook.</b> Cold, skeptical traffic gets something of value before any ask, which lowers friction on the very first click.</li>
          <li><b>A real mechanism.</b> The Mirror Block gives you a distinct story to tell, so your emails and ads do not sound like everyone else in the niche.</li>
          <li><b>A believable persona.</b> Luna Ross is warm and specific about patterns, the kind of voice spiritual audiences trust and reply to.</li>
          <li><b>A full funnel behind the front end.</b> A $37 reading is followed by three upsells with downsells and a recurring membership sold by email, so there is more than one way to earn per customer.</li>
          <li><b>A 90-day money-back guarantee.</b> A long, honest guarantee makes the first purchase an easier yes for your list.</li>
          <li><b>Done-for-you assets.</b> Ready-to-send email swipes and a one-click hoplink generator are right here on this page.</li>
          <li><b>ClickBank handles the plumbing.</b> Tracking, payouts, and refunds are managed for you through the platform.</li>
        </ul>

        <hr class="gold-rule" />

        <h3 class="center" style="font-size:24px">Who This Converts With</h3>
        <div class="body-copy" style="margin-top:6px">
          <p>Spiritually-minded men and women, roughly <strong>30 to 55</strong>, in the US, UK, Canada, Australia, and New Zealand. It fits lists and audiences built around <strong>tarot, manifestation, spirituality, self-help, and astrology</strong>.</p>
        </div>

        <div class="panel" style="max-width:640px;margin:26px auto 0;text-align:center">
          <h3 style="color:var(--gold-light)">Commissions</h3>
          <!-- TS: insert confirmed commission % here -->
          <p style="font-size:16.5px;color:var(--text);margin-top:4px">Commissions are paid on the front end and every upsell in the funnel, through ClickBank.</p>
        </div>
      </div>
    </section>

    <!-- LINK GENERATOR -->
    <section class="section" id="generator">
      <div class="wrap">
        <span class="eyebrow center" style="display:block">Your Affiliate Link</span>
        <h2 class="center">Generate Your ClickBank Hoplink</h2>
        <p class="lead center" style="margin-bottom:28px">Enter your ClickBank nickname below. Add an optional tracking ID if you want to track a specific campaign, list, or placement inside ClickBank. You get two links, one for each angle page: pick the one that fits your list, or test both.</p>

        <div class="panel" style="max-width:600px;margin:0 auto">
          <div class="field-row">
            <div class="field">
              <label for="cbid">Your ClickBank ID (nickname)</label>
              <input type="text" id="cbid" name="cbid" autocomplete="off" spellcheck="false" placeholder="e.g. yournickname" />
              <span class="hint">Required. This is the account name you log in to ClickBank with.</span>
            </div>
            <div class="field">
              <label for="tid">Tracking ID (optional)</label>
              <input type="text" id="tid" name="tid" autocomplete="off" spellcheck="false" placeholder="e.g. email1 or fb-list" />
              <span class="hint">Optional. Letters, numbers, hyphens, and underscores work best.</span>
            </div>
          </div>

          <div class="gen-actions">
            <button type="button" class="btn" id="genBtn" disabled>Generate My Link</button>
          </div>

          <div class="result" id="result" aria-live="polite">
            <span class="angle-label">Wealth Angle</span>
            <div class="codebox">
              <code id="hoplink-wealth"></code>
              <button type="button" class="copy-btn" id="copyWealth">Copy</button>
            </div>
            <span class="angle-label" style="margin-top:18px">Love Angle</span>
            <div class="codebox">
              <code id="hoplink-love"></code>
              <button type="button" class="copy-btn" id="copyLove">Copy</button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- EMAIL SWIPES -->
    <section class="section" id="swipes">
      <div class="wrap">
        <span class="eyebrow center" style="display:block">Email Swipes</span>
        <h2 class="center">Ready-To-Send Emails</h2>
        <p class="lead center" style="margin-bottom:22px">One swipe document per angle. Use the swipes that match the hoplink you generated above, replace <b style="color:var(--gold-light)">YOURLINK</b> in each email with your link, then send.</p>

        <div class="note" style="margin-bottom:8px">
          <b>Please read before sending.</b> These swipes are starting points; edit them in your own voice. You are responsible for including your own affiliate disclosure, for example a clear line stating that you may earn a commission if someone buys through your link. Follow the FTC endorsement guides and the CAN-SPAM Act. Do not add income promises, health or medical claims, guaranteed outcomes, or fake scarcity.
        </div>

        <div class="gen-actions" style="margin-top:26px;display:flex;flex-wrap:wrap;gap:14px;justify-content:center">
          <a class="btn" href="https://docs.google.com/document/d/1NPkmuwgd_hUrAgFE-2XIsfpv676732GnNs3mm7iNcPw/edit" target="_blank" rel="noopener">Wealth Angle Swipes &rarr;</a>
          <a class="btn" href="https://docs.google.com/document/d/1nwFMETbJviUHKBKia3LMNKbxYr9HGvLCpRleztuWkRU/edit" target="_blank" rel="noopener">Love Angle Swipes &rarr;</a>
          <p class="hint" style="flex-basis:100%;margin-top:4px;color:#bdb4d6;font-size:13.5px">Opens in Google Docs. Match the swipe doc to the angle of your hoplink.</p>
        </div>
      </div>
    </section>

    <!-- AFFILIATE RULES -->
    <section class="section section--tight">
      <div class="wrap">
        <div class="panel" style="max-width:720px;margin:0 auto">
          <h2 style="font-size:26px">Affiliate Rules</h2>
          <p class="lead" style="margin:0 0 4px">Promoting this offer means agreeing to keep it clean. These protect your audience, the brand, and your own account standing.</p>
          <ul class="rules">
            <li><b>No spam.</b> Follow the CAN-SPAM Act: mail only people who opted in, use a truthful from-name and subject, and include a working unsubscribe. No purchased or scraped lists.</li>
            <li><b>No misleading claims.</b> No fabricated testimonials, invented results, or statements the offer does not support.</li>
            <li><b>No fake scarcity.</b> No false countdowns, fake stock counts, or deadlines that do not exist.</li>
            <li><b>No income guarantees.</b> Never promise money, earnings, or specific financial outcomes.</li>
            <li><b>No health or medical claims.</b> This is a reflective, entertainment-style reading, not therapy, medical, or professional advice.</li>
            <li><b>No brand-term or trademark bidding.</b> Do not run PPC on "Soul Mirror Reading", "Luna Ross", or close misspellings, and no direct-linking on brand terms.</li>
            <li><b>No coupon or discount misrepresentation.</b> Do not advertise coupons, deals, or discounts that are not officially offered.</li>
            <li><b>Always disclose.</b> Include a clear affiliate disclosure wherever you promote, and comply with ClickBank's affiliate policies and the FTC endorsement guides.</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- SUPPORT -->
    <section class="section section--tight center">
      <div class="wrap">
        <div class="support-box">
          <h3 style="font-size:22px">Questions Before You Promote?</h3>
          <p style="color:var(--text-muted);font-size:16px;margin-top:4px">Reach the affiliate team at <a href="mailto:affiliate@ignitevisionmedia.com">affiliate@ignitevisionmedia.com</a> and we will be glad to help.</p>
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
      <p><a href="/privacy-policy">Privacy Policy</a> &nbsp;&middot;&nbsp; <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;&middot;&nbsp; <a href="mailto:support@soulmirrorreading.com">Contact Us</a> &nbsp;&middot;&nbsp; <a href="/refund-return-policy">Refund &amp; Return Policy</a></p>
      <p>&copy; 2026 Soul Mirror Reading, A Luna Ross Brand. All Rights Reserved.</p>
    </footer>

  </main>

  <script>
    (function(){
      var VENDOR = 'rebornf';
      var cbid = document.getElementById('cbid');
      var tid = document.getElementById('tid');
      var genBtn = document.getElementById('genBtn');
      var result = document.getElementById('result');
      var hoplinkWealth = document.getElementById('hoplink-wealth');
      var hoplinkLove = document.getElementById('hoplink-love');
      var copyWealth = document.getElementById('copyWealth');
      var copyLove = document.getElementById('copyLove');

      function clean(v){ return (v || '').trim(); }

      function toggleGen(){ genBtn.disabled = clean(cbid.value) === ''; }
      cbid.addEventListener('input', toggleGen);
      toggleGen();

      function buildLink(page){
        var id = clean(cbid.value);
        if(!id) return '';
        var link = 'https://hop.clickbank.net/?affiliate=' + encodeURIComponent(id) + '&vendor=' + VENDOR + '&cbpage=' + page;
        var track = clean(tid.value);
        if(track) link += '&tid=' + encodeURIComponent(track);
        return link;
      }

      genBtn.addEventListener('click', function(){
        var wealth = buildLink('wealth');
        if(!wealth) return;
        hoplinkWealth.textContent = wealth;
        hoplinkLove.textContent = buildLink('love');
        result.classList.add('show');
        [copyWealth, copyLove].forEach(function(b){ b.classList.remove('copied'); b.textContent = 'Copy'; });
      });

      function copyText(text, btn, doneLabel){
        function done(){
          var original = doneLabel || 'Copy';
          btn.classList.add('copied');
          btn.textContent = 'Copied';
          setTimeout(function(){ btn.classList.remove('copied'); btn.textContent = original; }, 2000);
        }
        if(navigator.clipboard && navigator.clipboard.writeText){
          navigator.clipboard.writeText(text).then(done).catch(function(){ fallback(text); done(); });
        } else {
          fallback(text); done();
        }
      }

      function fallback(text){
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.setAttribute('readonly', '');
        ta.style.position = 'absolute';
        ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); } catch(e) {}
        document.body.removeChild(ta);
      }

      copyWealth.addEventListener('click', function(){
        var text = hoplinkWealth.textContent;
        if(text) copyText(text, copyWealth, 'Copy');
      });

      copyLove.addEventListener('click', function(){
        var text = hoplinkLove.textContent;
        if(text) copyText(text, copyLove, 'Copy');
      });

    })();
  </script>

</body>
</html>

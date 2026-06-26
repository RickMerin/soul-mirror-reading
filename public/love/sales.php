<?php
declare(strict_types=1);
$cssPath = __DIR__ . "/../assets/sales-v2-bundle.min.css";
$cssVer = is_file($cssPath) ? filemtime($cssPath) : time();
$jsPath = __DIR__ . "/../assets/sales-v2.min.js";
$jsVer = is_file($jsPath) ? filemtime($jsPath) : time();
$checkoutUrl = "https://rebornf.pay.clickbank.net/?cbitems=smr-1-l&template=order-4&cbfid=63457&exitoffer=exit-1";
?>
<!-- love sales.php 2026-06-13: love-angle rebuild on the wealth-v2 design (shared CSS bundle); copy reframed to the Love Block as the root pattern; item smr-1-l. -->
<!DOCTYPE html>
<html lang="en" data-funnel-base="love/">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <meta name="description" content="See the one core belief, your Love Block, behind your Love, Life, and Wealth cards. Deep card work, clearing practice, and 90-day prompts delivered with your Soul Mirror Reading.">
  <title>Your Soul Mirror Reading. The Love Block, Decoded From Your Cards</title>
  <link rel="icon" type="image/svg+xml" href="../favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&amp;family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&amp;family=Crimson+Pro:wght@300;400;500;600&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/sales-v2-bundle.min.css?v=<?= htmlspecialchars((string) $cssVer, ENT_QUOTES) ?>">
  <script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "wq82rtc2gf");
  </script>
<style id="tsl-style">
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
.tslwrap{max-width:600px;margin:24px auto 0;text-align:left;}
.tsl{font-family:'Crimson Pro',Georgia,serif;font-size:18px;line-height:1.65;color:#e9e2f2;margin-bottom:15px;}
.vsl-sub,.testi-body,.faq-a,.card-mirror p{font-size:18px !important;line-height:1.72 !important;}
.vip-list li,.vip-list li span,.price-note{font-size:18px !important;line-height:1.6 !important;}
.tslbold{color:#fff;font-weight:600;}
.pcard{color:#E8C97A;font-style:italic;font-weight:600;}
.pdate{color:#E8C97A;font-style:italic;}
.tslpull{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:20px;color:#E8C97A;border-left:3px solid #C9A14A;padding:16px 22px;margin:26px 0;background:rgba(201,161,74,.08);border-radius:0 8px 8px 0;line-height:1.5;}
.tslbridge{text-align:center;font-family:'Cormorant Garamond',serif;font-style:italic;font-size:18px;color:#cbb88a;margin:8px 0 0;}
body{background:#0b0718 !important;}
.dream-bg{filter:brightness(0.5) saturate(1.05) !important;}
.luna-hero{display:block;width:62%;max-width:320px;margin:6px auto 14px;border-radius:14px;border:1px solid rgba(212,175,55,.55);box-shadow:0 16px 44px rgba(0,0,0,.55);overflow:hidden;background:#160c34;}
.luna-cap{text-align:center;font-family:'Cormorant Garamond',serif;font-style:italic;font-size:18px;color:#cdb98c;letter-spacing:.02em;margin:0 0 24px;}
.vsl-headline{font-size:clamp(25px,6vw,36px) !important;line-height:1.07 !important;margin-bottom:14px !important;letter-spacing:0 !important;}
.vsl-headline em{font-size:0.93em;}
body,p,li,.tsl,.vsl-sub,.testi-body,.testi-name,.faq-q,.faq-a,.card-mirror p,.card-name-label,.vip-list,.vip-list li,.vip-list li span,.price-note,.luna-cap,.cta,.cta-trust-row,.cta-trust-row span,.topnotice,.topnotice strong,[style*="Cinzel"],[style*="Crimson"]{font-family:'Inter',system-ui,sans-serif !important;}
h1,h2,h3,h4,h5,.vsl-headline,.vsl-headline em,.tslpull{font-family:'Cormorant Garamond',Georgia,serif !important;}
.tsl,.vsl-sub,.testi-body,.faq-a,.vip-list li{letter-spacing:-0.003em;}
.tslfig{margin:30px 0;}
.tslmid{display:block;width:100%;border-radius:14px;border:1px solid rgba(212,175,55,.5);box-shadow:0 16px 44px rgba(0,0,0,.5);overflow:hidden;background:#160c34;}
.tslfigcap{text-align:center;font-family:'Cormorant Garamond',Georgia,serif;font-style:italic;font-size:18px;color:#cdb98c;letter-spacing:.02em;margin:10px 0 0;}

/* ============================================================
   THE GILDED SPREAD, MIRROR-CROWNED
   Drop-in redesign for the "These Are the Cards You Chose Today"
   section. Scoped with the .gs- prefix so it overrides the old
   .three-cards / .card-mirror tile look WITHOUT touching the JS
   injection contract (data-card-image / data-card-name / has-card / visible).
   ============================================================ */

.gs-subhead{
  text-align:center;
  max-width:560px;
  margin:0 auto 6px;
  color:var(--text);
}

/* opening divider under the subhead */
.gs-divider{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:14px;
  max-width:300px;
  margin:16px auto 34px;
}
.gs-divider span{
  flex:1;
  height:1px;
  background:linear-gradient(90deg,transparent,#d4af3766,transparent);
}
.gs-divider i{
  color:var(--gold);
  font-style:normal;
  font-size:14px;
  opacity:.85;
}

/* the three-card spread row, with a warm altar light pool behind it */
.gs-spread{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:22px;
  margin:0 auto;
  max-width:760px;
  align-items:start;
  position:relative;
}
.gs-spread::before{
  content:"";
  position:absolute;
  left:50%;
  top:46%;
  transform:translate(-50%,-50%);
  width:84%;
  height:74%;
  pointer-events:none;
  z-index:0;
  border-radius:50%;
  background:radial-gradient(60% 60% at 50% 50%,rgba(212,175,55,.13),rgba(212,175,55,0) 70%);
  filter:blur(6px);
}

/* each hero frame (glass card matching the system) */
.gs-frame{
  position:relative;
  z-index:1;
  text-align:center;
  background:linear-gradient(180deg,#2d1b6980,#1e0d40d9);
  border:1px solid #d4af3759;
  border-radius:16px;
  padding:20px 16px 22px;
  -webkit-backdrop-filter:blur(4px);
  backdrop-filter:blur(4px);
}

/* the ornate themed mirror, shown INTACT as a crowning emblem */
.gs-emblem{
  width:88px;
  height:104px;
  margin:0 auto 4px;
  display:flex;
  align-items:flex-end;
  justify-content:center;
}
.gs-emblem img{
  width:100%;
  height:100%;
  object-fit:contain;
  display:block;
  filter:drop-shadow(0 5px 14px rgba(0,0,0,.45));
}
/* themed colored glow per area (rose / blue / gold) */
.gs-frame-love   .gs-emblem img{ filter:drop-shadow(0 5px 14px rgba(0,0,0,.45)) drop-shadow(0 0 12px rgba(224,138,154,.42)); }
.gs-frame-life   .gs-emblem img{ filter:drop-shadow(0 5px 14px rgba(0,0,0,.45)) drop-shadow(0 0 12px rgba(127,160,224,.42)); }
.gs-frame-wealth .gs-emblem img{ filter:drop-shadow(0 5px 14px rgba(0,0,0,.45)) drop-shadow(0 0 12px rgba(232,201,122,.50)); }

.gs-kicker{
  font-family:'Cinzel',serif;
  font-size:10px;
  letter-spacing:.26em;
  text-transform:uppercase;
  color:var(--gold);
  opacity:.85;
  margin-bottom:14px;
}

/* stage holds the framed card window */
.gs-stage{
  position:relative;
  max-width:200px;
  margin:0 auto 16px;
}
/* thin gold frame just outside the window edge */
.gs-stage::before{
  content:"";
  position:absolute;
  inset:-7px;
  border:1px solid #d4af3766;
  border-radius:13px;
  pointer-events:none;
  z-index:2;
}
/* themed glow color fed to the window box-shadow */
.gs-frame-love   .gs-stage{ --gs-glow:rgba(224,138,154,.30); }
.gs-frame-life   .gs-stage{ --gs-glow:rgba(127,160,224,.30); }
.gs-frame-wealth .gs-stage{ --gs-glow:rgba(232,201,122,.34); }

/* THE INJECTION TARGET. Stays a DIV + keeps .card-wireframe.
   Portrait ratio locked in BOTH states so nothing reflows. */
.gs-window{
  position:relative;
  aspect-ratio:1/1.6;
  width:100%;
  max-width:200px;
  margin:0 auto;
  display:flex;
  flex-direction:column;
  justify-content:center;
  align-items:center;
  border:2px dashed #d4af3766;
  border-radius:9px;
  background-color:#ffffff08;
  background-position:50% 50%;
  background-size:cover;
  background-repeat:no-repeat;
  color:#d4af378c;
  overflow:hidden;
  box-shadow:inset 0 0 24px #1e0d4080, 0 0 26px var(--gs-glow,transparent);
}

/* faint numeral watermark behind the empty placeholder */
.gs-numeral{
  position:absolute;
  inset:0;
  display:flex;
  align-items:center;
  justify-content:center;
  font-family:'Cinzel',serif;
  font-size:88px;
  font-weight:700;
  color:#d4af3712;
  z-index:0;
  pointer-events:none;
  line-height:1;
  user-select:none;
}

/* FILLED STATE. JS adds .has-card after setting the inline
   background-image; we only restyle border / shadow, never the
   background shorthand, so the injected card survives at cover. */
.gs-window.has-card,
.card-wireframe.gs-window.has-card{
  border:1px solid #d4af37;
  background-color:transparent;
  box-shadow:0 8px 26px #00000059, 0 0 0 1px #d4af3733, 0 0 30px var(--gs-glow,transparent);
}
.gs-window.has-card .gs-numeral{ display:none; }
.gs-window.has-card .wf-content,
.card-wireframe.gs-window.has-card .wf-content{ display:none; }

/* EMPTY-STATE placeholder content */
.gs-window .wf-content{
  position:relative;
  z-index:1;
  text-align:center;
  line-height:1.3;
}
.gs-window .wf-icon{
  font-size:30px;
  color:#d4af3773;
  margin-bottom:7px;
  line-height:1;
}
.gs-window .wf-label{
  font-family:'Cinzel',serif;
  font-size:9px;
  letter-spacing:.22em;
  text-transform:uppercase;
  color:#d4af378c;
}

/* alive gold sheen on the EMPTY placeholder only (killed once filled) */
.gs-window::after{
  content:"";
  position:absolute;
  inset:0;
  z-index:1;
  pointer-events:none;
  border-radius:9px;
  opacity:0;
  background:linear-gradient(115deg,transparent 38%,rgba(232,201,122,.22) 50%,transparent 62%);
  background-size:220% 100%;
}
.gs-window.has-card::after{ display:none; }

/* labels under each frame */
.gs-area{
  font-family:'Cinzel',serif;
  font-size:13px;
  letter-spacing:.2em;
  text-transform:uppercase;
  color:var(--gold);
  margin-bottom:4px;
}
/* drawn card NAME. Forced to Cormorant to beat the global Inter
   override; stays hidden until JS adds .visible (display untouched). */
.gs-cardname{
  font-family:'Cormorant Garamond',serif !important;
  font-style:italic;
  font-size:18px;
  line-height:1.25;
  color:var(--gold-light);
  margin:0 0 10px;
}
.gs-cardname.visible{
  display:block;
  animation:gsNameIn .6s ease both;
}
@keyframes gsNameIn{
  from{ opacity:0; transform:translateY(4px); }
  to{ opacity:1; transform:translateY(0); }
}
.gs-frame p{
  margin:0;
  font-size:15px;
  line-height:1.55;
  color:var(--text);
}

/* connector: the trio resolves down into the Love Block */
.gs-connector{
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:8px;
  margin:34px auto 0;
}
.gs-connector-line{
  width:1px;
  height:34px;
  background:linear-gradient(180deg,transparent,#d4af3799);
}
.gs-connector-label{
  font-family:'Cinzel',serif;
  font-size:12px;
  font-weight:500;
  letter-spacing:.16em;
  text-transform:uppercase;
  color:var(--gold);
  opacity:1;
}
.gs-connector-arrow{
  color:var(--gold);
  font-size:18px;
  line-height:.6;
  opacity:.85;
}

/* featured Love Block payoff panel */
.gs-featured{
  display:flex;
  align-items:center;
  gap:24px;
  max-width:620px;
  margin:18px auto 0;
  padding:26px 30px;
  text-align:left;
  border:1px solid var(--gold-light);
  border-radius:16px;
  background:linear-gradient(180deg,#2d1b69,#1e0d40);
  box-shadow:0 8px 32px #d4af3733, inset 0 0 40px #d4af370d;
}
.gs-featured-emblem{
  flex-shrink:0;
  width:104px;
  height:120px;
  display:flex;
  align-items:center;
  justify-content:center;
}
.gs-featured-emblem img{
  width:100%;
  height:100%;
  object-fit:contain;
  display:block;
  filter:drop-shadow(0 6px 18px rgba(0,0,0,.55)) drop-shadow(0 0 14px rgba(212,175,55,.20));
}
.gs-featured-body h4{
  font-family:'Cinzel',serif;
  font-size:14px;
  letter-spacing:.16em;
  text-transform:uppercase;
  color:var(--gold-light);
  line-height:1.5;
  margin:0 0 10px;
}
.gs-featured-body p{
  margin:0;
  font-size:15px;
  line-height:1.6;
  color:var(--text);
}

/* alive empty-state sheen, only when motion is allowed */
@media (prefers-reduced-motion:no-preference){
  .gs-window:not(.has-card)::after{
    animation:gsSheen 5.5s ease-in-out infinite;
  }
  @keyframes gsSheen{
    0%{ opacity:0; background-position:160% 0; }
    18%{ opacity:.5; }
    40%{ opacity:0; background-position:-60% 0; }
    100%{ opacity:0; background-position:-60% 0; }
  }
}

/* ---------- mobile: premium single-column stack ---------- */
@media (max-width:720px){
  .gs-spread{
    grid-template-columns:1fr;
    gap:30px;
    max-width:340px;
  }
  .gs-spread::before{ display:none; }
  .gs-stage{ max-width:150px; }
  .gs-window{ max-width:150px; }
  .gs-cardname{ font-size:18px; }
  .gs-featured{
    flex-direction:column;
    text-align:center;
    gap:16px;
    padding:26px 22px;
  }
  .gs-featured-body h4{ letter-spacing:.14em; }
}

/* ---------- small phones ---------- */
@media (max-width:400px){
  .gs-spread{ max-width:300px; }
  .gs-emblem{ width:78px; height:92px; }
}

/* ---------- respect reduced motion ---------- */
@media (prefers-reduced-motion:reduce){
  .gs-cardname.visible{ animation:none; }
  .gs-window:not(.has-card)::after{ animation:none; opacity:0; }
}
</style></head>

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


<div class="topnotice" style="background:#9b1c1c;color:#fff;border-bottom:1px solid #c0392b;">
  <strong><span class="firstname">Friend</span>, Your Free Reading Is On Its Way</strong><span class="notice-dot" style="color:#f3b0a0;">&middot;</span><strong>Read This First, It Changes How You Recognize Real Love When It Comes</strong>
</div>



<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     HERO + VSL
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section class="vsl-section">
  <div class="wrap">


<h1 class="vsl-headline">There Is <span style="color:var(--gold-light);">One Hidden Pattern</span> Beneath Everything You've Struggled With.</h1>




<p class="vsl-sub">Your three cards have warned you about this for years. Ignored long enough, it quietly costs you <span style="color:var(--gold-light);text-decoration:underline;text-decoration-color:rgba(212,175,55,0.6);text-decoration-thickness:2px;text-underline-offset:4px;">the love you are meant for</span>.</p>
<div class="tslwrap">
<video class="luna-hero" autoplay loop muted playsinline preload="auto" poster="../assets/luna-portrait.jpg" aria-label="Luna Ross, your reader">
<source src="../assets/luna-motion.mp4?v=3" type="video/mp4">
<img src="../assets/luna-portrait.jpg" alt="Luna Ross, your reader">
</video>
<p class="luna-cap">Luna Ross, your reader</p>
<p class="tsl"><span class="firstname">Friend</span>, here on <span class="pdate">today</span>, I have all three of your cards in front of me.</p>
<p class="tsl cards-line" style="display:none"><span class="pcard" data-card="love">your Love card</span> in your Love house. <span class="pcard" data-card="life">your Life card</span> in your Life. <span class="pcard" data-card="wealth">your Wealth card</span> in your Wealth.</p>
<p class="tsl">The moment I laid them out, one pattern stepped forward.</p>
<p class="tsl">There is a reason the love you can feel is meant for you keeps not quite arriving.</p>
<p class="tsl">The almost-ones. The near-misses. The wrong people who felt familiar, and the long stretches alone in between. Different faces, the same quiet ending.</p>
<p class="tsl">Sometimes it starts to be real. A connection you can feel, a closeness you can almost lean into, a person you can nearly let in.</p>
<p class="tsl">Then, right as it gets close, something in you braces. You pull back a little, or look past the steady one toward someone who was never quite available, and the distance returns.</p>
<p class="tsl"><span class="pcard" data-card="love">your Love card</span> landing in your Love house is where I see it most clearly.</p>
<p class="tsl">You have tried the things that were supposed to bring it. The apps. The advice. The therapy. The courses.</p>
<p class="tsl">Each one helped for a while. Then the right love stayed just out of reach again, the same way it always has.</p>
<p class="tsl">That is not about trying harder, <span class="firstname">Friend</span>. And it was never your fault.</p>
<p class="tsl">They all aimed at the symptom. None of them touched the root.</p>
<p class="tsl">It is a single belief you formed before you had words for it, often one you quietly inherited. A belief about whether love is safe to have, and safe to keep, once it is truly yours.</p>
<p class="tsl">It runs underneath your love first, and quietly underneath your money and your sense of purpose too, all at the same time.</p>
<p class="tsl">This is your <span class="tslbold">Love Block</span>. And your three cards have been pointing straight at it.</p>
<figure class="tslfig"><video class="tslmid" autoplay loop muted playsinline preload="auto" poster="../assets/luna-hands-cards.jpg" aria-label="Luna reading your three cards"><source src="../assets/luna-hands-motion.mp4?v=1" type="video/mp4"><img class="tslmid" src="../assets/luna-hands-cards.jpg" alt="Luna reading your three cards"></video><figcaption class="tslfigcap">The three cards you drew, as I read them.</figcaption></figure>
<p class="tsl">It speaks loudest in love, because your closest relationships keep the most honest record of who you let near.</p>
<p class="tsl">Every time something in you whispers "not this one, not too close, not yet," you are following an instruction you did not knowingly write.</p>
<p class="tsl">Here is what it is doing right now.</p>
<p class="tsl">It is keeping one hand on the door of your own heart, holding the right person at exactly the distance you have learned to expect. And it is patient.</p>
<p class="tsl">It does not need a heartbreak to win. It only needs you to keep deciding, quietly, that you are not ready to be fully met and fully kept.</p>
<p class="tsl">Picture six months from now. The same near-miss. The same quiet bracing the moment someone real gets close, where you give more, let in less, and call it normal.</p>
<p class="tsl">A year from now, the distance has compounded. Another person you almost let stay, and a quieter part of you that has stopped expecting your person to arrive at all.</p>
<div class="tslpull">The block does not fight you. It waits you out. Left unnamed, it keeps the right love at arm's length, because you cannot let in what you cannot see you are pushing away.</div>
<p class="tsl">The free preview on its way to your inbox will confirm your Love Block is there, <span class="firstname">Friend</span>. It can show you its shape.</p>
<p class="tsl">What it cannot do is read <span class="pcard" data-card="love">your Love card</span>, <span class="pcard" data-card="life">your Life card</span>, and <span class="pcard" data-card="wealth">your Wealth card</span> together.</p>
<p class="tsl">That is your Soul Mirror Reading.</p>
<p class="tsl">I read all three by hand, <span class="firstname">Friend</span>, and show you the precise belief keeping your person at arm's length, the exact moves it makes the moment real closeness arrives, and the one practice that interrupts it where it actually lives.</p>
<p class="tsl">Hand-written for your cards. In your inbox within 24 hours.</p>
<p class="tsl">Your cards have already shown me what it is doing. The reading is how you finally see it, stop bracing when love gets close, and let the right one come closer, and stay.</p>
</div>







    </div>
</section>

<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     CTA #1, RIGHT AFTER VSL (NEW)
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->


<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     3-CARD BREAKDOWN (existing, now personalized + headline updated)
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section class="section">
  <div class="wrap">
    <h2><span class="firstname">Friend</span>, These Are the Cards<br><em>You Chose Today</em></h2>
    <p class="gs-subhead">Three cards. Three mirrors. One hidden belief about whether love is safe to keep, running all of them. This is what your reading has been pointing to.</p>

    <div class="gs-divider" aria-hidden="true"><span></span><i>&#10022;</i><span></span></div>

    <div class="gs-spread">

      <div class="gs-frame gs-frame-love" data-card-slot="love">
        <div class="gs-emblem"><img src="https://soulmirrorreading.com/cards/mirror-love.png" alt="Love Mirror"></div>
        <div class="gs-kicker">Mirror One</div>
        <div class="gs-stage">
          <div class="gs-window card-wireframe" data-card-image="love">
            <span class="gs-numeral" aria-hidden="true">I</span>
            <div class="wf-content">
              <div class="wf-icon">&#10022;</div>
              <div class="wf-label">Your Card</div>
            </div>
          </div>
        </div>
        <div class="gs-area">Love</div>
        <div class="gs-cardname card-name-label" data-card-name="love"></div>
        <p>Where you brace, or quietly pull back, the moment real closeness arrives. The pattern that keeps the love you can feel is meant for you just out of reach.</p>
      </div>

      <div class="gs-frame gs-frame-life" data-card-slot="life">
        <div class="gs-emblem"><img src="https://soulmirrorreading.com/cards/mirror-life.png" alt="Life Mirror"></div>
        <div class="gs-kicker">Mirror Two</div>
        <div class="gs-stage">
          <div class="gs-window card-wireframe" data-card-image="life">
            <span class="gs-numeral" aria-hidden="true">II</span>
            <div class="wf-content">
              <div class="wf-icon">&#10022;</div>
              <div class="wf-label">Your Card</div>
            </div>
          </div>
        </div>
        <div class="gs-area">Life</div>
        <div class="gs-cardname card-name-label" data-card-name="life"></div>
        <p>Where the same belief shapes your choices and your sense of purpose. The place you feel most stuck, showing you the exact map you have been following in love and out of it.</p>
      </div>

      <div class="gs-frame gs-frame-wealth" data-card-slot="wealth">
        <div class="gs-emblem"><img src="https://soulmirrorreading.com/cards/mirror-wealth.png" alt="Wealth Mirror"></div>
        <div class="gs-kicker">Mirror Three</div>
        <div class="gs-stage">
          <div class="gs-window card-wireframe" data-card-image="wealth">
            <span class="gs-numeral" aria-hidden="true">III</span>
            <div class="wf-content">
              <div class="wf-icon">&#10022;</div>
              <div class="wf-label">Your Card</div>
            </div>
          </div>
        </div>
        <div class="gs-area">Wealth</div>
        <div class="gs-cardname card-name-label" data-card-name="wealth"></div>
        <p>What you believe you are allowed to receive and keep. The same inherited story about deserving, quietly shaping your money the way it shapes your love.</p>
      </div>

    </div>

    <div class="gs-connector" aria-hidden="true">
      <span class="gs-connector-line"></span>
      <span class="gs-connector-label">Three Cards, One Root</span>
      <span class="gs-connector-arrow">&#9662;</span>
    </div>

    <div class="gs-featured">
      <div class="gs-featured-emblem"><img src="https://soulmirrorreading.com/cards/mirror-block.png" alt="Love Block"></div>
      <div class="gs-featured-body">
        <h4>The Hidden Layer.<br>This Is Your Love Block.</h4>
        <p>The one belief, about whether love is safe to have and to keep, running all three. Until it is named clearly, every reading you ever get will point to the same wall between you and your person.</p>
      </div>
    </div>


  </div>
</section>

<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     DIAGNOSTIC PROOF (NEW v2)
     "This Is a Diagnosis. Not a Prediction."
     Diagnosis framing: three cards read as one connected system
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section class="section" style="position:relative;">
  <div class="wrap">
    <h2>This Is a <em>Diagnosis.</em> Not a Prediction.</h2>



<p style="text-align:center; max-width:600px; margin:0 auto 18px;">Your three cards point to one belief sitting at the root of who you let close, and who you keep at arm's length. This is not only about your love life: the same belief is quietly shaping your money and your purpose too. Name it once, and all three start to move.</p>

    <p style="text-align:center; max-width:600px; margin:0 auto 36px;">This is not a horoscope. Like a doctor reading three symptoms together instead of one at a time, it reads the <strong style="color:var(--gold-light);">three cards you actually drew</strong>, your Love card first, then your Life and your Wealth, and the houses they landed in, as one connected system. That is what turns an interesting card into the exact reason the right love keeps almost arriving and then slipping away. Built from your cards. Never a script.</p>



    <!-- Down arrow / decoding indicator -->
    <div style="text-align:center; margin:24px auto 8px;">
      <div class="gs-connector-label" style="margin-bottom:8px;">Now The Pattern Has A Name</div>
      <div style="color:var(--gold); font-size:24px; line-height:1;">&darr;</div>
    </div>

    <!-- Diagnosis card (always visible, no tap gate) -->
    <div style="max-width:560px; margin:16px auto 0;">
      <div style="background: linear-gradient(180deg, rgba(45,27,105,0.65), rgba(30,13,64,0.92)); border: 1px solid var(--gold); border-radius: 14px; padding: 32px 28px; box-shadow: 0 16px 40px rgba(0,0,0,0.35); text-align: center;">

        <h4 data-mirror-block-name style="font-family:'Cormorant Garamond',serif; font-size:28px; color:#fff; font-weight:600; margin-bottom:14px; line-height:1.25;">The Love Meant For You Is Blocked</h4>
        <p data-mirror-block-summary style="font-size:18px; color:rgba(255,255,255,0.85); line-height:1.7; margin:0 0 20px; font-style:italic;">The right love almost arrives. Then it quietly slips away, or you brace and hold it at a distance. The same wall, near-miss after near-miss, no matter who it is.</p>
        <div style="border-top:1px solid rgba(212,175,55,0.25); padding-top:18px; font-size:18px; color:rgba(255,255,255,0.78); line-height:1.6;">Your Soul Mirror Reading shows you <strong style="color:var(--gold-light);">exactly how</strong> your Love Block is working, and the one practice that clears it. Not more dating apps, not another book, not loving harder. Just one quiet shift, so you stop recognizing the wrong people as familiar and stop bracing when the right one gets close. The love you have always been able to feel is meant for you can finally come <strong style="color:var(--gold-light);">closer</strong>, and you can let it stay.</div>
      </div>
    </div>



    <!-- CTA after diagnostic proof -->
    <div style="text-align:center; margin-top:40px;">
      <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">Clear My Love Block &rarr;</a>
      <p style="max-width:480px; margin:14px auto 0; font-size:14px; line-height:1.6; color:rgba(255,255,255,0.72);">Your free cards named the belief keeping your person at arm's length. Your full reading reads all three together, your Love card first, and shows you <strong style="color:var(--gold-light);">exactly how it works</strong>, where it took root, and the practice that clears it. Hand-written for your cards, in your inbox within 24 hours.</p>
      <div class="cta-trust-row" style="margin-top:16px;">
        <span>&#128274; Secure Checkout</span>
        <span>&#9889; Delivered in 12-24 Hrs</span>
      </div>
      <p style="max-width:500px; margin:12px auto 0; font-size:13px; line-height:1.6; color:rgba(255,255,255,0.62);"><span style="color:var(--gold-light);">&#127769; 90-day promise.</span> Read it. If the Love Block I name does not feel unmistakably like your life, reply and I refund every penny. You keep the reading and all four bonuses.</p>
      <p style="max-width:460px; margin:18px auto 0; font-size:13px; font-style:italic; line-height:1.6; color:rgba(255,255,255,0.6);">&ldquo;It named the exact belief that had quietly kept me at arm's length for years. A few weeks after the clearing practice, I stopped reaching for the unavailable ones, and when a steady, kind man got close I finally let him in instead of pulling back. For the first time, I let it stay.&rdquo; <span style="color:var(--gold-light); font-style:normal;">Hannah T., 44</span></p>
    </div>
  </div>
</section>

<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     REBECCA TESTIMONIAL (PLANNED ABOVE INSIDE)
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section style="padding: 0 0 20px;">
  <div class="wrap">
    <div class="testi-card">
      <div class="testi-avatar-row">
        <picture>
          <source type="image/webp" srcset="https://soulmirrorreading.com/frontend/images/sales/rebecca-hartley.webp">
          <img class="testi-avatar" src="https://soulmirrorreading.com/frontend/images/sales/rebecca-hartley.png" alt="Rebecca Hartley" decoding="async" loading="lazy">
        </source></picture>
        <div>
          <div class="testi-name">Rebecca Hartley</div>
          <div class="testi-meta">47 &middot; Graphic designer</div>
        </div>
      </div>
      <div class="testi-stars">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
      <p class="testi-body">"I almost closed the tab. After ten years of apps and readings that all said the same gentle thing, I expected more of the same. It was not. The Love Block Luna named was the exact reason I spent fifteen years over-giving and then quietly pulling back the moment anyone got close. Two weeks later the man I had been seeing asked to make things real. Normally I would have found a careful reason to disappear. I sat with what the reading showed me and let him in instead. He stayed the weekend, and for once I did not spend it waiting for it to end. I am not saying the cards did it. I am saying they finally let me see the wall I had been keeping love behind."</p>
    </div>
  </div>
</section>

<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     INSIDE YOUR SOUL MIRROR READING
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section class="section">
  <div class="wrap">
    <div class="vip-box">
      <h2>Inside Your <em>Soul Mirror Reading</em></h2>
      <img src="https://soulmirrorreading.com/frontend/images/sales/soul-mirror-reading.png" alt="Soul Mirror Reading" class="vip-mockup">
      <div class="vip-valued">Valued at <span class="amount">$197</span></div>
      <ul class="vip-list">
        <li><span><strong>Your Love Block Identified.</strong> The one hidden belief keeping the right love at arm's length, the same belief quietly shaping your Love, Life, and Wealth at once.</span></li>
        <li><span><strong>Love Mirror Deep-Dive.</strong> What your Love card reveals about why your person keeps almost arriving, read in the context of all three cards.</span></li>
        <li><span><strong>Life Mirror Deep-Dive.</strong> How the same block shapes your days and your sense of purpose, and what it would take to shift it.</span></li>
        <li><span><strong>Wealth Mirror Deep-Dive.</strong> The same inherited story about what you are allowed to receive, the one that decides whether you let love stay, quietly setting your ceiling with money too.</span></li>
        <li><span><strong>Mirror Block Clearing Practice.</strong> Seven questions, ten minutes, designed to begin loosening the wall you have been keeping love behind.</span></li>
        <li><span><strong>Reversed Card Companion.</strong> Nuanced interpretation if any of your cards appeared reversed.</span></li>
        <li><span><strong>90-Day Mirror Check-In Prompts.</strong> Twelve weekly questions to keep you letting love close instead of bracing the moment it gets near.</span></li>
      </ul>

      <!-- CTA inside the VIP box -->
      <div style="text-align:center; margin-top:32px;">
        <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">Clear My Love Block &rarr;</a>
        <div class="cta-trust-row" style="margin-top:14px;">
          <span>&#128274; Secure Checkout</span>
          <span>&#9889; Delivered in 12-24 Hrs</span>
          <span>&#127769; 90-Day Guarantee</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     BONUSES (COMPRESSED GRID, NEW)
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section class="section" style="position:relative;">
  <div class="wrap">
    <h2>4 Bonuses Included <em>Free</em></h2>
    <p style="text-align:center; max-width:520px; margin:0 auto 8px;">Available only on this page, when you claim your Soul Mirror Reading now. Each one helps you recognize the right love, and stop bracing when it comes.</p>

    <div class="bonus-grid">
      <div class="bonus-card-compact">
        <span class="free-badge">FREE</span>
        <img src="https://soulmirrorreading.com/frontend/images/sales/mirror-block-companion-guide.png" alt="Companion Guide">
        <div class="bonus-num">Bonus 1</div>
        <h4>Mirror Block Companion Guide</h4>
        <div class="value">Valued at $67</div>
      </div>
      <div class="bonus-card-compact">
        <span class="free-badge">FREE</span>
        <img src="https://soulmirrorreading.com/frontend/images/sales/21-days-shift-tracker.png" alt="Shift Tracker">
        <div class="bonus-num">Bonus 2</div>
        <h4>21-Day Shift Tracker</h4>
        <div class="value">Valued at $47</div>
      </div>
      <div class="bonus-card-compact">
        <span class="free-badge">FREE</span>
        <img src="https://soulmirrorreading.com/frontend/images/sales/root-cause-reading-guide.png" alt="Root Cause Guide">
        <div class="bonus-num">Bonus 3</div>
        <h4>Root Cause Reading Guide</h4>
        <div class="value">Valued at $47</div>
      </div>
      <div class="bonus-card-compact">
        <span class="free-badge">FREE</span>
        <img src="https://soulmirrorreading.com/frontend/images/sales/mirror-clarity-meditation.png" alt="Clarity Meditation">
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

<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     CONSOLIDATED SCARCITY + COUNTDOWN + PRICE ANCHOR (ALS-style)
     Replaces: Daily Cap + Objection Killer + standalone Countdown
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section style="padding: 60px 24px 40px; text-align:center; position:relative;">
  <div class="wrap">
    <h2 style="margin-bottom:18px;"><span class="firstname">Friend</span>, Your Soul Mirror Reading<br>Is Written by Hand.<br><em>Order Today, The Love Meant For You Is One Reading Closer.</em></h2>

    <p style="max-width:560px; margin:0 auto 14px; color:rgba(255,255,255,0.85); line-height:1.7;">Luna reads your Love card first, then your Life and your Wealth, and writes every reading by hand from your specific 3-card combination. Order now and yours goes into today's writing queue, delivered straight to your inbox within 24 hours.</p>

    <p style="max-width:560px; margin:0 auto 32px; color:rgba(255,255,255,0.7); font-style:italic;">The exact combination you drew today is what makes the diagnosis precise, the reason the right love keeps slipping away just as it gets close. Come back in a week and the cards, and the reading, will be different.</p>


    <p style="max-width:560px; margin:0 auto 24px; color:rgba(255,255,255,0.85); line-height:1.7;">Luna's private readings run <strong style="color:var(--gold-light); text-decoration:line-through; text-decoration-color: rgba(212,175,55,0.5);">$395</strong>.<br>Today, the complete package with all 4 bonuses, and the belief keeping your person at arm's length finally named, is just <strong style="color:var(--gold-light); font-size:18px;">$37</strong>.</p>

    <div style="text-align:center; margin-top:24px;">
      <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">Get My Full Reading &middot; $37 &rarr;</a>
      <div class="cta-trust-row" style="margin-top:16px;">
        <span>&#127769; 90-Day Money-Back Guarantee</span>
        <span>&#9889; Delivered Within 24 Hours</span>
        <span>&#128274; Secure Checkout</span>
      </div>
    </div>
  </div>
</section>

<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     PRICING BLOCK WITH INLINE GUARANTEE (NEW)
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
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
    <p class="price-note">One-time payment &middot; Delivered within 12 to 24 hours</p>

    <ul class="vip-list" style="max-width:440px;">
      <li><span>Your personalised Soul Mirror Reading, the one belief keeping love at arm's length, named. <em>$197 value</em></span></li>
      <li><span>Love Block identification, Love card read first, plus all 3 deep-dives</span></li>
      <li><span>Love Block Clearing Practice + 90-day prompts to stop bracing when love gets close</span></li>
      <li><span><strong>Bonus 1.</strong> Companion Guide ($67)</span></li>
      <li><span><strong>Bonus 2.</strong> 21-Day Shift Tracker ($47)</span></li>
      <li><span><strong>Bonus 3.</strong> Root Cause Guide ($47)</span></li>
      <li><span><strong>Bonus 4.</strong> Clarity Meditation ($37)</span></li>
    </ul>

    <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">Get My Full Reading &middot; $37 &rarr;</a>

    <div class="pricing-inline-guarantee">
      <img src="https://soulmirrorreading.com/cards/guarantee-badge.png" alt="90-Day Guarantee">
      <div><strong>90-Day Money-Back Guarantee.</strong><br>Read it. If the Love Block I name does not feel unmistakably like your life, reply and I refund every penny. You keep the reading and all four bonuses.</div>
    </div>

    <div class="trust-badge-row">
      <span>&#128274; Secure Checkout</span>
      <span>&#9889; Delivered in 12-24 Hrs</span>
      <span>&#9733;&#9733;&#9733;&#9733;&#9733; 4,800+ Readings</span>
    </div>
  </div>
</section>

<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     TESTIMONIALS CLUSTER
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section class="section">
  <div class="wrap">
    <h2>What 4,800 Readings <em>Have Looked Like</em></h2>

    <div class="testi-card">
      <div class="testi-avatar-row">
        <picture><source type="image/webp" srcset="https://soulmirrorreading.com/frontend/images/frontend/testimonial-diane-r.webp"><img class="testi-avatar" src="https://soulmirrorreading.com/frontend/images/frontend/testimonial-diane-r.png" alt="Diane R." width="56" height="56" decoding="async" loading="lazy"></picture>
        <div>
          <div class="testi-name">Diane R.</div>
          <div class="testi-meta">54 &middot; Retired teacher</div>
        </div>
      </div>
      <div class="testi-stars">&#9733; &#9733; &#9733; &#9733; &#9733;</div>


<p class="testi-body">"I have had tarot readings for twenty years and always felt one piece was missing. This gave me the piece. The Love Block Luna named was the same reason I kept choosing men who could never quite stay, and then blaming myself when they left. One belief, sitting under my love, my work, and my money all at once. Six weeks later I stopped reaching for the familiar ache, and the steady, ordinary man I had almost overlooked is the one still here. Closer than I have let anyone be in years."</p>


    </div>

    <div class="testi-card">
      <div class="testi-avatar-row">
        <picture><source type="image/webp" srcset="https://soulmirrorreading.com/frontend/images/frontend/testimonial-james-h.webp"><img class="testi-avatar" src="https://soulmirrorreading.com/frontend/images/frontend/testimonial-james-h.png" alt="James H." width="56" height="56" decoding="async" loading="lazy"></picture>
        <div>
          <div class="testi-name">James H.</div>
          <div class="testi-meta">48 &middot; Business owner</div>
        </div>
      </div>
      <div class="testi-stars">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
      <p class="testi-body">"I am a practical person and I only wanted to see what the cards said. The Love Block explanation stopped me cold. It named the exact thing I do every time someone gets close, I pull away first so I am never the one left behind. The very next person who mattered, I stayed in the conversation instead of going cold. We are still together. That one change has been worth more than I can put a price on."</p>
    </div>

    <div class="testi-card">
      <div class="testi-avatar-row">
        <picture><source type="image/webp" srcset="https://soulmirrorreading.com/frontend/images/frontend/testimonial-carolyn-m.webp"><img class="testi-avatar" src="https://soulmirrorreading.com/frontend/images/frontend/testimonial-carolyn-m.png" alt="Carolyn M." width="56" height="56" decoding="async" loading="lazy"></picture>
        <div>
          <div class="testi-name">Carolyn M.</div>
          <div class="testi-meta">61 &middot; Holistic practitioner</div>
        </div>
      </div>
      <div class="testi-stars">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
      <p class="testi-body">"Three cards, one pattern. I have spent years in therapy trying to understand why love kept arriving and then thinning out, the same story in different faces. This showed me in twenty minutes. The belief it named was the reason I never felt safe enough to let the good ones stay. The clearing practice alone gave me back something I thought I had aged out of, the feeling that the right love could still find me, and that I would not push it away this time."</p>
    </div>
    <p style="text-align:center; max-width:580px; margin:22px auto 0; font-size:11.5px; color:rgba(255,255,255,0.45); font-style:italic; line-height:1.6;">Individual results vary and are not typical. A Soul Mirror Reading is for insight and self-reflection. It is not relationship advice and does not guarantee any specific outcome.</p>

    <!-- CTA after testimonials cluster -->
    <div style="text-align:center; margin-top:40px;">
      <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">Get My Full Reading &middot; $37 &rarr;</a>
      <div class="cta-trust-row" style="margin-top:16px;">
        <span>&#128274; Secure Checkout</span>
        <span>&#9889; Delivered in 12-24 Hrs</span>
        <span>&#127769; 90-Day Guarantee</span>
      </div>
    </div>
  </div>
</section>

<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     TWO PATHS FROM HERE (NEW)
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->

<section class="section" style="position:relative;">
  <div class="wrap">
    <h2>Two Paths <em>From Here</em></h2>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:32px;">
      <!-- Path A -->
      <div style="background: linear-gradient(180deg, rgba(45,27,105,0.6), rgba(30,13,64,0.85)); border: 1px solid rgba(212,175,55,0.45); border-radius: 14px; padding: 28px 22px; backdrop-filter: blur(4px);">
        <div style="font-family:'Cinzel',sans-serif; font-size:11px; letter-spacing:0.22em; color:var(--gold); text-transform:uppercase; margin-bottom:14px;">Path A</div>
        <h3 style="font-family:'Cormorant Garamond',serif; font-size:22px; color:#fff; font-weight:600; line-height:1.3; margin-bottom:14px;">You <em style="color:var(--gold-light); font-style:italic;">See It Clearly</em></h3>
        <p style="font-size:18px; color:rgba(255,255,255,0.85); line-height:1.7; margin:0;">Within 24 hours, your Soul Mirror Reading lands in your inbox. You read it once on your couch. The next morning you read it again, slower. You see the single belief that has kept the right love almost arriving and then slipping away, that has drawn you to the unavailable and made you go cold first, and how that same belief has quietly capped your money and your sense of purpose too. The work begins from there. Most people tell me it takes 21 days before they notice they have stopped bracing the moment love gets close, the way they have for years, and start recognizing the steady ones they used to overlook.</p>
      </div>
      <!-- Path B -->
      <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.12); border-radius: 14px; padding: 28px 22px; backdrop-filter: blur(4px);">
        <div style="font-family:'Cinzel',sans-serif; font-size:11px; letter-spacing:0.22em; color:rgba(255,255,255,0.5); text-transform:uppercase; margin-bottom:14px;">Path B</div>
        <h3 style="font-family:'Cormorant Garamond',serif; font-size:22px; color:rgba(255,255,255,0.7); font-weight:600; line-height:1.3; margin-bottom:14px;">You <em style="color:rgba(255,255,255,0.55); font-style:italic;">Don't.</em></h3>
        <p style="font-size:18px; color:rgba(255,255,255,0.6); line-height:1.7; margin:0;">You close this page. You finish your tea. The block keeps running. Six months from now, you are back here. Or with someone new, watching the closeness slowly pull away again, or quietly overlooking the person who was right in front of you, the same wall in different paint. The cards you chose minutes ago will not carry the same precision a week from now. The window narrows quietly.</p>
      </div>
    </div>



<div style="text-align:center; margin-top:40px;">
      <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">Take Path A &middot; Get My Full Reading &middot; $37 &rarr;</a>
      <div class="cta-trust-row" style="margin-top:16px;">
        <span>&#128274; Secure Checkout</span>
        <span>&#9889; Delivered in 12-24 Hrs</span>
        <span>&#127769; 90-Day Guarantee</span>
      </div>
    </div>


  </div>
</section>

<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     FAQ (PROCESS FOLDED IN)
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section class="faq-section">
  <div class="wrap">
    <h2>The <em>Honest</em> Answers</h2>

    <div class="faq" style="margin-top:32px;">
      <details>
        <summary>What is a Love Block exactly?</summary>
        <p class="faq-a">A Love Block is a hidden belief about whether love is safe to have and to keep, usually formed before you had words for it and often inherited from someone else. It quietly draws you toward the unavailable, makes you brace the moment real closeness arrives, or keeps you invisible to the person actually meant for you. The same belief shows up in your daily life and your relationship with money and purpose too. It is not a flaw and it is not permanent. But it is specific, and once you can see it clearly, it loses most of its power.</p>
      </details>
      <details>
        <summary>How is this different from the reading I will receive in my email?</summary>


<p class="faq-a">The email reading shows you what each card says in isolation. The Soul Mirror Reading decodes what your Love card, your Life card, and your Wealth card mean together, with your Love card read most closely. The belief keeping the right love at arm's length, the single thread connecting all three, and the Clearing Practice to begin loosening it. One card is information. Three cards read as a system is a diagnosis.</p>


      </details>
      <details>
        <summary>What happens after I order?</summary>
        <p class="faq-a">Your reading takes 12 to 24 hours to complete, hand-written by Luna for your specific card combination, your Love card read first. You will receive it as a PDF in your inbox. Use the Clearing Practice once. Seven questions, ten minutes. The shift begins there.</p>
      </details>
      <details>
        <summary>What if I am not satisfied?</summary>
        <p class="faq-a">You are covered by a 90-day guarantee. Read your reading. If the Love Block I name does not feel unmistakably like your own love life, if it reads like something that could be sent to anyone, reply to the delivery email and I refund every penny. You keep the reading and all four bonuses. In nineteen years, that has almost never happened.</p>
      </details>
    </div>
  </div>
</section>

<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     FINAL CTA
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section style="padding: 0 24px 60px;">
  <div class="final-cta">
    <h2><span class="firstname">Friend</span>, Your Cards Are Drawn.<br><em>The Love Meant For You Is Closer Than The Block Has Let You Feel.</em></h2>


<p style="max-width:560px; margin:18px auto 24px; color:rgba(255,255,255,0.85);">The block is already there. It has been running quietly for years, keeping the right love almost arriving and then slipping away, and quietly capping your money and your sense of purpose too. The only question is whether you let another year pass without seeing it clearly, while your person stays just out of reach.</p>



    <div style="margin:28px auto 12px;">
      <img src="https://soulmirrorreading.com/frontend/images/sales/bundle-product-image.png" alt="Soul Mirror Reading complete bundle" style="max-width:420px; width:100%; height:auto; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.4));">
    </div>

    <div style="margin:24px 0;">
      <div style="font-family:'Cinzel',sans-serif; font-size:11px; letter-spacing:0.22em; color:var(--gold); text-transform:uppercase; margin-bottom:6px;">Total Value $395 &middot; Today</div>
      <div style="font-family:'Cormorant Garamond',serif; font-size:48px; color:var(--gold-light); font-weight:600;">$37</div>
    </div>

    <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta">Get My Full Reading &middot; $37 &rarr;</a>
    <p style="margin-top:18px; font-size:13px; color:rgba(255,255,255,0.6);">Instant access &middot; 90-day guarantee &middot; Secure checkout</p>
  </div>
</section>

  </main>

  <!-- &#9552;&#9552; FOOTER &#9552;&#9552; -->
  <footer class="site-footer js-reveal">
    <div class="footer-legal-copy">
      <p>ClickBank is the retailer of products on this site. CLICKBANK&reg; is a registered trademark of Click Sales, Inc.,
        a Delaware corporation located at 1444 S. Entertainment Ave., Suite 410 Boise, ID 83709, USA and used by
        permission. ClickBank's role as retailer does not constitute an endorsement, approval or review of these
        products or any claim, statement or opinion used in promotion of these products.</p>
      <p>For Product Support, please contact the vendor: <a href="mailto:support@soulmirrorreading.com">HERE</a></p>
      <p>For Order Support, please contact ClickBank: <a href="https://www.clkbank.com/" target="_blank" rel="noopener">HERE</a> or 1-800-390-6035</p>
      <p class="footer-links">
        <a href="/privacy-policy">Privacy Policy</a> &nbsp;&middot;&nbsp;
        <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;&middot;&nbsp;
        <a href="mailto:support@soulmirrorreading.com">Contact Us</a> &nbsp;&middot;&nbsp;
        <a href="/refund-return-policy">Refund &amp; Return Policy</a>
      </p>
      <p>Copyright &copy; 2026 Soul Mirror Reading. All Right Reserved.</p>
    </div>
  </footer>

  <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
  <script defer src="../assets/sales-v2.min.js?v=<?= htmlspecialchars((string) $jsVer, ENT_QUOTES) ?>"></script>
<script>(function(){function go(){try{var d=new Date();var t=d.toLocaleDateString('en-US',{month:'long',day:'numeric',year:'numeric'});var pd=document.querySelectorAll('.pdate');for(var i=0;i<pd.length;i++){pd[i].textContent=t;}}catch(e){}try{var any=false;var L=document.querySelectorAll('[data-card-name]');for(var j=0;j<L.length;j++){var k=L[j].getAttribute('data-card-name');var tx=(L[j].textContent||'').trim();if(tx){any=true;var pc=document.querySelectorAll('.pcard');for(var m=0;m<pc.length;m++){if(pc[m].getAttribute('data-card')===k){pc[m].textContent=tx;}}}}if(any){var cl=document.querySelectorAll('.cards-line');for(var n=0;n<cl.length;n++){cl[n].style.display='';}}}catch(e){}}document.addEventListener('DOMContentLoaded',go);window.addEventListener('load',go);var c=0,iv=setInterval(function(){go();if(++c>10){clearInterval(iv);}},250);})();</script></body>

</html>

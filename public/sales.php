<?php
declare(strict_types=1);
$cssPath = __DIR__ . "/assets/sales-v2-bundle.min.css";
$cssVer = is_file($cssPath) ? filemtime($cssPath) : time();
$jsPath = __DIR__ . "/assets/sales-v2.min.js";
$jsVer = is_file($jsPath) ? filemtime($jsPath) : time();
?>
<!-- sales.php update 2026-07-08: CaseFileDossier redesign (exhibit cards, diagnosis/contents/charges case files, redacted preview, envelope notice). Split test vs /wealth-v2/ GildedSpread. Item smr-1, cbfid 63520. -->
<!DOCTYPE html>
<html lang="en" data-funnel-base="">

<head>
  <meta name="google-adsense-account" content="ca-pub-7614509729474530">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <meta name="description" content="See the one core belief, your Mirror Block, behind your Love, Life, and Wealth cards. Deep card work, clearing practice, and 90-day prompts delivered with your Soul Mirror Reading.">
  <title>Your Soul Mirror Reading. What the Cards Are Really Saying</title>
  <link rel="icon" type="image/svg+xml" href="favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&amp;family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&amp;family=Inter:wght@300;400;500;600;700&amp;family=Special+Elite&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/sales-v2-bundle.min.css?v=<?= htmlspecialchars((string) $cssVer, ENT_QUOTES) ?>">
  <script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "wq82rtc2gf");
  </script>
<style id="tsl-style">
.tslwrap{max-width:600px;margin:24px auto 0;text-align:left;}
.tsl{font-family:'Inter',system-ui,sans-serif;font-size:18px;line-height:1.65;color:#e9e2f2;margin-bottom:15px;}
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

/* connector: the trio resolves down into the Wealth Block */
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

/* featured Wealth Block payoff panel */
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

/* =====================================================
   ASTROLOVERSKETCH PLAYBOOK INTEGRATION
   Case File · Redaction · Dual-Mechanism · Comparison · Warning · Trust chips
   All styled to match wealth-v2 violet/gold system (not cream/typewriter).
   ===================================================== */

/* Case file band (slim, above the existing red notice) */
.caseband{position:relative;z-index:3;background:#0a0716;border-bottom:1px solid rgba(212,175,55,.25);color:#e8c97a;text-align:center;padding:10px 16px;font-family:'Special Elite',monospace;font-size:12px;letter-spacing:.22em;text-transform:uppercase;line-height:1.4;display:flex;align-items:center;justify-content:center;gap:14px;flex-wrap:wrap;}
.caseband .g-dot{width:5px;height:5px;background:#d4af37;border-radius:50%;display:inline-block;flex-shrink:0}
.caseband .caseband-red{color:#f0abab}
@media (max-width:640px){.caseband{font-size:10.5px;letter-spacing:.14em;padding:8px 12px;gap:9px}}

/* Dual-mechanism attack line (callout, lives after hero H1) */
.dual-attack{max-width:660px;margin:18px auto 6px;padding:16px 22px;border-left:2px solid var(--gold,#d4af37);background:linear-gradient(90deg,rgba(212,175,55,.10),rgba(212,175,55,0));font-family:'Cormorant Garamond',serif;font-size:19px;line-height:1.6;color:#e9e2f2;font-style:italic;text-align:left;border-radius:0 6px 6px 0}
.dual-attack strong{font-style:normal;color:#f0d38a;font-weight:600}
@media (max-width:640px){.dual-attack{font-size:17px;padding:14px 16px;margin-left:12px;margin-right:12px}}

/* Redacted case-file preview card (styled dark to match SMR, still feels like a document) */
.case-preview{max-width:600px;margin:36px auto 6px;padding:0 16px;position:relative}
.case-preview-card{position:relative;background:linear-gradient(180deg,#20153e 0%,#150c2c 100%);border:1px solid rgba(212,175,55,.55);border-radius:10px;padding:32px 30px 26px;box-shadow:0 26px 60px rgba(0,0,0,.55),inset 0 0 0 1px rgba(212,175,55,.06);overflow:hidden}
.case-preview-card::before{content:"";position:absolute;top:0;left:0;right:0;height:5px;background:repeating-linear-gradient(45deg,#c9982b,#c9982b 8px,#0a0716 8px,#0a0716 16px);opacity:.55}
.case-preview-card::after{content:"";position:absolute;bottom:0;left:0;right:0;height:5px;background:repeating-linear-gradient(45deg,#c9982b,#c9982b 8px,#0a0716 8px,#0a0716 16px);opacity:.55}
.case-preview-metarow{display:flex;justify-content:space-between;align-items:center;gap:10px;margin:0 0 12px;position:relative;z-index:1}
.case-preview-metarow .meta-tag{font-family:'Special Elite',monospace;font-size:10.5px;letter-spacing:.2em;text-transform:uppercase;color:#8b7a94}
.case-preview-metarow .meta-tag.right{color:#e8c97a}
.case-preview-header{text-align:center;padding:14px 0 16px;border-bottom:1px dashed rgba(212,175,55,.28);margin-bottom:18px}
.case-preview-stamp{font-family:'Special Elite',monospace;color:#f0abab;font-size:10.5px;letter-spacing:.32em;text-transform:uppercase;border:2px solid rgba(240,171,171,.6);padding:5px 14px;display:inline-block;transform:rotate(-1.5deg);opacity:.9;margin-bottom:14px}
.case-preview-title{font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:600;color:#fff;line-height:1.15;margin:8px 0 4px;background:linear-gradient(180deg,#fff 0%,#f0d38a 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.case-preview-title em{font-style:italic;background:linear-gradient(180deg,#f0d38a 0%,#c9982b 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.case-preview-sub{font-family:'Special Elite',monospace;font-size:11px;color:#a99cba;letter-spacing:.14em;text-transform:uppercase}
.case-preview-line{font-family:'Special Elite',monospace;font-size:14.5px;line-height:1.85;color:#e9e2f2;margin:10px 0}
.case-preview-line .label{color:#a99cba;font-size:10.5px;letter-spacing:.18em;text-transform:uppercase;display:block;margin-bottom:2px}
.redact{display:inline-block;position:relative;overflow:hidden;background:#000;color:#000;padding:0 6px;border-radius:2px;user-select:none;box-shadow:inset 0 0 0 1px #333;line-height:1.4;vertical-align:middle}
.redact::after{content:"";position:absolute;inset:0;pointer-events:none;background:linear-gradient(115deg,transparent 42%,rgba(255,255,255,.07) 50%,transparent 58%);background-size:250% 100%;background-position:150% 0}
@media (prefers-reduced-motion:no-preference){.redact::after{animation:redactSheen 7s linear infinite}@keyframes redactSheen{0%{background-position:150% 0}55%{background-position:-70% 0}100%{background-position:-70% 0}}}
.redact.short{min-width:70px}
.redact.med{min-width:130px}
.redact.long{min-width:210px}
.redact.block-name{min-width:250px;padding:2px 10px;font-size:16px}
.case-preview-tag{text-align:center;margin-top:20px;padding-top:16px;border-top:1px dashed rgba(212,175,55,.28);font-family:'Special Elite',monospace;font-size:11px;color:#a99cba;letter-spacing:.14em;text-transform:uppercase}

/* --- v2 flare: watermark, stars, corner ornaments, positions, timeline, seal --- */
.case-watermark{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;font-family:'Cinzel',serif;font-size:clamp(60px,14vw,110px);font-weight:800;letter-spacing:.36em;color:rgba(212,175,55,.035);transform:rotate(-24deg);white-space:nowrap;text-transform:uppercase;user-select:none;text-align:center;line-height:1;z-index:0}
.case-preview-card > *:not(.case-watermark):not(.case-stars):not(.cf-corner):not(.cf-grain){position:relative;z-index:1}
@media (max-width:640px){.case-watermark{font-size:clamp(44px,12vw,80px);letter-spacing:.24em}}

.case-stars{position:absolute;inset:0;pointer-events:none;z-index:0}
.case-stars .star{position:absolute;width:6px;height:6px;background:radial-gradient(circle,#f0d38a 0%,#e8c97a 40%,rgba(212,175,55,0) 70%);border-radius:50%;opacity:.55;filter:blur(.3px);animation:twinkle 4.5s ease-in-out infinite}
.case-stars .star:nth-child(2){animation-delay:-1.6s;width:4px;height:4px}
.case-stars .star:nth-child(3){animation-delay:-2.8s;width:7px;height:7px}
.case-stars .star:nth-child(4){animation-delay:-3.6s;width:5px;height:5px}
.case-stars .star:nth-child(5){animation-delay:-.9s;width:6px;height:6px}
@keyframes twinkle{0%,100%{opacity:.3;transform:scale(1)}50%{opacity:.85;transform:scale(1.25)}}

.case-preview-header{position:relative;z-index:1;padding:22px 0 18px !important}
.case-diamond{color:#d4af37;font-size:14px;margin:8px 0;opacity:.75;letter-spacing:.3em}

.case-positions{display:grid;grid-template-columns:1fr auto 1fr auto 1fr;gap:6px;align-items:center;margin:20px auto 8px;max-width:520px;position:relative;z-index:1}
.case-position{text-align:center;padding:14px 8px 12px;border:1px solid rgba(212,175,55,.28);border-radius:10px;background:linear-gradient(180deg,rgba(212,175,55,.06),rgba(212,175,55,.02))}
.case-position-icon{color:#e8c97a;width:26px;height:26px;margin:0 auto 8px;display:flex;align-items:center;justify-content:center;opacity:.9}
.case-position-icon svg{width:100%;height:100%;display:block}
.case-position-kicker{font-family:'Cinzel',sans-serif;font-size:9px;letter-spacing:.24em;text-transform:uppercase;color:#d4af37;margin-bottom:4px;font-weight:600}
.case-position-name{font-family:'Cormorant Garamond',serif;font-size:17px;font-weight:600;color:#fff;line-height:1.15;margin-bottom:2px}
.case-position-sub{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:12px;color:#c8bfe0;letter-spacing:.02em}
.case-position-sep{font-family:'Cinzel',serif;color:#e8c97a;font-size:14px;opacity:.55;line-height:1}
@media (max-width:640px){
  .case-positions{grid-template-columns:1fr;gap:8px}
  .case-position-sep{display:none}
}

.case-divider{display:flex;align-items:center;justify-content:center;gap:14px;margin:22px 0 20px;color:#e8c97a;opacity:.6}
.case-divider .case-divider-dot{width:60px;height:1px;background:linear-gradient(90deg,rgba(212,175,55,0),rgba(212,175,55,.55),rgba(212,175,55,0))}
.case-divider i{font-style:normal;font-size:12px;letter-spacing:.3em}

.case-cards-row{display:flex;gap:22px;flex-wrap:wrap;margin-top:2px}
.case-cards-row em{color:#a99cba;font-style:normal;font-size:11px;letter-spacing:.18em;text-transform:uppercase;margin-right:4px}
.case-cards-row > span{display:inline-flex;align-items:center;gap:4px}

.type-marker{color:#a99cba;font-size:11px;letter-spacing:.18em;text-transform:uppercase;margin-right:2px}
.redact.type-num{min-width:26px;padding:2px 8px;font-size:15px;text-align:center;color:transparent}
.redact.xshort{min-width:36px}

.case-quote-line{margin:14px 0}
.case-quote{margin:6px 0 0;padding:16px 22px 16px 26px;background:linear-gradient(90deg,rgba(212,175,55,.09),rgba(212,175,55,0));border-left:2px solid #d4af37;border-radius:0 6px 6px 0;font-family:'Cormorant Garamond',serif;font-style:italic;font-size:16px;color:#e9e2f2;line-height:1.6}

.case-timeline{margin:8px 0 4px;padding:6px 4px 4px}
.case-timeline-track{display:flex;justify-content:space-between;align-items:flex-start;position:relative;padding:0 8px;gap:8px}
.case-timeline-track::before{content:"";position:absolute;left:22px;right:22px;top:6px;height:1px;background:linear-gradient(90deg,rgba(212,175,55,.4),rgba(212,175,55,.6),rgba(212,175,55,.4));z-index:0}
.case-timeline-node{flex:1;text-align:center;position:relative;z-index:1;min-width:0}
.case-timeline-node .dot{display:block;width:11px;height:11px;background:#20153e;border:2px solid #d4af37;border-radius:50%;margin:0 auto 8px;box-shadow:0 0 0 2px rgba(20,12,44,1)}
.case-timeline-node.active .dot{background:radial-gradient(circle,#f0d38a 0%,#d4af37 100%);box-shadow:0 0 12px rgba(240,211,138,.7),0 0 0 2px rgba(20,12,44,1);animation:pulse 2.4s ease-in-out infinite}
@keyframes pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.18);opacity:.85}}
.case-timeline-node .lbl{font-family:'Cinzel',sans-serif;font-size:9.5px;letter-spacing:.16em;text-transform:uppercase;color:#e8c97a;line-height:1.3;margin-bottom:2px}
.case-timeline-node .sub{font-family:'Cormorant Garamond',serif;font-style:italic;font-size:11px;color:#a99cba;line-height:1.3;min-height:14px}

.case-activation-list{margin-top:4px;font-family:'Special Elite',monospace;font-size:13.5px;line-height:1.75;color:#e9e2f2}
.case-activation-list > div{margin:4px 0;padding-left:2px}
.case-activation-list i{color:#e8c97a;font-style:normal;margin-right:6px;font-size:11px}
.case-activation-more{color:#a99cba !important;font-style:italic;font-family:'Cormorant Garamond',serif !important;font-size:13px !important;margin-top:8px !important;text-align:center;letter-spacing:.05em}

.case-seal-row{display:flex;align-items:center;gap:20px;margin-top:24px;padding:18px 0 4px;position:relative}
.case-seal{position:relative;width:88px;height:88px;flex-shrink:0}
.case-seal-ring{position:absolute;inset:0;border-radius:50%;border:2px dashed rgba(212,175,55,.45);animation:rotate 32s linear infinite}
@keyframes rotate{to{transform:rotate(360deg)}}
.case-seal-inner{position:absolute;inset:8px;border-radius:50%;background:radial-gradient(circle at 30% 30%,#4a2f8f 0%,#2d1b69 55%,#1e0d40 100%);border:1px solid rgba(212,175,55,.55);display:flex;flex-direction:column;align-items:center;justify-content:center;box-shadow:0 6px 18px rgba(0,0,0,.5),inset 0 1px 2px rgba(240,211,138,.35);overflow:hidden}
.case-seal-inner svg{width:38px;height:38px;color:#e8c97a;margin-bottom:-4px}
.case-seal-monogram{font-family:'Cinzel',serif;font-size:9.5px;letter-spacing:.28em;color:#e8c97a;margin-top:2px;font-weight:700}
.case-seal-inner.has-photo{background:#1e0d40;padding:0}
.case-seal-photo{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 22%;filter:contrast(1.05) saturate(1.05)}
.case-seal-inner.has-photo::after{content:"";position:absolute;inset:0;border-radius:50%;background:linear-gradient(180deg,rgba(30,13,64,0) 55%,rgba(30,13,64,.85) 100%);pointer-events:none}
.case-seal-inner.has-photo .case-seal-monogram{position:absolute;bottom:8px;left:0;right:0;text-align:center;margin:0;text-shadow:0 1px 4px rgba(0,0,0,.9);z-index:2;font-size:8.5px}
.case-seal-meta{flex:1;min-width:0;font-family:'Special Elite',monospace}
.case-seal-name{font-family:'Cormorant Garamond',serif !important;font-size:22px;font-weight:600;color:#fff;font-style:italic;line-height:1.1;margin-bottom:4px}
.case-seal-title{font-family:'Cinzel',sans-serif !important;font-size:10.5px;letter-spacing:.18em;text-transform:uppercase;color:#e8c97a;margin-bottom:6px;font-weight:600}
.case-seal-line{font-size:11px;color:#a99cba;letter-spacing:.06em;line-height:1.4;font-family:'Special Elite',monospace}
@media (max-width:640px){
  .case-seal-row{gap:14px}
  .case-seal{width:72px;height:72px}
  .case-seal-name{font-size:19px}
}

/* ============================================
   DIAGNOSIS CARD - case-file restyle
   ============================================ */
.diagnosis-file{max-width:600px;margin:20px auto 0;padding:0 8px}
.diagnosis-file-inner{position:relative;background:linear-gradient(180deg,#20153e 0%,#150c2c 100%);border:1px solid rgba(212,175,55,.55);border-radius:10px;padding:0;box-shadow:0 26px 60px rgba(0,0,0,.55),inset 0 0 0 1px rgba(212,175,55,.06);overflow:hidden}
.diagnosis-file-inner::before,.diagnosis-file-inner::after{content:"";position:absolute;left:0;right:0;height:5px;background:repeating-linear-gradient(45deg,#c9982b,#c9982b 8px,#0a0716 8px,#0a0716 16px);opacity:.55}
.diagnosis-file-inner::before{top:0}
.diagnosis-file-inner::after{bottom:0}

.dx-header-row{display:flex;justify-content:space-between;align-items:center;padding:22px 26px 0;position:relative;z-index:1}
.dx-tag{font-family:'Special Elite',monospace;font-size:10px;letter-spacing:.2em;text-transform:uppercase;color:#a99cba}
.dx-tag.right{color:#e8c97a}

.dx-body{padding:18px 32px 30px;text-align:center;position:relative;z-index:1}
.dx-eyebrow{font-family:'Cinzel',sans-serif !important;font-size:10.5px;letter-spacing:.28em;text-transform:uppercase;color:#d4af37;display:block;margin-top:8px;font-weight:600}
.dx-diamond{color:#d4af37;font-size:12px;margin:12px 0 6px;opacity:.7;letter-spacing:.3em}
.dx-title{font-family:'Cormorant Garamond',serif !important;font-size:clamp(28px,4.6vw,36px);font-weight:600;color:#fff;line-height:1.15;margin:6px 0 14px;letter-spacing:-.01em}
.dx-title[data-mirror-block-name]{background:linear-gradient(180deg,#fff 0%,#f0d38a 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.dx-quote{font-family:'Cormorant Garamond',serif !important;font-style:italic;font-size:17px;line-height:1.6;color:#e9e2f2;margin:0 auto 8px;max-width:460px;padding:14px 22px;background:linear-gradient(90deg,rgba(212,175,55,.09),rgba(212,175,55,0));border-left:2px solid #d4af37;border-radius:0 6px 6px 0}

.dx-divider{display:flex;align-items:center;justify-content:center;gap:14px;margin:22px 0 18px;color:#e8c97a;opacity:.65}
.dx-divider span{width:60px;height:1px;background:linear-gradient(90deg,rgba(212,175,55,0),rgba(212,175,55,.55),rgba(212,175,55,0))}
.dx-divider i{font-style:normal;font-size:12px;letter-spacing:.3em}

.dx-prescription{position:relative;padding:20px 22px 20px;border:1px dashed rgba(212,175,55,.35);border-radius:8px;background:linear-gradient(180deg,rgba(212,175,55,.05),rgba(212,175,55,.01));text-align:left}
.dx-prescription-label{position:absolute;top:-9px;left:20px;background:#150c2c;padding:0 10px;font-family:'Cinzel',sans-serif !important;font-size:9.5px;letter-spacing:.24em;text-transform:uppercase;color:#d4af37;font-weight:600}
.dx-prescription p{font-family:'Inter',sans-serif !important;font-size:16px;line-height:1.65;color:#e9e2f2;margin:0}
.dx-prescription strong{color:#f0d38a;font-weight:600}

.dx-signature{margin-top:20px;text-align:right;font-family:'Special Elite',monospace}
.dx-sig-line{font-size:12px;color:#c8bfe0;letter-spacing:.08em;line-height:1.5}
.dx-sig-line em{color:#f0d38a;font-family:'Cormorant Garamond',serif !important;font-style:italic;font-size:18px;font-weight:600;letter-spacing:0;margin-left:4px}
.dx-sig-line.dx-sig-meta{font-size:10px;color:#8f88ab;margin-top:4px;letter-spacing:.14em;text-transform:uppercase}

@media (max-width:640px){
  .diagnosis-file{padding:0 4px}
  .dx-header-row{padding:20px 20px 0}
  .dx-body{padding:14px 22px 26px}
  .dx-quote{font-size:16px;padding:12px 16px}
  .dx-prescription{padding:18px 18px 16px}
  .dx-prescription p{font-size:15px}
}

/* ============================================
   CONTENTS FILE - offer box restyle
   ============================================ */
.contents-file{max-width:640px;margin:0 auto}
.contents-file-inner{position:relative;background:linear-gradient(180deg,#20153e 0%,#150c2c 100%);border:1px solid rgba(212,175,55,.55);border-radius:10px;box-shadow:0 26px 60px rgba(0,0,0,.55),inset 0 0 0 1px rgba(212,175,55,.06);overflow:hidden}
.contents-file-inner::before,.contents-file-inner::after{content:"";position:absolute;left:0;right:0;height:5px;background:repeating-linear-gradient(45deg,#c9982b,#c9982b 8px,#0a0716 8px,#0a0716 16px);opacity:.55}
.contents-file-inner::before{top:0}
.contents-file-inner::after{bottom:0}

.contents-body{padding:22px 30px 30px;text-align:center;position:relative;z-index:1}
.contents-title{font-family:'Cormorant Garamond',serif !important;font-size:clamp(30px,4.4vw,36px);font-weight:600;color:#fff;line-height:1.15;margin:8px 0 6px;letter-spacing:-.01em;background:linear-gradient(180deg,#fff 0%,#f0d38a 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.contents-sub{font-family:'Cormorant Garamond',serif !important;font-style:italic;font-size:17px;color:#c8bfe0;max-width:480px;margin:0 auto 18px;line-height:1.55}
.contents-mockup{display:block;max-width:220px;margin:14px auto 8px;filter:drop-shadow(0 22px 44px rgba(0,0,0,.55))}

.contents-index{list-style:none;padding:0;margin:8px auto 12px;text-align:left;counter-reset:none;max-width:520px}
.contents-index li{display:flex;align-items:flex-start;gap:14px;padding:14px 6px;border-bottom:1px dashed rgba(212,175,55,.22);position:relative;line-height:1.5}
.contents-index li:last-child{border-bottom:none}
.contents-index .ci-num{flex-shrink:0;font-family:'Special Elite',monospace !important;font-size:12px;letter-spacing:.14em;color:#d4af37;background:linear-gradient(180deg,rgba(212,175,55,.16),rgba(212,175,55,.06));border:1px solid rgba(212,175,55,.4);border-radius:4px;padding:6px 8px;line-height:1;font-weight:700;margin-top:2px;min-width:32px;text-align:center}
.contents-index .ci-body{flex:1;min-width:0;font-family:'Inter',sans-serif !important;font-size:15.5px;color:#e9e2f2;line-height:1.55}
.contents-index .ci-body strong{color:#fff;font-weight:600;display:inline}
.contents-index .ci-body .ci-detail{color:#c8bfe0;display:block;margin-top:2px;font-size:14.5px}
.contents-index .ci-tag{flex-shrink:0;font-family:'Special Elite',monospace !important;font-size:9.5px;letter-spacing:.18em;text-transform:uppercase;color:#a99cba;border:1px dashed rgba(212,175,55,.35);border-radius:3px;padding:4px 8px;line-height:1;margin-top:6px;white-space:nowrap}
.contents-index li:first-child .ci-tag{color:#f0abab;border-color:rgba(240,171,171,.4)}

.contents-signature{margin-top:18px;text-align:right;font-family:'Special Elite',monospace}

@media (max-width:640px){
  .contents-body{padding:18px 20px 24px}
  .contents-index li{flex-wrap:wrap}
  .contents-index .ci-tag{margin-left:46px;margin-top:2px}
}

/* ============================================
   CHARGES FILE - pricing block restyle
   ============================================ */
.charges-file{max-width:640px;margin:0 auto}
.charges-file-inner{position:relative;background:linear-gradient(180deg,#20153e 0%,#150c2c 100%);border:1px solid rgba(212,175,55,.55);border-radius:10px;box-shadow:0 26px 60px rgba(0,0,0,.55),inset 0 0 0 1px rgba(212,175,55,.06);overflow:hidden}
.charges-file-inner::before,.charges-file-inner::after{content:"";position:absolute;left:0;right:0;height:5px;background:repeating-linear-gradient(45deg,#c9982b,#c9982b 8px,#0a0716 8px,#0a0716 16px);opacity:.55}
.charges-file-inner::before{top:0}
.charges-file-inner::after{bottom:0}

.charges-body{padding:22px 30px 30px;text-align:center;position:relative;z-index:1}
.charges-title{font-family:'Cormorant Garamond',serif !important;font-size:clamp(30px,4.4vw,38px);font-weight:600;color:#fff;line-height:1.15;margin:8px 0 4px;letter-spacing:-.01em;background:linear-gradient(180deg,#fff 0%,#f0d38a 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.charges-sub{font-family:'Cormorant Garamond',serif !important;font-style:italic;font-size:16px;color:#c8bfe0;max-width:440px;margin:0 auto 22px;line-height:1.55}

.charges-ledger{list-style:none;padding:0;margin:6px auto 8px;text-align:left;max-width:520px}
.charges-ledger li{display:flex;align-items:center;gap:12px;padding:12px 4px;border-bottom:1px dashed rgba(212,175,55,.22);font-family:'Special Elite',monospace}
.charges-ledger li:last-child{border-bottom:none}
.charges-ledger .ledger-num{flex-shrink:0;font-family:'Special Elite',monospace !important;font-size:11px;letter-spacing:.14em;color:#d4af37;background:linear-gradient(180deg,rgba(212,175,55,.16),rgba(212,175,55,.06));border:1px solid rgba(212,175,55,.4);border-radius:4px;padding:5px 7px;line-height:1;font-weight:700;min-width:28px;text-align:center}
.charges-ledger .ledger-item{flex:1;min-width:0;font-family:'Inter',sans-serif !important;font-size:15px;color:#e9e2f2;line-height:1.4}
.charges-ledger .ledger-item strong{color:#f0d38a;font-weight:600}
.charges-ledger .ledger-value{flex-shrink:0;font-family:'Special Elite',monospace !important;font-size:12.5px;letter-spacing:.06em;color:#e8c97a;font-weight:700;text-align:right;min-width:74px;white-space:nowrap}
.charges-ledger .ledger-value.ledger-enclosed{color:#a99cba;font-weight:400;text-transform:uppercase;letter-spacing:.14em;font-size:10.5px}
.charges-ledger .ledger-value.ledger-free{color:#7ee0a0}
.charges-ledger .ledger-value.ledger-free s{color:#8f88ab;font-weight:400;margin-right:4px;text-decoration-color:rgba(240,171,171,.5)}

.charges-divider{display:flex;align-items:center;justify-content:center;gap:14px;margin:22px 0 18px;color:#e8c97a;opacity:.65}
.charges-divider span{width:60px;height:1px;background:linear-gradient(90deg,rgba(212,175,55,0),rgba(212,175,55,.55),rgba(212,175,55,0))}
.charges-divider i{font-style:normal;font-size:12px;letter-spacing:.3em}

.charges-totals{max-width:440px;margin:8px auto 4px;text-align:left}
.totals-row{display:flex;justify-content:space-between;align-items:baseline;padding:8px 4px;font-family:'Special Elite',monospace}
.totals-original{border-bottom:1px dashed rgba(212,175,55,.22)}
.totals-label{font-family:'Cinzel',sans-serif !important;font-size:11px;letter-spacing:.22em;text-transform:uppercase;color:#a99cba;font-weight:600}
.totals-today .totals-label{color:#e8c97a}
.totals-strike{font-family:'Cinzel',sans-serif !important;font-size:20px;color:#8f88ab;text-decoration:line-through;text-decoration-color:rgba(240,171,171,.55);text-decoration-thickness:1.5px}
.totals-today{padding-top:14px;padding-bottom:2px}
.totals-price{font-family:'Cormorant Garamond',serif !important;font-size:clamp(56px,10vw,72px);font-weight:600;color:#e8c97a;line-height:1;background:linear-gradient(180deg,#f0d38a 0%,#c9982b 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;text-shadow:none;letter-spacing:-.02em}
.totals-price em{font-size:.42em;vertical-align:super;font-style:normal;margin-right:4px;font-weight:500}

.charges-signature{margin-top:22px;text-align:right;font-family:'Special Elite',monospace}

@media (max-width:640px){
  .charges-body{padding:18px 18px 22px}
  .charges-ledger li{flex-wrap:wrap;padding:12px 2px}
  .charges-ledger .ledger-item{flex:1 1 100%;order:2;padding-left:38px;margin-top:2px}
  .charges-ledger .ledger-num{order:1}
  .charges-ledger .ledger-value{order:3;margin-left:auto;padding-left:38px;min-width:auto}
}

/* ============================================
   TOP DELIVERY NOTICE - sealed envelope redesign
   ============================================ */
.topnotice.topnotice-envelope{
  position:relative;z-index:3;
  background:linear-gradient(180deg,#5a1418 0%,#7a1c1c 45%,#5a1418 100%);
  color:#f8ecc8;
  border-top:1px solid rgba(240,199,116,.45);
  border-bottom:1px solid rgba(240,199,116,.45);
  padding:0;
  font-family:'Inter',system-ui,sans-serif !important;
  overflow:hidden;
}
.topnotice.topnotice-envelope::before,
.topnotice.topnotice-envelope::after{
  content:"";position:absolute;left:0;right:0;height:2px;pointer-events:none;
}
.topnotice.topnotice-envelope::before{top:1px;background:linear-gradient(90deg,rgba(240,199,116,0),rgba(240,199,116,.35) 20%,rgba(240,199,116,.35) 80%,rgba(240,199,116,0))}
.topnotice.topnotice-envelope::after{bottom:1px;background:linear-gradient(90deg,rgba(240,199,116,0),rgba(240,199,116,.35) 20%,rgba(240,199,116,.35) 80%,rgba(240,199,116,0))}

.topnotice-inner{
  max-width:1080px;margin:0 auto;padding:11px 22px;
  display:flex;align-items:center;justify-content:center;gap:14px;
  position:relative;z-index:1;line-height:1.4;
}
.topnotice-icon{
  color:#f0c774;flex-shrink:0;display:inline-flex;align-items:center;
  filter:drop-shadow(0 1px 0 rgba(0,0,0,.4));
}
.topnotice-text{
  font-size:12.5px;letter-spacing:.12em;text-transform:uppercase;
  color:#f8ecc8;font-weight:700;text-align:center;
  display:inline-flex;align-items:center;gap:12px;flex-wrap:wrap;justify-content:center;
}
.topnotice-text strong{color:#fff;font-weight:700;letter-spacing:.12em}
.topnotice-text .firstname{color:#f0c774}
.topnotice-sep{color:#f0c774;font-size:11px;letter-spacing:.3em;opacity:.9;font-family:'Cinzel',serif !important;line-height:1}

.topnotice-status{
  flex-shrink:0;display:inline-flex;align-items:center;gap:7px;
  padding:5px 11px;border:1px solid rgba(240,199,116,.4);border-radius:20px;
  background:rgba(0,0,0,.22);
  font-family:'Cinzel',sans-serif !important;font-size:9.5px;letter-spacing:.24em;text-transform:uppercase;color:#f0c774;font-weight:600;
}
.pulse-dot{
  width:7px;height:7px;border-radius:50%;background:#7ee0a0;flex-shrink:0;position:relative;
  box-shadow:0 0 6px rgba(126,224,160,.7);
  animation:tnPulse 2s ease-in-out infinite;
}
.pulse-dot::after{
  content:"";position:absolute;inset:-4px;border-radius:50%;border:1px solid rgba(126,224,160,.6);
  animation:tnPulseRing 2s ease-out infinite;
}
@keyframes tnPulse{0%,100%{opacity:1}50%{opacity:.6}}
@keyframes tnPulseRing{0%{transform:scale(.6);opacity:.9}100%{transform:scale(1.9);opacity:0}}

@media (max-width:640px){
  .topnotice-inner{padding:10px 14px;gap:10px}
  .topnotice-text{font-size:11px;letter-spacing:.08em;gap:8px}
  .topnotice-sep{font-size:9px}
  .topnotice-status .status-label{display:none}
  .topnotice-status{padding:5px 8px}
}

/* ============================================
   Shared case-file corner ornaments (pseudo-corner brackets)
   ============================================ */
.cf-corner{position:absolute;width:14px;height:14px;border:1.5px solid rgba(212,175,55,.55);pointer-events:none;z-index:3;border-radius:0}
.cf-corner.tl{top:8px;left:8px;border-right:none;border-bottom:none;border-top-left-radius:6px}
.cf-corner.tr{top:8px;right:8px;border-left:none;border-bottom:none;border-top-right-radius:6px}
.cf-corner.bl{bottom:8px;left:8px;border-right:none;border-top:none;border-bottom-left-radius:6px}
.cf-corner.br{bottom:8px;right:8px;border-left:none;border-top:none;border-bottom-right-radius:6px}

/* ============================================
   Shared document paper-grain overlay
   ============================================ */
.cf-grain{position:absolute;inset:0;pointer-events:none;z-index:0;opacity:.04;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='140' height='140'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");background-size:140px 140px}

/* ============================================
   EXHIBIT CARDS - Gilded Spread 3 frames restyle
   ============================================ */
.gs-frame.cf-exhibit{position:relative;padding-top:58px}
.gs-frame.cf-exhibit .cf-exhibit-header{position:absolute;top:13px;left:12px;right:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:2;gap:3px}
.gs-frame.cf-exhibit .cf-exhibit-tag{font-family:'Special Elite',monospace !important;font-size:10px;letter-spacing:.22em;text-transform:uppercase;color:#e8c97a;font-weight:700;white-space:nowrap;line-height:1}
.gs-frame.cf-exhibit .cf-exhibit-domain{font-family:'Special Elite',monospace !important;font-size:9px;letter-spacing:.14em;text-transform:uppercase;color:#a99cba;white-space:nowrap;line-height:1}
.gs-frame.cf-exhibit .gs-kicker{font-family:'Cormorant Garamond',serif !important;font-style:italic;font-weight:600;color:#f0d38a !important;letter-spacing:.02em !important;text-transform:none !important}

/* Shift the empty-state placeholder up so the sealed stamp sits clear below it */
.gs-frame.cf-exhibit .gs-window .wf-content{transform:translateY(-26px)}

/* Sealed stamp inside the card window - two stacked lines (only shown when no personalized card yet) */
.gs-frame.cf-exhibit .cf-sealed-stamp{
  position:absolute;top:60%;left:50%;transform:translate(-50%,-50%) rotate(-8deg);
  max-width:86%;text-align:center;line-height:1.05;
  font-family:'Special Elite',monospace !important;font-size:12px;letter-spacing:.3em;text-transform:uppercase;color:#f0abab;font-weight:700;
  border:2px solid rgba(240,171,171,.65);padding:6px 10px;border-radius:4px;background:rgba(0,0,0,.4);
  white-space:nowrap;pointer-events:none;z-index:2;
  text-shadow:0 1px 2px rgba(0,0,0,.6);
  box-shadow:0 4px 12px rgba(0,0,0,.4),inset 0 0 0 1px rgba(240,171,171,.15);
}
.gs-frame.cf-exhibit .cf-sealed-stamp .cf-sealed-sub{display:block;font-size:7.5px;letter-spacing:.18em;margin-top:3px;font-weight:700}
.gs-frame.cf-exhibit .gs-window.has-card .cf-sealed-stamp{display:none}

/* ============================================
   ROOT CAUSE PANEL - Wealth Block featured restyle
   ============================================ */
.cf-root-panel{position:relative;max-width:640px;margin:30px auto 0;background:linear-gradient(180deg,rgba(74,47,143,.5) 0%,rgba(30,13,64,.85) 100%);border:1px solid rgba(212,175,55,.55);border-radius:12px;padding:0;overflow:hidden;box-shadow:0 22px 50px rgba(0,0,0,.45),inset 0 0 0 1px rgba(212,175,55,.06)}
.cf-root-panel::before,.cf-root-panel::after{content:"";position:absolute;left:0;right:0;height:5px;background:repeating-linear-gradient(45deg,#c9982b,#c9982b 8px,#0a0716 8px,#0a0716 16px);opacity:.55;z-index:1;pointer-events:none}
.cf-root-panel::before{top:0}
.cf-root-panel::after{bottom:0}
.cf-root-header{display:flex;justify-content:space-between;align-items:center;padding:20px 26px 0;position:relative;z-index:2;gap:10px;flex-wrap:wrap}
.cf-root-tag{font-family:'Special Elite',monospace !important;font-size:10.5px;letter-spacing:.22em;text-transform:uppercase;color:#a99cba}
.cf-root-tag.right{color:#e8c97a;font-weight:700}
.cf-root-body{display:flex;align-items:center;gap:22px;padding:18px 26px 24px;position:relative;z-index:2}
.cf-root-emblem{position:relative;width:92px;height:92px;flex-shrink:0}
.cf-root-emblem-ring{position:absolute;inset:0;border-radius:50%;border:1.5px dashed rgba(212,175,55,.55);animation:rotate 40s linear infinite}
.cf-root-emblem img{position:absolute;inset:8px;width:calc(100% - 16px);height:calc(100% - 16px);object-fit:contain;filter:drop-shadow(0 4px 10px rgba(212,175,55,.35))}
.cf-root-content{flex:1;min-width:0}
.cf-root-eyebrow{font-family:'Cinzel',sans-serif !important;font-size:10.5px;letter-spacing:.26em;text-transform:uppercase;color:#d4af37;display:block;font-weight:600;margin-bottom:4px}
.cf-root-title{font-family:'Cormorant Garamond',serif !important;font-size:clamp(24px,3.6vw,30px);font-weight:600;color:#fff;line-height:1.15;margin:2px 0 8px;letter-spacing:-.005em;background:linear-gradient(180deg,#fff 0%,#f0d38a 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.cf-root-desc{font-family:'Inter',sans-serif !important;font-size:15px;line-height:1.6;color:#e9e2f2;margin:0}
@media (max-width:640px){
  .cf-root-body{flex-direction:column;text-align:center;gap:14px;padding:14px 22px 22px}
  .cf-root-header{padding:18px 20px 0}
  .cf-root-emblem{width:76px;height:76px}
}

/* ============================================
   SUPPLEMENTARY MATERIALS - Bonuses restyle
   ============================================ */
.cf-supplementary{position:relative;background:linear-gradient(180deg,#20153e 0%,#150c2c 100%);border:1px solid rgba(212,175,55,.55);border-radius:12px;padding:0;overflow:hidden;box-shadow:0 26px 60px rgba(0,0,0,.55),inset 0 0 0 1px rgba(212,175,55,.06);max-width:960px;margin:0 auto}
.cf-supplementary::before,.cf-supplementary::after{content:"";position:absolute;left:0;right:0;height:5px;background:repeating-linear-gradient(45deg,#c9982b,#c9982b 8px,#0a0716 8px,#0a0716 16px);opacity:.55;pointer-events:none;z-index:1}
.cf-supplementary::before{top:0}
.cf-supplementary::after{bottom:0}
.cf-supplementary-header{display:flex;justify-content:space-between;align-items:center;padding:22px 30px 0;position:relative;z-index:2;gap:10px;flex-wrap:wrap}
.cf-supplementary-tag{font-family:'Special Elite',monospace !important;font-size:10.5px;letter-spacing:.22em;text-transform:uppercase;color:#a99cba}
.cf-supplementary-tag.right{color:#e8c97a;font-weight:700}
.cf-supplementary-body{padding:16px 30px 32px;text-align:center;position:relative;z-index:2}
.cf-supplementary-eyebrow{font-family:'Cinzel',sans-serif !important;font-size:11px;letter-spacing:.28em;text-transform:uppercase;color:#d4af37;display:block;font-weight:600;margin-top:8px}
.cf-supplementary-title{font-family:'Cormorant Garamond',serif !important;font-size:clamp(28px,4.2vw,38px) !important;font-weight:600 !important;color:#fff !important;line-height:1.15 !important;margin:10px 0 8px !important;text-align:center;background:linear-gradient(180deg,#fff 0%,#f0d38a 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.cf-supplementary-title em{color:#e8c97a !important;background:linear-gradient(180deg,#f0d38a 0%,#c9982b 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-style:italic}
.cf-supplementary-sub{font-family:'Cormorant Garamond',serif !important;font-style:italic;font-size:16px;color:#c8bfe0;max-width:520px;margin:0 auto 22px;line-height:1.55}

.cf-bonus-grid{max-width:920px;margin:0 auto}
.cf-bonus-card{position:relative;background:linear-gradient(180deg,rgba(45,27,105,.4) 0%,rgba(30,13,64,.7) 100%);border:1px solid rgba(212,175,55,.28) !important;border-radius:10px !important;padding:20px 16px 16px !important;text-align:center;transition:transform .16s ease,box-shadow .16s ease,border-color .16s ease}
@media (prefers-reduced-motion:no-preference){.cf-bonus-card:hover{transform:translateY(-3px);box-shadow:0 14px 30px rgba(0,0,0,.42);border-color:rgba(212,175,55,.5) !important}}
.cf-bonus-card img{max-width:80% !important;margin:2px auto 8px !important}
.cf-nocharge-stamp{position:absolute;top:12px;right:12px;transform:rotate(6deg);font-family:'Special Elite',monospace !important;font-size:9.5px;letter-spacing:.22em;text-transform:uppercase;color:#f0abab;font-weight:700;border:1.5px solid rgba(240,171,171,.6);padding:4px 8px;border-radius:3px;background:rgba(160,35,35,.14);box-shadow:0 2px 8px rgba(0,0,0,.3);z-index:2;line-height:1}
.cf-bonus-num{font-family:'Special Elite',monospace !important;font-size:9.5px !important;letter-spacing:.24em !important;text-transform:uppercase !important;color:#d4af37 !important;font-weight:700 !important;margin-bottom:6px !important;text-align:center}
.cf-bonus-card h4{font-family:'Cormorant Garamond',serif !important;font-size:17px !important;font-weight:600 !important;color:#fff !important;line-height:1.25 !important;margin:4px 0 10px !important;text-align:center}
.cf-bonus-value{font-family:'Special Elite',monospace !important;font-size:12px;letter-spacing:.08em;color:#7ee0a0}
.cf-bonus-value s{color:#8f88ab;font-weight:400;margin-right:4px;text-decoration-color:rgba(240,171,171,.5)}
.cf-bonus-value strong{color:#7ee0a0;font-weight:700}

.cf-bonus-total{text-align:center;padding:14px 0 4px}
.cf-bonus-total-label{font-family:'Cinzel',sans-serif !important;font-size:11px;letter-spacing:.24em;text-transform:uppercase;color:#a99cba;display:block;font-weight:600;margin-bottom:6px}
.cf-bonus-total-value{font-family:'Cormorant Garamond',serif !important;font-size:clamp(46px,7vw,60px);font-weight:600;color:#e8c97a;line-height:1;display:inline-block;background:linear-gradient(180deg,#f0d38a 0%,#c9982b 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;letter-spacing:-.02em}
.cf-bonus-total-value em{font-size:.42em;vertical-align:super;font-style:normal;margin-right:4px;font-weight:500}
.cf-bonus-total-note{display:block;font-family:'Special Elite',monospace !important;font-size:11.5px;letter-spacing:.18em;text-transform:uppercase;color:#7ee0a0;margin-top:6px}

@media (max-width:640px){
  .cf-supplementary-body{padding:12px 18px 24px}
  .cf-supplementary-header{padding:18px 20px 0}
  .cf-bonus-card{padding:18px 12px 14px !important}
  .cf-nocharge-stamp{font-size:8.5px;padding:3px 6px;top:9px;right:9px;letter-spacing:.14em}
}

/* Comparison panel (Most X does one, yours does both) */
.compare-panel{max-width:800px;margin:56px auto 0;padding:0 20px}
.compare-panel-inner{background:linear-gradient(180deg,rgba(45,27,105,.55),rgba(30,13,64,.72));border:1px solid rgba(212,175,55,.28);border-radius:14px;padding:34px 30px 32px;backdrop-filter:blur(4px)}
.compare-panel h3{font-family:'Cormorant Garamond',serif !important;font-size:clamp(22px,3.6vw,30px);font-weight:600;color:#fff;text-align:center;margin-bottom:10px;line-height:1.2}
.compare-panel h3 em{color:#e8c97a;font-style:italic}
.compare-panel .lead{text-align:center;color:#c8bfe0;font-size:16px;max-width:560px;margin:0 auto 22px;line-height:1.6;font-family:'Inter',sans-serif}
.compare-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:14px}
.compare-cell{border:1px solid rgba(212,175,55,.2);border-radius:10px;padding:20px 18px;text-align:left}
.compare-cell.us{border-color:rgba(212,175,55,.6);background:linear-gradient(180deg,rgba(212,175,55,.08),rgba(212,175,55,0))}
.compare-badge{font-family:'Cinzel',sans-serif !important;font-size:10.5px;letter-spacing:.22em;text-transform:uppercase;color:#8f88ab;display:block;margin-bottom:8px;font-weight:600}
.compare-cell.us .compare-badge{color:#d4af37}
.compare-cell p{font-family:'Cormorant Garamond',serif !important;font-size:17px;line-height:1.55;color:#e9e2f2;margin:0}
.compare-cell.them p{color:#a49dbe;text-decoration:line-through;text-decoration-color:rgba(240,171,171,.35);text-decoration-thickness:1px}
.compare-cell.us p{color:#fff}
@media (max-width:640px){.compare-grid{grid-template-columns:1fr}}

/* Warning pattern-interrupt callout */
.warn-callout{max-width:660px;margin:26px auto 0;padding:14px 20px;background:rgba(160,35,35,.10);border:1px solid rgba(240,171,171,.32);border-radius:8px;text-align:center;font-family:'Special Elite',monospace !important;font-size:13.5px;letter-spacing:.06em;color:#f0dca8;line-height:1.6}
.warn-callout .icon{color:#f0abab;margin-right:8px}

/* Trust chip trio (under every CTA) */
.trust-trio{display:flex;justify-content:center;align-items:center;gap:22px;flex-wrap:wrap;margin-top:16px;font-family:'Cinzel',sans-serif !important;font-size:11.5px;letter-spacing:.18em;text-transform:uppercase;color:#c8bfe0;font-weight:600}
.trust-trio > span{display:inline-flex;align-items:center;gap:8px}
.trust-trio .g-dot{width:5px;height:5px;background:#d4af37;border-radius:50%;display:inline-block}
.trust-trio strong{color:#e8c97a;font-weight:700}
@media (max-width:640px){.trust-trio{gap:14px;font-size:10.5px;letter-spacing:.12em}}
</style><script>
    window.clickmagick_cmc = {
        uid: '92654',
        hid: '1214314307',
        cmc_project: 'Soul Mirror Reading',
        cmc_goal: 'a',
        vid_info: 'on',
    }
</script>
<script src='//cdn.clkmc.com/cmc.js'></script>
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


<!-- Redesigned delivery notice - sealed envelope aesthetic -->
<div class="topnotice topnotice-envelope">
  <div class="topnotice-inner">
    <span class="topnotice-icon" aria-hidden="true">
      <svg viewBox="0 0 32 24" width="22" height="16"><rect x="1" y="3" width="30" height="18" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M2 4 L16 15 L30 4" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><circle cx="16" cy="14" r="3.2" fill="#c62828" stroke="#f0c774" stroke-width=".8"/></svg>
    </span>
    <span class="topnotice-text">
      <strong><span class="firstname">Friend</span>, Your Free Reading Is On Its Way</strong>
      <span class="topnotice-sep" aria-hidden="true">&#10022;</span>
      <strong>Read This First, It Changes What You Do With It</strong>
    </span>
    <span class="topnotice-status" aria-hidden="true">
      <span class="pulse-dot"></span>
      <span class="status-label">In Transit</span>
    </span>
  </div>
</div>



<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     HERO + VSL
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section class="vsl-section">
  <div class="wrap">
    

<h1 class="vsl-headline">There Is <span style="color:var(--gold-light);">One Hidden Pattern</span> Beneath Everything You've Struggled With.</h1>




<p class="vsl-sub">Your three cards have warned you about this for years. Ignored long enough, it quietly turns <span style="color:var(--gold-light);text-decoration:underline;text-decoration-color:rgba(212,175,55,0.6);text-decoration-thickness:2px;text-underline-offset:4px;">deadly</span>.</p>
<div class="tslwrap">
<video class="luna-hero" autoplay loop muted playsinline preload="auto" poster="assets/luna-portrait.jpg" aria-label="Luna Ross, your reader">
<source src="assets/luna-motion.mp4?v=3" type="video/mp4">
<img src="assets/luna-portrait.jpg" alt="Luna Ross, your reader">
</video>
<p class="luna-cap">Luna Ross, your reader</p>
<p class="tsl"><span class="firstname">Friend</span>, here on <span class="pdate">today</span>, I have all three of your cards in front of me.</p>
<p class="tsl cards-line" style="display:none"><span class="pcard" data-card="wealth">your Wealth card</span> in your Wealth house. <span class="pcard" data-card="love">your Love card</span> in your Love. <span class="pcard" data-card="life">your Life card</span> in your Life.</p>
<p class="tsl">The moment I laid them out, one pattern stepped forward.</p>
<p class="tsl">There is a number your money keeps returning to.</p>
<p class="tsl">No matter how hard you work, how much you learn, or how much you pray on it, you land back at the same figure.</p>
<p class="tsl">More comes close. A windfall, an unexpected opening, a yes you can almost taste.</p>
<p class="tsl">Then something unseen pulls it back, and the door quietly closes again.</p>
<p class="tsl"><span class="pcard" data-card="wealth">your Wealth card</span> landing in your Wealth house is where I see it most clearly.</p>
<p class="tsl">You have tried the things that were supposed to fix this. Therapy. Manifesting. The courses.</p>
<p class="tsl">Each one worked for a while. Then the same ceiling came back.</p>
<p class="tsl">That is not a willpower problem, <span class="firstname">Friend</span>. And it was never your fault.</p>
<p class="tsl">They all aimed at the symptom. None of them touched the root.</p>
<p class="tsl">It is a single belief you formed before you had words for it. Usually in childhood. About what is safe to have, and what it quietly costs you to keep it.</p>
<p class="tsl">It runs underneath your money, your love, and your sense of purpose, all at the same time.</p>
<p class="tsl">It is the same quiet pull that has you bracing right when love starts to feel safe, the small voice that says do not get too comfortable.</p>
<p class="tsl">It is why the work you know you were meant to do still feels one step away, always almost, never quite yet.</p>
<p class="tsl">This is your <span class="tslbold">Wealth Block</span>. And your three cards have been pointing straight at it.</p>
<figure class="tslfig"><video class="tslmid" autoplay loop muted playsinline preload="auto" poster="assets/luna-hands-cards.jpg" aria-label="Luna reading your three cards"><source src="assets/luna-hands-motion.mp4?v=1" type="video/mp4"><img class="tslmid" src="assets/luna-hands-cards.jpg" alt="Luna reading your three cards"></video><figcaption class="tslfigcap">The three cards you drew, as I read them.</figcaption></figure>
<p class="tsl">It speaks loudest in money, because money keeps the most precise record.</p>
<p class="tsl">Every time something in you whispers "not yet, not for me," you are following an instruction you did not knowingly write.</p>
<p class="tsl">Here is what it is doing right now.</p>
<p class="tsl">It is holding your income exactly where you have learned to expect it. And it is patient.</p>
<p class="tsl">It does not need a bad month to win. It only needs you to keep deciding you are not ready.</p>
<p class="tsl">Picture six months from now. The same ceiling. The same quiet math where you settle for less and call it realistic.</p>
<p class="tsl">A year from now, the loss has compounded. In money, and in the part of you that has stopped expecting more.</p>
<div class="tslpull">The block does not fight you. It waits you out. Left unnamed, it always wins, because you cannot clear what you cannot see.</div>
<p class="tsl">The free preview on its way to your inbox will confirm your Wealth Block is there, <span class="firstname">Friend</span>. It can show you its shape.</p>
<p class="tsl">What it cannot do is read <span class="pcard" data-card="wealth">your Wealth card</span>, <span class="pcard" data-card="love">your Love card</span>, and <span class="pcard" data-card="life">your Life card</span> together.</p>
<p class="tsl">That is your Soul Mirror Reading.</p>
<p class="tsl">I read all three by hand, <span class="firstname">Friend</span>, and show you the precise belief setting your ceiling, the exact moves it has been making behind your back, and the one practice that interrupts it where it actually lives.</p>
<p class="tsl">Hand-written for your cards. In your inbox within 24 hours.</p>
<p class="tsl">Your cards have already shown me what it is doing. The reading is how you finally see it, and begin to clear it.</p>
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
    <p class="gs-subhead">Three cards. Three mirrors. One hidden belief running all of them. This is what your reading has been pointing to.</p>

    <div class="gs-divider" aria-hidden="true"><span></span><i>&#10022;</i><span></span></div>

    <div class="gs-spread">

      <div class="gs-frame gs-frame-love cf-exhibit" data-card-slot="love">
        <span class="cf-corner tl"></span><span class="cf-corner tr"></span><span class="cf-corner bl"></span><span class="cf-corner br"></span>
        <div class="cf-exhibit-header">
          <span class="cf-exhibit-tag">Exhibit &middot; I</span>
          <span class="cf-exhibit-domain">Love Thread</span>
        </div>
        <div class="gs-emblem"><img loading="lazy" src="https://soulmirrorreading.com/cards/mirror-love.webp" alt="Love Mirror"></div>
        <div class="gs-kicker">The Mirror</div>
        <div class="gs-stage">
          <div class="gs-window card-wireframe" data-card-image="love">
            <span class="gs-numeral" aria-hidden="true">I</span>
            <div class="wf-content">
              <div class="wf-icon">&#10022;</div>
              <div class="wf-label">Your Card</div>
            </div>
            <span class="cf-sealed-stamp">Sealed<span class="cf-sealed-sub">Pending Reveal</span></span>
          </div>
        </div>
        <div class="gs-area">Love</div>
        <div class="gs-cardname card-name-label" data-card-name="love"></div>
        <p>Where you pull back right before connection becomes real. The pattern that keeps love feeling just slightly out of reach.</p>
      </div>

      <div class="gs-frame gs-frame-life cf-exhibit" data-card-slot="life">
        <span class="cf-corner tl"></span><span class="cf-corner tr"></span><span class="cf-corner bl"></span><span class="cf-corner br"></span>
        <div class="cf-exhibit-header">
          <span class="cf-exhibit-tag">Exhibit &middot; II</span>
          <span class="cf-exhibit-domain">Destiny Current</span>
        </div>
        <div class="gs-emblem"><img loading="lazy" src="https://soulmirrorreading.com/cards/mirror-life.webp" alt="Life Mirror"></div>
        <div class="gs-kicker">The Opening</div>
        <div class="gs-stage">
          <div class="gs-window card-wireframe" data-card-image="life">
            <span class="gs-numeral" aria-hidden="true">II</span>
            <div class="wf-content">
              <div class="wf-icon">&#10022;</div>
              <div class="wf-label">Your Card</div>
            </div>
            <span class="cf-sealed-stamp">Sealed<span class="cf-sealed-sub">Pending Reveal</span></span>
          </div>
        </div>
        <div class="gs-area">Life</div>
        <div class="gs-cardname card-name-label" data-card-name="life"></div>
        <p>Where your energy leaks and your choices keep looping. The place you feel most stuck, showing you the exact map you have been following.</p>
      </div>

      <div class="gs-frame gs-frame-wealth cf-exhibit" data-card-slot="wealth">
        <span class="cf-corner tl"></span><span class="cf-corner tr"></span><span class="cf-corner bl"></span><span class="cf-corner br"></span>
        <div class="cf-exhibit-header">
          <span class="cf-exhibit-tag">Exhibit &middot; III</span>
          <span class="cf-exhibit-domain">Wealth Gate</span>
        </div>
        <div class="gs-emblem"><img loading="lazy" src="https://soulmirrorreading.com/cards/mirror-wealth.webp" alt="Wealth Mirror"></div>
        <div class="gs-kicker">The Shadow</div>
        <div class="gs-stage">
          <div class="gs-window card-wireframe" data-card-image="wealth">
            <span class="gs-numeral" aria-hidden="true">III</span>
            <div class="wf-content">
              <div class="wf-icon">&#10022;</div>
              <div class="wf-label">Your Card</div>
            </div>
            <span class="cf-sealed-stamp">Sealed<span class="cf-sealed-sub">Pending Reveal</span></span>
          </div>
        </div>
        <div class="gs-area">Wealth</div>
        <div class="gs-cardname card-name-label" data-card-name="wealth"></div>
        <p>What you believe you are allowed to have. The inherited story about deserving that has been setting your ceiling without your knowledge.</p>
      </div>

    </div>

    <div class="gs-connector" aria-hidden="true">
      <span class="gs-connector-line"></span>
      <span class="gs-connector-label">Three Exhibits, One Root</span>
      <span class="gs-connector-arrow">&#9662;</span>
    </div>

    <!-- Wealth Block Featured - restyled as case-file "Root Identified" panel -->
    <div class="cf-root-panel">
      <span class="cf-grain" aria-hidden="true"></span>
      <span class="cf-corner tl"></span><span class="cf-corner tr"></span><span class="cf-corner bl"></span><span class="cf-corner br"></span>
      <div class="cf-root-header">
        <span class="cf-root-tag">Root Cause &middot; Identified</span>
        <span class="cf-root-tag right">Case File #4,802</span>
      </div>
      <div class="cf-root-body">
        <div class="cf-root-emblem">
          <div class="cf-root-emblem-ring"></div>
          <img loading="lazy" src="https://soulmirrorreading.com/cards/mirror-block.webp" alt="Wealth Block">
        </div>
        <div class="cf-root-content">
          <span class="cf-root-eyebrow">The Hidden Layer</span>
          <h4 class="cf-root-title">This Is Your Wealth Block.</h4>
          <p class="cf-root-desc">The one belief running all three. Until it is named clearly, every reading you ever get will point to the same wall.</p>
        </div>
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

    

<p style="text-align:center; max-width:600px; margin:0 auto 18px;">Your three cards point to one belief sitting at the root of your wealth. The same belief is quietly shaping your love and your purpose too. Name it once, and all three start to move.</p>

    <p style="text-align:center; max-width:600px; margin:0 auto 36px;">This is not a horoscope. Like a doctor reading three symptoms together instead of one at a time, it reads the <strong style="color:var(--gold-light);">three cards you actually drew</strong>, and the houses they landed in, as one connected system. That is what turns an interesting card into the exact pattern you keep living. Built from your cards. Never a script.</p>



    <!-- Down arrow / decoding indicator -->
    <div style="text-align:center; margin:24px auto 8px;">
      <div class="gs-connector-label" style="margin-bottom:8px;">Now The Pattern Has A Name</div>
      <div style="color:var(--gold); font-size:24px; line-height:1;">&darr;</div>
    </div>

    <!-- Diagnosis card - restyled as an official case-file diagnosis panel -->
    <div class="diagnosis-file">
      <div class="diagnosis-file-inner">
        <!-- Paper grain + minimal corner brackets -->
        <span class="cf-grain" aria-hidden="true"></span>
        <span class="cf-corner tl"></span><span class="cf-corner tr"></span><span class="cf-corner bl"></span><span class="cf-corner br"></span>

        <!-- Header row -->
        <div class="dx-header-row">
          <span class="dx-tag">Diagnosis // Confirmed</span>
          <span class="dx-tag right">Case File #4,802</span>
        </div>

        <div class="dx-body">
          <span class="dx-eyebrow">Reading Interpretation</span>
          <div class="dx-diamond" aria-hidden="true">&#10022;</div>
          <h4 data-mirror-block-name class="dx-title">Your Wealth Is Blocked</h4>
          <p data-mirror-block-summary class="dx-quote">&ldquo; Money arrives. Money quietly leaves. The same ceiling, year after year, no matter what you try. &rdquo;</p>

          <div class="dx-divider"><span></span><i>&#10022;</i><span></span></div>

          <div class="dx-prescription">
            <span class="dx-prescription-label">Prescribed &middot; The Soul Mirror Reading</span>
            <p>Your Soul Mirror Reading shows you <strong>exactly how</strong> your Wealth Block is working, and the one practice that clears it. Not more effort, not another course, not trying harder. Just one quiet shift, and the pattern that kept pulling your money back finally loosens its grip, so what comes in has a <strong>real chance to stay</strong>.</p>
          </div>

          <div class="dx-signature">
            <div class="dx-sig-line">Reader &middot; <em>Luna Ross</em></div>
            <div class="dx-sig-line dx-sig-meta">Prepared 27 &middot; 06 &middot; 2026 &middot; Twelve Years of Practice</div>
          </div>
        </div>
      </div>
    </div>

    

    <!-- CTA after diagnostic proof -->
    <div style="text-align:center; margin-top:40px;">
      <a href="https://rebornf.pay.clickbank.net/?cbitems=smr-1&amp;template=order-4&amp;cbfid=63520&amp;exitoffer=exit-1&amp;vtid=[cmc_vid]" class="cta">Clear My Wealth Block &rarr;</a>
      <p style="max-width:480px; margin:14px auto 0; font-size:14px; line-height:1.6; color:rgba(255,255,255,0.72);">Your free cards named the block. Your full reading reads all three together and shows you <strong style="color:var(--gold-light);">exactly how it works</strong>, where it took root, and the practice that clears it. Hand-written for your cards, in your inbox within 24 hours.</p>
      <div class="cta-trust-row" style="margin-top:16px;">
        <span>&#128274; Secure Checkout</span>
        <span>&#9889; Delivered in 12-24 Hrs</span>
      </div>
      <p style="max-width:500px; margin:12px auto 0; font-size:13px; line-height:1.6; color:rgba(255,255,255,0.62);"><span style="color:var(--gold-light);">&#127769; 90-day promise.</span> Read your full reading. If it is not worth far more than $37 to you, for any reason, reply and I refund every penny.</p>
      <p style="max-width:460px; margin:18px auto 0; font-size:13px; font-style:italic; line-height:1.6; color:rgba(255,255,255,0.6);">&ldquo;It named the exact belief that had quietly kept me at the same number for years. Once I could finally see it, I stopped quietly talking myself out of asking for what my work is worth.&rdquo; <span style="color:var(--gold-light); font-style:normal;">Hannah T., 44</span></p>
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
      <p class="testi-body">"I almost closed the tab. After ten years of courses and readings that all said the same thing in slightly different words, I expected more of the same. It was not. The Wealth Block Luna named was the exact reason I had spent fifteen years quoting low and apologizing for my prices. Two weeks later a client asked for a full brand kit. Normally I would have said fifteen hundred and felt guilty. I sat with what the reading showed me and sent the quote for four thousand. She replied 'that sounds fair' and paid the deposit that afternoon. I am not saying the cards did it. I am saying they finally let me see the wall I had been pricing myself behind."</p>
    </div>
    <p style="text-align:center; max-width:580px; margin:16px auto 0; font-size:11.5px; color:rgba(255,255,255,0.45); font-style:italic; line-height:1.6;">Individual results vary and are not typical. A Soul Mirror Reading is for insight and self-reflection. It is not financial advice and does not guarantee income or any specific outcome.</p>
  </div>
</section>
<!-- REDACTED CASE-FILE PREVIEW v2 - real reading vocabulary + more flare -->
<section class="section" style="padding:26px 0 44px;">
  <div class="case-preview">
    <!-- Intro connector -->
    <div style="text-align:center;margin-bottom:22px;">
      <div class="gs-connector-label" style="margin-bottom:8px;letter-spacing:.25em;">The Reading That Is Waiting In Your Inbox</div>
      <div style="color:var(--gold); font-size:20px; line-height:1;">&#10022;</div>
    </div>

    <div class="case-preview-card">
      <!-- Watermark background -->
      <div class="case-watermark" aria-hidden="true">CONFIDENTIAL</div>

      <!-- Star-field decoration -->
      <div class="case-stars" aria-hidden="true">
        <span class="star" style="top:14%;left:8%"></span>
        <span class="star" style="top:32%;left:88%"></span>
        <span class="star" style="top:58%;left:5%"></span>
        <span class="star" style="top:71%;left:92%"></span>
        <span class="star" style="top:88%;left:16%"></span>
      </div>

      <!-- Paper grain + minimal corner brackets -->
      <span class="cf-grain" aria-hidden="true"></span>
      <span class="cf-corner tl"></span><span class="cf-corner tr"></span><span class="cf-corner bl"></span><span class="cf-corner br"></span>

      <!-- Meta header row -->
      <div class="case-preview-metarow">
        <span class="meta-tag">Form SMR-3C &middot; Rev. 2026</span>
        <span class="meta-tag right">File Classification: <strong style="color:#f0abab;">A</strong></span>
      </div>

      <!-- Header -->
      <div class="case-preview-header">
        <div class="case-preview-stamp">Sealed &middot; Recipient Eyes Only</div>
        <div class="case-diamond" aria-hidden="true">&#10022;</div>
        <div class="case-preview-title">Soul Mirror Reading<br /><em>Case File #4,802</em></div>
        <div class="case-preview-sub">Prepared 27 &middot; 06 &middot; 2026 &middot; L. Ross, Senior Reader &middot; Twelve Years of Practice</div>
      </div>

      <!-- Three Card Positions (the reading's actual architecture) -->
      <div class="case-positions" aria-hidden="true">
        <div class="case-position">
          <div class="case-position-icon"><svg viewBox="0 0 32 32"><path d="M2 16 Q16 4 30 16 Q16 28 2 16 Z" fill="none" stroke="currentColor" stroke-width="1.4"/><circle cx="16" cy="16" r="4" fill="currentColor"/></svg></div>
          <div class="case-position-kicker">Position One</div>
          <div class="case-position-name">The Shadow</div>
          <div class="case-position-sub">Wealth Gate</div>
        </div>
        <div class="case-position-sep">&#10022;</div>
        <div class="case-position">
          <div class="case-position-icon"><svg viewBox="0 0 32 32"><path d="M22 5 A13 13 0 1 0 22 27 A11 11 0 1 1 22 5 Z" fill="currentColor"/></svg></div>
          <div class="case-position-kicker">Position Two</div>
          <div class="case-position-name">The Mirror</div>
          <div class="case-position-sub">Love Thread</div>
        </div>
        <div class="case-position-sep">&#10022;</div>
        <div class="case-position">
          <div class="case-position-icon"><svg viewBox="0 0 32 32"><path d="M16 3 L19 13 L29 13 L21 19 L24 29 L16 23 L8 29 L11 19 L3 13 L13 13 Z" fill="currentColor"/></svg></div>
          <div class="case-position-kicker">Position Three</div>
          <div class="case-position-name">The Opening</div>
          <div class="case-position-sub">Destiny Current</div>
        </div>
      </div>

      <!-- Divider -->
      <div class="case-divider"><span class="case-divider-dot"></span><i>&#10022;</i><span class="case-divider-dot"></span></div>

      <!-- Cards Drawn -->
      <div class="case-preview-line">
        <span class="label">Cards Drawn <span style="color:#f0abab;">// Redacted</span></span>
        <div class="case-cards-row">
          <span><em>Shadow:</em> <span class="redact short">&nbsp;</span></span>
          <span><em>Mirror:</em> <span class="redact short">&nbsp;</span></span>
          <span><em>Opening:</em> <span class="redact short">&nbsp;</span></span>
        </div>
      </div>

      <!-- Your Mirror Block Type -->
      <div class="case-preview-line">
        <span class="label">Your Mirror Block Type &nbsp; // &nbsp; One Of Four</span>
        <div style="margin:2px 0 6px;">
          <span class="type-marker">Type</span> <span class="redact type-num">?</span>
          <span style="margin-left:12px;">&middot;</span>
          <span class="redact block-name">&nbsp;</span>&nbsp;<em style="color:#a99cba;">Block</em>
        </div>
      </div>

      <!-- Root Belief -->
      <div class="case-preview-line case-quote-line">
        <span class="label">The Root Belief &nbsp; // &nbsp; One Sentence, Spoken in Three Languages</span>
        <blockquote class="case-quote">&ldquo; <span class="redact long">&nbsp;</span> <span class="redact med">&nbsp;</span>. <span class="redact med">&nbsp;</span> <span class="redact short">&nbsp;</span>. &rdquo;</blockquote>
      </div>

      <!-- How It Formed / Timeline -->
      <div class="case-preview-line">
        <span class="label">How It Formed &nbsp; // &nbsp; Encoded Before You Had Language For It</span>
        <div class="case-timeline">
          <div class="case-timeline-track">
            <div class="case-timeline-node"><span class="dot"></span><div class="lbl">Birth</div><div class="sub">&nbsp;</div></div>
            <div class="case-timeline-node active"><span class="dot"></span><div class="lbl">Age <span class="redact xshort">?</span></div><div class="sub"><em>the belief forms</em></div></div>
            <div class="case-timeline-node"><span class="dot"></span><div class="lbl">Adult Life</div><div class="sub"><em>still running</em></div></div>
            <div class="case-timeline-node"><span class="dot"></span><div class="lbl">Today</div><div class="sub"><em>you see it at last</em></div></div>
          </div>
        </div>
      </div>

      <!-- What It Has Cost You -->
      <div class="case-preview-line">
        <span class="label">What It Has Cost You &nbsp; // &nbsp; The Distance Between What You Have Done And What You Have Allowed Yourself To Have</span>
        <div>In the <em>Wealth Gate</em>: <span class="redact med">&nbsp;</span> for years, deflecting <span class="redact short">&nbsp;</span> before it could land.</div>
        <div style="margin-top:6px;">In the <em>Love Thread</em>: <span class="redact med">&nbsp;</span>, kept at <span class="redact short">&nbsp;</span>.</div>
        <div style="margin-top:6px;">In the <em>Destiny Current</em>: the <span class="redact med">&nbsp;</span> you never quite begun.</div>
      </div>

      <!-- What Changes When It Lifts -->
      <div class="case-preview-line">
        <span class="label">What Changes When It Lifts &nbsp; // &nbsp; What The First Two Weeks Look Like</span>
        <div>You state a price and <span class="redact short">&nbsp;</span>. You let the silence after it sit without <span class="redact short">&nbsp;</span>. The <span class="redact short">&nbsp;</span> you have been preparing for years finally <span class="redact short">&nbsp;</span>.</div>
      </div>

      <!-- What Activates It -->
      <div class="case-preview-line">
        <span class="label">What Activates It &nbsp; // &nbsp; The Eight Moments To Watch For</span>
        <div class="case-activation-list">
          <div><i>&#10022;</i> An opportunity that would require you to claim <span class="redact short">&nbsp;</span> you have not publicly stated before.</div>
          <div><i>&#10022;</i> Positive feedback that <span class="redact short">&nbsp;</span> you feel you have earned.</div>
          <div><i>&#10022;</i> A relationship that offers more <span class="redact short">&nbsp;</span> than you feel justified in having yet.</div>
          <div><i>&#10022;</i> The moment a long preparation ends and there is nothing left to prepare, only <span class="redact short">&nbsp;</span> to begin.</div>
          <div class="case-activation-more">+ four more moments &middot; sealed until reveal</div>
        </div>
      </div>

      <!-- The Ritual -->
      <div class="case-preview-line">
        <span class="label">A Ritual For Your Mirror Block Type &nbsp; // &nbsp; Written For You Alone</span>
        <div>A three-step practice: <span class="redact med">&nbsp;</span>, <span class="redact med">&nbsp;</span>, and <span class="redact med">&nbsp;</span>. Meant to be run each morning for <span class="redact xshort">?</span> days.</div>
      </div>

      <!-- Divider -->
      <div class="case-divider"><span class="case-divider-dot"></span><i>&#10022;</i><span class="case-divider-dot"></span></div>

      <!-- Wax seal + signature block -->
      <div class="case-seal-row">
        <div class="case-seal" aria-hidden="true">
          <div class="case-seal-ring"></div>
          <div class="case-seal-inner has-photo">
            <img src="https://soulmirrorreading.com/frontend/images/sales/luna-ross-seal.png" alt="Luna Ross" class="case-seal-photo">
            <div class="case-seal-monogram">L R</div>
          </div>
        </div>
        <div class="case-seal-meta">
          <div class="case-seal-name">Luna Ross</div>
          <div class="case-seal-title">Soul Mirror Reader &middot; Reader #001</div>
          <div class="case-seal-line">Reviewed and supervised, personally.</div>
        </div>
      </div>

      <div class="case-preview-tag">Access Prepared &middot; Awaiting Recipient Confirmation &middot; Delivery Within 24 Hrs</div>
    </div>
  </div>
</section>

<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     INSIDE YOUR SOUL MIRROR READING
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section class="section">
  <div class="wrap">
    <!-- Offer box - restyled as case-file contents / attached materials -->
    <div class="contents-file">
      <div class="contents-file-inner">
        <!-- Paper grain + minimal corner brackets -->
        <span class="cf-grain" aria-hidden="true"></span>
        <span class="cf-corner tl"></span><span class="cf-corner tr"></span><span class="cf-corner bl"></span><span class="cf-corner br"></span>

        <!-- Header row -->
        <div class="dx-header-row">
          <span class="dx-tag">Contents // Case File Attached</span>
          <span class="dx-tag right">Valued at $197</span>
        </div>

        <div class="contents-body">
          <span class="dx-eyebrow">Inside The Envelope</span>
          <div class="dx-diamond" aria-hidden="true">&#10022;</div>
          <h4 class="contents-title">Your Soul Mirror Reading</h4>
          <p class="contents-sub">Seven documents. One case file. Written by hand from your specific three-card draw.</p>

          <img loading="lazy" src="https://soulmirrorreading.com/frontend/images/sales/soul-mirror-reading.webp" alt="Soul Mirror Reading" class="contents-mockup">

          <div class="dx-divider"><span></span><i>&#10022;</i><span></span></div>

          <ol class="contents-index">
            <li>
              <span class="ci-num">01</span>
              <div class="ci-body">
                <strong>Your Wealth Block Identified.</strong>
                <span class="ci-detail">The one core belief running Love, Life, and Wealth at the same time.</span>
              </div>
              <span class="ci-tag">Primary</span>
            </li>
            <li>
              <span class="ci-num">02</span>
              <div class="ci-body">
                <strong>Love Mirror Deep-Dive.</strong>
                <span class="ci-detail">What your Love card means in the context of all three cards.</span>
              </div>
              <span class="ci-tag">Enclosed</span>
            </li>
            <li>
              <span class="ci-num">03</span>
              <div class="ci-body">
                <strong>Life Mirror Deep-Dive.</strong>
                <span class="ci-detail">Where your energy is leaking, and what it would take to shift it.</span>
              </div>
              <span class="ci-tag">Enclosed</span>
            </li>
            <li>
              <span class="ci-num">04</span>
              <div class="ci-body">
                <strong>Wealth Mirror Deep-Dive.</strong>
                <span class="ci-detail">The inherited story about deserving that has been setting your ceiling.</span>
              </div>
              <span class="ci-tag">Enclosed</span>
            </li>
            <li>
              <span class="ci-num">05</span>
              <div class="ci-body">
                <strong>Mirror Block Clearing Practice.</strong>
                <span class="ci-detail">Seven questions, ten minutes, designed to begin the release.</span>
              </div>
              <span class="ci-tag">Enclosed</span>
            </li>
            <li>
              <span class="ci-num">06</span>
              <div class="ci-body">
                <strong>Reversed Card Companion.</strong>
                <span class="ci-detail">Nuanced interpretation if any of your cards appeared reversed.</span>
              </div>
              <span class="ci-tag">Enclosed</span>
            </li>
            <li>
              <span class="ci-num">07</span>
              <div class="ci-body">
                <strong>90-Day Mirror Check-In Prompts.</strong>
                <span class="ci-detail">Twelve weekly questions to keep the clarity working.</span>
              </div>
              <span class="ci-tag">Follow-Up</span>
            </li>
          </ol>

          <div class="contents-signature">
            <div class="dx-sig-line">Prepared By &middot; <em>Luna Ross</em></div>
            <div class="dx-sig-line dx-sig-meta">Delivered Within 24 Hours &middot; Recipient Eyes Only</div>
          </div>
        </div>
      </div>

      <!-- CTA below the offer box (kept outside the case-file frame) -->
      <div style="text-align:center; margin-top:36px;">
        <a href="https://rebornf.pay.clickbank.net/?cbitems=smr-1&amp;template=order-4&amp;cbfid=63520&amp;exitoffer=exit-1&amp;vtid=[cmc_vid]" class="cta">Clear My Wealth Block &rarr;</a>
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
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section class="section" style="position:relative;">
  <div class="wrap">
    <div class="cf-supplementary">
      <span class="cf-grain" aria-hidden="true"></span>
      <span class="cf-corner tl"></span><span class="cf-corner tr"></span><span class="cf-corner bl"></span><span class="cf-corner br"></span>
      <div class="cf-supplementary-header">
        <span class="cf-supplementary-tag">Supplementary Materials &middot; Attached</span>
        <span class="cf-supplementary-tag right">Enclosed Free of Charge</span>
      </div>
      <div class="cf-supplementary-body">
        <span class="cf-supplementary-eyebrow">Also Included In This Case File</span>
        <h2 class="cf-supplementary-title">4 Bonuses Included <em>Free</em></h2>
        <p class="cf-supplementary-sub">Available only on this page, when you claim your Soul Mirror Reading now.</p>

        <div class="bonus-grid cf-bonus-grid">
          <div class="bonus-card-compact cf-bonus-card">
            <span class="cf-nocharge-stamp">No Charge</span>
            <img loading="lazy" src="https://soulmirrorreading.com/frontend/images/sales/mirror-block-companion-guide.webp" alt="Companion Guide">
            <div class="cf-bonus-num">Attachment &middot; 01</div>
            <h4>Mirror Block Companion Guide</h4>
            <div class="cf-bonus-value"><s>$67</s> <strong>Free</strong></div>
          </div>
          <div class="bonus-card-compact cf-bonus-card">
            <span class="cf-nocharge-stamp">No Charge</span>
            <img loading="lazy" src="https://soulmirrorreading.com/frontend/images/sales/21-days-shift-tracker.webp" alt="Shift Tracker">
            <div class="cf-bonus-num">Attachment &middot; 02</div>
            <h4>21-Day Shift Tracker</h4>
            <div class="cf-bonus-value"><s>$47</s> <strong>Free</strong></div>
          </div>
          <div class="bonus-card-compact cf-bonus-card">
            <span class="cf-nocharge-stamp">No Charge</span>
            <img loading="lazy" src="https://soulmirrorreading.com/frontend/images/sales/root-cause-reading-guide.webp" alt="Root Cause Guide">
            <div class="cf-bonus-num">Attachment &middot; 03</div>
            <h4>Root Cause Reading Guide</h4>
            <div class="cf-bonus-value"><s>$47</s> <strong>Free</strong></div>
          </div>
          <div class="bonus-card-compact cf-bonus-card">
            <span class="cf-nocharge-stamp">No Charge</span>
            <img loading="lazy" src="https://soulmirrorreading.com/frontend/images/sales/mirror-clarity-meditation.webp" alt="Clarity Meditation">
            <div class="cf-bonus-num">Attachment &middot; 04</div>
            <h4>Mirror Clarity Meditation</h4>
            <div class="cf-bonus-value"><s>$37</s> <strong>Free</strong></div>
          </div>
        </div>

        <div class="charges-divider" style="margin:26px 0 16px;"><span></span><i>&#10022;</i><span></span></div>

        <div class="cf-bonus-total">
          <span class="cf-bonus-total-label">Combined Value Of Attachments</span>
          <span class="cf-bonus-total-value"><em>$</em>198</span>
          <span class="cf-bonus-total-note">Yours Free Today</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     CONSOLIDATED SCARCITY + COUNTDOWN + PRICE ANCHOR (ALS-style)
     Replaces: Daily Cap + Objection Killer + standalone Countdown
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section style="padding: 60px 24px 40px; text-align:center; position:relative;">
  <div class="wrap">
    <h2 style="margin-bottom:18px;"><span class="firstname">Friend</span>, Your Soul Mirror Reading<br>Is Written by Hand.<br><em>Order Today, It Lands Within 24 Hours.</em></h2>

    <p style="max-width:560px; margin:0 auto 14px; color:rgba(255,255,255,0.85); line-height:1.7;">Luna writes every reading by hand from your specific 3-card combination. Order now and yours goes into today's writing queue, delivered straight to your inbox within 24 hours.</p>

    <p style="max-width:560px; margin:0 auto 32px; color:rgba(255,255,255,0.7); font-style:italic;">The exact combination you drew today is what makes the diagnosis precise. Come back in a week and the cards, and the reading, will be different.</p>


    <p style="max-width:560px; margin:0 auto 24px; color:rgba(255,255,255,0.85); line-height:1.7;">Luna's private readings run <strong style="color:var(--gold-light); text-decoration:line-through; text-decoration-color: rgba(212,175,55,0.5);">$395</strong>.<br>Today, the complete package with all 4 bonuses is just <strong style="color:var(--gold-light); font-size:18px;">$37</strong>.</p>

    <div style="text-align:center; margin-top:24px;">
      <a href="https://rebornf.pay.clickbank.net/?cbitems=smr-1&amp;template=order-4&amp;cbfid=63520&amp;exitoffer=exit-1&amp;vtid=[cmc_vid]" class="cta">Get My Full Reading &middot; $37 &rarr;</a>
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
<section class="section" style="padding:36px 20px 60px;">
  <div class="charges-file">
    <div class="charges-file-inner">
      <!-- Paper grain + minimal corner brackets -->
      <span class="cf-grain" aria-hidden="true"></span>
      <span class="cf-corner tl"></span><span class="cf-corner tr"></span><span class="cf-corner bl"></span><span class="cf-corner br"></span>

      <!-- Header row -->
      <div class="dx-header-row">
        <span class="dx-tag">&nbsp;</span>
        <span class="dx-tag right">Case File #4,802</span>
      </div>

      <div class="charges-body">
        <span class="dx-eyebrow">Amount Due</span>
        <div class="dx-diamond" aria-hidden="true">&#10022;</div>
        <h4 class="charges-title">Everything Below.</h4>
        <p class="charges-sub">One-time payment. Delivered within 12 to 24 hours.</p>

        <!-- Ledger -->
        <ul class="charges-ledger">
          <li>
            <span class="ledger-num">01</span>
            <div class="ledger-item">Your personalised Soul Mirror Reading</div>
            <span class="ledger-value">$197</span>
          </li>
          <li>
            <span class="ledger-num">02</span>
            <div class="ledger-item">Mirror Block identification &middot; all 3 deep-dives</div>
            <span class="ledger-value ledger-enclosed">Enclosed</span>
          </li>
          <li>
            <span class="ledger-num">03</span>
            <div class="ledger-item">Mirror Block Clearing Practice &middot; 90-day prompts</div>
            <span class="ledger-value ledger-enclosed">Enclosed</span>
          </li>
          <li class="ledger-bonus">
            <span class="ledger-num">04</span>
            <div class="ledger-item"><strong>Bonus 1.</strong> Companion Guide</div>
            <span class="ledger-value ledger-free"><s>$67</s> Free</span>
          </li>
          <li class="ledger-bonus">
            <span class="ledger-num">05</span>
            <div class="ledger-item"><strong>Bonus 2.</strong> 21-Day Shift Tracker</div>
            <span class="ledger-value ledger-free"><s>$47</s> Free</span>
          </li>
          <li class="ledger-bonus">
            <span class="ledger-num">06</span>
            <div class="ledger-item"><strong>Bonus 3.</strong> Root Cause Guide</div>
            <span class="ledger-value ledger-free"><s>$47</s> Free</span>
          </li>
          <li class="ledger-bonus">
            <span class="ledger-num">07</span>
            <div class="ledger-item"><strong>Bonus 4.</strong> Clarity Meditation</div>
            <span class="ledger-value ledger-free"><s>$37</s> Free</span>
          </li>
        </ul>

        <div class="charges-divider"><span></span><i>&#10022;</i><span></span></div>

        <!-- Totals -->
        <div class="charges-totals">
          <div class="totals-row totals-original">
            <span class="totals-label">Usual Rate</span>
            <span class="totals-strike">$395</span>
          </div>
          <div class="totals-row totals-today">
            <span class="totals-label">Amount Due Today</span>
            <span class="totals-price"><em>$</em>37</span>
          </div>
        </div>

        <!-- CTA inside the frame -->
        <div style="text-align:center; margin-top:26px;">
          <a href="https://rebornf.pay.clickbank.net/?cbitems=smr-1&amp;template=order-4&amp;cbfid=63520&amp;exitoffer=exit-1&amp;vtid=[cmc_vid]" class="cta">Get My Full Reading &middot; $37 &rarr;</a>
        </div>

        <div class="charges-signature">
          <div class="dx-sig-line">Filed By &middot; <em>Luna Ross</em></div>
          <div class="dx-sig-line dx-sig-meta">Delivered Within 24 Hours &middot; One-Time Payment &middot; No Rebill</div>
        </div>
      </div>
    </div>

    <!-- Guarantee (kept as-is, outside the case-file frame) -->
    <div class="pricing-inline-guarantee" style="margin-top:24px;">
      <img loading="lazy" src="https://soulmirrorreading.com/cards/guarantee-badge.webp" alt="90-Day Guarantee">
      <div><strong>90-Day Money-Back Guarantee.</strong><br>Read your full reading. If it is not worth far more than $37 to you, for any reason, reply and I refund every penny.</div>
    </div>

    <div class="trust-badge-row" style="margin-top:14px;">
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
      

<p class="testi-body">"I have had tarot readings for twenty years and always felt something was missing. This one gave me the piece. The Wealth Block Luna named was the same reason I had been undercharging in my last three jobs. One belief. Three rooms of my life. Six weeks later I raised my rates for the first time in four years, and the first client I sent the new number to said yes without blinking. The extra has been quietly adding up every month since."</p>


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
      <p class="testi-body">"I was skeptical. I'm a practical person. I just wanted to see what the cards said. But the Wealth Block explanation stopped me cold. It named the exact thing I do every time money gets close, I quietly talk the number down. I held my price on the very next deal instead of discounting it like I always had. That one change paid for this many times over."</p>
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
      <p class="testi-body">"Three cards. One pattern. I have spent years in therapy trying to understand why the same things kept happening in love, at work, with money. This report showed me in 20 minutes. The clearing practice alone is worth ten times what I paid."</p>
    </div>
    <p style="text-align:center; max-width:580px; margin:22px auto 0; font-size:11.5px; color:rgba(255,255,255,0.45); font-style:italic; line-height:1.6;">Individual results vary and are not typical. A Soul Mirror Reading is for insight and self-reflection. It is not financial advice and does not guarantee income or any specific outcome.</p>

    <!-- CTA after testimonials cluster -->
    <div style="text-align:center; margin-top:40px;">
      <a href="https://rebornf.pay.clickbank.net/?cbitems=smr-1&amp;template=order-4&amp;cbfid=63520&amp;exitoffer=exit-1&amp;vtid=[cmc_vid]" class="cta">Get My Full Reading &middot; $37 &rarr;</a>
      <div class="cta-trust-row" style="margin-top:16px;">
        <span>&#128274; Secure Checkout</span>
        <span>&#9889; Delivered in 12-24 Hrs</span>
        <span>&#127769; 90-Day Guarantee</span>
      </div>
    </div>
  </div>
</section>

<!-- Warning pattern-interrupt (standalone, was inside removed comparison panel) -->
<section class="section" style="padding:24px 0 16px;">
  <div class="wrap">
    <div class="warn-callout">
      <span class="icon">&#9888;</span> Most readers name their block on the second card, not the first. Read all three before you decide what it means.
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
        <p style="font-size:18px; color:rgba(255,255,255,0.85); line-height:1.7; margin:0;">Within 24 hours, your Soul Mirror Reading lands in your inbox. You read it once on your couch. The next morning you read it again, slower. You see the single belief that has been running underneath every choice in love, money, and purpose. The work begins from there. Most people tell me it takes 21 days before they notice they have stopped doing the thing they have been doing for years.</p>
      </div>
      <!-- Path B -->
      <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.12); border-radius: 14px; padding: 28px 22px; backdrop-filter: blur(4px);">
        <div style="font-family:'Cinzel',sans-serif; font-size:11px; letter-spacing:0.22em; color:rgba(255,255,255,0.5); text-transform:uppercase; margin-bottom:14px;">Path B</div>
        <h3 style="font-family:'Cormorant Garamond',serif; font-size:22px; color:rgba(255,255,255,0.7); font-weight:600; line-height:1.3; margin-bottom:14px;">You <em style="color:rgba(255,255,255,0.55); font-style:italic;">Don't.</em></h3>
        <p style="font-size:18px; color:rgba(255,255,255,0.6); line-height:1.7; margin:0;">You close this page. You finish your tea. The pattern keeps running. Six months from now, you are back here. Or somewhere else, looking at the same wall in different paint. The cards you chose minutes ago will not carry the same precision a week from now. The window narrows quietly.</p>
      </div>
    </div>

    

<div style="text-align:center; margin-top:40px;">
      <a href="https://rebornf.pay.clickbank.net/?cbitems=smr-1&amp;template=order-4&amp;cbfid=63520&amp;exitoffer=exit-1&amp;vtid=[cmc_vid]" class="cta">Take Path A &middot; Get My Full Reading &middot; $37 &rarr;</a>
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
        <summary>What is a Wealth Block exactly?</summary>
        <p class="faq-a">A Wealth Block is a core belief, usually formed early in life and often inherited from someone else, that shows up identically across your love life, your daily experience, and your relationship with money and abundance. It is not a flaw and it is not permanent. But it is specific, and once you can see it clearly, it loses most of its power.</p>
      </details>
      <details>
        <summary>How is this different from the reading I will receive in my email?</summary>
        

<p class="faq-a">The email reading shows you what each card says in isolation. The Soul Mirror Reading decodes what your three cards mean together. The pattern running underneath them, the single belief connecting them, and the Clearing Practice to begin loosening it. One card is information. Three cards read as a system is a diagnosis.</p>


      </details>
      <details>
        <summary>What happens after I order?</summary>
        <p class="faq-a">Your reading takes 12 to 24 hours to complete, hand-written by Luna for your specific card combination. You will receive it as a PDF in your inbox. Use the Clearing Practice once. Seven questions, ten minutes. The shift begins there.</p>
      </details>
      <details>
        <summary>What if I am not satisfied?</summary>
        <p class="faq-a">You are covered by a 90-day money-back guarantee. Read your full reading. If it is not worth far more than $37 to you, for any reason, reply to the delivery email and I refund every penny. In nineteen years, that has almost never happened.</p>
      </details>
    </div>
  </div>
</section>

<!-- &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;
     FINAL CTA
     &#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552;&#9552; -->
<section style="padding: 0 24px 60px;">
  <div class="final-cta">
    <h2><span class="firstname">Friend</span>, Your Cards Are Drawn.<br><em>Your Wealth Block Is Waiting to Be Cleared.</em></h2>
    

<p style="max-width:560px; margin:18px auto 24px; color:rgba(255,255,255,0.85);">The pattern is already there. It has been running quietly for years, draining your wealth, your love, and your sense of purpose. The only question is whether you let another year pass without seeing it clearly.</p>



    <div style="margin:28px auto 12px;">
      <img loading="lazy" src="https://soulmirrorreading.com/frontend/images/sales/bundle-product-image.webp" alt="Soul Mirror Reading complete bundle" style="max-width:420px; width:100%; height:auto; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.4));">
    </div>

    <div style="margin:24px 0;">
      <div style="font-family:'Cinzel',sans-serif; font-size:11px; letter-spacing:0.22em; color:var(--gold); text-transform:uppercase; margin-bottom:6px;">Total Value $395 &middot; Today</div>
      <div style="font-family:'Cormorant Garamond',serif; font-size:48px; color:var(--gold-light); font-weight:600;">$37</div>
    </div>

    <a href="https://rebornf.pay.clickbank.net/?cbitems=smr-1&amp;template=order-4&amp;cbfid=63520&amp;exitoffer=exit-1&amp;vtid=[cmc_vid]" class="cta">Get My Full Reading &middot; $37 &rarr;</a>
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
  <script defer src="assets/sales-v2.min.js?v=<?= htmlspecialchars((string) $jsVer, ENT_QUOTES) ?>"></script>
<script>(function(){function go(){try{var d=new Date();var t=d.toLocaleDateString('en-US',{month:'long',day:'numeric',year:'numeric'});var pd=document.querySelectorAll('.pdate');for(var i=0;i<pd.length;i++){pd[i].textContent=t;}}catch(e){}try{var any=false;var L=document.querySelectorAll('[data-card-name]');for(var j=0;j<L.length;j++){var k=L[j].getAttribute('data-card-name');var tx=(L[j].textContent||'').trim();if(tx){any=true;var pc=document.querySelectorAll('.pcard');for(var m=0;m<pc.length;m++){if(pc[m].getAttribute('data-card')===k){pc[m].textContent=tx;}}}}if(any){var cl=document.querySelectorAll('.cards-line');for(var n=0;n<cl.length;n++){cl[n].style.display='';}}}catch(e){}}document.addEventListener('DOMContentLoaded',go);window.addEventListener('load',go);var c=0,iv=setInterval(function(){go();if(++c>10){clearInterval(iv);}},250);})();</script></body>

</html>

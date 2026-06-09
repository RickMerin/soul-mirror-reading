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
  <!-- Refresh v8 v6 -->
  <title>Complete Your Soul Mirror Reading, The Clearing Practice From Luna Ross</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Crimson+Pro:ital,wght@0,300;0,400;0,600;1,300;1,400&display=swap"
    rel="stylesheet"
  />
  <style>
    :root {
      --violet-deep:   #2D1B69;
      --violet-mid:    #2a1252;
      --violet-dark:   #1a0d40;
      --gold:          #D4AF37;
      --gold-light:    #e8c97a;
      --gold-pale:     #f5e9c0;
      --cream:         #FEFCF8;
      --cream-warm:    #f9f5ec;
      --text-body:     #2c2040;
      --text-muted:    #6b5c82;
      --white:         #ffffff;
      --gold-dim:      #8B6914;
      --text-mid:      #6b5c82;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      background-color: var(--cream);
      color: var(--text-body);
      font-family: 'Crimson Pro', Georgia, serif;
      font-size: 19px;
      line-height: 1.75;
    }

    .container { max-width: 760px; margin: 0 auto; padding: 0 24px; }

    .gold-rule {
      border: none; height: 1px;
      background: linear-gradient(90deg, transparent, var(--gold), transparent);
      margin: 0 auto; max-width: 380px;
    }

    .section-label {
      font-family: 'Cinzel', serif;
      font-size: 11px; letter-spacing: 0.22em; text-transform: uppercase;
      color: var(--gold); display: block; margin-bottom: 14px;
    }

    /* ═══ NOTICE BAR ═══ */
    .notice-bar { background: var(--violet-deep); padding: 11px 24px; text-align: center; }
    .notice-bar p {
      font-family: 'Cinzel', serif; font-size: 12px;
      letter-spacing: 0.12em; color: var(--gold-light); text-transform: uppercase;
    }

    /* ═══ HERO ═══ */
    .hero {
      background: linear-gradient(160deg, var(--violet-mid) 0%, var(--violet-deep) 60%, var(--violet-dark) 100%);
      padding: 64px 24px 0;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .hero::before {
      content: ''; position: absolute; inset: 0;
      background: radial-gradient(ellipse at 50% 0%, rgba(212,175,55,0.14) 0%, transparent 65%);
      pointer-events: none;
    }
    .hero__eyebrow {
      font-family: 'Cinzel', serif; font-size: 13px; letter-spacing: 0.14em;
      text-transform: uppercase; color: var(--gold-light); margin-bottom: 24px; display: block;
      position: relative; font-weight: 600;
    }
    .hero__headline {
      font-family: 'Cinzel', serif; font-weight: 700;
      font-size: clamp(28px, 5vw, 46px); line-height: 1.2; color: var(--cream);
      margin-bottom: 22px; max-width: 700px; margin-left: auto; margin-right: auto;
      position: relative;
    }
    .hero__headline em { color: var(--gold-light); font-style: italic; }
    .hero__subhead {
      font-family: 'Cormorant Garamond', serif; font-size: clamp(18px, 2.8vw, 23px);
      font-style: italic; color: rgba(254,252,248,0.85);
      max-width: 600px; margin: 0 auto 40px; line-height: 1.55;
      position: relative;
    }
    .hero__image {
      max-width: 760px; width: 100%; margin: 0 auto;
      display: block; border-radius: 10px 10px 0 0;
      box-shadow: 0 -10px 40px rgba(212,175,55,0.18);
      position: relative;
    }

    /* ═══ PART 1 / PART 2 FRAME (upgrade anchor) ═══ */
    .journey { background: var(--cream); padding: 56px 24px 40px; }
    .journey__inner { max-width: 660px; margin: 0 auto; text-align: center; }
    .journey__label {
      font-family: 'Cinzel', serif; font-size: 10px; letter-spacing: 0.3em;
      text-transform: uppercase; color: var(--gold-dim, #8B6914);
      margin-bottom: 16px; display: block;
    }
    .journey__body {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(20px, 2.6vw, 24px);
      font-style: italic;
      color: var(--violet-deep);
      line-height: 1.55;
    }
    .journey__body strong { font-style: normal; font-family: 'Cinzel', serif; font-weight: 600; font-size: 0.95em; letter-spacing: 0.04em; }

    .part-row {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      gap: 24px;
      margin-top: 36px;
      align-items: stretch;
    }
    .part-col {
      padding: 22px 18px;
      border: 1px solid rgba(154,114,48,0.25);
      border-radius: 8px;
      text-align: center;
      background: var(--cream-warm);
    }
    .part-col--done { border-color: rgba(154,114,48,0.45); background: rgba(212,175,55,0.08); }
    .part-col__num {
      font-family: 'Cinzel', serif; font-size: 10px; letter-spacing: 0.22em;
      text-transform: uppercase; color: var(--gold);
      margin-bottom: 8px; display: block;
    }
    .part-col__title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 18px; font-weight: 600;
      color: var(--violet-deep); margin-bottom: 6px;
      line-height: 1.3;
    }
    .part-col__status {
      font-family: 'Crimson Pro', serif; font-size: 14px;
      font-style: italic; color: var(--text-muted);
    }
    .part-col__status--done { color: #15803D; font-weight: 500; }
    .part-arrow {
      display: flex; align-items: center; justify-content: center;
      color: var(--gold); font-size: 22px; font-family: 'Cinzel', serif;
    }

    /* ═══ BRIDGE / LUNA MESSAGE ═══ */
    .bridge { background: var(--cream); padding: 40px 24px 64px; }
    .bridge__body { font-size: 20px; line-height: 1.8; color: var(--text-body); max-width: 640px; margin: 0 auto; }
    .bridge__body p + p { margin-top: 20px; }
    .bridge__body strong { color: var(--violet-deep); }
    .bridge__sig {
      margin-top: 28px;
      padding-top: 24px;
      border-top: 1px solid rgba(212,175,55,0.25);
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: 18px;
      color: var(--text-muted);
      text-align: center;
    }
    .bridge__sig strong { color: var(--violet-deep); font-style: normal; }

    /* ═══ AGITATION ═══ */
    .agitation { background: var(--violet-deep); padding: 72px 24px; }
    .agitation__headline {
      font-family: 'Cinzel', serif; font-size: clamp(22px, 4vw, 34px); font-weight: 600;
      color: var(--cream); text-align: center;
      margin-bottom: 36px; line-height: 1.3;
    }
    .agitation__headline em { color: var(--gold-light); font-style: italic; }
    .agitation__body {
      font-size: 19px; line-height: 1.8; color: rgba(254,252,248,0.88);
      max-width: 620px; margin: 0 auto;
    }
    .agitation__body p + p { margin-top: 22px; }
    .agitation__body strong { color: var(--gold-light); }
    .agitation__callout {
      border-left: 3px solid var(--gold);
      padding: 20px 24px;
      margin: 32px 0;
      background: rgba(212,175,55,0.08);
    }
    .agitation__callout p {
      font-family: 'Cormorant Garamond', serif;
      font-size: 22px; font-style: italic;
      color: var(--gold-light); line-height: 1.55;
    }

    /* ═══ PAIN POINTS ═══ */
    .pain { background: var(--violet-dark); padding: 72px 24px; }
    .pain__headline {
      font-family: 'Cinzel', serif; font-size: clamp(22px, 3.8vw, 32px);
      font-weight: 600; color: var(--cream);
      margin-bottom: 40px; line-height: 1.3;
    }
    .pain__headline em { color: var(--gold-light); font-style: italic; }
    .pain__list { display: flex; flex-direction: column; gap: 18px; }
    .pain__item { padding: 18px 0; border-bottom: 1px solid rgba(212,175,55,0.15); }
    .pain__item p {
      font-family: 'Crimson Pro', serif; font-size: 19px;
      color: rgba(254,252,248,0.9); line-height: 1.75;
    }
    .pain__item p em { font-style: italic; color: var(--gold-light); }
    .pain__callout {
      padding: 22px 26px; margin-top: 12px;
      border-left: 3px solid var(--gold);
      background: rgba(212,175,55,0.08);
    }
    .pain__callout p {
      font-family: 'Cormorant Garamond', serif; font-size: 22px;
      font-style: italic; color: var(--gold-light); line-height: 1.55;
    }

    /* ═══ MECHANISM / 3 REPORTS ═══ */
    .mechanism { background: var(--cream-warm); padding: 76px 24px; }
    .mechanism__eyebrow {
      font-family: 'Cinzel', serif; font-size: 11px; letter-spacing: 0.22em;
      text-transform: uppercase; color: var(--gold);
      text-align: center; margin-bottom: 14px; display: block;
    }
    .mechanism__headline {
      font-family: 'Cinzel', serif; font-size: clamp(22px, 3.8vw, 32px);
      font-weight: 600; color: var(--violet-deep);
      text-align: center; margin-bottom: 14px; line-height: 1.3;
    }
    .mechanism__headline em { color: var(--gold); font-style: italic; font-family: 'Cormorant Garamond', serif; }
    .mechanism__subhead {
      font-family: 'Cormorant Garamond', serif; font-size: 20px;
      font-style: italic; color: var(--text-muted);
      text-align: center; margin-bottom: 32px;
      max-width: 560px; margin-left: auto; margin-right: auto;
    }
    .mechanism__body {
      font-size: 19px; line-height: 1.8; color: var(--text-body);
      max-width: 620px; margin: 0 auto 44px;
    }
    .mechanism__body p + p { margin-top: 20px; }
    .mechanism__body strong { color: var(--violet-deep); }

    .three-part { display: flex; flex-direction: column; gap: 20px; }
    .report-card {
      display: flex; gap: 22px; align-items: flex-start;
      background: var(--white); border: 1px solid rgba(212,175,55,0.25);
      border-radius: 8px; padding: 28px;
      box-shadow: 0 4px 16px rgba(45,27,105,0.05);
    }
    .report-card__num {
      font-family: 'Cinzel', serif; font-size: 15px; letter-spacing: 0.08em;
      text-transform: uppercase; color: var(--gold);
      font-weight: 600;
      min-width: 104px; padding-top: 3px; flex-shrink: 0;
    }
    .report-card__body { flex: 1; }
    .report-card__body h3 {
      font-family: 'Cormorant Garamond', serif; font-size: 22px;
      font-weight: 600; color: var(--violet-deep); margin-bottom: 8px;
    }
    .report-card__body p {
      font-size: 17px; line-height: 1.7; color: var(--text-body);
    }

    /* ═══ LUNA CREDENTIAL ═══ */
    .luna-section { background: var(--violet-deep); padding: 76px 24px; }
    .luna-section__headline {
      font-family: 'Cinzel', serif; font-size: clamp(22px, 3.8vw, 30px);
      font-weight: 600; color: var(--cream);
      text-align: center; margin-bottom: 40px; line-height: 1.3;
    }
    .luna-section__headline em { color: var(--gold-light); font-style: italic; }
    .luna-card {
      background: rgba(255,255,255,0.06); border: 1px solid rgba(212,175,55,0.3);
      border-radius: 10px; padding: 40px 40px 36px;
      max-width: 680px; margin: 0 auto;
    }
    .luna-card__body {
      font-size: 19px; line-height: 1.8; color: rgba(254,252,248,0.9);
    }
    .luna-card__body p + p { margin-top: 20px; }
    .luna-card__body strong { color: var(--gold-light); }
    .luna-card__body em { font-style: italic; color: var(--gold-pale); }
    .luna-card__sig {
      margin-top: 28px;
      padding-top: 24px;
      border-top: 1px solid rgba(212,175,55,0.2);
    }
    .luna-card__sig p {
      font-family: 'Cormorant Garamond', serif; font-size: 20px;
      font-style: italic; color: var(--gold-light);
    }

    /* ═══ PRACTICE VISUAL ═══ */
    .practice-visual {
      background: var(--cream);
      padding: 40px 24px;
      text-align: center;
    }
    .practice-visual img {
      max-width: 640px;
      height: auto;
      display: block;
      margin: 0 auto;
      width: 100%;
      border-radius: 10px;
      box-shadow: 0 12px 40px rgba(45,27,105,0.18);
    }
    @media (max-width: 720px) {
      .practice-visual { padding: 28px 16px; }
    }

    /* ═══ INCLUDES ═══ */
    .includes { background: var(--cream); padding: 76px 24px; }
    .includes__eyebrow {
      font-family: 'Cinzel', serif; font-size: 13px; letter-spacing: 0.12em;
      text-transform: uppercase; color: var(--gold-dim);
      font-weight: 600;
      text-align: center; margin-bottom: 14px; display: block;
    }
    .includes__headline {
      font-family: 'Cinzel', serif; font-size: clamp(22px, 3.8vw, 32px);
      font-weight: 600; color: var(--violet-deep);
      text-align: center; margin-bottom: 12px; line-height: 1.3;
    }
    .includes__headline em { color: var(--gold); font-style: italic; font-family: 'Cormorant Garamond', serif; }
    .includes__subhead {
      font-family: 'Cormorant Garamond', serif; font-size: 20px;
      font-style: italic; color: var(--text-muted);
      text-align: center; margin-bottom: 44px;
    }
    .includes-grid { display: flex; flex-direction: column; gap: 18px; }
    .include-item {
      display: flex; gap: 20px; align-items: flex-start;
      padding: 26px 28px;
      background: var(--cream-warm);
      border-radius: 8px;
      border: 1px solid rgba(212,175,55,0.2);
    }
    .include-item__icon {
      font-size: 28px; flex-shrink: 0; margin-top: 2px;
      color: var(--gold);
    }
    .include-item__content { flex: 1; }
    .include-item__content h3 {
      font-family: 'Cormorant Garamond', serif; font-size: 20px;
      font-weight: 600; color: var(--violet-deep);
      margin-bottom: 8px; line-height: 1.35;
    }
    .include-item__content p {
      font-size: 17px; line-height: 1.65; color: var(--text-body);
    }
    .include-item__value {
      font-family: 'Cinzel', serif; font-size: 14px; letter-spacing: 0.06em;
      color: var(--gold-dim); margin-top: 12px; display: block;
      font-weight: 600;
    }
    /* Grouped headline that sits above each hero image */
    .include-item__headline {
      font-family: 'Cinzel', serif;
      font-size: clamp(20px, 3.2vw, 26px);
      font-weight: 600;
      color: var(--violet-deep);
      text-align: center;
      line-height: 1.3;
      margin: 28px auto 14px;
      max-width: 620px;
      padding: 0 16px;
    }
    .include-item__headline em {
      color: var(--gold); font-style: italic;
      font-family: 'Cormorant Garamond', serif;
    }

    /* Transparent hero image sitting above each include-item card */
    .include-item__hero {
      text-align: center;
      padding: 0 20px;
      margin: 0 0 -20px;
    }
    .include-item__hero img {
      max-width: 460px;
      width: 100%;
      height: auto;
      display: block;
      margin: 0 auto;
      background: transparent;
    }
    .include-item__hero--portrait img {
      max-width: 260px;
    }
    @media (max-width: 720px) {
      .include-item__hero { margin-bottom: -10px; }
      .include-item__hero img { max-width: 340px; }
      .include-item__hero--portrait img { max-width: 200px; }
    }

    /* Legacy includes-hero (kept for safety) */
    .includes-hero {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 32px;
      max-width: 860px;
      margin: 0 auto 52px;
      align-items: end;
    }
    .includes-hero__item { text-align: center; }
    .includes-hero__item img {
      width: 100%;
      height: auto;
      display: block;
      border-radius: 8px;
      box-shadow: 0 18px 50px rgba(45,27,105,0.28);
      margin-bottom: 14px;
    }
    .includes-hero__caption {
      font-family: 'Cormorant Garamond', serif;
      font-size: 16px;
      font-style: italic;
      color: var(--text-muted);
      letter-spacing: 0.02em;
    }
    @media (max-width: 720px) {
      .includes-hero {
        grid-template-columns: 1fr;
        gap: 36px;
        max-width: 380px;
        margin-bottom: 40px;
      }
    }
    .include-item__parts {
      margin: 18px 0 12px;
      padding: 16px 20px;
      background: rgba(45,27,105,0.04);
      border-radius: 6px;
      border-left: 3px solid var(--gold);
    }
    .part-item { margin-bottom: 14px; }
    .part-item:last-child { margin-bottom: 0; }
    .part-item strong {
      display: block;
      font-family: 'Cormorant Garamond', serif;
      font-size: 18px; font-weight: 600;
      color: var(--violet-deep);
      margin-bottom: 5px;
    }
    .part-item p {
      font-size: 16px; line-height: 1.6;
      color: var(--text-body);
      margin: 0;
    }

    /* ═══ BONUSES ═══ */
    .bonuses {
      background: linear-gradient(180deg, #2D1B69 0%, #1e0d40 100%);
      padding: 76px 24px;
      position: relative;
      overflow: hidden;
    }
    .bonuses::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse at 50% 0%, rgba(212,175,55,0.12) 0%, transparent 50%);
      pointer-events: none;
    }
    .bonuses__eyebrow {
      font-family: 'Cinzel', serif; font-size: 13px; letter-spacing: 0.14em;
      text-transform: uppercase; color: var(--gold-light);
      font-weight: 600;
      text-align: center; margin-bottom: 14px; display: block;
      position: relative;
    }
    .bonuses__headline {
      font-family: 'Cinzel', serif; font-size: clamp(22px, 3.8vw, 32px);
      font-weight: 600; color: var(--cream);
      text-align: center; margin-bottom: 12px; line-height: 1.3;
      position: relative;
    }
    .bonuses__headline em {
      color: var(--gold-light); font-style: italic;
      font-family: 'Cormorant Garamond', serif;
    }
    .bonuses__subhead {
      font-family: 'Cormorant Garamond', serif; font-size: 19px;
      font-style: italic; color: rgba(254,252,248,0.8);
      text-align: center; margin: 0 auto 44px;
      max-width: 620px; position: relative;
    }
    .bonuses-grid { display: flex; flex-direction: column; gap: 18px; position: relative; }
    .bonus-item {
      display: flex; gap: 20px; align-items: flex-start;
      padding: 26px 28px;
      background: rgba(255,255,255,0.04);
      border-radius: 8px;
      border: 1px solid rgba(212,175,55,0.35);
      position: relative;
    }
    .bonus-item__badge {
      position: absolute;
      top: -13px; left: 24px;
      background: linear-gradient(135deg, #D4AF37 0%, #b8941f 100%);
      color: #1a0d40;
      font-family: 'Cinzel', serif;
      font-size: 12px; letter-spacing: 0.1em;
      text-transform: uppercase; font-weight: 700;
      padding: 6px 16px; border-radius: 14px;
      box-shadow: 0 3px 10px rgba(212,175,55,0.3);
    }
    .bonus-item__icon {
      font-size: 28px; flex-shrink: 0; margin-top: 6px;
      color: var(--gold-light);
    }
    .bonus-item__content { flex: 1; }
    .bonus-item__content h3 {
      font-family: 'Cormorant Garamond', serif; font-size: 20px;
      font-weight: 600; color: var(--gold-light);
      margin: 4px 0 8px; line-height: 1.35;
    }
    .bonus-item__content p {
      font-size: 17px; line-height: 1.65;
      color: rgba(254,252,248,0.85);
    }
    .bonus-item__value {
      font-family: 'Cinzel', serif; font-size: 14px; letter-spacing: 0.06em;
      color: var(--gold-light); margin-top: 12px; display: block;
      font-weight: 600;
    }
    .bonus-item__image {
      flex-shrink: 0;
      width: 160px;
      align-self: center;
    }
    .bonus-item__image img {
      width: 100%;
      height: auto;
      display: block;
      border-radius: 6px;
      box-shadow: 0 8px 24px rgba(212,175,55,0.2);
    }
    @media (max-width: 720px) {
      .bonus-item__image { width: 130px; }
    }

    /* ═══ TESTIMONIALS ═══ */
    .testimonials { background: var(--cream-warm); padding: 76px 24px; }
    .testimonials__eyebrow {
      font-family: 'Cinzel', serif; font-size: 13px; letter-spacing: 0.12em;
      text-transform: uppercase; color: var(--gold-dim);
      font-weight: 600;
      text-align: center; margin-bottom: 12px; display: block;
    }
    .testimonials__headline {
      font-family: 'Cinzel', serif; font-size: clamp(20px, 3.5vw, 28px);
      font-weight: 600; color: var(--violet-deep);
      text-align: center; margin-bottom: 44px; line-height: 1.35;
    }
    .testimonials__headline em { color: var(--gold); font-style: italic; font-family: 'Cormorant Garamond', serif; }
    .testimonial-grid { display: flex; flex-direction: column; gap: 22px; }
    .testimonial {
      display: flex; gap: 22px; align-items: flex-start;
      background: var(--white); border-radius: 10px;
      border: 1px solid rgba(212,175,55,0.22);
      padding: 28px;
      box-shadow: 0 4px 16px rgba(45,27,105,0.05);
    }
    .testimonial__avatar {
      width: 78px; height: 78px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(212,175,55,0.4);
      flex-shrink: 0;
      box-shadow: 0 4px 12px rgba(45,27,105,0.12);
    }
    .testimonial__body { flex: 1; }
    .testimonial__stars {
      color: var(--gold); font-size: 15px; letter-spacing: 3px;
      margin-bottom: 10px; display: block;
    }
    .testimonial__quote {
      font-family: 'Cormorant Garamond', serif; font-size: 19px;
      font-style: italic; line-height: 1.6; color: var(--text-body);
      margin-bottom: 14px;
    }
    .testimonial__name {
      font-family: 'Cinzel', serif; font-size: 13px; letter-spacing: 0.08em;
      font-weight: 600; text-transform: uppercase; color: var(--text-mid);
    }

    /* ═══ URGENCY ═══ */
    .urgency { background: var(--violet-dark); padding: 52px 24px; text-align: center; }
    .urgency__headline {
      font-family: 'Cinzel', serif; font-size: clamp(17px, 3vw, 23px);
      font-weight: 600; color: var(--gold-light);
      margin-bottom: 14px; line-height: 1.4;
      max-width: 640px; margin-left: auto; margin-right: auto;
    }
    .urgency__body {
      font-size: 17px; color: rgba(254,252,248,0.78);
      max-width: 560px; margin: 0 auto; line-height: 1.7;
    }

    /* ═══ OFFER BOX ═══ */
    .offer {
      background: linear-gradient(160deg, var(--violet-mid) 0%, var(--violet-deep) 100%);
      padding: 76px 24px; text-align: center;
    }
    .offer__eyebrow {
      font-family: 'Cinzel', serif; font-size: 13px; letter-spacing: 0.14em;
      text-transform: uppercase; color: var(--gold-light);
      font-weight: 600;
      margin-bottom: 16px; display: block;
    }
    .offer__headline {
      font-family: 'Cinzel', serif; font-size: clamp(24px, 4vw, 36px); font-weight: 700;
      color: var(--cream); margin-bottom: 14px; line-height: 1.25;
    }
    .offer__package-image {
      text-align: center;
      margin: 20px auto 32px;
      padding: 0 20px;
    }
    .offer__package-image img {
      max-width: 600px;
      width: 100%;
      height: auto;
      display: block;
      margin: 0 auto;
      background: transparent;
      filter: drop-shadow(0 20px 40px rgba(0,0,0,0.3));
    }
    @media (max-width: 720px) {
      .offer__package-image img { max-width: 100%; }
    }

    .offer__subhead {
      font-family: 'Cormorant Garamond', serif; font-size: 21px; font-style: italic;
      color: rgba(254,252,248,0.78); margin-bottom: 44px;
    }
    .value-stack {
      background: rgba(255,255,255,0.06); border: 1px solid rgba(212,175,55,0.3);
      border-radius: 10px; padding: 32px 30px; margin-bottom: 32px; text-align: left;
    }
    .value-stack__title {
      font-family: 'Cinzel', serif; font-size: 14px; letter-spacing: 0.1em;
      text-transform: uppercase; color: var(--gold-light);
      font-weight: 600;
      margin-bottom: 20px; text-align: center;
    }
    .value-line {
      display: flex; justify-content: space-between; align-items: baseline;
      padding: 10px 0; border-bottom: 1px solid rgba(212,175,55,0.12); gap: 16px;
    }
    .value-line:last-child { border-bottom: none; }
    .value-line__name { font-size: 16px; color: rgba(254,252,248,0.88); line-height: 1.4; }
    .value-line__price {
      font-family: 'Cinzel', serif; font-size: 13px; color: var(--gold-light);
      flex-shrink: 0; text-decoration: line-through; opacity: 0.7;
    }
    .value-line--total {
      margin-top: 8px; padding-top: 14px;
      border-top: 1px solid rgba(212,175,55,0.35); border-bottom: none;
    }
    .value-line--total .value-line__name {
      font-family: 'Cormorant Garamond', serif; font-size: 19px;
      font-weight: 600; color: var(--cream);
    }
    .value-line--total .value-line__price {
      font-size: 15px; text-decoration: none; opacity: 1; color: var(--gold-light);
    }

    /* ═══ FOUNDING COUPON BADGE ═══ */
    .coupon {
      display: inline-block;
      position: relative;
      background: linear-gradient(135deg, rgba(212,175,55,0.15) 0%, rgba(212,175,55,0.08) 100%);
      border: 1.5px dashed rgba(232,201,122,0.6);
      border-radius: 8px;
      padding: 14px 28px;
      margin: 0 auto 28px;
      max-width: 440px;
    }
    .coupon::before, .coupon::after {
      content: '';
      position: absolute;
      top: 50%;
      width: 16px; height: 16px;
      border-radius: 50%;
      background: var(--violet-deep);
      transform: translateY(-50%);
    }
    .coupon::before { left: -10px; }
    .coupon::after { right: -10px; }
    .coupon__label {
      font-family: 'Cinzel', serif; font-size: 9px;
      letter-spacing: 0.3em; text-transform: uppercase;
      color: var(--gold); display: block; margin-bottom: 6px;
    }
    .coupon__amount {
      font-family: 'Cinzel', serif; font-size: 22px;
      font-weight: 700; color: var(--gold-light);
      display: block; letter-spacing: 0.04em;
    }
    .coupon__code {
      font-family: 'Cinzel', serif; font-size: 11px;
      letter-spacing: 0.16em; color: rgba(254,252,248,0.62);
      display: block; margin-top: 6px;
    }
    .coupon__code span {
      color: var(--gold-light);
      background: rgba(212,175,55,0.12);
      border: 1px solid rgba(232,201,122,0.3);
      padding: 2px 8px;
      border-radius: 3px;
      margin-left: 6px;
      letter-spacing: 0.14em;
    }

    .price-reveal { text-align: center; margin: 28px 0 32px; }
    .price-reveal__label {
      font-family: 'Cinzel', serif; font-size: 13px; letter-spacing: 0.12em;
      text-transform: uppercase; color: var(--gold-light);
      font-weight: 600;
      display: block; margin-bottom: 10px;
    }
    .price-reveal__was {
      font-family: 'Cormorant Garamond', serif; font-size: 24px;
      color: rgba(254,252,248,0.4); text-decoration: line-through;
      display: block; margin-bottom: 4px;
    }
    .price-reveal__now {
      font-family: 'Cinzel', serif; font-size: clamp(52px, 10vw, 76px);
      font-weight: 700; color: var(--gold-light); line-height: 1; display: block;
    }
    .price-reveal__note {
      font-size: 15px; color: rgba(254,252,248,0.6);
      margin-top: 10px; display: block;
    }

    .cta-btn {
      display: inline-block;
      background: linear-gradient(135deg, var(--gold) 0%, #b8941f 100%);
      color: var(--violet-deep); font-family: 'Cinzel', serif;
      font-size: clamp(13px, 2.5vw, 17px); font-weight: 700; letter-spacing: 0.1em;
      text-transform: uppercase; text-decoration: none; padding: 22px 44px;
      border-radius: 6px; border: none; cursor: pointer;
      width: 100%; max-width: 520px; text-align: center;
      box-shadow: 0 6px 24px rgba(212,175,55,0.35);
      transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .cta-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(212,175,55,0.45); }
    .cta-btn--secondary {
      background: transparent; color: rgba(254,252,248,0.5);
      font-family: 'Crimson Pro', serif; font-size: 15px;
      text-transform: none; font-weight: 300;
      display: block; margin-top: 16px; text-align: center;
      cursor: pointer; text-decoration: underline; text-underline-offset: 3px;
      border: none; padding: 8px; width: 100%;
    }
    .cta-btn--secondary:hover { color: rgba(254,252,248,0.75); }
    .cta-subtext { margin-top: 20px; font-size: 14px; color: rgba(254,252,248,0.55); line-height: 1.6; }

    /* ═══ GUARANTEE ═══ */
    .guarantee { background: var(--cream); padding: 68px 24px; }
    .guarantee__inner {
      background: var(--cream-warm); border: 2px solid rgba(212,175,55,0.3);
      border-radius: 12px; padding: 44px 38px; text-align: center;
      max-width: 620px; margin: 0 auto;
    }
    .guarantee__badge {
      display: block;
      margin: 0 auto 18px;
      width: 140px;
      height: auto;
      filter: drop-shadow(0 10px 20px rgba(212,175,55,0.25));
    }
    .guarantee__badge-wrap { position: relative; display: inline-block; }
    .guarantee__badge-text {
      position: absolute;
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      font-family: 'Cinzel', serif;
      font-weight: 700;
      color: #E8C97A;
      text-align: center;
      line-height: 1.1;
      letter-spacing: 0.08em;
      text-shadow: 0 2px 6px rgba(0,0,0,0.6);
      pointer-events: none;
    }
    .guarantee__badge-text .badge-num {
      display: block; font-size: 26px; line-height: 1;
    }
    .guarantee__badge-text .badge-label {
      display: block; font-size: 10px; letter-spacing: 0.16em;
      margin-top: 2px; text-transform: uppercase;
    }
    @media (max-width: 768px) {
      .guarantee__badge { width: 120px; }
      .guarantee__badge-text .badge-num { font-size: 22px; }
      .guarantee__badge-text .badge-label { font-size: 9px; }
    }
    .guarantee__headline {
      font-family: 'Cinzel', serif; font-size: clamp(18px, 2.8vw, 24px);
      font-weight: 600; color: var(--violet-deep); margin-bottom: 16px;
    }
    .guarantee__body {
      font-size: 18px; line-height: 1.75; color: var(--text-body);
      max-width: 540px; margin: 0 auto;
    }

    /* ═══ FAQ ═══ */
    .faq { background: var(--cream-warm); padding: 76px 24px; }
    .faq__headline {
      font-family: 'Cinzel', serif; font-size: clamp(22px, 3.5vw, 28px);
      font-weight: 600; color: var(--violet-deep);
      text-align: center; margin-bottom: 44px;
    }
    .faq-list { display: flex; flex-direction: column; gap: 18px; }
    .faq-item {
      background: var(--white); border-radius: 8px;
      border: 1px solid rgba(212,175,55,0.18); padding: 26px 28px;
    }
    .faq-item__q {
      font-family: 'Cormorant Garamond', serif; font-size: 20px;
      font-weight: 600; color: var(--violet-deep);
      margin-bottom: 10px; line-height: 1.4;
    }
    .faq-item__a { font-size: 17px; line-height: 1.7; color: var(--text-body); }

    /* ═══ FINAL CTA ═══ */
    .final-cta {
      background: linear-gradient(160deg, var(--violet-mid) 0%, var(--violet-deep) 100%);
      padding: 76px 24px; text-align: center;
    }
    .final-cta__headline {
      font-family: 'Cinzel', serif; font-size: clamp(24px, 4vw, 34px);
      font-weight: 700; color: var(--cream);
      margin-bottom: 18px; line-height: 1.3;
    }
    .final-cta__headline em { color: var(--gold-light); font-style: italic; }
    .final-cta__body {
      font-size: 19px; color: rgba(254,252,248,0.82);
      max-width: 580px; margin: 0 auto 36px; line-height: 1.7;
    }

    /* ═══ FOOTER ═══ */
    .footer { background: var(--violet-mid); padding: 28px 24px; text-align: center; }
    .footer p { font-size: 12px; color: rgba(254,252,248,0.38); line-height: 1.6; }
    .footer a { color: rgba(254,252,248,0.45); text-decoration: underline; text-underline-offset: 2px; }

    @media (max-width: 600px) {
      .part-row { grid-template-columns: 1fr; }
      .part-arrow { transform: rotate(90deg); padding: 8px 0; }
      .report-card { flex-direction: column; gap: 12px; padding: 24px; }
      .include-item { flex-direction: column; gap: 12px; padding: 24px 20px; }
      .testimonial { flex-direction: column; align-items: center; text-align: center; gap: 16px; padding: 24px 20px; }
      .value-stack { padding: 24px 18px; }
      .guarantee__inner { padding: 32px 22px; }
      .luna-card { padding: 32px 24px; }
    }
  
    /* MOBILE OPTIMIZATION - Tablet, phone, small phone */
    @media (max-width: 768px) {
      .container { padding: 0 18px; }
      .notice-bar p { font-size: 10px; letter-spacing: 0.08em; }

      .hero { padding: 44px 18px 28px; }
      .hero__headline { font-size: clamp(22px, 5.5vw, 32px); line-height: 1.22; }
      .hero__subhead { font-size: 17px; line-height: 1.5; }
      .hero__image { border-radius: 6px; }

      .journey { padding: 44px 18px; }
      .part-row { grid-template-columns: 1fr !important; gap: 14px; }
      .part-col { padding: 18px; }
      .part-col__title { font-size: 17px; }
      .part-arrow { transform: rotate(90deg) !important; padding: 6px 0; }

      .bridge { padding: 44px 18px; }
      .bridge__body p { font-size: 17px; line-height: 1.65; }
      .luna-section { padding: 52px 18px; }
      .luna-card { padding: 26px 22px; }
      .luna-card__body p { font-size: 17px; line-height: 1.65; }

      .pain { padding: 52px 18px; }
      .pain__headline { font-size: clamp(22px, 5vw, 28px); line-height: 1.3; margin-bottom: 28px; }
      .pain__item p { font-size: 17px; line-height: 1.6; }
      .pain__callout p { font-size: 18px; }

      .mechanism { padding: 52px 18px; }
      .mechanism__headline { font-size: clamp(22px, 5vw, 28px); }
      .mechanism__subhead { font-size: 17px; }
      .report-card { flex-direction: column; padding: 22px 20px; gap: 10px; }
      .report-card__num { min-width: auto; font-size: 13px; padding-top: 0; }
      .report-card__body h3 { font-size: 19px; line-height: 1.3; }
      .report-card__body p { font-size: 16px; line-height: 1.6; }

      .includes { padding: 52px 18px; }
      .includes__headline { font-size: clamp(22px, 5vw, 28px); }
      .includes__subhead { font-size: 17px; }
      .include-item { padding: 22px 20px; }
      .include-item__content h3 { font-size: 19px; }
      .include-item__content p { font-size: 16px; }
      .include-item__headline { font-size: clamp(18px, 4.5vw, 22px); margin: 20px auto 10px; }
      .part-item strong { font-size: 17px; }
      .part-item p { font-size: 15px; }

      .bonuses { padding: 52px 18px; }
      .bonuses__headline { font-size: clamp(22px, 5vw, 28px); }
      .bonuses__subhead { font-size: 17px; }
      .bonus-item { flex-direction: column; padding: 24px 20px; gap: 12px; align-items: flex-start; }
      .bonus-item__badge { top: -12px; left: 18px; font-size: 11px; padding: 5px 14px; }
      .bonus-item__content h3 { font-size: 19px; line-height: 1.3; }
      .bonus-item__content p { font-size: 16px; line-height: 1.6; }

      .testimonials { padding: 52px 18px; }
      .testimonials__headline { font-size: clamp(20px, 4.8vw, 26px); }
      .testimonial { flex-direction: column; padding: 22px 20px; gap: 14px; text-align: center; align-items: center; }
      .testimonial__avatar { width: 84px; height: 84px; }
      .testimonial__quote { font-size: 17px; line-height: 1.6; }

      .urgency { padding: 36px 18px; }
      .urgency__headline { font-size: 18px; line-height: 1.4; }
      .urgency__body { font-size: 16px; line-height: 1.65; }

      .offer { padding: 52px 18px; }
      .offer__eyebrow { font-size: 12px; letter-spacing: 0.1em; }
      .offer__headline { font-size: clamp(22px, 5.2vw, 28px); }
      .offer__subhead { font-size: 17px; }
      .offer__package-image { margin: 16px auto 24px; padding: 0 12px; }
      .value-stack { padding: 22px 18px; }
      .value-stack__title { font-size: 12px; letter-spacing: 0.08em; }
      .value-line__name { font-size: 14px; line-height: 1.35; }
      .value-line__price { font-size: 13px; }
      .value-line--total .value-line__name { font-size: 17px; }
      .value-line--total .value-line__price { font-size: 14px; }
      .coupon { max-width: 320px; padding: 12px 22px; }
      .coupon__amount { font-size: 19px; }
      .coupon__code { font-size: 10px; }
      .price-reveal__now { font-size: clamp(44px, 12vw, 60px); }
      .price-reveal__was { font-size: 20px; }
      .cta-btn { padding: 20px 22px; font-size: clamp(13px, 3.5vw, 15px); letter-spacing: 0.08em; min-height: 54px; }
      .cta-btn--secondary { font-size: 13px; margin-top: 14px; padding: 10px 6px; }
      .cta-subtext { font-size: 13px; }

      .guarantee { padding: 52px 18px; }
      .guarantee__inner { padding: 32px 22px; }
      .guarantee__headline { font-size: 20px; }
      .guarantee__body { font-size: 16px; line-height: 1.65; }

      .faq { padding: 52px 18px; }
      .faq__headline { font-size: clamp(20px, 4.8vw, 24px); }
      .faq-item { padding: 22px 20px; }
      .faq-item__q { font-size: 18px; line-height: 1.4; }
      .faq-item__a { font-size: 16px; line-height: 1.65; }

      .final-cta { padding: 52px 18px; }
      .final-cta__headline { font-size: clamp(22px, 5vw, 28px); }
      .final-cta__body { font-size: 17px; line-height: 1.65; }

      .footer { padding: 22px 18px; }
      .footer p { font-size: 12px; }
    }

    @media (max-width: 420px) {
      .container { padding: 0 14px; }
      .hero__headline { font-size: 22px; }
      .pain__headline, .mechanism__headline, .includes__headline, .bonuses__headline, .offer__headline, .final-cta__headline, .testimonials__headline { font-size: 20px; }
      .price-reveal__now { font-size: 40px; }
      .coupon { max-width: 100%; padding: 12px 16px; }
      .cta-btn { padding: 18px 12px; font-size: 12px; letter-spacing: 0.06em; }
    }

  
    /* ═══ FAQ Collapsible (details/summary) ═══ */
    .faq-item {
      background: var(--cream-warm);
      border: 1px solid rgba(212,175,55,0.25);
      border-radius: 10px;
      padding: 0;
      margin-bottom: 14px;
      overflow: hidden;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .faq-item[open] {
      border-color: rgba(212,175,55,0.5);
      box-shadow: 0 6px 20px rgba(45,27,105,0.08);
    }
    .faq-item__q {
      list-style: none;
      cursor: pointer;
      padding: 22px 56px 22px 26px;
      font-family: 'Cormorant Garamond', serif;
      font-size: 20px;
      font-weight: 600;
      color: var(--violet-deep);
      position: relative;
      transition: background 0.2s ease;
      user-select: none;
      outline: none;
    }
    .faq-item__q::-webkit-details-marker, .faq-item__q::marker {
      display: none; content: '';
    }
    .faq-item__q:hover { background: rgba(212,175,55,0.06); }
    .faq-item__q::after {
      content: '+';
      position: absolute;
      right: 22px;
      top: 50%;
      transform: translateY(-50%);
      font-family: 'Cinzel', serif;
      font-size: 28px;
      font-weight: 400;
      color: var(--gold-dim);
      line-height: 1;
      transition: transform 0.3s ease, color 0.2s ease;
    }
    .faq-item[open] .faq-item__q::after {
      content: '−';
      color: var(--gold);
    }
    .faq-item__a {
      padding: 0 26px 22px 26px;
      font-size: 17px;
      line-height: 1.7;
      color: var(--text-body);
      margin: 0;
      animation: faqFadeIn 0.3s ease;
    }
    @keyframes faqFadeIn {
      from { opacity: 0; transform: translateY(-6px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @media (max-width: 768px) {
      .faq-item__q { padding: 20px 48px 20px 22px; font-size: 18px; line-height: 1.35; }
      .faq-item__q::after { right: 18px; font-size: 24px; }
      .faq-item__a { padding: 0 22px 20px 22px; font-size: 16px; line-height: 1.65; }
    }

  </style>

  <!-- ─────────────────────────────────────────
    Microsoft Clarity
  ───────────────────────────────────────── -->
  <script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "wq82rtc2gf");
  </script>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
<style id="match-salespage-fonts">
body{font-family:'Inter',system-ui,-apple-system,'Segoe UI',sans-serif !important;font-size:18px !important;line-height:1.72 !important;}
.part-col__status,.pain__item p,.cta-btn--secondary{font-family:'Inter',system-ui,sans-serif !important;}
.hero__headline,.agitation__headline,.pain__headline,.mechanism__headline,.luna-section__headline,
.includes__headline,.bonuses__headline,.testimonials__headline,.urgency__headline,.offer__headline,
.guarantee__headline,.faq__headline,.final-cta__headline,.include-item__headline,
.journey__body strong{font-family:'Cormorant Garamond',Georgia,serif !important;font-weight:600 !important;}
.price-reveal__now{font-family:'Cormorant Garamond',Georgia,serif !important;font-weight:600 !important;}
.section-label,.notice-bar p,.hero__eyebrow,.journey__label,.part-col__num,.part-arrow,
.mechanism__eyebrow,.report-card__num,.includes__eyebrow,.include-item__value,.bonuses__eyebrow,
.bonus-item__badge,.bonus-item__value,.testimonials__eyebrow,.testimonial__name,.offer__eyebrow,
.value-stack__title,.value-line__price,.coupon__label,.coupon__amount,.coupon__code,
.price-reveal__label,.cta-btn,.guarantee__badge-text{font-family:'Inter',system-ui,-apple-system,sans-serif !important;}
@keyframes foundingBlink{0%,100%{opacity:1}50%{opacity:.3}}
.founding-blink{animation:foundingBlink 1.05s ease-in-out infinite;color:#E8C97A !important;font-weight:600;}
</style>
</head>
<body>

  <!-- ═══ NOTICE BAR ═══ -->
  <div class="notice-bar" style="background:#9b1c1c;border-bottom:1px solid #c0392b;text-align:center;text-transform:uppercase;letter-spacing:0.13em;font-weight:700;padding:14px 18px;font-size:13px;line-height:1.5;font-family:'Inter',system-ui,sans-serif;color:#f0dca8;">
    <strong>Hold On, This Is Just for You</strong><span style="color:#f3b0a0;margin:0 9px;">&middot;</span><strong>A Members-Only Upgrade Just Unlocked, $100 Off</strong><span style="color:#f3b0a0;margin:0 9px;">&middot;</span><strong>Before Your Reading Lands</strong>
  </div>

  <!-- ═══ HERO ═══ -->
  <section class="hero">
    <div class="container">
      <h1 class="hero__headline">
        The Reading Named the Pattern Capping Your Money.<br />
        <em>This Is How You Clear It.</em>
      </h1>
      <p class="hero__subhead">
        The Soul Ritual Practice is the fast track to clearing it: the exact rituals, in the right order, for the precise Wealth Block your three cards revealed.<br />You are seconds from holding both halves of the work.
      </p>
    </div>
    <video class="hero__image" autoplay loop muted playsinline preload="auto" poster="frontend/images/upsell/upsell-hero.jpg">
      <source src="frontend/images/upsell/upsell-hero-motion.mp4?v=1" type="video/mp4" />
      <img class="hero__image" src="frontend/images/upsell/upsell-hero.jpg" alt="Hands performing a clearing ritual over a gold filigree mirror with rising smoke, deep violet and gold" />
    </video>
  </section>

  <!-- ═══ EARLY OFFER BLOCK ═══ -->
  <section style="padding:30px 18px; background:linear-gradient(180deg, rgba(45,27,105,0.30), rgba(20,12,52,0.55));">
    <div style="max-width:600px; margin:0 auto;">
      <div style="border:1px solid rgba(212,175,55,0.45); border-radius:16px; background:rgba(20,12,52,0.65); padding:26px 22px; box-shadow:0 16px 44px rgba(0,0,0,0.4);">
        <div style="text-align:center; font-family:'Inter',system-ui,sans-serif; font-size:11px; letter-spacing:0.18em; text-transform:uppercase; color:#E8C97A; margin-bottom:12px;">The Fast Track to Clear Your Wealth Block</div>
        <h2 style="text-align:center; font-family:'Cormorant Garamond',serif; color:#fff; font-size:30px; font-weight:600; line-height:1.2; margin:0 0 12px;">Add The Soul Ritual Practice</h2>
        <p style="text-align:center; color:#e9e2f2; font-size:17px; line-height:1.6; margin:0 0 18px;">One payment. Instant download. Yours to keep for life. The reading lands in your inbox within 24 hours; this is ready the moment you confirm.</p>
        <ul style="list-style:none; padding:0; margin:0 0 18px; color:#e9e2f2; font-size:16px; line-height:1.6;">
          <li style="padding:7px 0; border-bottom:1px solid rgba(212,175,55,0.15);">&#10022; The Mirror Block Clearing Rituals, a 3-part protocol. All four block-type versions are inside, so whichever one your reading reveals is ready the moment it lands.</li>
          <li style="padding:7px 0; border-bottom:1px solid rgba(212,175,55,0.15);">&#10022; The Mirror Block Workbook, 45 pages to deepen and hold the work</li>
          <li style="padding:7px 0; border-bottom:1px solid rgba(212,175,55,0.15);">&#10022; 3 free bonuses: the Audio Companion, the Wealth Alert Protocol, the Love Harmony Audio</li>
          <li style="padding:7px 0;">&#10022; Yours to keep for life. Instant download.</li>
        </ul>
        <div style="text-align:center; margin-bottom:6px;">
          <span style="color:rgba(255,255,255,0.55); font-size:14px; display:block; margin-bottom:2px;">$449 value &middot; <span class="founding-blink">$100 founding discount applied</span></span>
          <span style="color:rgba(255,255,255,0.55); text-decoration:line-through; font-size:18px;">$197</span>
          <span style="color:#E8C97A; font-size:44px; font-weight:600; font-family:'Cormorant Garamond',serif; margin-left:10px; line-height:1;">$97</span>
        </div>
        <p style="text-align:center; color:#cdb98c; font-size:14px; margin:0 0 18px;">Founding member rate, this page only. Public price after launch is $197. One payment, no subscription.</p>
        <div style="text-align:center;">
          <a href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>" class="cta-btn" style="display:inline-block; text-decoration:none;">Yes, Add The Soul Ritual Practice &middot; $97</a>
          <p style="color:rgba(255,255,255,0.72); font-size:13px; margin:12px 0 0;">This adds to the order you just placed. No card to re-enter. One tap and it is yours.</p>
          <p style="color:#cdb98c; font-size:13px; margin:7px 0 0; font-style:italic;">Backed by a 90-day money-back guarantee. Keep everything.</p>
          <a class="cta-btn--secondary" href="<?= htmlspecialchars($downsellPageUrl, ENT_QUOTES, 'UTF-8') ?>" style="display:inline-block; margin-top:14px;">No thank you, continue to my reading &rarr;</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ AGITATION ═══ -->
  <section class="agitation">
    <div class="container">
      <h2 class="agitation__headline">
        You Have Named This Pattern Before.<br />
        <em>It Still Decides What You Earn.</em>
      </h2>
      <div class="agitation__body">
        <p>Be honest with yourself for a moment.</p>
        <p>You have read the books. Done the journaling. Sat through the courses, the therapy, the manifesting. At some point you saw the money pattern clearly and told yourself, <strong>"Now that I see it, I can change it."</strong></p>
        <p>And then it came back. The opportunity appeared, and something in you hesitated. The rate was yours to raise, and you left it where it was. The money came in, and somehow it did not stay. <strong>Same ceiling. Different month.</strong></p>

        <div class="agitation__callout">
          <p>"You did not name it wrong. You named it. But naming a ceiling has never once lowered it, and every month it stays named and unmoved, it quietly resets your income again."</p>
        </div>

        <p>That is the gap between a reading that names your Wealth Block and the work that actually dissolves it. <strong>The Soul Ritual Practice is built to close that gap, at the layer where the ceiling was set.</strong></p>
      </div>
    </div>
  </section>

  <!-- ═══ MECHANISM / THREE PROTOCOLS ═══ -->
  <section class="mechanism">
    <div class="container">
      <h2 class="mechanism__headline">
        Three Written Rituals,<br /><em>Calibrated to Your Wealth Block</em>
      </h2>
      <p class="mechanism__subhead">Not a general healing. The exact protocol for the precise ceiling your cards revealed.</p>

      <div class="mechanism__body">
        <p>There is a slow way to clear a Wealth Block, the one you have been on for years: read enough, journal enough, manifest enough, and hope it loosens on its own. It almost never does, because none of it reaches the layer the block actually lives on. <strong>The Soul Ritual Practice is the fast track.</strong> It goes straight to that layer, in the right order, and clears it directly.</p>
        <p>Your Wealth Block was installed at a specific moment. A younger version of you drew a quiet conclusion about money, about what was safe to have, to want, to keep. That conclusion hardened into a belief. The belief became a ceiling. <strong>The ceiling has been resetting your income ever since.</strong></p>
        <p>Most money work tries to reason with that ceiling, to talk it out of existence with mindset and budgets. But the ceiling is not rational. It lives in the body, not the bank account. The Soul Ritual Practice moves through three stages, each meeting the block at a different layer, each written for your specific type.</p>
      </div>

      <hr class="gold-rule" style="margin: 0 auto 40px;" />

      <div class="three-part">
        <div class="report-card">
          <div class="report-card__num">Ritual One</div>
          <div class="report-card__body">
            <h3>The Root Witnessing</h3>
            <p>Traces your Wealth Block to the exact moment it was set, not the story you tell about money, the moment underneath it. This is precision work, not journaling. Most people say this ritual alone feels like something loosening on the first read.</p>
          </div>
        </div>
        <div class="report-card">
          <div class="report-card__num">Ritual Two</div>
          <div class="report-card__body">
            <h3>The Pattern Interruption</h3>
            <p>The ceiling shows itself most clearly the instant money gets close: the moment you are about to receive, quote, ask, or invest. This is the exact sequence for catching it there and interrupting it in real time, with the specific move for your block type.</p>
          </div>
        </div>
        <div class="report-card">
          <div class="report-card__num">Ritual Three</div>
          <div class="report-card__body">
            <h3>The New Imprint</h3>
            <p>Clearing the old ceiling leaves an empty space. What fills it decides whether the change holds or quietly resets. This final ritual writes a new ceiling at the body level, the imprint that makes more money feel safe to keep. This is what makes the shift structural, not a good week.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ LUNA CREDENTIAL ═══ -->
  <section class="luna-section">
    <div class="container">
      <h2 class="luna-section__headline">Why Me, <em>and Why Now</em></h2>

      <div class="luna-card">
        <div class="luna-card__body">
          <p>Eleven years ago I was exactly where you are. I had done the reading. I understood my money pattern perfectly. I could trace it, name it, explain it. <strong>And I watched it cap me anyway, year after year.</strong></p>
          <p>What finally moved it was not another reading. It was meeting the pattern at the layer where it was set, not explained, not budgeted around, but <em>witnessed and rewritten</em> in the body. I spent the years after distilling that into a written protocol anyone could follow alone, specific to each Wealth Block type, no practitioner and no scheduling required.</p>
          <p>I have now walked thousands of people through this. <strong>The ceiling that would not move through understanding moved when they did the right work, at the right layer, in the right order.</strong> Your reading names your block. This is how I walk you through clearing it, the same sequence I would use if you were sitting across from me.</p>
        </div>
        <div class="luna-card__sig">
          <p>With love and with clarity,<br /><strong>Luna Ross</strong> &middot; 11 years, 4,800+ readings</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ TESTIMONIALS ═══ -->
  <section class="testimonials">
    <div class="container">
      <span class="testimonials__eyebrow">✦ From People Who Almost Clicked Away ✦</span>
      <h2 class="testimonials__headline">
        They Nearly Said No.<br /><em>Then the Ceiling Moved.</em>
      </h2>

      <div class="testimonial-grid">

        <div class="testimonial">
          <img class="testimonial__avatar" src="frontend/images/upsell/testimonial-margaret-v.png" alt="Margaret V." />
          <div class="testimonial__body">
            <span class="testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
            <p class="testimonial__quote">"I almost closed this page. I told myself I had done enough inner work to last a lifetime. Something made me stay. Four months on, I finally raised my rates after years of stalling, and the client said yes without blinking. The number I could never get past is not the ceiling anymore."</p>
            <span class="testimonial__name">Margaret V., 52 &middot; Edinburgh, UK</span>
          </div>
        </div>

        <div class="testimonial">
          <img class="testimonial__avatar" src="frontend/images/upsell/testimonial-david-r.png" alt="David R." />
          <div class="testimonial__body">
            <span class="testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
            <p class="testimonial__quote">"I will be honest, I bought it more out of curiosity than belief. I have done the courses. What I did not expect was Ritual Two catching me in the exact half-second I always talk myself down on price. I held my number on a proposal last month that I would have slashed a year ago. It stuck. Ninety-seven dollars came back the first time I used it."</p>
            <span class="testimonial__name">David R., 49 &middot; Manchester, UK</span>
          </div>
        </div>

        <div class="testimonial">
          <img class="testimonial__avatar" src="frontend/images/upsell/testimonial-sophia-k.png" alt="Sophia K." />
          <div class="testimonial__body">
            <span class="testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
            <p class="testimonial__quote">"I actually did click away. Then I came back the next day and bought it. The Root Witnessing showed me where my whole 'money never stays' story started, and I cried, not from pain, from relief. For the first time in my life a good month did not quietly undo itself the next."</p>
            <span class="testimonial__name">Sophia K., 47 &middot; Melbourne, AU</span>
          </div>
        </div>

      </div>
      <p style="text-align:center; max-width:640px; margin:28px auto 0; font-size:13px; color:#9a8fae; font-style:italic;">Individual results vary and are not typical. The Soul Ritual Practice is a self-guided practice, not financial advice.</p>
    </div>
  </section>

  <!-- ═══ URGENCY ═══ -->
  <section class="urgency">
    <div class="container">
      <h2 class="urgency__headline">Founding Member Price, This Page Only</h2>
      <p class="urgency__body">Two things are true right now. The $97 founding rate is shown once, here, then returns to its $197 public price. And the ceiling does not pause while you decide. Left alone, it resets your income again next month, the same way it has every month so far. The page closing is the small loss. Another year at the same ceiling is the real one.</p>
    </div>
  </section>

  <!-- ═══ OFFER BOX ═══ -->
  <section class="offer" id="offer">
    <div class="container">
      <span class="offer__eyebrow">Complete Your Wealth Work</span>
      <h2 class="offer__headline">Add The Soul Ritual Practice</h2>
      <p class="offer__subhead">The diagnosis is on its way. This is the treatment. Yours to keep for life.</p>

      <div class="offer__package-image">
        <img src="frontend/images/ups-downs/upsell1-package.png" alt="The Complete Soul Ritual Practice, all products together" />
      </div>

      <div class="value-stack">
        <p class="value-stack__title">Everything You Receive</p>
        <div class="value-line">
          <span class="value-line__name">The Mirror Block Clearing Rituals, a 3-Part Protocol</span>
          <span class="value-line__price">$249</span>
        </div>
        <div class="value-line">
          <span class="value-line__name">The Mirror Block Workbook, 45 Pages</span>
          <span class="value-line__price">$79</span>
        </div>
        <div class="value-line">
          <span class="value-line__name"><strong style="color:#E8C97A;">Bonus 1</strong> &middot; Soul Ritual Audio Companion</span>
          <span class="value-line__price">$59</span>
        </div>
        <div class="value-line">
          <span class="value-line__name"><strong style="color:#E8C97A;">Bonus 2</strong> &middot; The Wealth Alert Protocol</span>
          <span class="value-line__price">$39</span>
        </div>
        <div class="value-line">
          <span class="value-line__name"><strong style="color:#E8C97A;">Bonus 3</strong> &middot; The Love Harmony Audio</span>
          <span class="value-line__price">$23</span>
        </div>
        <div class="value-line value-line--total">
          <span class="value-line__name">Total Value</span>
          <span class="value-line__price">$449</span>
        </div>
      </div>

      <div class="coupon">
        <span class="coupon__label">✦ &nbsp; Founding Member Coupon Applied &nbsp; ✦</span>
        <span class="coupon__amount">- $100.00 OFF</span>
        <span class="coupon__code">Code <span>FOUNDING100</span></span>
      </div>

      <div class="price-reveal">
        <span class="price-reveal__label">Founding Member Price Today</span>
        <span class="price-reveal__was">$197</span>
        <span class="price-reveal__now">$97</span>
        <span class="price-reveal__note">The full $449 practice, $100 off, for $97. One payment, no subscription.</span>
      </div>

      <a href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>" class="cta-btn">
        Yes, Add The Soul Ritual Practice &middot; $97
      </a>
      <p style="text-align:center; color:#cdb98c; font-size:13px; margin:10px 0 0;">Charged to the order you just placed. No card to re-enter. Backed by the 90-day guarantee below.</p>

      <a class="cta-btn--secondary" href="<?= htmlspecialchars($downsellPageUrl, ENT_QUOTES, 'UTF-8') ?>">
        No thank you, continue to my reading &rarr;
      </a>
    </div>
  </section>

  <!-- ═══ GUARANTEE ═══ -->
  <section class="guarantee">
    <div class="container">
      <div class="guarantee__inner">
        <img class="guarantee__badge" src="frontend/images/ups-downs/img-bb865578d0.png" alt="90-Day Guarantee Badge" />
        <h2 class="guarantee__headline">The Soul Ritual Practice, 90-Day Guarantee</h2>
        <p class="guarantee__body">Do the work. Read the three rituals, follow them in order, and use them for 90 days. If you have honestly done that and nothing about your money ceiling has loosened, email me for a full refund of your $97. You decide whether it worked, no hoops, no explanation required. You keep the rituals, the workbook, and all three bonuses either way. The risk is entirely mine, not yours.</p>
      </div>
    </div>
  </section>

  <!-- ═══ FAQ ═══ -->
  <section class="faq">
    <div class="container">
      <h2 class="faq__headline">Questions About the Practice</h2>
      <div class="faq-list">

        <details class="faq-item">
          <summary class="faq-item__q">I have not seen my reading yet. Am I buying blind?</summary>
          <div class="faq-item__a">No, and this is the part most people get backwards. All four block-type versions live inside the practice. Your reading has already identified which one is yours, so the moment it lands, the exact protocol for your ceiling is sitting ready to open, no waiting, nothing to request. Buying now simply means the treatment is already in your hands the instant the diagnosis arrives, instead of days later.</div>
        </details>

        <details class="faq-item">
          <summary class="faq-item__q">How is this different from the reading I just bought?</summary>
          <div class="faq-item__a">Your reading is the diagnosis. It names the ceiling and shows you where it lives. The Soul Ritual Practice is the treatment: the written protocol that clears it at the layer where it was set. The reading is Part One. This is Part Two. They are built to work as one.</div>
        </details>

        <details class="faq-item">
          <summary class="faq-item__q">What if I am not "spiritual enough" for this?</summary>
          <div class="faq-item__a">The rituals work at the level of the nervous system and body memory, not belief. They do not ask you to believe anything. They ask your body to release a ceiling it has been holding. You do not need to be spiritual. You need to be willing.</div>
        </details>

        <details class="faq-item">
          <summary class="faq-item__q">How does access work?</summary>
          <div class="faq-item__a">The moment your payment confirms, everything downloads instantly: the three Clearing Rituals, the 45-page Workbook, and all three bonuses. Yours to keep for life. No subscription, no expiry. It is charged to the order you just placed, so there is no card to re-enter.</div>
        </details>

      </div>
    </div>
  </section>

  <!-- ═══ FINAL CTA ═══ -->
  <section class="final-cta" id="no-thanks">
    <div class="container">
      <h2 class="final-cta__headline">
        You Have the Diagnosis.<br />
        <em>Take the Treatment With You.</em>
      </h2>
      <p class="final-cta__body">
        The reading names the ceiling on your money. The Soul Ritual Practice is how you lift it. Add it now and all four block-type protocols sit waiting, so the instant your reading names yours, the exact clearing work is already in your hands. These are the two halves of one piece of work, and you have come too far to keep only half.
      </p>

      <a href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>" class="cta-btn" style="text-decoration:none; display:inline-block; margin-bottom:16px;">
        Yes, Add The Soul Ritual Practice &middot; $97
      </a>

      <a class="cta-btn--secondary" href="<?= htmlspecialchars($downsellPageUrl, ENT_QUOTES, 'UTF-8') ?>" style="display:inline-block; margin-top:14px;">No thank you, continue to my reading &rarr;</a>
      <p class="cta-subtext" style="margin-top:12px;">Your reading arrives within 24 hours either way.</p>
    </div>
  </section>

  <!-- ═══ FOOTER ═══ -->
  <footer class="footer">
    <div class="container">
      <p>
        &copy; 2026 Soul Mirror Reading, A Luna Ross Brand, All Rights Reserved<br />
        <a href="#">Privacy Policy</a> &nbsp;&middot;&nbsp; <a href="#">Terms of Service</a> &nbsp;&middot;&nbsp; <a href="#">Contact</a><br /><br />
        ClickBank is the retailer of products on this site. CLICKBANK&reg; is a registered trademark of Click Sales Inc.
        The Soul Ritual Practice is a self-guided written practice. Results may vary and are not typical. This is not financial advice and is not a substitute for licensed mental health care.
      </p>
    </div>
  </footer>

</body>
</html>

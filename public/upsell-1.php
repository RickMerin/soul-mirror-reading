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
  <title>Complete Your Soul Mirror Reading — The Clearing Practice From Luna Ross</title>
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
      .pain__headline, .mechanism__headline, .includes__headline,
      .bonuses__headline, .offer__headline, .final-cta__headline,
      .testimonials__headline { font-size: 20px; }
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
    .faq-item__q::-webkit-details-marker,
    .faq-item__q::marker {
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
</head>
<body>

  <!-- ═══ NOTICE BAR ═══ -->
  <div class="notice-bar">
    <p style="color: #ec1229; font-weight: 700;">
      Soul Mirror Member Exclusive &mdash; Seen Only After Your Reading
    </p>
  </div>

  <!-- ═══ HERO ═══ -->
  <section class="hero">
    <div class="container">
      <span class="hero__eyebrow">Your Reading Is Being Prepared</span>
      <h1 class="hero__headline">
        You Just Took Part One.<br />
        <em>This Is How You Complete It.</em>
      </h1>
      <p class="hero__subhead">
        Your Soul Mirror Reading reveals the pattern. The Soul Ritual Practice is how Luna walks you through clearing it — one written protocol at a time, designed specifically for your block type.
      </p>
    </div>
    <img class="hero__image" src="frontend/images/upsell/hero-tarot-mirror-candle.png" alt="Three tarot cards, mirror, and lit candle on a writing desk" />
  </section>

  <!-- ═══ PART 1 / PART 2 JOURNEY FRAME ═══ -->
  <section class="journey">
    <div class="journey__inner">
      <span class="journey__label">✦ &nbsp; The Two Halves of the Work &nbsp; ✦</span>
      <p class="journey__body">
        The reading you just paid for is <strong>Part One</strong>.<br />
        This page is <strong>Part Two</strong> — the piece that makes the first one hold.
      </p>

      <div class="part-row">
        <div class="part-col part-col--done">
          <span class="part-col__num">Part One</span>
          <p class="part-col__title">The Soul Mirror Reading</p>
          <p class="part-col__status part-col__status--done">✓ You just claimed this</p>
        </div>
        <div class="part-arrow">→</div>
        <div class="part-col">
          <span class="part-col__num">Part Two</span>
          <p class="part-col__title">The Soul Ritual Practice</p>
          <p class="part-col__status">The clearing work</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ BRIDGE / LUNA MESSAGE ═══ -->
  <section class="bridge">
    <div class="container">
      <span class="section-label" style="text-align:center;">A personal note from Luna Ross</span>
      <div class="bridge__body">
        <p>Before your reading even reaches you, I want you to know something most readers will never tell you.</p>
        <p>Knowing the name of your block is not the same as clearing it. I have sat with people who understood their pattern perfectly — who could trace it, describe it, explain it in flawless detail — <strong>and still watched it run their life for another decade.</strong></p>
        <p>Understanding lives in the mind. Your Mirror Block does not. <strong>The clearing is a different kind of work entirely</strong> — and if you have come this far, you deserve the tools to finish what the reading started.</p>
      </div>
      <p class="bridge__sig"><strong>Luna Ross</strong> &mdash; 11 years, 4,800+ readings</p>
    </div>
  </section>

  <!-- ═══ AGITATION ═══ -->
  <section class="agitation">
    <div class="container">
      <h2 class="agitation__headline">
        You Have Been Here Before.<br />
        <em>You Knew. And Nothing Changed.</em>
      </h2>
      <div class="agitation__body">
        <p>Think about it honestly for a moment.</p>
        <p>You have read the books. Done the journaling. Sat through the therapy. You have, at some point, identified the exact pattern you are caught in and said to yourself: <strong>"I see it. Now I can change it."</strong></p>
        <p>And then — slowly, quietly — the pattern came back. Same shape, different situation.</p>

        <div class="agitation__callout">
          <p>"The money opportunity appeared, and something made you hesitate. The relationship got good, and something made you pull back. The purpose felt clear, and something made you doubt."</p>
        </div>

        <p>Not because you were not trying hard enough. Because <strong>the part of you running that pattern is not your conscious mind</strong> — and your conscious mind, no matter how awake, cannot clear what it cannot reach.</p>
        <p>This is the gap between a reading that names your block and work that actually dissolves it. <strong>This is the gap the Soul Ritual Practice closes.</strong></p>
      </div>
    </div>
  </section>

  <!-- ═══ PAIN POINTS ═══ -->
  <section class="pain">
    <div class="container">
      <span class="section-label">If Any of This Sounds Familiar</span>
      <h2 class="pain__headline">
        You Already Know What Your Pattern Is.<br />
        <em>That Has Never Been the Problem.</em>
      </h2>
      <div class="pain__list">
        <div class="pain__item">
          <p>You know exactly what your pattern is. You could write an essay on it. <em>And it still runs your life every single day.</em></p>
        </div>
        <div class="pain__item">
          <p>You have had the breakthrough. More than once. The clarity came — and six weeks later you were back in the same place wearing a different outfit.</p>
        </div>
        <div class="pain__item">
          <p>The money, the relationship, the purpose — you work on one and another falls. <em>Something seems to regulate how much good you are allowed to hold at once.</em></p>
        </div>
        <div class="pain__item">
          <p>You are not broken. You are not resistant. <em>You are working on the wrong layer.</em></p>
        </div>
        <div class="pain__callout">
          <p>Most healing work asks your mind to fix what your mind did not create. That is the gap. That is why nothing holds.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ MECHANISM / THREE PROTOCOLS ═══ -->
  <section class="mechanism">
    <div class="container">
      <span class="mechanism__eyebrow">The Clearing Mechanism</span>
      <h2 class="mechanism__headline">
        Three Written Protocols,<br /><em>Calibrated to Your Block Type</em>
      </h2>
      <p class="mechanism__subhead">Not a general healing. A targeted practice for the exact pattern your cards revealed.</p>

      <div class="mechanism__body">
        <p>Every Mirror Block was installed at a specific moment — a moment where a younger version of you drew a conclusion about how the world works and what was safe to receive. That conclusion hardened into a belief. That belief became a pattern. <strong>The pattern has been running ever since.</strong></p>
        <p>The problem with most healing work is that it tries to reason with the pattern — to talk it out of existence. But the pattern is not rational. It is somatic. It lives in the body's memory, not the mind's story.</p>
        <p>The Soul Ritual Practice works differently. It moves through three distinct stages, each one targeting a different layer of where your block lives. Every protocol is written specifically for your block type — calibrated at the exact depth the pattern was first laid down.</p>
      </div>

      <hr class="gold-rule" style="margin: 0 auto 40px;" />

      <div class="three-part">
        <div class="report-card">
          <div class="report-card__num">Ritual One</div>
          <div class="report-card__body">
            <h3>The Root Witnessing</h3>
            <p>Traces your block back to the exact moment it was installed — not the story you tell about it. This is precision work, not journalling. Most people say this ritual alone feels like something lifting after the first read.</p>
          </div>
        </div>
        <div class="report-card">
          <div class="report-card__num">Ritual Two</div>
          <div class="report-card__body">
            <h3>The Pattern Interruption</h3>
            <p>The exact sequence for interrupting your block the moment it starts running. Different techniques for different block types — because the fear underneath each one was installed differently and must be met differently.</p>
          </div>
        </div>
        <div class="report-card">
          <div class="report-card__num">Ritual Three</div>
          <div class="report-card__body">
            <h3>The New Imprint</h3>
            <p>Clearing the old pattern leaves a space. What fills that space determines whether the change holds. This final ritual installs a new belief at the body level — the piece that makes the shift structural rather than temporary.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ LUNA CREDENTIAL ═══ -->
  <section class="luna-section">
    <div class="container">
      <h2 class="luna-section__headline">Why Luna &mdash; <em>and Why Now</em></h2>

      <div class="luna-card">
        <div class="luna-card__body">
          <p>Eleven years ago I was where you are. I had done the reading. I understood my pattern intellectually. I could see exactly how it showed up across every area of my life. <strong>And I kept watching it happen anyway.</strong></p>
          <p>The turning point was not another reading. It was a session with a healer who understood that the pattern had to be met at the level where it was created — not explained, not reasoned with, but <em>witnessed and released</em> in the body.</p>
          <p>I spent the next seven years learning every clearing method I could find. Then I spent four more years distilling what works into a written protocol that could reach anyone — specific to each Mirror Block type, no practitioner required, no scheduling, no in-person session.</p>
          <p>I have now run this practice with thousands of people at every stage of their journey. <strong>The block that could not be shifted through understanding moved when they followed the right kind of work, at the right layer, in the right order.</strong></p>
          <p>The Soul Mirror Reading you just claimed identifies your block. The practice below is how I walk you through clearing it — the same sequence I would take you through if you were sitting in my reading room.</p>
        </div>
        <div class="luna-card__sig">
          <p>With love and with clarity,<br /><strong>Luna Ross</strong> &mdash; 11 years, 4,800+ readings</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ PRACTICE VISUAL REMOVED — product images now live inside each card ═══ -->

  <!-- ═══ INCLUDES ═══ -->
  <section class="includes">
    <div class="container">
      <span class="includes__eyebrow">Everything Inside The Practice</span>
      <h2 class="includes__headline">Here Is What You <em>Receive</em></h2>
      <p class="includes__subhead">Two core pieces that form one complete clearing practice. Delivered the moment you confirm — yours to keep for life.</p>
<div class="includes-grid">

        <h3 class="include-item__headline">The Mirror Block Clearing Rituals &mdash; A 3-Part Protocol</h3>
        <div class="include-item__hero">
          <img src="frontend/images/ups-downs/main-clearing-rituals.png" alt="The Mirror Block Clearing Rituals — 3-Part Trilogy">
        </div>

        <div class="include-item">
          <div class="include-item__content">
            <p>Luna's complete clearing practice, built as three sequential rituals specific to your block type. Each ritual meets the pattern at a different layer — from the moment it was first installed, to the daily interruption technique, to the new belief that takes its place.</p>
            <div class="include-item__parts">
              <div class="part-item">
                <strong>Ritual One &mdash; The Root Witnessing</strong>
                <p>Traces your block back to the exact moment it was installed. Precision work, not journalling.</p>
              </div>
              <div class="part-item">
                <strong>Ritual Two &mdash; The Pattern Interruption</strong>
                <p>The sequence for interrupting your block the moment it runs. Specific to your block type.</p>
              </div>
              <div class="part-item">
                <strong>Ritual Three &mdash; The New Imprint</strong>
                <p>Installing a new belief at the body level. What makes the shift structural, not temporary.</p>
              </div>
            </div>
            <span class="include-item__value">Value: $201</span>
          </div>
        </div>

        <h3 class="include-item__headline">The Mirror Block Workbook &mdash; 45 Pages</h3>
        <div class="include-item__hero include-item__hero--portrait">
          <img src="frontend/images/ups-downs/main-workbook.png" alt="The Mirror Block Workbook — 45 Pages">
        </div>

        <div class="include-item">
          <div class="include-item__content">
            <p>Your companion workbook for the full practice — prompts, reflections, and integration exercises that walk alongside each of the three rituals. Designed to be returned to repeatedly. The work deepens with every pass.</p>
            <span class="include-item__value">Value: $97</span>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ═══ BONUSES ═══ -->
  <section class="bonuses">
    <div class="container">
      <span class="bonuses__eyebrow">✦ Plus, When You Claim Today ✦</span>
      <h2 class="bonuses__headline">Three Free Bonuses<br /><em>Worth $151, Yours Free</em></h2>
      <p class="bonuses__subhead">These are the tools I built for people who want the shift to hold — not just land. Included free when you claim the Soul Ritual Practice today.</p>

      <div class="bonuses-grid">

        <div class="bonus-item">
          <span class="bonus-item__badge">Bonus One</span>
          <div class="bonus-item__image">
            <img src="frontend/images/ups-downs/bonus-1-audio-companion.png" alt="Soul Ritual Audio Companion">
          </div>
          <div class="bonus-item__content">
            <h3>Soul Ritual Audio Companion</h3>
            <p>Three short guided audios (10&ndash;15 minutes each), one for each Clearing Ritual. I walk you through the key practice out loud &mdash; for the moments when your body needs to hear the work instead of read it. Listen in the morning. Listen on the commute. Listen before you sleep.</p>
            <span class="bonus-item__value">Value: $67</span>
          </div>
        </div>

        <div class="bonus-item">
          <span class="bonus-item__badge">Bonus Two</span>
          <div class="bonus-item__image">
            <img src="frontend/images/ups-downs/bonus-2-wealth-alert.png" alt="The Wealth Alert Protocol">
          </div>
          <div class="bonus-item__content">
            <h3>The Wealth Alert Protocol</h3>
            <p>A specific protocol for catching your Mirror Block in the moment it affects money. The block shows up most clearly at the instant you are about to receive, quote, or ask for more &mdash; and that is exactly where most people miss it. A written guide with the exact signals to watch for, and the counter-move to make in real time.</p>
            <span class="bonus-item__value">Value: $47</span>
          </div>
        </div>

        <div class="bonus-item">
          <span class="bonus-item__badge">Bonus Three</span>
          <div class="bonus-item__image">
            <img src="frontend/images/ups-downs/bonus-3-love-harmony.png" alt="The Love Harmony Audio">
          </div>
          <div class="bonus-item__content">
            <h3>The Love Harmony Audio</h3>
            <p>A 15-minute guided audio voiced by Luna, specifically for the love mirror. The clearing work changes how you show up in partnership &mdash; this audio walks you through integrating the shift so your relationships can hold what you are now allowed to receive.</p>
            <span class="bonus-item__value">Value: $37</span>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ═══ TESTIMONIALS ═══ -->
  <section class="testimonials">
    <div class="container">
      <span class="testimonials__eyebrow">✦ From People Who Almost Said No ✦</span>
      <h2 class="testimonials__headline">
        They Nearly Clicked Away.<br /><em>Then They Chose This.</em>
      </h2>

      <div class="testimonial-grid">

        <div class="testimonial">
          <img class="testimonial__avatar" src="frontend/images/upsell/testimonial-margaret-v.png" alt="Margaret V." />
          <div class="testimonial__body">
            <span class="testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
            <p class="testimonial__quote">"I almost closed this page. I told myself I had done enough inner work already. Something made me stay. Four months in, and the pattern that ran my life for thirty years is finally quiet. I have never written a review for anything. I am writing this one."</p>
            <span class="testimonial__name">Margaret V., 52 &mdash; Edinburgh, UK</span>
          </div>
        </div>

        <div class="testimonial">
          <img class="testimonial__avatar" src="frontend/images/upsell/testimonial-david-r.png" alt="David R." />
          <div class="testimonial__body">
            <span class="testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
            <p class="testimonial__quote">"I hovered over 'no thanks' for a long time. I am not usually an add-on person. But the way Luna described the gap — between knowing and clearing — landed too precisely to ignore. Best $97 I have spent this year. I am on my second read-through of Ritual One."</p>
            <span class="testimonial__name">David R., 49 &mdash; Manchester, UK</span>
          </div>
        </div>

        <div class="testimonial">
          <img class="testimonial__avatar" src="frontend/images/upsell/testimonial-sophia-k.png" alt="Sophia K." />
          <div class="testimonial__body">
            <span class="testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
            <p class="testimonial__quote">"I actually did click away. Then I came back through the follow-up email the next day and bought it. The section on how my block was formed made me cry — not from pain, from relief. I have been in therapy for six years. This reached somewhere therapy did not."</p>
            <span class="testimonial__name">Sophia K., 47 &mdash; Melbourne, AU</span>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ═══ URGENCY ═══ -->
  <section class="urgency">
    <div class="container">
      <h2 class="urgency__headline">Founding Member Price &mdash; Only Shown on This Page</h2>
      <p class="urgency__body">This offer is shown only to new Soul Mirror members immediately after their reading. When the Soul Ritual Practice opens to the public, it will be priced at $197. The founding member rate below is available only here, only now.</p>
    </div>
  </section>

  <!-- ═══ OFFER BOX ═══ -->
  <section class="offer" id="offer">
    <div class="container">
      <span class="offer__eyebrow">Complete Your Soul Mirror Journey</span>
      <h2 class="offer__headline">Add The Soul Ritual Practice</h2>
      <p class="offer__subhead">One payment. Instant download. Yours to keep for life.</p>

      <div class="offer__package-image">
        <img src="frontend/images/ups-downs/upsell1-package.png" alt="The Complete Soul Ritual Practice — all 5 products together">
      </div>

      <div class="value-stack">
        <p class="value-stack__title">Everything You Receive</p>
        <div class="value-line">
          <span class="value-line__name">The Mirror Block Clearing Rituals &mdash; A 3-Part Protocol</span>
          <span class="value-line__price">$201</span>
        </div>
        <div class="value-line">
          <span class="value-line__name">The Mirror Block Workbook &mdash; 45 Pages</span>
          <span class="value-line__price">$97</span>
        </div>
        <div class="value-line">
          <span class="value-line__name"><strong style="color:#E8C97A;">Bonus 1</strong> &mdash; Soul Ritual Audio Companion</span>
          <span class="value-line__price">$67</span>
        </div>
        <div class="value-line">
          <span class="value-line__name"><strong style="color:#E8C97A;">Bonus 2</strong> &mdash; The Wealth Alert Protocol</span>
          <span class="value-line__price">$47</span>
        </div>
        <div class="value-line">
          <span class="value-line__name"><strong style="color:#E8C97A;">Bonus 3</strong> &mdash; The Love Harmony Audio</span>
          <span class="value-line__price">$37</span>
        </div>
        <div class="value-line value-line--total">
          <span class="value-line__name">Total Value</span>
          <span class="value-line__price">$449</span>
        </div>
      </div>

      <div class="coupon">
        <span class="coupon__label">✦ &nbsp; Founding Member Coupon Applied &nbsp; ✦</span>
        <span class="coupon__amount">&minus; $100.00 OFF</span>
        <span class="coupon__code">Code <span>FOUNDING100</span></span>
      </div>

      <div class="price-reveal">
        <span class="price-reveal__label">Founding Member Price Today</span>
        <span class="price-reveal__was">$197</span>
        <span class="price-reveal__now">$97</span>
        <span class="price-reveal__note">One payment. Instant access. No subscription.</span>
      </div>

      <a href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>" class="cta-btn">
        Yes, I Want To Upgrade My Soul Mirror Journey
      </a>

      <a class="cta-btn--secondary" href="<?= htmlspecialchars($downsellPageUrl, ENT_QUOTES, 'UTF-8') ?>">
        No thank you &mdash; I'll work through my block alone
      </a>
    </div>
  </section>

  <!-- ═══ GUARANTEE ═══ -->
  <section class="guarantee">
    <div class="container">
      <div class="guarantee__inner">
        <img class="guarantee__badge" src="frontend/images/ups-downs/img-bb865578d0.png" alt="90-Day Guarantee Badge">
        <h2 class="guarantee__headline">The Soul Ritual Practice &mdash; 90-Day Guarantee</h2>
        <p class="guarantee__body">Work through the practice. If you do not feel a genuine shift in how you relate to your Mirror Block within 90 days — email us for a full refund of your $97. No explanation required. No questions asked. The risk is entirely mine, not yours.</p>
      </div>
    </div>
  </section>

  <!-- ═══ FAQ ═══ -->
  <section class="faq">
    <div class="container">
      <h2 class="faq__headline">Questions About the Practice</h2>
      <div class="faq-list">

        <details class="faq-item">
          <summary class="faq-item__q">Do I need to read my Soul Mirror Reading first?</summary>
          <div class="faq-item__a">Not necessarily — the practice is built around your Mirror Block type, which your reading has already identified. That said, reading your PDF first deepens the work considerably. Most people find the two work best together.</div>
        </details>

        <details class="faq-item">
          <summary class="faq-item__q">How is this different from the reading I just bought?</summary>
          <div class="faq-item__a">Your reading is diagnostic — it names the pattern and shows you where it lives. The Soul Ritual Practice is the clearing — the written protocol for dissolving the pattern at the level where it was created. The two are designed to work together. Reading alone is Part One. Practice is Part Two.</div>
        </details>

        <details class="faq-item">
          <summary class="faq-item__q">What if I'm not "spiritual enough" for this?</summary>
          <div class="faq-item__a">The clearing techniques work at the level of the nervous system and somatic memory — not belief. They are not asking you to believe anything. They are asking your body to release something it has been holding. You do not need to be spiritual. You need to be willing.</div>
        </details>

        <details class="faq-item">
          <summary class="faq-item__q">How is this different from therapy or journalling?</summary>
          <div class="faq-item__a">Therapy operates through language and narrative. The Soul Ritual Practice targets the pre-narrative layer where the block was created. Many people who worked through this practice had been in therapy for years beforehand. Most say the reports reached something therapy could not.</div>
        </details>

        <details class="faq-item">
          <summary class="faq-item__q">How does access work?</summary>
          <div class="faq-item__a">The moment your payment processes, everything is available for immediate download — the three Clearing Rituals, the Workbook, and all three bonuses. Yours to keep forever. No subscription, no expiry.</div>
        </details>

        <details class="faq-item">
          <summary class="faq-item__q">Is the practice specific to my block type?</summary>
          <div class="faq-item__a">Yes. Each report contains block-specific protocols. The method for a "Not Yet Ready" block is different from the method for a "Too Much" block — because the pattern forms differently and must be interrupted differently. Your reading identified your block. The practice was written for it.</div>
        </details>

      </div>
    </div>
  </section>

  <!-- ═══ FINAL CTA ═══ -->
  <section class="final-cta" id="no-thanks">
    <div class="container">
      <h2 class="final-cta__headline">
        You Already Have <em>Part One.</em><br />
        Take the Second Half With You.
      </h2>
      <p class="final-cta__body">
        The reading gave you the name. The practice gives you the clearing. These are the two halves of the same work — and you deserve both.
      </p>

      <a href="<?= htmlspecialchars($otoCheckoutUrl, ENT_QUOTES, 'UTF-8') ?>" class="cta-btn" style="text-decoration:none; display:inline-block; margin-bottom:16px;">
        Yes, I Want To Upgrade My Soul Mirror Journey
      </a>

      <p class="cta-subtext" style="margin-top:12px;">
        Or close this page &mdash; your Soul Mirror Reading will be delivered within 12&ndash;24 hours regardless.
      </p>
    </div>
  </section>

  <!-- ═══ FOOTER ═══ -->
  <footer class="footer">
    <div class="container">
      <p>
        &copy; 2026 Soul Mirror Reading &mdash; A Luna Ross Brand &mdash; All Rights Reserved<br />
        <a href="#">Privacy Policy</a> &nbsp;&middot;&nbsp; <a href="#">Terms of Service</a> &nbsp;&middot;&nbsp; <a href="#">Contact</a><br /><br />
        ClickBank is the retailer of products on this site. CLICKBANK&reg; is a registered trademark of Click Sales Inc.
        The Soul Ritual Practice is a self-guided written practice. Results may vary. This service is not a substitute for licensed mental health care.
      </p>
    </div>
  </footer>

</body>
</html>

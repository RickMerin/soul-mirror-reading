<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Wait — One More Thing From Luna</title>
  <link rel="icon" type="image/svg+xml" href="favicon.svg" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Crimson+Pro:ital,wght@0,300;0,400;0,600;1,300;1,400&display=swap"
    rel="stylesheet"
  />
  <style>
    :root {
      --violet-deep:  #2D1B69;
      --violet-mid:   #2a1252;
      --violet-dark:  #1a0d40;
      --gold:         #D4AF37;
      --gold-light:   #e8c97a;
      --gold-pale:    #f5e9c0;
      --cream:        #FEFCF8;
      --cream-warm:   #f9f5ec;
      --text-body:    #2c2040;
      --text-muted:   #6b5c82;
      --white:        #ffffff;
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

    .container { max-width: 720px; margin: 0 auto; padding: 0 24px; }

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
      padding: 64px 24px 0; text-align: center;
      position: relative; overflow: hidden;
    }
    .hero::before {
      content: ''; position: absolute; inset: 0;
      background: radial-gradient(ellipse at 50% 0%, rgba(212,175,55,0.14) 0%, transparent 65%);
      pointer-events: none;
    }
    .hero__eyebrow {
      font-family: 'Cinzel', serif; font-size: 11px; letter-spacing: 0.26em;
      text-transform: uppercase; color: var(--gold); margin-bottom: 22px; display: block;
      position: relative;
    }
    .hero__headline {
      font-family: 'Cinzel', serif; font-weight: 700;
      font-size: clamp(28px, 5vw, 42px); line-height: 1.2; color: var(--cream);
      margin-bottom: 22px; max-width: 640px; margin-left: auto; margin-right: auto;
      position: relative;
    }
    .hero__headline em { color: var(--gold-light); font-style: italic; }
    .hero__subhead {
      font-family: 'Cormorant Garamond', serif; font-size: clamp(18px, 2.8vw, 22px);
      font-style: italic; color: rgba(254,252,248,0.85);
      max-width: 580px; margin: 0 auto 40px; line-height: 1.55;
      position: relative;
    }
    .hero__image {
      max-width: 760px; width: 100%; margin: 0 auto;
      display: block; border-radius: 10px 10px 0 0;
      position: relative;
      aspect-ratio: 16 / 9;
      object-fit: cover;
      object-position: center;
      box-shadow: 0 -10px 40px rgba(212,175,55,0.18);
    }

    /* ═══ PART 1 / PART 2 FRAME ═══ */
    .journey { background: var(--cream); padding: 56px 24px 40px; }
    .journey__inner { max-width: 640px; margin: 0 auto; text-align: center; }
    .journey__label {
      font-family: 'Cinzel', serif; font-size: 10px; letter-spacing: 0.3em;
      text-transform: uppercase; color: #8B6914;
      margin-bottom: 16px; display: block;
    }
    .journey__body {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(19px, 2.6vw, 23px);
      font-style: italic;
      color: var(--violet-deep);
      line-height: 1.55;
    }
    .journey__body strong {
      font-style: normal; font-family: 'Cinzel', serif;
      font-weight: 600; font-size: 0.95em; letter-spacing: 0.04em;
    }

    .part-row {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      gap: 20px;
      margin-top: 32px;
      align-items: stretch;
    }
    .part-col {
      padding: 20px 16px;
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
      font-size: 17px; font-weight: 600;
      color: var(--violet-deep); margin-bottom: 6px;
      line-height: 1.3;
    }
    .part-col__status {
      font-family: 'Crimson Pro', serif; font-size: 13px;
      font-style: italic; color: var(--text-muted);
    }
    .part-col__status--done { color: #15803D; font-weight: 500; }
    .part-arrow {
      display: flex; align-items: center; justify-content: center;
      color: var(--gold); font-size: 22px; font-family: 'Cinzel', serif;
    }

    /* ═══ BRIDGE ═══ */
    .bridge { background: var(--cream); padding: 40px 24px 60px; }
    .bridge__body { font-size: 19px; line-height: 1.8; color: var(--text-body); max-width: 620px; margin: 0 auto; }
    .bridge__body p + p { margin-top: 20px; }
    .bridge__body strong { color: var(--violet-deep); }
    .bridge__sig {
      margin-top: 28px;
      padding-top: 24px;
      border-top: 1px solid rgba(212,175,55,0.25);
      font-family: 'Cormorant Garamond', serif;
      font-style: italic;
      font-size: 17px;
      color: var(--text-muted);
      text-align: center;
    }
    .bridge__sig strong { color: var(--violet-deep); font-style: normal; }

    /* ═══ PAIN POINTS ═══ */
    .pain { background: var(--violet-deep); padding: 64px 24px; }
    .pain__headline {
      font-family: 'Cinzel', serif; font-size: clamp(21px, 3.6vw, 30px);
      font-weight: 600; color: var(--cream);
      margin-bottom: 36px; line-height: 1.3;
    }
    .pain__headline em { color: var(--gold-light); font-style: italic; }
    .pain__list { display: flex; flex-direction: column; gap: 16px; }
    .pain__item { padding: 16px 0; border-bottom: 1px solid rgba(212,175,55,0.15); }
    .pain__item:last-of-type { border-bottom: none; }
    .pain__item p {
      font-family: 'Crimson Pro', serif; font-size: 18px;
      color: rgba(254,252,248,0.9); line-height: 1.72;
    }
    .pain__item p em { font-style: italic; color: var(--gold-light); }

    /* ═══ TESTIMONIALS ═══ */
    .testimonials { background: var(--cream-warm); padding: 68px 24px; }
    .testimonials__eyebrow {
      font-family: 'Cinzel', serif; font-size: 11px; letter-spacing: 0.22em;
      text-transform: uppercase; color: var(--gold);
      text-align: center; margin-bottom: 12px; display: block;
    }
    .testimonials__headline {
      font-family: 'Cinzel', serif; font-size: clamp(20px, 3.4vw, 27px);
      font-weight: 600; color: var(--violet-deep);
      text-align: center; margin-bottom: 40px; line-height: 1.35;
    }
    .testimonials__headline em { color: var(--gold); font-style: italic; font-family: 'Cormorant Garamond', serif; }
    .testimonial-grid { display: flex; flex-direction: column; gap: 18px; }
    .testimonial {
      display: flex; gap: 22px; align-items: center;
      background: var(--white); border-radius: 10px;
      border: 1px solid rgba(212,175,55,0.22);
      padding: 28px;
      box-shadow: 0 4px 16px rgba(45,27,105,0.05);
    }
    .testimonial__avatar {
      width: 78px; height: 78px;
      border-radius: 50%;
      object-fit: cover;
      object-position: center;
      border: 2px solid rgba(212,175,55,0.4);
      flex-shrink: 0;
      box-shadow: 0 4px 12px rgba(45,27,105,0.1);
    }
    .testimonial__body { flex: 1; }
    .testimonial__stars {
      color: var(--gold); font-size: 14px; letter-spacing: 3px;
      margin-bottom: 8px; display: block;
    }
    .testimonial__quote {
      font-family: 'Cormorant Garamond', serif; font-size: 18px;
      font-style: italic; line-height: 1.6; color: var(--text-body);
      margin-bottom: 12px;
    }
    .testimonial__name {
      font-family: 'Cinzel', serif; font-size: 11px; letter-spacing: 0.14em;
      text-transform: uppercase; color: var(--text-muted);
    }

    /* ═══ PRICE DROP / OFFER ═══ */
    .price-drop {
      background: linear-gradient(160deg, var(--violet-mid) 0%, var(--violet-deep) 100%);
      padding: 72px 24px; text-align: center;
    }
    .price-drop__eyebrow {
      font-family: 'Cinzel', serif; font-size: 11px; letter-spacing: 0.26em;
      text-transform: uppercase; color: var(--gold);
      margin-bottom: 16px; display: block;
    }
    .price-drop__headline {
      font-family: 'Cinzel', serif; font-size: clamp(23px, 4vw, 32px);
      font-weight: 700; color: var(--cream);
      margin-bottom: 12px; line-height: 1.3;
    }
    .price-drop__sub {
      font-family: 'Cormorant Garamond', serif; font-size: 20px;
      font-style: italic; color: rgba(254,252,248,0.78);
      margin-bottom: 40px;
    }
    .value-stack {
      background: rgba(255,255,255,0.06); border: 1px solid rgba(212,175,55,0.3);
      border-radius: 10px; padding: 30px 26px; margin-bottom: 32px; text-align: left;
    }
    .value-stack__title {
      font-family: 'Cinzel', serif; font-size: 12px; letter-spacing: 0.18em;
      text-transform: uppercase; color: var(--gold);
      margin-bottom: 20px; text-align: center;
    }
    .value-line {
      display: flex; justify-content: space-between; align-items: baseline;
      padding: 9px 0; border-bottom: 1px solid rgba(212,175,55,0.12); gap: 16px;
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
      font-family: 'Cormorant Garamond', serif; font-size: 18px;
      font-weight: 600; color: var(--cream);
    }
    .value-line--total .value-line__price {
      font-size: 15px; text-decoration: none; opacity: 1; color: var(--gold-light);
    }

    /* ═══ COUPON BADGE ═══ */
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
      font-family: 'Cinzel', serif; font-size: 11px; letter-spacing: 0.22em;
      text-transform: uppercase; color: var(--gold);
      display: block; margin-bottom: 8px;
    }
    .price-was-row {
      display: flex; align-items: center; justify-content: center; gap: 20px;
      margin-bottom: 4px;
    }
    .price-reveal__was {
      font-family: 'Cormorant Garamond', serif; font-size: 26px;
      color: rgba(254,252,248,0.38); text-decoration: line-through;
    }
    .price-reveal__arrow { color: var(--gold-light); font-size: 22px; }
    .price-reveal__now {
      font-family: 'Cinzel', serif; font-size: clamp(52px, 10vw, 76px);
      font-weight: 700; color: var(--gold-light); line-height: 1; display: block;
    }
    .price-reveal__note {
      font-size: 15px; color: rgba(254,252,248,0.55);
      margin-top: 8px; display: block;
    }
    .price-savings {
      display: inline-block;
      background: rgba(212,175,55,0.15);
      border: 1px solid rgba(212,175,55,0.4);
      border-radius: 20px;
      font-family: 'Cinzel', serif;
      font-size: 11px;
      letter-spacing: 0.15em;
      color: var(--gold-light);
      padding: 6px 18px;
      margin-top: 14px;
    }

    .cta-btn {
      display: inline-block;
      background: linear-gradient(135deg, var(--gold) 0%, #b8941f 100%);
      color: var(--violet-deep); font-family: 'Cinzel', serif;
      font-size: clamp(13px, 2.5vw, 16px); font-weight: 700; letter-spacing: 0.1em;
      text-transform: uppercase; text-decoration: none; padding: 22px 44px;
      border-radius: 6px; border: none; cursor: pointer;
      width: 100%; max-width: 500px; text-align: center;
      box-shadow: 0 6px 24px rgba(212,175,55,0.35);
      transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .cta-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(212,175,55,0.45); }
    .cta-btn--secondary {
      background: transparent; color: rgba(254,252,248,0.45);
      font-family: 'Crimson Pro', serif; font-size: 14px;
      text-transform: none; font-weight: 300;
      display: block; margin-top: 14px; text-align: center;
      cursor: pointer; text-decoration: underline; text-underline-offset: 3px;
      border: none; padding: 8px; width: 100%;
    }
    .cta-btn--secondary:hover { color: rgba(254,252,248,0.7); }
    .cta-subtext { margin-top: 18px; font-size: 14px; color: rgba(254,252,248,0.55); line-height: 1.6; }

    /* ═══ GUARANTEE ═══ */
    .guarantee { background: var(--cream); padding: 60px 24px; }
    .guarantee__inner {
      background: var(--cream-warm); border: 2px solid rgba(212,175,55,0.3);
      border-radius: 12px; padding: 40px 34px; text-align: center;
      max-width: 600px; margin: 0 auto;
    }
    .guarantee__badge { font-size: 44px; margin-bottom: 14px; display: block; color: var(--gold); }
    .guarantee__headline {
      font-family: 'Cinzel', serif; font-size: clamp(17px, 2.5vw, 22px);
      font-weight: 600; color: var(--violet-deep); margin-bottom: 14px;
    }
    .guarantee__body {
      font-size: 17px; line-height: 1.75; color: var(--text-body);
    }

    /* ═══ FOOTER ═══ */
    .footer { background: var(--violet-mid); padding: 24px; text-align: center; }
    .footer p { font-size: 12px; color: rgba(254,252,248,0.38); line-height: 1.6; }
    .footer a { color: rgba(254,252,248,0.45); text-decoration: underline; text-underline-offset: 2px; }

    @media (max-width: 600px) {
      .part-row { grid-template-columns: 1fr; }
      .part-arrow { transform: rotate(90deg); padding: 6px 0; }
      .hero__image { aspect-ratio: 4 / 3; }
      .testimonial { flex-direction: column; align-items: center; text-align: center; gap: 14px; padding: 22px 18px; }
      .value-stack { padding: 24px 18px; }
      .guarantee__inner { padding: 32px 20px; }
    }
  </style>
</head>
<body>

  <!-- ═══ NOTICE BAR ═══ -->
  <div class="notice-bar">
    <p>Wait &mdash; Before You Go &mdash; A One-Time Offer</p>
  </div>

  <!-- ═══ HERO ═══ -->
  <section class="hero">
    <div class="container">
      <span class="hero__eyebrow">A final note from Luna Ross</span>
      <h1 class="hero__headline">
        I Am Not Letting You Leave<br /><em>Without the Second Half.</em>
      </h1>
      <p class="hero__subhead">
        $97 was not right for you today. Fine. I would rather meet you where you are than watch you walk away from work you will need.
      </p>
    </div>
    <img class="hero__image" src="frontend/images/downsell/hero-journal-candle-mirror.png" alt="Open journal, lit candle, antique mirror on writing desk" />
  </section>

  <!-- ═══ PART 1 / PART 2 FRAME ═══ -->
  <section class="journey">
    <div class="journey__inner">
      <span class="journey__label">✦ &nbsp; What You Already Have. What You Still Need. &nbsp; ✦</span>
      <p class="journey__body">
        You have <strong>Part One</strong>. This page is still offering you <strong>Part Two</strong> — at a price I do not usually show.
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
          <p class="part-col__status">Now $67 &mdash; one time only</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ BRIDGE ═══ -->
  <section class="bridge">
    <div class="container">
      <div class="bridge__body">
        <p>Your reading shows you the root. That matters enormously.</p>
        <p>But I have sat with thousands of people who could see their Mirror Block exactly — and still watched it run their life for another decade. Not because they were not trying. <strong>Because seeing is not the same as clearing.</strong></p>
        <p>The Soul Ritual Practice is the same four files. The same clearing work. The only thing that changed is the price — and the fact that this page will not be offered to you again.</p>
      </div>
      <p class="bridge__sig"><strong>Luna Ross</strong></p>
    </div>
  </section>

  <!-- ═══ PAIN POINTS ═══ -->
  <section class="pain">
    <div class="container">
      <span class="section-label">You Already Know This Is You</span>
      <h2 class="pain__headline">
        You Have Tried Everything.<br />
        <em>And The Pattern Keeps Coming Back.</em>
      </h2>
      <div class="pain__list">
        <div class="pain__item">
          <p>You know exactly what your pattern is. You could write an essay on it. <em>And it still runs your life every single day.</em></p>
        </div>
        <div class="pain__item">
          <p>You have had breakthroughs. The clarity came — and six weeks later you were back in the same place wearing a different outfit.</p>
        </div>
        <div class="pain__item">
          <p>You are not broken. You are not resistant. <em>You are working on the wrong layer.</em></p>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ TESTIMONIALS ═══ -->
  <section class="testimonials">
    <div class="container">
      <span class="testimonials__eyebrow">✦ From People Who Almost Said No Twice ✦</span>
      <h2 class="testimonials__headline">
        They Clicked Away Once.<br /><em>This Page Brought Them Back.</em>
      </h2>

      <div class="testimonial-grid">

        <div class="testimonial">
          <img class="testimonial__avatar" src="frontend/images/downsell/testimonial-jennifer-l.png" alt="Jennifer L." />
          <div class="testimonial__body">
            <span class="testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
            <p class="testimonial__quote">"I almost said no at $97. When I saw the price drop I almost said no again — I thought it must be a lesser version. It is not. It is the same practice. I finished it over a weekend. My husband noticed the change before I told him I had done anything."</p>
            <span class="testimonial__name">Jennifer L., 51 &mdash; Denver, US</span>
          </div>
        </div>

        <div class="testimonial">
          <img class="testimonial__avatar" src="frontend/images/downsell/testimonial-marcus-t.png" alt="Marcus T." />
          <div class="testimonial__body">
            <span class="testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
            <p class="testimonial__quote">"Full disclosure: I was the definition of sceptical. I clicked 'no thanks' on the first offer. Something made me reconsider when I saw this page. Three weeks later I accepted a role I would have walked away from. I do not know how to explain it. I just know it worked."</p>
            <span class="testimonial__name">Marcus T., 46 &mdash; Austin, US</span>
          </div>
        </div>

        <div class="testimonial">
          <img class="testimonial__avatar" src="frontend/images/downsell/testimonial-elena-m.png" alt="Elena M." />
          <div class="testimonial__body">
            <span class="testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
            <p class="testimonial__quote">"I nearly did not take it. I already have too many courses sitting unfinished. This is the only one I finished in a single weekend — because it was not a course, it was a practice. Reading it felt like being seen. I keep Report Three on my desk now."</p>
            <span class="testimonial__name">Elena M., 43 &mdash; Barcelona, ES</span>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ═══ PRICE DROP + OFFER ═══ -->
  <section class="price-drop" id="offer">
    <div class="container">
      <span class="price-drop__eyebrow">One-Time Offer &mdash; Disappears When You Leave</span>
      <h2 class="price-drop__headline">Complete Your Journey for $67</h2>
      <p class="price-drop__sub">Same four files. Same practice. The only thing lower is the price.</p>

      <div class="value-stack">
        <p class="value-stack__title">Everything You Receive</p>
        <div class="value-line">
          <span class="value-line__name">Report One &mdash; The Root Witnessing Guide</span>
          <span class="value-line__price">$67</span>
        </div>
        <div class="value-line">
          <span class="value-line__name">Report Two &mdash; The Pattern Interruption Protocol</span>
          <span class="value-line__price">$67</span>
        </div>
        <div class="value-line">
          <span class="value-line__name">Report Three &mdash; The New Imprint Practice</span>
          <span class="value-line__price">$67</span>
        </div>
        <div class="value-line">
          <span class="value-line__name">The Mirror Block Workbook &mdash; 45 Pages</span>
          <span class="value-line__price">$37</span>
        </div>
        <div class="value-line value-line--total">
          <span class="value-line__name">Total Value</span>
          <span class="value-line__price">$238</span>
        </div>
      </div>

      <div class="coupon">
        <span class="coupon__label">✦ &nbsp; One-Time Discount Applied &nbsp; ✦</span>
        <span class="coupon__amount">&minus; $30.00 OFF</span>
        <span class="coupon__code">Code <span>EXTRA30</span></span>
      </div>

      <div class="price-reveal">
        <span class="price-reveal__label">Your Price &mdash; One Time Only</span>
        <div class="price-was-row">
          <span class="price-reveal__was">$97</span>
          <span class="price-reveal__arrow">→</span>
        </div>
        <span class="price-reveal__now">$67</span>
        <span class="price-reveal__note">One payment. Instant download. No subscription.</span>
      </div>

      <a href="#" class="cta-btn">
        Yes, I Want To Upgrade My Soul Mirror Journey
      </a>

      <button class="cta-btn--secondary" onclick="window.location.href='member/login.php'">
        No thank you &mdash; proceed to my reading only
      </button>

      <p class="cta-subtext">
        Secure checkout via ClickBank &mdash; All four files delivered immediately
      </p>
    </div>
  </section>

  <!-- ═══ GUARANTEE ═══ -->
  <section class="guarantee">
    <div class="container">
      <div class="guarantee__inner">
        <span class="guarantee__badge">&#9674;</span>
        <h2 class="guarantee__headline">90-Day Full Refund Guarantee</h2>
        <p class="guarantee__body">
          Work through the practice. If you do not feel a genuine shift within 90 days — email us for a full refund of your $67. No questions, no explanation. The risk is entirely mine.
        </p>
      </div>
    </div>
  </section>

  <!-- ═══ FINAL DECLINE ═══ -->
  <section id="no-thanks" style="background:var(--cream-warm);padding:40px 24px;text-align:center;">
    <div class="container">
      <p style="font-size:16px;color:var(--text-muted);max-width:500px;margin:0 auto;line-height:1.7;">
        Your Soul Mirror Reading will be delivered within 12&ndash;24 hours regardless.<br />
        <a href="member/login.php" style="color:var(--violet-deep);font-size:15px;text-underline-offset:3px;">
          No thank you — proceed to my reading only
        </a>
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
      </p>
    </div>
  </footer>

</body>
</html>

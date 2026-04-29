<?php
declare(strict_types=1);
$cssPath = __DIR__ . '/assets/sales-bundle.min.css';
$cssVer = is_file($cssPath) ? filemtime($cssPath) : time();
$jsPath = __DIR__ . '/assets/sales.min.js';
$jsVer = is_file($jsPath) ? filemtime($jsPath) : time();
$checkoutUrl = 'https://rebornf.pay.clickbank.net/?cbitems=smr-1';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description"
    content="See the one core belief—your Mirror Block—behind your Love, Life, and Wealth cards. Deep card work, clearing practice, and 90-day prompts delivered with your Soul Mirror Reading.">
  <title>Your Soul Mirror Reading — What the Cards Are Really Saying</title>
  <link rel="icon" type="image/svg+xml" href="favicon.svg">
  <link rel="stylesheet" href="assets/sales-bundle.min.css?v=<?= htmlspecialchars((string) $cssVer, ENT_QUOTES) ?>">
  <style>
    .testimonials-section {
      padding: 40px 0 88px;
      position: relative;
      z-index: 1;
    }

    .testimonials-section h2 {
      font-size: clamp(28px, 4.5vw, 42px);
      text-align: center;
      margin-bottom: 44px;
    }

    .testi-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
    }

    .testi-card {
      background: rgba(255, 255, 255, 0.6);
      border: 1px solid rgba(59, 31, 110, 0.14);
      border-radius: 12px;
      padding: 28px 26px;
      backdrop-filter: blur(4px);
    }

    .testi-avatar-row {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 16px;
    }

    .testi-avatar {
      width: 56px;
      height: 56px;
      border-radius: 100%;
      object-fit: cover;
      border: 2px solid rgba(212, 175, 55, 0.45);
      flex-shrink: 0;
      box-shadow: 0 3px 10px rgba(59, 31, 110, 0.14);
    }

    .testi-avatar-meta {
      min-width: 0;
      flex: 1;
    }

    .testi-avatar-meta .testi-name {
      font-family: var(--serif);
      font-size: 15px;
      font-weight: 700;
      color: var(--text);
      line-height: 1.2;
    }

    .testi-avatar-meta .testi-meta {
      font-size: 12px;
      color: var(--text-soft);
      margin-top: 3px;
    }
  </style>
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

  <!-- ══ RED TOP NOTICE BAR ══ -->
  <div class="topnotice">
    <strong>Check Your Inbox:</strong><span class="notice-dot">·</span>Your 3-Card Personal Tarot Reading Is On Its
    Way.<span class="notice-dot">·</span><strong>While You Wait, Read This Carefully.</strong>
  </div>

  <main id="main-content">

  <!-- ══ VSL SECTION ══ -->
  <section class="vsl-section">
    <div class="wrap">
      <h1 class="vsl-headline">One Card Fell Out Before<br><em>The Reading Even Began</em></h1>
      <p class="vsl-sub">Face-up. Uninvited. A direct message from the universe that refuses to be ignored. Yours
        revealed the one belief running silently behind all three of your mirrors. <strong>Your Mirror Block.</strong>
      </p>
      <p class="vsl-sub" style="margin-top:-8px;"><em>The message came through. Watch now. This is the part most people
          never get told.</em></p>

      <div class="video-frame">
       <vturb-smartplayer id="vid-69e22380ed35062270c75e01" style="display: block; margin: 0 auto; width: 100%; "></vturb-smartplayer> <script type="text/javascript"> var s=document.createElement("script"); s.src="https://scripts.converteai.net/6fa5f75c-723e-4301-a459-76c14edde081/players/69e22380ed35062270c75e01/v4/player.js", s.async=!0,document.head.appendChild(s); </script>
      </div>

      <div class="badges">
        <span class="badge">🔒 &nbsp; Secure &amp; Private</span>
        <span class="badge">⭐ &nbsp; 4,800+ Readings</span>
        <span class="badge">🌙 &nbsp; 90-Day Guarantee</span>
      </div>

      <div style="margin-top:28px;">
        <button type="button" class="read-toggle" aria-expanded="false">
          ☽ &nbsp; Prefer to read instead? &nbsp; ☾
        </button>
      </div>
    </div>
  </section>

  <div id="read-version" style="display:none;">

    <!-- ══ LUNA'S STORY ══ -->
    <section class="story-section section">
      <div class="wrap-narrow">
        <p>Before you open your reading, take just a moment.</p>
        <p>What I'm about to share changes how the cards land. And if you've been doing this for a while, you need to
          hear this first.</p>
        <p>You already know the feeling.</p>
        <p>You pull a card and something in your chest goes still. <em>That's it.</em> That's the answer you've been
          circling for months. You write it down. You sit with it. You feel, for the first time in a long time, like
          things are finally going to shift.</p>
        <p>And they do. For a day or two.</p>
        <p>Then day three arrives. You're back in the same conversation with the same person. Making the same choice you
          swore you wouldn't make. Feeling the exact same weight you thought the cards had just lifted off you.</p>
        <div class="story-pull">You don't throw the cards out. You don't blame the reading. You blame yourself.</div>
        <p>You think you're not doing the inner work hard enough. Not surrendering enough. Not trusting the process
          enough.</p>
        <p>So you pull another card.</p>
        <p>I want to tell you something gently but clearly.</p>
        <p>The readings were accurate. The insights were real. The problem was never the cards, and it was never you.
        </p>
        <p>There's something structural missing between seeing and shifting. A step that almost no reading is designed
          to address.</p>
        <div class="story-pull">Most readings show you what's happening. Very few show you why it keeps happening.</div>
        <p>That gap between seeing and actually changing is where years can disappear.</p>
        <p>I was a reader for eleven years before I understood this.</p>
        <p>Not someone who did readings for herself on a Tuesday afternoon. I sat with clients. Real people with real
          pain, week after week, year after year. And I was good at it. The cards rarely lied.</p>
        <p>But something started to trouble me.</p>
        <p>I would watch someone have a genuine breakthrough in a session. Tears. Recognition. That unmistakable look of
          a person who has just seen something true about themselves. And I would think: <em>this is the one. This is
            the session that changes things for her.</em></p>
        <p>Three weeks later, she'd be back. Same situation. Same story in a slightly different arrangement.</p>
        <div class="story-pull">It wasn't that the reading failed. It was that something underneath the reading never
          got touched.</div>
        <p>I started asking a question I couldn't stop asking. <em>What is the thing beneath the things?</em></p>
        <p>I began going deeper with clients. Not into new cards. Into patterns.</p>
        <p>I started mapping what was showing up in love against what was showing up in work. Then against money. Then
          against how they described their daily life.</p>
        <p>And I kept finding the same thing.</p>
        <p>It wasn't three problems. It was one.</p>
        <p>One belief, specific to each person and invisible to them, quietly running every area of their life at the
          same time. Their relationships were shaped by it. Their career decisions were shaped by it. The way they
          handled money, spent it, avoided it. All of it traced back to this one thing.</p>
        <div class="story-pull">I started calling it the Mirror Block. Because it sits between you and every reflection
          you're trying to read.</div>
        <p>When it's there, no amount of accurate cards moves the needle. When it's finally seen clearly, not just
          understood but truly <em>seen</em>, something releases. Not dramatically. More like a window opening in a room
          that's been closed too long.</p>
        <p>Women I'd worked with for years stopped coming back. Not because something went wrong. Because something
          finally went right.</p>
        <p>One woman stopped circling the same decision about leaving her job. She just left. Another stopped explaining
          herself to her mother. She didn't make a big announcement. She just stopped.</p>
        <p>The changes were quiet. They were lasting. They didn't look like transformation. They looked like finally
          being able to breathe.</p>
        <p>The Soul Mirror Reading was built to do exactly this one thing.</p>
        <p>It finds your Mirror Block, the single belief running love, life, and wealth simultaneously, and gives you
          the deep card work and the practice to begin dissolving it. Not a reading that describes your life. A reading
          that shows you what's been shaping it all along.</p>
        <p>One reading. One root. Everything shifts from there.</p>

      </div>
    </section>

    <div style="text-align:center; padding-bottom:48px;">
      <button type="button" class="read-toggle-collapse">
        ☽ &nbsp; Collapse this section &nbsp; ☾
      </button>
    </div>

  </div><!-- /#read-version -->

  <!-- ══ TRANSITION BANNER ══ -->
  <div class="transition-banner js-reveal">
    <div class="transition-banner-inner">
      <div class="tb-tag">☽ &nbsp; Your Soul Mirror Reading &nbsp; ☾</div>
      <h2 class="tb-headline">The Complete Picture Behind All 3 Cards</h2>
      <p class="tb-sub">Three cards. Three mirrors. One hidden belief running all of them.<br>This is what your reading
        has been pointing to.</p>
    </div>
  </div>

  <!-- ══ READING BREAKDOWN ══ -->
  <section class="reading-section section js-reveal">
    <div class="wrap">
      <h2>What Each Card Is <em>Actually</em> Showing You</h2>
      <div class="section-divider"></div>

      <div class="path-card">
        <span class="path-label">Mirror One &nbsp;·&nbsp; Love</span>
        <div class="path-title-row">
          <div class="path-icon"><picture>
              <source type="image/webp" srcset="cards/mirror-love.webp">
              <img src="cards/mirror-love.png" width="865" height="1080" alt="Love Mirror" decoding="async" loading="lazy">
            </picture></div>
          <div>
            <h3>The Love Mirror</h3>
            <p>Where you pull back right before connection becomes real. The pattern that keeps love feeling just slightly
              out of reach.
            </p>
          </div>
        </div>
      </div>

      <div class="path-card">
        <span class="path-label">Mirror Two &nbsp;·&nbsp; Life</span>
        <div class="path-title-row">
          <div class="path-icon"><picture>
              <source type="image/webp" srcset="cards/mirror-life.webp">
              <img src="cards/mirror-life.png" width="763" height="1080" alt="Life Mirror" decoding="async" loading="lazy">
            </picture>
          </div>
          <div>
            <h3>The Life Mirror</h3>
            <p>Where your energy leaks and your choices keep looping. The place you feel most stuck, showing you the exact
             map you have been following.
            </p>
          </div>
        </div>
      </div>

      <div class="path-card">
        <span class="path-label">Mirror Three &nbsp;·&nbsp; Wealth</span>
        <div class="path-title-row">
          <div class="path-icon"><picture>
              <source type="image/webp" srcset="cards/mirror-wealth.webp">
              <img src="cards/mirror-wealth.png" width="942" height="1080" alt="Wealth Mirror" decoding="async" loading="lazy">
            </picture></div>
          <div>
            <h3>The Wealth Mirror</h3>
            <p>What you believe you are allowed to have. The inherited story about deserving that has been setting your
              ceiling without your knowledge.
            </p>
          </div>
        </div>
      </div>

      <div class="mirror-card">
        <span class="path-label">The Hidden Layer &nbsp;·&nbsp; Mirror Block</span>
        <div class="path-title-row">
          <div class="path-icon"><picture>
              <source type="image/webp" srcset="cards/mirror-block.webp">
              <img src="cards/mirror-block.png" width="826" height="1080" alt="Mirror Block" decoding="async" loading="lazy">
            </picture>
          </div>
          <div>
            <h3>The One Belief Running All Three</h3>
            <p>This is where most readings stop. They name the patterns. They describe the cards. And then they leave you
              with the insight and no way through.
            </p>
          </div>
        </div>
        <p>What they miss is this: your three cards are not three separate problems. They are three reflections of
          <strong>one core belief</strong>, formed early, embedded deep, and invisible to you precisely because it has
          been there so long.
        </p>
        <p>It shapes who you let close. What you reach for. What you decide you deserve. And until it is seen clearly,
          every reading you ever get will point to the same wall you cannot quite get past.</p>
        <p>We call it your <strong>Mirror Block</strong>. And right now, while the cards you drew are still fresh, is
          the clearest window you will have to see it.</p>
        <p><strong>Your Soul Mirror Reading names it exactly. Not a general theme. Yours.</strong></p>
      </div>
    </div>
  </section>

  <!-- ══ VIP SECTION ══ -->
  <section class="vip-section section js-reveal">
    <div class="wrap">
      <div class="vip-box">
        <h2>Inside Your Soul Mirror Reading</h2>
        <picture>
          <source type="image/webp" srcset="cards/product-mockup.webp">
          <img src="cards/product-mockup.png" width="auto" height="300" alt="Soul Mirror Reading" class="vip-mockup"
            decoding="async" loading="lazy">
        </picture>
        <div
          style="text-align:center;margin:-8px 0 20px;font-family:var(--serif);font-size:17px;color:#1a1a1a;letter-spacing:0.06em;">
          Valued at <span style="color:var(--purple-mid);font-weight:700;font-size:20px;">$197</span></div>
        <ul class="vip-list">
          <li><span><strong>Your Mirror Block Identified</strong> &nbsp;the one core belief running Love, Life, and
              Wealth at the same time</span></li>
          <li><span><strong>Love Mirror Deep-Dive</strong> &nbsp;what your Love card means in context of all three
              cards, not in isolation</span></li>
          <li><span><strong>Life Mirror Deep-Dive</strong> &nbsp;where your energy is leaking and what it would take to
              shift it</span></li>
          <li><span><strong>Wealth Mirror Deep-Dive</strong> &nbsp;the inherited story about deserving that has been
              setting your ceiling</span></li>
          <li><span><strong>Mirror Block Clearing Practice</strong> &nbsp;seven questions, ten minutes, designed to
              begin the release</span></li>
          <li><span><strong>Reversed Card Companion</strong> &nbsp;nuanced interpretation if any of your cards appeared
              reversed</span></li>
          <li><span><strong>90-Day Mirror Check-In Prompts</strong> &nbsp;twelve weekly questions to keep the clarity
              alive and working</span></li>
        </ul>
      </div>
    </div>
  </section>


  <!-- ══ BONUSES ══ -->
  <section class="bonuses-section section js-reveal">
    <div class="wrap">
      <h2>Free Bonuses Included Today</h2>
      <p class="bonuses-intro">Available only on this page, when you claim your Soul Mirror Reading now.</p>

      <div class="bonus-row">
        <div class="bonus-tile">
          <div class="bonus-header">
            <h4>Mirror Block Companion Guide</h4>
            <div class="bonus-value">Valued at $67</div>
          </div>
          <div class="bonus-img">
            <picture>
              <source type="image/webp" srcset="frontend/images/sales/mirror-block-companion-guide.webp">
              <img src="frontend/images/sales/mirror-block-companion-guide.png" width="250" height="333" alt="Mirror Block Companion Guide" decoding="async"
                loading="lazy">
            </picture>
          </div>
          <div class="bonus-body">
            <p>A deep-dive reference covering all four Mirror Block types: their root causes, language patterns, hidden
              costs, and the signs a real shift is beginning.</p>
            <div class="bonus-tag">Free Bonus</div>
          </div>
        </div>
        <div class="bonus-tile">
          <div class="bonus-header">
            <h4>21-Day Shift Tracker</h4>
            <div class="bonus-value">Valued at $47</div>
          </div>
          <div class="bonus-img"><picture>
              <source type="image/webp" srcset="frontend/images/sales/21-days-shift-tracker.webp">
                <img src="frontend/images/sales/21-days-shift-tracker.png" width="250" height="333" alt="21-Day Shift Tracker" decoding="async"
                loading="lazy">
            </picture></div>
          <div class="bonus-body">
            <p>A guided workbook with daily prompts across three themed weeks: Observation, Recognition, and Evidence,
              to help you track and anchor your pattern shifts in real time.</p>
            <div class="bonus-tag">Free Bonus</div>
          </div>
        </div>
      </div>

      <!-- 21-Day Shift Tracker -->
      <div class="bonus-row">
        <div class="bonus-tile">
          <div class="bonus-header">
            <h4>Root Cause Reading Guide</h4>
            <div class="bonus-value">Valued at $47</div>
          </div>
          <div class="bonus-img">
            <picture>
              <source type="image/webp" srcset="/frontend/images/sales/root-cause-reading-guide.webp">
              <img src="/frontend/images/sales/root-cause-reading-guide.png" width="250" height="333" alt="Image 3" decoding="async"
                loading="lazy">
            </picture>
          </div>
          <div class="bonus-body">
            <p>Story-based vignettes for each Mirror Block type that show exactly how the pattern formed, helping you
              see the origin clearly and stop mistaking the symptom for the source.</p>
            <div class="bonus-tag">Free Bonus</div>
          </div>
        </div>
        <div class="bonus-tile">
          <div class="bonus-header">
            <h4>Mirror Clarity Meditation</h4>
            <div class="bonus-value">Valued at $37</div>
          </div>
          <div class="bonus-img"><picture>
              <source type="image/webp" srcset="frontend/images/sales/mirror-block-companion-guide.webp">
              <img src="frontend/images/sales/mirror-block-companion-guide.png" width="250" height="333" alt="Mirror Block Companion Guide" decoding="async"
                loading="lazy">
            </picture></div>
          <div class="bonus-body">
            <p>A 10-minute guided audio meditation voiced by Luna that takes you through a somatic mirror visualization
              to feel, briefly, what life looks like on the other side of the pattern.</p>
            <div class="bonus-tag">Free Bonus</div>
          </div>
        </div>
      </div>

      <div class="bonus-total-row">
        <div>
          <div class="btl">Total Value</div>
          <div style="font-size:16px;color:rgba(245,240,250,0.85);font-style:italic;">Bonuses Included Free With Your
            Soul Mirror Reading Today</div>
        </div>
        <div class="btv">$395</div>
      </div>
    </div>
  </section>

  <!-- ══ PRICING ══ -->
  <section class="pricing-section section js-reveal" id="pricing">
    <div class="wrap-wide">
      <h2>The Cards You Chose <em>Are Still Fresh.</em><br>Read Them While They Are.</h2>

      <div class="price-bridge">
        <span class="price-bridge__eyebrow">☽ &nbsp; Why this price &nbsp; ☾</span>
        <p class="price-bridge__body">
          Luna's one-on-one readings were priced at <strong>$197</strong>. The four bonus materials were built over eleven
          years of client work. <strong>This is not a discount.</strong> It is a different price for a different moment
          — available only to people who drew their cards today.
        </p>
      </div>

      <div class="pricing-grid pricing-grid--single">
        <div class="price-card featured">
          <div class="price-card-badge">Complete Package</div>
          <div class="price-card-header">
            <span class="price-card-label">Soul Mirror Reading</span>
            <h3>Everything, One Price</h3>
          </div>
          <div class="price-card-body">
            <span class="price-amount"><span class="price-was">$395</span> <sup>$</sup>37</span>
            <span class="price-term">One-time payment &nbsp;·&nbsp; Delivered within 12&ndash;24 hours</span>
            <ul class="price-includes">
              <li>Your personalised Soul Mirror Reading &mdash; <em>$197 value</em></li>
              <li>Mirror Block identification &amp; all 3 card deep-dives</li>
              <li>Mirror Block clearing practice + 90-day check-in prompts</li>
              <li><strong>Bonus 1</strong> &mdash; Mirror Block Companion Guide ($67)</li>
              <li><strong>Bonus 2</strong> &mdash; 21-Day Shift Tracker ($47)</li>
              <li><strong>Bonus 3</strong> &mdash; Root Cause Reading Guide ($47)</li>
              <li><strong>Bonus 4</strong> &mdash; Mirror Clarity Meditation audio ($37)</li>
            </ul>
            <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="price-card-cta vip">Claim Your Soul Mirror Reading &rarr;</a>
          </div>
        </div>
      </div>

      <p class="value-line">Total value: <strong>$395</strong>. Yours today for <strong>$37</strong>.</p>

      <div class="cta-block">
        <div class="cta-note">
          Instant access &nbsp;·&nbsp; Secure checkout &nbsp;·&nbsp; 90-day money-back guarantee<br>
          <span class="stars">★★★★★</span> &nbsp; 4,800+ Soul Mirror Readings delivered
        </div>
      </div>
    </div>
  </section>

  <!-- ══ TESTIMONIALS ══ -->
  <section class="testimonials-section section js-reveal">
    <div class="wrap-wide">
      <h2>What Others Are Saying</h2>
      <div class="section-divider"></div>

      <div class="testi-grid">
        <div class="testi-card">
          <div class="testi-avatar-row">
            <img class="testi-avatar" src="frontend/images/frontend/testimonial-diane-r.png" alt="Diane R.">
            <div class="testi-avatar-meta">
              <div class="testi-name">Diane R.</div>
              <div class="testi-meta">Age 54 &nbsp;·&nbsp; Retired teacher</div>
            </div>
          </div>
          <div class="testi-stars">★★★★★</div>
          <p class="testi-quote">"I've had tarot readings before and always felt something was missing. This report gave
            me the missing piece. I could actually see the one thread running through all three areas of my life. I
            cried reading it, in the best possible way."</p>
        </div>
        <div class="testi-card">
          <div class="testi-avatar-row">
            <img class="testi-avatar" src="frontend/images/frontend/testimonial-james-h.png" alt="James H.">
            <div class="testi-avatar-meta">
              <div class="testi-name">James H.</div>
              <div class="testi-meta">Age 48 &nbsp;·&nbsp; Business owner</div>
            </div>
          </div>
          <div class="testi-stars">★★★★★</div>
          <p class="testi-quote">"I was skeptical. I'm a practical person. I just wanted to see what the cards said. But
            the Mirror Block explanation stopped me cold. It described something I've never been able to name. Whatever
            this is, it works."</p>
        </div>
        <div class="testi-card">
          <div class="testi-avatar-row">
            <img class="testi-avatar" src="frontend/images/frontend/testimonial-carolyn-m.png" alt="Carolyn M.">
            <div class="testi-avatar-meta">
              <div class="testi-name">Carolyn M.</div>
              <div class="testi-meta">Age 61 &nbsp;·&nbsp; Holistic practitioner</div>
            </div>
          </div>
          <div class="testi-stars">★★★★★</div>
          <p class="testi-quote">"Three cards. One pattern. I've spent years in therapy trying to understand why the
            same things kept happening in love, at work, with money. This report showed me in 20 minutes. The clearing
            practice alone is worth ten times what I paid."</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ══ GUARANTEE ══ -->
  <section class="guarantee-section section js-reveal">
    <div class="wrap">
      <div class="guarantee-box">
        <div class="guarantee-shield"><picture>
            <source type="image/webp" srcset="cards/guarantee-badge.webp">
            <img src="cards/guarantee-badge.png" width="1024" height="1024" alt="90-Day Mirror Guarantee"
              style="width:100%;height:100%;object-fit:contain;" decoding="async" loading="lazy">
          </picture></div>
        <div>
          <h3>90-Day Mirror Guarantee</h3>
          <p>If your Soul Mirror Reading doesn't give you a clearer picture of the pattern running your love, life, and
            wealth than anything you've seen before. Just send us a a message within 90 days and we'll refund every
            penny. No questions, no forms, no waiting.</p>
          <p>We stand behind this reading completely. If the mirror doesn't show you something real, you owe us nothing.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ══ HOW IT WORKS ══ -->
  <section class="steps-section section js-reveal">
    <div class="wrap">
      <h2>How Your Report Works</h2>
      <div class="section-divider"></div>
      <div class="steps-list">
        <div class="step-row">
          <div class="step-num">1</div>
          <div>
            <h4>Your cards are already drawn</h4>
            <p>The three cards you selected hold the pattern. The reading you're about to receive in your inbox is
              calibrated to those three specific cards and their combination. Not a generic result.</p>
          </div>
        </div>
        <div class="step-row">
          <div class="step-num">2</div>
          <div>
            <h4>Claim your Soul Mirror Reading</h4>
            <p>Your report takes your three cards deeper, identifying the Mirror Block connecting all three and giving
              you the written interpretation, clearing practice, and 90-day prompts to work with it.</p>
          </div>
        </div>
        <div class="step-row">
          <div class="step-num">3</div>
          <div>
            <h4>Read it when your email arrives</h4>
            <p>Your 3-card email is on its way. When it lands, have your Soul Mirror Reading open beside it. The two
              work together. The email shows you what the cards say, the report shows you what they mean beneath the
              surface.</p>
          </div>
        </div>
        <div class="step-row">
          <div class="step-num">4</div>
          <div>
            <h4>Do the clearing practice once</h4>
            <p>Set aside ten minutes. The Mirror Block clearing practice in your report is the only thing between where
              you are now and where the cards are pointing. Seven questions. Done in one sitting. The shift begins
              there.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══ FAQ ══ -->
  <section class="faq-section section">
    <div class="wrap">
      <h2>Questions</h2>
      <div class="section-divider"></div>

      <details class="faq-item">
        <summary class="faq-q">Is this based on my specific cards or is it generic?</summary>
        <p class="faq-a">Your Soul Mirror Reading is built around the specific three-card combination you drew, not a
          template. The Mirror Block identification and all three deep-dives are calibrated to how your cards interact
          with each other, not just what each card means individually. That's what makes this reading different from
          anything you've likely received before.</p>
      </details>

      <details class="faq-item">
        <summary class="faq-q">Do I need to know anything about tarot to understand this?</summary>
        <p class="faq-a">No prior knowledge needed. The report is written in plain language designed for real people,
          not tarot scholars. The Root Cause Reading Guide bonus is also included precisely because we want you to
          understand the origin of your pattern clearly, not just its surface symptoms.</p>
      </details>

      <details class="faq-item">
        <summary class="faq-q">What is a Mirror Block exactly?</summary>
        <p class="faq-a">A Mirror Block is a core belief, usually formed early in life and often inherited from someone
          else, that shows up identically across your love life, your daily experience, and your relationship with money
          and abundance. It's not a flaw and it's not permanent. But it is specific, and once you can see it clearly, it
          loses most of its power. That's what the report is designed to do.</p>
      </details>

      <details class="faq-item">
        <summary class="faq-q">How is this different from the reading I'll receive in my email?</summary>
        <p class="faq-a">The email reading you're receiving shows you what each card says. The Soul Mirror Reading shows
          you what your three cards mean together: the pattern underneath them, the belief connecting them, and what to
          do with that information. Think of the email as the mirror and the report as the light source that lets you
          actually see.</p>
      </details>

      <details class="faq-item">
        <summary class="faq-q">What if I'm not satisfied?</summary>
        <p class="faq-a">You're covered by our 90-day guarantee. If the report doesn't give you something genuinely
          useful, a clearer picture of the pattern in your life than you've had before, email us and we'll refund you
          completely. We'd rather you keep the money than feel like the mirror didn't show you anything real.</p>
      </details>

    </div>
  </section>

  <!-- ══ FINAL CTA ══ -->
  <section class="final-cta-section js-reveal wavy">
    <span class="eyebrow">☽ &nbsp; The Mirror Is Ready &nbsp; ☾</span>
    <h2>See the One Belief That Has Been Running<br><em>Love, Life, and Wealth From the Shadows</em></h2>
    <p>Your cards have already been drawn. The pattern is already there. The only question is whether you're ready to
      see it clearly.</p>

    <!-- Value Stack -->
    <div class="vstack">
      <div class="vstack-row">
        <span class="vstack-label">Soul Mirror Reading</span>
        <span class="vstack-val">Worth $197</span>
      </div>
      <div class="vstack-row">
        <span class="vstack-label">Mirror Block Companion Guide</span>
        <span class="vstack-val">Worth $67</span>
      </div>
      <div class="vstack-row">
        <span class="vstack-label">21-Day Shift Tracker</span>
        <span class="vstack-val">Worth $47</span>
      </div>
      <div class="vstack-row">
        <span class="vstack-label">Root Cause Reading Guide</span>
        <span class="vstack-val">Worth $47</span>
      </div>
      <div class="vstack-row">
        <span class="vstack-label">Mirror Clarity Meditation</span>
        <span class="vstack-val">Worth $37</span>
      </div>
      <div class="vstack-total">
        <span class="vstack-total-label">Total Value</span>
        <span class="vstack-total-val"><s style="opacity:0.45;font-weight:400;">$395</s> &nbsp; $37</span>
      </div>
    </div>

    <div class="cta-block">
      <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="cta-btn">
        Claim Your Soul Mirror Reading →</a>
      <div class="cta-note">
        Instant access &nbsp;·&nbsp; 90-day guarantee &nbsp;·&nbsp; Secure checkout
      </div>
    </div>
  </section>

  </main>

  <!-- ══ FOOTER ══ -->
  <footer class="site-footer js-reveal">
    <div class="footer-legal-copy">
      <p>ClickBank is the retailer of products on this site. CLICKBANK® is a registered trademark of Click Sales, Inc.,
        a Delaware corporation located at 1444 S. Entertainment Ave., Suite 410 Boise, ID 83709, USA and used by
        permission. ClickBank's role as retailer does not constitute an endorsement, approval or review of these
        products or any claim, statement or opinion used in promotion of these products.</p>
      <p>For Product Support, please contact the vendor: <a href="mailto:support@soulmirrorreading.com">HERE</a></p>
      <p>For Order Support, please contact ClickBank: <a href="https://www.clkbank.com/" target="_blank"
          rel="noopener">HERE</a> or 1-800-390-6035</p>
      <p class="footer-links">
        <a href="/privacy-policy">Privacy Policy</a> &nbsp;·&nbsp;
        <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;·&nbsp;
        <a href="mailto:support@soulmirrorreading.com">Contact Us</a> &nbsp;·&nbsp;
        <a href="/refund-return-policy">Refund &amp; Return Policy</a>
      </p>
      <p>Copyright © 2026 Soul Mirror Reading. All Right Reserved.</p>
    </div>
  </footer>

  <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
  <script defer src="assets/sales.min.js?v=<?= htmlspecialchars((string) $jsVer, ENT_QUOTES) ?>"></script>

</body>

</html>
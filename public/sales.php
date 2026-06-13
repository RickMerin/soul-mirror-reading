<?php
declare(strict_types=1);
$cssPath = __DIR__ . '/assets/sales-bundle.min.css';
$cssVer = is_file($cssPath) ? filemtime($cssPath) : time();
$jsPath = __DIR__ . '/assets/sales.min.js';
$jsVer = is_file($jsPath) ? filemtime($jsPath) : time();
$guardPath = __DIR__ . '/assets/vturb-player-guard.min.js';
$guardVer = is_file($guardPath) ? filemtime($guardPath) : time();
$checkoutUrl = 'https://rebornf.pay.clickbank.net/?cbitems=smr-1&template=order-3&cbfid=63457&exitoffer=exit-1';
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
    @media (max-width: 900px) {
      .testi-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    @media (max-width: 600px) {
      .testi-grid {
        grid-template-columns: 1fr;
        gap: 14px;
      }
    }

    .testi-card {
      background: rgba(255, 255, 255, 0.829);
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
      color: var(--panel-text);
      line-height: 1.2;
    }

    .testi-avatar-meta .testi-meta {
      font-size: 12px;
      color: var(--panel-text-soft);
      margin-top: 3px;
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
    <strong>Check Your Inbox:</strong><span class="notice-dot">·</span>Your 3-Card Personal Tarot Reading Is On Its Way.<span class="notice-dot">·</span><strong>While You Wait, Read This Carefully.</strong>
  </div>

  <main id="main-content">

  <!-- ══ VSL SECTION ══ -->
  <section class="vsl-section">
    <div class="wrap">
      <h1 class="vsl-headline">One Card Fell Out Before<br><em>The Reading Even Began</em></h1>
      <p class="vsl-sub">Face-up. Uninvited. A direct message from the universe that refuses to be ignored. Yours revealed the one belief running silently behind all three of your mirrors. <strong>Your Mirror Block.</strong></p>

      <div class="video-frame">
        <vturb-smartplayer id="vid-6a03e48213e119642182af7b"
          style="display: block; margin: 0 auto; width: 100%;"
          data-autoplay="false" autoplay="false"
          data-muted="true" muted="true"
          data-smr-guard="1"></vturb-smartplayer>
        <script type="text/javascript">
          var s = document.createElement("script");
          s.src = "https://scripts.converteai.net/6fa5f75c-723e-4301-a459-76c14edde081/players/6a03e48213e119642182af7b/v4/player.js";
          s.async = !0;
          document.head.appendChild(s);
        </script>
        <script defer src="assets/vturb-player-guard.min.js?v=<?= htmlspecialchars((string) $guardVer, ENT_QUOTES) ?>"></script>
      </div>
 

      <div class="badges">
        <span class="badge">🔒 &nbsp; Secure &amp; Private</span>
        <span class="badge">⭐ &nbsp; 4,800+ Readings</span>
        <span class="badge">🌙 &nbsp; 90-Day Guarantee</span>
      </div>

      <!-- <div style="margin-top:28px;">
        <button type="button" class="read-toggle" aria-expanded="false">
          ☽ &nbsp; Prefer to read instead? &nbsp; ☾
        </button>
      </div> -->
    </div>
  </section>

  <div id="read-version" style="display:none;">

    <!-- ══ LUNA'S STORY ══ -->
    <section class="story-section section">
      <div class="wrap-narrow">
        <p>You can feel it. Not all the time — but in the quiet moments. When the bank balance dips again for no clear
          reason. When a relationship that started with promise fades into the same tired distance. When you sit down to
          do the work that is supposed to matter and your body fills with fog instead of fire.</p>
        <p>Three different areas of your life. Three different kinds of pain. And yet they all have the same texture.
          The same heaviness. The same strange timing.</p>
        <p>Something keeps pulling you back to the same place.</p>
        <div class="story-pull">It is not that you are doing the wrong things. It is that something beneath the
          surface keeps rearranging the results.</div>
        <p>You have tried to fix this. Of course you have. You are not someone who sits and waits. You have done the
          inner work — the therapy, the journaling, the intention-setting, the energy clearing. Maybe a course. Maybe
          several. Maybe an entire bookshelf of spiritual development sitting behind you right now.</p>
        <p>And some of it helped. For a while.</p>
        <p>The money came in — and then bled out just as fast. The relationship softened — and then hardened in exactly
          the same spot. The clarity arrived — and dissolved within weeks, replaced by the same confusion wearing a
          different outfit.</p>
        <p>You started to wonder if the problem was you. If some people are just built to struggle in certain areas. If
          maybe you missed the window. If this is simply what your life looks like.</p>
        <p>I need you to hear this: that thought is the pattern talking. Not the truth.</p>
        <p>My name is Luna Ross. I have been reading for 11 years. I have sat with more than 4,800 people across six
          countries, and I have heard some version of what I just described from nearly every single one of them.</p>
        <p>Not the same words. But the same feeling. The same quiet bewilderment. <em>I have done everything I was
            supposed to do. Why does nothing hold?</em></p>
        <p>For the first several years, I treated each problem as its own thread. Love readings for love. Career
          readings for purpose. Abundance work for money. That is how I was trained. That is what every reading system
          teaches.</p>
        <p>And my clients would leave those sessions relieved. Hopeful. Sometimes they cried from the recognition alone.
          They would send me messages a few weeks later saying things had shifted — a new opportunity appeared, a
          conversation they had been avoiding finally happened, the weight lifted.</p>
        <p>Then, quietly, it would come back.</p>
        <p>Not the same problem. A different version of the same problem. As if the pattern had simply moved to a new
          address.</p>
        <div class="story-pull">The relationship improved. Then the money collapsed. The career took off. Then the
          health unravelled. As if something was regulating how much good they were allowed to hold at once.</div>
        <p>I saw this hundreds of times. Thousands. A person would break through in one area and watch another area fall
          apart in the same week. It was too consistent to be coincidence. Too patterned to be bad luck.</p>
        <p>So I stopped looking at the three areas separately. I started asking a different question.</p>
        <div class="story-pull">What if it is not three problems? What if it is one belief wearing three
          different masks?</div>
        <p>What if money, love, and purpose are all being shaped by the same thing — one single pattern, running
          beneath conscious awareness, silently deciding what you are allowed to receive and what gets pulled away?</p>
        <p>That question changed every reading I have done since.</p>
        <p>I call it a Mirror Block. Not because it reflects who you are — but because it reflects who you were taught
          you had to be, long before you could question it.</p>
        <p>A Mirror Block is a core belief — usually formed in childhood, often before language — that installed itself
          as a survival mechanism. It was useful once. It protected you from something real. But it never updated. And
          now it runs beneath every decision, every relationship, every financial pattern, every moment you reach for
          something bigger and feel an invisible hand pull you back.</p>
        <p>It is not a flaw. It is not a failing. It is a programme that was never meant to last this long.</p>
        <p>After years of mapping these patterns across thousands of readings, I identified four distinct types. Every
          person I have ever read for carries one. Most carry it their entire life without ever knowing it has a name.
        </p>
        <div class="story-pull">There are four types of Mirror Block. Each one shapes your life differently. Each
          one requires a completely different approach to clear.</div>
        <p><strong>The Not Yet Ready Block.</strong> You live in permanent preparation. Success is always coming — next
          month, next year, after one more course, one more healing, one more sign. You wait. The waiting feels
          responsible. But nothing arrives because the block has convinced you that you are not ready to receive it. You
          have been ready for years.</p>
        <p><strong>The Waiting for the Good Thing to End Block.</strong> When something good happens, your first instinct
          is not joy — it is anxiety. How long until this falls apart? You monitor every relationship for cracks. You
          spend the raise before it hits your account. You sabotage quietly, unconsciously, because losing something on
          your own terms feels safer than having it taken from you.</p>
        <p><strong>The Too Much Block.</strong> You are overwhelmed not by failure but by possibility. Every door opens
          and you freeze. Every opportunity brings paralysis. You cannot choose because choosing means losing the others,
          and somewhere inside you learned that wanting too much was dangerous. So you receive nothing fully.</p>
        <p><strong>The Not Enough Block.</strong> Underneath everything, a voice says you do not deserve this. Not the
          money. Not the love. Not the recognition. You work harder than anyone around you and still feel like you are
          getting away with something. Receiving feels fraudulent. So you keep yourself just below the line of what you
          actually want.</p>
        <p>One of these has been running your life. Quietly. For years. Across every area at once.</p>
        <p>And the reason nothing you have tried has worked permanently is simple: you have been treating the symptoms in
          isolation. The money work. The relationship work. The purpose work. All separate. All surface-level. None of
          them touched the root.</p>
        <p>The shift is not dramatic. That is the first thing people tell me.</p>
        <p>There is no lightning bolt. No overnight transformation. What happens is quieter and far more permanent.</p>
        <p>Someone who spent three years stuck in the same income range — give or take a few hundred — sees their Not
          Enough Block clearly for the first time. Within weeks, they stop unconsciously turning down the work that pays
          more. They stop apologising for their rates. The change is not forced. It just stops being blocked.</p>
        <div class="story-pull">The changes were not loud. They were structural. Like removing a dam nobody knew
          was there and watching the water finally move.</div>
        <p>A person carrying the Waiting for the Good Thing to End Block reads their Soul Mirror Reading on a Tuesday
          evening and sits with it for three days without telling anyone. On the fourth day, they stop scanning their
          partner's face for signs of leaving. They do not decide to stop. They simply notice they have stopped. The
          vigilance that has drained them for a decade loosens its grip — not because they fought it, but because they
          finally saw where it was anchored.</p>
        <p>This is what I mean when I say the reading does not describe your life. It shows you what has been shaping
          it.</p>
        <p>The Soul Mirror Reading uses 3 cards — not to predict your future, but to reveal the single belief pattern
          that has been organising your past.</p>
        <p>Card one reflects love and connection. Card two reflects life path and purpose. Card three reflects abundance
          and material flow. But I do not read them separately. I read the space between them — the place where one
          pattern shows up wearing three different masks.</p>
        <p>That pattern is your Mirror Block. And once I name it for you — once you see it clearly, in your own life, in
          your own words — something fundamental shifts in the way you relate to all three areas.</p>
        <p>Not because I tell you what to do. Because the pattern can no longer operate in the dark.</p>
        <p>This is the reading that shows you the root. One root. Running love, money, and purpose at the same time. Once
          you see it, you cannot unsee it. And the patterns that have recycled for years begin to lose their hold.</p>
        <div class="story-pull">You have spent years working on three separate problems. There was only ever one.
        </div>
        <p>The cards you chose minutes ago are still charged with the energy of that moment. That is not a metaphor — the
          combination you selected is specific to where you are right now, and it will not carry the same precision a
          week from now. This is why I ask people to move forward the same day they pick their cards.</p>
        <p>Your reading takes me 12 to 24 hours to complete. Every word is written by hand, specific to your card
          combination and your Mirror Block type. It is not a template. It is not generated. It is the reading I would
          give you if you were sitting across from me.</p>
        <p>One reading. One root belief. And the clarity to finally stop treating symptoms and start dissolving the
          source.</p>
        <p>Your Soul Mirror Reading begins the moment you claim it. I will be the one writing it. By hand, for you,
          specific to the combination you chose today.</p>
        <p>If something in what I just said felt familiar, that is not coincidence. That is the pattern recognising
          itself. And that is exactly where the work begins.</p>
        <div class="story-pull">Scroll down. I will meet you on the other side.</div>

      </div>
    </section>

    <!-- <div style="text-align:center; padding-bottom:48px;">
      <button type="button" class="read-toggle-collapse">
        ☽ &nbsp; Collapse this section &nbsp; ☾
      </button>
    </div> -->

  </div>
  <!-- /#read-version -->

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

  <!-- Testimonial Rebecca Hartley -->
  <section class="testimonials-section section js-reveal">
    <div class="wrap-narrow">
      <div class="testi-card">
        <div class="testi-avatar-row">
          <picture>
            <source type="image/webp" srcset="frontend/images/sales/rebecca-hartley.webp">
            <img class="testi-avatar" src="frontend/images/sales/rebecca-hartley.png" alt="Rebecca Hartley" decoding="async"
              loading="lazy">
          </picture>
          <div class="testi-avatar-meta">
            <div class="testi-name">Rebecca Hartley</div>
            <div class="testi-meta">Age 47 &nbsp;·&nbsp; Realtor</div>
          </div>
        </div>
        <div class="testi-stars">★★★★★</div>
        <p class="testi-quote">&ldquo;Within two weeks of reading mine and following the ritual, three things shifted that had been stuck for years. My income jumped from $4,200 to $7,800 a month because I finally stopped flinching when clients pushed back on my rates. I ended a six-year relationship that had been quietly shrinking me. And I finished the book I had been writing for nine years. The Mirror Block Luna named in mine was the same wall I had been working around in love, in money, in my work, without ever realizing it was the same wall. Once I saw it, I could not un-see it. Everything started moving.&rdquo;</p>
      </div>
    </div>
  </section>

  <!-- ══ VIP SECTION ══ -->
  <section class="vip-section section js-reveal">
    <div class="wrap">
      <div class="vip-box">
        <h2>Inside Your Soul Mirror Reading</h2>
        <picture>
          <source type="image/webp" srcset="frontend/images/sales/soul-mirror-reading.webp">
          <img src="frontend/images/sales/soul-mirror-reading.png" width="auto" height="300" alt="Soul Mirror Reading" class="vip-mockup"
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
      <h2 style="text-align: center;">Free Bonuses Included Today</h2>
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
              <source type="image/webp" srcset="frontend/images/sales/root-cause-reading-guide.webp">
              <img src="frontend/images/sales/root-cause-reading-guide.png" width="250" height="333" alt="Root Cause Reading Guide" decoding="async"
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
          <div class="bonus-img">
            <picture>
              <source type="image/webp" srcset="frontend/images/sales/mirror-clarity-meditation.webp">
              <img src="frontend/images/sales/mirror-clarity-meditation.png" width="250" height="333" alt="Mirror Clarity Meditation" decoding="async"
                loading="lazy">
            </picture>
          </div>
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
              <li><strong style="white-space: nowrap;">Bonus 1</strong> &mdash; Mirror Block Companion Guide ($67)</li>
              <li><strong style="white-space: nowrap;">Bonus 2</strong> &mdash; 21-Day Shift Tracker ($47)</li>
              <li><strong style="white-space: nowrap;">Bonus 3</strong> &mdash; Root Cause Reading Guide ($47)</li>
              <li><strong style="white-space: nowrap;">Bonus 4</strong> &mdash; Mirror Clarity Meditation audio ($37)</li>
            </ul>
            <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES) ?>" class="price-card-cta vip">Claim Your Soul Mirror Reading</a>
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

    <!-- Bundle Product Image -->
    <div class="bundle-product-image">
      <picture>
        <source type="image/webp" srcset="frontend/images/sales/bundle-product-image.webp">
        <img 
          src="frontend/images/sales/bundle-product-image.png"
          style="min-width:300px;max-width:50%;height:auto;object-fit:contain;display:block;margin:0 auto;"
          alt="Bundle Product Image"
          decoding="async"
          loading="lazy"
        >
      </picture>
    </div>

    <br>

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
      <a href="<?= htmlspecialchars((string) $checkoutUrl, ENT_QUOTES | ENT_HTML5, 'UTF-8') ?>" class="cta-btn">
        Claim Your Soul Mirror Reading
      </a>
 
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
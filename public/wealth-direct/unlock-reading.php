<?php
declare(strict_types=1);
$assetRoot = '../';
$cssPath = dirname(__DIR__) . '/assets/bundle.min.css';
$cssVer = is_file($cssPath) ? filemtime($cssPath) : time();
?>
<!DOCTYPE html>
<html lang="en" data-funnel-base="wealth-direct/">

<head>
  <meta name="google-adsense-account" content="ca-pub-7614509729474530">
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7614509729474530" crossorigin="anonymous"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Your Reading. Soul Mirror</title>
  <link rel="icon" type="image/svg+xml" href="<?= htmlspecialchars($assetRoot, ENT_QUOTES) ?>favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Crimson+Pro:ital,wght@0,300;0,400;1,300&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="<?= htmlspecialchars($assetRoot, ENT_QUOTES) ?>assets/bundle.min.css?v=<?= htmlspecialchars((string) $cssVer, ENT_QUOTES) ?>">
  <script>
    (function () {
      try {
        var K = 'soulMirrorReadingPick';
        function pickOk(raw) {
          try {
            var d = JSON.parse(raw);
            if (!d || d.v !== 1 || !Array.isArray(d.cards) || d.cards.length !== 3) return false;
            for (var i = 0; i < 3; i++) {
              var c = d.cards[i];
              if (!c || typeof c.slug !== 'string' || !c.slug) return false;
              if (typeof c.name !== 'string' || !c.name) return false;
              var id = +c.id;
              if (id !== (id | 0) || id < 1 || id > 78) return false;
            }
            return true;
          } catch (e) { return false; }
        }
        document.documentElement.classList.add(
          pickOk(sessionStorage.getItem(K)) ? 'unlock-ok' : 'unlock-bad'
        );
      } catch (e) {
        document.documentElement.classList.add('unlock-bad');
      }
    })();
  </script>

  <script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "wq82rtc2gf");
  </script>

  <style id="optin-focus">
    .dream-bg::after{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 50% 24%,rgba(46,27,105,.55),rgba(10,7,22,.86) 78%);}
    .unlock-main .unlock-panel{background:rgba(255,253,249,.975);border:1px solid rgba(196,160,82,.5);border-radius:18px;box-shadow:0 26px 70px rgba(0,0,0,.55);padding:26px 20px 22px;max-width:430px;margin:26px auto;}
    .chosen-recap{gap:8px;margin-bottom:22px;}
    .recap-card img{max-width:62px;height:85px;}
    .recap-card{flex:46px;min-width:40px;max-width:66px;padding:6px 5px 5px;}
    .recap-label{font-size:9px;letter-spacing:.1em;}
    .recap-name{font-size:11px;}
  </style>
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

  <div class="unlock-blocked unlock-page" aria-labelledby="unlockBlockedTitle">
    <div class="unlock-blocked-inner">
      <p class="unlock-eyebrow">Soul Mirror Reading</p>
      <h1 id="unlockBlockedTitle">Choose your three cards first</h1>
      <p class="unlock-blocked-copy">The mirror only opens after you have drawn all three cards on the reading page.</p>
      <a class="unlock-back-btn" href="index.php">&larr; Back to card reading</a>
    </div>
  </div>

  <main class="unlock-main unlock-page" aria-labelledby="unlockTitle">
    <div class="unlock-panel">
      <h1 id="unlockTitle">Your 3 Cards Are Locked In.</h1>
      <p class="form-sub">Love. Life. Wealth. Your three cards have already chosen themselves, and together they point to one quiet pattern sitting beneath all three. Add your first name to see what they reveal.</p>

      <div class="chosen-recap" id="chosenRecap"></div>

      <div class="form-box">
        <form id="readingForm" novalidate>
          <div class="form-group">
            <label for="inputName">First Name</label>
            <input type="text" id="inputName" placeholder="Enter Your First Name..." required
              autocomplete="given-name" />
          </div>
          <div class="error-msg" id="errorMsg" role="alert" aria-live="assertive"></div>
          <button type="submit" class="submit-btn is-invalid" id="submitBtn" disabled>
            Reveal My 3 Cards &nbsp;&rarr;
          </button>
          <p class="form-privacy">Your reading opens on the very next page.</p>
        </form>
      </div>
    </div>
  </main>

  <footer class="site-footer wavy">
    <p class="site-footer-links">
      <a href="/privacy-policy">Privacy Policy</a> &nbsp;&middot;&nbsp;
      <a href="/terms-conditions">Terms &amp; Conditions</a> &nbsp;&middot;&nbsp;
      <a href="mailto:support@soulmirrorreading.com">Contact Us</a> &nbsp;&middot;&nbsp;
      <a href="/refund-return-policy">Refund &amp; Return Policy</a>
    </p>
    <p>Copyright &copy; 2026 Soul Mirror Reading. All Right Reserved.</p>
  </footer>

  <script>
    (function () {
      var IMG = 'https://www.trustedtarot.com/img/cards/';
      var SLOTS = ['Your Love', 'Your Life', 'Your Wealth'];
      var KEYS = ['love', 'life', 'wealth'];
      var NAME_RE = /^[\p{L}\p{M}][\p{L}\p{M}\s'.-]*$/u;

      function getPick() {
        try {
          var d = JSON.parse(sessionStorage.getItem('soulMirrorReadingPick'));
          if (!d || d.v !== 1 || !Array.isArray(d.cards) || d.cards.length !== 3) return null;
          return d.cards;
        } catch (e) { return null; }
      }

      var cards = getPick();
      if (!cards) return; // pick gate already showed the "choose your cards" state

      var recap = document.getElementById('chosenRecap');
      if (recap) {
        recap.innerHTML = cards.map(function (c, i) {
          return '<div class="recap-card"><img src="' + IMG + encodeURIComponent(c.slug) + '.png" alt="' + c.name + '" />' +
            '<div class="recap-card-info"><div class="recap-label">' + SLOTS[i] + '</div>' +
            '<div class="recap-name">' + c.name + '</div></div></div>';
        }).join('');
      }

      var form = document.getElementById('readingForm');
      var nameInput = document.getElementById('inputName');
      var btn = document.getElementById('submitBtn');
      var err = document.getElementById('errorMsg');

      function nameState() {
        var v = (nameInput.value || '').trim();
        if (v.length < 2 || v.length > 120) return 'short';
        if (!NAME_RE.test(v)) return 'chars';
        return 'ok';
      }

      function sync() {
        var s = nameState();
        btn.disabled = (s !== 'ok');
        btn.classList.toggle('is-invalid', (s !== 'ok'));
        if (s === 'chars') {
          err.textContent = 'Your First Name Only Please (letters only)';
          err.classList.add('visible');
        } else {
          err.textContent = '';
          err.classList.remove('visible');
        }
      }

      nameInput.addEventListener('input', sync);
      nameInput.addEventListener('change', sync);
      sync();

      form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (nameState() !== 'ok') return;
        var params = new URLSearchParams();
        params.set('first_name', nameInput.value.trim());
        cards.forEach(function (c, i) {
          params.set(KEYS[i] + '_card', c.name);
          params.set(KEYS[i] + '_card_image', IMG + c.slug + '.png');
        });
        window.location.href = 'sales.php?' + params.toString();
      });
    })();
  </script>
</body>

</html>

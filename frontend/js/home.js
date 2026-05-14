import { initDreamBackground } from "./lib/dream-background.js";
import { funnelUrl } from "./lib/funnel-base.js";
import { READING_PICK_KEY, READING_PICK_VERSION } from "./lib/reading-pick.js";

// ── Card data — 78 cards ────────────────────────────────────────────────────
const CARD_SLUGS = [
  "the-fool",
  "the-magician",
  "the-high-priestess",
  "the-empress",
  "the-emperor",
  "the-heirophant",
  "the-lovers",
  "the-chariot",
  "strength",
  "the-hermit",
  "wheel-of-fortune",
  "justice",
  "the-hanged-man",
  "death",
  "temperance",
  "the-devil",
  "the-tower",
  "the-star",
  "the-moon",
  "the-sun",
  "judgement",
  "the-world",
  "ace-of-wands",
  "two-of-wands",
  "three-of-wands",
  "four-of-wands",
  "five-of-wands",
  "six-of-wands",
  "seven-of-wands",
  "eight-of-wands",
  "nine-of-wands",
  "ten-of-wands",
  "page-of-wands",
  "knight-of-wands",
  "queen-of-wands",
  "king-of-wands",
  "ace-of-cups",
  "two-of-cups",
  "three-of-cups",
  "four-of-cups",
  "five-of-cups",
  "six-of-cups",
  "seven-of-cups",
  "eight-of-cups",
  "nine-of-cups",
  "ten-of-cups",
  "page-of-cups",
  "knight-of-cups",
  "queen-of-cups",
  "king-of-cups",
  "ace-of-swords",
  "two-of-swords",
  "three-of-swords",
  "four-of-swords",
  "five-of-swords",
  "six-of-swords",
  "seven-of-swords",
  "eight-of-swords",
  "nine-of-swords",
  "ten-of-swords",
  "page-of-swords",
  "knight-of-swords",
  "queen-of-swords",
  "king-of-swords",
  "ace-of-pentacles",
  "two-of-pentacles",
  "three-of-pentacles",
  "four-of-pentacles",
  "five-of-pentacles",
  "six-of-pentacles",
  "seven-of-pentacles",
  "eight-of-pentacles",
  "nine-of-pentacles",
  "ten-of-pentacles",
  "page-of-pentacles",
  "knight-of-pentacles",
  "queen-of-pentacles",
  "king-of-pentacles",
];

const CARD_NAMES = [
  "The Fool",
  "The Magician",
  "The High Priestess",
  "The Empress",
  "The Emperor",
  "The Hierophant",
  "The Lovers",
  "The Chariot",
  "Strength",
  "The Hermit",
  "Wheel of Fortune",
  "Justice",
  "The Hanged Man",
  "Death",
  "Temperance",
  "The Devil",
  "The Tower",
  "The Star",
  "The Moon",
  "The Sun",
  "Judgement",
  "The World",
  "Ace of Wands",
  "Two of Wands",
  "Three of Wands",
  "Four of Wands",
  "Five of Wands",
  "Six of Wands",
  "Seven of Wands",
  "Eight of Wands",
  "Nine of Wands",
  "Ten of Wands",
  "Page of Wands",
  "Knight of Wands",
  "Queen of Wands",
  "King of Wands",
  "Ace of Cups",
  "Two of Cups",
  "Three of Cups",
  "Four of Cups",
  "Five of Cups",
  "Six of Cups",
  "Seven of Cups",
  "Eight of Cups",
  "Nine of Cups",
  "Ten of Cups",
  "Page of Cups",
  "Knight of Cups",
  "Queen of Cups",
  "King of Cups",
  "Ace of Swords",
  "Two of Swords",
  "Three of Swords",
  "Four of Swords",
  "Five of Swords",
  "Six of Swords",
  "Seven of Swords",
  "Eight of Swords",
  "Nine of Swords",
  "Ten of Swords",
  "Page of Swords",
  "Knight of Swords",
  "Queen of Swords",
  "King of Swords",
  "Ace of Pentacles",
  "Two of Pentacles",
  "Three of Pentacles",
  "Four of Pentacles",
  "Five of Pentacles",
  "Six of Pentacles",
  "Seven of Pentacles",
  "Eight of Pentacles",
  "Nine of Pentacles",
  "Ten of Pentacles",
  "Page of Pentacles",
  "Knight of Pentacles",
  "Queen of Pentacles",
  "King of Pentacles",
];

const CARD_SUITS = [
  ...Array(22).fill("Major Arcana"),
  ...Array(14).fill("Wands"),
  ...Array(14).fill("Cups"),
  ...Array(14).fill("Swords"),
  ...Array(14).fill("Pentacles"),
];

const IMG_BASE = "https://www.trustedtarot.com/img/cards/";

const PROMPTS = [
  "Take a breath. Think of what feels unresolved right now.<br>Hold it in your heart — and <em>choose your first card.</em>",
  "Stay with that feeling. Don't overthink it.<br><em>Choose the card you're drawn to.</em>",
  "Last one. Trust what calls to you —<br><em>not what you think you should pick.</em>",
  "Your three cards have been drawn.<br><em>Your reading is being prepared…</em>",
];

const FLOAT_CLASSES = ["float-0", "float-1", "float-2"];

// ── State ───────────────────────────────────────────────────────────────────
let chosen = 0;
let animating = false;
let remaining = [];
let drawnCards = [];
const TOTAL_CARDS_TO_CHOOSE = 3;
function updateCardsRemainingText() {
  const label = document.getElementById("cardsRemainingText");
  if (!label) return;
  const left = Math.max(TOTAL_CARDS_TO_CHOOSE - chosen, 0);
  const unit = left === 1 ? "more" : "more";
  label.textContent = `Choose your cards below · ${left} ${unit}`;
}

// ── Shuffle ─────────────────────────────────────────────────────────────────
function shuffle(arr) {
  const a = [...arr];
  for (let i = a.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [a[i], a[j]] = [a[j], a[i]];
  }
  return a;
}

// ── Build deck ───────────────────────────────────────────────────────────────
let deckSpread = false;

/**
 * Matches `70-responsive.css` @media (max-width: 720px) — card-slot 62px vs 86px.
 * Mobile uses a compact arc (radius + angle span tuned per card count) so rotated cards stay inside the viewport; `10-base.css` sets `overflow-x: hidden` on html/body.
 * Wider angle + slightly larger radius on small screens so taps are not cramped.
 */
function getDeckArcLayout() {
  const mobile =
    typeof window.matchMedia === "function" &&
    window.matchMedia("(max-width: 720px)").matches;
  if (mobile) {
    return {
      N: 14,
      radius: 138,
      halfCard: 31,
      angleSpan: 78,
      stackSpread: 1.68,
    };
  }
  return {
    N: 15,
    radius: 280,
    halfCard: 43,
    angleSpan: 88,
    stackSpread: 1.8,
  };
}

function buildDeck() {
  remaining = shuffle(
    CARD_SLUGS.map((s, i) => ({
      id: i + 1,
      slug: s,
      name: CARD_NAMES[i],
      suit: CARD_SUITS[i],
    })),
  );

  const arc = document.getElementById("deckArc");
  const { N, radius, halfCard, angleSpan, stackSpread } = getDeckArcLayout();
  const halfArc = angleSpan / 2;
  const angles = Array.from(
    { length: N },
    (_, i) => -halfArc + (angleSpan / (N - 1)) * i,
  );
  const slots = [];

  angles.forEach((angle, i) => {
    const rad = (angle * Math.PI) / 180;
    const cx = Math.sin(rad) * radius;
    const cy = radius - Math.cos(rad) * radius;

    const slot = document.createElement("div");
    slot.className = "card-slot";
    slot.dataset.i = String(i);

    const stackRot = (i - (N - 1) / 2) * stackSpread;
    slot.style.cssText = `
      left: calc(50% - ${halfCard}px);
      bottom: 10px;
      transform: rotate(${stackRot}deg);
      transform-origin: bottom center;
      transition: none;
    `;

    slot.dataset.finalLeft = `calc(50% + ${cx - halfCard}px)`;
    slot.dataset.finalBottom = `${-cy + 10}px`;
    slot.dataset.finalAngle = String(angle);

    slot.innerHTML = `
      <div class="card-inner">
        <div class="cf cb">
          <div class="cb-pattern"></div>
          <div class="ms"></div>
          <div class="mv"></div>
          <div class="msh"></div>
          <div class="mg"><span>☽</span><span style="font-size:12px;opacity:0.6">✦</span></div>
        </div>
      </div>`;

    slot.addEventListener("click", () => {
      if (
        !deckSpread ||
        animating ||
        chosen >= TOTAL_CARDS_TO_CHOOSE ||
        slot.classList.contains("done") ||
        slot.classList.contains("spent")
      )
        return;
      animating = true;
      const idx = Math.floor(Math.random() * remaining.length);
      const card = remaining.splice(idx, 1)[0];
      flyCard(slot, card, parseFloat(slot.dataset.finalAngle), chosen);
    });

    arc.insertBefore(slot, document.getElementById("shufflePrompt"));
    slots.push(slot);
  });

  document
    .getElementById("shufflePrompt")
    .addEventListener("click", () => spreadDeck(slots));
}

function spreadDeck(slots) {
  if (deckSpread) return;
  deckSpread = true;

  document.getElementById("shufflePrompt").classList.add("hidden");
  const instr = document.getElementById("deckInstruction");
  if (instr) instr.classList.add("hidden");

  slots.forEach((slot, i) => {
    setTimeout(() => {
      slot.style.transition =
        "left 0.65s cubic-bezier(0.22,1,0.36,1), bottom 0.65s cubic-bezier(0.22,1,0.36,1), transform 0.65s cubic-bezier(0.22,1,0.36,1), filter 0.65s ease";
      slot.style.left = slot.dataset.finalLeft;
      slot.style.bottom = slot.dataset.finalBottom;
      slot.style.transform = `rotate(${slot.dataset.finalAngle}deg)`;
      slot.classList.add("revealed");
    }, i * 40);
  });
}

// ── Fly animation ────────────────────────────────────────────────────────────
function flyCard(slot, card, angle, destIdx) {
  const inner = slot.querySelector(".card-inner");
  const srcRect = inner.getBoundingClientRect();
  const destSlot = document.getElementById(`cs${destIdx}`);
  const destRect = destSlot.getBoundingClientRect();
  const FW = 164,
    FH = 272;
  const destCx = destRect.left + destRect.width / 2;
  const destCy = destRect.top + destRect.height / 2;

  slot.classList.add("done");

  if (destIdx === 0) {
    const sub = document.querySelector(".header-sub");
    if (sub) sub.style.opacity = "0";
    setTimeout(() => {
      if (sub) sub.style.display = "none";
      document.getElementById("chosenZone").classList.add("visible");
      document.getElementById("pickerDivider").classList.add("visible");
    }, 500);
  }

  const fly = document.createElement("div");
  fly.className = "flying-card";
  fly.style.cssText = `
    left:${srcRect.left}px; top:${srcRect.top}px;
    width:${srcRect.width}px; height:${srcRect.height}px;
    transform: rotate(${angle}deg);
    transform-style: preserve-3d;
  `;

  fly.innerHTML = `
    <div class="fly-back">
      <div class="cb-pattern"></div>
      <div class="ms"></div><div class="mv"></div>
      <div class="mg">✦</div>
    </div>
    <div class="fly-front">
      <img src="${IMG_BASE}${card.slug}.png" alt="${card.name}" />
    </div>`;

  document.body.appendChild(fly);

  requestAnimationFrame(() => {
    fly.style.transition = "left .28s ease, top .28s ease, transform .28s ease";
    fly.style.top = srcRect.top - 50 + "px";
    fly.style.transform = "rotate(0deg) scale(1.08)";
  });

  setTimeout(() => {
    fly.style.transition =
      "left .5s cubic-bezier(.4,0,.2,1), top .5s cubic-bezier(.4,0,.2,1), width .5s ease, height .5s ease, transform .5s ease";
    fly.style.left = destCx - FW / 2 + "px";
    fly.style.top = destCy - FH / 2 - 20 + "px";
    fly.style.width = FW + "px";
    fly.style.height = FH + "px";
    fly.style.transform = "rotate(0deg) scale(1)";
  }, 250);

  setTimeout(() => {
    fly.style.transition = "transform .65s cubic-bezier(.4,0,.2,1)";
    fly.style.transform = "rotateY(180deg)";
  }, 780);

  setTimeout(() => {
    fly.remove();
    landCard(destSlot, card, destIdx);
    drawnCards[destIdx] = card;
    chosen++;

    if (chosen === TOTAL_CARDS_TO_CHOOSE) {
      document
        .querySelectorAll(".card-slot:not(.done)")
        .forEach((s) => s.classList.add("spent"));
    }

    const prompt = document.getElementById("smPrompt");
    prompt.style.opacity = "0";
    setTimeout(() => {
      prompt.innerHTML = PROMPTS[Math.min(chosen, 3)];
      prompt.style.opacity = "1";
    }, 280);

    updateCardsRemainingText();

    if (chosen === TOTAL_CARDS_TO_CHOOSE) {
      setTimeout(goToUnlockPage, 1000);
    }

    animating = false;
  }, 1450);
}

// ── Land card into slot ──────────────────────────────────────────────────────
function landCard(slot, card, idx) {
  const pc = document.getElementById(`pc${idx}`);
  pc.innerHTML = `
    <img src="${IMG_BASE}${card.slug}.png" alt="${card.name}" />
    <div class="placed-card-overlay">
      <div class="placed-name">${card.name}</div>
      <div class="placed-suit">${card.suit}</div>
    </div>`;
  pc.classList.add(FLOAT_CLASSES[idx]);
  slot.classList.add("filled");
  requestAnimationFrame(() => setTimeout(() => pc.classList.add("show"), 30));
  slot.querySelector(".cs-inner").style.display = "none";
}

function goToUnlockPage() {
  try {
    sessionStorage.setItem(
      READING_PICK_KEY,
      JSON.stringify({ v: READING_PICK_VERSION, cards: drawnCards }),
    );
  } catch {
    return;
  }
  window.location.assign(
    funnelUrl("unlock-reading.php"),
  );
}

initDreamBackground({
  sparkleMobile: 48,
  sparkleDesktop: 78,
  shootingMobile: 4,
  shootingDesktop: 8,
  glintChance: 0.16,
  sizeRange: 2.4,
  travelBase: 180,
  travelRange: 170,
  lenBase: 120,
  lenRange: 130,
  pointerParallax: true,
});

buildDeck();
updateCardsRemainingText();

import {
  CARD_SLUGS,
  CARD_NAMES,
  CARD_SUITS,
  IMG_BASE,
  PROMPTS,
  SLOT_LABELS,
  FLOAT_CLASSES,
} from "./cards-data.js";

const KIT_EMBED_ID = "kitLeadEmbed";
const KIT_EMBED_INPUT_WAIT_MS = 4000;

/**
 * Loads your Kit form script only when the server exposes subdomain + form UID (same account as KIT_API_KEY).
 */
async function injectKitEmbedIfConfigured() {
  const root = document.getElementById(KIT_EMBED_ID);
  if (!root || root.querySelector("script[data-kit-embed]")) return;

  let cfg;
  try {
    const res = await fetch("/api/public-config");
    cfg = await res.json();
  } catch {
    return;
  }

  const sub = cfg.kitEmbedSubdomain && String(cfg.kitEmbedSubdomain).trim();
  const uid = cfg.kitEmbedFormUid && String(cfg.kitEmbedFormUid).trim();
  if (!sub || !uid) return;

  const s = document.createElement("script");
  s.async = true;
  s.src = `https://${sub}.kit.com/${uid}/index.js`;
  s.setAttribute("data-uid", uid);
  s.setAttribute("data-kit-embed", "1");
  root.appendChild(s);
}

/**
 * @param {ParentNode} root
 * @param {string} selector
 * @param {number} timeoutMs
 * @returns {Promise<Element>}
 */
function waitForSelector(root, selector, timeoutMs) {
  return new Promise((resolve, reject) => {
    const start = Date.now();
    const id = setInterval(() => {
      const el = root.querySelector(selector);
      if (el) {
        clearInterval(id);
        resolve(el);
        return;
      }
      if (Date.now() - start >= timeoutMs) {
        clearInterval(id);
        reject(new Error(`Kit embed: timeout waiting for ${selector}`));
      }
    }, 50);
  });
}

/**
 * Fills the hidden Kit embed fields and clicks Subscribe (matches Formkit field names).
 * Best-effort only; /api/reading already persists the lead server-side.
 *
 * @param {{ name: string; email: string; dob: string; gender: string }} fields
 */
async function submitKitEmbedHidden(fields) {
  const root = document.getElementById(KIT_EMBED_ID);
  if (!root) return;

  let emailInput;
  try {
    emailInput = await waitForSelector(
      root,
      'input[name="email_address"]',
      KIT_EMBED_INPUT_WAIT_MS,
    );
  } catch {
    return;
  }

  const scope =
    emailInput.closest("form") ||
    emailInput.closest("[data-element='fields']")?.parentElement ||
    root;

  const first = scope.querySelector('input[name="fields[first_name]"]');
  const genderEl = scope.querySelector('input[name="fields[gender]"]');
  const dobEl = scope.querySelector('input[name="fields[date_of_birth]"]');

  if (first) first.value = fields.name;
  emailInput.value = fields.email;
  if (genderEl) genderEl.value = fields.gender;
  if (dobEl) dobEl.value = fields.dob;

  const submit =
    scope.querySelector('[data-element="submit"]') ||
    scope.querySelector("button.formkit-submit");

  if (submit) submit.click();

  await new Promise((r) => setTimeout(r, 500));
}

/**
 * 3-card picker UI, animations, and lead form submission.
 */
class TarotReadingApp {
  constructor() {
    this.chosen = 0;
    this.animating = false;
    /** @type {Array<{ id: number; slug: string; name: string; suit: string }>} */
    this.remaining = [];
    /** @type {Array<{ id: number; slug: string; name: string; suit: string } | undefined>} */
    this.drawnCards = [];
    this.deckSpread = false;
  }

  shuffle(arr) {
    const a = [...arr];
    for (let i = a.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
  }

  buildDeck() {
    this.remaining = this.shuffle(
      CARD_SLUGS.map((s, i) => ({
        id: i + 1,
        slug: s,
        name: CARD_NAMES[i],
        suit: CARD_SUITS[i],
      })),
    );

    const arc = document.getElementById("deckArc");
    const N = 15;
    const angles = Array.from(
      { length: N },
      (_, i) => -44 + (88 / (N - 1)) * i,
    );
    const radius = 280;
    const slots = [];

    angles.forEach((angle, i) => {
      const rad = (angle * Math.PI) / 180;
      const cx = Math.sin(rad) * radius;
      const cy = radius - Math.cos(rad) * radius;

      const slot = document.createElement("div");
      slot.className = "card-slot";
      slot.dataset.i = String(i);

      const stackRot = (i - (N - 1) / 2) * 1.8;
      slot.style.cssText = `
      left: calc(50% - 43px);
      bottom: 10px;
      transform: rotate(${stackRot}deg);
      transform-origin: bottom center;
      transition: none;
    `;

      slot.dataset.finalLeft = `calc(50% + ${cx - 43}px)`;
      slot.dataset.finalBottom = `${-cy + 10}px`;
      slot.dataset.finalAngle = String(angle);

      slot.innerHTML = `
      <div class="card-inner">
        <div class="cf cb">
          <div class="cb-pattern"></div>
          <div class="ms"></div>
          <div class="mv"></div>
          <div class="msh"></div>
          <div class="mg"><span>☽</span><span style="font-size:10px;opacity:0.6">✦</span></div>
        </div>
      </div>`;

      slot.addEventListener("click", () => {
        if (
          !this.deckSpread ||
          this.animating ||
          this.chosen >= 3 ||
          slot.classList.contains("done") ||
          slot.classList.contains("spent")
        )
          return;
        this.animating = true;
        const idx = Math.floor(Math.random() * this.remaining.length);
        const card = this.remaining.splice(idx, 1)[0];
        this.flyCard(
          slot,
          card,
          parseFloat(slot.dataset.finalAngle || "0"),
          this.chosen,
        );
      });

      arc.insertBefore(slot, document.getElementById("shufflePrompt"));
      slots.push(slot);
    });

    document
      .getElementById("shufflePrompt")
      .addEventListener("click", () => this.spreadDeck(slots));
  }

  spreadDeck(slots) {
    if (this.deckSpread) return;
    this.deckSpread = true;

    document.getElementById("shufflePrompt").classList.add("hidden");
    const instr = document.getElementById("deckInstruction");
    if (instr) instr.classList.add("hidden");

    slots.forEach((slot, i) => {
      setTimeout(() => {
        slot.style.transition =
          "left 0.65s cubic-bezier(0.22,1,0.36,1), bottom 0.65s cubic-bezier(0.22,1,0.36,1), transform 0.65s cubic-bezier(0.22,1,0.36,1), filter 0.65s ease";
        slot.style.left = slot.dataset.finalLeft || "";
        slot.style.bottom = slot.dataset.finalBottom || "";
        slot.style.transform = `rotate(${slot.dataset.finalAngle}deg)`;
        slot.classList.add("revealed");
      }, i * 40);
    });
  }

  flyCard(slot, card, angle, destIdx) {
    const inner = slot.querySelector(".card-inner");
    const srcRect = inner.getBoundingClientRect();
    const destSlot = document.getElementById(`cs${destIdx}`);
    const destRect = destSlot.getBoundingClientRect();
    const FW = 82;
    const FH = 136;
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
      fly.style.transition =
        "left .28s ease, top .28s ease, transform .28s ease";
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
      this.landCard(destSlot, card, destIdx);
      this.drawnCards[destIdx] = card;
      this.chosen++;

      if (this.chosen === 3) {
        document
          .querySelectorAll(".card-slot:not(.done)")
          .forEach((s) => s.classList.add("spent"));
      }

      const prompt = document.getElementById("smPrompt");
      prompt.style.opacity = "0";
      setTimeout(() => {
        prompt.innerHTML = PROMPTS[Math.min(this.chosen, 3)];
        prompt.style.opacity = "1";
      }, 280);

      if (this.chosen === 3) {
        setTimeout(() => this.showForm(), 1000);
      }

      this.animating = false;
    }, 1450);
  }

  landCard(slot, card, idx) {
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

  showForm() {
    const recap = document.getElementById("chosenRecap");
    recap.innerHTML = this.drawnCards
      .map(
        (card, i) => `
    <div class="recap-card">
      <img src="${IMG_BASE}${card.slug}.png" alt="${card.name}" />
      <div class="recap-card-info">
        <div class="recap-label">${SLOT_LABELS[i]}</div>
        <div class="recap-name">${card.name}</div>
      </div>
    </div>`,
      )
      .join("");

    const modal = document.getElementById("formModal");
    modal.classList.add("open");
    document.body.style.overflow = "hidden";
    setTimeout(() => document.getElementById("inputName").focus(), 100);
  }

  closeModal() {
    document.getElementById("formModal").classList.remove("open");
    document.body.style.overflow = "";
  }

  populateDobSelects() {
    const daySelect = document.getElementById("inputDobDay");
    const yearSelect = document.getElementById("inputDobYear");
    for (let d = 1; d <= 31; d++) {
      const o = document.createElement("option");
      o.value = String(d).padStart(2, "0");
      o.textContent = String(d);
      daySelect.appendChild(o);
    }
    const currentYear = new Date().getFullYear();
    for (let y = currentYear; y >= currentYear - 100; y--) {
      const o = document.createElement("option");
      o.value = String(y);
      o.textContent = String(y);
      yearSelect.appendChild(o);
    }
  }

  bindForm() {
    document.getElementById("inputName").addEventListener("input", function () {
      const greet = document.getElementById("nameGreet");
      greet.textContent = this.value.trim() ? this.value.trim() + "?" : "";
    });

    document
      .getElementById("readingForm")
      .addEventListener("submit", async (e) => {
        e.preventDefault();

        const name = document.getElementById("inputName").value.trim();
        const email = document.getElementById("inputEmail").value.trim();
        const dobMonth = document.getElementById("inputDobMonth").value;
        const dobDay = document.getElementById("inputDobDay").value;
        const dobYear = document.getElementById("inputDobYear").value;
        const dob =
          dobMonth && dobDay && dobYear
            ? `${dobMonth}/${dobDay}/${dobYear}`
            : "";
        const gender = document.getElementById("inputGender").value.trim();
        const btn = document.getElementById("submitBtn");
        const err = document.getElementById("errorMsg");

        if (!name || !email || !dob || !gender) return;

        btn.disabled = true;
        btn.textContent = "Reading the cards…";
        err.classList.remove("visible");

        try {
          const res = await fetch("/api/reading", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              name,
              email,
              dob,
              gender,
              card1: this.drawnCards[0].id,
              card2: this.drawnCards[1].id,
              card3: this.drawnCards[2].id,
              card1Name: this.drawnCards[0].name,
              card2Name: this.drawnCards[1].name,
              card3Name: this.drawnCards[2].name,
            }),
          });

          const data = await res.json();
          if (!res.ok) throw new Error(data.error || "Server error");

          try {
            await submitKitEmbedHidden({ name, email, dob, gender });
          } catch {
            /* Hidden Kit embed is optional; API already saved the subscriber */
          }

          this.closeModal();
          window.location.href = "/thankyou.html";
        } catch {
          btn.disabled = false;
          btn.textContent = "Unlock My Reading →";
          err.textContent = "Something went wrong. Please try again.";
          err.classList.add("visible");
        }
      });
  }

  init() {
    void injectKitEmbedIfConfigured();
    this.populateDobSelects();
    this.bindForm();
    this.buildDeck();
  }
}

new TarotReadingApp().init();

import { initDreamBackground } from "./lib/dream-background.js";
import { funnelUrl, siteUrl } from "./lib/funnel-base.js";
import { parseReadingPick, READING_PICK_KEY } from "./lib/reading-pick.js";
import { storeSalesHandoff } from "./lib/sales-handoff.js";

const IMG_BASE = "https://www.trustedtarot.com/img/cards/";
const SLOT_LABELS = ["Your Love", "Your Life", "Your Wealth"];
const KIT_EMBED_FORM_WAIT_MS = 10_000;
const KIT_EMBED_POST_SUBMIT_SETTLE_MS = 2000;
const SUBMIT_BTN_LABEL = "Unlock My Reading \u00a0\u2192";
const SUBMIT_PENDING_LABEL = "Reading the cards\u2026";
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

/**
 * @returns {{ embedScriptSrc: string, embedDataUid: string, formSubscribeVia: string } | null}
 */
function readUnlockKitConfig() {
  const el = document.getElementById("unlockKitConfig");
  const raw = el?.textContent?.trim();
  if (!raw) return null;
  try {
    const o = JSON.parse(raw);
    if (
      o &&
      typeof o.embedScriptSrc === "string" &&
      typeof o.embedDataUid === "string" &&
      typeof o.formSubscribeVia === "string"
    ) {
      return o;
    }
  } catch {
    /* ignore */
  }
  return null;
}

/**
 * @param {{ embedScriptSrc: string, embedDataUid: string, formSubscribeVia: string }} cfg
 */
function injectKitEmbedScript(cfg) {
  if (cfg.formSubscribeVia !== "embed" || !cfg.embedScriptSrc) return;
  const root = document.getElementById("kit-form-embed-root");
  if (!root) return;
  const s = document.createElement("script");
  s.async = true;
  s.src = cfg.embedScriptSrc;
  if (cfg.embedDataUid) s.setAttribute("data-uid", cfg.embedDataUid);
  root.appendChild(s);
}

function formHasEmailField(form) {
  for (const el of form.querySelectorAll("input")) {
    if (!(el instanceof HTMLInputElement)) continue;
    const t = el.type?.toLowerCase?.() ?? "";
    if (t === "email") return true;
    const n = el.name;
    if (
      n === "email_address" ||
      n === "email" ||
      n === "fields[email]" ||
      n.endsWith("[email]")
    ) {
      return true;
    }
  }
  return false;
}

/**
 * @param {HTMLElement} root
 * @returns {HTMLFormElement | null}
 */
function findFillableForm(root) {
  const forms = root.querySelectorAll("form");
  for (const form of forms) {
    if (formHasEmailField(form)) return form;
  }
  return null;
}

/**
 * @param {HTMLElement} root
 * @param {number} timeoutMs
 * @returns {Promise<HTMLFormElement | null>}
 */
function waitForFillableForm(root, timeoutMs) {
  return new Promise((resolve) => {
    let settled = false;
    const tid = setTimeout(() => {
      if (settled) return;
      settled = true;
      obs.disconnect();
      resolve(findFillableForm(root));
    }, timeoutMs);

    const finish = (/** @type {HTMLFormElement | null} */ f) => {
      if (settled) return;
      settled = true;
      clearTimeout(tid);
      obs.disconnect();
      resolve(f ?? findFillableForm(root));
    };

    const obs = new MutationObserver(() => {
      const f = findFillableForm(root);
      if (f) finish(f);
    });
    obs.observe(root, { childList: true, subtree: true });
    const initial = findFillableForm(root);
    if (initial) finish(initial);
  });
}

/**
 * @param {HTMLFormElement} form
 * @param {{ type?: string, names?: string[], selectors?: string[] }} spec
 * @param {string} value
 */
function setControlledField(form, spec, value) {
  if (spec.names) {
    for (const el of form.querySelectorAll("input, textarea, select")) {
      if (
        !(el instanceof HTMLInputElement) &&
        !(el instanceof HTMLTextAreaElement) &&
        !(el instanceof HTMLSelectElement)
      ) {
        continue;
      }
      if (spec.names.includes(el.name)) {
        el.value = value;
        el.dispatchEvent(new Event("input", { bubbles: true }));
        el.dispatchEvent(new Event("change", { bubbles: true }));
        return true;
      }
    }
  }
  if (spec.selectors) {
    for (const sel of spec.selectors) {
      const input = form.querySelector(sel);
      if (
        input instanceof HTMLInputElement ||
        input instanceof HTMLTextAreaElement ||
        input instanceof HTMLSelectElement
      ) {
        input.value = value;
        input.dispatchEvent(new Event("input", { bubbles: true }));
        input.dispatchEvent(new Event("change", { bubbles: true }));
        return true;
      }
    }
  }
  if (spec.type === "email") {
    const input = form.querySelector('input[type="email"]');
    if (input instanceof HTMLInputElement) {
      input.value = value;
      input.dispatchEvent(new Event("input", { bubbles: true }));
      input.dispatchEvent(new Event("change", { bubbles: true }));
      return true;
    }
  }
  return false;
}

/**
 * @param {HTMLFormElement} form
 * @param {Record<string, string>} kitEmbedFields
 */
const KIT_READING_FIELD_NAMES = new Set([
  "fields[love_reading]",
  "fields[life_reading]",
  "fields[wealth_reading]",
  "fields[love_card]",
  "fields[life_card]",
  "fields[wealth_card]",
  "fields[love_card_image]",
  "fields[life_card_image]",
  "fields[wealth_card_image]",
]);

function applyKitEmbedFields(form, kitEmbedFields) {
  for (const [fieldName, value] of Object.entries(kitEmbedFields)) {
    if (!fieldName) continue;
    if (value === "" && !KIT_READING_FIELD_NAMES.has(fieldName)) continue;
    setControlledField(form, { names: [fieldName] }, value);
  }
}

/**
 * After a successful `/api/reading`, mirror subscriber data into Kit's injected form and submit.
 *
 * @param {string} name
 * @param {string} email
 * @param {Record<string, string> | null | undefined} kitEmbedFields
 */
async function submitKitEmbedAfterUnlock(name, email, kitEmbedFields) {
  const cfg = readUnlockKitConfig();
  if (!cfg || cfg.formSubscribeVia !== "embed" || !cfg.embedScriptSrc) return;

  const root = document.getElementById("kit-form-embed-root");
  if (!root) {
    console.warn("[soul-mirror] Kit embed root missing");
    return;
  }

  const emailNames = ["email_address", "email", "fields[email]"];
  const firstNameNames = ["fields[first_name]", "first_name", "name"];

  const form = await waitForFillableForm(root, KIT_EMBED_FORM_WAIT_MS);
  if (!form) {
    console.warn(
      "[soul-mirror] Kit embed: no fillable form (timeouts, Shadow DOM, or cross-origin iframe cannot be scripted here)",
    );
    return;
  }

  if (kitEmbedFields && typeof kitEmbedFields === "object") {
    applyKitEmbedFields(form, kitEmbedFields);
  } else {
    console.warn("[soul-mirror] Kit embed: kitEmbedFields missing from /api/reading response");
  }

  const emailOk =
    setControlledField(form, { names: emailNames, type: "email" }, email) ||
    setControlledField(form, { selectors: ['input[autocomplete="email"]'] }, email);
  if (!emailOk) {
    console.warn("[soul-mirror] Kit embed: could not set email field (check field names)");
  }

  const nameOk =
    setControlledField(form, { names: firstNameNames }, name.trim()) ||
    setControlledField(form, { selectors: ['input[autocomplete="given-name"]'] }, name.trim());
  if (!nameOk && name.trim() !== "") {
    console.warn("[soul-mirror] Kit embed: could not set first name field (optional for some forms)");
  }

  try {
    form.requestSubmit();
  } catch {
    try {
      HTMLFormElement.prototype.submit.call(form);
    } catch (e2) {
      console.warn("[soul-mirror] Kit embed submit failed", e2);
    }
  }

  await new Promise((resolve) => setTimeout(resolve, KIT_EMBED_POST_SUBMIT_SETTLE_MS));
}

function reconcileGate(data) {
  const root = document.documentElement;
  if (!data) {
    root.classList.remove("unlock-ok");
    root.classList.add("unlock-bad");
    return null;
  }
  root.classList.remove("unlock-bad");
  root.classList.add("unlock-ok");
  return data.cards;
}

const pick = reconcileGate(parseReadingPick(sessionStorage.getItem(READING_PICK_KEY)));

if (pick) {
  const recap = document.getElementById("chosenRecap");
  if (recap) {
    recap.innerHTML = pick
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
  }

  setTimeout(() => document.getElementById("inputName")?.focus(), 100);
  const kitCfg = readUnlockKitConfig();
  if (kitCfg) injectKitEmbedScript(kitCfg);
}

const readingForm = document.getElementById("readingForm");
const submitBtn = document.getElementById("submitBtn");
const errorMsg = document.getElementById("errorMsg");

if (readingForm && submitBtn && errorMsg && pick) {
  const formControls = {
    name: document.getElementById("inputName"),
    email: document.getElementById("inputEmail"),
    gender: document.getElementById("inputGender"),
  };

  /**
   * @param {HTMLElement | null} element
   * @param {boolean} hasError
   */
  function toggleFieldError(element, hasError) {
    if (!element) return;
    const group = element.closest(".form-group");
    if (group) group.classList.toggle("field-error", hasError);
    element.setAttribute("aria-invalid", hasError ? "true" : "false");
  }

  /**
   * @param {boolean} [showErrors]
   */
  function validateForm(showErrors = false) {
    const name = formControls.name?.value.trim() ?? "";
    const email = formControls.email?.value.trim() ?? "";
    const gender = formControls.gender?.value.trim() ?? "";

    const invalidName = name.length < 2 || name.length > 120;
    const invalidEmail = !EMAIL_RE.test(email) || email.length > 254;
    const invalidGender = !(gender === "Female" || gender === "Male");
    const hasErrors = invalidName || invalidEmail || invalidGender;

    if (showErrors) {
      toggleFieldError(formControls.name, invalidName);
      toggleFieldError(formControls.email, invalidEmail);
      toggleFieldError(formControls.gender, invalidGender);
    } else {
      toggleFieldError(formControls.name, false);
      toggleFieldError(formControls.email, false);
      toggleFieldError(formControls.gender, false);
    }

    submitBtn.disabled = hasErrors;
    submitBtn.classList.toggle("is-invalid", hasErrors);

    if (showErrors && hasErrors) {
      errorMsg.textContent =
        "Please complete all required fields with valid details before unlocking your reading.";
      errorMsg.classList.add("visible");
    } else if (!hasErrors) {
      errorMsg.textContent = "";
      errorMsg.classList.remove("visible");
    }

    return {
      isValid: !hasErrors,
      payload: {
        name,
        email,
        gender,
      },
    };
  }

  [
    formControls.name,
    formControls.email,
    formControls.gender,
  ].forEach((field) => {
    field?.addEventListener("input", () => validateForm(false));
    field?.addEventListener("change", () => validateForm(false));
  });

  validateForm(false);

  readingForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const validation = validateForm(true);
    if (!validation.isValid) return;

    const { name, email, gender } = validation.payload;

    submitBtn.disabled = true;
    submitBtn.classList.remove("is-invalid");
    submitBtn.textContent = SUBMIT_PENDING_LABEL;
    errorMsg.classList.remove("visible");

    const readingApiUrl = siteUrl("api/reading");
    const thankYouUrl = funnelUrl("thankyou.php");

    try {
      const res = await fetch(readingApiUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          name,
          email,
          gender,
          card1: pick[0].id,
          card2: pick[1].id,
          card3: pick[2].id,
          card1Name: pick[0].name,
          card2Name: pick[1].name,
          card3Name: pick[2].name,
        }),
      });

      const data = await res.json();
      if (!res.ok) throw new Error(data.error || "Server error");

      await submitKitEmbedAfterUnlock(name, email, data.kitEmbedFields);

      storeSalesHandoff({
        firstName: name,
        cards: pick.map((card) => ({
          name: card.name,
          imageUrl: `${IMG_BASE}${card.slug}.png`,
        })),
      });

      try {
        sessionStorage.removeItem(READING_PICK_KEY);
      } catch {
        /* ignore */
      }
      window.location.href = thankYouUrl;
    } catch {
      validateForm(false);
      submitBtn.textContent = SUBMIT_BTN_LABEL;
      errorMsg.textContent = "Something went wrong. Please try again.";
      errorMsg.classList.add("visible");
    }
  });
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

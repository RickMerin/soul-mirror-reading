import { initDreamBackground } from "./lib/dream-background.js";
import {
  apiDobToKitEmbed,
  kitEmbedDobToApi,
} from "./lib/kit-embed-dob.js";
import { parseReadingPick, READING_PICK_KEY } from "./lib/reading-pick.js";

const IMG_BASE = "https://www.trustedtarot.com/img/cards/";
const SLOT_LABELS = ["Your Love", "Your Life", "Your Wealth"];
const KIT_EMBED_FORM_WAIT_MS = 10_000;
const KIT_EMBED_POST_SUBMIT_SETTLE_MS = 2000;

const EMAIL_FIELD_NAMES = ["email_address", "email", "fields[email]"];
const FIRST_NAME_FIELD_NAMES = ["fields[first_name]", "first_name", "name"];
const GENDER_FIELD_NAMES = ["fields[gender]", "gender"];
const GENDER_SELECT_OPTIONS = ["Female", "Male"];
const DOB_MONTH_OPTIONS = [
  ["01", "January"],
  ["02", "February"],
  ["03", "March"],
  ["04", "April"],
  ["05", "May"],
  ["06", "June"],
  ["07", "July"],
  ["08", "August"],
  ["09", "September"],
  ["10", "October"],
  ["11", "November"],
  ["12", "December"],
];
const DOB_FIELD_NAMES = ["fields[date_of_birth]", "date_of_birth", "dob"];
const KIT_SUBMIT_LABEL = "Unlock My Reading \u00a0→";
const KIT_SUBMIT_BUSY_LABEL = "Reading the cards\u2026";
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

/** @type {boolean} */
let allowNativeKitSubmit = false;

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

/**
 * @param {string} message
 */
function showUnlockFormError(message) {
  const errorEl = document.getElementById("unlockFormError");
  if (!errorEl) return;
  errorEl.textContent = message;
  errorEl.classList.add("visible");
}

function clearUnlockFormError() {
  const errorEl = document.getElementById("unlockFormError");
  if (!errorEl) return;
  errorEl.textContent = "";
  errorEl.classList.remove("visible");
}

/**
 * @param {boolean} visible
 */
function setKitEmbedLoading(visible) {
  const loadingEl = document.getElementById("kitEmbedLoading");
  if (!loadingEl) return;
  loadingEl.hidden = !visible;
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
 * @param {{ silent?: boolean }} [opts]
 */
function setControlledField(form, spec, value, opts = {}) {
  const silent = opts.silent === true;
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
        if (DOB_FIELD_NAMES.includes(el.name) && form.querySelector(".kit-dob-sync-input")) {
          return applyKitDobFieldValue(form, value);
        }
        el.value = value;
        if (!silent) {
          el.dispatchEvent(new Event("input", { bubbles: true }));
          el.dispatchEvent(new Event("change", { bubbles: true }));
        }
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
        if (!silent) {
          input.dispatchEvent(new Event("input", { bubbles: true }));
          input.dispatchEvent(new Event("change", { bubbles: true }));
        }
        return true;
      }
    }
  }
  if (spec.type === "email") {
    const input = form.querySelector('input[type="email"]');
    if (input instanceof HTMLInputElement) {
      input.value = value;
      if (!silent) {
        input.dispatchEvent(new Event("input", { bubbles: true }));
        input.dispatchEvent(new Event("change", { bubbles: true }));
      }
      return true;
    }
  }
  return false;
}

/**
 * @param {HTMLFormElement} form
 * @param {string[]} names
 * @returns {string}
 */
function readControlledField(form, names) {
  for (const el of form.querySelectorAll("input, textarea, select")) {
    if (
      !(el instanceof HTMLInputElement) &&
      !(el instanceof HTMLTextAreaElement) &&
      !(el instanceof HTMLSelectElement)
    ) {
      continue;
    }
    if (names.includes(el.name)) {
      return el.value.trim();
    }
  }
  return "";
}

/**
 * @param {HTMLFormElement} form
 * @param {Record<string, string>} kitEmbedFields
 * @param {{ silent?: boolean }} [opts]
 */
function applyKitEmbedFields(form, kitEmbedFields, opts = {}) {
  for (const [fieldName, value] of Object.entries(kitEmbedFields)) {
    if (!fieldName || value === "") continue;
    setControlledField(form, { names: [fieldName] }, value, opts);
  }
}

/**
 * @param {import("./lib/reading-pick.js").PickedCard[]} cards
 * @returns {Record<string, string>}
 */
function buildInitialKitFieldsFromPick(cards) {
  return {
    "fields[love_card]": cards[0].name,
    "fields[life_card]": cards[1].name,
    "fields[wealth_card]": cards[2].name,
    "fields[love_card_image]": `${IMG_BASE}${cards[0].slug}.png`,
    "fields[life_card_image]": `${IMG_BASE}${cards[1].slug}.png`,
    "fields[wealth_card_image]": `${IMG_BASE}${cards[2].slug}.png`,
  };
}

/**
 * @param {HTMLFormElement} form
 * @returns {{ name: string, email: string, dob: string, gender: string } | null}
 */
function readKitReadingPayload(form) {
  const name = readControlledField(form, FIRST_NAME_FIELD_NAMES);
  const email = readControlledField(form, EMAIL_FIELD_NAMES);
  const gender = readControlledField(form, GENDER_FIELD_NAMES);
  const dobRaw = readControlledField(form, DOB_FIELD_NAMES);
  const dob = kitEmbedDobToApi(dobRaw);

  if (!name || !email || !dob || !gender) return null;
  return { name, email, dob, gender };
}

/**
 * @param {string} apiDob
 * @returns {{ month: string, day: string, year: string } | null}
 */
function splitApiDob(apiDob) {
  const match = /^(\d{2})\/(\d{2})\/(\d{4})$/.exec(apiDob);
  if (!match) return null;
  return { month: match[1], day: match[2], year: match[3] };
}

/**
 * @param {HTMLElement} fieldWrap
 */
function syncKitDobHiddenValue(fieldWrap) {
  const month = fieldWrap.querySelector('[data-dob-part="month"]');
  const day = fieldWrap.querySelector('[data-dob-part="day"]');
  const year = fieldWrap.querySelector('[data-dob-part="year"]');
  const hidden = fieldWrap.querySelector(".kit-dob-sync-input");
  if (
    !(month instanceof HTMLSelectElement) ||
    !(day instanceof HTMLSelectElement) ||
    !(year instanceof HTMLSelectElement) ||
    !(hidden instanceof HTMLInputElement)
  ) {
    return;
  }

  if (!month.value || !day.value || !year.value) {
    hidden.value = "";
    return;
  }

  hidden.value = `${month.value}/${day.value}/${year.value}`;
  hidden.dispatchEvent(new Event("input", { bubbles: true }));
  hidden.dispatchEvent(new Event("change", { bubbles: true }));
}

/**
 * @param {HTMLFormElement} form
 * @param {string} value
 */
function applyKitDobFieldValue(form, value) {
  const fieldWrap = form.querySelector(".kit-dob-field");
  if (!fieldWrap) return false;

  const month = fieldWrap.querySelector('[data-dob-part="month"]');
  const day = fieldWrap.querySelector('[data-dob-part="day"]');
  const year = fieldWrap.querySelector('[data-dob-part="year"]');
  if (
    !(month instanceof HTMLSelectElement) ||
    !(day instanceof HTMLSelectElement) ||
    !(year instanceof HTMLSelectElement)
  ) {
    return false;
  }

  const apiDob = kitEmbedDobToApi(value);
  const parts = apiDob ? splitApiDob(apiDob) : null;
  month.value = parts?.month ?? "";
  day.value = parts?.day ?? "";
  year.value = parts?.year ?? "";
  syncKitDobHiddenValue(fieldWrap);
  return true;
}

/**
 * @param {string} placeholder
 * @param {"month" | "day" | "year"} part
 */
function createDobPartSelect(placeholder, part) {
  const select = document.createElement("select");
  select.dataset.dobPart = part;
  select.className = "formkit-input";
  select.required = true;

  const placeholderOption = document.createElement("option");
  placeholderOption.value = "";
  placeholderOption.disabled = true;
  placeholderOption.selected = true;
  placeholderOption.textContent = placeholder;
  select.appendChild(placeholderOption);

  return select;
}

/**
 * @param {HTMLFormElement} form
 */
function configureKitDobField(form) {
  const currentYear = new Date().getFullYear();

  for (const el of form.querySelectorAll("input")) {
    if (!(el instanceof HTMLInputElement)) continue;
    if (!DOB_FIELD_NAMES.includes(el.name)) continue;

    const fieldWrap = el.closest(".formkit-field, .seva-field, .form-group");
    if (!fieldWrap || fieldWrap.dataset.dobSplit === "1") continue;

    const apiDob = kitEmbedDobToApi(el.value);
    const parts = apiDob ? splitApiDob(apiDob) : null;
    fieldWrap.dataset.dobSplit = "1";
    fieldWrap.classList.add("kit-dob-field");

    const label = document.createElement("label");
    label.className = "kit-dob-label";
    label.textContent = "Date of Birth";
    fieldWrap.insertBefore(label, el);

    const row = document.createElement("div");
    row.className = "dob-row";

    const monthSelect = createDobPartSelect("Month", "month");
    for (const [value, text] of DOB_MONTH_OPTIONS) {
      const option = document.createElement("option");
      option.value = value;
      option.textContent = text;
      monthSelect.appendChild(option);
    }

    const daySelect = createDobPartSelect("Day", "day");
    for (let day = 1; day <= 31; day += 1) {
      const option = document.createElement("option");
      option.value = String(day).padStart(2, "0");
      option.textContent = String(day);
      daySelect.appendChild(option);
    }

    const yearSelect = createDobPartSelect("Year", "year");
    for (let year = currentYear; year >= currentYear - 100; year -= 1) {
      const option = document.createElement("option");
      option.value = String(year);
      option.textContent = String(year);
      yearSelect.appendChild(option);
    }

    row.append(monthSelect, daySelect, yearSelect);
    fieldWrap.insertBefore(row, el);

    el.type = "hidden";
    el.classList.add("kit-dob-sync-input");
    el.removeAttribute("placeholder");
    el.required = true;

    if (parts) {
      monthSelect.value = parts.month;
      daySelect.value = parts.day;
      yearSelect.value = parts.year;
    }
    syncKitDobHiddenValue(fieldWrap);

    for (const select of row.querySelectorAll("select")) {
      const sync = () => {
        syncKitDobHiddenValue(fieldWrap);
        syncKitSubmitState(form);
      };
      select.addEventListener("input", sync);
      select.addEventListener("change", sync);
    }
  }
}

/**
 * @param {HTMLFormElement} form
 */
function prepareKitDobFieldForNativeSubmit(form) {
  const hidden = form.querySelector(".kit-dob-sync-input");
  if (!(hidden instanceof HTMLInputElement)) return;

  const apiDob = kitEmbedDobToApi(hidden.value);
  if (!apiDob) return;

  const kitDob = apiDobToKitEmbed(apiDob);
  if (!kitDob) return;

  hidden.value = kitDob;
}

/**
 * @param {string} value
 */
function normalizeGenderValue(value) {
  const trimmed = value.trim();
  if (trimmed === "Female" || trimmed === "Male") return trimmed;
  if (trimmed.toLowerCase() === "female") return "Female";
  if (trimmed.toLowerCase() === "male") return "Male";
  return "";
}

/**
 * @param {HTMLFormElement} form
 */
function configureKitGenderField(form) {
  for (const el of form.querySelectorAll("input, select")) {
    if (el instanceof HTMLSelectElement && GENDER_FIELD_NAMES.includes(el.name)) {
      if (el.dataset.genderSelect === "1") continue;
      el.dataset.genderSelect = "1";
      const current = normalizeGenderValue(el.value);
      el.innerHTML = "";
      const placeholder = document.createElement("option");
      placeholder.value = "";
      placeholder.disabled = true;
      placeholder.textContent = "Select...";
      placeholder.selected = current === "";
      el.appendChild(placeholder);
      for (const gender of GENDER_SELECT_OPTIONS) {
        const option = document.createElement("option");
        option.value = gender;
        option.textContent = gender;
        if (gender === current) option.selected = true;
        el.appendChild(option);
      }
      continue;
    }

    if (!(el instanceof HTMLInputElement)) continue;
    if (!GENDER_FIELD_NAMES.includes(el.name)) continue;

    const select = document.createElement("select");
    select.name = el.name;
    select.required = el.required;
    select.className = el.className;
    select.dataset.genderSelect = "1";
    const ariaLabel = el.getAttribute("aria-label");
    if (ariaLabel) select.setAttribute("aria-label", ariaLabel);

    const placeholder = document.createElement("option");
    placeholder.value = "";
    placeholder.disabled = true;
    placeholder.textContent = "Select...";
    select.appendChild(placeholder);

    const current = normalizeGenderValue(el.value);
    placeholder.selected = current === "";
    for (const gender of GENDER_SELECT_OPTIONS) {
      const option = document.createElement("option");
      option.value = gender;
      option.textContent = gender;
      if (gender === current) option.selected = true;
      select.appendChild(option);
    }

    el.replaceWith(select);
  }
}

/**
 * @param {HTMLFormElement} form
 * @returns {HTMLButtonElement | HTMLInputElement | null}
 */
function getKitSubmitControl(form) {
  const submit = form.querySelector(
    '[data-element="submit"], .formkit-submit, button[type="submit"], input[type="submit"]',
  );
  return submit instanceof HTMLButtonElement || submit instanceof HTMLInputElement
    ? submit
    : null;
}

/**
 * @param {HTMLFormElement} form
 * @returns {HTMLButtonElement | HTMLInputElement | null}
 */
function customizeKitSubmitButton(form) {
  const submit = getKitSubmitControl(form);
  if (!submit) return null;

  if (submit instanceof HTMLButtonElement) {
    const label = submit.querySelector("span");
    if (label) label.textContent = KIT_SUBMIT_LABEL;
    submit.setAttribute("aria-label", "Unlock my reading");
  } else if (submit instanceof HTMLInputElement) {
    submit.value = KIT_SUBMIT_LABEL;
    submit.setAttribute("aria-label", "Unlock my reading");
  }

  return submit;
}

/**
 * @param {HTMLFormElement} form
 */
function isKitFormReadyForSubmit(form) {
  const name = readControlledField(form, FIRST_NAME_FIELD_NAMES);
  const email = readControlledField(form, EMAIL_FIELD_NAMES);
  const gender = readControlledField(form, GENDER_FIELD_NAMES);
  const dob = kitEmbedDobToApi(readControlledField(form, DOB_FIELD_NAMES));

  if (name.length < 2 || name.length > 120) return false;
  if (!EMAIL_RE.test(email) || email.length > 254) return false;
  if (!dob) return false;
  if (!(gender === "Female" || gender === "Male")) return false;

  return true;
}

/**
 * @param {HTMLFormElement} form
 */
function syncKitSubmitState(form) {
  const submit = customizeKitSubmitButton(form);
  if (!submit || submit.dataset.submitting === "1") return;

  const ready = isKitFormReadyForSubmit(form);
  submit.disabled = !ready;
  submit.classList.toggle("is-invalid", !ready);
}

/**
 * @param {HTMLFormElement} form
 */
function wireKitSubmitValidation(form) {
  const controls = form.querySelectorAll(
    'input[name="fields[first_name]"], input[name="email_address"], select[name="fields[gender]"], select[data-dob-part]',
  );
  for (const control of controls) {
    control.addEventListener("input", () => syncKitSubmitState(form));
    control.addEventListener("change", () => syncKitSubmitState(form));
  }
  syncKitSubmitState(form);
}

/**
 * @param {HTMLFormElement} form
 */
function setKitSubmitBusy(form, busy) {
  const submit = getKitSubmitControl(form);
  if (!submit) return;

  if (busy) {
    submit.dataset.submitting = "1";
    submit.disabled = true;
    submit.classList.remove("is-invalid");
    if (submit instanceof HTMLButtonElement) {
      const label = submit.querySelector("span");
      if (label) label.textContent = KIT_SUBMIT_BUSY_LABEL;
    } else if (submit instanceof HTMLInputElement) {
      submit.value = KIT_SUBMIT_BUSY_LABEL;
    }
    return;
  }

  delete submit.dataset.submitting;
  syncKitSubmitState(form);
}

/**
 * @param {HTMLFormElement} form
 * @param {Record<string, string> | null | undefined} kitEmbedFields
 */
async function submitKitEmbedForm(form, kitEmbedFields) {
  if (kitEmbedFields && typeof kitEmbedFields === "object") {
    applyKitEmbedFields(form, kitEmbedFields, { silent: true });
  }

  prepareKitDobFieldForNativeSubmit(form);

  allowNativeKitSubmit = true;
  try {
    form.requestSubmit();
  } catch {
    try {
      HTMLFormElement.prototype.submit.call(form);
    } catch (e2) {
      console.warn("[soul-mirror] Kit embed submit failed", e2);
    }
  } finally {
    allowNativeKitSubmit = false;
  }

  await new Promise((resolve) => setTimeout(resolve, KIT_EMBED_POST_SUBMIT_SETTLE_MS));
}

/**
 * @param {import("./lib/reading-pick.js").PickedCard[]} cards
 */
async function mountKitEmbedForm(cards) {
  const cfg = readUnlockKitConfig();
  const root = document.getElementById("kit-form-embed-root");
  if (!root) return;

  if (!cfg || cfg.formSubscribeVia !== "embed" || !cfg.embedScriptSrc) {
    setKitEmbedLoading(false);
    showUnlockFormError(
      "The reading form is not configured yet. Please try again later or contact support.",
    );
    return;
  }

  setKitEmbedLoading(true);
  clearUnlockFormError();
  injectKitEmbedScript(cfg);

  const form = await waitForFillableForm(root, KIT_EMBED_FORM_WAIT_MS);
  setKitEmbedLoading(false);

  if (!form) {
    showUnlockFormError(
      "We could not load the reading form. Please refresh the page and try again.",
    );
    console.warn(
      "[soul-mirror] Kit embed: no fillable form (timeouts, Shadow DOM, or cross-origin iframe cannot be scripted here)",
    );
    return;
  }

  applyKitEmbedFields(form, buildInitialKitFieldsFromPick(cards), { silent: true });
  configureKitDobField(form);
  configureKitGenderField(form);
  customizeKitSubmitButton(form);
  wireKitSubmitValidation(form);

  form.addEventListener(
    "submit",
    async (event) => {
      if (allowNativeKitSubmit) return;
      event.preventDefault();
      clearUnlockFormError();

    const payload = readKitReadingPayload(form);
    if (!payload) {
      showUnlockFormError(
        "Please complete all required fields with valid details before unlocking your reading.",
      );
      return;
    }

    setKitSubmitBusy(form, true);

    const readingApiUrl = new URL("api/reading", window.location.href).href;
    const thankYouUrl = new URL("thankyou.php", window.location.href).href;

    try {
      const res = await fetch(readingApiUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          name: payload.name,
          email: payload.email,
          dob: payload.dob,
          gender: payload.gender,
          card1: cards[0].id,
          card2: cards[1].id,
          card3: cards[2].id,
          card1Name: cards[0].name,
          card2Name: cards[1].name,
          card3Name: cards[2].name,
        }),
      });

      const data = await res.json();
      if (!res.ok) throw new Error(data.error || "Server error");

      await submitKitEmbedForm(form, data.kitEmbedFields);

      try {
        sessionStorage.removeItem(READING_PICK_KEY);
      } catch {
        /* ignore */
      }
      window.location.href = thankYouUrl;
    } catch {
      setKitSubmitBusy(form, false);
      showUnlockFormError("Something went wrong. Please try again.");
    }
  },
    true,
  );
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

  void mountKitEmbedForm(pick);
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

import { initDreamBackground } from "./lib/dream-background.js";
import { parseReadingPick, READING_PICK_KEY } from "./lib/reading-pick.js";

const IMG_BASE = "https://www.trustedtarot.com/img/cards/";
const SLOT_LABELS = ["Your Love", "Your Life", "Your Wealth"];

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

const pick = reconcileGate(
  parseReadingPick(sessionStorage.getItem(READING_PICK_KEY)),
);

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
}

(function () {
  const daySelect = document.getElementById("inputDobDay");
  const yearSelect = document.getElementById("inputDobYear");
  if (!daySelect || !yearSelect) return;
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
})();

const nameInput = document.getElementById("inputName");
if (nameInput) {
  nameInput.addEventListener("input", function () {
    const greet = document.getElementById("nameGreet");
    if (greet)
      greet.textContent = this.value.trim() ? this.value.trim() + "?" : "";
  });
}

const readingForm = document.getElementById("readingForm");
const submitBtn = document.getElementById("submitBtn");
const errorMsg = document.getElementById("errorMsg");

if (readingForm && submitBtn && errorMsg && pick) {
  const formControls = {
    name: document.getElementById("inputName"),
    email: document.getElementById("inputEmail"),
    month: document.getElementById("inputDobMonth"),
    day: document.getElementById("inputDobDay"),
    year: document.getElementById("inputDobYear"),
    gender: document.getElementById("inputGender"),
  };
  const dobRow = document.querySelector(".dob-row");
  const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  function toggleFieldError(element, hasError) {
    if (!element) return;
    const group = element.closest(".form-group");
    if (group) group.classList.toggle("field-error", hasError);
    element.setAttribute("aria-invalid", hasError ? "true" : "false");
  }

  function toggleDobError(hasError) {
    const dobGroup = formControls.month?.closest(".form-group");
    if (dobGroup) dobGroup.classList.toggle("field-error", hasError);
    if (dobRow) dobRow.classList.toggle("field-error", hasError);
    [formControls.month, formControls.day, formControls.year].forEach(
      (field) => {
        field?.setAttribute("aria-invalid", hasError ? "true" : "false");
      },
    );
  }

  function validateForm(showErrors = false) {
    const name = formControls.name?.value.trim() ?? "";
    const email = formControls.email?.value.trim() ?? "";
    const dobMonth = formControls.month?.value ?? "";
    const dobDay = formControls.day?.value ?? "";
    const dobYear = formControls.year?.value ?? "";
    const gender = formControls.gender?.value.trim() ?? "";

    const invalidName = name.length < 2 || name.length > 120;
    const invalidEmail = !EMAIL_RE.test(email) || email.length > 254;
    const invalidDob = !(dobMonth && dobDay && dobYear);
    const invalidGender = !(gender === "Female" || gender === "Male");
    const hasErrors =
      invalidName || invalidEmail || invalidDob || invalidGender;

    if (showErrors) {
      toggleFieldError(formControls.name, invalidName);
      toggleFieldError(formControls.email, invalidEmail);
      toggleDobError(invalidDob);
      toggleFieldError(formControls.gender, invalidGender);
    } else {
      toggleFieldError(formControls.name, false);
      toggleFieldError(formControls.email, false);
      toggleDobError(false);
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
        dob: `${dobMonth}/${dobDay}/${dobYear}`,
        gender,
      },
    };
  }

  [
    formControls.name,
    formControls.email,
    formControls.month,
    formControls.day,
    formControls.year,
    formControls.gender,
  ].forEach((field) => {
    field?.addEventListener("input", () => validateForm(false));
    field?.addEventListener("change", () => validateForm(false));
  });

  validateForm(false);

  readingForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const validation = validateForm(true);
    if (!validation.isValid) {
      return;
    }

    const { name, email, dob, gender } = validation.payload;

    submitBtn.disabled = true;
    submitBtn.classList.remove("is-invalid");
    submitBtn.textContent = "Reading the cards…";
    errorMsg.classList.remove("visible");

    const readingApiUrl = new URL("api/reading", window.location.href).href;
    const thankYouUrl = new URL("thankyou.php", window.location.href).href;

    try {
      const res = await fetch(readingApiUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          name,
          email,
          dob,
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

      try {
        sessionStorage.removeItem(READING_PICK_KEY);
      } catch {
        /* ignore */
      }
      window.location.href = thankYouUrl;
    } catch {
      validateForm(false);
      submitBtn.textContent = "Unlock My Reading →";
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

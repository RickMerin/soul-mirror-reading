const CARD_SLOTS = ["love", "life", "wealth"];

/**
 * @param {string} raw
 * @returns {string}
 */
export function normalizeFirstName(raw) {
  const trimmed = decodeURIComponent(raw).trim();
  if (!trimmed) return "";
  const first = trimmed.split(/\s+/)[0] ?? "";
  if (!first) return "";
  return first.charAt(0).toUpperCase() + first.slice(1).toLowerCase();
}

/**
 * @param {URLSearchParams} params
 * @returns {string}
 */
export function resolveDisplayName(params) {
  const raw =
    params.get("first_name") ?? params.get("firstname") ?? params.get("name") ?? "";
  const normalized = normalizeFirstName(raw);
  return normalized || "Friend";
}

export function injectFirstName() {
  const params = new URLSearchParams(window.location.search);
  const displayName = resolveDisplayName(params);
  document.querySelectorAll(".firstname").forEach((el) => {
    el.textContent = displayName;
  });
  if (displayName !== "Friend") {
    try {
      localStorage.setItem("smr_first_name", displayName);
    } catch {
      /* ignore */
    }
  }
}

/**
 * @param {HTMLElement} el
 * @param {string} imageUrl
 * @param {string} [cardName]
 */
function applyCardImage(el, imageUrl, cardName) {
  const decoded = decodeURIComponent(imageUrl);
  if (el.tagName === "IMG") {
    const img = /** @type {HTMLImageElement} */ (el);
    const fallback = img.src;
    img.addEventListener("error", () => {
      img.src = fallback;
    }, { once: true });
    img.src = decoded;
    if (cardName) img.alt = decodeURIComponent(cardName);
    return;
  }
  el.style.backgroundImage = `url("${decoded}")`;
  el.classList.add("has-card");
  if (cardName) el.setAttribute("aria-label", decodeURIComponent(cardName));
}

export function injectChosenCards() {
  const params = new URLSearchParams(window.location.search);
  const blockName = params.get("mirror_block_name");
  const blockSummary = params.get("mirror_block_summary");
  if (blockName) {
    document.querySelectorAll("[data-mirror-block-name]").forEach((el) => {
      el.textContent = decodeURIComponent(blockName);
    });
  }
  if (blockSummary) {
    document.querySelectorAll("[data-mirror-block-summary]").forEach((el) => {
      el.textContent = decodeURIComponent(blockSummary);
    });
  }

  for (const slot of CARD_SLOTS) {
    const cardName = params.get(`${slot}_card`);
    const cardImage = params.get(`${slot}_card_image`);
    if (cardImage) {
      document.querySelectorAll(`[data-card-image="${slot}"]`).forEach((el) => {
        applyCardImage(/** @type {HTMLElement} */ (el), cardImage, cardName ?? undefined);
      });
    }
    if (cardName) {
      document.querySelectorAll(`[data-card-name="${slot}"]`).forEach((nameEl) => {
        nameEl.textContent = decodeURIComponent(cardName);
        nameEl.classList.add("visible");
      });
    }
  }
}

export function initSalesPersonalization() {
  injectFirstName();
  injectChosenCards();
}

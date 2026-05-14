export const SALES_HANDOFF_KEY = "smr_sales_handoff";

const CARD_SLOTS = ["love", "life", "wealth"];

/**
 * @param {{
 *   firstName: string,
 *   cards: Array<{ name: string, imageUrl: string }>,
 * }} payload
 */
export function storeSalesHandoff(payload) {
  try {
    sessionStorage.setItem(SALES_HANDOFF_KEY, JSON.stringify(payload));
  } catch {
    /* ignore */
  }
}

/**
 * @returns {{
 *   firstName: string,
 *   cards: Array<{ name: string, imageUrl: string }>,
 * } | null}
 */
export function readSalesHandoff() {
  try {
    const raw = sessionStorage.getItem(SALES_HANDOFF_KEY);
    if (!raw) return null;
    const data = JSON.parse(raw);
    if (!data || typeof data !== "object") return null;
    if (typeof data.firstName !== "string") return null;
    if (!Array.isArray(data.cards) || data.cards.length !== 3) return null;
    for (const card of data.cards) {
      if (!card || typeof card.name !== "string" || typeof card.imageUrl !== "string") {
        return null;
      }
    }
    return data;
  } catch {
    return null;
  }
}

export function clearSalesHandoff() {
  try {
    sessionStorage.removeItem(SALES_HANDOFF_KEY);
  } catch {
    /* ignore */
  }
}

/**
 * @param {Record<string, string>} params
 * @returns {string}
 */
export function buildSalesQueryString(params) {
  const search = new URLSearchParams();
  for (const [key, value] of Object.entries(params)) {
    if (value !== "") search.set(key, value);
  }
  const qs = search.toString();
  return qs === "" ? "" : `?${qs}`;
}

/**
 * @param {{
 *   firstName: string,
 *   cards: Array<{ name: string, imageUrl: string }>,
 * }} handoff
 * @returns {Record<string, string>}
 */
export function salesHandoffToQueryParams(handoff) {
  /** @type {Record<string, string>} */
  const params = {};
  if (handoff.firstName) params.first_name = handoff.firstName;
  CARD_SLOTS.forEach((slot, index) => {
    const card = handoff.cards[index];
    if (!card) return;
    if (card.name) params[`${slot}_card`] = card.name;
    if (card.imageUrl) params[`${slot}_card_image`] = card.imageUrl;
  });
  return params;
}

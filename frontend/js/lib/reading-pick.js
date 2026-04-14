/** @typedef {{ id: number; slug: string; name: string; suit: string }} PickedCard */

export const READING_PICK_KEY = "soulMirrorReadingPick";
export const READING_PICK_VERSION = 1;

/**
 * @param {unknown} d
 * @returns {d is { v: number; cards: PickedCard[] }}
 */
export function isValidReadingPick(d) {
  if (!d || typeof d !== "object") return false;
  const o = /** @type {{ v?: unknown; cards?: unknown }} */ (d);
  if (
    o.v !== READING_PICK_VERSION ||
    !Array.isArray(o.cards) ||
    o.cards.length !== 3
  ) {
    return false;
  }
  for (const c of o.cards) {
    if (!c || typeof c !== "object") return false;
    const card =
      /** @type {{ id?: unknown; slug?: unknown; name?: unknown; suit?: unknown }} */ (
        c
      );
    const id = Number(card.id);
    if (!Number.isInteger(id) || id < 1 || id > 78) return false;
    if (typeof card.slug !== "string" || card.slug.length === 0) return false;
    if (typeof card.name !== "string" || card.name.length === 0) return false;
    if (typeof card.suit !== "string" || card.suit.length === 0) return false;
  }
  return true;
}

/**
 * @param {string | null} raw
 * @returns {{ v: number; cards: PickedCard[] } | null}
 */
export function parseReadingPick(raw) {
  if (raw == null || raw === "") return null;
  try {
    const d = JSON.parse(raw);
    return isValidReadingPick(d) ? d : null;
  } catch {
    return null;
  }
}

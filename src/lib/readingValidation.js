"use strict";

/** trustedtarot slug order — card IDs 1–78 (see tarotCards.js). */
const MIN_CARD_ID = 1;
const MAX_CARD_ID = 78;

const MAX_NAME_LEN = 200;
const MAX_EMAIL_LEN = 254;
const MAX_CARD_NAME_LEN = 120;
const MAX_DOB_LEN = 32;
const MAX_GENDER_LEN = 64;

/** Practical single-line email check; rejects obvious garbage. */
function isValidEmail(email) {
  if (typeof email !== "string") return false;
  const s = email.trim();
  if (s.length === 0 || s.length > MAX_EMAIL_LEN) return false;
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(s);
}

/**
 * @param {unknown} value
 * @returns {number | null}
 */
function parseTarotCardId(value) {
  const n = Number(value);
  if (!Number.isFinite(n) || !Number.isInteger(n)) return null;
  if (n < MIN_CARD_ID || n > MAX_CARD_ID) return null;
  return n;
}

/**
 * @param {Record<string, unknown>} body
 * @returns {{ ok: true; value: Record<string, string> } | { ok: false; error: string }}
 */
function parseReadingPayload(body) {
  if (body == null || typeof body !== "object") {
    return { ok: false, error: "Invalid request body." };
  }

  const {
    name,
    email,
    dob,
    gender,
    card1,
    card2,
    card3,
    card1Name,
    card2Name,
    card3Name,
  } = body;

  const c1 = parseTarotCardId(card1);
  const c2 = parseTarotCardId(card2);
  const c3 = parseTarotCardId(card3);

  if (!name || !email || c1 == null || c2 == null || c3 == null) {
    return { ok: false, error: "Missing or invalid required fields." };
  }

  const nameStr = String(name).trim();
  const emailStr = String(email).trim();

  if (!nameStr || nameStr.length > MAX_NAME_LEN) {
    return { ok: false, error: "Invalid name." };
  }
  if (!isValidEmail(emailStr)) {
    return { ok: false, error: "Invalid email address." };
  }

  const dobStr = dob != null ? String(dob).trim() : "";
  if (dobStr.length > MAX_DOB_LEN) {
    return { ok: false, error: "Invalid date of birth." };
  }

  const genderStr = gender != null ? String(gender).trim() : "";
  if (genderStr.length > MAX_GENDER_LEN) {
    return { ok: false, error: "Invalid gender field." };
  }

  const cn = (x) => {
    const s = x != null ? String(x).trim() : "";
    if (s.length > MAX_CARD_NAME_LEN) return null;
    return s;
  };
  const n1 = cn(card1Name);
  const n2 = cn(card2Name);
  const n3 = cn(card3Name);
  if (n1 == null || n2 == null || n3 == null || !n1 || !n2 || !n3) {
    return { ok: false, error: "Invalid card names." };
  }

  return {
    ok: true,
    value: {
      name: nameStr,
      email: emailStr,
      dob: dobStr,
      gender: genderStr,
      card1: String(c1),
      card2: String(c2),
      card3: String(c3),
      card1Name: n1,
      card2Name: n2,
      card3Name: n3,
    },
  };
}

module.exports = {
  parseReadingPayload,
  parseTarotCardId,
  isValidEmail,
  MIN_CARD_ID,
  MAX_CARD_ID,
};

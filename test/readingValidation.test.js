"use strict";

const { test } = require("node:test");
const assert = require("node:assert");
const {
  parseReadingPayload,
  parseTarotCardId,
  isValidEmail,
} = require("../src/lib/readingValidation");

test("isValidEmail accepts common shapes and rejects garbage", () => {
  assert.strictEqual(isValidEmail("a@b.co"), true);
  assert.strictEqual(isValidEmail("  a@b.co  "), true);
  assert.strictEqual(isValidEmail("not"), false);
  assert.strictEqual(isValidEmail(""), false);
});

test("parseTarotCardId enforces 1–78", () => {
  assert.strictEqual(parseTarotCardId(1), 1);
  assert.strictEqual(parseTarotCardId(78), 78);
  assert.strictEqual(parseTarotCardId(0), null);
  assert.strictEqual(parseTarotCardId(79), null);
  assert.strictEqual(parseTarotCardId(1.5), null);
  assert.strictEqual(parseTarotCardId("12"), 12);
});

test("parseReadingPayload returns 400-style errors for bad input", () => {
  const bad = parseReadingPayload(null);
  assert.strictEqual(bad.ok, false);

  const missing = parseReadingPayload({ name: "x", email: "a@b.co" });
  assert.strictEqual(missing.ok, false);

  const emailBad = parseReadingPayload({
    name: "N",
    email: "bad",
    card1: 1,
    card2: 2,
    card3: 3,
    card1Name: "The Fool",
    card2Name: "The Magician",
    card3Name: "The High Priestess",
  });
  assert.strictEqual(emailBad.ok, false);

  const good = parseReadingPayload({
    name: "  N  ",
    email: "  a@b.co  ",
    dob: "3/21/1990",
    gender: "x",
    card1: 1,
    card2: 2,
    card3: 3,
    card1Name: "The Fool",
    card2Name: "The Magician",
    card3Name: "The High Priestess",
  });
  assert.strictEqual(good.ok, true);
  assert.strictEqual(good.value.email, "a@b.co");
  assert.strictEqual(good.value.name, "N");
});

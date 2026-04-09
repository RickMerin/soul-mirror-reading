"use strict";

const { test } = require("node:test");
const assert = require("node:assert");
const { getSunSign } = require("../src/lib/sunSign");

test("getSunSign returns known slugs", () => {
  assert.strictEqual(getSunSign(3, 21), "aries");
  assert.strictEqual(getSunSign(1, 15), "capricorn");
  assert.strictEqual(getSunSign(2, 19), "pisces");
});

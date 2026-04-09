"use strict";

const { test } = require("node:test");
const assert = require("node:assert");
const { createApp } = require("../src/app");

/**
 * Binds to an ephemeral port, runs `fn(port)`, then closes the server.
 * Ensures assertion failures reject the test instead of becoming unhandled rejections.
 */
async function withServer(app, fn) {
  const server = await new Promise((resolve, reject) => {
    const s = app.listen(0, "127.0.0.1");
    s.on("error", reject);
    s.on("listening", () => resolve(s));
  });
  try {
    const addr = server.address();
    const port = typeof addr === "object" && addr ? addr.port : addr;
    await fn(port);
  } finally {
    await new Promise((resolve, reject) => {
      server.close((err) => (err ? reject(err) : resolve()));
    });
  }
}

test("GET / serves the landing page", async () => {
  const app = createApp();
  await withServer(app, async (port) => {
    const res = await fetch(`http://127.0.0.1:${port}/`);
    assert.strictEqual(res.status, 200);
    const text = await res.text();
    assert.match(text, /Soul Mirror/i);
    assert.match(text, /\/css\/index\.css/);
    assert.match(text, /\/js\/tarot\/reading-app\.js/);
    assert.doesNotMatch(text, /astrologyvault\.kit\.com/);
  });
});

test("GET /api/public-config returns embed hints", async () => {
  const app = createApp();
  await withServer(app, async (port) => {
    const res = await fetch(`http://127.0.0.1:${port}/api/public-config`);
    assert.strictEqual(res.status, 200);
    const data = await res.json();
    assert.ok("kitEmbedSubdomain" in data);
    assert.ok("kitEmbedFormUid" in data);
  });
});

test("GET /css/index.css and /js/tarot/reading-app.js return 200", async () => {
  const app = createApp();
  await withServer(app, async (port) => {
    const base = `http://127.0.0.1:${port}`;
    const css = await fetch(`${base}/css/index.css`);
    const js = await fetch(`${base}/js/tarot/reading-app.js`);
    assert.strictEqual(css.status, 200);
    assert.strictEqual(js.status, 200);
    const jsText = await js.text();
    assert.match(jsText, /import/);
  });
});

test("POST /api/reading returns 400 when required fields missing", async () => {
  const app = createApp();
  await withServer(app, async (port) => {
    const res = await fetch(`http://127.0.0.1:${port}/api/reading`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        name: "x",
        email: "test@test.com",
      }),
    });
    assert.strictEqual(res.status, 400);
    const data = await res.json();
    assert.ok(data.error);
  });
});

test("POST /api/reading returns 400 when email invalid", async () => {
  const app = createApp();
  await withServer(app, async (port) => {
    const res = await fetch(`http://127.0.0.1:${port}/api/reading`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        name: "Test",
        email: "not-an-email",
        dob: "1/1/1990",
        gender: "x",
        card1: 1,
        card2: 2,
        card3: 3,
        card1Name: "The Fool",
        card2Name: "The Magician",
        card3Name: "The High Priestess",
      }),
    });
    assert.strictEqual(res.status, 400);
    const data = await res.json();
    assert.strictEqual(data.error, "Invalid email address.");
  });
});

test("POST /api/reading returns 400 when card id out of range", async () => {
  const app = createApp();
  await withServer(app, async (port) => {
    const res = await fetch(`http://127.0.0.1:${port}/api/reading`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        name: "Test",
        email: "test@test.com",
        dob: "1/1/1990",
        gender: "x",
        card1: 0,
        card2: 2,
        card3: 3,
        card1Name: "The Fool",
        card2Name: "The Magician",
        card3Name: "The High Priestess",
      }),
    });
    assert.strictEqual(res.status, 400);
  });
});

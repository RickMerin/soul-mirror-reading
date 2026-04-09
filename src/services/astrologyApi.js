"use strict";

const BASE = "https://json.astrologyapi.com/v1";

const FETCH_TIMEOUT_MS = 20_000;

/**
 * HTTP client for AstrologyAPI (tarot + sun-sign endpoints).
 */
class AstrologyApiClient {
  /**
   * @param {string | undefined} userId
   * @param {string | undefined} apiKey
   */
  constructor(userId, apiKey) {
    this._userId = userId;
    this._apiKey = apiKey;
    this._auth =
      userId && apiKey
        ? Buffer.from(`${userId}:${apiKey}`).toString("base64")
        : "";
  }

  _headers() {
    return {
      Authorization: `Basic ${this._auth}`,
      "Content-Type": "application/json",
    };
  }

  /**
   * @param {{ love: number; career: number; finance: number }} cards
   * @returns {Promise<Record<string, unknown>>}
   */
  async fetchTarotPredictions(cards) {
    const res = await fetch(`${BASE}/tarot_predictions`, {
      method: "POST",
      headers: this._headers(),
      body: JSON.stringify({
        love: Number(cards.love),
        career: Number(cards.career),
        finance: Number(cards.finance),
      }),
      signal: AbortSignal.timeout(FETCH_TIMEOUT_MS),
    });
    if (!res.ok) {
      await res.text().catch(() => {});
      const err = new Error(`AstrologyAPI tarot_predictions ${res.status}`);
      throw err;
    }
    return res.json();
  }

  /**
   * @param {string} sunSignSlug
   * @returns {Promise<{ prediction?: Record<string, string> }>}
   */
  async fetchSunSignDaily(sunSignSlug) {
    const res = await fetch(
      `${BASE}/sun_sign_prediction/daily/${sunSignSlug}`,
      {
        method: "POST",
        headers: this._headers(),
        signal: AbortSignal.timeout(FETCH_TIMEOUT_MS),
      },
    );
    if (!res.ok) {
      console.warn("Sun sign prediction failed:", await res.text());
      return { prediction: {} };
    }
    return res.json();
  }
}

module.exports = { AstrologyApiClient };

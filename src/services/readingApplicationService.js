"use strict";

const { getSunSign } = require("../lib/sunSign");
const { getCardImageUrl } = require("../lib/tarotCards");
const { parseReadingPayload } = require("../lib/readingValidation");

/**
 * Orchestrates AstrologyAPI reads + Kit subscriber sync for POST /api/reading.
 */
class ReadingApplicationService {
  /**
   * @param {{ astrology: import("./astrologyApi").AstrologyApiClient; kit: import("./kit").KitService }} deps
   */
  constructor(deps) {
    this._astrology = deps.astrology;
    this._kit = deps.kit;
  }

  /**
   * @param {Record<string, unknown>} body
   * @returns {Promise<{ ok: true } | { ok: false; status: number; error: string }>}
   */
  async processReading(body) {
    const parsed = parseReadingPayload(body);
    if (!parsed.ok) {
      return { ok: false, status: 400, error: parsed.error };
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
    } = parsed.value;

    let reading;
    try {
      reading = await this._astrology.fetchTarotPredictions({
        love: card1,
        career: card2,
        finance: card3,
      });
    } catch (err) {
      console.error("AstrologyAPI tarot_predictions failed:", err.message);
      return {
        ok: false,
        status: 502,
        error: "Failed to fetch tarot reading.",
      };
    }

    const [dobMonth, dobDay] = (dob || "").split("/");
    const sunSign = dobMonth && dobDay ? getSunSign(dobMonth, dobDay) : null;
    let sunPrediction = {};

    if (sunSign) {
      const sunData = await this._astrology.fetchSunSignDaily(sunSign);
      sunPrediction = sunData.prediction || {};
    }

    try {
      await this._kit.ensureCustomFields();

      await this._kit.upsertSubscriber({
        name,
        email,
        dob,
        gender,
        card1Name,
        card2Name,
        card3Name,
        loveReading: reading.love || "",
        lifeReading: reading.career || "",
        wealthReading: reading.finance || "",
        loveCardImage: getCardImageUrl(card1),
        lifeCardImage: getCardImageUrl(card2),
        wealthCardImage: getCardImageUrl(card3),
        sunSign: sunSign || "",
        sunPersonalLife: sunPrediction.personal_life || "",
        sunProfession: sunPrediction.profession || "",
        sunHealth: sunPrediction.health || "",
        sunEmotions: sunPrediction.emotions || "",
        sunTravel: sunPrediction.travel || "",
        sunLuck: sunPrediction.luck || "",
      });

      await this._kit.tagSubscriber(email);

      console.log(
        `Reading sent | Cards: ${card1Name} / ${card2Name} / ${card3Name}`,
      );
      return { ok: true };
    } catch (err) {
      console.error("Error processing reading:", err);
      return {
        ok: false,
        status: 500,
        error: "Internal server error.",
      };
    }
  }
}

module.exports = { ReadingApplicationService };

"use strict";

const FETCH_TIMEOUT_MS = 20_000;

const REQUIRED_FIELDS = [
  { label: "Date of Birth", key: "date_of_birth" },
  { label: "Gender", key: "gender" },
  { label: "Love Card", key: "love_card" },
  { label: "Life Card", key: "life_card" },
  { label: "Wealth Card", key: "wealth_card" },
  { label: "Love Reading", key: "love_reading" },
  { label: "Life Reading", key: "life_reading" },
  { label: "Wealth Reading", key: "wealth_reading" },
  { label: "Love Card Image", key: "love_card_image" },
  { label: "Life Card Image", key: "life_card_image" },
  { label: "Wealth Card Image", key: "wealth_card_image" },
  { label: "Sun Sign", key: "sun_sign" },
  { label: "Sun Personal Life", key: "sun_personal_life" },
  { label: "Sun Profession", key: "sun_profession" },
  { label: "Sun Health", key: "sun_health" },
  { label: "Sun Emotions", key: "sun_emotions" },
  { label: "Sun Travel", key: "sun_travel" },
  { label: "Sun Luck", key: "sun_luck" },
];

/**
 * Kit (ConvertKit) API — custom fields, subscribers, tags.
 */
class KitService {
  /**
   * @param {{ kitApiKey: string | undefined; kitTagName?: string }} config
   */
  constructor(config) {
    this._apiKey = config.kitApiKey;
    this._tagName = config.kitTagName || "soul-mirror-leads";
  }

  async _kitFetch(method, endpoint, body = null) {
    const opts = {
      method,
      headers: {
        "X-Kit-Api-Key": this._apiKey,
        "Content-Type": "application/json",
      },
    };
    if (body) opts.body = JSON.stringify(body);

    const res = await fetch(`https://api.kit.com/v4/${endpoint}`, {
      ...opts,
      signal: AbortSignal.timeout(FETCH_TIMEOUT_MS),
    });
    let data;
    try {
      data = await res.json();
    } catch {
      const err = new Error(
        `Kit API ${method} ${endpoint}: invalid JSON (${res.status})`,
      );
      err.status = res.status;
      throw err;
    }
    if (!res.ok) {
      const msg = Array.isArray(data.errors)
        ? data.errors.join("; ")
        : typeof data.message === "string"
          ? data.message
          : res.statusText;
      const err = new Error(
        `Kit API ${method} ${endpoint}: ${msg || res.status}`,
      );
      err.status = res.status;
      err.kitResponse = data;
      throw err;
    }
    return data;
  }

  async ensureCustomFields() {
    const res = await this._kitFetch("GET", "custom_fields");
    const existing = (res.custom_fields || []).map((f) => f.key);

    for (const field of REQUIRED_FIELDS) {
      if (!existing.includes(field.key)) {
        await this._kitFetch("POST", "custom_fields", { label: field.label });
        console.log(`Created KIT custom field: ${field.label}`);
      }
    }
  }

  /**
   * @param {Record<string, string | undefined>} data
   */
  async upsertSubscriber(data) {
    const {
      name,
      email,
      dob,
      gender,
      card1Name,
      card2Name,
      card3Name,
      loveReading,
      lifeReading,
      wealthReading,
      loveCardImage,
      lifeCardImage,
      wealthCardImage,
      sunSign,
      sunPersonalLife,
      sunProfession,
      sunHealth,
      sunEmotions,
      sunTravel,
      sunLuck,
    } = data;

    const body = {
      email_address: email,
      first_name: name,
      fields: {
        date_of_birth: dob || "",
        gender: gender || "",
        love_card: card1Name,
        life_card: card2Name,
        wealth_card: card3Name,
        love_reading: loveReading,
        life_reading: lifeReading,
        wealth_reading: wealthReading,
        love_card_image: loveCardImage,
        life_card_image: lifeCardImage,
        wealth_card_image: wealthCardImage,
        sun_sign: sunSign || "",
        sun_personal_life: sunPersonalLife || "",
        sun_profession: sunProfession || "",
        sun_health: sunHealth || "",
        sun_emotions: sunEmotions || "",
        sun_travel: sunTravel || "",
        sun_luck: sunLuck || "",
      },
    };

    const res = await this._kitFetch("POST", "subscribers", body);
    return res.subscriber?.id;
  }

  /**
   * @param {string} email
   */
  async tagSubscriber(email) {
    if (!email) return;

    const tagsRes = await this._kitFetch("GET", "tags");
    let tag = (tagsRes.tags || []).find((t) => t.name === this._tagName);

    if (!tag) {
      const created = await this._kitFetch("POST", "tags", {
        name: this._tagName,
      });
      tag = created.tag;
      console.log(`Created KIT tag: ${this._tagName}`);
    }

    await this._kitFetch("POST", `tags/${tag.id}/subscribers`, {
      email_address: email,
    });
  }
}

module.exports = { KitService };

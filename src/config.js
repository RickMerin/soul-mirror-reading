"use strict";

module.exports = {
  port: Number(process.env.PORT) || 3000,
  astroUserId: process.env.ASTRO_USER_ID,
  astroApiKey: process.env.ASTRO_API_KEY,
  kitApiKey: process.env.KIT_API_KEY,
  kitTagName: process.env.KIT_TAG_NAME || "soul-mirror-leads",
  /** Optional: your Kit account subdomain + form UID for an optional client-side embed (must match the same Kit account as KIT_API_KEY). */
  kitEmbedSubdomain: process.env.KIT_EMBED_SUBDOMAIN,
  kitEmbedFormUid: process.env.KIT_EMBED_FORM_UID,
};

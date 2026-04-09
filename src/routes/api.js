"use strict";

const express = require("express");
const config = require("../config");
const { AstrologyApiClient } = require("../services/astrologyApi");
const { KitService } = require("../services/kit");
const {
  ReadingApplicationService,
} = require("../services/readingApplicationService");

const router = express.Router();

const readingService = new ReadingApplicationService({
  astrology: new AstrologyApiClient(config.astroUserId, config.astroApiKey),
  kit: new KitService(config),
});

/** Non-secret client hints (Kit embed is public in HTML anyway). */
router.get("/public-config", (req, res) => {
  res.json({
    kitEmbedSubdomain: config.kitEmbedSubdomain || null,
    kitEmbedFormUid: config.kitEmbedFormUid || null,
  });
});

router.post("/reading", async (req, res) => {
  const result = await readingService.processReading(req.body);
  if (!result.ok) {
    return res.status(result.status).json({ error: result.error });
  }
  return res.json({ success: true });
});

module.exports = router;

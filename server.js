"use strict";

require("dotenv").config({ path: require("path").join(__dirname, ".env") });

const config = require("./src/config");
const { createApp } = require("./src/app");

const app = createApp();

app.listen(config.port, () => {
  console.log(
    `Soul Mirror Reading server running at http://localhost:${config.port}`,
  );
  console.log(
    `AstrologyAPI User ID: ${config.astroUserId ? "set" : "MISSING — add to .env"}`,
  );
  console.log(
    `AstrologyAPI Key:     ${config.astroApiKey ? "set" : "MISSING — add to .env"}`,
  );
  console.log(`KIT API Key:          ${config.kitApiKey ? "set" : "MISSING"}`);
});

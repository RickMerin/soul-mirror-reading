"use strict";

const path = require("path");
const express = require("express");
const { registerRoutes } = require("./routes");

function createApp() {
  const app = express();
  app.use(express.json());
  registerRoutes(app);

  const publicDir = path.join(__dirname, "public");
  app.use(express.static(publicDir));

  return app;
}

module.exports = { createApp };

"use strict";

const apiRouter = require("./api");

/**
 * Registers all HTTP routes on the Express app (API first, then static in app.js).
 * @param {import('express').Express} app
 */
function registerRoutes(app) {
  app.use("/api", apiRouter);
}

module.exports = { registerRoutes };

# Soul Mirror Reading

A small **Node.js + Express** app that serves static marketing pages and a **tarot reading flow**: users pick three cards, the server calls **AstrologyAPI** for interpretations, enriches with **sun-sign daily** text when date of birth is provided, then **upserts the lead in Kit** (ConvertKit) and applies a tag so your **Kit automation** can email the personalized reading.

## Prerequisites

- **Node.js** 18 or newer (`package.json` → `engines`)
- Accounts / keys for:
  - [AstrologyAPI](https://www.astrologyapi.com/) (`ASTRO_USER_ID`, `ASTRO_API_KEY`)
  - [Kit](https://developers.kit.com/) API v4 (`KIT_API_KEY`)
- In Kit: an automation triggered by the tag **`soul-mirror-leads`** (or set `KIT_TAG_NAME` in `.env` to match your setup)

## Setup

1. Clone the repository and install dependencies:

   ```bash
   npm install
   ```

2. Copy `.env.example` to `.env` (e.g. `cp .env.example .env` or on Windows cmd `copy .env.example .env`), then edit `.env`.

3. Edit `.env` and set `ASTRO_USER_ID`, `ASTRO_API_KEY`, and `KIT_API_KEY`. Optionally set `PORT` (defaults to `3000`). The root `.gitignore` excludes `.env` so secrets are not committed by mistake.

4. Run the server:

   ```bash
   npm run dev
   ```

   Or without watch:

   ```bash
   npm start
   ```

5. Open the app (default): [http://localhost:3000/](http://localhost:3000/) (landing funnel is `src/public/index.html`)

> **WAMP / Apache:** This project is a **standalone Node** server. You do not need Apache for the Node app; run Node separately. You can still keep the repo under `www` if that fits your workflow.

## Environment variables

| Variable              | Required | Purpose                                                                                               |
| --------------------- | -------- | ----------------------------------------------------------------------------------------------------- |
| `ASTRO_USER_ID`       | Yes\*    | AstrologyAPI user id for Basic auth                                                                   |
| `ASTRO_API_KEY`       | Yes\*    | AstrologyAPI key                                                                                      |
| `KIT_API_KEY`         | Yes      | Kit API v4 key (`X-Kit-Api-Key`)                                                                      |
| `PORT`                | No       | HTTP port (default `3000`)                                                                            |
| `KIT_TAG_NAME`        | No       | Kit tag after upsert (default `soul-mirror-leads`)                                                    |
| `KIT_EMBED_SUBDOMAIN` | No       | Your Kit site subdomain (only if you use the optional client embed; must match `KIT_API_KEY` account) |
| `KIT_EMBED_FORM_UID`  | No       | Kit form UID from the embed script URL (pairs with `KIT_EMBED_SUBDOMAIN`)                             |

\*Required for real readings; without them, tarot/sun API calls fail and the route returns a safe error to the client.

## Scripts

| Command       | Description                                       |
| ------------- | ------------------------------------------------- |
| `npm start`   | Run `server.js`                                   |
| `npm run dev` | Run with `node --watch` (restart on file changes) |
| `npm test`    | Run `node:test` suite (`test/`)                   |

## Project layout

| Path                  | Role                                                                              |
| --------------------- | --------------------------------------------------------------------------------- |
| `server.js`           | Entry: loads env, creates the app from `src/app.js`, listens on `PORT`            |
| `src/app.js`          | Express app factory (`createApp`): JSON body, routes, static files                |
| `src/routes/index.js` | Registers all routers (API under `/api`)                                          |
| `src/routes/api.js`   | `POST /api/reading` — AstrologyAPI + Kit                                          |
| `src/services/kit.js` | Kit API v4 helpers (custom fields, subscriber, tags)                              |
| `src/lib/`            | Pure helpers (e.g. sun sign, tarot card image URLs)                               |
| `src/public/`         | Static site: `index.html` (funnel), `sales-page.html`, `thankyou.html`, templates |
| `src/public/cards/`   | Optional local images referenced by pages (e.g. sales mockups)                    |
| `test/`               | Automated tests (`npm test`)                                                      |

## Business flow (request path)

1. User submits **name**, **email**, optional **DOB** / **gender**, and **three card ids** (and display names) from the landing page (`src/public/index.html`).
2. **`POST /api/reading`** validates required fields.
3. Server calls AstrologyAPI **`tarot_predictions`**: card 1 → love, card 2 → career/life, card 3 → finance/wealth.
4. If DOB parses to month/day, server derives **sun sign** and calls **`sun_sign_prediction/daily/{sign}`** for extra lines (personal life, profession, health, etc.).
5. Server ensures **Kit custom fields** exist, then **creates/updates the subscriber** with readings and image URLs (see `src/services/kit.js`).
6. Server **tags** the subscriber with **`soul-mirror-leads`** so Kit can send the email via your automation. The Node app does **not** send `src/public/email-template.html` itself — copy that HTML into your Kit automation email (or sequence) so the message matches the template; merge tags use Kit’s `subscriber` fields (see that file).

Card image URLs are built from known slugs → `trustedtarot.com` PNG paths (`src/lib/tarotCards.js`).

## API

### `POST /api/reading`

- **Body (JSON):** `name`, `email`, `card1`, `card2`, `card3` (required); `dob`, `gender`, `card1Name`, `card2Name`, `card3Name` (optional but used when present).
- **Success:** `{ "success": true }`
- **Errors:** `400` validation, `502` upstream Astrology failure, `500` unexpected server error (generic message to client; details logged server-side only).

## Development notes

- **Secrets:** Only in `.env`, never in HTML or committed source. See `.cursor/rules/server-express-security.mdc` and `soul-mirror-engineering.mdc`.
- **Docs:** When you change setup, env vars, routes, or integrations, update `README.md` and `.env.example` together — see `.cursor/rules/readme-documentation-sync.mdc` (Cursor applies this when editing `package.json`, `server.js`, `src/`, or `.env.example`).
- **Tests:** Run `npm test` before shipping; extend `test/` when you add extractable logic or HTTP behavior worth locking in.

## License

Private / unlicensed unless you add a `LICENSE` file.

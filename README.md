# Soul Mirror Reading

Tarot funnel: visitors pick three cards, submit a form, and the server fetches AstrologyAPI readings, enriches with optional sun-sign data, and upserts a **Kit** (ConvertKit) subscriber plus a tag so your email automation runs.

## Prerequisites

- **PHP 8.2+** with `curl`, `json`, `mbstring`
- **Composer** 2.x
- **Apache** with `mod_rewrite` (typical on cPanel/WAMP), or another server that routes `/api/reading` to `public/api/reading.php`

## Setup

1. Clone the repository.
2. From the project root:

   ```bash
   composer install
   ```

3. Copy `.env.example` to `.env` and fill in:

   | Variable                       | Purpose                                                               |
   | ------------------------------ | --------------------------------------------------------------------- |
   | `ASTRO_USER_ID`                | AstrologyAPI user id (Basic auth)                                     |
   | `ASTRO_API_KEY`                | AstrologyAPI key                                                      |
   | `KIT_API_KEY`                  | Kit API v4 key (`X-Kit-Api-Key`)                                      |
   | `KIT_TAG_NAME`                 | Tag applied after upsert (default `soul-mirror-leads`)                |
   | `KIT_TAG_NAME_BUYER`           | Tag for ClickBank buyer when INS has no SKU (defaults to `KIT_TAG_NAME`) |
   | `DB_HOST`, `DB_PORT`           | MySQL host/port for lead and member data                              |
   | `DB_NAME`, `DB_USER`, `DB_PASS`| Database credentials for lead capture, purchases, and member login    |
| `APP_BASE_URL`                 | Absolute public base URL (include subdirectory for WAMP installs)     |

4. Point the **web server document root** at the `public/` directory (recommended).  
   If you cannot change the document root (some local WAMP setups), an extra `.htaccess` in the **repository root** rewrites requests into `public/`.

5. For WAMP subdirectory installs (`http://localhost/soul-mirror-reading`), set:

   ```env
   APP_BASE_URL=http://localhost/soul-mirror-reading
   ```

## Member portal URLs (local WAMP)

- `http://localhost/soul-mirror-reading/member`
- `http://localhost/soul-mirror-reading/login.php`

## Scripts

| Command            | Description                                                                       |
| ------------------ | --------------------------------------------------------------------------------- |
| `composer install` | Install PHP dependencies (run after clone or when `composer.json` changes)        |
| `composer test`    | Run PHPUnit (`tests/`)                                                            |
| `composer migrate` | Apply pending SQL migrations from `database/migrations/*.sql`                     |
| `npm ci`           | Install Node dependencies for CSS/email builds (see `package.json`)               |
| `npm run build`    | Bundle CSS to `public/assets/*.min.css` and generate `public/email-template.html` |
| `php scripts/deliver-readings.php --limit=5` | Batch deliver pending personalized reading PDFs (cron worker) |

## Reading PDF delivery (cron)

After a buyer’s purchase is approved, a worker generates their personalized PDF (HTML → PDF API → S3 → Kit delivery email). The worker is **run-and-exit** — not a daemon.

**Option 1 — cPanel cron (preferred, $0):** add a job every minute:

```bash
php /home/USER/path/to/soul-mirror-reading/scripts/deliver-readings.php --limit=5
```

Use the full server path to PHP and the project. A 24-hour delivery promise is fine with every 1–5 minutes.

**Option 2 — HTTP + external scheduler (if CLI cron is flaky):** set `DELIVERY_CRON_KEY` in `.env` to a long random secret, then ping once per minute:

```http
GET /api/run-delivery.php?key=YOUR_SECRET&limit=1
```

Use a free scheduler (cron-job.org, GitHub Actions `schedule`, Cloudflare Cron Trigger, UptimeRobot, etc.). Each request processes up to `limit` readings (max 5); default `limit=1` keeps web requests bounded.

Requires AWS S3, PDF Generator API, database, and Kit vars (see `.env.example`). Manual recovery: `php scripts/deliver-reading-one.php --purchase-id=ID`.

## GitHub Actions deploy secrets

To run migrations during deploy (without cPanel terminal access), add these repository or environment secrets:

- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`

If testing and production use different databases, define environment-specific secrets and scope each workflow/job to the correct environment.

## Email template (build)

The funnel sends mail through **Kit** automation; PHP does **not** read these files at runtime. They exist so you can paste or sync a single HTML file with inlined styles into your ESP.

| File                                     | Role                                                                                               |
| ---------------------------------------- | -------------------------------------------------------------------------------------------------- |
| `frontend/email/email-template.src.html` | **Source:** HTML shell; must contain the placeholder `@@SOUL_MIRROR_EMAIL_CSS@@`.                  |
| `public/email-template.html`             | **Output** from `npm run build`: placeholder replaced with minified CSS from the email stylesheet. |

Styles for the email shell live under `frontend/styles/email/`. If `public/email-template.html` is missing or stale, run `npm run build` (CI runs this before FTP deploy).

## Project layout

| Path                   | Role                                                                              |
| ---------------------- | --------------------------------------------------------------------------------- |
| `frontend/`            | Source CSS and email HTML; `npm run build` writes compiled assets under `public/` |
| `public/`              | Web root: PHP pages, static assets, `cards/`, `api/reading.php`                   |
| `src/`                 | PHP application (PSR-4 `App\`)                                                    |
| `tests/`               | PHPUnit tests                                                                     |
| `docs/ARCHITECTURE.md` | Deeper structure, request flow, debugging                                         |
| `.env`                 | Secrets (never commit)                                                            |

## Business flow

1. User completes the three-card experience on the main reading page and submits name, email, DOB, gender, and card ids/names.
2. Browser `POST`s JSON to **`/api/reading`** (same origin).
3. PHP runs **AstrologyAPI** `tarot_predictions` (love / career / finance slots from the three cards).
4. If DOB parses to month/day, **sun sign** daily prediction is requested (non-fatal if it fails).
5. **Kit**: ensure custom fields → create/update subscriber with readings and image URLs → apply tag → automation sends email.

## API

- **`POST /api/reading`**
  - **Body (JSON):** `name`, `email`, `dob`, `gender`, `card1`, `card2`, `card3`, `card1Name`, `card2Name`, `card3Name`
  - **200:** `{ "success": true }`
  - **400:** `{ "error": "..." }` (validation / bad JSON)
  - **502:** `{ "error": "Failed to fetch tarot reading." }` (upstream AstrologyAPI failure)
  - **500:** generic internal error message for the client; details in server logs only

- **`GET /api/run-delivery.php`** (delivery worker; requires `DELIVERY_CRON_KEY`)
  - **Query:** `key` (required), `limit` (optional, default `1`, max `5`)
  - **200:** `{ "ok": true, "queued": 0, "success": 0, "failed": 0, "purchase_ids": [] }`
  - **401:** invalid or missing `key`
  - **503:** endpoint disabled or worker not configured

Security and layering conventions are summarized in `.cursor/rules/` (see `soul-mirror-engineering.mdc` and `php-api-security.mdc`).

---
name: php-soul-mirror
description: >-
  PHP 8.2+ stack for Soul Mirror Reading: PSR-4, Guzzle, PHPUnit, phpdotenv, thin
  public/api entrypoints, OOP services, PHPDoc, and cPanel-friendly deployment.
  Use when editing src/, public/api/, Composer, tests, or server-side behavior.
---

# Soul Mirror — PHP development skill

## Stack

- **PHP** 8.2+ (`declare(strict_types=1);` in new files).
- **Composer** for autoloading (`App\` → `src/`) and dependencies (Guzzle, phpdotenv, PHPUnit).
- **Apache** + `mod_rewrite`: `/api/reading` → `public/api/reading.php`; document root should be `public/` when possible.

## Conventions

1. **Thin controllers**: `public/api/*.php` only bootstraps autoload, loads config, instantiates services, returns JSON — no business rules inline.
2. **Orchestration**: Put multi-step flows in `App\Application\*` (e.g. `ReadingOrchestrator`).
3. **External HTTP**: Implement in `App\Services\*` with `ClientInterface` injected for tests.
4. **Pure logic**: `App\Domain\*` — no Guzzle; unit-test everything meaningful here.
5. **Documentation**: PHPDoc on public methods and non-obvious array shapes; link behavior to `docs/ARCHITECTURE.md` when adding routes or integrations.
6. **Secrets**: Only `App\Config\AppConfig` (or env) — never literals in commits.

## Testing

- Add/update **PHPUnit** tests in `tests/` for changed domain logic or request validation.
- Run before finishing:

  ```bash
  composer test
  ```

- Prefer fast unit tests; use Guzzle mock handlers for HTTP integration tests when needed.

## Quality bar

- Match existing naming and file layout.
- Stable JSON error messages for clients; log details only server-side without PII/secrets.
- After structural changes, update `README.md` and `.env.example` if env or setup changed.

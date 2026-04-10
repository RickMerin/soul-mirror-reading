# Ship with quality (modern + scalable + secure + tested)

Use this when implementing or changing features in this repo.

## Instructions for the agent

1. **Clarify scope** — Confirm files and behavior touched; avoid unrelated refactors.

2. **Structure** — Prefer clear layers: routing → validation → domain/services → external HTTP. Reuse helpers; do not duplicate request/response or mapping logic.

3. **Security** — No secrets in client HTML/JS or committed source. Validate server-side inputs; avoid logging PII/secrets; safe error messages to clients.

4. **Tests** — Add or update PHPUnit tests for new logic and important edge cases. Cover happy path + at least one failure/validation case when relevant.

5. **Verify** — Run `composer test`. If routes or `.htaccess` changed, confirm the vhost document root is `public/` (or root `.htaccess` rewrite) and `/api/reading` still hits `public/api/reading.php`.

6. **Summary** — Reply with what changed, which tests were added/updated, and any follow-ups (e.g. env vars).

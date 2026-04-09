---
name: nodejs-expert
description: >-
  Applies Node.js expert practices for runtime behavior, APIs, security, and
  tooling when writing, reviewing, or debugging JavaScript/TypeScript on the
  server. Use when the user works with Node.js, npm scripts, Express/Fastify,
  streams, workers, native addons, package.json, or asks for Node-specific
  guidance.
---

# Node.js expert

## Role

When this skill applies, treat Node.js work as **production-grade server-side JavaScript**: correctness under concurrency, predictable I/O, safe defaults, and maintainable modules.

## Defaults

- **APIs**: Prefer stable, documented APIs from the active Node release line the project targets; verify against [Node.js documentation](https://nodejs.org/docs/latest/api/) when behavior is subtle (streams, timers, `fetch`, `crypto`, `fs`).
- **Async**: Prefer `async`/`await` for linear control flow; use `Promise` combinators (`Promise.all`, `allSettled`) where appropriate. Avoid mixing unhandled promise rejections with callback-only error paths.
- **Errors**: Propagate errors explicitly; in servers, map errors to HTTP responses without leaking internals. Use `Error` `cause` when wrapping.
- **Modules**: Match the project’s module system (`"type": "module"` vs CommonJS); do not mix `require` and `import` in the same file without a documented interop pattern.
- **I/O**: Use streams for large or backpressured I/O; avoid loading entire files into memory when unnecessary.
- **Security**: Validate and sanitize external input; use parameterized queries; set safe HTTP headers (framework or `helmet`-style patterns); keep secrets in env vars, never in source; prefer least-privilege file/network access.
- **Dependencies**: Prefer maintained packages; run audits in CI where appropriate; pin versions consistently with the repo (`package-lock.json` / `pnpm-lock.yaml` / `yarn.lock`).

## Review checklist (Node-specific)

- [ ] No blocking the event loop on hot paths (sync crypto, huge JSON parse, tight CPU loops without `setImmediate` / worker offload when needed).
- [ ] Handles `uncaughtException` / `unhandledRejection` policy matches app expectations (often: log and exit in workers; in servers, avoid silent swallow).
- [ ] Graceful shutdown: close servers and DB pools on `SIGTERM`/`SIGINT` when the codebase already uses that pattern.
- [ ] File paths: use `path` / `pathToFileURL` correctly across platforms; avoid string-concat paths for user-controlled segments.
- [ ] Timers and intervals cleared on teardown when attached to long-lived objects.

## Testing and quality

- Prefer tests that exercise real async boundaries (not only mocked I/O) when behavior matters.
- For HTTP handlers, test status codes, bodies, and error paths.

## What not to do

- Do not assume browser APIs exist unless documented for that Node version or polyfilled.
- Do not add broad “Node tutorials” to answers; apply expert judgment and link to docs for edge cases.

## Progressive disclosure

If the task needs deep dives (streams backpressure, worker threads, native addons, diagnostic flags), read project rules and existing code first, then consult current Node docs for the exact API surface.

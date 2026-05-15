const STORAGE_KEY = "smr_countdown_start";
const DURATION_SECONDS = 25 * 60;

function rollAnchorIfNeeded(startTime) {
  let elapsed = Math.floor((Date.now() - startTime) / 1000);
  if (elapsed < DURATION_SECONDS) {
    return startTime;
  }
  const skips = Math.floor(elapsed / DURATION_SECONDS);
  const next = startTime + skips * DURATION_SECONDS * 1000;
  localStorage.setItem(STORAGE_KEY, String(next));
  return next;
}

export function initSalesCountdown() {
  const minEl = document.getElementById("countdownMinutes");
  const secEl = document.getElementById("countdownSeconds");
  if (!minEl && !secEl) return;

  let startTime = Number.parseInt(localStorage.getItem(STORAGE_KEY) ?? "", 10);
  if (!Number.isFinite(startTime) || startTime > Date.now()) {
    startTime = Date.now();
    localStorage.setItem(STORAGE_KEY, String(startTime));
  }
  startTime = rollAnchorIfNeeded(startTime);

  const tick = () => {
    startTime = rollAnchorIfNeeded(startTime);
    const elapsed = Math.floor((Date.now() - startTime) / 1000);
    const remaining = Math.max(0, DURATION_SECONDS - elapsed);
    const minutes = Math.floor(remaining / 60);
    const seconds = remaining % 60;
    if (minEl) minEl.textContent = String(minutes).padStart(2, "0");
    if (secEl) secEl.textContent = String(seconds).padStart(2, "0");
  };

  tick();
  window.setInterval(tick, 1000);
}

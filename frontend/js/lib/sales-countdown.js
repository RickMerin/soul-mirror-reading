const STORAGE_KEY = "smr_countdown_start";
const DURATION_SECONDS = 45 * 60;

export function initSalesCountdown() {
  const minEl = document.getElementById("countdownMinutes");
  const secEl = document.getElementById("countdownSeconds");
  if (!minEl && !secEl) return;

  let startTime = Number.parseInt(localStorage.getItem(STORAGE_KEY) ?? "", 10);
  if (!Number.isFinite(startTime)) {
    startTime = Date.now();
    localStorage.setItem(STORAGE_KEY, String(startTime));
  }

  const tick = () => {
    const elapsed = Math.floor((Date.now() - startTime) / 1000);
    let remaining = DURATION_SECONDS - elapsed;
    if (remaining < 0) remaining = 0;
    const minutes = Math.floor(remaining / 60);
    const seconds = remaining % 60;
    if (minEl) minEl.textContent = String(minutes).padStart(2, "0");
    if (secEl) secEl.textContent = String(seconds).padStart(2, "0");
  };

  tick();
  window.setInterval(tick, 1000);
}

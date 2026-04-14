import { initDreamBackground } from "./lib/dream-background.js";

initDreamBackground({
  sparkleMobile: 40,
  sparkleDesktop: 62,
  shootingMobile: 4,
  shootingDesktop: 6,
  glintChance: 0.14,
  sizeRange: 2.3,
  travelBase: 170,
  travelRange: 150,
  lenBase: 110,
  lenRange: 110,
  pointerParallax: false,
});

const salesPageUrl = new URL("sales-page.php", window.location.href).href;
const countdownEl = document.getElementById("redirectCountdown");
const countdownCopyEl = document.getElementById("redirectCopy");
const ringProgressEl = document.getElementById("countdownRingProgress");
let secondsLeft = 10;
const totalSeconds = 10;
const ringRadius = 48;
const ringCircumference = 2 * Math.PI * ringRadius;

if (ringProgressEl) {
  ringProgressEl.style.strokeDasharray = String(ringCircumference);
  ringProgressEl.style.strokeDashoffset = "0";
}

function updateCountdownVisual(seconds) {
  if (countdownEl) countdownEl.textContent = String(seconds);
  if (countdownCopyEl) {
    countdownCopyEl.textContent = `Redirecting to your next step in ${seconds} second${seconds === 1 ? "" : "s"}.`;
  }
  if (ringProgressEl) {
    const elapsed = totalSeconds - seconds;
    const progress = Math.min(Math.max(elapsed / totalSeconds, 0), 1);
    ringProgressEl.style.strokeDashoffset = String(
      progress * ringCircumference,
    );
  }
}

updateCountdownVisual(secondsLeft);

const countdownTimer = setInterval(() => {
  secondsLeft -= 1;
  if (secondsLeft >= 0) updateCountdownVisual(secondsLeft);
  if (secondsLeft <= 0) {
    clearInterval(countdownTimer);
  }
}, 1000);

setTimeout(() => {
  window.location.href = salesPageUrl;
}, 10000);

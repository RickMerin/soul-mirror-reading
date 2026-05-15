import { initDreamBackground } from "./lib/dream-background.js";
import { initSalesCountdown } from "./lib/sales-countdown.js";
import { initMirrorBlockReveal } from "./lib/sales-mirror-reveal.js";
import { initSalesPersonalization } from "./lib/sales-personalization.js";

initDreamBackground({
  sparkleMobile: 48,
  sparkleDesktop: 78,
  shootingMobile: 4,
  shootingDesktop: 8,
  glintChance: 0.16,
  sizeRange: 2.4,
  travelBase: 180,
  travelRange: 170,
  lenBase: 120,
  lenRange: 130,
  pointerParallax: true,
});

initSalesPersonalization();
initSalesCountdown();
initMirrorBlockReveal();

document.querySelectorAll(".cta, .cta-btn").forEach((btn) => {
  btn.addEventListener("click", function (e) {
    const href = btn.getAttribute("href") ?? "";
    if (href.startsWith("#")) {
      e.preventDefault();
      document.querySelector(href)?.scrollIntoView({ behavior: "smooth" });
    }
  });
});

(function initScrollReveals() {
  if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") return;
  if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;

  gsap.registerPlugin(ScrollTrigger);

  gsap.utils.toArray(".js-reveal").forEach(function (el) {
    gsap.from(el, {
      y: 28,
      autoAlpha: 0,
      duration: 0.85,
      ease: "power2.out",
      scrollTrigger: {
        trigger: el,
        start: "top 88%",
        once: true,
      },
    });
  });
})();

import { initDreamBackground } from "./lib/dream-background.js";

function toggleReadVersion(btn) {
  const panel = document.getElementById("read-version");
  if (!panel) return;
  const open = panel.style.display === "block";
  panel.style.display = open ? "none" : "block";
  btn.setAttribute("aria-expanded", open ? "false" : "true");
  btn.textContent = open
    ? "☽  Prefer to read instead?  ☾"
    : "☽  Hide reading  ☾";
  if (!open) panel.scrollIntoView({ behavior: "smooth", block: "start" });
}

document.querySelector(".read-toggle")?.addEventListener("click", function () {
  toggleReadVersion(this);
});

document
  .querySelector(".read-toggle-collapse")
  ?.addEventListener("click", function () {
    const btn = document.querySelector(".read-toggle");
    if (btn) toggleReadVersion(btn);
  });

document.querySelectorAll(".cta-btn").forEach((btn) => {
  btn.addEventListener("click", function (e) {
    const href = btn.getAttribute("href") ?? "";
    if (href.startsWith("#")) {
      e.preventDefault();
      document.querySelector(href)?.scrollIntoView({ behavior: "smooth" });
    }
    // http(s) URLs and mailto:/tel: leave default navigation intact
  });
});

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

(function initScrollReveals() {
  if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined")
    return;
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

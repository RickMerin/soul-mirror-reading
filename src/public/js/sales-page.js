"use strict";

/**
 * Sales page: CTA placeholders and read-alternate toggle (inline onclick).
 */
class SalesPageUi {
  wireCtaPlaceholders() {
    document.querySelectorAll(".cta-btn").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault();
      });
    });
  }

  toggleReadVersion(btn) {
    const panel = document.getElementById("read-version");
    const open = panel.style.display === "block";
    panel.style.display = open ? "none" : "block";
    btn.setAttribute("aria-expanded", String(!open));
    btn.textContent = open
      ? "☽  Prefer to read instead?  ☾"
      : "☽  Hide reading  ☾";
    if (!open) panel.scrollIntoView({ behavior: "smooth", block: "start" });
  }

  init() {
    this.wireCtaPlaceholders();
    window.toggleReadVersion = (b) => this.toggleReadVersion(b);
  }
}

new SalesPageUi().init();

export function initMirrorBlockReveal() {
  document.querySelectorAll(".block-reveal-wrapper").forEach((wrapper) => {
    const target = /** @type {HTMLElement} */ (wrapper);
    const activate = () => {
      target.classList.add("revealed");
    };
    target.addEventListener("click", activate);
    target.addEventListener("keydown", (event) => {
      if (event.key === "Enter" || event.key === " ") {
        event.preventDefault();
        activate();
      }
    });
    if (!target.hasAttribute("tabindex")) {
      target.setAttribute("tabindex", "0");
    }
    if (!target.hasAttribute("role")) {
      target.setAttribute("role", "button");
    }
  });
}

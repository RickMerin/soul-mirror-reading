/**
 * @param {object} [opts]
 * @param {number} [opts.sparkleMobile]
 * @param {number} [opts.sparkleDesktop]
 * @param {number} [opts.shootingMobile]
 * @param {number} [opts.shootingDesktop]
 * @param {number} [opts.glintChance]
 * @param {number} [opts.sizeRange] multiplier for random size (1 + Math.random() * sizeRange)
 * @param {number} [opts.travelBase]
 * @param {number} [opts.travelRange]
 * @param {number} [opts.lenBase]
 * @param {number} [opts.lenRange]
 * @param {boolean} [opts.pointerParallax]
 */
export function initDreamBackground(opts = {}) {
  const {
    sparkleMobile = 48,
    sparkleDesktop = 78,
    shootingMobile = 4,
    shootingDesktop = 8,
    glintChance = 0.16,
    sizeRange = 2.4,
    travelBase = 180,
    travelRange = 170,
    lenBase = 120,
    lenRange = 130,
    pointerParallax = true,
  } = opts;

  const sparkleWrap = document.getElementById("dreamSparkles");
  const shootingWrap = document.getElementById("dreamShooting");
  if (!sparkleWrap || !shootingWrap) return;

  const narrow = window.innerWidth < 700;
  const sparkleCount = narrow ? sparkleMobile : sparkleDesktop;
  for (let i = 0; i < sparkleCount; i++) {
    const dot = document.createElement("span");
    dot.className = "sparkle";
    dot.style.left = `${Math.random() * 100}%`;
    dot.style.top = `${Math.random() * 100}%`;
    dot.style.setProperty("--dur", `${3.2 + Math.random() * 4.8}s`);
    dot.style.setProperty("--delay", `${Math.random() * 8}s`);
    dot.style.opacity = `${0.2 + Math.random() * 0.65}`;
    const size = 1 + Math.random() * sizeRange;
    dot.style.width = `${size}px`;
    dot.style.height = `${size}px`;
    if (Math.random() < glintChance) dot.classList.add("glint");
    sparkleWrap.appendChild(dot);
  }

  const shootingCount = narrow ? shootingMobile : shootingDesktop;
  for (let i = 0; i < shootingCount; i++) {
    const star = document.createElement("span");
    star.className = "shooting-star";
    const angle = 28 + Math.random() * 14;
    const travel = travelBase + Math.random() * travelRange;
    const rad = angle * (Math.PI / 180);
    const inTopBand = Math.random() < 0.65;
    const side = i % 2 === 0 ? "right" : "left";
    const xZone =
      side === "right" ? 74 + Math.random() * 28 : -18 + Math.random() * 26;
    star.style.left = `${xZone}%`;
    star.style.top = inTopBand
      ? `${2 + Math.random() * 20}%`
      : `${80 + Math.random() * 14}%`;
    star.style.setProperty("--angle", `${angle}deg`);
    star.style.setProperty("--dx", `${Math.cos(rad) * travel}px`);
    star.style.setProperty("--dy", `${Math.sin(rad) * travel}px`);
    star.style.setProperty("--len", `${lenBase + Math.random() * lenRange}px`);
    star.style.setProperty("--dur", `${5.5 + Math.random() * 8}s`);
    star.style.setProperty("--delay", `${Math.random() * 14}s`);
    shootingWrap.appendChild(star);
  }

  if (pointerParallax) {
    const root = document.documentElement;
    window.addEventListener("pointermove", (e) => {
      const x = (e.clientX / window.innerWidth - 0.5) * 22;
      const y = (e.clientY / window.innerHeight - 0.5) * 22;
      root.style.setProperty("--mx", `${x}px`);
      root.style.setProperty("--my", `${y}px`);
    });
  }
}

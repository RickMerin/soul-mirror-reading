/**
 * @returns {string} Normalized funnel prefix from `data-funnel-base`, e.g. "" or "staging/"
 */
export function getFunnelBase() {
  const raw = document.documentElement.getAttribute("data-funnel-base") ?? "";
  const trimmed = raw.trim().replace(/^\/+|\/+$/g, "");
  return trimmed === "" ? "" : `${trimmed}/`;
}

/**
 * @param {string} relativePath
 * @returns {string}
 */
export function funnelUrl(relativePath) {
  const funnelBase = getFunnelBase();
  const relative = relativePath.replace(/^\/+/, "");
  const path = funnelBase === "" ? relative : `${funnelBase}${relative}`;
  return new URL(path, `${window.location.origin}${getSiteBase()}`).href;
}

/**
 * Directory prefix for the deployed site (includes subdirectory installs).
 * @returns {string} e.g. "/" or "/soul-mirror-reading/"
 */
export function getSiteBase() {
  let pathname = window.location.pathname;
  if (!pathname.endsWith("/")) {
    pathname = pathname.replace(/\/[^/]*$/, "/");
  }

  const funnelBase = getFunnelBase();
  if (funnelBase !== "") {
    const funnelSegment = funnelBase.replace(/\/$/, "");
    const suffix = `${funnelSegment}/`;
    if (pathname.endsWith(suffix)) {
      pathname = pathname.slice(0, -suffix.length);
    }
  }

  if (!pathname.endsWith("/")) {
    pathname += "/";
  }

  return pathname;
}

/**
 * @param {string} relativePath Path from site root, e.g. api/reading
 * @returns {string}
 */
export function siteUrl(relativePath) {
  const path = relativePath.replace(/^\/+/, "");
  return new URL(path, `${window.location.origin}${getSiteBase()}`).href;
}

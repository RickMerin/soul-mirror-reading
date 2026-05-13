const KIT_MONTHS = [
  "Jan",
  "Feb",
  "Mar",
  "Apr",
  "May",
  "Jun",
  "Jul",
  "Aug",
  "Sep",
  "Oct",
  "Nov",
  "Dec",
];

/**
 * @param {number} year
 * @param {number} month
 * @param {number} day
 */
function isValidGregorianDate(year, month, day) {
  const date = new Date(year, month - 1, day);
  return (
    date.getFullYear() === year &&
    date.getMonth() === month - 1 &&
    date.getDate() === day
  );
}

/**
 * @param {string} value
 * @returns {string | null}
 */
export function apiDobToKitEmbed(value) {
  const trimmed = value.trim();
  const match = /^(\d{2})\/(\d{2})\/(\d{4})$/.exec(trimmed);
  if (!match) return null;
  const month = Number(match[1]);
  const day = Number(match[2]);
  const year = Number(match[3]);
  if (!isValidGregorianDate(year, month, day)) return null;
  return `${day}-${KIT_MONTHS[month - 1]}-${year}`;
}

/**
 * @param {string} value
 * @returns {string | null}
 */
export function apiDobToDateInputValue(value) {
  const trimmed = value.trim();
  const apiMatch = /^(\d{2})\/(\d{2})\/(\d{4})$/.exec(trimmed);
  if (apiMatch) {
    const month = Number(apiMatch[1]);
    const day = Number(apiMatch[2]);
    const year = Number(apiMatch[3]);
    if (!isValidGregorianDate(year, month, day)) return null;
    return `${apiMatch[3]}-${apiMatch[1]}-${apiMatch[2]}`;
  }

  const dateMatch = /^(\d{4})-(\d{2})-(\d{2})$/.exec(trimmed);
  if (!dateMatch) return null;
  const year = Number(dateMatch[1]);
  const month = Number(dateMatch[2]);
  const day = Number(dateMatch[3]);
  if (!isValidGregorianDate(year, month, day)) return null;
  return trimmed;
}

/**
 * @param {string} value
 * @returns {string | null}
 */
export function readingDobToDateInputValue(value) {
  const direct = apiDobToDateInputValue(value);
  if (direct) return direct;

  const apiDob = kitEmbedDobToApi(value);
  if (!apiDob) return null;
  return apiDobToDateInputValue(apiDob);
}

/**
 * @param {string} value
 * @returns {string | null}
 */
export function kitEmbedDobToApi(value) {
  const trimmed = value.trim();
  const dateInputMatch = /^(\d{4})-(\d{2})-(\d{2})$/.exec(trimmed);
  if (dateInputMatch) {
    const year = Number(dateInputMatch[1]);
    const month = Number(dateInputMatch[2]);
    const day = Number(dateInputMatch[3]);
    if (!isValidGregorianDate(year, month, day)) return null;
    return `${dateInputMatch[2]}/${dateInputMatch[3]}/${dateInputMatch[1]}`;
  }

  const apiMatch = /^(\d{2})\/(\d{2})\/(\d{4})$/.exec(trimmed);
  if (apiMatch) {
    const month = Number(apiMatch[1]);
    const day = Number(apiMatch[2]);
    const year = Number(apiMatch[3]);
    if (!isValidGregorianDate(year, month, day)) return null;
    return trimmed;
  }

  const kitMatch = /^(\d{1,2})-([A-Za-z]{3})-(\d{4})$/.exec(trimmed);
  if (!kitMatch) return null;

  const day = Number(kitMatch[1]);
  const monthIndex = KIT_MONTHS.findIndex(
    (month) => month.toLowerCase() === kitMatch[2].toLowerCase(),
  );
  const year = Number(kitMatch[3]);
  if (monthIndex < 0 || !isValidGregorianDate(year, monthIndex + 1, day)) return null;

  const month = String(monthIndex + 1).padStart(2, "0");
  const dayPadded = String(day).padStart(2, "0");
  return `${month}/${dayPadded}/${year}`;
}

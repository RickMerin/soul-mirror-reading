/**
 * One-shot: generate WebP variants for sales-page.php card images.
 * Run: node scripts/convert-sales-cards-webp.mjs
 */
import fs from "node:fs/promises";
import path from "node:path";
import sharp from "sharp";

const root = path.resolve(import.meta.dirname, "../public/cards");
const files = [
  "mirror-love.png",
  "mirror-life.png",
  "mirror-wealth.png",
  "mirror-block.png",
  "product-mockup.png",
  "bonus-companion.png",
  "bonus-tracker.png",
  "bonus-rootcause.png",
  "bonus-meditation.png",
  "guarantee-badge.png",
];

for (const name of files) {
  const input = path.join(root, name);
  const out = path.join(root, name.replace(/\.png$/i, ".webp"));
  const buf = await fs.readFile(input);
  const meta = await sharp(buf).metadata();
  await sharp(buf).webp({ quality: 82, effort: 6 }).toFile(out);
  const outStat = await fs.stat(out);
  console.log(
    `${name} → ${path.basename(out)}  ${meta.width}x${meta.height}  ${(outStat.size / 1024).toFixed(1)} KB`,
  );
}

#!/usr/bin/env node
import fs from "node:fs";
import path from "node:path";
import puppeteer from "puppeteer";

function parseArgs(argv) {
  /** @type {Record<string, string>} */
  const out = {};
  for (let i = 0; i < argv.length; i += 1) {
    const token = argv[i];
    if (!token.startsWith("--")) continue;
    const key = token.slice(2);
    const value = argv[i + 1];
    if (value && !value.startsWith("--")) {
      out[key] = value;
      i += 1;
    }
  }
  return out;
}

const args = parseArgs(process.argv.slice(2));
const input = args.input;
const output = args.output;

if (!input || !output) {
  console.error("Usage: node render-pdf.mjs --input <html> --output <pdf>");
  process.exit(1);
}

const inputPath = path.resolve(input);
const outputPath = path.resolve(output);

if (!fs.existsSync(inputPath)) {
  console.error(`Input not found: ${inputPath}`);
  process.exit(1);
}

const browser = await puppeteer.launch({
  headless: true,
  args: ["--no-sandbox", "--disable-setuid-sandbox"],
});

try {
  const page = await browser.newPage();
  await page.goto(`file://${inputPath.replace(/\\/g, "/")}`, {
    waitUntil: "networkidle0",
    timeout: 120_000,
  });
  await page.emulateMediaType("print");
  await page.pdf({
    path: outputPath,
    format: "A4",
    printBackground: true,
    preferCSSPageSize: true,
    margin: { top: "0", right: "0", bottom: "0", left: "0" },
  });
} finally {
  await browser.close();
}

console.log(`Wrote ${outputPath}`);

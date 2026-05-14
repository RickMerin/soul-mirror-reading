import * as esbuild from "esbuild";
import * as path from "node:path";
import { fileURLToPath } from "node:url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.join(__dirname, "..");

await esbuild.build({
  entryPoints: {
    home: path.join(root, "frontend", "js", "home.js"),
    sales: path.join(root, "frontend", "js", "sales.js"),
    "sales-v2": path.join(root, "frontend", "js", "sales-v2.js"),
    thankyou: path.join(root, "frontend", "js", "thankyou.js"),
    "unlock-reading": path.join(root, "frontend", "js", "unlock-reading.js"),
  },
  bundle: true,
  minify: true,
  outdir: path.join(root, "public", "assets"),
  entryNames: "[name].min",
  format: "iife",
  platform: "browser",
});

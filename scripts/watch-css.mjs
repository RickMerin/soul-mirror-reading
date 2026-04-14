import { spawn } from "node:child_process";
import * as path from "node:path";
import { fileURLToPath } from "node:url";
import chokidar from "chokidar";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.join(__dirname, "..");
const buildCss = path.join(__dirname, "build-css.mjs");
const buildJs = path.join(__dirname, "build-js.mjs");

function runBuild() {
  const t0 = Date.now();
  const css = spawn(process.execPath, [buildCss], {
    stdio: "inherit",
    cwd: root,
  });
  css.on("close", (code) => {
    if (code !== 0) return;
    const js = spawn(process.execPath, [buildJs], {
      stdio: "inherit",
      cwd: root,
    });
    js.on("close", (jsCode) => {
      if (jsCode === 0) {
        console.log(`[assets] built in ${Date.now() - t0}ms`);
      }
    });
  });
}

const watchGlobs = [
  path.join(root, "frontend", "styles", "**", "*.css"),
  path.join(root, "frontend", "email", "**", "*.html"),
  path.join(root, "frontend", "js", "**", "*.js"),
];

runBuild();
chokidar.watch(watchGlobs, { ignoreInitial: true }).on("all", () => {
  runBuild();
});
console.log(`[assets] watching ${watchGlobs.join(", ")}`);

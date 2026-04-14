import * as fs from "node:fs";
import * as path from "node:path";
import { Buffer } from "node:buffer";
import { fileURLToPath } from "node:url";
import { bundle } from "lightningcss";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.join(__dirname, "..");

function writeBundle(relEntry, relOut) {
  const entry = path.join(root, relEntry);
  const outFile = path.join(root, relOut);
  fs.mkdirSync(path.dirname(outFile), { recursive: true });
  const { code } = bundle({
    filename: entry,
    minify: true,
  });
  fs.writeFileSync(outFile, code);
  return code;
}

writeBundle(path.join("frontend", "styles", "main.css"), path.join("public", "assets", "bundle.min.css"));
writeBundle(
  path.join("frontend", "styles", "sales", "main.css"),
  path.join("public", "assets", "sales-bundle.min.css"),
);
writeBundle(
  path.join("frontend", "styles", "thankyou", "main.css"),
  path.join("public", "assets", "thankyou-bundle.min.css"),
);

const emailCss = writeBundle(
  path.join("frontend", "styles", "email", "main.css"),
  path.join("public", "assets", "email.min.css"),
);

const emailSrc = path.join(root, "frontend", "email", "email-template.src.html");
const emailOut = path.join(root, "public", "email-template.html");
const emailTpl = fs.readFileSync(emailSrc, "utf8");
if (!emailTpl.includes("@@SOUL_MIRROR_EMAIL_CSS@@")) {
  throw new Error("email-template.src.html missing @@SOUL_MIRROR_EMAIL_CSS@@ placeholder");
}
const emailCssText = Buffer.from(emailCss).toString("utf8");
fs.writeFileSync(emailOut, emailTpl.replace("@@SOUL_MIRROR_EMAIL_CSS@@", emailCssText));

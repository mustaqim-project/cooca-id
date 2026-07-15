const fs = require("fs");
const path = require("path");

const DIRS = [
    "pages/about",
    "pages/pricing",
    "pages/contact",
    "pages/solutions",
    "pages/faq",
    "pages/docs",
    "pages/affiliate",
    "pages/legal",
    "pages/products",
    "pages/blog",
    "auth/customer",
    "auth/affiliator",
    "auth/admin",
];

let badFiles = [];
let totalFiles = 0;

DIRS.forEach(function (dir) {
    const fullDir = path.join("resources/views", dir);
    if (!fs.existsSync(fullDir)) return;
    const files = fs.readdirSync(fullDir).filter(function (f) {
        return f.endsWith(".blade.php");
    });
    files.forEach(function (f) {
        totalFiles++;
        const fp = path.join(fullDir, f);
        const content = fs.readFileSync(fp, "utf8");
        const issues = [];

        if (
            content.indexOf("@extends") === -1 &&
            dir.indexOf("partials") === -1 &&
            dir.indexOf("layouts") === -1
        ) {
            issues.push("MISSING @extends");
        }
        if (
            content.indexOf("@section") === -1 &&
            content.indexOf("layout") === -1 &&
            dir.indexOf("partials") === -1 &&
            dir.indexOf("layouts") === -1
        ) {
            issues.push("MISSING @section");
        }
        if (content.indexOf("btn-cooca") > -1) {
            issues.push("HAS btn-cooca (old class)");
        }
        if (
            content.indexOf("logo-icon") > -1 &&
            content.indexOf("logo-icon-large") === -1
        ) {
            issues.push("HAS logo-icon (old class)");
        }
        if (content.indexOf("navbar-brand-cooca") > -1) {
            issues.push("HAS navbar-brand-cooca (old class)");
        }
        if (content.indexOf("offcanvas-cooca") > -1) {
            issues.push("HAS offcanvas-cooca (old class)");
        }

        if (issues.length > 0) {
            console.log("ISSUES in " + fp + ": " + issues.join(", "));
            badFiles.push({ file: fp, issues: issues });
        }
    });
});

console.log("");
console.log("Total files checked: " + totalFiles);
console.log("Files with issues: " + badFiles.length);

if (badFiles.length > 0) {
    console.log("\nFIXING FILES...");
    badFiles.forEach(function (bf) {
        let c = fs.readFileSync(bf.file, "utf8");
        c = c.replace(/btn-cooca/g, "btn");
        c = c.replace(/class="btn-cooca"/g, 'class="btn btn-primary"');
        c = c.replace(/class='btn-cooca'/g, "class='btn btn-primary'");
        c = c.replace(/logo-icon(?![-\w])/g, "brand-icon");
        c = c.replace(/navbar-brand-cooca/g, "navbar-brand");
        c = c.replace(/offcanvas-cooca/g, "offcanvas-c");
        c = c.replace(/nav-link-cooca/g, "nav-link");
        c = c.replace(/dropdown-cooca/g, "");
        c = c.replace(/btn-cooca-primary/g, "btn-primary");
        c = c.replace(/btn-cooca-success/g, "btn-success");
        c = c.replace(/btn-cooca-outline/g, "btn-outline");
        c = c.replace(/btn-cooca-sm/g, "btn-sm");
        fs.writeFileSync(bf.file, c, "utf8");
        console.log("  Fixed: " + bf.file);
    });
    console.log("All fixes applied.");
}

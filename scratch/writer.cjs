const fs = require("fs");
const base = "resources/views";

// Helper: update a file's CSS class names to match new design system
function updateFile(filepath) {
    let content = fs.readFileSync(filepath, "utf8");
    // Keep btn-cooca that has specific styles, but update the common ones
    content = content.replace(/navbar-cooca/g, "navbar");
    content = content.replace(/nav-link-cooca/g, "nav-link");
    content = content.replace(/offcanvas-cooca/g, "offcanvas-c");
    content = content.replace(/btn-cooca-primary/g, "btn-primary");
    content = content.replace(/btn-cooca-success/g, "btn-success");
    content = content.replace(/btn-cooca-outline/g, "btn-outline");
    content = content.replace(/btn-cooca-sm/g, "btn-sm");
    // Fix standalone btn-cooca usage to use btn btn-primary
    content = content.replace(/class="btn-cooca"/g, 'class="btn btn-primary"');
    content = content.replace(/class='btn-cooca'/g, "class='btn btn-primary'");
    fs.writeFileSync(filepath, content, "utf8");
    console.log("Updated: " + filepath);
}

// Update layout
updateFile(base + "/layouts/guest.blade.php");

// Update header
updateFile(base + "/partials/header.blade.php");

// Update footer
updateFile(base + "/partials/footer.blade.php");

console.log("Core layout files updated.");

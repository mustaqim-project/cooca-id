import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { execSync } from 'child_process';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const viewsDir = path.join(__dirname, 'resources', 'views');

function walk(dir) {
    let results = [];
    const list = fs.readdirSync(dir);
    list.forEach(function(file) {
        file = path.join(dir, file);
        const stat = fs.statSync(file);
        if (stat && stat.isDirectory()) { 
            results = results.concat(walk(file));
        } else { 
            if(file.endsWith('.blade.php')) {
                results.push(file);
            }
        }
    });
    return results;
}

// 1. Get all registered route names
let routeOutput = '';
try {
    routeOutput = execSync('php artisan route:list --json').toString();
} catch (e) {
    console.error("Error running route:list");
    process.exit(1);
}

let routes = JSON.parse(routeOutput);
let routeNames = new Set(routes.map(r => r.name).filter(n => n));

// 2. Scan all blade files for route() calls
const files = walk(viewsDir);
let errors = [];
let routeReplacements = {
    // some common misspellings from the scaffold
    'admin.settings.index': 'admin.cms.pages.index',
    'admin.blog.index': 'admin.blogs.index',
    'admin.blog.create': 'admin.blogs.create',
    'admin.blog.store': 'admin.blogs.store',
    'admin.blog.edit': 'admin.blogs.edit',
    'admin.blog.update': 'admin.blogs.update',
    'admin.blog.destroy': 'admin.blogs.destroy',
    'admin.blog.show': 'admin.blogs.show',
    'customer.profile': 'customer.profile.edit'
};

files.forEach(file => {
    let content = fs.readFileSync(file, 'utf-8');
    let original = content;
    
    // Find route('name') or route('name', ...)
    const routeRegex = /route\(['"]([^'"]+)['"]/g;
    let match;
    while ((match = routeRegex.exec(content)) !== null) {
        let rName = match[1];
        if (!routeNames.has(rName)) {
            // Check if it's a known typo
            if (routeReplacements[rName]) {
                content = content.replaceAll(`route('${rName}'`, `route('${routeReplacements[rName]}'`);
                content = content.replaceAll(`route("${rName}"`, `route("${routeReplacements[rName]}"`);
                console.log(`Auto-fixed route ${rName} to ${routeReplacements[rName]} in ${file.replace(__dirname, '')}`);
            } else {
                errors.push(`Missing route: ${rName} in ${file.replace(__dirname, '')}`);
            }
        }
    }
    
    if (content !== original) {
        fs.writeFileSync(file, content, 'utf-8');
    }
});

console.log("=== ROUTE ERRORS ===");
if (errors.length > 0) {
    errors.forEach(e => console.log(e));
} else {
    console.log("No missing routes found!");
}

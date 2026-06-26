import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { execSync } from 'child_process';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const viewsDir = path.join(__dirname, 'resources', 'views', 'admin');

function walk(dir) {
    let results = [];
    const list = fs.readdirSync(dir);
    list.forEach(function(file) {
        file = path.join(dir, file);
        const stat = fs.statSync(file);
        if (stat && stat.isDirectory()) { 
            results = results.concat(walk(file));
        } else { 
            if(file.endsWith('index.blade.php')) {
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

const files = walk(viewsDir);
let changedCount = 0;

files.forEach(file => {
    let content = fs.readFileSync(file, 'utf-8');
    
    // Skip if it already has a create button
    if (content.includes('.create') || content.includes('Create ') || content.includes('Add New')) {
        return;
    }
    
    // Guess the resource name from the file path
    // e.g. resources/views/admin/products/index.blade.php -> 'products'
    // resources/views/admin/emailtemplates/index.blade.php -> 'email-templates' (we'll check common variants)
    const folderName = path.basename(path.dirname(file));
    
    // Possible create route names
    const possibleRoutes = [
        `admin.${folderName}.create`,
        `admin.${folderName.replace('templates', '-templates').replace('campaigns', '-campaigns').replace('categories', '-categories')}.create`,
        `admin.cms.${folderName}.create` // for cms/pages
    ];
    
    let createRoute = null;
    for (let r of possibleRoutes) {
        if (routeNames.has(r)) {
            createRoute = r;
            break;
        }
    }
    
    if (createRoute) {
        console.log(`Injecting ${createRoute} into ${folderName}/index.blade.php`);
        const btnHtml = `\n                <a href="{{ route('${createRoute}') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-surface-900">\n                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>\n                    Add New\n                </a>\n            `;
        
        // Find <div class="flex items-center space-x-3 w-full sm:w-auto"> and inject button inside
        content = content.replace(/<div class="flex items-center space-x-3 w-full sm:w-auto">\s*<\/div>/, `<div class="flex items-center space-x-3 w-full sm:w-auto">${btnHtml}</div>`);
        fs.writeFileSync(file, content, 'utf-8');
        changedCount++;
    }
});

console.log(`Injected create button in ${changedCount} views.`);

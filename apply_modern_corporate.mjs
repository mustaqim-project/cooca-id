import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const viewsDir = path.join(__dirname, 'resources', 'views');

// Helper to walk directories
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

const allViews = walk(viewsDir);

allViews.forEach(file => {
    let content = fs.readFileSync(file, 'utf-8');
    let original = content;

    // --- Layout specific replacements ---
    if (file.includes('layouts\\admin.blade.php') || file.includes('layouts/admin.blade.php') ||
        file.includes('layouts\\customer.blade.php') || file.includes('layouts/customer.blade.php') ||
        file.includes('layouts\\affiliator.blade.php') || file.includes('layouts/affiliator.blade.php')) {
        
        // Sidebar styling
        content = content.replace(/class="([^"]*)bg-white dark:bg-surface-800 border-r border-surface-200 dark:border-surface-700([^"]*)"/g, 'class="$1corporate-sidebar$2"');
        
        // Header styling
        content = content.replace(/class="([^"]*)bg-white dark:bg-surface-800 shadow-sm border-b border-surface-200 dark:border-surface-700([^"]*)"/g, 'class="$1corporate-header$2"');
        
        // Nav items styling
        content = content.replace(/class="([^"]*)flex items-center px-4 py-[0-9.]+ text-sm font-medium rounded-lg text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700 hover:text-surface-900 dark:hover:text-white([^"]*)"/g, 'class="$1corporate-nav-item$2"');
        content = content.replace(/class="([^"]*)flex items-center px-4 py-[0-9.]+ text-sm font-medium rounded-lg bg-primary-50 dark:bg-primary-900\/20 text-primary-600 dark:text-primary-400([^"]*)"/g, 'class="$1corporate-nav-item-active$2"');
        
        // 3D Icons for Navigation
        content = content.replace(/<i data-lucide="([^"]+)" class="([^"]*)"><\/i>/g, (match, icon, classes) => {
             if(!classes.includes('icon-3d')) {
                 return `<i data-lucide="${icon}" class="${classes} icon-3d"></i>`;
             }
             return match;
        });
    }

    // --- Global View Replacements (Cards) ---
    // Standard Card
    const cardRegex1 = /class="([^"]*)bg-white dark:bg-surface-800 shadow-sm rounded-lg border border-surface-200 dark:border-surface-700([^"]*)"/g;
    content = content.replace(cardRegex1, 'class="$1corporate-card$2"');
    
    // Glass Card (from previous run if any)
    const cardRegex2 = /class="([^"]*)glass-card([^"]*)"/g;
    content = content.replace(cardRegex2, 'class="$1corporate-card$2"');

    // Add 3D icon effect to any icon inside a dashboard stat card
    content = content.replace(/<i data-lucide="([^"]+)" class="([^"]*)"><\/i>/g, (match, icon, classes) => {
        if (classes.includes('w-6') || classes.includes('w-8') || classes.includes('w-12') || classes.includes('w-10')) {
            if (!classes.includes('icon-3d')) {
                return `<i data-lucide="${icon}" class="${classes} icon-3d"></i>`;
            }
        }
        return match;
    });

    if (content !== original) {
        fs.writeFileSync(file, content, 'utf-8');
        console.log(`Updated: ${file.replace(__dirname, '')}`);
    }
});

console.log('Finished applying Modern Corporate theme.');

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

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

const files = walk(viewsDir);

files.forEach(file => {
    let content = fs.readFileSync(file, 'utf-8');
    
    let countLeft = (content.match(/<!-- Left Column: Main Form -->/g) || []).length;
    
    if (countLeft > 1) {
        console.log(`Fixing corrupted file: ${file.replace(__dirname, '')} (Duplications: ${countLeft})`);
        
        // We will remove the exact duplicated left column chunk (countLeft - 1) times.
        // It looks exactly like:
        //                         <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">\n            <!-- Left Column: Main Form -->\n            <div class="lg:col-span-2 space-y-6">\n                <div class="corporate-card">\n                    <div class="card-header">\n                        <h3 class="card-title">Form Details</h3>\n                    </div>\n                    <div class="p-6 space-y-5">\n
        // Actually, spacing might vary slightly. Let's use a regex to replace exactly ONE match of the wrapper.
        
        let leftRegex = /(?:<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">\s*)?<!-- Left Column: Main Form -->\s*<div class="lg:col-span-2 space-y-6">\s*<div class="corporate-card">\s*<div class="card-header">\s*<h3 class="card-title">Form Details<\/h3>\s*<\/div>\s*<div class="p-6 space-y-5">\s*/;
        
        for (let i = 0; i < countLeft - 1; i++) {
            content = content.replace(leftRegex, '');
        }
        
        // Also fix right columns
        // Right columns might not have <!-- Right Column: Actions --> if the script replaced them fully.
        // Let's count how many times "Review your changes before submitting." appears.
        let countRight = (content.match(/Review your changes before submitting\./g) || []).length;
        
        let rightRegex1 = /\s*<\/div>\s*<\/div>\s*<\/div>\s*<!-- Right Column: Actions -->\s*<div class="space-y-6">\s*<div class="corporate-card">\s*<div class="p-6">\s*<h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Actions<\/h3>\s*<p class="text-sm text-surface-500 dark:text-surface-400 mb-6">Review your changes before submitting\.<\/p>\s*<div class="flex flex-col space-y-3">\s*<a href="javascript:history\.back\(\)" class="btn btn-secondary w-full">\s*Cancel\s*<\/a>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/;
        
        let rightRegex2 = /\s*<\/div>\s*<\/div>\s*<\/div>\s*<!-- Right Column: Actions -->\s*<div class="space-y-6">\s*<div class="corporate-card">\s*<div class="p-6">\s*<h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Actions<\/h3>\s*<p class="text-sm text-surface-500 dark:text-surface-400 mb-6">Review your changes before submitting\.<\/p>\s*<div class="flex flex-col space-y-3">\s*<a href="javascript:history\.back\(\)" class="w-full inline-flex justify-center items-center px-4 py-2\.5 border border-surface-300 dark:border-surface-600 rounded-lg shadow-sm text-sm font-medium text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">\s*Cancel\s*<\/a>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/;
        
        let rightRegex3 = /\s*<\/div>\s*<\/div>\s*<\/div>\s*<!-- Right Column: Actions -->\s*<div class="space-y-6">\s*<div class="corporate-card">\s*<div class="p-6">\s*<h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Actions<\/h3>\s*<p class="text-sm text-surface-500 dark:text-surface-400 mb-6">Review your changes before submitting\.<\/p>\s*<div class="flex flex-col space-y-3">\s*<button type="submit"[^>]*>[\s\S]*?<\/button>\s*<a href="javascript:history\.back\(\)"[^>]*>\s*Cancel\s*<\/a>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/;

        for (let i = 0; i < countRight - 1; i++) {
            if (rightRegex3.test(content)) {
                content = content.replace(rightRegex3, '');
            } else if (rightRegex2.test(content)) {
                content = content.replace(rightRegex2, '');
            } else if (rightRegex1.test(content)) {
                content = content.replace(rightRegex1, '');
            } else {
                // Try a generic one
                let rightRegexGeneric = /\s*<\/div>\s*<\/div>\s*<\/div>\s*<!-- Right Column: Actions -->\s*<div class="space-y-6">\s*<div class="corporate-card">\s*<div class="p-6">\s*<h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Actions<\/h3>[\s\S]*?<\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/;
                content = content.replace(rightRegexGeneric, '');
            }
        }
        
        // Let's also check if there is an unbalanced `</div>` problem
        // Actually, there shouldn't be because we removed exactly matching opening/closing sets!
        // Wait, rightRegex removes 5 closing divs and the whole right col. leftRegex removes 3 opening divs. So it balances perfectly!
        
        fs.writeFileSync(file, content, 'utf-8');
    }
});

// Also fix admin/customers/show.blade.php manually as it had a specific error with `card-body`
let customerShowPath = path.join(viewsDir, 'admin', 'customers', 'show.blade.php');
if (fs.existsSync(customerShowPath)) {
    let content = fs.readFileSync(customerShowPath, 'utf-8');
    if (content.includes('</a>\n</div>\n\n<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">')) {
        content = content.replace(
            '</a>\n</div>\n\n<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">',
            '</a>\n        </div>\n    </div>\n\n<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">'
        );
        // And we need to remove the two extra closing divs at the very end
        content = content.replace(
            '    </div>\n        </div>\n    </div>\n</div>\n@endsection',
            '    </div>\n</div>\n@endsection'
        );
        fs.writeFileSync(customerShowPath, content, 'utf-8');
        console.log("Fixed: admin/customers/show.blade.php");
    }
}

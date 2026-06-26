import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const layouts = [
    path.join(__dirname, 'resources', 'views', 'layouts', 'admin.blade.php'),
    path.join(__dirname, 'resources', 'views', 'layouts', 'customer.blade.php'),
    path.join(__dirname, 'resources', 'views', 'layouts', 'affiliator.blade.php')
];

layouts.forEach(file => {
    if (fs.existsSync(file)) {
        let content = fs.readFileSync(file, 'utf-8');
        
        // Fix header
        content = content.replace(
            /class="h-16 bg-white\/80 dark:bg-surface-800\/80 backdrop-blur-md border-b border-surface-200 dark:border-surface-700/g,
            'class="h-16 corporate-header'
        );
        
        // Fix nav items (this captures the multi-line class definition)
        content = content.replace(
            /class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors\s*\{\{ request\(\)->routeIs\(\$item\['route_name'\]\) \? 'bg-primary-600 text-white shadow-sm' : 'text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700 hover:text-surface-900 dark:hover:text-white' \}\}"/g,
            'class="{{ request()->routeIs($item[\'route_name\']) ? \'corporate-nav-item-active\' : \'corporate-nav-item\' }}"'
        );
        
        // Another variation of nav items for customer/affiliator if slightly different
        content = content.replace(
            /class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors\s*\{\{ request\(\)->routeIs\(\$item\['route_name'\]\) \? 'bg-primary-600 text-white shadow-sm' : 'text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700 hover:text-surface-900 dark:hover:text-white' \}\}"/g,
            'class="{{ request()->routeIs($item[\'route_name\']) ? \'corporate-nav-item-active\' : \'corporate-nav-item\' }}"'
        );

        fs.writeFileSync(file, content, 'utf-8');
        console.log(`Fixed layouts in ${path.basename(file)}`);
    }
});

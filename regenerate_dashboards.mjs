import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const dashboardFiles = [
    path.join(__dirname, 'resources', 'views', 'admin', 'dashboard', 'index.blade.php'),
    path.join(__dirname, 'resources', 'views', 'customer', 'dashboard', 'index.blade.php'),
    path.join(__dirname, 'resources', 'views', 'affiliator', 'dashboard', 'index.blade.php')
];

dashboardFiles.forEach(file => {
    if (!fs.existsSync(file)) return;
    
    let content = fs.readFileSync(file, 'utf-8');
    
    // Convert generic bg-white shadow rounded-lg to corporate-card
    content = content.replace(/bg-white\s+(?:dark:bg-[a-zA-Z0-9-]+\s+)?overflow-hidden\s+shadow-sm\s+(?:sm:)?rounded-lg/g, 'corporate-card');
    content = content.replace(/bg-white\s+(?:dark:bg-[a-zA-Z0-9-]+\s+)?shadow\s+(?:sm:)?rounded-lg/g, 'corporate-card');
    
    // Generic cards
    content = content.replace(/class="bg-white dark:bg-[^"]+ shadow rounded-lg p-6"/g, 'class="corporate-card p-6"');
    content = content.replace(/class="bg-white shadow rounded-lg p-6"/g, 'class="corporate-card p-6"');

    // Stats Grid
    content = content.replace(/grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6/g, 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6');
    content = content.replace(/grid-cols-1 md:grid-cols-3 gap-6/g, 'grid grid-cols-1 md:grid-cols-3 gap-6');
    
    fs.writeFileSync(file, content, 'utf-8');
    console.log(`Cleaned dashboard: ${file.replace(__dirname, '')}`);
});

console.log('Finished dashboard view cleaning.');

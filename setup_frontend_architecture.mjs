import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const cssDir = path.join(__dirname, 'resources', 'css');
const jsDir = path.join(__dirname, 'resources', 'js');
const componentsCssDir = path.join(cssDir, 'components');
const uiJsDir = path.join(jsDir, 'ui');

// Create directories if they don't exist
[componentsCssDir, uiJsDir].forEach(dir => {
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }
});

// --- CSS Architecture ---

const buttonsCss = `
@layer components {
    .btn {
        @apply inline-flex justify-center items-center px-4 py-2.5 border rounded-lg shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed;
    }
    
    .btn-sm {
        @apply px-3 py-1.5 text-xs;
    }
    
    .btn-lg {
        @apply px-6 py-3 text-base;
    }
    
    .btn-primary {
        @apply border-transparent text-white bg-primary-600 hover:bg-primary-700 focus:ring-primary-500 hover:shadow-md hover:-translate-y-0.5;
    }
    
    .btn-secondary {
        @apply border-surface-300 dark:border-surface-600 text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:hover:bg-surface-700 focus:ring-primary-500;
    }
    
    .btn-danger {
        @apply border-transparent text-white bg-red-600 hover:bg-red-700 focus:ring-red-500 hover:shadow-md hover:-translate-y-0.5;
    }
    
    .btn-ghost {
        @apply border-transparent shadow-none bg-transparent text-surface-600 hover:text-primary-600 hover:bg-primary-50 dark:text-surface-400 dark:hover:text-primary-400 dark:hover:bg-primary-900/20;
    }
    
    .btn-ghost-danger {
        @apply border-transparent shadow-none bg-transparent text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20;
    }
}
`;

const formsCss = `
@layer components {
    .form-group {
        @apply mb-5;
    }
    
    .form-label {
        @apply block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5;
    }
    
    .form-input {
        @apply block w-full py-2.5 px-3.5 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 text-surface-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors;
    }
    
    .form-input-error {
        @apply border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500;
    }
    
    .form-error-msg {
        @apply mt-1.5 text-sm text-red-600 dark:text-red-400;
    }
    
    .form-select {
        @apply block w-full py-2.5 px-3.5 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 text-surface-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors;
    }
}
`;

const cardsCss = `
@layer components {
    .corporate-card {
        @apply bg-white dark:bg-surface-800 border border-surface-200 dark:border-surface-700 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] rounded-xl overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300;
    }
    
    .card-header {
        @apply px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50 flex justify-between items-center;
    }
    
    .card-title {
        @apply text-lg font-medium text-surface-900 dark:text-white;
    }
    
    .card-body {
        @apply p-6;
    }
    
    .card-footer {
        @apply px-6 py-4 bg-surface-50 dark:bg-surface-900 border-t border-surface-200 dark:border-surface-700 flex justify-end gap-3;
    }
}
`;

const tablesCss = `
@layer components {
    .table-container {
        @apply overflow-x-auto w-full;
    }
    
    .corporate-table {
        @apply min-w-full divide-y divide-surface-200 dark:divide-surface-700;
    }
    
    .table-thead {
        @apply bg-surface-50 dark:bg-surface-900/50;
    }
    
    .table-th {
        @apply px-6 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider whitespace-nowrap;
    }
    
    .table-tbody {
        @apply bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700;
    }
    
    .table-tr {
        @apply hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors;
    }
    
    .table-td {
        @apply px-6 py-4 whitespace-nowrap text-sm text-surface-900 dark:text-surface-300;
    }
}
`;

const layoutCss = `
@layer components {
    .corporate-header {
        @apply bg-white/90 dark:bg-surface-800/90 backdrop-blur-md border-b border-surface-200 dark:border-surface-700 shadow-sm sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16;
    }
    
    .corporate-sidebar {
        @apply bg-surface-50 dark:bg-surface-900 border-r border-surface-200 dark:border-surface-700 h-screen fixed inset-y-0 left-0 z-40 transition-transform duration-300 ease-in-out;
    }
    
    .corporate-nav-item {
        @apply flex items-center px-4 py-2.5 text-sm font-medium rounded-lg text-surface-700 dark:text-surface-300 hover:bg-white dark:hover:bg-surface-800 hover:text-primary-600 dark:hover:text-primary-400 hover:shadow-sm transition-all duration-200;
    }
    
    .corporate-nav-item-active {
        @apply bg-white dark:bg-surface-800 text-primary-600 dark:text-primary-400 shadow-sm border-l-4 border-primary-600;
    }
    
    .icon-3d {
        @apply relative inline-block transition-transform duration-300 hover:-translate-y-1 hover:scale-110 drop-shadow-md;
        filter: drop-shadow(0px 4px 6px rgba(37, 99, 235, 0.2)) drop-shadow(0px 1px 3px rgba(37, 99, 235, 0.1));
    }
}
`;

fs.writeFileSync(path.join(componentsCssDir, 'buttons.css'), buttonsCss);
fs.writeFileSync(path.join(componentsCssDir, 'forms.css'), formsCss);
fs.writeFileSync(path.join(componentsCssDir, 'cards.css'), cardsCss);
fs.writeFileSync(path.join(componentsCssDir, 'tables.css'), tablesCss);
fs.writeFileSync(path.join(componentsCssDir, 'layout.css'), layoutCss);

// --- UPDATE app.css ---
const appCssPath = path.join(cssDir, 'app.css');
let appCssContent = fs.readFileSync(appCssPath, 'utf-8');

// We need to inject the imports right after @source declarations
const importStatements = `
@import './components/buttons.css';
@import './components/forms.css';
@import './components/cards.css';
@import './components/tables.css';
@import './components/layout.css';
`;

if (!appCssContent.includes('@import \'./components/buttons.css\';')) {
    // Inject after the @source lines
    appCssContent = appCssContent.replace(
        /(@source '[^']+';\s*@source '[^']+';)/,
        '$1\\n' + importStatements
    );
    
    // Remove the old hardcoded corporate classes from utilities layer
    appCssContent = appCssContent.replace(/\.corporate-card[\s\S]*?\.icon-3d[\s\S]*?}/g, '');
    
    fs.writeFileSync(appCssPath, appCssContent);
}

// --- JS Architecture ---

const themeJs = `
export function initTheme() {
    // Check for saved theme preference or use system preference
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

export function toggleTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    if (isDark) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
}
`;

const alertsJs = `
export function initAlerts() {
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            alert.style.transition = 'all 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
}
`;

fs.writeFileSync(path.join(uiJsDir, 'theme.js'), themeJs);
fs.writeFileSync(path.join(uiJsDir, 'alerts.js'), alertsJs);

// Update app.js
const appJsPath = path.join(jsDir, 'app.js');
let appJsContent = fs.readFileSync(appJsPath, 'utf-8');

const appJsAdditions = `
import { initTheme, toggleTheme } from './ui/theme.js';
import { initAlerts } from './ui/alerts.js';

document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initAlerts();
    
    // Bind theme toggle button if exists
    const themeBtn = document.getElementById('theme-toggle');
    if (themeBtn) {
        themeBtn.addEventListener('click', toggleTheme);
    }
});
`;

if (!appJsContent.includes('./ui/theme.js')) {
    fs.appendFileSync(appJsPath, '\\n' + appJsAdditions);
}

// Update the regenerate script
const regenScriptPath = path.join(__dirname, 'regenerate_views_structurally.mjs');
let regenScriptContent = fs.readFileSync(regenScriptPath, 'utf-8');

// We modify the regenerate script to use the new CSS component classes
regenScriptContent = regenScriptContent.replace(
    /class="block w-full py-2 px-3 border border-surface-300 dark:border-surface-600 bg-white dark:bg-surface-800 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"/g,
    'class="form-input"'
);

regenScriptContent = regenScriptContent.replace(
    /class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1"/g,
    'class="form-label"'
);

regenScriptContent = regenScriptContent.replace(
    /class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors"/g,
    'class="btn btn-primary w-full"'
);

regenScriptContent = regenScriptContent.replace(
    /class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-surface-300 dark:border-surface-600 rounded-lg shadow-sm text-sm font-medium text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors"/g,
    'class="btn btn-secondary w-full"'
);

regenScriptContent = regenScriptContent.replace(
    /class="px-6 py-3 text-left text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider bg-surface-50 dark:bg-surface-900\/50"/g,
    'class="table-th"'
);

regenScriptContent = regenScriptContent.replace(
    /<table class="min-w-full divide-y divide-surface-200 dark:divide-surface-700">/g,
    '<table class="corporate-table">'
);

regenScriptContent = regenScriptContent.replace(
    /<thead class="bg-surface-50 dark:bg-surface-900\/50">/g,
    '<thead class="table-thead">'
);

regenScriptContent = regenScriptContent.replace(
    /<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">/g,
    '<tbody class="table-tbody">'
);

regenScriptContent = regenScriptContent.replace(
    /bg-primary-600 shadow-sm rounded-lg text-sm transition-colors/g,
    'btn btn-primary'
);

regenScriptContent = regenScriptContent.replace(
    /class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50\/50 dark:bg-surface-900\/50"/g,
    'class="card-header"'
);

regenScriptContent = regenScriptContent.replace(
    /class="text-lg font-medium text-surface-900 dark:text-white"/g,
    'class="card-title"'
);

fs.writeFileSync(regenScriptPath, regenScriptContent);

console.log("Successfully created CSS and JS components, injected imports, and updated regeneration script.");

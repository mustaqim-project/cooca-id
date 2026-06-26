import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const viewsDir = path.join(__dirname, 'resources', 'views');

const iconMap = {
    'bi-speedometer2': 'layout-dashboard',
    'bi-people': 'users',
    'bi-person-badge': 'contact',
    'bi-person-workspace': 'briefcase',
    'bi-box-seam': 'package',
    'bi-tags': 'tags',
    'bi-arrow-repeat': 'repeat',
    'bi-key': 'key',
    'bi-currency-dollar': 'dollar-sign',
    'bi-cash-stack': 'coins',
    'bi-ticket': 'ticket',
    'bi-server': 'server',
    'bi-file-text': 'file-text',
    'bi-newspaper': 'newspaper',
    'bi-question-circle': 'help-circle',
    'bi-chat-quote': 'message-square-quote',
    'bi-star': 'star',
    'bi-envelope': 'mail',
    'bi-envelope-paper': 'mail-open',
    'bi-gear': 'settings',
    'bi-list': 'menu',
    'bi-moon': 'moon',
    'bi-sun': 'sun',
    'bi-bell': 'bell',
    'bi-search': 'search',
    'bi-box-arrow-right': 'log-out',
    'bi-bar-chart': 'bar-chart',
    'bi-wallet2': 'wallet',
    'bi-link-45deg': 'link',
    'bi-shield-check': 'shield-check',
    'bi-person': 'user',
    'bi-cart': 'shopping-cart',
    'bi-journal-text': 'book-open',
    'bi-file-earmark-text': 'file',
    'bi-pencil': 'pencil',
    'bi-trash': 'trash-2',
    'bi-plus': 'plus',
    'bi-plus-circle': 'plus-circle',
    'bi-arrow-left': 'arrow-left',
    'bi-arrow-right': 'arrow-right',
    'bi-check': 'check',
    'bi-x': 'x',
    'bi-info-circle': 'info',
    'bi-exclamation-triangle': 'alert-triangle',
    'bi-download': 'download',
    'bi-upload': 'upload',
    'bi-eye': 'eye',
    'bi-eye-slash': 'eye-off',
    'bi-calendar': 'calendar',
    'bi-calendar3': 'calendar',
    'bi-clock': 'clock',
    'bi-check-circle': 'check-circle',
    'bi-x-circle': 'x-circle',
    'bi-box-arrow-up-right': 'external-link',
    'bi-chat-dots': 'message-square',
    'bi-star-fill': 'star',
};

function processDirectory(directory) {
    const files = fs.readdirSync(directory);
    
    for (const file of files) {
        const fullPath = path.join(directory, file);
        const stat = fs.statSync(fullPath);
        
        if (stat.isDirectory()) {
            if (file !== 'layouts' && file !== 'components') {
                processDirectory(fullPath);
            }
        } else if (file.endsWith('.blade.php')) {
            let content = fs.readFileSync(fullPath, 'utf-8');
            
            // Replace colors
            content = content.replace(/bg-gray-/g, 'bg-surface-');
            content = content.replace(/text-gray-/g, 'text-surface-');
            content = content.replace(/border-gray-/g, 'border-surface-');
            content = content.replace(/ring-gray-/g, 'ring-surface-');
            content = content.replace(/divide-gray-/g, 'divide-surface-');
            
            content = content.replace(/bg-indigo-/g, 'bg-primary-');
            content = content.replace(/text-indigo-/g, 'text-primary-');
            content = content.replace(/border-indigo-/g, 'border-primary-');
            content = content.replace(/ring-indigo-/g, 'ring-primary-');
            
            // Add animations
            content = content.replace(/class="bg-white/g, 'class="bg-white animate-fade-in-up');
            
            // Refactor icons
            for (const [bi, lucide] of Object.entries(iconMap)) {
                const regex = new RegExp(`<i class="bi ${bi}(?:[\\s\\w-]*)?"><\\/i>`, 'g');
                content = content.replace(regex, `<i data-lucide="${lucide}" class="w-4 h-4"></i>`);
                
                const regexWithClasses = new RegExp(`<i class="bi ${bi} ([^"]+)"><\\/i>`, 'g');
                content = content.replace(regexWithClasses, (match, classes) => {
                    return `<i data-lucide="${lucide}" class="w-4 h-4 ${classes}"></i>`;
                });
            }
            
            // Catch-all for any remaining bootstrap icons
            content = content.replace(/<i class="bi bi-([^"\s]+)([^"]*)"><\/i>/g, (match, iconName, extraClasses) => {
                return `<i data-lucide="${iconName}" class="w-4 h-4${extraClasses}"></i>`;
            });

            fs.writeFileSync(fullPath, content, 'utf-8');
        }
    }
}

// Process admin, customer, affiliator directories
['admin', 'customer', 'affiliator'].forEach(role => {
    const roleDir = path.join(viewsDir, role);
    if (fs.existsSync(roleDir)) {
        processDirectory(roleDir);
        console.log(`Refactored views in ${role}`);
    }
});

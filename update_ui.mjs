import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const layoutsDir = path.join(__dirname, 'resources', 'views', 'layouts');
const files = ['admin.blade.php', 'customer.blade.php', 'affiliator.blade.php'];

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
    'bi-file-earmark-text': 'file'
};

for (const file of files) {
    const filePath = path.join(layoutsDir, file);
    if (fs.existsSync(filePath)) {
        let content = fs.readFileSync(filePath, 'utf-8');
        
        // 1. Update Fonts
        content = content.replace(
            /<link href="https:\/\/fonts.googleapis.com\/css2\?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" \/>/g,
            '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet" />'
        );
        
        // 2. Remove Bootstrap Icons, Add Lucide Script
        content = content.replace(
            /<link href="https:\/\/cdn.jsdelivr.net\/npm\/bootstrap-icons@1.11.3\/font\/bootstrap-icons.min.css" rel="stylesheet" \/>/g,
            '<script src="https://unpkg.com/lucide@latest"></script>'
        );
        
        // Remove sweetalert2 CDN since we import it in app.js
        content = content.replace(
            /<script src="https:\/\/cdn.jsdelivr.net\/npm\/sweetalert2@11"><\/script>\n\s*/g,
            ''
        );

        // 3. Replace Icons
        for (const [bi, lucide] of Object.entries(iconMap)) {
            const regex = new RegExp(`<i class="bi ${bi}(?:[\\s\\w-]*)?"><\\/i>`, 'g');
            content = content.replace(regex, `<i data-lucide="${lucide}" class="w-5 h-5"></i>`);
            
            // For instances with mr-2 or other classes
            const regexWithClasses = new RegExp(`<i class="bi ${bi} ([^"]+)"><\\/i>`, 'g');
            content = content.replace(regexWithClasses, (match, classes) => {
                return `<i data-lucide="${lucide}" class="w-5 h-5 ${classes}"></i>`;
            });
        }
        
        // generic replace for any missed icons
        content = content.replace(/<i class="bi bi-([^"\s]+)([^"]*)"><\/i>/g, (match, iconName, extraClasses) => {
            return `<i data-lucide="${iconName}" class="w-5 h-5${extraClasses}"></i>`;
        });
        
        // Fix up navbar to be glassmorphism
        content = content.replace(
            /class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 h-16/g,
            'class="glass sticky top-0 z-30 h-16'
        );

        fs.writeFileSync(filePath, content, 'utf-8');
        console.log(`Updated ${file}`);
    }
}

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const layoutsDir = path.join(__dirname, 'resources', 'views', 'layouts');
const files = ['admin.blade.php', 'customer.blade.php', 'affiliator.blade.php'];

for (const file of files) {
    const filePath = path.join(layoutsDir, file);
    if (fs.existsSync(filePath)) {
        let content = fs.readFileSync(filePath, 'utf-8');
        
        // Replace colors
        content = content.replace(/bg-gray-/g, 'bg-surface-');
        content = content.replace(/text-gray-/g, 'text-surface-');
        content = content.replace(/border-gray-/g, 'border-surface-');
        
        content = content.replace(/bg-indigo-/g, 'bg-primary-');
        content = content.replace(/text-indigo-/g, 'text-primary-');
        content = content.replace(/border-indigo-/g, 'border-primary-');
        
        // Add subtle animation classes to sidebar links
        content = content.replace(/class="flex items-center px-2 py-2 text-sm font-medium rounded-md /g, 'class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group ');

        fs.writeFileSync(filePath, content, 'utf-8');
        console.log(`Refactored styling in ${file}`);
    }
}

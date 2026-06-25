import fs from 'fs';
import path from 'path';

function processDirectory(dir, layoutName, layoutImportPath) {
    if (!fs.existsSync(dir)) return;
    const files = fs.readdirSync(dir);
    for (const file of files) {
        const fullPath = path.join(dir, file);
        const stat = fs.statSync(fullPath);
        if (stat.isDirectory()) {
            processDirectory(fullPath, layoutName, layoutImportPath);
        } else if (fullPath.endsWith('.vue')) {
            let content = fs.readFileSync(fullPath, 'utf8');
            if (!content.includes(layoutName)) {
                const scriptSetupRegex = /<script setup[^>]*>/;
                if (scriptSetupRegex.test(content)) {
                    content = content.replace(
                        scriptSetupRegex, 
                        `$&\nimport ${layoutName} from '${layoutImportPath}';\ndefineOptions({ layout: ${layoutName} });`
                    );
                    fs.writeFileSync(fullPath, content, 'utf8');
                    console.log(`Fixed ${fullPath}`);
                }
            }
        }
    }
}

processDirectory('./resources/js/Pages/Admin', 'AdminLayout', '@/Layouts/AdminLayout.vue');
processDirectory('./resources/js/Pages/Customer', 'CustomerLayout', '@/Layouts/CustomerLayout.vue');
processDirectory('./resources/js/Pages/Affiliator', 'AffiliatorLayout', '@/Layouts/AffiliatorLayout.vue');

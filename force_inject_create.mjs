import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const viewsDir = path.join(__dirname, 'resources', 'views', 'admin');

const createRoutesMap = {
    'blog': 'admin.blog.create',
    'blogs': 'admin.blog.create',
    'cms/pages': 'admin.cms.pages.create',
    'emailcampaigns': 'admin.email-campaigns.create',
    'emailtemplates': 'admin.email-templates.create',
    'faqs': 'admin.faqs.create',
    'productcategories': 'admin.product-categories.create',
    'products': 'admin.products.create',
    'testimonials': 'admin.testimonials.create',
    'vouchers': 'admin.vouchers.create'
};

for (const [folder, routeName] of Object.entries(createRoutesMap)) {
    const file = path.join(viewsDir, folder, 'index.blade.php');
    if (fs.existsSync(file)) {
        let content = fs.readFileSync(file, 'utf-8');
        
        // Let's check if the toolbar already has the specific Add New button for this route
        if (content.includes(`route('${routeName}')`) && content.includes('Add New')) {
            console.log(`Skip ${folder}: already has Add New`);
            continue;
        }
        
        const btnHtml = `\n                <a href="{{ route('${routeName}') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-surface-900">\n                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>\n                    Add New\n                </a>\n            `;
        
        // Find <div class="flex items-center space-x-3 w-full sm:w-auto">
        const toolbarRegex = /<div class="flex items-center space-x-3 w-full sm:w-auto">([\s\S]*?)<\/div>/;
        const match = content.match(toolbarRegex);
        
        if (match) {
            let innerContent = match[1];
            // If innerContent already has the route, we probably don't need to append.
            if (!innerContent.includes(`route('${routeName}')`)) {
                content = content.replace(toolbarRegex, `<div class="flex items-center space-x-3 w-full sm:w-auto">${innerContent}${btnHtml}</div>`);
                fs.writeFileSync(file, content, 'utf-8');
                console.log(`Injected Add New for ${folder}`);
            } else {
                console.log(`Skip ${folder}: Toolbar already has route`);
            }
        }
    }
}

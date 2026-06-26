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

let changedFiles = 0;

files.forEach(file => {
    let content = fs.readFileSync(file, 'utf-8');
    let original = content;

    // Remove all onsubmit="return confirm(...)"
    content = content.replace(/onsubmit="return confirm\([^)]+\);?"/g, '');
    content = content.replace(/onsubmit='return confirm\([^)]+\);?'/g, '');
    content = content.replace(/onsubmit="return confirmDelete\([^)]+\);?"/g, '');

    // Now let's find all forms and inject the correct class
    // Regex to match <form ...>
    content = content.replace(/<form([^>]*)>/gi, (match, attrs) => {
        // Skip GET forms (like search)
        if (attrs.toUpperCase().includes('METHOD="GET"') || attrs.toUpperCase().includes("METHOD='GET'")) {
            return match;
        }

        // Check if it's a delete form (action contains destroy, or has @method('DELETE') inside?)
        // We can't check @method('DELETE') inside from here easily, but we can check if action contains 'destroy' or 'delete'
        const isDelete = attrs.includes('destroy') || attrs.includes('delete');
        
        let targetClass = isDelete ? 'form-confirm-delete' : 'form-confirm-submit';
        
        // If it already has the class, do nothing
        if (attrs.includes(targetClass)) {
            return `<form${attrs}>`;
        }
        
        // If it has class attribute, append to it
        if (attrs.includes('class="')) {
            attrs = attrs.replace(/class="/, `class="${targetClass} `);
        } else if (attrs.includes("class='")) {
            attrs = attrs.replace(/class='/, `class='${targetClass} `);
        } else {
            // No class attribute, add one
            attrs = ` class="${targetClass}"` + attrs;
        }
        
        return `<form${attrs}>`;
    });

    if (content !== original) {
        fs.writeFileSync(file, content, 'utf-8');
        changedFiles++;
    }
});

console.log(`Updated ${changedFiles} files with SweetAlert classes and removed native confirm().`);

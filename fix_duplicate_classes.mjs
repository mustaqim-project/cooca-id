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
    let original = content;

    // We will look for <form ... > and merge duplicate class attributes
    content = content.replace(/<form([^>]*)>/gi, (match, attrs) => {
        let classMatches = [...attrs.matchAll(/class=['"]([^'"]*)['"]/gi)];
        
        if (classMatches.length > 1) {
            // Merge classes
            let allClasses = new Set();
            classMatches.forEach(m => {
                m[1].split(/\s+/).forEach(c => {
                    if (c) allClasses.add(c);
                });
            });
            
            // Remove all class attributes from attrs
            attrs = attrs.replace(/\s*class=['"][^'"]*['"]/gi, '');
            
            // Add the merged class attribute
            attrs += ` class="${Array.from(allClasses).join(' ')}"`;
        }
        
        return `<form${attrs}>`;
    });

    if (content !== original) {
        fs.writeFileSync(file, content, 'utf-8');
    }
});

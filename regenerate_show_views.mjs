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
    // Only process show views
    if (!file.endsWith('show.blade.php')) return;
    
    let content = fs.readFileSync(file, 'utf-8');
    
    // Determine Layout
    const layoutMatch = content.match(/@extends\(['"]([^'"]+)['"]\)/);
    const layout = layoutMatch ? layoutMatch[1] : 'layouts.admin';
    
    // Determine Sections
    const titleMatch = content.match(/@section\('title',\s*(.*?)\)/);
    const title = titleMatch ? titleMatch[1] : "'Detail View'";
    
    const subtitleMatch = content.match(/@section\('subtitle',\s*(.*?)\)/);
    const subtitle = subtitleMatch ? subtitleMatch[1] : "'View details'";

    // Extract the main content container which is usually a dl or a grid of divs
    // Let's grab everything inside the outermost div inside @section('content')
    const contentMatch = content.match(/@section\('content'\)[\s\S]*?<div[^>]*>([\s\S]*?)<\/div>\s*@endsection/);
    
    if (contentMatch) {
        let innerContent = contentMatch[1];
        
        // Sometimes innerContent still contains the outer wrapper classes we want to strip.
        // We'll just take the innerContent, strip basic top level div wrappers if they're just containers.
        // A safer way is to just grab the raw <dl> or data grids.
        
        // Clean up some hardcoded old tailwind text classes
        innerContent = innerContent.replace(/text-gray-500/g, 'text-surface-500 dark:text-surface-400');
        innerContent = innerContent.replace(/text-gray-900/g, 'text-surface-900 dark:text-white');
        innerContent = innerContent.replace(/bg-gray-50/g, 'bg-surface-50 dark:bg-surface-900/50');
        innerContent = innerContent.replace(/border-gray-200/g, 'border-surface-200 dark:border-surface-700');

        let newContent = `@extends('${layout}')

@section('title', ${title})
@section('subtitle', ${subtitle})

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
        </a>
    </div>

    <!-- Details Card -->
    <div class="corporate-card">
        <div class="card-header">
            <h3 class="card-title">Information Details</h3>
        </div>
        <div class="card-body">
            ${innerContent.trim()}
        </div>
    </div>
</div>
@endsection
`;
        fs.writeFileSync(file, newContent, 'utf-8');
        console.log(`Structurally regenerated show view: ${file.replace(__dirname, '')}`);
    }
});

console.log('Finished show view regeneration.');

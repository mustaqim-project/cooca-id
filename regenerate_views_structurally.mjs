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
    // Skip non-standard views
    if(file.includes('layouts') || file.includes('components') || file.includes('emails') || file.includes('auth')) return;
    
    let content = fs.readFileSync(file, 'utf-8');
    let newContent = content;
    
    // Determine Layout
    const layoutMatch = content.match(/@extends\(['"]([^'"]+)['"]\)/);
    const layout = layoutMatch ? layoutMatch[1] : 'layouts.admin';
    
    // Determine Sections
    const titleMatch = content.match(/@section\('title',\s*(.*?)\)/);
    const title = titleMatch ? titleMatch[1] : "'View'";
    
    const subtitleMatch = content.match(/@section\('subtitle',\s*(.*?)\)/);
    const subtitle = subtitleMatch ? subtitleMatch[1] : "'Manage'";

    // --- INDEX VIEWS ---
    if (file.endsWith('index.blade.php') && content.includes('<table')) {
        // Extract top buttons
        const actionBtnMatch = content.match(/<a href="[^"]+" class="[^"]*bg-primary-600[^"]*">([\s\S]*?)<\/a>/);
        let actionBtn = '';
        if (actionBtnMatch) {
            actionBtn = actionBtnMatch[0].replace('bg-primary-600', 'btn btn-primary');
        }

        // Extract table contents
        const theadMatch = content.match(/<thead[^>]*>([\s\S]*?)<\/thead>/);
        const tbodyMatch = content.match(/<tbody[^>]*>([\s\S]*?)<\/tbody>/);
        
        if (theadMatch && tbodyMatch) {
            let thead = theadMatch[1];
            let tbody = tbodyMatch[1];
            
            // Clean up thead styling
            thead = thead.replace(/class="[^"]*"/g, 'class="table-th"');

            // Clean up delete forms
            tbody = tbody.replace(/onsubmit="return confirm\\([^)]+\\);?"/g, '');
            tbody = tbody.replace(/<form action="([^"]+)" method="POST" class="([^"]*)"/g, function(match, p1, p2) {
                if(p2.includes('form-confirm-delete')) return match;
                return '<form action="' + p1 + '" method="POST" class="' + p2 + ' form-confirm-delete"';
            });
            
            // Reconstruct HTML
            newContent = `@extends('${layout}')

@section('title', ${title})
@section('subtitle', ${subtitle})

@section('content')
<div class="space-y-6">
    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div class="relative w-full sm:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-5 h-5 text-surface-400"></i>
            </div>
            <input type="text" placeholder="Search..." class="block w-full pl-10 pr-3 py-2 border border-surface-300 dark:border-surface-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800 text-surface-900 dark:text-white placeholder-surface-400 shadow-sm transition-shadow hover:shadow-md">
        </div>
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            ${actionBtn ? actionBtn : ''}
        </div>
    </div>

    <!-- Data Table -->
    <div class="corporate-card">
        <div class="overflow-x-auto">
            <table class="corporate-table">
                <thead class="table-thead">
                    ${thead}
                </thead>
                <tbody class="table-tbody">
                    ${tbody}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
`;
        }
    } 
    // --- CREATE / EDIT VIEWS ---
    else if ((file.endsWith('create.blade.php') || file.endsWith('edit.blade.php')) && content.includes('<form')) {
        const formMatch = content.match(/<form\s+action="([^"]+)"\s+method="([^"]+)"(?:\s+enctype="([^"]+)")?[\s\S]*?>([\s\S]*?)<\/form>/);
        
        if (formMatch) {
            const action = formMatch[1];
            const method = formMatch[2];
            const enctype = formMatch[3] ? `enctype="${formMatch[3]}"` : '';
            const formInner = formMatch[4];
            
            // Extract CSRF & Method
            const csrf = formInner.match(/@csrf/) ? '@csrf' : '';
            const putMethod = formInner.match(/@method\(['"][^'"]+['"]\)/) ? formInner.match(/@method\(['"][^'"]+['"]\)/)[0] : '';
            
            // Extract all content excluding the submit button div and csrf/method
            let fieldsContent = formInner
                .replace(/@csrf/g, '')
                .replace(/@method\(['"][^'"]+['"]\)/g, '')
                .replace(/<div class="(?:mb-4\s+)?(?:mt-6\s+)?flex justify-end[\s\S]*?<\/div>\s*$/i, ''); // Strip trailing button div
                
            // Also try to strip generic submit buttons
            fieldsContent = fieldsContent.replace(/<button type="submit"[^>]*>[\s\S]*?<\/button>/, '');

            newContent = `@extends('${layout}')

@section('title', ${title})
@section('subtitle', ${subtitle})

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-surface-500 hover:text-surface-900 dark:text-surface-400 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
        </a>
    </div>

    <form action="${action}" method="${method}" ${enctype} class="form-confirm-submit">
        ${csrf}
        ${putMethod}
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="corporate-card">
                    <div class="card-header">
                        <h3 class="card-title">Form Details</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        ${fieldsContent.trim()}
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Actions -->
            <div class="space-y-6">
                <div class="corporate-card">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Actions</h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400 mb-6">Review your changes before submitting.</p>
                        
                        <div class="flex flex-col space-y-3">
                            <button type="submit" class="btn btn-primary w-full">
                                <i data-lucide="save" class="w-4 h-4 mr-2 icon-3d"></i> Save Data
                            </button>
                            <a href="javascript:history.back()" class="btn btn-secondary w-full">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
`;
        }
    }

    if (content !== newContent) {
        fs.writeFileSync(file, newContent, 'utf-8');
        console.log(`Structurally regenerated: ${file.replace(__dirname, '')}`);
    }
});

console.log('Finished structural regeneration.');

import os
import re
import glob

VIEWS_DIR = r"c:\laragon\www\cooca-id\resources\views"

def fix_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    original_content = content

    # The duplicated left column start block usually looks like:
    #         <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    #             <!-- Left Column: Main Form -->
    #             <div class="lg:col-span-2 space-y-6">
    #                 <div class="corporate-card">
    # ...
    
    # Actually, a simpler way is to find the exact duplicated blocks and remove them.
    # Block A:
    # <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">\s*<!-- Left Column: Main Form -->\s*<div class="lg:col-span-2 space-y-6">\s*<div class="corporate-card">\s*<div class="card-header">\s*<h3 class="card-title">Form Details</h3>\s*</div>\s*<div class="p-6 space-y-5">\s*
    
    # Let's just remove the recursively injected outer wrappers
    bad_wrapper_1 = re.compile(
        r'<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">\s*<!-- Left Column: Main Form -->\s*<div class="lg:col-span-2 space-y-6">\s*<div class="corporate-card">\s*<div class="card-header">\s*<h3 class="card-title">Form Details</h3>\s*</div>\s*<div class="p-6 space-y-5">\s*',
        re.IGNORECASE | re.MULTILINE
    )
    
    # We just want to strip all occurrences of bad_wrapper_1 EXCEPT the last one if it's not nested? No, wait. 
    # In the generated code, the REAL form details is:
    # <div class="p-6 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
    #                        <h3 class="text-lg font-medium text-surface-900 dark:text-white">Form Details</h3>
    #                    </div>
    
    # So ANY occurrence of bad_wrapper_1 is actually garbage added by the script. We can just replace it with empty string!
    # BUT wait, the first one is:
    # <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    # That one might be needed!
    
    # Instead of regex, let's use a cleaner approach: just reconstruct the files from their inner content.
    # It might be very complex to parse HTML. Let's look for known duplication patterns.
    
    pattern_left_col = r'(?:<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">\s*)?<!-- Left Column: Main Form -->\s*<div class="lg:col-span-2 space-y-6">\s*<div class="corporate-card">\s*<div class="card-header">\s*<h3 class="card-title">Form Details</h3>\s*</div>\s*<div class="p-6 space-y-5">\s*'
    content = re.sub(pattern_left_col, '', content)
    
    # And there is a duplicate Right Column:
    pattern_right_col = r'\s*</div>\s*</div>\s*</div>\s*<!-- Right Column: Actions -->\s*<div class="space-y-6">\s*<div class="corporate-card">\s*<div class="p-6">\s*<h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Actions</h3>\s*<p class="text-sm text-surface-500 dark:text-surface-400 mb-6">Review your changes before submitting.</p>\s*<div class="flex flex-col space-y-3">\s*<a href="javascript:history.back\(\)" class="btn btn-secondary w-full">\s*Cancel\s*</a>\s*</div>\s*</div>\s*</div>\s*</div>\s*</div>'
    content = re.sub(pattern_right_col, '', content)
    
    # Another variant of the right col (with custom button)
    pattern_right_col_2 = r'\s*</div>\s*</div>\s*</div>\s*<!-- Right Column: Actions -->\s*<div class="space-y-6">\s*<div class="corporate-card">\s*<div class="p-6">\s*<h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Actions</h3>\s*<p class="text-sm text-surface-500 dark:text-surface-400 mb-6">Review your changes before submitting.</p>\s*<div class="flex flex-col space-y-3">\s*<a href="javascript:history.back\(\)" class="w-full inline-flex justify-center items-center px-4 py-2\.5 border border-surface-300 dark:border-surface-600 rounded-lg shadow-sm text-sm font-medium text-surface-700 dark:text-surface-300 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">\s*Cancel\s*</a>\s*</div>\s*</div>\s*</div>\s*</div>\s*</div>'
    content = re.sub(pattern_right_col_2, '', content)

    if content != original_content:
        # Also need to re-inject <div class="grid grid-cols-1 lg:grid-cols-3 gap-6"> before the REAL left column.
        content = content.replace('<!-- Left Column: Main Form -->', '<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">\n            <!-- Left Column: Main Form -->')
        
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed: {filepath}")

for root, _, files in os.walk(VIEWS_DIR):
    for file in files:
        if file.endswith('.blade.php'):
            fix_file(os.path.join(root, file))


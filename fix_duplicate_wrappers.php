<?php

$viewsDir = __DIR__ . '/resources/views';

function clean_duplicate_wrappers($filepath) {
    $content = file_get_contents($filepath);
    $original = $content;

    // Pattern to match the left column wrapper that gets repeated
    // <div class="grid grid-cols-1 lg:grid-cols-3 gap-6"> (optional)
    // <!-- Left Column: Main Form -->
    // <div class="lg:col-span-2 space-y-6">
    // <div class="corporate-card">
    // <div class="card-header">
    // <h3 class="card-title">Form Details</h3>
    // </div>
    // <div class="p-6 space-y-5">
    
    $left_col_pattern = '/(?:<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">\s*)?<!-- Left Column: Main Form -->\s*<div class="lg:col-span-2 space-y-6">\s*<div class="corporate-card">\s*<div class="card-header">\s*<h3 class="card-title">Form Details<\/h3>\s*<\/div>\s*<div class="p-6 space-y-5">\s*/is';
    
    $content = preg_replace($left_col_pattern, '', $content);
    
    $right_col_pattern = '/\s*<\/div>\s*<\/div>\s*<\/div>\s*<!-- Right Column: Actions -->\s*<div class="space-y-6">\s*<div class="corporate-card">\s*<div class="p-6">\s*<h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Actions<\/h3>\s*<p class="text-sm text-surface-500 dark:text-surface-400 mb-6">Review your changes before submitting\.<\/p>\s*<div class="flex flex-col space-y-3">\s*<a href="javascript:history\.back\(\)" class="btn btn-secondary w-full">\s*Cancel\s*<\/a>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/is';
    
    $content = preg_replace($right_col_pattern, '', $content);
    
    $right_col_pattern_2 = '/\s*<\/div>\s*<\/div>\s*<\/div>\s*<!-- Right Column: Actions -->\s*<div class="space-y-6">\s*<div class="corporate-card">\s*<div class="p-6">\s*<h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Actions<\/h3>\s*<p class="text-sm text-surface-500 dark:text-surface-400 mb-6">Review your changes before submitting\.<\/p>\s*<div class="flex flex-col space-y-3">\s*(?:<button type="submit"[^>]*>.*?<\/button>\s*)?<a href="javascript:history\.back\(\)"[^>]*>\s*Cancel\s*<\/a>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/is';
    
    $content = preg_replace($right_col_pattern_2, '', $content);
    
    // Also there is a variant where the button is inside the right column:
    // <button type="submit" class="btn btn-primary w-full">... Save Data ...</button>
    // We handle it with the (?:<button...) in right_col_pattern_2 above.

    if ($content !== $original) {
        // Re-inject one valid wrapper!
        // We know what the true inner content looks like. We just removed all the outer wrappers.
        // Wait! We also need the real Right Column to exist!
        // If we removed ALL of them, we need to put ONE back.
        
        // Find where <!-- Right Column: Actions --> was supposed to go.
        // It should be placed after the form details closing tags.
        // This regex approach might be too destructive.
        
        // Let's just restore the file from the basic fields!
    }
}

// Better approach: Let's use a specialized node script to regenerate the views cleanly!

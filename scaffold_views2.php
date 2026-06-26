<?php
$viewsDir = __DIR__ . '/resources/views';

$replacements = [
    "admin/settings/index.blade.php" => <<<HTML
<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- General Settings -->
    <div class="bg-white dark:bg-surface-800 shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
            <h3 class="text-lg font-medium text-surface-900 dark:text-white">General Settings</h3>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label for="platform_name" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Platform Name</label>
                <input type="text" name="platform_name" id="platform_name" value="{{ old('platform_name', \$settings['platform_name'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
            </div>
            <div>
                <label for="email_support" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Support Email</label>
                <input type="email" name="email_support" id="email_support" value="{{ old('email_support', \$settings['email_support'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
            </div>
            <div>
                <label for="whatsapp_number" class="block text-sm font-medium text-surface-700 dark:text-surface-300">WhatsApp Number</label>
                <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', \$settings['whatsapp_number'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
            </div>
        </div>
    </div>
    
    <!-- Affiliate Settings -->
    <div class="bg-white dark:bg-surface-800 shadow-sm rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-900">
            <h3 class="text-lg font-medium text-surface-900 dark:text-white">Affiliate Settings</h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="affiliate_commission_l1" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Level 1 Commission (%)</label>
                    <input type="number" step="0.01" name="affiliate_commission_l1" id="affiliate_commission_l1" value="{{ old('affiliate_commission_l1', \$settings['affiliate_commission_l1'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                </div>
                <div>
                    <label for="affiliate_commission_l2" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Level 2 Commission (%)</label>
                    <input type="number" step="0.01" name="affiliate_commission_l2" id="affiliate_commission_l2" value="{{ old('affiliate_commission_l2', \$settings['affiliate_commission_l2'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="minimum_withdrawal" class="block text-sm font-medium text-surface-700 dark:text-surface-300">Minimum Withdrawal (Rp)</label>
                    <input type="number" name="minimum_withdrawal" id="minimum_withdrawal" value="{{ old('minimum_withdrawal', \$settings['minimum_withdrawal'] ?? '') }}" class="mt-1 block w-full border border-surface-300 dark:border-surface-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-white dark:bg-surface-800">
                </div>
            </div>
        </div>
    </div>
    
    <div class="flex justify-end">
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm text-sm">
            Save Settings
        </button>
    </div>
</form>
HTML,

    "affiliator/downlines/index.blade.php" => <<<HTML
<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse(\$downlines ?? [] as \$downline)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ \$downline->id ?? \$downline['id'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ \$downline->name ?? \$downline['name'] }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">{{ \$downline->email ?? \$downline['email'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                            Active
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">Today</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <span class="text-surface-400">Read Only</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">You don't have any downlines yet.</td></tr>
                @endforelse
            </tbody>
HTML,

    "affiliator/downlines/tree.blade.php" => <<<HTML
<div class="px-6 py-12 text-center text-surface-500 dark:text-surface-400">
        <i data-lucide="network" class="w-12 h-12 mx-auto text-surface-300 dark:text-surface-600 mb-4"></i>
        <h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Downline Tree is currently in development</h3>
        <p>A visual representation of your affiliate network will be available soon.</p>
    </div>
HTML,

    "affiliator/downlines/stats.blade.php" => <<<HTML
<div class="px-6 py-12 text-center text-surface-500 dark:text-surface-400">
        <i data-lucide="bar-chart-2" class="w-12 h-12 mx-auto text-surface-300 dark:text-surface-600 mb-4"></i>
        <h3 class="text-lg font-medium text-surface-900 dark:text-white mb-2">Detailed Statistics coming soon</h3>
        <p>Advanced metrics and charts for your network performance are being prepared.</p>
    </div>
HTML,

    "affiliator/marketing/index.blade.php" => <<<HTML
<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse(\$products ?? [] as \$product)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ \$product['id'] ?? \$product->id ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ \$product['name'] ?? \$product->name ?? 'Product' }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400 line-clamp-1">{{ \$product['description'] ?? \$product->description ?? 'Description' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Active</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">-</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end">
                        <a href="{{ \$product['referral_link'] ?? '#' }}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 mr-3 inline-flex items-center" title="Open Link"><i data-lucide="external-link" class="w-4 h-4"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">No marketing materials found.</td></tr>
                @endforelse
            </tbody>
HTML,

    "affiliator/marketing/banners.blade.php" => <<<HTML
<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse(\$banners ?? [] as \$banner)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ \$banner['id'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ \$banner['name'] }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">Size: {{ \$banner['size'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Ready</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">-</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end">
                        <button onclick="navigator.clipboard.writeText('{{ addslashes(\$banner['html_code']) }}'); alert('Copied to clipboard!')" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 inline-flex items-center" title="Copy HTML"><i data-lucide="copy" class="w-4 h-4"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">No banners found.</td></tr>
                @endforelse
            </tbody>
HTML,

    "affiliator/marketing/links.blade.php" => <<<HTML
<tbody class="bg-white dark:bg-surface-800 divide-y divide-surface-200 dark:divide-surface-700">
                @forelse(\$links ?? [] as \$idx => \$link)
                <tr class="hover:bg-surface-50 dark:bg-surface-900 dark:hover:bg-surface-700/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">{{ \$idx + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-surface-900 dark:text-white">{{ \$link['name'] }}</div>
                        <div class="text-sm text-surface-500 dark:text-surface-400">{{ \$link['description'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Ready</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500 dark:text-surface-400">-</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end">
                        <button onclick="navigator.clipboard.writeText('{{ addslashes(\$link['url']) }}'); alert('Link copied to clipboard!')" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 inline-flex items-center" title="Copy Link"><i data-lucide="copy" class="w-4 h-4"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-surface-500">No links found.</td></tr>
                @endforelse
            </tbody>
HTML
];

foreach ($replacements as $filepath => $new_tbody) {
    $full_path = $viewsDir . '/' . $filepath;
    if (!file_exists($full_path)) {
        echo "File not found: $full_path\n";
        continue;
    }
    
    $content = file_get_contents($full_path);
    
    if (strpos($filepath, 'admin/settings/index') !== false) {
        // Completely rewrite the file for settings because it's a form, not a table
        $content = preg_replace('/<div class="mb-4 flex justify-between items-center">.*?<\/div>\s*<div class="bg-white.*?<\/div>/is', $new_tbody, $content);
    } 
    elseif (strpos($filepath, 'downlines/tree') !== false || strpos($filepath, 'downlines/stats') !== false) {
        // Just replace the table with the placeholder
        $content = preg_replace('/<div class="overflow-x-auto">.*?<\/div>\s*<div class="px-6 py-4 border-t.*?>\s*<p.*?>.*?<\/p>\s*<\/div>/is', $new_tbody, $content);
    }
    else {
        // Replace <tbody...>...</tbody>
        $content = preg_replace('/<tbody[^>]*>.*?<\/tbody>/is', $new_tbody, $content);
        // Remove "Data populated successfully."
        $content = str_replace('<p class="text-sm text-surface-500 dark:text-surface-400 text-center">Data populated successfully.</p>', '', $content);
    }
    
    // Remove "Add New" button for affiliator marketing and downlines (they are read-only)
    if (strpos($filepath, 'affiliator') !== false && strpos($filepath, 'reviews') === false) {
        $content = preg_replace('/<a href="#"[^>]*>.*?Add New.*?<\/a>/is', '', $content);
    }
    
    file_put_contents($full_path, $content);
    echo "Successfully updated $filepath\n";
}

<?php
$files = [
    'app/Http/Controllers/Affiliator/ReferralController.php' => [
        "    public function stats(): \\Inertia\\Response\n    {\n        return \\Inertia\\Inertia::render('Affiliator/Referrals/Stats');\n    }\n"
    ],
    'app/Http/Controllers/Affiliator/CommissionController.php' => [
        "    public function stats(): \\Inertia\\Response\n    {\n        return \\Inertia\\Inertia::render('Affiliator/Commissions/Stats');\n    }\n",
        "    public function export()\n    {\n        return response()->json(['message' => 'Exporting commissions']);\n    }\n"
    ],
    'app/Http/Controllers/Affiliator/DownlineController.php' => [
        "    public function tree(): \\Inertia\\Response\n    {\n        return \\Inertia\\Inertia::render('Affiliator/Downlines/Tree');\n    }\n",
        "    public function stats(): \\Inertia\\Response\n    {\n        return \\Inertia\\Inertia::render('Affiliator/Downlines/Stats');\n    }\n"
    ],
    'app/Http/Controllers/Affiliator/WithdrawalController.php' => [
        "    public function history(): \\Inertia\\Response\n    {\n        return \\Inertia\\Inertia::render('Affiliator/Withdrawals/History');\n    }\n",
        "    public function create(): \\Inertia\\Response\n    {\n        return \\Inertia\\Inertia::render('Affiliator/Withdrawals/Create');\n    }\n"
    ],
    'app/Http/Controllers/Affiliator/MarketingController.php' => [
        "    public function banners(): \\Inertia\\Response\n    {\n        return \\Inertia\\Inertia::render('Affiliator/Marketing/Banners');\n    }\n",
        "    public function links(): \\Inertia\\Response\n    {\n        return \\Inertia\\Inertia::render('Affiliator/Marketing/Links');\n    }\n"
    ]
];

foreach ($files as $file => $methods) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    // Insert before the last closing brace
    $lastBracePos = strrpos($content, '}');
    if ($lastBracePos !== false) {
        $insert = implode("\n", $methods);
        $newContent = substr($content, 0, $lastBracePos) . "\n" . $insert . "\n}\n";
        file_put_contents($file, $newContent);
        echo "Patched $file\n";
    }
}

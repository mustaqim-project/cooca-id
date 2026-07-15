<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

final class ErrorLogController extends Controller
{
    public function index(Request $request): View
    {
        $logPath = storage_path('logs/laravel.log');
        $logs = [];

        if (File::exists($logPath)) {
            // Read last 1000 lines max to prevent memory exhaustion
            $file = new \SplFileObject($logPath, 'r');
            $file->seek(PHP_INT_MAX);
            $totalLines = $file->key();
            
            $startLine = max(0, $totalLines - 1000);
            $file->seek($startLine);
            
            $currentLog = null;
            
            while (!$file->eof()) {
                $line = $file->fgets();
                if (trim($line) === '') {
                    continue;
                }
                
                // Parse standard Laravel log format: [2023-01-01 12:00:00] local.ERROR: message
                if (preg_match('/^\[(?P<date>.*?)\] (?P<env>\w+)\.(?P<level>[A-Z]+): (?P<message>.*)/', $line, $matches)) {
                    if ($currentLog) {
                        $logs[] = $currentLog;
                    }
                    $currentLog = [
                        'timestamp' => $matches['date'],
                        'env' => $matches['env'],
                        'level' => $matches['level'],
                        'message' => $matches['message'],
                        'stack' => '',
                    ];
                } else if ($currentLog) {
                    $currentLog['stack'] .= $line;
                }
            }
            if ($currentLog) {
                $logs[] = $currentLog;
            }
        }

        // Reverse to show newest first
        $logs = array_reverse($logs);

        // Filter by level
        if ($request->filled('level')) {
            $level = strtoupper($request->input('level'));
            $logs = array_filter($logs, fn($log) => $log['level'] === $level);
        }

        return view('admin.error-logs.index', compact('logs'));
    }

    public function clear()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, '');
        }
        
        return back()->with('success', 'Error logs cleared successfully.');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ErrorLogController extends Controller
{
    /**
     * Display the error logs view with parsed log records.
     */
    public function index(Request $request): View|JsonResponse
    {
        $logDir = storage_path('logs');
        $files = $this->getAvailableLogFiles($logDir);

        // Determine active file
        $selectedFile = $request->input('file');
        if (!$selectedFile || !in_array($selectedFile, $files, true)) {
            $selectedFile = $files[0] ?? 'laravel.log';
        }

        $activeFilePath = $logDir . DIRECTORY_SEPARATOR . $selectedFile;
        $parsedLogs = $this->parseLogFile($activeFilePath);

        // Stats before filtering
        $stats = [
            'total' => count($parsedLogs),
            'errors' => count(array_filter($parsedLogs, fn($l) => in_array($l['level'], ['ERROR', 'CRITICAL', 'EMERGENCY', 'ALERT'], true))),
            'warnings' => count(array_filter($parsedLogs, fn($l) => $l['level'] === 'WARNING')),
            'info' => count(array_filter($parsedLogs, fn($l) => in_array($l['level'], ['INFO', 'NOTICE', 'DEBUG'], true))),
        ];

        // Apply Level Filter
        if ($request->filled('level') && $request->input('level') !== 'all') {
            $filterLevel = strtoupper($request->input('level'));
            $parsedLogs = array_filter($parsedLogs, fn($l) => $l['level'] === $filterLevel);
        }

        // Apply Search Filter
        if ($request->filled('search')) {
            $search = strtolower((string) $request->input('search'));
            $parsedLogs = array_filter($parsedLogs, function ($l) use ($search) {
                return str_contains(strtolower($l['message']), $search) ||
                       str_contains(strtolower($l['stack']), $search) ||
                       str_contains(strtolower($l['timestamp']), $search);
            });
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'file' => $selectedFile,
                'stats' => $stats,
                'logs' => array_values($parsedLogs),
            ]);
        }

        return view('admin.error-logs.index', [
            'logs' => array_values($parsedLogs),
            'files' => $files,
            'selectedFile' => $selectedFile,
            'stats' => $stats,
            'fileSize' => File::exists($activeFilePath) ? round(File::size($activeFilePath) / 1024, 2) : 0,
        ]);
    }

    /**
     * Clear the currently selected log file.
     */
    public function clear(Request $request)
    {
        $logDir = storage_path('logs');
        $selectedFile = $request->input('file', 'laravel.log');
        $filePath = $logDir . DIRECTORY_SEPARATOR . basename($selectedFile);

        if (File::exists($filePath)) {
            File::put($filePath, '');
            return back()->with('success', "Log file [{$selectedFile}] has been cleared successfully.");
        }

        return back()->with('error', "Log file [{$selectedFile}] not found.");
    }

    /**
     * Download the raw log file.
     */
    public function download(Request $request): BinaryFileResponse
    {
        $logDir = storage_path('logs');
        $selectedFile = $request->input('file', 'laravel.log');
        $filePath = $logDir . DIRECTORY_SEPARATOR . basename($selectedFile);

        if (!File::exists($filePath)) {
            abort(404, 'Log file not found.');
        }

        return response()->download($filePath, $selectedFile, [
            'Content-Type' => 'text/plain',
        ]);
    }

    /**
     * List all log files in storage/logs sorted by modified date descending.
     *
     * @return array<string>
     */
    private function getAvailableLogFiles(string $dir): array
    {
        if (!File::isDirectory($dir)) {
            return [];
        }

        $allFiles = File::files($dir);
        $logFiles = [];

        foreach ($allFiles as $file) {
            if ($file->getExtension() === 'log') {
                $logFiles[$file->getFilename()] = $file->getMTime();
            }
        }

        arsort($logFiles);

        return array_keys($logFiles);
    }

    /**
     * Parse log file entries efficiently.
     *
     * @return array<array{id: int, timestamp: string, env: string, level: string, message: string, stack: string}>
     */
    private function parseLogFile(string $filePath): array
    {
        if (!File::exists($filePath) || File::size($filePath) === 0) {
            return [];
        }

        $logs = [];
        $currentLog = null;
        $logId = 1;

        // Read file line by line
        $file = new \SplFileObject($filePath, 'r');
        $file->seek(PHP_INT_MAX);
        $totalLines = $file->key();

        // Limit to reading the last 2000 lines to prevent OOM
        $startLine = max(0, $totalLines - 2000);
        $file->seek($startLine);

        while (!$file->eof()) {
            $line = (string) $file->fgets();
            if (trim($line) === '') {
                continue;
            }

            // Pattern: [2026-08-17 22:31:30] production.ERROR: message
            if (preg_match('/^\[(?P<date>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (?P<env>\w+)\.(?P<level>[A-Z]+): (?P<message>.*)/', $line, $matches)) {
                if ($currentLog !== null) {
                    $logs[] = $currentLog;
                }

                $currentLog = [
                    'id' => $logId++,
                    'timestamp' => $matches['date'],
                    'env' => $matches['env'],
                    'level' => $matches['level'],
                    'message' => trim($matches['message']),
                    'stack' => '',
                ];
            } elseif ($currentLog !== null) {
                $currentLog['stack'] .= $line;
            }
        }

        if ($currentLog !== null) {
            $logs[] = $currentLog;
        }

        // Return newest log entries first
        return array_reverse($logs);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $lines = max(50, min((int) $request->query('lines', 300), 2000));
        $level = strtoupper((string) $request->query('level', ''));
        $logPath = storage_path('logs/laravel.log');

        $entries = [];

        if (is_file($logPath)) {
            $rows = @file($logPath, FILE_IGNORE_NEW_LINES) ?: [];
            $tail = array_slice($rows, -$lines);

            if ($level !== '' && in_array($level, ['ERROR', 'WARNING', 'INFO', 'DEBUG'], true)) {
                $tail = array_values(array_filter(
                    $tail,
                    fn(string $line): bool => str_contains(strtoupper($line), ".{$level}:")
                ));
            }

            $entries = $tail;
        }

        return view('admin.logs.index', [
            'entries' => $entries,
            'logPath' => $logPath,
            'lines' => $lines,
            'level' => $level,
        ]);
    }
}

<?php

namespace App\Services\Dashboard;

use Illuminate\Pagination\LengthAwarePaginator;

class LogViewerService
{
    private const MAX_BYTES_READ = 5 * 1024 * 1024;

    public const LEVELS = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];

    public function paginate(?string $level, ?string $search, int $page, int $perPage = 25): LengthAwarePaginator
    {
        $entries = $this->parseEntries();

        if ($level) {
            $entries = array_filter($entries, fn (array $e) => $e['level'] === strtolower($level));
        }

        if ($search) {
            $needle = strtolower($search);
            $entries = array_filter($entries, fn (array $e) => str_contains(strtolower($e['message']), $needle));
        }

        $entries = array_values($entries);
        $offset = ($page - 1) * $perPage;

        return new LengthAwarePaginator(
            array_slice($entries, $offset, $perPage),
            count($entries),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    /**
     * @return array<int, array{timestamp: string, level: string, message: string}>
     */
    private function parseEntries(): array
    {
        $path = storage_path('logs/laravel.log');

        if (!is_file($path)) {
            return [];
        }

        $size = filesize($path);
        $handle = fopen($path, 'r');
        fseek($handle, $size > self::MAX_BYTES_READ ? $size - self::MAX_BYTES_READ : 0);
        $content = stream_get_contents($handle);
        fclose($handle);

        preg_match_all(
            '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(\w+): (.*?)(?=^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] \w+\.\w+: |\z)/ms',
            $content,
            $matches,
            PREG_SET_ORDER
        );

        $entries = [];
        foreach ($matches as $match) {
            $entries[] = [
                'timestamp' => $match[1],
                'level' => strtolower($match[2]),
                'message' => trim($match[3]),
            ];
        }

        return array_reverse($entries);
    }
}

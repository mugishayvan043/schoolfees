<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;

class SqliteDatabase
{
    public static function ensureFileExists(): void
    {
        if (config('database.default') !== 'sqlite') {
            return;
        }

        $database = config('database.connections.sqlite.database');

        if ($database === ':memory:' || blank($database)) {
            return;
        }

        try {
            $directory = dirname($database);

            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            if (! file_exists($database)) {
                touch($database);
                Log::info('SQLite database file created automatically.', ['path' => $database]);
            }
        } catch (\Throwable $exception) {
            Log::error('Unable to prepare SQLite database file.', [
                'path' => $database,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}

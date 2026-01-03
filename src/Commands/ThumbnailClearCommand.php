<?php

/**
 * Laravel On-Demand Thumbnails
 * 
 * @package    moonlight-poland/laravel-thumbnails
 * @author     Moonlight Poland Team <kontakt@howtodraw.pl>
 * @copyright  2024-2026 Moonlight Poland. All rights reserved.
 * @license    Commercial License - See LICENSE.md
 * @link       https://github.com/Moonlight4000/laravel-thumbnails
 * 
 * COMMERCIAL LICENSE: Free for personal use, paid for commercial use.
 * See: https://github.com/Moonlight4000/laravel-thumbnails/blob/main/LICENSE.md
 */

namespace Moonlight\Thumbnails\Commands;

use Illuminate\Console\Command;
use Moonlight\Thumbnails\Services\ThumbnailService;

/**
 * Clear thumbnails command
 * 
 * Usage:
 * php artisan thumbnails:clear                  # Clear all thumbnails
 * php artisan thumbnails:clear photos           # Clear directory
 * php artisan thumbnails:clear photos/image.jpg # Clear specific image
 * 
 * @package Moonlight\Thumbnails
 */
class ThumbnailClearCommand extends Command
{
    protected $signature = 'thumbnails:clear
                            {path? : Path to clear (optional, clears all if not provided)}
                            {--force : Skip confirmation}';

    protected $description = 'Clear thumbnail cache';

    public function handle(ThumbnailService $thumbnailService): int
    {
        $path = $this->argument('path');
        $force = $this->option('force');

        if (!$path) {
            // Clear all thumbnails
            if (!$force && !$this->confirm('Clear ALL thumbnails in entire app?', false)) {
                $this->info('Cancelled.');
                return self::SUCCESS;
            }

            $this->info('Clearing all thumbnails...');
            $deleted = $thumbnailService->clearAllThumbnails();
            $this->info("✓ Cleared {$deleted} thumbnail directories");

        } elseif (pathinfo($path, PATHINFO_EXTENSION)) {
            // Clear specific image thumbnails
            $this->info("Clearing thumbnails for: {$path}");
            $thumbnailService->deleteThumbnails($path);
            $this->info("✓ Done");

        } else {
            // Clear directory thumbnails
            if (!$force && !$this->confirm("Clear thumbnails in: {$path}?", true)) {
                $this->info('Cancelled.');
                return self::SUCCESS;
            }

            $this->info("Clearing thumbnails in: {$path}");
            $deleted = $thumbnailService->clearAllThumbnails($path);
            $this->info($deleted > 0 ? "✓ Cleared {$deleted} thumbnail directories" : "No thumbnails found");
        }

        return self::SUCCESS;
    }
}


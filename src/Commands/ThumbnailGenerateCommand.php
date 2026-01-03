<?php

namespace Moonlight\Thumbnails\Commands;

use Illuminate\Console\Command;
use Moonlight\Thumbnails\Services\ThumbnailService;

/**
 * Generate thumbnails command
 * 
 * Usage:
 * php artisan thumbnails:generate photos/image.jpg
 * php artisan thumbnails:generate photos/image.jpg --size=small
 * 
 * @package Moonlight\Thumbnails
 */
class ThumbnailGenerateCommand extends Command
{
    protected $signature = 'thumbnails:generate
                            {path : Relative path to image}
                            {--size= : Specific size to generate (default: all)}
                            {--force : Regenerate even if exists}';

    protected $description = 'Generate thumbnails for an image';

    public function handle(ThumbnailService $thumbnailService): int
    {
        $path = $this->argument('path');
        $specificSize = $this->option('size');
        $force = $this->option('force');

        $this->info("Generating thumbnails for: {$path}");

        $sizes = $specificSize 
            ? [$specificSize] 
            : array_keys(config('thumbnails.sizes', []));

        $generated = 0;
        $errors = 0;

        foreach ($sizes as $size) {
            try {
                if ($force) {
                    $thumbnailService->deleteThumbnails($path);
                }

                $thumbnailUrl = $thumbnailService->thumbnail($path, $size, true);

                if ($thumbnailUrl) {
                    $this->line("  ✓ Generated: {$size}");
                    $generated++;
                } else {
                    $this->error("  ✗ Failed: {$size}");
                    $errors++;
                }
            } catch (\Exception $e) {
                $this->error("  ✗ Error ({$size}): " . $e->getMessage());
                $errors++;
            }
        }

        $this->newLine();
        $this->info("Generated: {$generated} | Errors: {$errors}");

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}


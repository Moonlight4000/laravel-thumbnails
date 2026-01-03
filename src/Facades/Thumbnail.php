<?php

namespace Moonlight\Thumbnails\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Thumbnail Facade
 * 
 * @method static string|null thumbnail(string $imagePath, string $size = 'small', bool $returnUrl = true)
 * @method static void deleteThumbnails(string $imagePath)
 * @method static int clearAllThumbnails(?string $directory = null)
 * 
 * @see \Moonlight\Thumbnails\Services\ThumbnailService
 * @package Moonlight\Thumbnails
 */
class Thumbnail extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'thumbnail';
    }
}


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


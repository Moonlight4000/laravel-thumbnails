<?php

/**
 * Laravel On-Demand Thumbnails - Global Helper Functions
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

if (!function_exists('thumbnail')) {
    /**
     * Generate thumbnail URL on-demand
     * 
     * @param string $imagePath Relative path to image
     * @param string $size Size name (small, medium, large, etc.)
     * @param bool $returnUrl Return URL instead of path
     * @return string|null
     */
    function thumbnail(string $imagePath, string $size = 'small', bool $returnUrl = true): ?string
    {
        return app('Moonlight\Thumbnails\Services\ThumbnailService')
            ->thumbnail($imagePath, $size, $returnUrl);
    }
}

if (!function_exists('thumbnail_url')) {
    /**
     * Get thumbnail URL (alias for thumbnail())
     */
    function thumbnail_url(string $imagePath, string $size = 'small'): ?string
    {
        return thumbnail($imagePath, $size, true);
    }
}

if (!function_exists('thumbnail_path')) {
    /**
     * Get thumbnail path (relative)
     */
    function thumbnail_path(string $imagePath, string $size = 'small'): ?string
    {
        return thumbnail($imagePath, $size, false);
    }
}


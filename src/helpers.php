<?php

/**
 * Global helper functions for Laravel Thumbnails
 * 
 * @package Moonlight\Thumbnails
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


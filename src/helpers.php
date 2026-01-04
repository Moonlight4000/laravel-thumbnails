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
     * Generate thumbnail URL on-demand with Context-Aware Thumbnails™
     * 
     * @param string $imagePath Relative path to image
     * @param string $size Size name (small, medium, large, etc.)
     * @param bool $returnUrl Return URL instead of path
     * @param string|null $context Context name (post, gallery, avatar, etc.)
     * @param array $contextData Context data (e.g., ['user_id' => 1, 'post_id' => 12])
     * @param bool|null $signed Enable signed URLs (null = use config default)
     * @param int|null $expiresIn Expiration in seconds (null = use config default)
     * @return string|null
     */
    function thumbnail(
        string $imagePath, 
        string $size = 'small', 
        bool $returnUrl = true,
        ?string $context = null,
        array $contextData = [],
        ?bool $signed = null,
        ?int $expiresIn = null
    ): ?string
    {
        return app('Moonlight\Thumbnails\Services\ThumbnailService')
            ->thumbnail($imagePath, $size, $returnUrl, $context, $contextData, $signed, $expiresIn);
    }
}

if (!function_exists('thumbnail_url')) {
    /**
     * Get thumbnail URL (alias for thumbnail())
     */
    function thumbnail_url(
        string $imagePath, 
        string $size = 'small',
        ?string $context = null,
        array $contextData = []
    ): ?string
    {
        return thumbnail($imagePath, $size, true, $context, $contextData);
    }
}

if (!function_exists('thumbnail_path')) {
    /**
     * Get thumbnail path (relative)
     */
    function thumbnail_path(
        string $imagePath, 
        string $size = 'small',
        ?string $context = null,
        array $contextData = []
    ): ?string
    {
        return thumbnail($imagePath, $size, false, $context, $contextData);
    }
}

if (!function_exists('original')) {
    /**
     * Get original (full-size) image URL with optional signed URL protection
     * 
     * @param string $imagePath Relative path to original image
     * @param bool|null $signed Enable signed URLs (null = use config 'sign_originals')
     * @param int|null $expiresIn Expiration in seconds (null = use config default)
     * @return string
     */
    function original(
        string $imagePath,
        ?bool $signed = null,
        ?int $expiresIn = null
    ): string
    {
        $disk = config('thumbnails.disk', 'public');
        
        // Determine if we should sign this URL
        $shouldSign = $signed ?? config('thumbnails.signed_urls.sign_originals', false);
        
        if ($shouldSign) {
            /** @var \Moonlight\Thumbnails\Services\SignedUrlService $signedUrlService */
            $signedUrlService = app('Moonlight\Thumbnails\Services\SignedUrlService');
            return $signedUrlService->generateSignedUrl($imagePath, $expiresIn);
        }
        
        return asset("storage/{$imagePath}");
    }
}


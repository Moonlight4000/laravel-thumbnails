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
     * Get original (full-size) image URL with optional signed URL protection and context-aware paths
     * 
     * @param string $imagePath Relative path to original image
     * @param bool|null $signed Enable signed URLs (null = use config 'sign_originals')
     * @param string|null $context Context name (e.g., 'appearance', 'gallery', 'avatar')
     * @param array $contextData Context data (e.g., ['user_id' => 1, 'asset_id' => 5])
     * @param int|null $expiresIn Expiration in seconds (null = use config default)
     * @return string
     */
    function original(
        string $imagePath,
        ?bool $signed = null,
        ?string $context = null,
        array $contextData = [],
        ?int $expiresIn = null
    ): string
    {
        // Determine if we should sign this URL
        $shouldSign = $signed ?? config('thumbnails.signed_urls.sign_originals', false);
        
        if ($shouldSign && $context) {
            // Use context-aware signed URL (same as thumbnail() system)
            $expiresIn = (int) ($expiresIn ?? config('thumbnails.signed_urls.expiration', 604800));
            $expiration = now()->addSeconds($expiresIn);
            
            // Build context-aware path using context template
            $contextTemplate = config("thumbnails.contexts.{$context}");
            
            if ($contextTemplate) {
                // Replace placeholders in context template
                $contextPath = $contextTemplate;
                foreach ($contextData as $key => $value) {
                    $contextPath = str_replace("{{$key}}", $value, $contextPath);
                }
                
                // Extract filename from imagePath
                $filename = basename($imagePath);
                
                // Build full path: context_path/filename
                $fullPath = trim($contextPath, '/') . '/' . $filename;
                
                return \Illuminate\Support\Facades\URL::temporarySignedRoute(
                    'storage.serve',
                    $expiration,
                    ['path' => $fullPath]
                );
            }
            
            // Fallback: use plain path if context not found
            return \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'storage.serve',
                $expiration,
                ['path' => $imagePath]
            );
        }
        
        return asset("storage/{$imagePath}");
    }
}


<?php

/**
 * Laravel On-Demand Thumbnails - Configuration
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

return [

    /*
    |--------------------------------------------------------------------------
    | Thumbnail Sizes
    |--------------------------------------------------------------------------
    |
    | Define your thumbnail sizes here. You can add as many custom sizes
    | as you need. Each size requires 'width' and 'height' in pixels.
    |
    */

    'sizes' => [
        'small' => ['width' => 150, 'height' => 150],
        'medium' => ['width' => 300, 'height' => 300],
        'large' => ['width' => 600, 'height' => 600],
        
        // Add your custom sizes:
        // 'avatar' => ['width' => 200, 'height' => 200],
        // 'banner' => ['width' => 1200, 'height' => 400],
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Disk
    |--------------------------------------------------------------------------
    |
    | The disk where thumbnails will be stored. Usually 'public'.
    | Make sure you have run: php artisan storage:link
    |
    */

    'disk' => env('THUMBNAILS_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Cache Folder Name
    |--------------------------------------------------------------------------
    |
    | Name of the folder where thumbnails will be cached.
    | They will be stored in: {original_path}/thumbnails/
    |
    */

    'cache_folder' => 'thumbnails',

    /*
    |--------------------------------------------------------------------------
    | Image Quality
    |--------------------------------------------------------------------------
    |
    | Quality for JPEG images (1-100). Higher = better quality, larger file.
    | PNG compression level is automatically adjusted.
    |
    */

    'quality' => env('THUMBNAILS_QUALITY', 85),

    /*
    |--------------------------------------------------------------------------
    | Resize Method
    |--------------------------------------------------------------------------
    |
    | How to resize images. Options:
    | - 'resize' = Proportional resize (preserves aspect ratio, may not be exact size)
    | - 'crop' = Center crop to exact size (fills entire thumbnail, may cut edges)
    | - 'fit' = Fit inside bounds (preserves entire image, adds padding if needed)
    |
    */

    'method' => env('THUMBNAILS_METHOD', 'resize'),

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Driver for image processing. Options:
    | - 'gd' (default, built-in)
    | - 'imagick' (requires ext-imagick)
    | - 'intervention' (requires intervention/image package)
    |
    */

    'driver' => env('THUMBNAILS_DRIVER', 'gd'),

    /*
    |--------------------------------------------------------------------------
    | Fallback on Error
    |--------------------------------------------------------------------------
    |
    | If thumbnail generation fails, return the original image URL instead
    | of throwing an exception. Recommended: true for production.
    |
    */

    'fallback_on_error' => env('THUMBNAILS_FALLBACK', true),

    /*
    |--------------------------------------------------------------------------
    | Generate on Upload
    |--------------------------------------------------------------------------
    |
    | Pre-generate all thumbnail sizes when image is uploaded.
    | false = lazy generation (on-demand, recommended)
    | true = eager generation (all sizes immediately)
    |
    */

    'generate_on_upload' => false,

    /*
    |--------------------------------------------------------------------------
    | Cache Control Headers
    |--------------------------------------------------------------------------
    |
    | HTTP Cache-Control header for generated thumbnails.
    | Aggressive caching recommended (thumbnails rarely change).
    |
    */

    'cache_control' => 'public, max-age=31536000', // 1 year

    /*
    |--------------------------------------------------------------------------
    | Allowed Extensions
    |--------------------------------------------------------------------------
    |
    | File extensions that can be processed for thumbnails.
    |
    */

    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],

    /*
    |--------------------------------------------------------------------------
    | Max File Size (MB)
    |--------------------------------------------------------------------------
    |
    | Maximum file size (in MB) for thumbnail generation.
    | Files larger than this will be skipped (and fallback used if enabled).
    |
    */

    'max_file_size' => 10, // MB

    /*
    |--------------------------------------------------------------------------
    | Filename Pattern
    |--------------------------------------------------------------------------
    |
    | Pattern for thumbnail filenames. Available placeholders:
    | {name} = original filename without extension
    | {size} = size name (e.g., 'small', 'medium')
    | {ext} = file extension
    |
    */

    'filename_pattern' => '{name}_thumb_{size}.{ext}',

    /*
    |--------------------------------------------------------------------------
    | Enable Middleware
    |--------------------------------------------------------------------------
    |
    | Enable automatic on-demand generation via middleware.
    | This is the core feature - thumbnails generate when requested (404/403).
    |
    */

    'enable_middleware' => true,

    /*
    |--------------------------------------------------------------------------
    | Context-Aware Paths™ (UNIQUE FEATURE!)
    |--------------------------------------------------------------------------
    |
    | Define custom directory structures for different content types.
    | Thumbnails will be organized exactly where your content lives!
    |
    | Use placeholders: {user_id}, {post_id}, {album_id}, {id}, {type}, etc.
    | Thumbnails are stored in: {context_path}/thumbnails/
    |
    | Example structures:
    | - 'post' => user-posts/{user_id}/{post_id}/thumbnails/
    | - 'gallery' => galleries/{user_id}/{album_id}/thumbnails/
    | - 'avatar' => avatars/{user_id}/thumbnails/
    |
    | NO OTHER LARAVEL THUMBNAIL PACKAGE HAS THIS!
    | Perfect for: organization, per-user isolation, easy cleanup, CDN routing
    |
    */

    'contexts' => [
        // Default - all thumbnails in main folder
        'default' => '',
        
        // User posts - separate per user and post
        // Example: user-posts/1/12/thumbnails/img_thumb_small.jpg
        'post' => 'user-posts/{user_id}/{post_id}',
        
        // Gallery photos - separate per user and album
        // Example: galleries/5/3/thumbnails/photo_thumb_medium.jpg
        'gallery' => 'galleries/{user_id}/{album_id}',
        
        // User avatars - per user only
        // Example: avatars/8/thumbnails/avatar_thumb_small.jpg
        'avatar' => 'avatars/{user_id}',
        
        // Fanpage content - per fanpage and type
        // Example: fanpages/42/photos/thumbnails/banner_thumb_large.jpg
        'fanpage' => 'fanpages/{fanpage_id}/{type}',
        
        // Generic with single ID
        // Example: products/15/thumbnails/product_thumb_medium.jpg
        'generic' => '{type}/{id}',
        
        // Add your own custom contexts here!
        // 'my_context' => 'my-folder/{custom_id}/{sub_id}',
    ],

];


<?php

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

];


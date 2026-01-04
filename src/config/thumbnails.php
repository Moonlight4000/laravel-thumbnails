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
    | License Configuration
    |--------------------------------------------------------------------------
    |
    | Your license key from Moonlight. Get yours at:
    | https://howtodraw.pl/developer/packages
    |
    | FREE for personal/non-commercial use (no key needed for local dev)
    | PAID for commercial use ($500-$15,000/year depending on company size)
    |
    */

    'license_key' => env('THUMBNAILS_LICENSE_KEY'),
    'license_api_url' => env('THUMBNAILS_API_URL', 'https://howtodraw.pl/api/v1/licenses'),

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
    | Subdirectory Strategies
    |--------------------------------------------------------------------------
    |
    | Choose how thumbnails are organized in subdirectories:
    | - auto_strategy: true = automatically selects best strategy
    |   * With model context → Context-Aware (semantic organization)
    |   * Without context (string path) → Hash Prefix (performance)
    | 
    | - auto_strategy: false = use manual_strategy
    |
    | Strategies available:
    | - 'context-aware' - Organize by user/post/album (our unique feature!)
    | - 'hash-prefix' - a/b/ subdirs for performance (1M+ files)
    | - 'date-based' - 2026/01/03/ subdirs by date
    | - 'hash-levels' - ab/cd/ef/ multi-level hash
    |
    */

    'subdirectory' => [
        // Auto-detect: Context-Aware when model, Hash when string path
        'auto_strategy' => env('THUMBNAILS_AUTO_STRATEGY', true),
        
        // Manual override (when auto_strategy = false)
        'manual_strategy' => env('THUMBNAILS_STRATEGY', 'context-aware'),
        
        'strategies' => [
            'context-aware' => [
                'enabled' => true,
                'priority' => 100, // Highest priority when model provided
            ],
            
            'hash-prefix' => [
                'enabled' => true,
                'length' => 2,  // First 2 chars of hash
                'depth' => 2,   // Two levels (a/b/)
                'priority' => 1, // Lowest = fallback when no context
                'base_dir' => 'thumbnails',
            ],
            
            'date-based' => [
                'enabled' => false,
                'format' => 'Y/m/d', // 2026/01/03
                'priority' => 50,
            ],
            
            'hash-levels' => [
                'enabled' => false,
                'levels' => 3,  // ab/cd/ef/
                'chars_per_level' => 2,
                'priority' => 25,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resize Method
    |--------------------------------------------------------------------------
    |
    | How to resize images. Options:
    | - 'resize' = Proportional resize (preserves aspect ratio, may not be exact size)
    | - 'crop' = Center crop to exact size (fills entire thumbnail, may cut edges)
    | - 'fit' = Fit inside bounds (preserves entire image, adds padding if needed)
    | - 'smart-crop' = Intelligent cropping based on content (requires smart_crop enabled)
    |
    */

    'method' => env('THUMBNAILS_METHOD', 'resize'),
    
    /*
    |--------------------------------------------------------------------------
    | Smart Crop Algorithm
    |--------------------------------------------------------------------------
    |
    | Intelligent cropping that automatically finds the most important part
    | of an image using energy detection (edge detection) or face detection.
    |
    | - enabled: Enable smart crop feature
    | - algorithm: 'energy' = edge detection (default), 'faces' = face detection (Imagick)
    | - rule_of_thirds: Align focal point with rule of thirds grid
    |
    | Use method='smart-crop' to enable for specific thumbnails.
    |
    */

    'smart_crop' => [
        'enabled' => env('THUMBNAILS_SMART_CROP', true),
        'algorithm' => env('THUMBNAILS_SMART_CROP_ALGORITHM', 'energy'),
        'rule_of_thirds' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Modern Image Formats (AVIF/WebP)
    |--------------------------------------------------------------------------
    |
    | Automatically convert thumbnails to modern formats for better performance.
    | AVIF: ~50% smaller than JPEG, WebP: ~30% smaller
    |
    | - auto_convert: Automatically use best available format
    | - priority: Try formats in order (first available wins)
    | - quality: Quality settings per format (1-100)
    | - fallback: Format to use if modern formats unavailable
    |
    */

    'formats' => [
        'auto_convert' => env('THUMBNAILS_AUTO_CONVERT', true),
        'priority' => ['avif', 'webp', 'jpg'], // Try in order
        'quality' => [
            'avif' => 85,
            'webp' => 90,
            'jpg' => 85,
            'png' => 90,
        ],
        'fallback' => 'jpg',
    ],

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
    | Variants System
    |--------------------------------------------------------------------------
    |
    | Define preset collections of thumbnail sizes (variants).
    | Generate multiple sizes at once for common use cases.
    |
    | Example: thumbnail_variant($post, 'image.jpg', 'avatar')
    | Generates all 3 avatar sizes in one call.
    |
    */

    'variants' => [
        'avatar' => [
            ['width' => 32, 'height' => 32, 'method' => 'crop'],
            ['width' => 64, 'height' => 64, 'method' => 'crop'],
            ['width' => 128, 'height' => 128, 'method' => 'crop'],
        ],
        'gallery' => [
            ['width' => 300, 'height' => 200, 'method' => 'crop'],
            ['width' => 800, 'height' => 600, 'method' => 'resize'],
            ['width' => 1920, 'height' => 1080, 'method' => 'fit'],
        ],
        'post_images' => [
            ['width' => 600, 'height' => 400, 'method' => 'fit'],
            ['width' => 1200, 'height' => 800, 'method' => 'fit'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Handling Mode
    |--------------------------------------------------------------------------
    |
    | How to handle thumbnail generation errors:
    | - 'silent': Log error, return original image (production-friendly)
    | - 'strict': Throw exceptions (development/debugging)
    | - 'fallback': Return placeholder image
    |
    */

    'error_mode' => env('THUMBNAILS_ERROR_MODE', 'silent'),

    'error_modes' => [
        'silent' => [
            'return_original' => true,
            'log_errors' => true,
        ],
        'strict' => [
            'throw_exceptions' => true,
        ],
        'fallback' => [
            'placeholder_path' => 'placeholders/no-image.jpg',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Statistics Tracking (Optional)
    |--------------------------------------------------------------------------
    |
    | Track thumbnail generation statistics for monitoring and analytics.
    | 
    | IMPORTANT: Database tracking is DISABLED by default (zero setup!).
    | To enable: php artisan vendor:publish --tag=thumbnails-statistics
    |           php artisan migrate
    |           Set THUMBNAILS_STATISTICS_ENABLED=true
    |
    | Alternative: File-based logging (always available)
    |
    */

    'statistics' => [
        'enabled' => env('THUMBNAILS_STATISTICS_ENABLED', false),
        'log_to_file' => env('THUMBNAILS_STATS_LOG', true),
        'log_file' => 'thumbnails-stats.log',
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Validation
    |--------------------------------------------------------------------------
    |
    | Validate images before processing for security and performance.
    |
    */

    'security' => [
        'max_file_size' => env('THUMBNAILS_MAX_SIZE', 10) * 1024 * 1024, // 10MB
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'],
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/avif',
        ],
        'max_dimensions' => [
            'width' => 10000,
            'height' => 10000,
        ],
        'block_svg' => true, // Prevent XXE attacks
    ],

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
        
        // Gallery photos - separate per user, album, and collection
        // Example: galleries/5/3/7/thumbnails/photo_thumb_medium.jpg
        'gallery' => 'galleries/{user_id}/{album_id}/{collection_id}',
        
        // Legacy gallery photos (without collections)
        // Example: galleries/5/3/thumbnails/photo_thumb_medium.jpg
        'gallery_legacy' => 'galleries/{user_id}/{album_id}',
        
        // User avatars - per user only
        // Example: avatars/8/thumbnails/avatar_thumb_small.jpg
        'avatar' => 'avatars/{user_id}',
        
        // Fanpage content - per fanpage (fanarts, banners, etc.)
        // Example: fanarts/42/thumbnails/fanart_thumb_large.jpg
        'fanpage' => 'fanarts/{fanpage_id}',
        
        // Generic with single ID
        // Example: products/15/thumbnails/product_thumb_medium.jpg
        'generic' => '{type}/{id}',
        
        // Add your own custom contexts here!
        // 'my_context' => 'my-folder/{custom_id}/{sub_id}',
    ],

    /*
    |--------------------------------------------------------------------------
    | Signed URLs Protection (Facebook-style)
    |--------------------------------------------------------------------------
    |
    | Protect your images with signed URLs that expire after a set time.
    | Like Facebook - prevents hotlinking and unauthorized access.
    |
    | - enabled: Enable signed URLs for thumbnails
    | - sign_originals: Also sign original image URLs (not thumbnails)
    | - secret: Secret key for HMAC signature (use APP_KEY or generate new)
    | - expiration: URL validity in seconds (default: 7 days)
    | - algorithm: Hash algorithm for signatures (default: sha256)
    |
    | Generated URLs look like: /storage/image.jpg?oh=signature&oe=expires
    | 
    | After expiration, links return 404. Invalid signatures return 403.
    |
    | Usage:
    | - Global: Set THUMBNAILS_SIGNED_URLS=true in .env
    | - Per-call: thumbnail($path, 'large', signed: true)
    | - Per-call disable: thumbnail($path, 'large', signed: false)
    |
    */

    'signed_urls' => [
        // Enable globally for all thumbnails
        'enabled' => env('THUMBNAILS_SIGNED_URLS', false),
        
        // Also sign original (full-size) images
        'sign_originals' => env('THUMBNAILS_SIGNED_ORIGINALS', false),
        
        // Secret key for HMAC signature (NEVER commit to git!)
        // Use APP_KEY or generate: php artisan tinker -> Str::random(64)
        'secret' => env('THUMBNAILS_SIGN_SECRET', env('APP_KEY')),
        
        // URL expiration time in seconds
        // 604800 = 7 days (like Facebook)
        // 3600 = 1 hour
        // 86400 = 1 day
        'expiration' => env('THUMBNAILS_URL_EXPIRATION', 604800),
        
        // Hash algorithm (sha256 recommended)
        'algorithm' => 'sha256',
    ],

];


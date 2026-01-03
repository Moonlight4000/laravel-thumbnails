<?php

namespace Moonlight\Thumbnails\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Moonlight\Thumbnails\Services\ThumbnailService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

/**
 * ThumbnailFallback Middleware
 * 
 * Automatically generates thumbnails on-demand when requested (like Symfony's LiipImagineBundle).
 * 
 * How it works:
 * 1. User requests: /storage/photos/thumbnails/image_thumb_small.jpg
 * 2. If 404/403 (thumbnail doesn't exist), middleware catches it
 * 3. Parses URL to extract original path and size
 * 4. Generates thumbnail using ThumbnailService
 * 5. Returns generated thumbnail
 * 6. Next request → Nginx/Apache serves cached file directly
 * 
 * @package Moonlight\Thumbnails
 */
class ThumbnailFallback
{
    protected ThumbnailService $thumbnailService;
    
    public function __construct(ThumbnailService $thumbnailService)
    {
        $this->thumbnailService = $thumbnailService;
    }
    
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only intercept if middleware is enabled
        if (!Config::get('thumbnails.enable_middleware', true)) {
            return $response;
        }
        
        // Intercept 403/404 on thumbnail requests
        if (($response->status() === 404 || $response->status() === 403) && 
            str_contains($request->path(), '/thumbnails/') &&
            str_contains($request->path(), 'storage/')) {
            
            $path = $request->path();
            $cacheFolder = Config::get('thumbnails.cache_folder', 'thumbnails');
            $sizes = array_keys(Config::get('thumbnails.sizes', []));
            $sizesPattern = implode('|', $sizes);
            
            // Parse URL: storage/photos/thumbnails/image_thumb_small.jpg
            $pattern = "#storage/(.+)/{$cacheFolder}/(.+)_thumb_({$sizesPattern})\.(\w+)$#";
            
            if (preg_match($pattern, $path, $matches)) {
                $directory = $matches[1];      // photos
                $basename = $matches[2];       // image
                $size = $matches[3];           // small|medium|large
                $extension = $matches[4];      // jpg|png|webp|gif
                
                // Reconstruct original file path
                $originalPath = "{$directory}/{$basename}.{$extension}";
                
                $disk = Config::get('thumbnails.disk', 'public');
                
                // Check if original exists
                if (!Storage::disk($disk)->exists($originalPath)) {
                    Log::debug('ThumbnailFallback: Original file not found', [
                        'requested_thumbnail' => $path,
                        'original_path' => $originalPath
                    ]);
                    return $response; // Return original 404/403
                }
                
                // Generate thumbnail on-demand!
                try {
                    Log::info('ThumbnailFallback: Generating thumbnail on-demand', [
                        'original' => $originalPath,
                        'size' => $size,
                        'requested' => $path
                    ]);
                    
                    // Generate thumbnail (returns URL)
                    $thumbnailUrl = $this->thumbnailService->thumbnail($originalPath, $size, true);
                    
                    // Extract path from URL
                    $thumbnailPath = str_replace([asset('storage/'), '/storage/'], '', $thumbnailUrl);
                    
                    // Verify thumbnail was generated
                    if (!Storage::disk($disk)->exists($thumbnailPath)) {
                        Log::error('ThumbnailFallback: Generation succeeded but file not found', [
                            'thumbnail_path' => $thumbnailPath
                        ]);
                        return $response;
                    }
                    
                    // Get file contents
                    $file = Storage::disk($disk)->get($thumbnailPath);
                    
                    // Determine MIME type
                    $mimeType = match($extension) {
                        'jpg', 'jpeg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                        'webp' => 'image/webp',
                        default => 'image/jpeg'
                    };
                    
                    $cacheControl = Config::get('thumbnails.cache_control', 'public, max-age=31536000');
                    
                    // Return generated thumbnail with caching
                    return response($file)
                        ->header('Content-Type', $mimeType)
                        ->header('Cache-Control', $cacheControl)
                        ->header('X-Thumbnail-Generated', 'on-demand')
                        ->header('X-Thumbnail-Size', $size);
                        
                } catch (\Exception $e) {
                    Log::error('ThumbnailFallback: Generation failed', [
                        'path' => $originalPath,
                        'size' => $size,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return $response; // Return original 404/403
                }
            }
        }
        
        return $response;
    }
}


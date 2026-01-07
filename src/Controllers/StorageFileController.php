<?php

/**
 * Laravel On-Demand Thumbnails - Storage File Controller
 * 
 * @package    moonlight-poland/laravel-thumbnails
 * @author     Moonlight Poland Team <kontakt@howtodraw.pl>
 * @copyright  2024-2026 Moonlight Poland. All rights reserved.
 * @license    Commercial License - See LICENSE.md
 * @link       https://github.com/Moonlight4000/laravel-thumbnails
 */

namespace Moonlight\Thumbnails\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * StorageFileController
 * 
 * Serves storage files with Laravel's native signed URL validation.
 * Uses manual hash_hmac validation as Request::hasValidSignature() 
 * can be unreliable with wildcard paths.
 * 
 * Flow:
 * 1. Browser requests: /storage/path/to/file.jpg?expires=...&signature=...
 * 2. This controller validates signature using hash_hmac + hash_equals
 * 3. If valid and not expired, serves file with cache headers
 * 4. If invalid/expired, returns 403
 * 
 * @package Moonlight\Thumbnails\Controllers
 */
class StorageFileController
{
    /**
     * Serve file from storage with signed URL validation
     * 
     * Validates signed URLs using Laravel's native signing mechanism:
     * - signature = hash_hmac('sha256', url_with_expires, app_key)
     * - Uses hash_equals for timing-safe comparison
     * 
     * Audio files (.mp3, .wav, .ogg, .m4a, .flac) are delegated to AudioStreamController
     * if it exists in the user's application (for HTTP Range Request support).
     * 
     * @param Request $request
     * @param string $path Relative path in storage (e.g., "galleries/1/5/8/photo.jpg")
     * @return BinaryFileResponse
     */
    public function serve(Request $request, string $path): BinaryFileResponse
    {
        // Decode path (handle URL encoding)
        $path = urldecode($path);
        
        // Verify signed URL if enabled
        if (Config::get('thumbnails.signed_urls.enabled')) {
            $expires = $request->query('expires');
            $signature = $request->query('signature');
            
            // Check presence of required parameters
            if (!$expires || !$signature) {
                abort(403, 'Missing signature parameters');
            }
            
            // Build URL for signature validation (same as helpers.php and ThumbnailService.php)
            $url = url("/storage/{$path}");
            $urlWithExpires = $url . '?expires=' . $expires;
            
            // Calculate expected signature using Laravel's method
            $appKey = config('app.key');
            $expectedSignature = hash_hmac('sha256', $urlWithExpires, $appKey);
            
            // Validate signature (timing-safe comparison)
            if (!hash_equals($expectedSignature, $signature)) {
                abort(403, 'Invalid signature');
            }
            
            // Validate expiration
            if (time() > (int) $expires) {
                abort(403, 'Signature expired');
            }
        }
        
        // Check if audio file - delegate to AudioStreamController if it exists
        // This allows projects to provide custom audio streaming with HTTP Range Request support
        if (preg_match('/\.(mp3|wav|ogg|m4a|flac)$/i', $path)) {
            $audioControllerClass = 'App\\Http\\Controllers\\AudioStreamController';
            if (class_exists($audioControllerClass)) {
                return app($audioControllerClass)->stream($request, $path);
            }
        }
        
        // Get file from storage
        $disk = Config::get('thumbnails.disk', 'public');
        $fullPath = Storage::disk($disk)->path($path);
        
        // Check if file exists
        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }
        
        // Determine cache headers
        $cacheControl = Config::get('thumbnails.signed_urls.enabled')
            ? 'public, max-age=' . Config::get('thumbnails.signed_urls.expiration', 604800)
            : 'public, max-age=31536000';
        
        $headers = [
            'Cache-Control' => $cacheControl,
            'Content-Type' => mime_content_type($fullPath) ?: 'application/octet-stream',
        ];
        
        // Add Expires header for signed URLs (browser cache until expiration)
        if (Config::get('thumbnails.signed_urls.enabled')) {
            $expiresTime = time() + Config::get('thumbnails.signed_urls.expiration', 604800);
            $headers['Expires'] = gmdate('D, d M Y H:i:s', $expiresTime) . ' GMT';
        }
        
        // Return file with headers
        return response()->file($fullPath, $headers);
    }
}

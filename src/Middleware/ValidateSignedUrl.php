<?php

/**
 * Laravel On-Demand Thumbnails - Signed URL Validation Middleware
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

namespace Moonlight\Thumbnails\Middleware;

use Closure;
use Illuminate\Http\Request;
use Moonlight\Thumbnails\Services\SignedUrlService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

/**
 * ValidateSignedUrl Middleware
 * 
 * Validates signed URLs for thumbnails and original images.
 * Protects against hotlinking and unauthorized access.
 * 
 * @package Moonlight\Thumbnails\Middleware
 */
class ValidateSignedUrl
{
    /**
     * @var SignedUrlService
     */
    protected $signedUrlService;

    /**
     * Constructor
     */
    public function __construct(SignedUrlService $signedUrlService)
    {
        $this->signedUrlService = $signedUrlService;
    }

    /**
     * Handle an incoming request
     * 
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip if signed URLs are disabled globally
        if (!Config::get('thumbnails.signed_urls.enabled')) {
            return $next($request);
        }

        // Only validate requests to storage paths
        $path = $request->path();
        if (!str_starts_with($path, 'storage/')) {
            return $next($request);
        }

        // Extract clean path (remove 'storage/' prefix)
        $storagePath = ltrim(str_replace('storage/', '', $path), '/');

        // Get signature parameters
        $signature = $request->query('oh');
        $expiresHex = $request->query('oe');

        // Validate presence of required parameters
        if (!$signature || !$expiresHex) {
            Log::warning('Thumbnail: Missing signature parameters', [
                'path' => $storagePath,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return response('Missing signature parameters', 403)
                ->header('X-Thumbnail-Error', 'missing-signature')
                ->header('Content-Type', 'text/plain');
        }

        // Validate signature and expiration
        if (!$this->signedUrlService->validateSignedUrl($storagePath, $signature, $expiresHex)) {
            $expiresAt = hexdec($expiresHex);
            $isExpired = time() > $expiresAt;
            
            Log::warning('Thumbnail: Signature validation failed', [
                'path' => $storagePath,
                'ip' => $request->ip(),
                'expired' => $isExpired,
                'expires_at' => date('Y-m-d H:i:s', $expiresAt),
                'user_agent' => $request->userAgent(),
            ]);
            
            if ($isExpired) {
                return response('Link expired', 404)
                    ->header('X-Thumbnail-Error', 'expired')
                    ->header('Content-Type', 'text/plain');
            }
            
            return response('Invalid signature', 403)
                ->header('X-Thumbnail-Error', 'invalid-signature')
                ->header('Content-Type', 'text/plain');
        }

        // Signature valid - proceed
        return $next($request);
    }
}


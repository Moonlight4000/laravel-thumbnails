<?php

/**
 * Laravel On-Demand Thumbnails - Signed URLs Service
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

namespace Moonlight\Thumbnails\Services;

use Illuminate\Support\Facades\Config;

/**
 * SignedUrlService
 * 
 * Generates and validates signed URLs with expiration (Facebook-style).
 * Protects against hotlinking and unauthorized access.
 * 
 * @package Moonlight\Thumbnails\Services
 */
class SignedUrlService
{
    /**
     * Generate signed URL with expiration (Facebook-style)
     * 
     * @param string $path Relative storage path (e.g., "user-posts/1/12/image.jpg")
     * @param int|null $expiresIn Seconds until expiration (null = use config default)
     * @return string Signed URL with 'oh' (signature) and 'oe' (expiration hex)
     */
    public function generateSignedUrl(string $path, ?int $expiresIn = null): string
    {
        if (!Config::get('thumbnails.signed_urls.enabled')) {
            return asset("storage/{$path}");
        }

        $expiresIn = $expiresIn ?? Config::get('thumbnails.signed_urls.expiration', 604800);
        $expiresAt = time() + $expiresIn;
        
        // Build base URL
        $baseUrl = asset("storage/{$path}");
        
        // Generate signature
        $signature = $this->generateSignature($path, $expiresAt);
        
        // Append query parameters (Facebook-style)
        $separator = parse_url($baseUrl, PHP_URL_QUERY) ? '&' : '?';
        
        return $baseUrl . $separator . http_build_query([
            'oh' => $signature,           // Origin Hash (signature)
            'oe' => dechex($expiresAt),  // Origin Expires (hex timestamp)
            '_t' => substr(md5($path), 0, 8), // Token (cache buster)
        ]);
    }

    /**
     * Validate signed URL
     * 
     * @param string $path Relative storage path
     * @param string $signature Signature from 'oh' parameter
     * @param string $expiresHex Expiration hex from 'oe' parameter
     * @return bool True if valid and not expired
     */
    public function validateSignedUrl(string $path, string $signature, string $expiresHex): bool
    {
        // Check expiration
        $expiresAt = hexdec($expiresHex);
        if (time() > $expiresAt) {
            return false; // EXPIRED
        }

        // Verify signature
        $expectedSignature = $this->generateSignature($path, $expiresAt);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Generate HMAC signature
     * 
     * @param string $path File path
     * @param int $expiresAt Unix timestamp
     * @return string HMAC signature
     */
    protected function generateSignature(string $path, int $expiresAt): string
    {
        $secret = Config::get('thumbnails.signed_urls.secret', Config::get('app.key'));
        $algorithm = Config::get('thumbnails.signed_urls.algorithm', 'sha256');
        
        // Combine data for signature
        $data = implode('|', [
            $path,
            $expiresAt,
            request()->ip(), // Bind to IP for extra security
        ]);
        
        return hash_hmac($algorithm, $data, $secret);
    }

    /**
     * Parse signed URL parameters
     * 
     * @param string $url Full URL
     * @return array|null Array with signature, expires_hex, token or null
     */
    public function parseSignedUrl(string $url): ?array
    {
        $query = parse_url($url, PHP_URL_QUERY);
        if (!$query) {
            return null;
        }

        parse_str($query, $params);
        
        return [
            'signature' => $params['oh'] ?? null,
            'expires_hex' => $params['oe'] ?? null,
            'token' => $params['_t'] ?? null,
        ];
    }

    /**
     * Check if signed URLs are enabled globally
     * 
     * @return bool
     */
    public function isEnabled(): bool
    {
        return Config::get('thumbnails.signed_urls.enabled', false);
    }

    /**
     * Check if original images signing is enabled
     * 
     * @return bool
     */
    public function isOriginalsSigningEnabled(): bool
    {
        return Config::get('thumbnails.signed_urls.sign_originals', false);
    }
}


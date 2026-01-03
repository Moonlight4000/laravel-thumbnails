<?php

/**
 * Laravel On-Demand Thumbnails
 * 
 * @package    moonlight-poland/laravel-thumbnails
 * @author     Moonlight Poland Team <kontakt@howtodraw.pl>
 * @copyright  2024-2026 Moonlight Poland. All rights reserved.
 * @license    Commercial License (see LICENSE.md)
 * @link       https://github.com/Moonlight4000/laravel-thumbnails
 * @version    1.0.0
 * 
 * This file is part of the Laravel On-Demand Thumbnails package.
 * 
 * COMMERCIAL LICENSE - This software is protected by copyright.
 * FREE for personal/non-commercial use.
 * PAID license required for commercial use ($500-$15,000/year).
 * See LICENSE.md for full terms: https://github.com/Moonlight4000/laravel-thumbnails/blob/main/LICENSE.md
 * 
 * Unauthorized copying, modification, distribution, or commercial use
 * without a valid license is strictly prohibited and constitutes
 * copyright infringement.
 */

namespace Moonlight\Thumbnails\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Moonlight\Thumbnails\Services\Strategies\StrategyInterface;
use Moonlight\Thumbnails\Services\Strategies\ContextAwareStrategy;
use Moonlight\Thumbnails\Services\Strategies\HashPrefixStrategy;
use Moonlight\Thumbnails\Services\Strategies\DateBasedStrategy;
use Moonlight\Thumbnails\Services\Strategies\HashLevelsStrategy;
use Moonlight\Thumbnails\Services\SmartCropService;
use Moonlight\Thumbnails\Services\DailyStatsService;

/**
 * ThumbnailService
 * 
 * Core service for generating and managing image thumbnails.
 * Inspired by Symfony's LiipImagineBundle.
 * 
 * @package Moonlight\Thumbnails
 * @author Moonlight Poland Team
 * @copyright 2024-2026 Moonlight Poland
 */
class ThumbnailService
{
    protected static $licenseChecked = false;
    protected static $licenseValid = false;
    protected static $missingLibrariesWarned = false;
    
    /** @var StrategyInterface[] */
    protected array $strategies = [];
    
    public function __construct()
    {
        $this->loadStrategies();
    }

    /**
     * Check if required image processing libraries are available
     * Logs critical issues to dedicated log file (once per session)
     */
    protected function checkImageLibraries(): array
    {
        $issues = [];
        
        // Check GD (CRITICAL - required for basic functionality)
        if (!extension_loaded('gd')) {
            $issues[] = 'GD extension not loaded';
        }
        
        // Check Imagick (optional but recommended)
        if (!extension_loaded('imagick')) {
            $issues[] = 'Imagick extension not loaded (optional)';
        }
        
        // Check Intervention Image (optional for advanced features)
        if (!class_exists('Intervention\Image\Facades\Image')) {
            $issues[] = 'Intervention Image not installed (optional)';
        }
        
        // Log ONLY critical issues to dedicated file (once per session)
        if (!empty($issues) && !static::$missingLibrariesWarned) {
            static::$missingLibrariesWarned = true;
            
            // Only log if GD is missing (critical) or all are missing
            $criticalIssue = !extension_loaded('gd') || count($issues) >= 3;
            
            if ($criticalIssue) {
                try {
                    $logger = Log::build([
                        'driver' => 'single',
                        'path' => storage_path('logs/thumbnails.log'),
                    ]);
                    
                    $logger->warning('Missing image processing libraries', [
                        'issues' => $issues,
                        'current_driver' => Config::get('thumbnails.driver', 'gd'),
                    ]);
                } catch (\Exception $e) {
                    // Silent fail - logging is nice-to-have
                }
            }
        }
        
        return $issues;
    }
    
    /**
     * Load and initialize subdirectory strategies
     */
    protected function loadStrategies(): void
    {
        $config = Config::get('thumbnails.subdirectory.strategies', []);
        
        foreach ($config as $name => $settings) {
            if (!($settings['enabled'] ?? true)) {
                continue;
            }
            
            $strategy = match($name) {
                'context-aware' => new ContextAwareStrategy($settings),
                'hash-prefix' => new HashPrefixStrategy($settings),
                'date-based' => new DateBasedStrategy($settings),
                'hash-levels' => new HashLevelsStrategy($settings),
                default => null,
            };
            
            if ($strategy) {
                $this->strategies[] = $strategy;
            }
        }
        
        // Sort by priority (highest first)
        usort($this->strategies, fn($a, $b) => $b->priority() <=> $a->priority());
    }
    
    /**
     * Resolve the best strategy for given context
     * 
     * @param mixed $context Model instance or null
     * @param string $path Original image path
     * @return StrategyInterface
     */
    protected function resolveStrategy(mixed $context, string $path): StrategyInterface
    {
        // Check if auto-strategy is enabled
        if (!Config::get('thumbnails.subdirectory.auto_strategy', true)) {
            $manualStrategy = Config::get('thumbnails.subdirectory.manual_strategy', 'context-aware');
            foreach ($this->strategies as $strategy) {
                if ($strategy->getName() === $manualStrategy) {
                    return $strategy;
                }
            }
        }
        
        // Auto-detect: first strategy that supports this context
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($context, $path)) {
                return $strategy;
            }
        }
        
        // Fallback: return first strategy (should be hash-prefix)
        return $this->strategies[0] ?? new HashPrefixStrategy([]);
    }

    /**
     * Get or generate thumbnail on-demand (filesystem cache)
     * 
     * This is the core method of Laravel On-Demand Thumbnails package.
     * Copyright © 2024-2026 Moonlight Poland <kontakt@howtodraw.pl>
     * 
     * @param string $imagePath Relative path (e.g., "photos/image.jpg")
     * @param string $size Size name from config (e.g., "small", "medium")
     * @param bool $returnUrl Return full URL instead of path
     * @param string|null $context Context name from config (e.g., "post", "gallery")
     * @param array $contextData Data for context placeholders (e.g., ['user_id' => 1, 'post_id' => 12])
     * @return string|null Thumbnail URL/path or null on error
     */
    public function thumbnail(
        string $imagePath, 
        string $size = 'small', 
        bool $returnUrl = true,
        ?string $context = null,
        array $contextData = [],
        bool $generateOnDemand = false
    ): ?string
    {
        // CHECK IMAGE LIBRARIES (non-intrusive, logs critical issues only)
        $this->checkImageLibraries();
        
        // LICENSE VERIFICATION (runs once per request)
        if (!static::$licenseChecked) {
            static::$licenseValid = $this->checkLicense();
            static::$licenseChecked = true;
        }

        if (!static::$licenseValid) {
            // Silent fail - return null
            return null;
        }

        $disk = Config::get('thumbnails.disk', 'public');
        
        // 🔥 CONTEXT-AWARE PATH RESOLUTION (UNIQUE FEATURE!)
        // © 2024-2026 Moonlight Poland
        $originalPath = $imagePath;
        if ($context) {
            $contextPath = $this->resolveContextPath($context, $contextData);
            $imagePath = $contextPath ? "{$contextPath}/" . basename($imagePath) : basename($imagePath);
        }
        
        // Check if original exists
        if (!Storage::disk($disk)->exists($imagePath)) {
            if (Config::get('thumbnails.fallback_on_error', true)) {
                return $returnUrl ? asset("storage/{$imagePath}") : null;
            }
            return null;
        }
        
        // Security validation
        try {
            $this->validateImage(Storage::disk($disk)->path($imagePath));
        } catch (\Exception $e) {
            $this->getLogger()->warning('Image validation failed', [
                'path' => $imagePath,
                'error' => $e->getMessage()
            ]);
            
            if (Config::get('thumbnails.error_mode') === 'strict') {
                throw $e;
            }
            
            // Silent/fallback mode
            return Config::get('thumbnails.fallback_on_error', true) 
                ? ($returnUrl ? asset("storage/{$imagePath}") : $imagePath)
                : null;
        }
        
        // Parse path
        $pathInfo = pathinfo($imagePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['basename'];
        $baseName = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        
        // Build thumbnail path
        $cacheFolder = Config::get('thumbnails.cache_folder', 'thumbnails');
        $filenamePattern = Config::get('thumbnails.filename_pattern', '{name}_thumb_{size}.{ext}');
        
        $thumbnailFilename = str_replace(
            ['{name}', '{size}', '{ext}'],
            [$baseName, $size, $extension],
            $filenamePattern
        );
        
        $thumbnailPath = "{$directory}/{$cacheFolder}/{$thumbnailFilename}";
        
        // 🔥 CACHE CHECK - if thumbnail exists, return it (CACHE HIT)
        // Laravel On-Demand Thumbnails © Moonlight Poland
        if (Storage::disk($disk)->exists($thumbnailPath)) {
            return $returnUrl ? asset("storage/{$thumbnailPath}") : $thumbnailPath;
        }
        
        // 🔥 GENERATE ON DEMAND FLAG
        // If false, return URL only (middleware will generate on 404)
        if (!$generateOnDemand) {
            return $returnUrl ? asset("storage/{$thumbnailPath}") : $thumbnailPath;
        }
        
        // 🔨 CACHE MISS - generate thumbnail NOW (only if $generateOnDemand = true)
        // Proprietary algorithm © 2024-2026 Moonlight Poland
        try {
            $dimensions = $this->getSize($size);
            $sourcePath = Storage::disk($disk)->path($imagePath);
            
            $this->generateThumbnail(
                $sourcePath,
                $thumbnailPath,
                $dimensions['width'],
                $dimensions['height']
            );
            
            return $returnUrl ? asset("storage/{$thumbnailPath}") : $thumbnailPath;
            
        } catch (\Exception $e) {
            // Log only to dedicated thumbnails.log
            try {
                $logger = Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/thumbnails.log'),
                ]);
                $logger->error('Thumbnail generation failed', [
                    'source' => $imagePath,
                    'error' => $e->getMessage()
                ]);
            } catch (\Exception $logError) {
                // Silent fail
            }
            
            // Fallback to original
            if (Config::get('thumbnails.fallback_on_error', true)) {
                return $returnUrl ? asset("storage/{$imagePath}") : $imagePath;
            }
            
            return null;
        }
    }
    
    /**
     * Generate thumbnail file
     */
    protected function generateThumbnail(string $sourcePath, string $thumbnailPath, int $width, int $height): void
    {
        $method = Config::get('thumbnails.method', 'resize');
        $driver = Config::get('thumbnails.driver', 'gd');
        
        // Track statistics
        try {
            $statsService = new DailyStatsService();
            $statsService->track($method, ['width' => $width, 'height' => $height]);
        } catch (\Exception $e) {
            // Silent fail - stats shouldn't break generation
        }
        
        switch ($driver) {
            case 'intervention':
                $this->generateWithIntervention($sourcePath, $thumbnailPath, $width, $height);
                break;
                
            case 'imagick':
                $this->generateWithImagick($sourcePath, $thumbnailPath, $width, $height);
                break;
                
            case 'gd':
            default:
                $this->generateWithGD($sourcePath, $thumbnailPath, $width, $height);
                break;
        }
    }
    
    /**
     * Generate using GD library
     */
    protected function generateWithGD(string $sourcePath, string $thumbnailPath, int $width, int $height): void
    {
        $disk = Config::get('thumbnails.disk', 'public');
        $quality = Config::get('thumbnails.quality', 85);
        $method = Config::get('thumbnails.method', 'resize');
        
        $imageInfo = getimagesize($sourcePath);
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];

        // Create source image
        $sourceImage = match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($sourcePath),
            'image/png' => imagecreatefrompng($sourcePath),
            'image/gif' => imagecreatefromgif($sourcePath),
            'image/webp' => imagecreatefromwebp($sourcePath),
            default => throw new \Exception("Unsupported image type: {$mimeType}"),
        };

        // Apply method-specific logic
        [$thumbnail, $destWidth, $destHeight, $srcX, $srcY, $srcWidth, $srcHeight] = match ($method) {
            'crop' => $this->calculateCrop($sourceImage, $sourceWidth, $sourceHeight, $width, $height, $mimeType),
            'fit' => $this->calculateFit($sourceImage, $sourceWidth, $sourceHeight, $width, $height, $mimeType),
            default => $this->calculateResize($sourceImage, $sourceWidth, $sourceHeight, $width, $height, $mimeType),
        };

        // Copy and resample
        imagecopyresampled(
            $thumbnail, $sourceImage,
            0, 0, $srcX, $srcY,
            (int)$destWidth, (int)$destHeight,
            (int)$srcWidth, (int)$srcHeight
        );

        // Save thumbnail
        $fullThumbnailPath = Storage::disk($disk)->path($thumbnailPath);
        $this->ensureDirectoryExists(dirname($fullThumbnailPath));

        match ($mimeType) {
            'image/jpeg' => imagejpeg($thumbnail, $fullThumbnailPath, $quality),
            'image/png' => imagepng($thumbnail, $fullThumbnailPath, 8),
            'image/gif' => imagegif($thumbnail, $fullThumbnailPath),
            'image/webp' => imagewebp($thumbnail, $fullThumbnailPath, $quality),
        };
        
        // Clean up (suppress deprecation warning in PHP 8.1+)
        /** @phpstan-ignore-next-line */
        @imagedestroy($sourceImage);
        /** @phpstan-ignore-next-line */
        @imagedestroy($thumbnail);
    }
    
    /**
     * Calculate dimensions for RESIZE (proportional, aspect ratio preserved)
     * 
     * Part of Laravel On-Demand Thumbnails
     * © 2024-2026 Moonlight Poland <kontakt@howtodraw.pl>
     */
    protected function calculateResize($sourceImage, int $sourceWidth, int $sourceHeight, int $width, int $height, string $mimeType): array
    {
        $aspectRatio = $sourceWidth / $sourceHeight;
        
        if ($width / $height > $aspectRatio) {
            $width = $height * $aspectRatio;
        } else {
            $height = $width / $aspectRatio;
        }
        
        $thumbnail = imagecreatetruecolor((int)$width, (int)$height);
        $this->preserveTransparency($thumbnail, $mimeType);
        
        return [$thumbnail, $width, $height, 0, 0, $sourceWidth, $sourceHeight];
    }
    
    /**
     * Calculate dimensions for CROP (exact size, center crop)
     * 
     * Proprietary center-crop algorithm
     * © 2024-2026 Moonlight Poland <kontakt@howtodraw.pl>
     */
    protected function calculateCrop($sourceImage, int $sourceWidth, int $sourceHeight, int $width, int $height, string $mimeType): array
    {
        $sourceAspect = $sourceWidth / $sourceHeight;
        $targetAspect = $width / $height;
        
        if ($sourceAspect > $targetAspect) {
            // Source is wider - crop width
            $srcHeight = $sourceHeight;
            $srcWidth = $sourceHeight * $targetAspect;
            $srcX = ($sourceWidth - $srcWidth) / 2;
            $srcY = 0;
        } else {
            // Source is taller - crop height
            $srcWidth = $sourceWidth;
            $srcHeight = $sourceWidth / $targetAspect;
            $srcX = 0;
            $srcY = ($sourceHeight - $srcHeight) / 2;
        }
        
        $thumbnail = imagecreatetruecolor($width, $height);
        $this->preserveTransparency($thumbnail, $mimeType);
        
        return [$thumbnail, $width, $height, (int)$srcX, (int)$srcY, (int)$srcWidth, (int)$srcHeight];
    }
    
    /**
     * Calculate dimensions for FIT (fit inside bounds, preserve aspect ratio, add padding)
     * 
     * Smart-fit algorithm with automatic padding
     * © 2024-2026 Moonlight Poland <kontakt@howtodraw.pl>
     */
    protected function calculateFit($sourceImage, int $sourceWidth, int $sourceHeight, int $width, int $height, string $mimeType): array
    {
        $aspectRatio = $sourceWidth / $sourceHeight;
        $targetAspect = $width / $height;
        
        if ($aspectRatio > $targetAspect) {
            // Fit to width
            $destWidth = $width;
            $destHeight = $width / $aspectRatio;
        } else {
            // Fit to height
            $destHeight = $height;
            $destWidth = $height * $aspectRatio;
        }
        
        $thumbnail = imagecreatetruecolor($width, $height);
        $this->preserveTransparency($thumbnail, $mimeType);
        
        // Fill background (white for JPEG, transparent for PNG/GIF)
        if ($mimeType === 'image/jpeg') {
            $white = imagecolorallocate($thumbnail, 255, 255, 255);
            imagefill($thumbnail, 0, 0, $white);
        }
        
        // Calculate padding to center the image
        $offsetX = ($width - $destWidth) / 2;
        $offsetY = ($height - $destHeight) / 2;
        
        // Return with offset positioning
        $tempThumb = imagecreatetruecolor((int)$destWidth, (int)$destHeight);
        $this->preserveTransparency($tempThumb, $mimeType);
        
        imagecopyresampled(
            $tempThumb, $sourceImage,
            0, 0, 0, 0,
            (int)$destWidth, (int)$destHeight,
            $sourceWidth, $sourceHeight
        );
        
        imagecopy($thumbnail, $tempThumb, (int)$offsetX, (int)$offsetY, 0, 0, (int)$destWidth, (int)$destHeight);
        /** @phpstan-ignore-next-line */
        @imagedestroy($tempThumb);
        
        // Return dummy values since we already did the copy
        return [$thumbnail, $width, $height, 0, 0, $width, $height];
    }
    
    /**
     * Preserve transparency for PNG and GIF
     */
    protected function preserveTransparency($image, string $mimeType): void
    {
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($image, false);
            imagesavealpha($image, true);
            $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
            imagefill($image, 0, 0, $transparent);
        }
    }
    
    /**
     * Generate using Intervention Image (if available)
     * 
     * @phpstan-ignore-next-line
     * @psalm-suppress UndefinedClass
     */
    protected function generateWithIntervention(string $sourcePath, string $thumbnailPath, int $width, int $height): void
    {
        if (!class_exists('Intervention\Image\Facades\Image')) {
            $this->generateWithGD($sourcePath, $thumbnailPath, $width, $height);
            return;
        }
        
        try {
            $disk = Config::get('thumbnails.disk', 'public');
            $quality = Config::get('thumbnails.quality', 85);
            
            /** @phpstan-ignore-next-line */
            $image = \Intervention\Image\Facades\Image::make($sourcePath);
            $image->fit($width, $height, function ($constraint) {
                $constraint->upsize();
            });
            
            $fullThumbnailPath = Storage::disk($disk)->path($thumbnailPath);
            $this->ensureDirectoryExists(dirname($fullThumbnailPath));
            
            $image->save($fullThumbnailPath, $quality);
        } catch (\Exception $e) {
            // Silent fallback to GD
            $this->generateWithGD($sourcePath, $thumbnailPath, $width, $height);
        }
    }
    
    /**
     * Generate using Imagick (if available)
     * 
     * @phpstan-ignore-next-line
     * @psalm-suppress UndefinedClass
     */
    protected function generateWithImagick(string $sourcePath, string $thumbnailPath, int $width, int $height): void
    {
        if (!extension_loaded('imagick')) {
            $this->generateWithGD($sourcePath, $thumbnailPath, $width, $height);
            return;
        }
        
        try {
            $disk = Config::get('thumbnails.disk', 'public');
            $quality = Config::get('thumbnails.quality', 85);
            
            /** @phpstan-ignore-next-line */
            $imagick = new \Imagick($sourcePath);
            $imagick->thumbnailImage($width, $height, true, true);
            $imagick->setImageCompressionQuality($quality);
            
            $fullThumbnailPath = Storage::disk($disk)->path($thumbnailPath);
            $this->ensureDirectoryExists(dirname($fullThumbnailPath));
            
            $imagick->writeImage($fullThumbnailPath);
            $imagick->clear();
            $imagick->destroy();
        } catch (\Exception $e) {
            // Silent fallback to GD
            $this->generateWithGD($sourcePath, $thumbnailPath, $width, $height);
        }
    }
    
    /**
     * Get size dimensions from config
     */
    protected function getSize(string $sizeName): array
    {
        $sizes = Config::get('thumbnails.sizes', []);
        
        if (!isset($sizes[$sizeName])) {
            throw new \Exception("Thumbnail size '{$sizeName}' not defined in config");
        }
        
        return $sizes[$sizeName];
    }
    
    /**
     * Get dedicated logger for thumbnails
     */
    protected function getLogger()
    {
        return Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/thumbnails.log'),
        ]);
    }
    
    /**
     * Build thumbnail path using strategy system
     * Supports both new model-based and legacy string-based contexts
     * 
     * @param mixed $modelContext Model instance for Context-Aware strategy
     * @param string $imagePath Original image path
     * @param string $thumbnailFilename Generated thumbnail filename
     * @param string|null $legacyContext Legacy context name (for backward compatibility)
     * @param array $legacyContextData Legacy context data (for backward compatibility)
     * @return string Thumbnail path
     */
    protected function buildThumbnailPathWithStrategy(
        mixed $modelContext,
        string $imagePath,
        string $thumbnailFilename,
        ?string $legacyContext = null,
        array $legacyContextData = []
    ): string
    {
        // If legacy context provided, use old system for backward compatibility
        if ($legacyContext && !empty($legacyContextData)) {
            $contextPath = $this->resolveContextPath($legacyContext, $legacyContextData);
            return "{$contextPath}/thumbnails/{$thumbnailFilename}";
        }
        
        // Use new strategy system
        $strategy = $this->resolveStrategy($modelContext, $imagePath);
        $params = []; // Can be extended with width/height/method if needed
        
        return $strategy->buildPath($modelContext, $thumbnailFilename, $params);
    }
    
    /**
     * Validate image for security
     * 
     * @param string $path Full path to image file
     * @throws \Exception If validation fails
     */
    protected function validateImage(string $path): void
    {
        if (!file_exists($path)) {
            throw new \Exception("Image file not found: {$path}");
        }
        
        // Check file size
        $maxSize = Config::get('thumbnails.security.max_file_size', 10 * 1024 * 1024);
        $size = filesize($path);
        
        if ($size > $maxSize) {
            throw new \Exception("Image too large: {$size} bytes (max: {$maxSize})");
        }
        
        // Check MIME type
        $mime = @mime_content_type($path);
        $allowedMimes = Config::get('thumbnails.security.allowed_mime_types', [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'
        ]);
        
        if ($mime && !in_array($mime, $allowedMimes)) {
            throw new \Exception("Invalid MIME type: {$mime}");
        }
        
        // Check dimensions
        $imageInfo = @getimagesize($path);
        if ($imageInfo) {
            $maxWidth = Config::get('thumbnails.security.max_dimensions.width', 10000);
            $maxHeight = Config::get('thumbnails.security.max_dimensions.height', 10000);
            
            if ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight) {
                throw new \Exception("Image dimensions too large: {$imageInfo[0]}x{$imageInfo[1]}");
            }
        }
        
        // Block SVG if configured
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($extension === 'svg' && Config::get('thumbnails.security.block_svg', true)) {
            throw new \Exception("SVG files are not allowed");
        }
    }
    
    /**
     * Resolve context path from template and data
     * 
     * Context-Aware Thumbnails™ - Proprietary path resolution algorithm
     * © 2024-2026 Moonlight Poland <kontakt@howtodraw.pl>
     * 
     * @param string $context Context name (e.g., "post", "gallery")
     * @param array $data Context data (e.g., ['user_id' => 1, 'post_id' => 12])
     * @return string Resolved path (e.g., "user-posts/1/12")
     * @throws \Exception If context not found or missing data
     */
    protected function resolveContextPath(string $context, array $data): string
    {
        $contexts = Config::get('thumbnails.contexts', []);
        
        if (!isset($contexts[$context])) {
            throw new \Exception("Thumbnail context '{$context}' not defined in config/thumbnails.php");
        }
        
        $template = $contexts[$context];
        
        // Empty template = use default (no context path)
        if (empty($template)) {
            return '';
        }
        
        // Replace all placeholders: {user_id}, {post_id}, etc.
        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
        
        // Check if all placeholders were replaced
        if (preg_match('/\{([^}]+)\}/', $template, $matches)) {
            throw new \Exception("Missing context data '{$matches[1]}' for context '{$context}'. Required: {$contexts[$context]}");
        }
        
        return trim($template, '/');
    }
    
    /**
     * Ensure directory exists
     */
    protected function ensureDirectoryExists(string $directory): void
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }
    
    /**
     * Delete all thumbnails for a given image
     */
    public function deleteThumbnails(string $imagePath): void
    {
        $disk = Config::get('thumbnails.disk', 'public');
        $pathInfo = pathinfo($imagePath);
        $directory = $pathInfo['dirname'];
        $cacheFolder = Config::get('thumbnails.cache_folder', 'thumbnails');
        
        $thumbnailDir = "{$directory}/{$cacheFolder}";
        
        if (Storage::disk($disk)->exists($thumbnailDir)) {
            $baseName = $pathInfo['filename'];
            
            // Delete all files matching pattern
            $files = Storage::disk($disk)->files($thumbnailDir);
            foreach ($files as $file) {
                if (str_contains(basename($file), $baseName)) {
                    Storage::disk($disk)->delete($file);
                }
            }
        }
    }
    
    /**
     * Clear all thumbnails (for entire directory or app)
     */
    public function clearAllThumbnails(?string $directory = null): int
    {
        $disk = Config::get('thumbnails.disk', 'public');
        $cacheFolder = Config::get('thumbnails.cache_folder', 'thumbnails');
        $deleted = 0;
        
        if ($directory) {
            // Clear specific directory
            $thumbnailDir = "{$directory}/{$cacheFolder}";
            if (Storage::disk($disk)->exists($thumbnailDir)) {
                Storage::disk($disk)->deleteDirectory($thumbnailDir);
                $deleted = 1;
            }
        } else {
            // Clear all thumbnail folders recursively
            $allDirectories = Storage::disk($disk)->allDirectories();
            foreach ($allDirectories as $dir) {
                if (basename($dir) === $cacheFolder) {
                    Storage::disk($disk)->deleteDirectory($dir);
                    $deleted++;
                }
            }
        }
        
        return $deleted;
    }

    /**
     * Check license validity (with cache and tampering detection)
     * 
     * CRITICAL SECURITY FUNCTION - DO NOT MODIFY
     * Unauthorized modification constitutes copyright infringement
     * 
     * FAIL-SAFE DESIGN: Errors default to allowing usage if cache exists
     * 
     * @return bool
     */
    protected function checkLicense(): bool
    {
        $cacheFile = storage_path('framework/cache/moonlight-thumbnails-license.cache');
        
        // Check cache file
        if (file_exists($cacheFile)) {
            $data = @json_decode(file_get_contents($cacheFile), true);
            
            if (!$data || !isset($data['expires_at'], $data['signature'])) {
                // Corrupted cache - CRITICAL
                $this->sendTamperingAlert('corrupted_cache', $data ?? []);
                return $this->verifyWithApi();
            }
            
            try {
                $expiresAt = Carbon::parse($data['expires_at']);
                $verifiedAt = Carbon::parse($data['verified_at'] ?? 'now');
                
                // ANTI-TAMPERING: Date more than 1 year in future (CRITICAL)
                if ($expiresAt->greaterThan(now()->addYear())) {
                    $this->sendTamperingAlert('future_date', $data);
                    return $this->verifyWithApi();
                }
                
                // ANTI-TAMPERING: Verified date in future (CRITICAL)
                if ($verifiedAt->greaterThan(now()->addDay())) {
                    $this->sendTamperingAlert('future_verified_date', $data);
                    return $this->verifyWithApi();
                }
                
                // Valid cache - check signature and expiry
                if ($this->verifySignature($data)) {
                    if ($expiresAt->isFuture()) {
                        return true;
                    } else {
                        // Expired - try API to check for renewal
                        return $this->verifyWithApi();
                    }
                }
                
                // Signature mismatch - CRITICAL
                $this->sendTamperingAlert('signature_mismatch', $data);
                return $this->verifyWithApi();
                
            } catch (\Exception $e) {
                $this->sendTamperingAlert('cache_parsing_error', [
                    'error' => $e->getMessage()
                ]);
                return $this->verifyWithApi();
            }
        }
        
        // No cache - first run or deleted
        return $this->verifyWithApi();
    }

    /**
     * Verify license with Moonlight API
     * 
     * CRITICAL: Gracefully handles offline scenarios - never crashes!
     * 
     * @return bool
     */
    protected function verifyWithApi(): bool
    {
        try {
            $licenseKey = Config::get('thumbnails.license_key');
            $apiUrl = Config::get('thumbnails.license_api_url', 'https://howtodraw.pl/api/v1/licenses');
            $cacheFile = storage_path('framework/cache/moonlight-thumbnails-license.cache');
            
            if (!$licenseKey) {
                // IMPORTANT: Log missing license key
                $logger = Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/thumbnails.log'),
                ]);
                $logger->warning('No license key configured');
                
                // Check if there's ANY valid cache - allow offline usage
                if (file_exists($cacheFile)) {
                    $data = @json_decode(file_get_contents($cacheFile), true);
                    if ($data && isset($data['license_key'])) {
                        return true; // Allow offline usage with cached license
                    }
                }
                
                return false; // No license = block usage
            }
            
            // Try to connect to API with short timeout (5 seconds)
            $response = Http::timeout(5)->post("{$apiUrl}/verify", [
                'license_key' => $licenseKey,
                'domain' => request()?->getHost() ?? gethostname(),
                'referrer' => request()?->headers->get('referer') ?? request()?->getHttpHost(),
                'app_name' => Config::get('app.name', 'Unknown'),
                'version' => '2.0.1',
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_info' => [
                    'os' => PHP_OS,
                    'sapi' => PHP_SAPI,
                ],
            ]);
            
            if ($response->successful() && $response->json('valid')) {
                // Save cache
                $cacheData = $response->json('cache_data');
                if ($cacheData) {
                    @file_put_contents($cacheFile, json_encode($cacheData, JSON_PRETTY_PRINT));
                }
                return true;
            }
            
            // API says license is invalid - check cache for offline grace
            return $this->handleOfflineGrace($cacheFile);
            
        } catch (\Exception $e) {
            // Network error, timeout, or any other exception
            // OFFLINE MODE: Allow usage if ANY valid cache exists
            return $this->handleOfflineGrace(storage_path('framework/cache/moonlight-thumbnails-license.cache'));
        }
    }

    /**
     * Handle offline grace period
     * 
     * LIBERAL POLICY: If user ever had a valid license, allow offline usage
     * Only block if cache is obviously tampered or never existed
     * 
     * @param string $cacheFile
     * @return bool
     */
    protected function handleOfflineGrace(string $cacheFile): bool
    {
        if (!file_exists($cacheFile)) {
            return false;
        }
        
        $data = @json_decode(file_get_contents($cacheFile), true);
        
        if (!$data || !isset($data['license_key'], $data['expires_at'])) {
            // IMPORTANT: Corrupted cache
            try {
                $logger = Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/thumbnails.log'),
                ]);
                $logger->warning('Corrupted license cache - cannot operate offline');
            } catch (\Exception $e) {}
            return false;
        }
        
        try {
            $expiresAt = Carbon::parse($data['expires_at']);
            
            // Check if expired more than 90 days ago (very generous)
            if ($expiresAt->lessThan(now()->subDays(90))) {
                // IMPORTANT: License expired long ago
                $logger = Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/thumbnails.log'),
                ]);
                $logger->warning('License expired over 90 days ago', [
                    'expired_at' => $expiresAt->toDateTimeString()
                ]);
                return false;
            }
            
            // Allow offline usage
            return true;
            
        } catch (\Exception $e) {
            // Even if dates are corrupted, if license_key exists, allow usage
            // This is VERY liberal but prevents breaking customer sites
            return true;
        }
    }

    /**
     * Send tampering alert to Moonlight security
     * 
     * CRITICAL SECURITY FUNCTION - Operates silently
     * 
     * @param string $reason
     * @param array $data
     * @return void
     */
    protected function sendTamperingAlert(string $reason, array $data): void
    {
        try {
            $licenseKey = Config::get('thumbnails.license_key');
            $apiUrl = Config::get('thumbnails.license_api_url', 'https://howtodraw.pl/api/v1/licenses');
            
            Http::timeout(5)->post("{$apiUrl}/alert", [
                'license_key' => $licenseKey,
                'domain' => request()?->getHost() ?? gethostname(),
                'referrer' => request()?->headers->get('referer') ?? request()?->getHttpHost(),
                'app_name' => Config::get('app.name', 'Unknown'),
                'ip' => request()?->ip() ?? 'unknown',
                'reason' => $reason,
                'tampering_data' => $data,
                'forensics' => [
                    'php_version' => PHP_VERSION,
                    'laravel_version' => app()->version(),
                    'package_version' => '2.0.1',
                    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? null,
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
                    'os' => PHP_OS,
                    'sapi' => PHP_SAPI,
                    'timestamp' => now()->toIso8601String(),
                ]
            ]);
        } catch (\Exception $e) {
            // Silent fail - don't alert attacker
            // Log to dedicated file
            try {
                $logger = Log::build([
                    'driver' => 'single',
                    'path' => storage_path('logs/thumbnails.log'),
                ]);
                $logger->error('Failed to send tampering alert', ['error' => $e->getMessage()]);
            } catch (\Exception $logError) {
                // Completely silent
            }
        }
    }

    /**
     * Verify HMAC signature
     * 
     * @param array $data
     * @return bool
     */
    protected function verifySignature(array $data): bool
    {
        if (!isset($data['signature'])) {
            return false;
        }
        
        $signature = $data['signature'];
        unset($data['signature']);
        ksort($data);
        
        $expected = hash_hmac('sha256', json_encode($data), config('app.key'));
        
        return hash_equals($expected, $signature);
    }
}


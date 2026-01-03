<?php

namespace Moonlight\Thumbnails\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

/**
 * ThumbnailService
 * 
 * Core service for generating and managing image thumbnails.
 * Inspired by Symfony's LiipImagineBundle.
 * 
 * @package Moonlight\Thumbnails
 */
class ThumbnailService
{
    /**
     * Get or generate thumbnail on-demand (filesystem cache)
     * 
     * @param string $imagePath Relative path (e.g., "photos/image.jpg")
     * @param string $size Size name from config (e.g., "small", "medium")
     * @param bool $returnUrl Return full URL instead of path
     * @return string|null Thumbnail URL/path or null on error
     */
    public function thumbnail(string $imagePath, string $size = 'small', bool $returnUrl = true): ?string
    {
        $disk = Config::get('thumbnails.disk', 'public');
        
        // Check if original exists
        if (!Storage::disk($disk)->exists($imagePath)) {
            if (Config::get('thumbnails.fallback_on_error', true)) {
                return $returnUrl ? asset("storage/{$imagePath}") : null;
            }
            return null;
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
        if (Storage::disk($disk)->exists($thumbnailPath)) {
            return $returnUrl ? asset("storage/{$thumbnailPath}") : $thumbnailPath;
        }
        
        // 🔨 CACHE MISS - generate thumbnail on-demand!
        try {
            $dimensions = $this->getSize($size);
            $sourcePath = Storage::disk($disk)->path($imagePath);
            
            $this->generateThumbnail(
                $sourcePath,
                $thumbnailPath,
                $dimensions['width'],
                $dimensions['height']
            );
            
            Log::info('Thumbnail generated on-demand', [
                'source' => $imagePath,
                'thumbnail' => $thumbnailPath,
                'size' => $size
            ]);
            
            return $returnUrl ? asset("storage/{$thumbnailPath}") : $thumbnailPath;
            
        } catch (\Exception $e) {
            Log::error('Thumbnail generation failed', [
                'source' => $imagePath,
                'size' => $size,
                'error' => $e->getMessage()
            ]);
            
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
        $driver = Config::get('thumbnails.driver', 'gd');
        
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

        // Clean up
        imagedestroy($sourceImage);
        imagedestroy($thumbnail);
    }
    
    /**
     * Calculate dimensions for RESIZE (proportional, aspect ratio preserved)
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
        imagedestroy($tempThumb);
        
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
     */
    protected function generateWithIntervention(string $sourcePath, string $thumbnailPath, int $width, int $height): void
    {
        if (!class_exists('Intervention\Image\Facades\Image')) {
            throw new \Exception('Intervention Image package not installed. Run: composer require intervention/image');
        }
        
        $disk = Config::get('thumbnails.disk', 'public');
        $quality = Config::get('thumbnails.quality', 85);
        
        $image = \Intervention\Image\Facades\Image::make($sourcePath);
        $image->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        });
        
        $fullThumbnailPath = Storage::disk($disk)->path($thumbnailPath);
        $this->ensureDirectoryExists(dirname($fullThumbnailPath));
        
        $image->save($fullThumbnailPath, $quality);
    }
    
    /**
     * Generate using Imagick (if available)
     */
    protected function generateWithImagick(string $sourcePath, string $thumbnailPath, int $width, int $height): void
    {
        if (!extension_loaded('imagick')) {
            throw new \Exception('Imagick extension not loaded. Enable ext-imagick in php.ini');
        }
        
        $disk = Config::get('thumbnails.disk', 'public');
        $quality = Config::get('thumbnails.quality', 85);
        
        $imagick = new \Imagick($sourcePath);
        $imagick->thumbnailImage($width, $height, true, true);
        $imagick->setImageCompressionQuality($quality);
        
        $fullThumbnailPath = Storage::disk($disk)->path($thumbnailPath);
        $this->ensureDirectoryExists(dirname($fullThumbnailPath));
        
        $imagick->writeImage($fullThumbnailPath);
        $imagick->clear();
        $imagick->destroy();
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
}


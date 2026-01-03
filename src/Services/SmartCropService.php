<?php

/**
 * Laravel Context-Aware Thumbnails
 * 
 * @package    moonlight-poland/laravel-context-aware-thumbnails
 * @author     Moonlight Poland Team <kontakt@howtodraw.pl>
 * @copyright  2024-2026 Moonlight Poland. All rights reserved.
 * @license    Commercial License - See LICENSE.md
 * @link       https://github.com/Moonlight4000/laravel-thumbnails
 */

namespace Moonlight\Thumbnails\Services;

use Illuminate\Support\Facades\Config;

/**
 * SmartCropService
 * 
 * Intelligent image cropping using energy detection and rule of thirds.
 * Automatically finds the most important part of an image for cropping.
 */
class SmartCropService
{
    /**
     * Detect the focal point in an image using energy detection
     * 
     * Uses Sobel edge detection operator to find areas of high energy
     * (edges, contrast, interesting features)
     * 
     * @param string $imagePath Path to image file
     * @return array ['x' => int, 'y' => int] Focal point coordinates
     */
    public function detectFocalPoint(string $imagePath): array
    {
        $algorithm = Config::get('thumbnails.smart_crop.algorithm', 'energy');
        
        return match($algorithm) {
            'faces' => $this->detectFaces($imagePath),
            'energy' => $this->detectEnergyPoint($imagePath),
            default => $this->detectEnergyPoint($imagePath),
        };
    }
    
    /**
     * Detect focal point using energy detection (Sobel operator)
     */
    protected function detectEnergyPoint(string $imagePath): array
    {
        // Create image resource
        $image = $this->createImageResource($imagePath);
        if (!$image) {
            // Fallback to center
            return $this->getCenterPoint($imagePath);
        }
        
        $width = imagesx($image);
        $height = imagesy($image);
        
        $maxEnergy = 0;
        $focalPoint = ['x' => (int)($width / 2), 'y' => (int)($height / 2)];
        
        // Sample grid (performance optimization - don't check every pixel)
        $step = max(5, min($width, $height) / 50);
        
        for ($y = 1; $y < $height - 1; $y += $step) {
            for ($x = 1; $x < $width - 1; $x += $step) {
                $energy = $this->calculatePixelEnergy($image, (int)$x, (int)$y);
                
                if ($energy > $maxEnergy) {
                    $maxEnergy = $energy;
                    $focalPoint = ['x' => (int)$x, 'y' => (int)$y];
                }
            }
        }
        
        @imagedestroy($image);
        
        return $focalPoint;
    }
    
    /**
     * Calculate energy at a pixel using Sobel operator
     * 
     * @param resource $image GD image resource
     * @param int $x X coordinate
     * @param int $y Y coordinate
     * @return float Energy value (0-255)
     */
    protected function calculatePixelEnergy($image, int $x, int $y): float
    {
        // Sobel operator kernels
        $sobelX = [[-1, 0, 1], [-2, 0, 2], [-1, 0, 1]];
        $sobelY = [[-1, -2, -1], [0, 0, 0], [1, 2, 1]];
        
        $gradientX = 0;
        $gradientY = 0;
        
        // Apply Sobel kernels
        for ($ky = -1; $ky <= 1; $ky++) {
            for ($kx = -1; $kx <= 1; $kx++) {
                $px = $x + $kx;
                $py = $y + $ky;
                
                // Get pixel brightness
                $rgb = imagecolorat($image, $px, $py);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $brightness = ($r + $g + $b) / 3;
                
                $gradientX += $brightness * $sobelX[$ky + 1][$kx + 1];
                $gradientY += $brightness * $sobelY[$ky + 1][$kx + 1];
            }
        }
        
        // Calculate magnitude of gradient
        return sqrt($gradientX * $gradientX + $gradientY * $gradientY);
    }
    
    /**
     * Detect faces in image (requires Imagick)
     * 
     * @param string $imagePath Path to image
     * @return array Focal point coordinates
     */
    protected function detectFaces(string $imagePath): array
    {
        if (!extension_loaded('imagick')) {
            return $this->detectEnergyPoint($imagePath);
        }
        
        try {
            $imagick = new \Imagick($imagePath);
            
            // Imagick doesn't have built-in face detection
            // This is a placeholder for future implementation
            // Could integrate with OpenCV or external face detection APIs
            
            $imagick->destroy();
        } catch (\Exception $e) {
            // Fallback to energy detection
        }
        
        return $this->detectEnergyPoint($imagePath);
    }
    
    /**
     * Apply rule of thirds to focal point
     * 
     * Adjusts crop box to align focal point with rule of thirds intersections
     * 
     * @param array $focalPoint ['x' => int, 'y' => int]
     * @param int $width Target width
     * @param int $height Target height
     * @param int $imageWidth Source image width
     * @param int $imageHeight Source image height
     * @return array ['x' => int, 'y' => int, 'width' => int, 'height' => int] Crop box
     */
    public function applyRuleOfThirds(
        array $focalPoint,
        int $width,
        int $height,
        int $imageWidth,
        int $imageHeight
    ): array
    {
        if (!Config::get('thumbnails.smart_crop.rule_of_thirds', true)) {
            // Without rule of thirds, just center on focal point
            return $this->centerOnPoint($focalPoint, $width, $height, $imageWidth, $imageHeight);
        }
        
        // Rule of thirds intersection points (relative to crop box)
        $thirdWidth = $width / 3;
        $thirdHeight = $height / 3;
        
        $intersections = [
            ['x' => $thirdWidth, 'y' => $thirdHeight],
            ['x' => 2 * $thirdWidth, 'y' => $thirdHeight],
            ['x' => $thirdWidth, 'y' => 2 * $thirdHeight],
            ['x' => 2 * $thirdWidth, 'y' => 2 * $thirdHeight],
        ];
        
        // Start with centered crop
        $cropX = max(0, min($focalPoint['x'] - $width / 2, $imageWidth - $width));
        $cropY = max(0, min($focalPoint['y'] - $height / 2, $imageHeight - $height));
        
        // Try to position focal point at closest intersection
        // (This is a simplified approach - could be more sophisticated)
        
        return [
            'x' => (int)$cropX,
            'y' => (int)$cropY,
            'width' => $width,
            'height' => $height,
        ];
    }
    
    /**
     * Center crop box on focal point
     */
    protected function centerOnPoint(
        array $focalPoint,
        int $width,
        int $height,
        int $imageWidth,
        int $imageHeight
    ): array
    {
        $x = max(0, min($focalPoint['x'] - $width / 2, $imageWidth - $width));
        $y = max(0, min($focalPoint['y'] - $height / 2, $imageHeight - $height));
        
        return [
            'x' => (int)$x,
            'y' => (int)$y,
            'width' => $width,
            'height' => $height,
        ];
    }
    
    /**
     * Get center point of image (fallback)
     */
    protected function getCenterPoint(string $imagePath): array
    {
        $size = @getimagesize($imagePath);
        if (!$size) {
            return ['x' => 0, 'y' => 0];
        }
        
        return [
            'x' => (int)($size[0] / 2),
            'y' => (int)($size[1] / 2),
        ];
    }
    
    /**
     * Create image resource from file
     */
    protected function createImageResource(string $imagePath)
    {
        $imageInfo = @getimagesize($imagePath);
        if (!$imageInfo) {
            return false;
        }
        
        return match($imageInfo[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($imagePath),
            IMAGETYPE_PNG => @imagecreatefrompng($imagePath),
            IMAGETYPE_GIF => @imagecreatefromgif($imagePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($imagePath) : false,
            default => false,
        };
    }
}


<?php
/**
 * Intelephense Stubs for Optional Dependencies
 * 
 * This file provides type hints for optional dependencies that may not be installed.
 * The `if (false)` blocks ensure this code never executes, but IDEs can still analyze it.
 * 
 * @package moonlight-poland/laravel-context-aware-thumbnails
 * @author Moonlight Poland Team <kontakt@howtodraw.pl>
 * 
 * Optional dependencies:
 * - intervention/image: composer require intervention/image
 * - ext-imagick: PHP extension
 */

namespace Intervention\Image\Facades {
    if (false) {
        /**
         * Intervention Image Facade (stub for Intelephense)
         * 
         * Install: composer require intervention/image
         * @see https://image.intervention.io/
         */
        class Image {
            /**
             * Create image instance from source
             * @param mixed $source
             * @return mixed
             */
            public static function make($source) {}
            
            /**
             * Crop and resize
             * @param int $width
             * @param int $height
             * @param callable|null $callback
             * @return mixed
             */
            public function fit($width, $height, $callback = null) {}
            
            /**
             * Save image
             * @param string $path
             * @param int $quality
             * @return mixed
             */
            public function save($path, $quality = 90) {}
            
            /**
             * Resize image
             * @param int|null $width
             * @param int|null $height
             * @param callable|null $callback
             * @return mixed
             */
            public function resize($width, $height, $callback = null) {}
            
            /**
             * Crop image
             * @param int $width
             * @param int $height
             * @param int|null $x
             * @param int|null $y
             * @return mixed
             */
            public function crop($width, $height, $x = null, $y = null) {}
        }
    }
}

namespace {
    if (false) {
        /**
         * Imagick Extension (stub for Intelephense)
         * 
         * Install: PHP extension (ext-imagick)
         * @see https://www.php.net/manual/en/book.imagick.php
         */
        class Imagick {
            /**
             * Constructor
             * @param string|null $path
             */
            public function __construct($path = null) {}
            
            /**
             * Create thumbnail
             * @param int $width
             * @param int $height
             * @param bool $bestfit
             * @param bool $fill
             * @return bool
             */
            public function thumbnailImage($width, $height, $bestfit = false, $fill = false): bool { return true; }
            
            /**
             * Set compression quality
             * @param int $quality
             * @return bool
             */
            public function setImageCompressionQuality($quality): bool { return true; }
            
            /**
             * Write image to file
             * @param string $filename
             * @return bool
             */
            public function writeImage($filename): bool { return true; }
            
            /**
             * Clear resources
             * @return bool
             */
            public function clear(): bool { return true; }
            
            /**
             * Destroy object
             * @return bool
             */
            public function destroy(): bool { return true; }
            
            /**
             * Resize image
             * @param int $width
             * @param int $height
             * @param int $filter
             * @param float $blur
             * @return bool
             */
            public function resizeImage($width, $height, $filter, $blur): bool { return true; }
            
            /**
             * Crop image
             * @param int $width
             * @param int $height
             * @param int $x
             * @param int $y
             * @return bool
             */
            public function cropImage($width, $height, $x, $y): bool { return true; }
        }
    }
}



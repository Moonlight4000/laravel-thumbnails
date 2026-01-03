/**
 * Laravel On-Demand Thumbnails - JavaScript Helper
 * 
 * @package    moonlight-poland/laravel-thumbnails
 * @author     Moonlight Poland Team <kontakt@howtodraw.pl>
 * @copyright  2024-2026 Moonlight Poland. All rights reserved.
 * @license    Commercial License - See LICENSE.md
 * @link       https://github.com/Moonlight4000/laravel-thumbnails
 * 
 * COMMERCIAL LICENSE: Free for personal use, paid for commercial use.
 * See: https://github.com/Moonlight4000/laravel-thumbnails/blob/main/LICENSE.md
 * 
 * Frontend utility for generating thumbnail URLs client-side.
 * Works with ThumbnailFallback middleware - thumbnails are generated on-demand.
 */

/**
 * Convert image path to thumbnail URL
 * 
 * @param {string} imagePath - Relative path (e.g., 'photos/image.jpg')
 * @param {string} size - Size name: 'small' | 'medium' | 'large' | custom
 * @returns {string} Thumbnail URL
 * 
 * @example
 * import { getThumbnailUrl } from 'moonlight-thumbnails';
 * 
 * const thumbUrl = getThumbnailUrl('photos/cat.jpg', 'small');
 * // Returns: /storage/photos/thumbnails/cat_thumb_small.jpg
 */
export function getThumbnailUrl(imagePath, size = 'small') {
    if (!imagePath) return '';
    
    // Validate size (adjust if your config has different sizes)
    const validSizes = ['small', 'medium', 'large'];
    if (!validSizes.includes(size)) {
        console.warn(`[Thumbnails] Unknown size "${size}", falling back to "small"`);
        size = 'small';
    }
    
    // Parse path: photos/image.jpg
    const parts = imagePath.split('/');
    const filename = parts.pop();
    
    // Extract name and extension
    const lastDotIndex = filename.lastIndexOf('.');
    if (lastDotIndex === -1) {
        console.error(`[Thumbnails] No extension found in: ${filename}`);
        return `/storage/${imagePath}`;
    }
    
    const name = filename.substring(0, lastDotIndex);
    const ext = filename.substring(lastDotIndex + 1);
    
    // Build thumbnail path: photos/thumbnails/image_thumb_small.jpg
    const thumbnailPath = [
        ...parts,
        'thumbnails',
        `${name}_thumb_${size}.${ext}`
    ].join('/');
    
    return `/storage/${thumbnailPath}`;
}

/**
 * Get multiple thumbnail URLs for different sizes
 * 
 * @param {string} imagePath - Relative path
 * @returns {Object} Object with size keys: { small: 'url', medium: 'url', ... }
 * 
 * @example
 * const thumbs = getThumbnailUrls('photos/cat.jpg');
 * console.log(thumbs.small);  // Small URL
 * console.log(thumbs.medium); // Medium URL
 */
export function getThumbnailUrls(imagePath) {
    return {
        small: getThumbnailUrl(imagePath, 'small'),
        medium: getThumbnailUrl(imagePath, 'medium'),
        large: getThumbnailUrl(imagePath, 'large'),
    };
}

/**
 * Preload thumbnails (create <link rel="preload"> tags)
 * Useful for galleries - preloads adjacent images
 * 
 * @param {Array<string>} imagePaths - Array of image paths
 * @param {string} size - Thumbnail size
 * 
 * @example
 * preloadThumbnails(['photo1.jpg', 'photo2.jpg'], 'medium');
 */
export function preloadThumbnails(imagePaths, size = 'medium') {
    imagePaths.forEach(path => {
        const url = getThumbnailUrl(path, size);
        const link = document.createElement('link');
        link.rel = 'preload';
        link.as = 'image';
        link.href = url;
        document.head.appendChild(link);
    });
}

/**
 * Check if thumbnail likely exists (non-blocking)
 * Makes a HEAD request to check if file is cached
 * 
 * @param {string} imagePath - Relative path
 * @param {string} size - Thumbnail size
 * @returns {Promise<boolean>} True if thumbnail exists/is cached
 * 
 * @example
 * const exists = await thumbnailExists('photo.jpg', 'small');
 * if (!exists) {
 *     console.log('Thumbnail will be generated on first view');
 * }
 */
export async function thumbnailExists(imagePath, size = 'small') {
    const url = getThumbnailUrl(imagePath, size);
    
    try {
        const response = await fetch(url, { method: 'HEAD' });
        return response.ok;
    } catch (error) {
        console.error('[Thumbnails] Check failed:', error);
        return false;
    }
}

// Default export for convenience
export default {
    getThumbnailUrl,
    getThumbnailUrls,
    preloadThumbnails,
    thumbnailExists,
};


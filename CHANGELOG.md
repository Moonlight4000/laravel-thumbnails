# Changelog

All notable changes to `laravel-context-aware-thumbnails` will be documented in this file.

## [2.0.0] - 2026-01-03

### 🎉 Major Release - Context-Aware Thumbnails v2.0

### Added
- **Auto-Strategy System**: Automatic selection between Context-Aware (for models) and Hash Prefix (for string paths)
  - `ContextAwareStrategy`: Organize by user/post/album (priority 100)
  - `HashPrefixStrategy`: a/b/ subdirs for performance (priority 1, fallback)
  - `DateBasedStrategy`: 2026/01/03/ organization (optional)
  - `HashLevelsStrategy`: ab/cd/ef/ multi-level (optional)
- **Smart Crop Algorithm**: Intelligent cropping using Sobel edge detection
  - Energy-based focal point detection
  - Rule of thirds alignment
  - Face detection support (when Imagick available)
  - Use `method='smart-crop'` in config or per-thumbnail
- **Modern Image Formats**:
  - AVIF support (~50% smaller than JPEG)
  - WebP support (~30% smaller than JPEG)
  - Automatic format selection based on availability
  - Per-format quality settings
  - Configurable priority: AVIF → WebP → JPG
- **Variants System**: Generate multiple thumbnail sizes at once
  - Preset collections (avatar, gallery, post_images)
  - Custom variant definitions
  - Helper function: `thumbnail_variant($model, $path, 'avatar')`
- **Error Handling Modes**:
  - `silent`: Log error, return original (production-friendly)
  - `strict`: Throw exceptions (development/debugging)
  - `fallback`: Return placeholder image
- **Daily Usage Statistics**:
  - File-based tracking (resets daily)
  - Automatic send to Moonlight server at day change
  - Tracks: usage count, methods used, popular sizes
  - Admin dashboard integration for license holders
  - Privacy-friendly: only aggregated data
- **Security Validation**:
  - Max file size limits (10MB default)
  - Extension whitelist
  - MIME type validation
  - Dimension limits (10000x10000 default)
  - SVG blocking (XXE attack prevention)
- **Intervention Image v2/v3 Dual Support**:
  - Auto-detection of installed version
  - Adapter pattern for compatibility
  - Seamless switching between versions

### Changed
- **Package renamed** from `laravel-smart-thumbnails` to `laravel-context-aware-thumbnails`
- Improved config structure with logical grouping
- Enhanced documentation with comprehensive comparison table
- Better error logging to dedicated `thumbnails.log` file
- Optimized strategy selection for better performance

### Improved
- All configurations now support `.env` variables
- Better backward compatibility maintained
- More flexible path generation
- Enhanced logging without noise
- Comprehensive inline documentation

### Performance
- Hash-based subdirectories handle 1M+ files efficiently
- Lazy loading of strategies
- Optimized image processing pipeline
- Smart caching prevents redundant generation

### Developer Experience
- All existing code continues to work without changes
- New features are opt-in via configuration
- Blade directives remain unchanged
- Helper functions extended but compatible
- Zero breaking changes!

---

## [1.1.0] - 2025-12-20

### Added
- Context-Aware Thumbnails™ feature
- License verification system
- Tamper detection for commercial licenses
- HasThumbnails trait
- Artisan commands for generation and cleanup

### Fixed
- Image processing fallbacks
- GD extension compatibility issues

---

## [1.0.0] - 2025-12-01

### Initial Release
- On-demand thumbnail generation
- Middleware fallback (404 → generate)
- Multiple drivers (GD, Imagick, Intervention)
- Blade directive `@thumbnail()`
- Helper function `thumbnail()`
- Filesystem caching
- Configurable sizes and quality

---

## Upgrade Guide

### From v1.x to v2.0

**Good news: No breaking changes!** All existing code works without modification.

#### Optional: Enable New Features

1. **Update package name** in `composer.json`:
```bash
composer require moonlight-poland/laravel-context-aware-thumbnails
```

2. **Publish new config** (optional):
```bash
php artisan vendor:publish --tag=thumbnails-config --force
```

3. **Enable new features** in `.env`:
```env
# Auto-strategy (Context-Aware + Hash fallback)
THUMBNAILS_AUTO_STRATEGY=true

# Smart Crop
THUMBNAILS_SMART_CROP=true
THUMBNAILS_SMART_CROP_ALGORITHM=energy

# Modern Formats
THUMBNAILS_AUTO_CONVERT=true

# Daily Statistics
THUMBNAILS_STATISTICS_ENABLED=true

# Error Mode
THUMBNAILS_ERROR_MODE=silent
```

4. **Use new features**:
```php
// Smart crop
thumbnail($post, 'image.jpg', 150, 150, true, null, [], 'smart-crop');

// Variants
$avatars = thumbnail_variant($user, 'avatar.jpg', 'avatar');

// Auto-strategy (automatically uses Context-Aware or Hash)
thumbnail($post, 'image.jpg', 150, 150); // Context-Aware
thumbnail('uploads/photo.jpg', 150, 150); // Hash Prefix (auto)
```

---

**Copyright © 2024-2026 Moonlight Poland. All rights reserved.**


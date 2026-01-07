# Changelog

All notable changes to `laravel-context-aware-thumbnails` will be documented in this file.

## [3.0.0] - 2026-01-08

### 🚀 Major Release - Laravel Native Signed URLs

This is a **BREAKING CHANGE** for projects using signed URLs feature.

### Changed (BREAKING)
- **Signed URLs now use Laravel's native signing mechanism** instead of custom Facebook-style (`oh`/`oe` parameters)
  - URLs now use standard `expires` and `signature` query parameters
  - Better compatibility with Laravel ecosystem
  - Simpler implementation, no custom middleware needed
  - Timing-safe signature validation using `hash_hmac()` + `hash_equals()`

### Removed (BREAKING)
- **Removed `SignedUrlService`** - replaced by Laravel's native `URL::temporarySignedRoute()`
- **Removed `ValidateSignedUrl` middleware** - replaced by validation in `StorageFileController`
- **Facebook-style signing (`oh`/`oe` parameters)** - now uses Laravel standard (`expires`/`signature`)

### Added
- **Auto-registered `/storage/{path}` route** in `ThumbnailsServiceProvider`
  - Named route: `storage.serve`
  - Used by `URL::temporarySignedRoute()` in helpers and ThumbnailService
  - Handles signed URL validation internally
- **Enhanced `StorageFileController`**:
  - Manual signature validation using `hash_hmac('sha256', url_with_expires, app_key)`
  - Timing-safe comparison with `hash_equals()`
  - Proper browser caching with `Cache-Control` and `Expires` headers
  - 7-day default expiration (configurable via `THUMBNAILS_URL_EXPIRATION`)

### Requirements
- **Laravel 12+** officially supported (10+ still compatible)
- PHP 8.1, 8.2, or 8.3

### Migration Guide (v2.x → v3.0)

If you're NOT using signed URLs (`THUMBNAILS_SIGNED_URLS=false`), **no changes needed**.

If you ARE using signed URLs:

1. **Update package**:
```bash
composer update moonlight-poland/laravel-context-aware-thumbnails
```

2. **Remove custom route** (if you added one):
   - Package now auto-registers `/storage/{path}` route
   - Remove any custom `storage.serve` routes from your `routes/web.php`

3. **Clear cache**:
```bash
php artisan route:clear
php artisan config:clear
php artisan optimize:clear
```

4. **Regenerate all signed URLs**:
   - Old URLs with `?oh=...&oe=...` will **no longer work**
   - New URLs will use `?expires=...&signature=...`
   - If you cached signed URLs, clear that cache
   - If users bookmarked old URLs, they'll need to refresh

5. **Test signed URLs**:
```php
// This now generates: /storage/path?expires=1234567890&signature=abc123...
$url = thumbnail($post, 'image.jpg', 'large', true, 'post', ['user_id' => 1], signed: true);
$originalUrl = original('photos/full.jpg', signed: true);
```

### Benefits of v3.0
- ✅ Standard Laravel signing - better ecosystem compatibility
- ✅ Simpler codebase - removed 300+ lines of custom code
- ✅ More reliable - uses Laravel's proven signing mechanism
- ✅ Better browser caching - 7-day cache with proper headers
- ✅ Out-of-the-box Laravel 12 support

---

## [2.0.1] - 2026-01-03

### Fixed
- **API Communication**: Added `domain`, `referrer`, and `app_name` to all API calls (verify, alert, daily-stats)
- **Config**: Removed duplicate `fallback_on_error` key (was on lines 234 and 351)
- **Console/Queue Safety**: Made `request()` calls null-safe with `request()?->` to prevent crashes in console/queue contexts
- **Domain Parsing**: Improved domain extraction from `app.url` config when request is not available

### Changed
- Updated `DailyStatsService` to use `parse_url()` for extracting hostname from config
- All API endpoints now send complete tracking information for better analytics

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


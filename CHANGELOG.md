# Changelog

All notable changes to `laravel-context-aware-thumbnails` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.1.0] - 2026-01-09

### Added
- **Context-aware signed URLs for `original()` helper**: The `original()` helper now supports context and contextData parameters, allowing it to generate context-aware signed URLs with the same path structure as `thumbnail()`. This ensures consistency across the entire thumbnail system.

### Changed
- **`original()` helper signature**: Added optional `$context` and `$contextData` parameters to match the context-aware capabilities of the `thumbnail()` helper.
  - Old: `original(string $imagePath, ?bool $signed = null, ?int $expiresIn = null)`
  - New: `original(string $imagePath, ?bool $signed = null, ?string $context = null, array $contextData = [], ?int $expiresIn = null)`

### Fixed
- Original images now properly use context templates (e.g., `appearance-backgrounds/{user_id}/{asset_id}`) when generating signed URLs, ensuring correct path resolution and consistent URL structure across thumbnails and originals.

### Migration Guide
If you're currently calling `original()` with only 2 parameters, your code remains compatible (backward compatible):
```php
// Still works (backward compatible)
$url = original($path, true);

// New usage with context-aware paths
$url = original($path, true, 'appearance', ['user_id' => 1, 'asset_id' => 5]);
```

---

## [3.0.0] - 2025-01-XX

### Added
- Initial release of laravel-context-aware-thumbnails
- Context-Aware Thumbnails™ system
- On-demand thumbnail generation
- Laravel Native Signed URLs support
- Smart Crop with Intervention Image
- AVIF/WebP support
- Variants system
- Zero configuration required

### Features
- Automatic thumbnail organization by context (user/post/album/etc.)
- Multiple size presets (small, medium, large, thumbnail, etc.)
- Hot-linking protection via signed URLs
- Intelligent caching
- Multiple image drivers (GD, Imagick)
- Laravel 10, 11, and 12 support

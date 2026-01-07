# 🚀 Release v3.0.0 - Laravel Native Signed URLs

## ⚠️ BREAKING CHANGES

This is a **major update** that replaces the custom Facebook-style signing with Laravel's native signed URL mechanism.

### What Changed?

**Old (v2.x):**
```
/storage/image.jpg?oh=abc123&oe=65f4a2c1
```

**New (v3.0):**
```
/storage/image.jpg?expires=1736274040&signature=abc123...
```

### Migration Required

If you're using signed URLs (`THUMBNAILS_SIGNED_URLS=true`):

1. **Remove custom route** (if you added one):
   ```php
   // DELETE THIS from routes/web.php:
   Route::get('/storage/{path}', [StorageController::class, 'serve'])
       ->where('path', '.*')
       ->name('storage.serve');
   ```

2. **Update package**:
   ```bash
   composer update moonlight-poland/laravel-context-aware-thumbnails
   ```

3. **Clear cache**:
   ```bash
   php artisan optimize:clear
   ```

4. **Regenerate all signed URLs** - old URLs will **no longer work**

### If NOT using signed URLs
✅ No changes needed - update and you're done!

---

## 🎯 What's New

### ✅ Laravel Native Signing
- Uses `URL::temporarySignedRoute()` - the Laravel standard
- Standard `expires` + `signature` query parameters
- Better ecosystem compatibility
- More reliable and battle-tested

### ✅ Auto-Route Registration
- Package now auto-registers `/storage/{path}` route
- Named `storage.serve` for use with `URL::temporarySignedRoute()`
- No manual route setup required

### ✅ Enhanced StorageFileController
- Manual signature validation using `hash_hmac()` + `hash_equals()`
- Timing-safe comparison prevents timing attacks
- Proper browser caching with `Cache-Control` and `Expires` headers
- 7-day default expiration (configurable via `THUMBNAILS_URL_EXPIRATION`)

### ✅ Audio Streaming Support
- Automatically delegates `.mp3`, `.wav`, `.ogg`, `.m4a`, `.flac` to `AudioStreamController`
- Works if `App\Http\Controllers\AudioStreamController` exists
- Enables HTTP Range Request support for audio seeking

### ✅ Laravel 12+ Ready
- Officially supports Laravel 12.x
- Tested with PHP 8.1, 8.2, 8.3
- Still compatible with Laravel 10.x and 11.x

---

## 📦 Removed (BREAKING)

- ❌ `SignedUrlService` - replaced by Laravel's `URL::temporarySignedRoute()`
- ❌ `ValidateSignedUrl` middleware - validation now in `StorageFileController`
- ❌ Facebook-style `oh`/`oe` parameters - now uses Laravel standard

---

## 🔧 Technical Details

### Signature Generation
```php
// In helpers.php and ThumbnailService.php
$baseUrl = url("/storage/{$path}");
$urlWithExpires = $baseUrl . '?expires=' . $expiration->timestamp;
$signature = hash_hmac('sha256', $urlWithExpires, config('app.key'));
$finalUrl = $urlWithExpires . '&signature=' . $signature;
```

### Signature Validation
```php
// In StorageFileController.php
$url = url("/storage/{$path}");
$urlWithExpires = $url . '?expires=' . $expires;
$expectedSignature = hash_hmac('sha256', $urlWithExpires, config('app.key'));

if (!hash_equals($expectedSignature, $signature)) {
    abort(403, 'Invalid signature');
}

if (time() > (int) $expires) {
    abort(403, 'Signature expired');
}
```

---

## 💡 Benefits

| Feature | v2.x | v3.0 |
|---------|------|------|
| **Signing** | Custom Facebook-style | ✅ Laravel Native |
| **Route Setup** | Manual | ✅ Auto-registered |
| **Middleware** | Custom `ValidateSignedUrl` | ✅ Built-in controller validation |
| **Code Lines** | ~550 | ✅ ~250 (-54%) |
| **Browser Cache** | Basic | ✅ 7-day with proper headers |
| **Audio Streaming** | ❌ Not supported | ✅ Auto-delegation |
| **Laravel 12+** | Partial | ✅ Official support |
| **Ecosystem Compat** | Limited | ✅ Full compatibility |

---

## 📚 Documentation

- [GitHub Repository](https://github.com/Moonlight4000/laravel-thumbnails)
- [Packagist](https://packagist.org/packages/moonlight-poland/laravel-context-aware-thumbnails)
- [Full CHANGELOG](CHANGELOG.md)

---

## 🙏 Credits

Thanks to all users who provided feedback on the signing implementation!

---

**Copyright © 2024-2026 Moonlight Poland. All rights reserved.**


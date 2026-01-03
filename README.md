# 🖼️ Laravel Context-Aware Thumbnails™
### Intelligent On-Demand Image Thumbnails with Smart Crop & Modern Formats

> **Copyright © 2024-2026 Moonlight Poland. All rights reserved.**  
> **Contact:** kontakt@howtodraw.pl  
> **License:** [Commercial License](LICENSE.md) - Free for personal use, paid for commercial use  
> **Repository:** https://github.com/Moonlight4000/laravel-thumbnails

[![Latest Version on Packagist](https://img.shields.io/packagist/v/moonlight-poland/laravel-context-aware-thumbnails.svg?style=flat-square)](https://packagist.org/packages/moonlight-poland/laravel-context-aware-thumbnails)
[![Total Downloads](https://img.shields.io/packagist/dt/moonlight-poland/laravel-context-aware-thumbnails.svg?style=flat-square)](https://packagist.org/packages/moonlight-poland/laravel-context-aware-thumbnails)
[![License: Commercial](https://img.shields.io/badge/License-Commercial-blue.svg)](LICENSE.md)

Generate image thumbnails on-the-fly in Laravel with **Context-Aware Thumbnails™** - the only package that organizes thumbnails exactly where your content lives!

**No pre-generation needed. No Redis required. Smart organization included.™**

---

## 🏆 Why Choose This Over Other Packages?

### 📊 Complete Feature Comparison

| Feature | **Laravel Smart Thumbnails™<br>(moonlight-poland)** | **askancy/<br>laravel-smart-thumbnails** | **lee-to/<br>laravel-thumbnails** | **spatie/<br>laravel-medialibrary** |
|---------|:---:|:---:|:---:|:---:|
| **🎯 UNIQUE FEATURES** |
| **Context-Aware Organization™** | ✅ **ONLY US!** | ❌ | ❌ | ❌ |
| Custom path templates | ✅ `{user_id}/{post_id}` | ❌ | ❌ | ⚠️ Limited |
| Per-user/post isolation | ✅ Built-in | ❌ Manual | ❌ Manual | ⚠️ Via DB |
| Commercial licensing | ✅ $500-$15k | ❌ MIT (free) | ❌ MIT | ✅ Spatie |
| **🖼️ IMAGE PROCESSING** |
| AVIF format support | 🔜 **v2.0** | ✅ | ❌ | ✅ |
| WebP format support | 🔜 **v2.0** | ✅ | ❌ | ✅ |
| Smart Crop (AI energy) | 🔜 **v2.0** | ✅ | ❌ | ✅ |
| Crop/Fit/Resize methods | ✅ All 3 | ✅ SmartCrop | ✅ All 3 | ✅ Yes |
| Multiple drivers | ✅ GD/Imagick/Intervention | ✅ GD/Imagick | ⚠️ Intervention only | ✅ Yes |
| Quality control | ✅ Per size | ✅ Per variant | ✅ Global | ✅ Yes |
| **🛡️ ERROR HANDLING** |
| Silent/Strict modes | 🔜 **v2.0** | ✅ | ❌ | ⚠️ Limited |
| Bulletproof fallbacks | ✅ | ✅ | ⚠️ Basic | ✅ |
| Never breaks app | ✅ | ✅ | ⚠️ Can throw | ✅ |
| **⚡ GENERATION** |
| On-demand (lazy) | ✅ Automatic | ✅ Automatic | ✅ Manual | ✅ Manual |
| Middleware fallback | ✅ Auto 404→generate | ❌ | ❌ | ❌ |
| Zero config | ✅ Works out-of-box | ⚠️ Requires setup | ⚠️ Setup needed | ❌ Complex |
| **📁 ORGANIZATION** |
| Subdirectory strategies | ✅ Context-aware | ✅ 5 strategies | ❌ Flat | ⚠️ Via DB |
| Hash-based distribution | ⚠️ Manual | ✅ Automatic | ❌ | ❌ |
| Date-based folders | ⚠️ Manual | ✅ Automatic | ❌ | ❌ |
| Handles millions of files | ✅ Yes | ✅ Yes | ⚠️ Slow | ✅ Yes |
| **🎨 VARIANTS & PRESETS** |
| Multiple sizes per preset | ✅ | ✅ Variants | ✅ | ✅ |
| Responsive images | ✅ | ✅ | ✅ | ✅ |
| Named presets | ✅ `'small'`, `'large'` | ✅ | ✅ | ✅ |
| **🔧 DEVELOPER EXPERIENCE** |
| Blade directive | ✅ `@thumbnail()` | ❌ | ❌ | ❌ |
| Helper function | ✅ `thumbnail()` | ❌ | ✅ | ❌ |
| Eloquent trait | ✅ `HasThumbnails` | ❌ | ✅ | ✅ |
| Artisan commands | ✅ generate, clear | ✅ purge, optimize | ❌ | ✅ Many |
| **📊 MONITORING** |
| Statistics & analytics | 🔜 **v2.0** | ✅ Full | ❌ | ✅ |
| Performance metrics | 🔜 **v2.0** | ✅ | ❌ | ⚠️ |
| Disk usage tracking | 🔜 **v2.0** | ✅ | ❌ | ✅ |
| **🔒 SECURITY** |
| File validation | 🔜 **v2.0** | ✅ | ⚠️ Basic | ✅ |
| Size limits | 🔜 **v2.0** | ✅ | ❌ | ✅ |
| Extension whitelist | 🔜 **v2.0** | ✅ | ❌ | ✅ |
| Tamper detection | ✅ Commercial only | ❌ | ❌ | ❌ |
| **💾 STORAGE** |
| Filesystem cache | ✅ | ✅ | ✅ | ✅ |
| Redis/Memcached tags | ❌ | ✅ | ❌ | ⚠️ |
| Multi-disk support | ✅ | ✅ | ✅ | ✅ |
| S3/Cloud storage | ✅ | ✅ | ✅ | ✅ |
| Database storage | ❌ | ❌ | ❌ | ✅ |
| **📦 INSTALLATION** |
| Installs | 🆕 New | 17 | ~500 | 50,000+ |
| Stars | ⭐ New | 1 | ~50 | 5,000+ |
| Maturity | 🆕 v1.1 | 🆕 v2.0 | ⚠️ v1.x | ✅ v11.x |

### 🎯 Which Package Should You Choose?

#### Choose **Laravel Context-Aware Thumbnails™ (moonlight-poland)** if you need:
- ✅ **Context-Aware organization** (unique feature!)
- ✅ Thumbnails organized by user/post/album automatically
- ✅ **Auto-strategy**: Context-Aware for models, Hash for paths
- ✅ **Smart Crop with energy detection** (v2.0)
- ✅ **AVIF/WebP modern formats** (v2.0)
- ✅ **Variants system** for multiple sizes (v2.0)
- ✅ **Daily usage statistics** sent to Moonlight (v2.0)
- ✅ Blade directives and helpers for easy use
- ✅ Automatic middleware fallback
- ✅ Commercial support with licensing
- ✅ Simple filesystem-based solution

#### Choose **askancy/laravel-smart-thumbnails** if you need:
- ✅ Advanced smart crop algorithm (energy detection) - **NOW**
- ✅ AVIF/WebP support - **NOW**
- ✅ Extensive statistics and monitoring - **NOW**
- ✅ Hash-based subdirectory strategies
- ✅ Silent/Strict error modes
- ❌ BUT: No context-aware organization

#### Choose **lee-to/laravel-thumbnails** if you need:
- ✅ Simple, basic thumbnail generation
- ✅ Russian community support
- ❌ Limited features compared to others

#### Choose **spatie/laravel-medialibrary** if you need:
- ✅ Full media library management
- ✅ Database storage for metadata
- ✅ File conversions beyond images
- ✅ Battle-tested (50k+ installs)
- ❌ More complex setup
- ❌ Requires database for everything

---

## 🔥 What Makes Context-Aware Thumbnails™ Special?

**Other packages dump all thumbnails in one folder. We organize them exactly where your content lives:**

```
❌ OTHER PACKAGES:
storage/thumbnails/
  ├── user1_avatar_thumb_small.jpg
  ├── post42_image_thumb_small.jpg
  ├── gallery_photo_thumb_small.jpg
  └── ... 10,000+ files in one folder!

✅ CONTEXT-AWARE THUMBNAILS™:
storage/
  ├── user-posts/1/12/thumbnails/image_thumb_small.jpg
  ├── galleries/5/3/thumbnails/photo_thumb_medium.jpg
  ├── avatars/8/thumbnails/avatar_thumb_small.jpg
  └── fanpages/42/photos/thumbnails/banner_thumb_large.jpg
```

**Benefits:**
- ✅ **Delete post** → thumbnails automatically deleted with folder
- ✅ **Per-user backups** → backup specific user folders
- ✅ **CDN routing** → route different contexts to different CDNs
- ✅ **Filesystem performance** → fewer files per directory = faster I/O
- ✅ **Security** → isolate user content with directory permissions
- ✅ **Organization** → find thumbnails instantly, no database queries

---

## ⚠️ License Notice

**This is a COMMERCIAL package with a dual-licensing model:**

- 🆓 **FREE** for personal/non-commercial use
- 💼 **PAID** for commercial use ($500-$15,000/year)

See [LICENSE.md](LICENSE.md) for details.

**Contact:** kontakt@howtodraw.pl  
**GitHub:** https://github.com/Moonlight4000/laravel-thumbnails

---

## ✨ Features

- 🔥 **Context-Aware Thumbnails™** - Organize thumbnails by user/post/album/any structure (UNIQUE!)
- 🚀 **On-Demand Generation** - Thumbnails generated only when requested (lazy loading)
- 💾 **Filesystem Cache** - Fast subsequent loads, no Redis/Memcached needed
- 🔌 **Zero Configuration** - Sensible defaults, works out of the box
- 🎨 **Multiple Drivers** - GD (default), Imagick, or Intervention Image
- 📐 **3 Resize Methods** - Resize (proportional), Crop (exact size), Fit (with padding)
- 🔧 **Fully Configurable** - Custom sizes, quality, drivers, paths, and more
- 🎯 **Blade Directive** - `@thumbnail('path/image.jpg', 'small', 'post', ['user_id' => 1])`
- 📦 **Facade & Helpers** - Multiple ways to use
- 🗑️ **Auto Cleanup** - Delete folder = thumbnails gone
- 🛠️ **Artisan Commands** - Generate or clear thumbnails via CLI
- 🌐 **JavaScript Helper** - Frontend utilities included
- ✅ **Laravel 10 & 11** - Full support for modern Laravel

---

## 📦 Installation

```bash
composer require moonlight-poland/laravel-smart-thumbnails
```

### License Activation

**For Personal (Free) use:**
```bash
php artisan thumbnails:license --type=personal
```

**For Commercial use:**
```bash
# Enter your license key (from purchase email)
php artisan thumbnails:license YOUR-LICENSE-KEY
```

**Contact for licensing:** kontakt@howtodraw.pl

### Optional: Publish Config

```bash
php artisan vendor:publish --tag=thumbnails-config
```

### Make Sure Storage is Linked

```bash
php artisan storage:link
```

---

## 🚀 Quick Start

### Basic Usage (Blade)

```blade
{{-- Original image --}}
<img src="{{ asset('storage/photos/cat.jpg') }}">

{{-- Thumbnail (auto-generated on first request!) --}}
<img src="@thumbnail('photos/cat.jpg', 'small')">
```

**That's it!** 🎉

- **First request**: Generates thumbnail (~50-200ms)
- **Next requests**: Cached file served by Nginx (~1-5ms)

---

## 🔥 Context-Aware Thumbnails™ (UNIQUE FEATURE!)

**The only Laravel package that organizes thumbnails exactly where your content lives!**

### Why Context Matters

Traditional packages dump all thumbnails into one folder. This causes:
- ❌ Messy filesystem (thousands of files in one directory)
- ❌ Difficult cleanup (delete post, but thumbnails remain)
- ❌ No per-user isolation
- ❌ CDN routing nightmare
- ❌ Slow backups (can't backup specific content types)

**Context-Aware Thumbnails™ solves this:**

```blade
{{-- USER POST CONTEXT --}}
<img src="@thumbnail('image.jpg', 'small', 'post', ['user_id' => 1, 'post_id' => 12])">
{{-- Result: /storage/user-posts/1/12/thumbnails/image_thumb_small.jpg --}}

{{-- GALLERY CONTEXT --}}
<img src="@thumbnail('photo.jpg', 'medium', 'gallery', ['user_id' => 5, 'album_id' => 3])">
{{-- Result: /storage/galleries/5/3/thumbnails/photo_thumb_medium.jpg --}}

{{-- AVATAR CONTEXT --}}
<img src="@thumbnail('avatar.jpg', 'small', 'avatar', ['user_id' => 8])">
{{-- Result: /storage/avatars/8/thumbnails/avatar_thumb_small.jpg --}}

{{-- NO CONTEXT (default) --}}
<img src="@thumbnail('cat.jpg', 'small')">
{{-- Result: /storage/thumbnails/cat_thumb_small.jpg --}}
```

### Configuration

Define custom contexts in `config/thumbnails.php`:

```php
'contexts' => [
    // User posts - separate per user and post
    'post' => 'user-posts/{user_id}/{post_id}',
    
    // Gallery - separate per user and album
    'gallery' => 'galleries/{user_id}/{album_id}',
    
    // Avatars - per user only
    'avatar' => 'avatars/{user_id}',
    
    // Fanpage content
    'fanpage' => 'fanpages/{fanpage_id}/{type}',
    
    // Your custom contexts
    'product' => 'products/{category_id}/{product_id}',
    'team' => 'companies/{company_id}/team',
],
```

### PHP Usage

```php
// In controllers
$url = thumbnail('image.jpg', 'small', true, 'post', [
    'user_id' => auth()->id(),
    'post_id' => $post->id
]);

// Helper functions
$url = thumbnail_url('photo.jpg', 'medium', 'gallery', [
    'user_id' => $user->id,
    'album_id' => $album->id
]);

// Facade
use Thumbnail;
$url = Thumbnail::generate('avatar.jpg', 'small', true, 'avatar', [
    'user_id' => $user->id
]);
```

### Model Integration

```php
use Moonlight\Thumbnails\Traits\HasThumbnails;

class UserPost extends Model
{
    use HasThumbnails;
    
    // Define default context for this model
    protected $thumbnailContext = 'post';
    
    // Provide context data automatically
    public function getThumbnailContextData(): array
    {
        return [
            'user_id' => $this->user_id,
            'post_id' => $this->id,
        ];
    }
}

// In Blade - context applied automatically!
<img src="{{ $post->thumbnail('image.jpg', 'small') }}">
{{-- Auto-uses 'post' context with user_id and post_id --}}
```

### Benefits

1. **✅ Automatic Cleanup** - Delete post folder = all thumbnails gone
2. **✅ Per-User Isolation** - Easy permissions & backups per user
3. **✅ CDN Routing** - Route different contexts to different CDNs
4. **✅ Performance** - Fewer files per directory = faster filesystem
5. **✅ Organization** - Find any thumbnail instantly
6. **✅ Scalability** - No "one folder with million files" problem

---

## 📐 Resize Methods

Choose how thumbnails should be generated:

### 1. **Resize** (Default - Proportional)
```php
// config/thumbnails.php
'method' => 'resize',
```
- ✅ Preserves aspect ratio
- ✅ No cropping
- ⚠️ Final size may differ slightly from target

**Use for:** Product images, photos where full content must be visible

### 2. **Crop** (Exact Size - Center Crop)
```php
// config/thumbnails.php
'method' => 'crop',
```
- ✅ Exact dimensions guaranteed
- ✅ Fills entire thumbnail
- ⚠️ May cut edges (center-focused)

**Use for:** Avatars, thumbnails in grids, cards

### 3. **Fit** (Preserve All - Add Padding)
```php
// config/thumbnails.php
'method' => 'fit',
```
- ✅ Entire image visible
- ✅ Exact dimensions
- ⚠️ May have padding/borders

**Use for:** Logos, icons, images where nothing can be cut

**Visual comparison:**

```
Original: 800x600 → Target: 200x200

RESIZE:  200x150 (proportional, smaller)
CROP:    200x200 (center cropped)
FIT:     200x200 (padded top/bottom)
```

---

## 📚 Usage Methods

### 1. Blade Directive

```blade
<img src="@thumbnail('photos/image.jpg', 'small')">
<img src="@thumbnail('photos/image.jpg', 'medium')">
<img src="@thumbnail('photos/image.jpg', 'large')">
```

### 2. Facade

```php
use Moonlight\Thumbnails\Facades\Thumbnail;

$url = Thumbnail::thumbnail('photos/image.jpg', 'medium');
```

### 3. Helper Functions

```php
// Get URL
$url = thumbnail('photos/image.jpg', 'small');

// Aliases
$url = thumbnail_url('photos/image.jpg', 'small');
$path = thumbnail_path('photos/image.jpg', 'small'); // Returns relative path
```

### 4. Service Injection

```php
use Moonlight\Thumbnails\Services\ThumbnailService;

class ImageController
{
    public function show(ThumbnailService $thumbnails)
    {
        $url = $thumbnails->thumbnail('photos/image.jpg', 'medium');
    }
}
```

### 5. JavaScript (Frontend)

```javascript
import { getThumbnailUrl } from 'moonlight-thumbnails';

const thumbUrl = getThumbnailUrl('photos/cat.jpg', 'small');
// Returns: /storage/photos/thumbnails/cat_thumb_small.jpg
```

---

## ⚙️ Configuration

### Default Sizes

```php
// config/thumbnails.php

'sizes' => [
    'small' => ['width' => 150, 'height' => 150],
    'medium' => ['width' => 300, 'height' => 300],
    'large' => ['width' => 600, 'height' => 600],
    
    // Add your custom sizes:
    'avatar' => ['width' => 200, 'height' => 200],
    'banner' => ['width' => 1200, 'height' => 400],
],
```

### Drivers

```php
'driver' => 'gd', // 'gd' (default), 'imagick', or 'intervention'
```

**GD** (built-in, no extra dependencies)
```php
'driver' => 'gd',
```

**Imagick** (better quality, requires `ext-imagick`)
```php
'driver' => 'imagick',
```

**Intervention Image** (most features, requires package)
```bash
composer require intervention/image
```
```php
'driver' => 'intervention',
```

### Quality & Performance

```php
'quality' => 85,              // JPEG quality (1-100)
'fallback_on_error' => true,  // Return original on error
'cache_control' => 'public, max-age=31536000', // 1 year cache
```

---

## 🎯 Advanced Features

### HasThumbnails Trait

Automatically delete thumbnails when model is deleted:

```php
use Moonlight\Thumbnails\Traits\HasThumbnails;

class UserPost extends Model
{
    use HasThumbnails;
    
    // Define which fields contain images
    protected $thumbnailFields = ['cover_image', 'gallery_image'];
}

// Usage in model
$post->thumbnail('cover_image', 'small'); // Get thumbnail URL
$post->thumbnails('cover_image'); // Get all sizes: ['small' => 'url', ...]
```

### Artisan Commands

```bash
# Generate thumbnails for specific image
php artisan thumbnails:generate photos/image.jpg

# Generate specific size
php artisan thumbnails:generate photos/image.jpg --size=small

# Force regenerate (overwrite existing)
php artisan thumbnails:generate photos/image.jpg --force

# Clear all thumbnails
php artisan thumbnails:clear

# Clear specific directory
php artisan thumbnails:clear photos

# Clear specific image thumbnails
php artisan thumbnails:clear photos/image.jpg
```

### Manual Management

```php
use Moonlight\Thumbnails\Facades\Thumbnail;

// Delete all thumbnails for an image
Thumbnail::deleteThumbnails('photos/image.jpg');

// Clear all thumbnails in directory
Thumbnail::clearAllThumbnails('photos');

// Clear ALL thumbnails in app
Thumbnail::clearAllThumbnails();
```

---

## 🏗️ How It Works

### Architecture

```
User Request → /storage/photos/thumbnails/image_thumb_small.jpg
                ↓
          [Nginx tries to serve]
                ↓ (404/403 - file doesn't exist)
          [ThumbnailFallback Middleware]
                ↓
          Parses URL:
          - Original: photos/image.jpg
          - Size: small
                ↓
          ThumbnailService::thumbnail()
                ↓
          Generates thumbnail (150x150)
          Saves to: photos/thumbnails/image_thumb_small.jpg
                ↓
          Returns thumbnail (200 OK)
          Header: X-Thumbnail-Generated: on-demand
                ↓
          [Next request → Nginx serves cached file directly]
```

### File Structure

**Before first request:**
```
storage/app/public/photos/
└── cat.jpg (original, 2.5 MB)
```

**After thumbnail request:**
```
storage/app/public/photos/
├── cat.jpg (original, 2.5 MB)
└── thumbnails/
    ├── cat_thumb_small.jpg (150x150, 15 KB)
    ├── cat_thumb_medium.jpg (300x300, 45 KB)
    └── cat_thumb_large.jpg (600x600, 120 KB)
```

---

## 💼 Licensing

### Choose Your License

| License | Price | Best For | Limits |
|---------|-------|----------|--------|
| **Personal** | **FREE** | Hobby projects, open-source | Non-commercial only |
| **Small Business** | **$500/year** | Startups, freelancers | 1-10 devs, <$1M revenue |
| **Medium Business** | **$1,500/year** | Growing companies | 11-50 devs, $1M-$10M revenue |
| **Enterprise** | **$15,000/year** | Large corporations | 50+ devs, unlimited |

**Full details:** [LICENSE.md](LICENSE.md)

**Contact for commercial licensing:** kontakt@howtodraw.pl

### Why Commercial License?

- 🛠️ **Ongoing Development** - New features, bug fixes, updates
- 💬 **Priority Support** - Fast response times
- 📖 **Comprehensive Docs** - Tutorials, examples, best practices
- 🔒 **Security Updates** - Critical patches within 24h
- 💼 **Business Continuity** - SLA for Enterprise customers

---

## 🆚 Comparison

| Feature | This Package | Traditional Solutions |
|---------|-------------|----------------------|
| **Generation** | On-demand (lazy) | Pre-generate all sizes |
| **Performance** | Fast (only needed) | Slow (generates unused) |
| **Storage** | Efficient | Wastes space |
| **Setup** | Zero config | Complex setup |
| **Cache** | Filesystem | Often needs Redis |
| **Dependencies** | ext-gd (built-in) | Various |

---

## 📖 Examples

### Gallery with Thumbnails

```blade
@foreach($images as $image)
    <a href="{{ asset('storage/' . $image->path) }}">
        <img src="@thumbnail($image->path, 'small')" 
             alt="{{ $image->title }}" 
             loading="lazy">
    </a>
@endforeach
```

### Responsive Images

```blade
<img src="@thumbnail('photos/image.jpg', 'small')"
     srcset="
        @thumbnail('photos/image.jpg', 'small') 150w,
        @thumbnail('photos/image.jpg', 'medium') 300w,
        @thumbnail('photos/image.jpg', 'large') 600w
     "
     sizes="(max-width: 600px) 150px, (max-width: 1200px) 300px, 600px"
     alt="Responsive image">
```

### React Component

```jsx
import { getThumbnailUrl } from 'moonlight-thumbnails';

function ImageGallery({ images }) {
    return (
        <div className="grid grid-cols-3 gap-4">
            {images.map(img => (
                <img 
                    key={img.id}
                    src={getThumbnailUrl(img.path, 'medium')}
                    alt={img.title}
                    loading="lazy"
                />
            ))}
        </div>
    );
}
```

---

## 🤝 Contributing

This is a commercial package. We welcome:
- 🐛 Bug reports (GitHub Issues)
- 💡 Feature suggestions (GitHub Issues)
- 📖 Documentation improvements (PRs welcome)

**Contact:** kontakt@howtodraw.pl

---

## 📄 License

**Commercial License** with free personal tier.

See [LICENSE.md](LICENSE.md) for full terms.

---

## 💝 Credits

Inspired by:
- [Symfony's LiipImagineBundle](https://github.com/liip/LiipImagineBundle)
- [Intervention Image](https://github.com/Intervention/image)

Built with ❤️ by Moonlight Poland Team

---

## 📞 Support

**GitHub Issues:** https://github.com/Moonlight4000/laravel-thumbnails/issues  
**Email:** kontakt@howtodraw.pl

---

**⭐ If this package helped you, please star it on GitHub!**

# 🖼️ Laravel On-Demand Thumbnails

> **Copyright © 2024-2026 Moonlight Poland. All rights reserved.**  
> **Contact:** kontakt@howtodraw.pl  
> **License:** [Commercial License](LICENSE.md) - Free for personal use, paid for commercial use  
> **Repository:** https://github.com/Moonlight4000/laravel-thumbnails

[![Latest Version on Packagist](https://img.shields.io/packagist/v/moonlight-poland/laravel-thumbnails.svg?style=flat-square)](https://packagist.org/packages/moonlight-poland/laravel-thumbnails)
[![Total Downloads](https://img.shields.io/packagist/dt/moonlight-poland/laravel-thumbnails.svg?style=flat-square)](https://packagist.org/packages/moonlight-poland/laravel-thumbnails)
[![License: Commercial](https://img.shields.io/badge/License-Commercial-blue.svg)](LICENSE.md)

Generate image thumbnails on-the-fly in Laravel, just like **Symfony's LiipImagineBundle**.

**No pre-generation needed. No Redis required. Just works.™**

---

## 🏆 Why Choose This Over Other Packages?

| Feature | **moonlight-poland/laravel-thumbnails** | lee-to/laravel-thumbnails | spatie/laravel-medialibrary |
|---------|----------------------------------------|---------------------------|------------------------------|
| **On-demand generation** | ✅ Automatic (middleware) | ✅ Manual | ✅ Manual |
| **Crop/Fit/Resize methods** | ✅ 3 methods | ✅ 3 methods | ✅ Yes |
| **Zero config** | ✅ Works out-of-box | ⚠️ Requires setup | ⚠️ Complex setup |
| **Blade directive** | ✅ `@thumbnail()` | ❌ No | ❌ No |
| **Multiple drivers** | ✅ GD/Imagick/Intervention | ⚠️ Intervention only | ✅ Yes |
| **Artisan commands** | ✅ Yes | ❌ No | ✅ Yes |
| **Middleware fallback** | ✅ Auto-generate on 404 | ❌ No | ❌ No |
| **HasThumbnails trait** | ✅ Yes | ✅ Yes | ✅ Yes |
| **Commercial support** | ✅ Tiered licensing | ❌ No | ✅ Yes (Spatie) |
| **Database storage** | ❌ Filesystem only | ❌ Filesystem only | ✅ Yes |
| **File conversions** | ❌ No | ❌ No | ✅ Yes |

**Best for:** Laravel apps that need **fast, automatic thumbnails** without database overhead.

**When to use Spatie:** When you need database storage, file conversions, and full media library management.

---

## ⚠️ License Notice

**This is a COMMERCIAL package with a dual-licensing model:**

- 🆓 **FREE** for personal/non-commercial use
- 💼 **PAID** for commercial use ($150-$1,500/year)

See [LICENSE.md](LICENSE.md) for details.

**Commercial licenses:** https://moonlight.app/thumbnails/pricing

---

## ✨ Features

- 🚀 **On-Demand Generation** - Thumbnails generated only when requested (lazy loading)
- 💾 **Filesystem Cache** - Fast subsequent loads, no Redis/Memcached needed
- 🔌 **Zero Configuration** - Sensible defaults, works out of the box
- 🎨 **Multiple Drivers** - GD (default), Imagick, or Intervention Image
- 📐 **3 Resize Methods** - Resize (proportional), Crop (exact size), Fit (with padding)
- 🔧 **Fully Configurable** - Custom sizes, quality, drivers, and more
- 🎯 **Blade Directive** - `@thumbnail('path/image.jpg', 'small')`
- 📦 **Facade & Helpers** - Multiple ways to use
- 🗑️ **Auto Cleanup** - Optional trait for automatic thumbnail deletion
- 🛠️ **Artisan Commands** - Generate or clear thumbnails via CLI
- 🌐 **JavaScript Helper** - Frontend utilities included
- ✅ **Laravel 10 & 11** - Full support for modern Laravel

---

## 📦 Installation

```bash
composer require moonlight-poland/laravel-thumbnails
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

**Purchase a license:** https://moonlight.app/thumbnails/pricing

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
| **Small Business** | **$150/year** | Startups, freelancers | 1-10 devs, <$1M revenue |
| **Medium Business** | **$500/year** | Growing companies | 11-50 devs, $1M-$10M revenue |
| **Enterprise** | **$1,500/year** | Large corporations | 50+ devs, unlimited |

**Full details:** [LICENSE.md](LICENSE.md)

**Purchase:** https://moonlight.app/thumbnails/pricing

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
- 💡 Feature suggestions (contact us)
- 📖 Documentation improvements (PRs welcome)

**For commercial features:** Contact enterprise@moonlight.app

---

## 📄 License

**Commercial License** with free personal tier.

See [LICENSE.md](LICENSE.md) for full terms.

---

## 💝 Credits

Inspired by:
- [Symfony's LiipImagineBundle](https://github.com/liip/LiipImagineBundle)
- [Intervention Image](https://github.com/Intervention/image)

Built with ❤️ by the [Moonlight Team](https://moonlight.app)

---

## 🐛 Support

**Free License (Personal):**
- GitHub Issues: https://github.com/moonlight/laravel-thumbnails/issues
- Community Discord: https://discord.gg/moonlight

**Paid Licenses:**
- Small Business: support@moonlight.app (48h response)
- Medium Business: priority@moonlight.app (24h response)
- Enterprise: enterprise@moonlight.app (12h response + SLA)

**Sales & Licensing:**
- Email: licensing@moonlight.app
- Website: https://moonlight.app/thumbnails

---

**⭐ If this package helped you, please star it on GitHub!**

**💼 Need a commercial license? [Get started here](https://moonlight.app/thumbnails/pricing)**

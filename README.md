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

### 🌟 What Makes Us Unique?

1. **🎯 Context-Aware Organization™** - Thumbnails organized by user/post/album (no other package does this!)
2. **⚛️ React/Vue/JavaScript Support** - The ONLY Laravel thumbnail package with `sync-js` command for frontend frameworks
3. **🤖 Smart Crop with AI Energy Detection** - Automatically focuses on important image areas
4. **🚀 AVIF/WebP Support** - Modern formats for 50%+ smaller file sizes
5. **🔒 Commercial Licensing** - Professional support & tamper detection included

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
| AVIF format support | ✅ **v2.0+** | ✅ | ❌ | ✅ |
| WebP format support | ✅ **v2.0+** | ✅ | ❌ | ✅ |
| Smart Crop (AI energy) | ✅ **v2.0+** | ✅ | ❌ | ✅ |
| Crop/Fit/Resize methods | ✅ All 3 | ✅ SmartCrop | ✅ All 3 | ✅ Yes |
| Multiple drivers | ✅ GD/Imagick/Intervention | ✅ GD/Imagick | ⚠️ Intervention only | ✅ Yes |
| Quality control | ✅ Per size | ✅ Per variant | ✅ Global | ✅ Yes |
| **🛡️ ERROR HANDLING** |
| Silent/Strict modes | ✅ **v2.0+** | ✅ | ❌ | ⚠️ Limited |
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
| **React/Vue/JS** | ✅ **ONLY US!** | ❌ | ❌ | ❌ |
| Auto-sync JS helper | ✅ `sync-js` | ❌ | ❌ | ❌ |
| Artisan commands | ✅ generate, clear, sync-js | ✅ purge, optimize | ❌ | ✅ Many |
| **📊 MONITORING** |
| Statistics & analytics | ✅ **v2.0+** | ✅ Full | ❌ | ✅ |
| Performance metrics | ✅ **v2.0+** | ✅ | ❌ | ⚠️ |
| Disk usage tracking | ✅ **v2.0+** | ✅ | ❌ | ✅ |
| **🔒 SECURITY** |
| File validation | ✅ **v2.0+** | ✅ | ⚠️ Basic | ✅ |
| Size limits | ✅ **v2.0+** | ✅ | ❌ | ✅ |
| Extension whitelist | ✅ **v2.0+** | ✅ | ❌ | ✅ |
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
| Maturity | 🆕 v2.0.1 | 🆕 v2.0 | ⚠️ v1.x | ✅ v11.x |

### 🎯 Which Package Should You Choose?

#### Choose **Laravel Context-Aware Thumbnails™ (moonlight-poland)** if you need:
- ✅ **Context-Aware organization** (unique feature!)
- ✅ Thumbnails organized by user/post/album automatically
- ✅ **React/Vue/JavaScript support** (ONLY package with sync-js!)
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

### Optional Dependencies (Recommended)

For best performance and advanced features, install these optional packages:

```bash
# Intervention Image - Required for Smart Crop and better performance
composer require intervention/image

# Imagick Extension - Required for AVIF format support
# (Install via your system's package manager, e.g., apt install php-imagick)
```

**What you get with optional dependencies:**
- ✅ **Smart Crop** - AI-powered energy detection (requires Intervention Image)
- ✅ **AVIF format** - Modern image format with 50% smaller files (requires ext-imagick)
- ✅ **Better performance** - Intervention Image is faster than GD for large images
- ⚠️ **Without them** - Package falls back to GD (works, but limited features)

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

### For React/Vue Apps: Generate JS Helper

**REQUIRED if using React, Vue, or any JavaScript framework:**

```bash
php artisan thumbnails:sync-js
```

This generates `resources/js/utils/thumbnails.js` with your config contexts.

**When to run:**
- ✅ After installation
- ✅ After changing `config/thumbnails.php`
- ✅ After adding new contexts

See [React/Vue Usage](#-react--vue--javascript-usage) section below for details.

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

✅ **Perfect organization** - thumbnails live with their content  
✅ **Easy cleanup** - delete post folder, thumbnails gone  
✅ **Per-user isolation** - great for multi-tenant apps  
✅ **CDN-friendly** - route `/user-posts/1/*` to User 1's CDN  
✅ **Faster backups** - backup specific content types  
✅ **Better performance** - fewer files per directory  

---

## 🎨 React / Vue / JavaScript Usage

> **🌟 UNIQUE FEATURE:** We are the **ONLY Laravel thumbnail package** that provides seamless React/Vue/JavaScript integration with automatic context synchronization! Other packages only work with Blade.

**IMPORTANT:** For React/Vue apps, you need to generate a JavaScript helper that mirrors your PHP config.

### Step 1: Generate JS Helper

```bash
php artisan thumbnails:sync-js
```

This creates `resources/js/utils/thumbnails.js` with your contexts from `config/thumbnails.php`.

**Run this command whenever you:**
- Change `config/thumbnails.php`
- Add new contexts
- Change filename patterns

### Step 2: Import in React/Vue

**✅ YES, the import is REQUIRED!** Without it, your React/Vue components won't have thumbnail URLs.

```jsx
// React Component
import { getThumbnailUrl } from '@/utils/thumbnails';

function PostMedia({ post }) {
    const mediaFiles = post.media_files || [];
    
    return (
        <div>
            {mediaFiles.map((media, index) => (
                <img 
                    key={index}
                    src={getThumbnailUrl(media.path, 'small')}
                    alt={media.alt}
                />
            ))}
        </div>
    );
}
```

### Available Functions

```js
import { 
    getThumbnailUrl,              // Basic usage
    getThumbnailUrlWithContext,   // With Context-Aware
    buildContextPath,             // Build context path only
    THUMBNAIL_CONTEXTS,           // Available contexts
    THUMBNAIL_SIZES               // Available sizes
} from '@/utils/thumbnails';

// Basic usage (path already includes context)
const url = getThumbnailUrl('user-posts/1/12/img.jpg', 'small');
// → /storage/user-posts/1/12/thumbnails/img_thumb_small.jpg

// With crop method + WebP format
const url = getThumbnailUrl('user-posts/1/12/img.jpg', 'small', { 
    method: 'crop',      // crop, fit, or resize
    format: 'webp',      // webp, avif, jpg, png
    quality: 85,         // 1-100
    smart_crop: true     // AI energy detection (v2.0+)
});
// → /storage/user-posts/1/12/thumbnails/img_thumb_small_crop.webp?quality=85&smart_crop=1

// Context-Aware (filename + context + data)
const url = getThumbnailUrlWithContext(
    'img.jpg',              // Just filename
    'small',                // Size
    'post',                 // Context
    { user_id: 1, post_id: 12 },  // Context data
    { method: 'crop', format: 'webp' }  // Options (optional)
);
// → /storage/user-posts/1/12/thumbnails/img_thumb_small_crop.webp

// Build context path only
const path = buildContextPath('post', { user_id: 1, post_id: 12 });
// → user-posts/1/12
```

### ✅ Full Feature Parity with PHP

JavaScript helper supports **ALL** PHP features:
- ✅ **Context-Aware paths** - Organized by user/post/album
- ✅ **Resize methods** - `crop`, `fit`, `resize`
- ✅ **Modern formats** - `webp`, `avif`, `jpg`, `png`
- ✅ **Quality control** - 1-100
- ✅ **Smart Crop** - AI energy detection (v2.0+)
- ✅ **On-demand generation** - Middleware handles 404

**Example with all options:**

```jsx
// React Component with Smart Crop + WebP
function PostMedia({ post }) {
    return (
        <div>
            {post.media_files.map((media, idx) => (
                <img 
                    key={idx}
                    src={getThumbnailUrl(media.path, 'medium', {
                        method: 'crop',
                        format: 'webp',
                        quality: 90,
                        smart_crop: true  // AI focuses on important areas!
                    })}
                    alt={media.alt}
                />
            ))}
        </div>
    );
}
```

### PHP Backend Setup for React

In your **PHP accessor** (e.g., `UserPost.php`):

```php
// Return ONLY path, React will build thumbnail URL
public function getMediaFilesAttribute(): array
{
    $mediaFiles = [];
    
    foreach ($this->attachments as $attachment) {
        if ($attachment['type'] === 'image') {
            $mediaFiles[] = [
                'type' => $attachment['type'],
                'path' => $attachment['path'],  // e.g., 'user-posts/1/12/img.jpg'
                'url' => asset('storage/' . $attachment['path']),
                'alt' => $attachment['original_name'],
            ];
        }
    }
    
    return $mediaFiles;
}
```

**React will:**
1. Call `getThumbnailUrl(media.path, 'small')`
2. Build URL: `/storage/user-posts/1/12/thumbnails/img_thumb_small.jpg`
3. Browser requests thumbnail
4. **404** on first request → middleware generates thumbnail
5. **200** on next requests → cached file served by Nginx

### Workflow

```
┌─────────────────────────────────────────────────────────┐
│ 1. Change config/thumbnails.php                        │
│    (add new context, change pattern, etc.)             │
└─────────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────────┐
│ 2. Run: php artisan thumbnails:sync-js                 │
│    Generates: resources/js/utils/thumbnails.js         │
└─────────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────────┐
│ 3. Commit thumbnails.js to git                         │
│    (single source of truth in PHP, synced to JS)       │
└─────────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────────┐
│ 4. React uses getThumbnailUrl() automatically          │
│    (always in sync with PHP config)                    │
└─────────────────────────────────────────────────────────┘
```

### Vue Example

```vue
<template>
    <div v-for="media in post.media_files" :key="media.path">
        <img 
            :src="getThumbnailUrl(media.path, 'small')" 
            :alt="media.alt"
        />
    </div>
</template>

<script setup>
import { getThumbnailUrl } from '@/utils/thumbnails';

const props = defineProps({
    post: Object
});
</script>
```

---

## 📦 Benefits

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

## 🆕 V2.0 New Features

### Smart Crop (AI Energy Detection)

Automatically detect the most important part of the image for intelligent cropping:

```php
// config/thumbnails.php
'smart_crop' => [
    'enabled' => true,
    'algorithm' => 'energy', // 'energy', 'faces', 'saliency'
    'rule_of_thirds' => true, // Align focal point to rule of thirds
],
```

**Usage:**
```blade
{{-- Smart crop will detect focal point automatically --}}
<img src="@thumbnail('photos/portrait.jpg', 'square', 'post', ['user_id' => 1], 'smart-crop')">
```

**When to use:**
- Portrait photos (focuses on face/eyes)
- Product photos (focuses on the product)
- Landscape photos (focuses on horizon/main subject)

---

### Modern Image Formats (AVIF/WebP)

Automatically convert thumbnails to modern formats for **50% smaller file sizes**:

```php
// config/thumbnails.php
'formats' => [
    'auto_convert' => true,
    'priority' => ['avif', 'webp', 'jpg'], // Try AVIF first, fallback to WebP, then JPG
    'quality' => [
        'avif' => 75,
        'webp' => 80,
        'jpg' => 85,
    ],
],
```

**Usage with Blade directive:**
```blade
{{-- Automatically generates AVIF, WebP, and JPG variants --}}
@thumbnail_picture('photos/sunset.jpg', 'large', 'post', ['user_id' => 5])
{{-- Output:
<picture>
  <source srcset="/storage/.../sunset_thumb_large.avif" type="image/avif">
  <source srcset="/storage/.../sunset_thumb_large.webp" type="image/webp">
  <img src="/storage/.../sunset_thumb_large.jpg" alt="...">
</picture>
--}}
```

**File size comparison:**
- AVIF: **~50% smaller** than JPEG (best quality per byte)
- WebP: **~30% smaller** than JPEG
- JPG: Original compression

---

### Variants System (Generate Multiple Sizes)

Generate multiple thumbnail sizes at once with preset collections:

```php
// config/thumbnails.php
'variants' => [
    'avatar' => [
        ['width' => 50, 'height' => 50, 'method' => 'crop'],
        ['width' => 150, 'height' => 150, 'method' => 'crop'],
        ['width' => 300, 'height' => 300, 'method' => 'crop'],
    ],
    'gallery' => [
        ['width' => 300, 'height' => 200, 'method' => 'crop'],
        ['width' => 800, 'height' => 600, 'method' => 'resize'],
        ['width' => 1200, 'height' => 800, 'method' => 'resize'],
    ],
],
```

**Usage:**
```php
// Generate all avatar sizes at once
$variants = thumbnail_variant($user, 'avatar.jpg', 'avatar');
// Returns: ['50x50' => 'url', '150x150' => 'url', '300x300' => 'url']

// In Blade
@foreach(thumbnail_variant($user, 'photo.jpg', 'gallery') as $size => $url)
    <img src="{{ $url }}" alt="Gallery {{ $size }}">
@endforeach
```

**When to use:**
- User avatars (small, medium, large)
- Gallery thumbnails (grid, lightbox, full-screen)
- Responsive images (different screen sizes)

---

### Subdirectory Strategies (Performance at Scale)

Choose how thumbnails are organized on the filesystem:

```php
// config/thumbnails.php
'subdirectory' => [
    'auto_strategy' => true, // Automatically select best strategy
    'default' => 'hash-prefix',
    'strategies' => [
        'context-aware' => [
            'priority' => 100, // Highest - used for models
            // Result: user-posts/1/12/thumbnails/image_thumb_small.jpg
        ],
        'hash-prefix' => [
            'priority' => 1, // Lowest - fallback for string paths
            'config' => [
                'levels' => 2, // e.g., a/b/
                'length' => 2,
            ],
            // Result: thumbnails/a/b/image_thumb_small.jpg
        ],
        'date-based' => [
            'config' => [
                'format' => 'Y/m/d', // e.g., 2026/01/03/
            ],
            // Result: thumbnails/2026/01/03/image_thumb_small.jpg
        ],
    ],
],
```

**Performance Benefits:**

| Files | Without Subdirs | With Hash Prefix |
|-------|----------------|------------------|
| 1,000 | ⚠️ Slow | ✅ Fast |
| 10,000 | ❌ Very Slow | ✅ Fast |
| 100,000 | ❌ Unusable | ✅ Fast |
| 1,000,000 | ❌ Impossible | ✅ Fast |

**Why:** Operating systems slow down with >1000 files per directory.

---

### Security Validation

Protect against malicious file uploads:

```php
// config/thumbnails.php
'security' => [
    'max_file_size' => 10, // MB
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'],
    'allowed_mime_types' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/avif',
    ],
    'max_dimensions' => [
        'width' => 10000,
        'height' => 10000,
    ],
    'block_svg' => true, // Prevent XXE attacks
],
```

**Automatic validation:** Package validates all images before processing.

---

### Error Handling Modes

Control how the package behaves when errors occur:

```php
// config/thumbnails.php
'error_mode' => 'silent', // 'silent', 'strict', 'fallback'
'placeholder_image' => 'images/placeholder.jpg', // For 'fallback' mode
```

**Modes:**
- **silent** (recommended for production): Log error, return original image
- **strict** (recommended for development): Throw exception
- **fallback**: Return placeholder image

**Example:**
```blade
{{-- If thumbnail generation fails, original image is returned (silent mode) --}}
<img src="@thumbnail('photos/corrupted.jpg', 'small')">
{{-- Instead of crashing, shows original image --}}
```

---

### Daily Usage Statistics (Privacy-Friendly)

Track thumbnail usage for analytics (commercial license holders only):

```php
// config/thumbnails.php
'statistics' => [
    'enabled' => true,
    'send_to_moonlight' => true, // Send to Moonlight dashboard
],
```

**What's tracked:**
- ✅ Daily usage count (how many thumbnails generated today)
- ✅ Methods used (resize, crop, fit)
- ✅ Popular sizes (which sizes are most used)
- ✅ PHP/Laravel versions
- ✅ Domain (where package is installed)

**What's NOT tracked:**
- ❌ Individual images (no filenames)
- ❌ User data (no emails, IPs, personal info)
- ❌ Image content (we never see your images)

**View statistics:** Commercial license holders can view stats at https://howtodraw.pl/developer/licenses

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

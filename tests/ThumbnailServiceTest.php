<?php

/**
 * Laravel On-Demand Thumbnails - Test Suite
 * 
 * @package    moonlight-poland/laravel-thumbnails
 * @author     Moonlight Poland Team <kontakt@howtodraw.pl>
 * @copyright  2024-2026 Moonlight Poland. All rights reserved.
 * @license    Commercial License - See LICENSE.md
 * @link       https://github.com/Moonlight4000/laravel-thumbnails
 */

namespace Moonlight\Thumbnails\Tests;

use Moonlight\Thumbnails\Services\ThumbnailService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class ThumbnailServiceTest extends TestCase
{
    protected ThumbnailService $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = new ThumbnailService();
        
        // Create test image
        Storage::fake('public');
    }

    /** @test */
    public function it_generates_thumbnail_on_demand()
    {
        // Create a test image
        $testImage = $this->createTestImage('photos/test.jpg');
        
        // Generate thumbnail
        $thumbnailUrl = $this->service->thumbnail('photos/test.jpg', 'small', true);
        
        // Assert thumbnail was generated
        $this->assertNotNull($thumbnailUrl);
        $this->assertStringContainsString('thumbnails', $thumbnailUrl);
        $this->assertStringContainsString('test_thumb_small.jpg', $thumbnailUrl);
        
        // Assert file exists
        $this->assertTrue(Storage::disk('public')->exists('photos/thumbnails/test_thumb_small.jpg'));
    }

    /** @test */
    public function it_returns_cached_thumbnail_on_second_request()
    {
        $testImage = $this->createTestImage('photos/test.jpg');
        
        // First request - generates
        $firstUrl = $this->service->thumbnail('photos/test.jpg', 'small', true);
        
        // Second request - cached
        $secondUrl = $this->service->thumbnail('photos/test.jpg', 'small', true);
        
        // URLs should be identical
        $this->assertEquals($firstUrl, $secondUrl);
    }

    /** @test */
    public function it_generates_multiple_sizes()
    {
        $testImage = $this->createTestImage('photos/test.jpg');
        
        $smallUrl = $this->service->thumbnail('photos/test.jpg', 'small', true);
        $mediumUrl = $this->service->thumbnail('photos/test.jpg', 'medium', true);
        $largeUrl = $this->service->thumbnail('photos/test.jpg', 'large', true);
        
        $this->assertNotNull($smallUrl);
        $this->assertNotNull($mediumUrl);
        $this->assertNotNull($largeUrl);
        
        $this->assertTrue(Storage::disk('public')->exists('photos/thumbnails/test_thumb_small.jpg'));
        $this->assertTrue(Storage::disk('public')->exists('photos/thumbnails/test_thumb_medium.jpg'));
        $this->assertTrue(Storage::disk('public')->exists('photos/thumbnails/test_thumb_large.jpg'));
    }

    /** @test */
    public function it_deletes_all_thumbnails_for_image()
    {
        $testImage = $this->createTestImage('photos/test.jpg');
        
        // Generate thumbnails
        $this->service->thumbnail('photos/test.jpg', 'small', true);
        $this->service->thumbnail('photos/test.jpg', 'medium', true);
        
        // Delete all
        $this->service->deleteThumbnails('photos/test.jpg');
        
        // Assert deleted
        $this->assertFalse(Storage::disk('public')->exists('photos/thumbnails/test_thumb_small.jpg'));
        $this->assertFalse(Storage::disk('public')->exists('photos/thumbnails/test_thumb_medium.jpg'));
    }

    /** @test */
    public function it_returns_null_for_non_existent_image()
    {
        Config::set('thumbnails.fallback_on_error', false);
        
        $result = $this->service->thumbnail('photos/nonexistent.jpg', 'small', true);
        
        $this->assertNull($result);
    }

    /** @test */
    public function it_falls_back_to_original_on_error_when_enabled()
    {
        Config::set('thumbnails.fallback_on_error', true);
        
        // This should return original URL since file doesn't exist
        $result = $this->service->thumbnail('photos/nonexistent.jpg', 'small', true);
        
        $this->assertNotNull($result);
        $this->assertStringContainsString('nonexistent.jpg', $result);
    }

    /**
     * Create a test image (simple 1x1 PNG)
     */
    protected function createTestImage(string $path): void
    {
        // Create a simple 1x1 red PNG
        $img = imagecreatetruecolor(100, 100);
        $red = imagecolorallocate($img, 255, 0, 0);
        imagefill($img, 0, 0, $red);
        
        ob_start();
        imagepng($img);
        $contents = ob_get_clean();
        imagedestroy($img);
        
        Storage::disk('public')->put($path, $contents);
    }
}


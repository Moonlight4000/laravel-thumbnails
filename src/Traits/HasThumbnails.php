<?php

namespace Moonlight\Thumbnails\Traits;

use Moonlight\Thumbnails\Services\ThumbnailService;

/**
 * HasThumbnails Trait
 * 
 * Add this trait to Eloquent models that have thumbnail-able images.
 * Provides automatic thumbnail deletion when model is deleted.
 * 
 * Usage:
 * ```php
 * class UserPost extends Model
 * {
 *     use HasThumbnails;
 *     
 *     protected $thumbnailFields = ['cover_image', 'banner'];
 * }
 * ```
 * 
 * @package Moonlight\Thumbnails
 */
trait HasThumbnails
{
    /**
     * Boot the trait - register model events
     */
    protected static function bootHasThumbnails(): void
    {
        // Delete thumbnails when model is deleted
        static::deleting(function ($model) {
            $thumbnailService = app(ThumbnailService::class);
            
            // Get thumbnail fields from model property
            $fields = $model->thumbnailFields ?? [];
            
            foreach ($fields as $field) {
                if ($model->$field) {
                    $thumbnailService->deleteThumbnails($model->$field);
                }
            }
        });
    }
    
    /**
     * Get thumbnail URL for a specific field
     * 
     * @param string $field Field name (e.g., 'cover_image')
     * @param string $size Size name
     * @return string|null
     */
    public function thumbnail(string $field, string $size = 'small'): ?string
    {
        if (!$this->$field) {
            return null;
        }
        
        return app(ThumbnailService::class)->thumbnail($this->$field, $size, true);
    }
    
    /**
     * Get all thumbnail URLs for a field
     * 
     * @param string $field Field name
     * @return array ['small' => 'url', 'medium' => 'url', ...]
     */
    public function thumbnails(string $field): array
    {
        if (!$this->$field) {
            return [];
        }
        
        $thumbnailService = app(ThumbnailService::class);
        $sizes = array_keys(config('thumbnails.sizes', []));
        $thumbnails = [];
        
        foreach ($sizes as $size) {
            $thumbnails[$size] = $thumbnailService->thumbnail($this->$field, $size, true);
        }
        
        return $thumbnails;
    }
}


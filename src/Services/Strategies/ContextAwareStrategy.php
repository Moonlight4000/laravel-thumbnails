<?php

/**
 * Laravel Context-Aware Thumbnails
 * 
 * @package    moonlight-poland/laravel-context-aware-thumbnails
 * @author     Moonlight Poland Team <kontakt@howtodraw.pl>
 * @copyright  2024-2026 Moonlight Poland. All rights reserved.
 * @license    Commercial License - See LICENSE.md
 * @link       https://github.com/Moonlight4000/laravel-thumbnails
 */

namespace Moonlight\Thumbnails\Services\Strategies;

use Illuminate\Support\Facades\Config;

/**
 * Context-Aware Strategy™ - UNIQUE FEATURE!
 * 
 * Organizes thumbnails by model context (user/post/album).
 * Perfect for: semantic organization, per-user isolation, easy cleanup.
 */
class ContextAwareStrategy implements StrategyInterface
{
    protected array $config;
    
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }
    
    public function supports(mixed $context, string $path): bool
    {
        // Supports when context is an Eloquent model
        return is_object($context) && method_exists($context, 'getTable');
    }
    
    public function buildPath(mixed $context, string $filename, array $params): string
    {
        $contextType = $this->detectContextType($context);
        $template = Config::get("thumbnails.contexts.{$contextType}", '');
        
        if (empty($template)) {
            // Fallback to generic with table name and ID
            $tableName = $context->getTable();
            $id = $context->id ?? $context->getKey();
            return "{$tableName}/{$id}/thumbnails/{$filename}";
        }
        
        // Replace placeholders
        $path = $this->replacePlaceholders($template, $context);
        
        return "{$path}/thumbnails/{$filename}";
    }
    
    public function priority(): int
    {
        return $this->config['priority'] ?? 100;
    }
    
    public function getName(): string
    {
        return 'context-aware';
    }
    
    /**
     * Detect context type from model
     */
    protected function detectContextType($model): string
    {
        $tableName = $model->getTable();
        
        // Map table names to context types
        $mapping = [
            'user_posts' => 'post',
            'posts' => 'post',
            'gallery_photos' => 'gallery',
            'photos' => 'gallery',
            'users' => 'avatar',
            'fanpages' => 'fanpage',
        ];
        
        return $mapping[$tableName] ?? 'generic';
    }
    
    /**
     * Replace placeholders in template
     */
    protected function replacePlaceholders(string $template, $model): string
    {
        $replacements = [
            '{id}' => $model->id ?? $model->getKey(),
            '{user_id}' => $model->user_id ?? null,
            '{post_id}' => $model->id ?? null,
            '{album_id}' => $model->album_id ?? null,
            '{fanpage_id}' => $model->fanpage_id ?? null,
            '{type}' => $model->getTable(),
        ];
        
        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $template
        );
    }
}


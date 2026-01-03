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

/**
 * Strategy Interface for Thumbnail Path Generation
 * 
 * Different strategies for organizing thumbnails in subdirectories.
 */
interface StrategyInterface
{
    /**
     * Check if this strategy supports the given context
     * 
     * @param mixed $context Model instance or null
     * @param string $path Original image path
     * @return bool
     */
    public function supports(mixed $context, string $path): bool;
    
    /**
     * Build thumbnail path using this strategy
     * 
     * @param mixed $context Model instance or null
     * @param string $filename Thumbnail filename
     * @param array $params width, height, method
     * @return string Relative path for thumbnail
     */
    public function buildPath(mixed $context, string $filename, array $params): string;
    
    /**
     * Get strategy priority (higher = checked first)
     * 
     * @return int Priority value (1-100)
     */
    public function priority(): int;
    
    /**
     * Get strategy name
     * 
     * @return string
     */
    public function getName(): string;
}


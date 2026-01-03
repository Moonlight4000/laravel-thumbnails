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
 * Date-Based Strategy
 * 
 * Organizes thumbnails by date (2026/01/03/file.jpg).
 * Perfect for: time-series content, easy date-based cleanup.
 */
class DateBasedStrategy implements StrategyInterface
{
    protected array $config;
    
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }
    
    public function supports(mixed $context, string $path): bool
    {
        return $this->config['enabled'] ?? false;
    }
    
    public function buildPath(mixed $context, string $filename, array $params): string
    {
        $format = $this->config['format'] ?? 'Y/m/d';
        $datePath = date($format);
        
        return "thumbnails/{$datePath}/{$filename}";
    }
    
    public function priority(): int
    {
        return $this->config['priority'] ?? 50;
    }
    
    public function getName(): string
    {
        return 'date-based';
    }
}


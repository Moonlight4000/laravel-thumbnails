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
 * Hash Levels Strategy
 * 
 * Organizes thumbnails using multi-level hash (ab/cd/ef/file.jpg).
 * Perfect for: extreme performance with 10M+ files.
 */
class HashLevelsStrategy implements StrategyInterface
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
        $hash = md5($filename);
        $levels = $this->config['levels'] ?? 3;
        $charsPerLevel = $this->config['chars_per_level'] ?? 2;
        
        // Build multi-level structure
        $subdirs = [];
        $offset = 0;
        
        for ($i = 0; $i < $levels; $i++) {
            $subdirs[] = substr($hash, $offset, $charsPerLevel);
            $offset += $charsPerLevel;
        }
        
        $subpath = implode('/', $subdirs);
        
        return "thumbnails/{$subpath}/{$filename}";
    }
    
    public function priority(): int
    {
        return $this->config['priority'] ?? 25;
    }
    
    public function getName(): string
    {
        return 'hash-levels';
    }
}


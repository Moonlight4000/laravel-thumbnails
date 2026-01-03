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
 * Hash Prefix Strategy
 * 
 * Organizes thumbnails using MD5 hash prefix (a/b/file.jpg).
 * Perfect for: high-performance with 1M+ files, no context needed.
 * Automatically used as fallback when no model context provided.
 */
class HashPrefixStrategy implements StrategyInterface
{
    protected array $config;
    
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }
    
    public function supports(mixed $context, string $path): bool
    {
        // Fallback strategy - supports everything
        return true;
    }
    
    public function buildPath(mixed $context, string $filename, array $params): string
    {
        $hash = md5($filename);
        $length = $this->config['length'] ?? 2;
        $depth = $this->config['depth'] ?? 2;
        $baseDir = $this->config['base_dir'] ?? 'thumbnails';
        
        // Build subdirectory structure from hash
        $subdirs = [];
        $offset = 0;
        
        for ($i = 0; $i < $depth; $i++) {
            $subdirs[] = substr($hash, $offset, $length);
            $offset += $length;
        }
        
        $subpath = implode('/', $subdirs);
        
        return "{$baseDir}/{$subpath}/{$filename}";
    }
    
    public function priority(): int
    {
        return $this->config['priority'] ?? 1; // Lowest = fallback
    }
    
    public function getName(): string
    {
        return 'hash-prefix';
    }
}


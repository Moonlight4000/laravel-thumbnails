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

namespace Moonlight\Thumbnails\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * DailyStatsService
 * 
 * Tracks daily usage statistics and sends them to Moonlight server.
 * Simple file-based tracking - resets each day at first use.
 */
class DailyStatsService
{
    protected string $statsFile = 'daily-stats.json';
    protected string $disk = 'local';
    
    /**
     * Track a thumbnail generation
     * 
     * @param string $method resize/crop/fit/smart-crop
     * @param array $dimensions width/height
     */
    public function track(string $method, array $dimensions = []): void
    {
        if (!Config::get('thumbnails.statistics.enabled', false)) {
            return;
        }
        
        try {
            $stats = $this->loadTodayStats();
            
            // Increment usage count
            $stats['usage_count']++;
            
            // Track methods used
            $stats['methods'][$method] = ($stats['methods'][$method] ?? 0) + 1;
            
            // Track popular sizes
            if (!empty($dimensions)) {
                $sizeKey = "{$dimensions['width']}x{$dimensions['height']}";
                $stats['sizes'][$sizeKey] = ($stats['sizes'][$sizeKey] ?? 0) + 1;
            }
            
            $this->saveStats($stats);
        } catch (\Exception $e) {
            // Silent fail - statistics shouldn't break the app
        }
    }
    
    /**
     * Load today's stats (or create new if new day)
     */
    protected function loadTodayStats(): array
    {
        $today = date('Y-m-d');
        
        if (!Storage::disk($this->disk)->exists($this->statsFile)) {
            return $this->createNewDayStats($today);
        }
        
        $stats = json_decode(Storage::disk($this->disk)->get($this->statsFile), true);
        
        // Check if it's a new day
        if ($stats['date'] !== $today) {
            // Send yesterday's stats to Moonlight before reset
            $this->sendStatsToMoonlight($stats);
            
            // Reset for new day
            return $this->createNewDayStats($today);
        }
        
        return $stats;
    }
    
    /**
     * Create fresh stats for new day
     */
    protected function createNewDayStats(string $date): array
    {
        return [
            'date' => $date,
            'usage_count' => 0,
            'methods' => [],
            'sizes' => [],
            'first_use' => now()->toDateTimeString(),
        ];
    }
    
    /**
     * Save stats to file
     */
    protected function saveStats(array $stats): void
    {
        Storage::disk($this->disk)->put(
            $this->statsFile,
            json_encode($stats, JSON_PRETTY_PRINT)
        );
    }
    
    /**
     * Send daily stats to Moonlight server
     * 
     * Called automatically when new day starts (at first thumbnail generation)
     */
    protected function sendStatsToMoonlight(array $stats): void
    {
        $licenseKey = Config::get('thumbnails.license_key');
        if (!$licenseKey || $stats['usage_count'] === 0) {
            return; // Don't send if no license or no usage
        }
        
        $apiUrl = Config::get('thumbnails.license_api_url', 'https://howtodraw.pl/api/v1/licenses');
        $endpoint = rtrim($apiUrl, '/') . '/daily-stats';
        
        try {
            Http::timeout(5) // Short timeout - don't block thumbnail generation
                ->post($endpoint, [
                    'license_key' => $licenseKey,
                    'package' => 'laravel-context-aware-thumbnails',
                    'date' => $stats['date'],
                    'usage_count' => $stats['usage_count'],
                    'methods' => $stats['methods'] ?? [],
                    'popular_sizes' => array_slice($stats['sizes'] ?? [], 0, 10), // Top 10
                    'first_use' => $stats['first_use'] ?? null,
                    'php_version' => PHP_VERSION,
                    'laravel_version' => app()->version(),
                ]);
            
            // Don't check response - fire and forget
        } catch (\Exception $e) {
            // Silent fail - sending stats is optional
            // Don't log to avoid noise
        }
    }
    
    /**
     * Get current day stats (for display/debugging)
     */
    public function getTodayStats(): array
    {
        return $this->loadTodayStats();
    }
    
    /**
     * Manually trigger sending stats (for testing)
     */
    public function sendNow(): bool
    {
        try {
            $stats = $this->loadTodayStats();
            if ($stats['usage_count'] > 0) {
                $this->sendStatsToMoonlight($stats);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}


<?php

/**
 * Laravel On-Demand Thumbnails - Sync JS Helper Command
 * 
 * @package    moonlight-poland/laravel-thumbnails
 * @author     Moonlight Poland Team <kontakt@howtodraw.pl>
 * @copyright  2024-2026 Moonlight Poland. All rights reserved.
 * @license    Commercial License - See LICENSE.md
 * @link       https://github.com/Moonlight4000/laravel-thumbnails
 * 
 * COMMERCIAL LICENSE: Free for personal use, paid for commercial use.
 * See: https://github.com/Moonlight4000/laravel-thumbnails/blob/main/LICENSE.md
 */

namespace Moonlight\Thumbnails\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ThumbnailSyncJsCommand extends Command
{
    protected $signature = 'thumbnails:sync-js';
    
    protected $description = 'Generate JavaScript helper from thumbnails config (for React/Vue)';

    public function handle(): int
    {
        $this->info('🔄 Syncing thumbnail contexts to JavaScript...');
        
        // Read config
        $contexts = Config::get('thumbnails.contexts', []);
        $sizes = Config::get('thumbnails.sizes', []);
        $cacheFolder = Config::get('thumbnails.cache_folder', 'thumbnails');
        $filenamePattern = Config::get('thumbnails.filename_pattern', '{name}_thumb_{size}.{ext}');
        
        // Generate JS file
        $jsContent = $this->generateJsFile($contexts, $sizes, $cacheFolder, $filenamePattern);
        
        // Target path
        $targetPath = resource_path('js/utils/thumbnails.js');
        
        // Ensure directory exists
        if (!File::exists(dirname($targetPath))) {
            File::makeDirectory(dirname($targetPath), 0755, true);
        }
        
        // Write file
        File::put($targetPath, $jsContent);
        
        $this->info("✅ Generated: {$targetPath}");
        $this->comment('💡 Import in React: import { getThumbnailUrl } from \'@/utils/thumbnails\';');
        
        return self::SUCCESS;
    }
    
    protected function generateJsFile(array $contexts, array $sizes, string $cacheFolder, string $filenamePattern): string
    {
        $contextsJson = json_encode($contexts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $sizesJson = json_encode(array_keys($sizes), JSON_PRETTY_PRINT);
        
        // Build template
        $template = file_get_contents(__DIR__ . '/stubs/thumbnails.js.stub');
        
        // Replace placeholders
        $js = str_replace('__CONTEXTS_JSON__', $contextsJson, $template);
        $js = str_replace('__SIZES_JSON__', $sizesJson, $js);
        $js = str_replace('__CACHE_FOLDER__', $cacheFolder, $js);
        $js = str_replace('__FILENAME_PATTERN__', $filenamePattern, $js);
        
        return $js;
    }
}


<?php

/**
 * Laravel On-Demand Thumbnails
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

namespace Moonlight\Thumbnails;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Moonlight\Thumbnails\Services\ThumbnailService;
use Moonlight\Thumbnails\Middleware\ThumbnailFallback;
use Moonlight\Thumbnails\Commands\ThumbnailGenerateCommand;
use Moonlight\Thumbnails\Commands\ThumbnailSyncJsCommand;

/**
 * ThumbnailsServiceProvider
 * 
 * Main service provider for Laravel Thumbnails package.
 * 
 * @package Moonlight\Thumbnails
 */
class ThumbnailsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge package config with app config
        $this->mergeConfigFrom(
            __DIR__.'/config/thumbnails.php', 'thumbnails'
        );
        
        // Register ThumbnailService as singleton
        $this->app->singleton(ThumbnailService::class, function ($app) {
            return new ThumbnailService();
        });
        
        // Register Facade alias
        $this->app->alias(ThumbnailService::class, 'thumbnail');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/thumbnails.php' => config_path('thumbnails.php'),
            ], 'thumbnails-config');
            
            // Register commands
            $this->commands([
                ThumbnailGenerateCommand::class,
                ThumbnailClearCommand::class,
                ThumbnailSyncJsCommand::class,
            ]);
        }
        
        // Register Blade directive
        Blade::directive('thumbnail', function ($expression) {
            return "<?php echo app('Moonlight\\Thumbnails\\Services\\ThumbnailService')->thumbnail({$expression}); ?>";
        });
        
        // Register middleware globally (if enabled in config)
        if (config('thumbnails.enable_middleware', true)) {
            $this->app[\Illuminate\Contracts\Http\Kernel::class]
                ->pushMiddleware(ThumbnailFallback::class);
        }
    }
}


<?php

namespace Moonlight\Thumbnails\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Moonlight\Thumbnails\ThumbnailsServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Additional setup for tests
    }

    protected function getPackageProviders($app)
    {
        return [
            ThumbnailsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default test environment
        $app['config']->set('filesystems.disks.public', [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ]);
    }
}


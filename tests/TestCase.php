<?php

namespace Theograms\FilamentTurnstile\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Theograms\FilamentTurnstile\FilamentTurnstileServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            FilamentTurnstileServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Setup test environment
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Setup Turnstile test configuration
        $app['config']->set('filament-turnstile.site_key', '1x00000000000000000000AA');
        $app['config']->set('filament-turnstile.secret_key', '1x0000000000000000000000000000000AA');
        $app['config']->set('filament-turnstile.test_mode', true);
    }
}

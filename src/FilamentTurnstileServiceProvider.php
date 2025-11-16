<?php

namespace Theograms\FilamentTurnstile;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentTurnstileServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-turnstile';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations();
    }

    public function packageRegistered(): void
    {
        // Register any bindings or services here
    }

    public function packageBooted(): void
    {
        // Validation message publishing
        $this->publishes([
            __DIR__ . '/../resources/lang' => $this->app->langPath('vendor/filament-turnstile'),
        ], 'filament-turnstile-translations');
    }
}

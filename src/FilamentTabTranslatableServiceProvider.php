<?php

namespace KaanTanis\FilamentTabTranslatable;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use KaanTanis\FilamentTabTranslatable\Commands\FilamentTabTranslatableCommand;
use KaanTanis\FilamentTabTranslatable\Testing\TestsFilamentTabTranslatable;

class FilamentTabTranslatableServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-tab-translatable';

    public static string $viewNamespace = 'filament-tab-translatable';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('kaantanis/filament-tab-translatable');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }
    }

    public function packageRegistered(): void
    {
    }

    protected function getAssetPackageName(): ?string
    {
        return 'kaantanis/filament-tab-translatable';
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_filament-tab-translatable_table',
        ];
    }
}

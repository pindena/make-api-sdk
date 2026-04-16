<?php

namespace Pindena\MakeSDK;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MakeSDKServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('make-sdk')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(MakeSDK::class, function ($app) {
            $config = $app['config']['make-sdk'];

            return new MakeSDK(
                username: $config['username'],
                password: $config['password'],
                subscribersApiUrl: $config['subscribersApiUrl'],
                newslettersApiUrl: $config['newslettersApiUrl'],
            );
        });
    }
}

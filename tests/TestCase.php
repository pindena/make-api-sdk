<?php

namespace Pindena\MakeSDK\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Pindena\MakeSDK\MakeSDKServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            MakeSDKServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Make' => \Pindena\MakeSDK\Facades\Make::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('make-sdk.username', 'test-user');
        $app['config']->set('make-sdk.password', 'test-pass');
        $app['config']->set('make-sdk.subscribersApiUrl', 'https://subscribers.dialogapi.no/api/public/v2');
        $app['config']->set('make-sdk.newslettersApiUrl', 'https://newsletters.dialogapi.no/api/public/v1');
    }
}

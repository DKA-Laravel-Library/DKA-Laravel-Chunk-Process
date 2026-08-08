<?php

declare(strict_types=1);

namespace LaravelChunkProcess\LaravelChunkProcess\Tests;

use LaravelChunkProcess\LaravelChunkProcess\LaravelChunkProcessServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelChunkProcessServiceProvider::class,
        ];
    }
}

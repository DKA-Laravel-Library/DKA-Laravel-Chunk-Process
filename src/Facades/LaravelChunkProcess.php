<?php

declare(strict_types=1);

namespace LaravelChunkProcess\LaravelChunkProcess\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LaravelChunkProcess\LaravelChunkProcess\LaravelChunkProcess
 */
class LaravelChunkProcess extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelChunkProcess\LaravelChunkProcess\LaravelChunkProcess::class;
    }
}

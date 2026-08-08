<?php

declare(strict_types=1);

use LaravelChunkProcess\LaravelChunkProcess\LaravelChunkProcess;

it('resolves the singleton', function () {
    expect(app(LaravelChunkProcess::class))->toBeInstanceOf(LaravelChunkProcess::class);
});

it('returns the same instance from the container', function () {
    expect(app(LaravelChunkProcess::class))->toBe(app(LaravelChunkProcess::class));
});

it('merges the package config', function () {
    expect(config('laravel-chunk-process.placeholder'))->toBe('default');
});

it('loads the package translations', function () {
    expect(trans('laravel-chunk-process::messages.placeholder'))->toBe('LaravelChunkProcess placeholder translation.');
});

it('loads the package views', function () {
    expect(view()->exists('laravel-chunk-process::placeholder'))->toBeTrue();
});

it('registers the artisan command', function () {
    $this->artisan('laravel-chunk-process:placeholder')
        ->expectsOutputToContain('LaravelChunkProcess placeholder command executed.')
        ->assertSuccessful();
});

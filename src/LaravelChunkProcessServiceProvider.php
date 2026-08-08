<?php

declare(strict_types=1);

namespace LaravelChunkProcess\LaravelChunkProcess;

use Illuminate\Support\ServiceProvider;
use LaravelChunkProcess\LaravelChunkProcess\Console\Commands\LaravelChunkProcessCommand;

class LaravelChunkProcessServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-chunk-process.php', 'laravel-chunk-process');

        $this->app->singleton(LaravelChunkProcess::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/laravel-chunk-process.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-chunk-process');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'laravel-chunk-process');

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/laravel-chunk-process.php' => config_path('laravel-chunk-process.php'),
        ], ['laravel-chunk-process', 'laravel-chunk-process-config']);

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-chunk-process'),
        ], ['laravel-chunk-process', 'laravel-chunk-process-views']);

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/laravel-chunk-process'),
        ], ['laravel-chunk-process', 'laravel-chunk-process-lang']);

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/laravel-chunk-process'),
        ], ['laravel-chunk-process', 'laravel-chunk-process-assets']);

        if (method_exists($this, 'publishesMigrations')) {
            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], ['laravel-chunk-process', 'laravel-chunk-process-migrations']);
        } else {
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], ['laravel-chunk-process', 'laravel-chunk-process-migrations']);
        }

        $this->commands([
            LaravelChunkProcessCommand::class,
        ]);
    }
}

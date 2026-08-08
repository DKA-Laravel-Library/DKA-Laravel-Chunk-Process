<?php

declare(strict_types=1);

namespace LaravelChunkProcess\LaravelChunkProcess\Console\Commands;

use Illuminate\Console\Command;

class LaravelChunkProcessCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'laravel-chunk-process:placeholder';

    /**
     * The command description.
     */
    protected $description = 'Placeholder Artisan command shipped by the package laravel-chunk-process.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->line('LaravelChunkProcess placeholder command executed.');

        return self::SUCCESS;
    }
}

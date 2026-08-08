<?php

declare(strict_types=1);

namespace LaravelChunkProcess\LaravelChunkProcess;

use Closure;
use Illuminate\Bus\PendingBatch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;
use InvalidArgumentException;

class LaravelChunkProcess
{
    public function chunk(string|Model|Builder $source, int $size, Closure $callback): void
    {
        $query = $this->resolveChunkSource($source);

        $query->chunk($size, $callback);
    }

    public function batch(array $jobs): PendingBatch
    {
        return Bus::batch($jobs);
    }

    public function stepsForModel(Model $model, array $steps): PendingBatch
    {
        $jobs = array_map(
            fn (string|Closure|ShouldQueue $step): ShouldQueue => $this->resolveStep($step, $model),
            $steps,
        );

        return $this->batch($jobs);
    }

    private function resolveChunkSource(string|Model|Builder $source): object
    {
        if ($source instanceof Builder) {
            return $source;
        }

        if (is_string($source) && is_subclass_of($source, Model::class)) {
            return $source::query();
        }

        if ($source instanceof Model) {
            return $source->newQuery();
        }

        throw new InvalidArgumentException('The chunk source must be a model class, model instance, or query builder.');
    }

    private function resolveStep(string|Closure|ShouldQueue $step, Model $model): ShouldQueue
    {
        if ($step instanceof Closure) {
            $job = $step($model);

            if (! $job instanceof ShouldQueue) {
                throw new InvalidArgumentException('The step callback must return a ShouldQueue job instance.');
            }

            return $job;
        }

        if ($step instanceof ShouldQueue) {
            return $step;
        }

        if (is_string($step) && class_exists($step)) {
            $job = new $step($model);

            if (! $job instanceof ShouldQueue) {
                throw new InvalidArgumentException('The job class must implement ShouldQueue.');
            }

            return $job;
        }

        throw new InvalidArgumentException('Each step must be a job class name, a closure returning a job, or a job instance.');
    }
}

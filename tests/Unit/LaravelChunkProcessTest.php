<?php

declare(strict_types=1);

use Closure;
use Illuminate\Bus\PendingBatch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use LaravelChunkProcess\LaravelChunkProcess\LaravelChunkProcess;

it('dispatches a batch from an array of jobs', function () {
    Bus::fake();

    $service = app(LaravelChunkProcess::class);

    $service->batch([
        new DummyJob('first'),
        new DummyJob('second'),
    ])->dispatch();

    Bus::assertBatched(fn (PendingBatch $batch) => count($batch->jobs) === 2);
});

it('builds a multi-step batch for a model instance', function () {
    Bus::fake();

    $service = app(LaravelChunkProcess::class);
    $model = new TestModel();

    $service->stepsForModel($model, [
        fn (TestModel $model) => new DummyJob($model),
        DummyStepJob::class,
    ])->dispatch();

    Bus::assertBatched(fn (PendingBatch $batch) => count($batch->jobs) === 2);
});

it('chunks a queryable model and executes the callback', function () {
    $service = app(LaravelChunkProcess::class);

    TestChunkModel::$chunkInvoked = false;
    TestChunkModel::$chunkSize = 0;
    TestChunkModel::$chunkValue = null;

    $service->chunk(TestChunkModel::class, 1000, function ($chunk) {
        TestChunkModel::$chunkValue = $chunk;
    });

    expect(TestChunkModel::$chunkInvoked)->toBeTrue();
    expect(TestChunkModel::$chunkSize)->toBe(1000);
    expect(TestChunkModel::$chunkValue)->toBeInstanceOf(Collection::class);
});

class TestModel extends Model
{
    protected $guarded = [];
}

class TestChunkModel extends Model
{
    public static bool $chunkInvoked = false;
    public static int $chunkSize = 0;
    public static mixed $chunkValue = null;

    public static function query()
    {
        return new class {
            public function chunk(int $size, Closure $callback): void
            {
                TestChunkModel::$chunkInvoked = true;
                TestChunkModel::$chunkSize = $size;
                $callback(new Collection(['one', 'two']));
            }
        };
    }
}

class DummyJob implements ShouldQueue
{
    use Illuminate\Bus\Queueable;

    public function __construct(public mixed $payload = null)
    {
    }
}

class DummyStepJob implements ShouldQueue
{
    use Illuminate\Bus\Queueable;

    public function __construct(public TestModel $model)
    {
    }
}

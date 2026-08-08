<div align="center">
    <h1>Laravel Chunk Process</h1>
</div>

<p align="center">
    <a href="https://packagist.org/packages/yovangga-anandhika/laravel-chunk-process"><img src="https://img.shields.io/packagist/v/yovangga-anandhika/laravel-chunk-process.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/yovangga-anandhika/laravel-chunk-process"><img src="https://img.shields.io/packagist/php-v/yovangga-anandhika/laravel-chunk-process.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://packagist.org/packages/yovangga-anandhika/laravel-chunk-process"><img src="https://badge.laravel.cloud/badge/yovangga-anandhika/laravel-chunk-process?style=flat" alt="Laravel versions"></a>
    <a href="https://github.com/yovangga-anandhika/laravel-chunk-process/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/yovangga-anandhika/laravel-chunk-process/tests.yml?branch=main&label=Tests&style=flat-square"></a>
    <a href="https://packagist.org/packages/yovangga-anandhika/laravel-chunk-process"><img src="https://img.shields.io/packagist/dt/yovangga-anandhika/laravel-chunk-process.svg?style=flat-square" alt="Total Downloads"></a>
</p>

Laravel Library For Background chunks

## Installation

You can install the package via Composer:

```bash
composer require yovangga-anandhika/laravel-chunk-process
```

You may publish all of the package's resources at once:

```bash
php artisan vendor:publish --tag="laravel-chunk-process"
```

Or, you may publish each resource individually:

### Publishing the Configuration File

```bash
php artisan vendor:publish --tag="laravel-chunk-process-config"
```

### Publishing and Running the Migrations

```bash
php artisan vendor:publish --tag="laravel-chunk-process-migrations"
php artisan migrate
```

### Publishing the Views

```bash
php artisan vendor:publish --tag="laravel-chunk-process-views"
```

### Publishing the Translations

```bash
php artisan vendor:publish --tag="laravel-chunk-process-lang"
```

### Publishing the Public Assets

```bash
php artisan vendor:publish --tag="laravel-chunk-process-assets"
```

## Usage

### 1. Chunk data and dispatch jobs

```php
use App\Jobs\ProsesImporDataJob;
use App\Models\DataMentah;
use LaravelChunkProcess\LaravelChunkProcess\Facades\LaravelChunkProcess;

DataMentah::chunk(1000, function ($chunk) {
    ProsesImporDataJob::dispatch($chunk);
});
```

Atau gunakan helper package untuk memproses sumber data model / query:

```php
use App\Jobs\ProsesImporDataJob;
use App\Models\DataMentah;
use LaravelChunkProcess\LaravelChunkProcess\Facades\LaravelChunkProcess;

LaravelChunkProcess::chunk(DataMentah::class, 1000, function ($chunk) {
    ProsesImporDataJob::dispatch($chunk);
});
```

### 2. Buat batch dengan `then` / `catch`

```php
use Illuminate\Support\Facades\Bus;
use App\Jobs\ProsesItemDataJob;

$batch = Bus::batch([
    new ProsesItemDataJob($item1),
    new ProsesItemDataJob($item2),
    // ...
])->then(function ($batch) {
    // Semua data selesai diproses.
})->catch(function ($batch, Throwable $e) {
    // Ada job yang gagal.
})->dispatch();

return response()->json(['batch_id' => $batch->id]);
```

### 3. Proses multi-step untuk model

```php
use App\Jobs\ProsesLangkahPertamaJob;
use App\Jobs\ProsesLangkahKeduaJob;
use App\Models\DataMentah;
use LaravelChunkProcess\LaravelChunkProcess\Facades\LaravelChunkProcess;

$model = DataMentah::find($id);

$batch = LaravelChunkProcess::stepsForModel($model, [
    ProsesLangkahPertamaJob::class,
    fn (DataMentah $model) => new ProsesLangkahKeduaJob($model),
])->dispatch();

return response()->json(['batch_id' => $batch->id]);
```

Gunakan `stepsForModel()` untuk membuat urutan job yang menerima instance model, lalu jalankan sebagai satu batch.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to Laravel Chunk Process! Please review our [contributing guide](.github/CONTRIBUTING.md) to get started.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Yovangga Anandhika](https://github.com/yovangga-anandhika)
- [All Contributors](../../contributors)

## License

Laravel Chunk Process is open-sourced software licensed under the [MIT license](LICENSE.md).

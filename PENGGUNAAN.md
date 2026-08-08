# Panduan Penggunaan Laravel Chunk Process

Gunakan teks berikut untuk dibagikan ke tim di WhatsApp atau chat lain.

## 1. Instalasi

```bash
composer require yovangga/laravel-chunk-process
```

## 2. Chunk data dan kirim job ke antrean

```php
use App\Jobs\ProsesImporDataJob;
use App\Models\DataMentah;

DataMentah::chunk(1000, function ($chunk) {
    ProsesImporDataJob::dispatch($chunk);
});
```

Jika ingin menggunakan helper package:

```php
use App\Jobs\ProsesImporDataJob;
use App\Models\DataMentah;
use LaravelChunkProcess\LaravelChunkProcess\Facades\LaravelChunkProcess;

LaravelChunkProcess::chunk(DataMentah::class, 1000, function ($chunk) {
    ProsesImporDataJob::dispatch($chunk);
});
```

## 3. Membuat batch job dengan `then` dan `catch`

```php
use Illuminate\Support\Facades\Bus;
use App\Jobs\ProsesItemDataJob;

$batch = Bus::batch([
    new ProsesItemDataJob($item1),
    new ProsesItemDataJob($item2),
    // ...
])->then(function ($batch) {
    // Semua job selesai diproses.
})->catch(function ($batch, Throwable $e) {
    // Ada job yang gagal.
})->dispatch();

return response()->json(['batch_id' => $batch->id]);
```

## 4. Proses multi-step berdasarkan model

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

## 5. Penjelasan singkat

- `chunk()` digunakan untuk memproses data besar dengan memecahnya per 1.000 baris.
- `Bus::batch()` digunakan untuk menjalankan banyak job dalam satu batch dan bisa pakai `then()` / `catch()`.
- `stepsForModel()` digunakan untuk membuat urutan job yang memproses satu model pada beberapa tahap.

Silakan salin seluruh isi file ini dan bagikan ke tim sebagai panduan penggunaan.

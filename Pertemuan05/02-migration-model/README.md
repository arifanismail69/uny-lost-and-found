# 02 - Migration dan Model

## Berkas

| Berkas | Isi |
|---|---|
| `aplikasi/database/migrations/2026_11_21_214122_create_reports_table.php` | Tabel `reports` |
| `aplikasi/database/migrations/2026_11_21_214124_create_claims_table.php` | Tabel `claims` + foreign key `report_id` |
| `aplikasi/app/Models/Report.php` | Model laporan: `$fillable`, cast tanggal, relasi `claims()`, scope `publik()`, aturan `sudahDiklaim()` |
| `aplikasi/app/Models/Claim.php` | Model klaim: `$fillable`, relasi `report()` |

Dibuat dengan:

```bash
php artisan make:model Report -m
php artisan make:model Claim -m
```

## Cuplikan migration

```php
Schema::create('claims', function (Blueprint $table) {
    $table->id();
    $table->foreignId('report_id')->constrained()->cascadeOnDelete();
    $table->string('pengklaim', 100);
    $table->text('bukti');
    $table->string('catatan', 500)->nullable();
    $table->string('status', 20)->default('Menunggu');
    $table->string('alasan', 500)->nullable();
    $table->timestamps();
});
```

Migration `reports` dibuat lebih dulu (timestamp lebih kecil) sehingga foreign key valid.

## Cuplikan model dan relasi

```php
// Report.php
public function claims(): HasMany
{
    return $this->hasMany(Claim::class);
}

// Claim.php
public function report(): BelongsTo
{
    return $this->belongsTo(Report::class);
}
```

## Menjalankan

```bash
php artisan migrate
php artisan migrate:status
```

Hasil (SQLite di laptop pengembang):

```
2026_11_21_214122_create_reports_table .. DONE
2026_11_21_214124_create_claims_table .. DONE
```

Migration dapat dijalankan ulang dari nol: `php artisan migrate:fresh --seed`.

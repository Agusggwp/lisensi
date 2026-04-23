# Paket Integrasi Web Client (Direktori Terpisah)

File di folder ini bisa langsung dipindah ke project Laravel client.

## 1. Copy file ke project client

- `app/Http/Middleware/EnsureValidLicense.php` -> `app/Http/Middleware/EnsureValidLicense.php`
- `resources/views/license/expired.blade.php` -> `resources/views/license/expired.blade.php`
- `resources/views/license/maintenance.blade.php` -> `resources/views/license/maintenance.blade.php`
- `config/license_client.php` -> `config/license_client.php` (opsional, untuk referensi)

## 2. Tambahkan env di project client

Salin isi `.env.client.example` ke `.env` client.

Template `.env.client.example` sudah full setup untuk environment client (APP, DB, SESSION, CACHE, MAIL, dan LICENSE).

Wajib disesuaikan sebelum dipakai:

- `APP_URL`
- `DB_*`
- `MAIL_*`
- `CLIENT_LICENSE_KEY`
- `CLIENT_LICENSE_SERVER_URL`
- `LICENSE_SERVER_HMAC_SECRET`

## 3. Daftarkan alias middleware

Lihat contoh di `snippets/bootstrap-app.php` lalu tambahkan ke `bootstrap/app.php` project client.
Contoh tersebut akan langsung memaksa validasi lisensi ke seluruh route web (global).

## 4. Pasang middleware pada route website client

Karena middleware sudah dipasang global, route website utama tidak perlu dibungkus manual.
Lihat contoh minimal di `snippets/routes-web.php`.

## 5. Clear cache config

```bash
php artisan optimize:clear
```

## 5.1 Wajib verifikasi middleware aktif

Pastikan middleware lisensi benar-benar aktif global di web dengan cara:

1. Cek `bootstrap/app.php` sudah ada:
	- alias `client.license`
	- `$middleware->web(append: [\App\Http\Middleware\EnsureValidLicense::class]);`
2. Jalankan:

```bash
php artisan optimize:clear
php artisan route:list
```

3. Test cepat:
	- Kosongkan `CLIENT_LICENSE_KEY` di `.env`
	- Akses website client
	- Harus langsung tampil halaman `License Expired`

## 6. Test

- Saat license valid -> website normal.
- Saat license expired/suspended/tidak valid -> otomatis blokir ke halaman expired/maintenance.

Catatan strict mode:

- `CLIENT_LICENSE_ALLOW_STALE_CACHE=false` (recommended): bila license server down, website tetap diblokir.
- `CLIENT_LICENSE_ALLOW_STALE_CACHE=true`: boleh jalan sementara pakai cache valid lama.

Rekomendasi mode paling ketat:

- `CLIENT_LICENSE_CACHE_SECONDS=0`
- `CLIENT_LICENSE_ALLOW_STALE_CACHE=false`

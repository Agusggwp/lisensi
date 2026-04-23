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

## 4. Pasang middleware pada route website client

Lihat contoh di `snippets/routes-web.php` lalu sesuaikan di `routes/web.php` project client.

## 5. Clear cache config

```bash
php artisan optimize:clear
```

## 6. Test

- Saat license valid -> website normal.
- Saat license expired/suspended/tidak valid -> otomatis blokir ke halaman expired/maintenance.

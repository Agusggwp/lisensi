# Web License Management (Laravel)

Sistem ini adalah License Server + Client License Guard berbasis Laravel.

## Fitur Utama

- Dashboard admin modern (Blade)
- CRUD client
- Generate license key otomatis
- Domain lock (1 lisensi untuk 1 domain)
- IP lock optional
- Status lisensi: `active`, `expired`, `suspended`
- Perpanjang masa aktif lisensi
- Riwayat validasi/aktivasi lisensi
- API validasi lisensi aman (HMAC + timestamp)
- Middleware client untuk auto lock website jika lisensi gagal
- Auto suspend saat expired
- Email reminder H-7 sebelum expired
- QR verification lisensi
- Multi website client support
- Admin-only panel (tidak ada user/client portal)

## Endpoint Penting

- `POST /api/v1/licenses/validate` (License validation API)
- `GET /admin/login` (Login admin)
- `GET /admin/dashboard` (Dashboard lisensi)
- `GET /client` (Contoh halaman client yang dilindungi middleware lisensi)
- `GET /license/verify/{licenseKey}` (Halaman verifikasi QR)

## Setup

1. Install dependency:

```bash
composer install
```

2. Siapkan `.env`:

```bash
copy .env.example .env
php artisan key:generate
```

3. Atur database MySQL di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=license_server
DB_USERNAME=root
DB_PASSWORD=
```

4. Jalankan migrasi + seeder:

```bash
php artisan migrate --seed
```

5. Jalankan server:

```bash
php artisan serve
```

## Akun Admin Default

Diambil dari `.env`:

- `ADMIN_EMAIL` (default: `admin@license.local`)
- `ADMIN_PASSWORD` (default: `admin12345`)

## Konfigurasi Lisensi Client

Konfigurasi wajib di `.env` client:

```env
CLIENT_LICENSE_KEY=LIC-XXXX-XXXX
CLIENT_LICENSE_SERVER_URL=http://127.0.0.1:8000
CLIENT_LICENSE_CHECK_PATH=/api/v1/licenses/validate
CLIENT_LICENSE_FAIL_MODE=expired
CLIENT_LICENSE_IP_LOCK=false
LICENSE_SERVER_HMAC_SECRET=change-this-secret
```

Middleware `client.license` akan memblokir akses jika lisensi invalid/expired/suspended.

## Queue, Cron, Email

- Queue digunakan untuk pengiriman email reminder (`MAIL` + `QUEUE_CONNECTION`).
- Command sinkronisasi lisensi:

```bash
php artisan licenses:sync-status
```

- Scheduler otomatis (setiap hari 01:00) sudah didaftarkan di `routes/console.php`.

Tambahkan cron Linux:

```bash
* * * * * php /path-to-project/artisan schedule:run >> /dev/null 2>&1
```

## Catatan Admin-Only

Sistem ini hanya memakai akun admin untuk mengelola semua client dan lisensi.
Tidak ada role user lain pada panel.

# lisensi

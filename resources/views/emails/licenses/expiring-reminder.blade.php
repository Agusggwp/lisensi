<x-mail::message>
# Reminder Perpanjangan License

Halo {{ $client?->name ?? 'Client' }},

License berikut akan expired dalam waktu dekat:

- Nama License: {{ $license->name }}
- Domain: {{ $license->domain }}
- Expired At: {{ optional($license->expires_at)->format('d M Y H:i') }}

Silakan lakukan perpanjangan license agar website tetap aktif.

<x-mail::button :url="config('app.url').'/admin/licenses'">
Kelola License
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Verification</title>
    <style>
        body { margin:0; font-family:"Segoe UI",sans-serif; background:#f8fafc; color:#0f172a; display:grid; place-items:center; min-height:100vh; }
        .card { width:min(620px,94vw); background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:24px; }
        .ok { color:#047857; }
        .bad { color:#b91c1c; }
    </style>
</head>
<body>
<div class="card">
    <h1>License Verification</h1>
    @if($license)
        <p class="ok">License ditemukan dan terdaftar.</p>
        <p><strong>License Key:</strong> {{ $license->license_key }}</p>
        <p><strong>Client:</strong> {{ $license->client->name ?? '-' }}</p>
        <p><strong>Domain:</strong> {{ $license->domain }}</p>
        <p><strong>Status:</strong> {{ strtoupper($license->status) }}</p>
        <p><strong>Expired At:</strong> {{ optional($license->expires_at)->format('d M Y H:i') }}</p>
    @else
        <p class="bad">License tidak ditemukan.</p>
    @endif
</div>
</body>
</html>

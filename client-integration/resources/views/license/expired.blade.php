<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Expired</title>
    <style>
        body { margin:0; min-height:100vh; display:grid; place-items:center; font-family:"Segoe UI",sans-serif;
               background: radial-gradient(circle at 50% 0%, #fee2e2, #fff7ed 40%, #fafaf9); color:#7f1d1d; }
        .card { background:#fff; border:1px solid #fecaca; border-radius:18px; padding:28px; width:min(540px,92vw); text-align:center; }
        h1 { margin-top:0; font-size:2rem; }
    </style>
</head>
<body>
<div class="card">
    <h1>License Expired</h1>
    <p>{{ $message ?? 'Lisensi website Anda sudah tidak aktif. Silakan hubungi administrator.' }}</p>
</div>
</body>
</html>

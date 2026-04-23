<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance License</title>
    <style>
        body { margin:0; min-height:100vh; display:grid; place-items:center; font-family:"Segoe UI",sans-serif;
               background: radial-gradient(circle at 10% 10%, #ccfbf1, #f0fdfa 35%, #f8fafc); color:#0f766e; }
        .card { background:#fff; border:1px solid #99f6e4; border-radius:18px; padding:28px; width:min(540px,92vw); text-align:center; }
        h1 { margin-top:0; font-size:2rem; }
    </style>
</head>
<body>
<div class="card">
    <h1>Maintenance Mode</h1>
    <p>{{ $message ?? 'Website dinonaktifkan sementara karena lisensi tidak valid.' }}</p>
</div>
</body>
</html>

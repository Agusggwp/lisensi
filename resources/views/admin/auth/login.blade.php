<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin License</title>
    <style>
        :root { --bg:#0f172a; --panel:#111827; --ink:#f8fafc; --muted:#94a3b8; --acc:#14b8a6; }
        * { box-sizing:border-box; }
        body {
            margin:0; min-height:100vh; display:grid; place-items:center;
            background: radial-gradient(circle at 20% 20%, #1d4ed8 0%, #0f172a 38%), #0f172a;
            font-family:"Segoe UI", sans-serif; color:var(--ink);
        }
        .card { width:min(430px, 92vw); background:rgba(17,24,39,.9); border:1px solid #1f2937; border-radius:18px; padding:26px; }
        h1 { margin:0 0 8px; }
        p { margin:0 0 20px; color:var(--muted); }
        input { width:100%; padding:12px; border-radius:10px; border:1px solid #334155; background:#0b1220; color:#fff; margin-bottom:10px; }
        button { width:100%; padding:12px; border:0; border-radius:10px; background:var(--acc); color:#042f2e; font-weight:700; cursor:pointer; }
        .err { color:#fca5a5; font-size:.9rem; margin-bottom:10px; }
    </style>
</head>
<body>
<form method="POST" action="{{ route('admin.login.submit') }}" class="card">
    @csrf
    <h1>Admin Login</h1>
    <p>Panel manajemen lisensi website client</p>

    @if ($errors->any())
        <div class="err">{{ $errors->first() }}</div>
    @endif

    <input name="email" type="email" placeholder="Email admin" required value="{{ old('email') }}">
    <input name="password" type="password" placeholder="Password" required>
    <button type="submit">Masuk</button>
</form>
</body>
</html>

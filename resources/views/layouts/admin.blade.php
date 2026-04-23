<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'License Server' }}</title>
    <style>
        :root {
            --bg: #f4f4ef;
            --panel: #ffffff;
            --ink: #1c1b1a;
            --muted: #6d665f;
            --primary: #0d9488;
            --danger: #b91c1c;
            --warning: #d97706;
            --ring: rgba(13, 148, 136, 0.25);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at 10% 0%, #fff7ed 0%, var(--bg) 40%), var(--bg);
            color: var(--ink);
        }
        .shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }
        .topbar {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .brand { font-size: 1.2rem; font-weight: 700; letter-spacing: 0.02em; }
        .nav { display: flex; gap: 8px; flex-wrap: wrap; }
        .nav a, .btn {
            border: none;
            text-decoration: none;
            background: var(--panel);
            color: var(--ink);
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid #e6e0d6;
            cursor: pointer;
        }
        .nav a.active { background: var(--primary); color: #fff; border-color: var(--primary); }
        .panel {
            background: var(--panel);
            border-radius: 16px;
            border: 1px solid #e7e3db;
            box-shadow: 0 6px 24px rgba(28, 27, 26, 0.06);
            padding: 18px;
            margin-bottom: 16px;
            animation: rise .35s ease-out both;
        }
        @keyframes rise {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .grid { display: grid; gap: 12px; }
        .grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .stat {
            background: #fcfbf8;
            border: 1px solid #ece7dc;
            border-radius: 14px;
            padding: 16px;
        }
        .stat .label { font-size: .85rem; color: var(--muted); }
        .stat .value { font-size: 1.7rem; font-weight: 700; margin-top: 6px; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.92rem;
        }
        th, td {
            text-align: left;
            padding: 10px 8px;
            border-bottom: 1px solid #efe9df;
            vertical-align: top;
        }
        th { color: var(--muted); font-weight: 600; }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ddd3c5;
            outline: none;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--ring);
        }
        .btn-primary { background: var(--primary); color: #fff; border-color: var(--primary); }
        .btn-danger { background: var(--danger); color: #fff; border-color: var(--danger); }
        .btn-warning { background: var(--warning); color: #fff; border-color: var(--warning); }
        .actions { display: flex; gap: 6px; flex-wrap: wrap; }
        .alert {
            background: #ecfeff;
            border: 1px solid #99f6e4;
            color: #115e59;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 12px;
        }
        .muted { color: var(--muted); }
        @media (max-width: 900px) {
            .grid-4, .grid-2 { grid-template-columns: 1fr; }
            .shell { padding: 14px; }
        }
    </style>
</head>
<body>
<div class="shell">
    <div class="topbar">
        <div class="brand">License Server</div>
        <nav class="nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.clients.index') }}" class="{{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">Client</a>
            <a href="{{ route('admin.licenses.index') }}" class="{{ request()->routeIs('admin.licenses.*') ? 'active' : '' }}">License</a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="btn" type="submit">Logout</button>
            </form>
        </nav>
    </div>

    @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    @yield('content')
</div>
</body>
</html>

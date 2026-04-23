<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Website - License Protected</title>
    <style>
        body { margin:0; font-family:"Segoe UI",sans-serif; background:#020617; color:#e2e8f0; }
        .wrap { min-height:100vh; display:grid; place-items:center; padding:20px; }
        .box { width:min(780px,95vw); border:1px solid #1e293b; border-radius:22px; background:linear-gradient(150deg,#0b1120,#111827); padding:34px; }
        h1 { margin-top:0; font-size:2.1rem; }
        .ok { color:#5eead4; }
        .badge { display:inline-block; padding:6px 10px; border-radius:999px; background:#0f766e; color:#ccfbf1; font-size:.85rem; }
        .clock { margin-top: 16px; padding: 12px 14px; border: 1px solid #1e293b; border-radius: 12px; background: #030712; }
        .clock .day { color: #94a3b8; font-size: .92rem; }
        .clock .time { color: #e2e8f0; font-size: 1.35rem; font-weight: 700; margin-top: 4px; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="box">
        <span class="badge">License Verified</span>
        <h1>Website Client Berjalan Normal</h1>
        <p class="ok">Halaman ini hanya bisa diakses jika validasi lisensi sukses ke License Server.</p>
        <p>Jika lisensi expired/suspend/tidak valid, otomatis akan dipindahkan ke halaman blokir lisensi.</p>
        <div class="clock" aria-live="polite">
            <div class="day" id="client-mk-day">-</div>
            <div class="time" id="client-mk-time">-</div>
        </div>
    </div>
</div>
<script>
    (function () {
        const dayEl = document.getElementById('client-mk-day');
        const timeEl = document.getElementById('client-mk-time');
        const tz = 'Asia/Makassar';

        function tick() {
            const now = new Date();
            const day = new Intl.DateTimeFormat('id-ID', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                timeZone: tz,
            }).format(now);

            const time = new Intl.DateTimeFormat('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZone: tz,
                hour12: false,
            }).format(now);

            dayEl.textContent = day + ' (WITA)';
            timeEl.textContent = time;
        }

        tick();
        setInterval(tick, 1000);
    })();
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTDEVATA - License Expired</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        :root {
            --primary: #0f3d2e;
            --secondary: #14532d;
            --accent: #16a34a;
            --soft: #dcfce7;
            --danger: #dc2626;
            --danger-soft: #fee2e2;
            --text: #0f172a;
            --muted: #475569;
            --white: rgba(255,255,255,0.88);
            --shadow: rgba(15, 61, 46, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background:
                radial-gradient(circle at top left, #dcfce7 0%, transparent 40%),
                radial-gradient(circle at bottom right, #bbf7d0 0%, transparent 35%),
                linear-gradient(135deg, #f0fdf4, #ffffff);
            padding: 20px;
            overflow: hidden;
            position: relative;
        }

        .blur-circle {
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(34, 197, 94, 0.15);
            filter: blur(80px);
            z-index: 0;
            animation: floating 8s ease-in-out infinite alternate;
        }

        .blur-circle.one {
            top: -100px;
            left: -100px;
        }

        .blur-circle.two {
            bottom: -120px;
            right: -100px;
            animation-duration: 10s;
        }

        .card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 700px;
            background: var(--white);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255,255,255,0.7);
            border-radius: 28px;
            padding: 45px;
            box-shadow: 0 20px 60px -20px var(--shadow);
            animation: fadeUp 0.7s ease;
        }

        .brand {
            display: inline-block;
            background: var(--soft);
            color: var(--primary);
            padding: 8px 18px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .icon-box {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--danger), #991b1b);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            color: white;
            font-weight: bold;
            box-shadow: 0 20px 35px -18px rgba(220, 38, 38, 0.5);
            margin-bottom: 20px;
        }

        h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            color: var(--text);
            line-height: 1.2;
            margin-bottom: 12px;
            font-weight: 800;
        }

        .subtitle {
            font-size: 1.05rem;
            color: var(--muted);
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .alert-box {
            background: #ffffff;
            border: 1px solid var(--danger-soft);
            border-left: 5px solid var(--danger);
            padding: 18px;
            border-radius: 16px;
            margin-bottom: 30px;
            color: #7f1d1d;
            font-size: 15px;
            line-height: 1.7;
        }

        .highlight {
            color: var(--primary);
            font-weight: 700;
        }

        .actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 22px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: 0.25s ease;
            border: none;
            cursor: pointer;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 15px 30px -18px rgba(15, 61, 46, 0.6);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 1px solid #bbf7d0;
        }

        .btn-secondary:hover {
            box-shadow: 0 0 0 5px rgba(34, 197, 94, 0.08);
        }

        .footer {
            margin-top: 35px;
            font-size: 14px;
            color: #64748b;
            text-align: center;
        }

        .footer strong {
            color: var(--primary);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes floating {
            from {
                transform: translateY(0px) translateX(0px);
            }
            to {
                transform: translateY(30px) translateX(-20px);
            }
        }

        @media (max-width: 768px) {
            .card {
                padding: 28px;
                border-radius: 22px;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="blur-circle one"></div>
    <div class="blur-circle two"></div>

    <div class="card">

        <div class="brand">ARTDEVATA SYSTEM</div>

        <div class="icon-box">
            !
        </div>

        <h1>License Expired</h1>

        <p class="subtitle">
            Sistem website Anda saat ini dinonaktifkan karena masa lisensi telah berakhir.
            Untuk mengembalikan akses penuh, silakan lakukan aktivasi ulang melalui tim support
            <span class="highlight">ARTDEVATA</span>.
        </p>

        <div class="alert-box">
            {{ $message ?? 'Akses website dibatasi secara otomatis untuk menjaga keamanan sistem dan mencegah penggunaan tanpa lisensi aktif. Silakan hubungi administrator ARTDEVATA untuk proses perpanjangan lisensi.' }}
        </div>

        <div class="actions">
            <button class="btn btn-primary" onclick="window.location.reload()">
                Coba Lagi
            </button>

            <!-- GANTI NOMOR DI BAWAH DENGAN NOMOR WHATSAPP ANDA -->
            <a href="https://wa.me/087777378613" class="btn btn-secondary" target="_blank">
                Hubungi Admin via WhatsApp
            </a>
        </div>

        <div class="footer">
            Powered by <strong>ARTDEVATA</strong> • Smart Digital Solution
        </div>

    </div>

</body>
</html>
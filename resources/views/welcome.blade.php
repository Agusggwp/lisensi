<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - License Server</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .welcome-container {
            background: white;
            padding: 60px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
        }
        .welcome-header h1 {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        .welcome-header p {
            color: #999;
            font-size: 16px;
            margin-bottom: 30px;
        }
        .btn-welcome {
            padding: 12px 30px;
            margin: 10px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            transition: transform 0.2s;
        }
        .btn-welcome:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-header">
            <h1>🔐 License Server</h1>
            <p>Sistem Manajemen Lisensi Website Terpercaya</p>
            <p style="font-size: 14px; color: #666; margin: 20px 0;">
                Platform lengkap untuk mengelola lisensi website Anda dengan mudah. 
                Otomasi, monitoring real-time, dan integrasi pembayaran dalam satu tempat.
            </p>
        </div>

        <div>
            <a href="{{ route('login') }}" class="btn btn-welcome" 
               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 40px; display: inline-block;">
                Masuk ke Admin Panel
            </a>
        </div>

        <hr style="margin: 30px 0;">

        <div style="text-align: left; margin-top: 30px;">
            <h5 style="color: #333; margin-bottom: 20px;">Fitur Unggulan:</h5>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 10px 0; color: #666;">
                    ✅ Manajemen Client yang mudah
                </li>
                <li style="margin: 10px 0; color: #666;">
                    ✅ API License Checker yang aman
                </li>
                <li style="margin: 10px 0; color: #666;">
                    ✅ Sistem auto-expired otomatis
                </li>
                <li style="margin: 10px 0; color: #666;">
                    ✅ Email reminder otomatis
                </li>
                <li style="margin: 10px 0; color: #666;">
                    ✅ Integrasi Midtrans untuk pembayaran
                </li>
                <li style="margin: 10px 0; color: #666;">
                    ✅ Dashboard admin lengkap
                </li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

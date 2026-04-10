<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Averia+Sans+Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Dancing+Script:wght@400..700&family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Consolas', 'Courier New', monospace;
            background: #ffffff;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            padding: 0 40px 50px;
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
        }

        .hero {
            position: relative;
            height: 420px;
            border-radius: 24px;
            overflow: hidden;
            margin-bottom: 60px;
            margin-top: 60px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.75) 0%, rgba(30, 64, 175, 0.65) 100%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 40px;
            color: white;
        }

        .hero h1 {
            font-size: 52px;
            font-weight: 900;
            letter-spacing: -1px;
            margin-bottom: 16px;
            line-height: 1.1;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .hero p {
            font-size: 20px;
            max-width: 700px;
            margin: 0 auto 32px;
            line-height: 1.6;
            opacity: 0.7;
            font-size: 1em;
            font-family: Poppins, sans-serif;
        }

        .cta-buttons {
            display: flex;
            gap: 28px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 16px 36px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-primary:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-4px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-4px);
        }

        .btn img {
            width: 24px;
            filter: brightness(0) invert(1);
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 36px;
            margin-top: 20px;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            border: 1px solid #f1f5f9;
            transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
        }

        .feature-card:hover {
            border-color: #dbeafe;
            transform: translateY(-8px);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12);
        }

        .feature-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            border-radius: 18px;
        }

        .feature-icon img {
            width: 36px;
            opacity: 0.9;
        }

        .feature-card h3 {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 16px;
            color: #0f172a;
        }

        .feature-card p {
            color: #64748b;
            font-size: 15px;
            line-height: 1.65;
        }

        footer {
            margin-top: auto;
        }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <main>
        <div class="hero" style="background: url('https://ap-southeast-2-seek-apac.graphassets.com/AEzBCRO50TYyqbV6XzRDQz/zxIpya5QSGao4qVtg9WS') center/cover no-repeat;">
            <div class="hero-content">
                <h1>LaporPak!</h1>
                <p>Laporin aja masalah di lingkunganmu dari jalan rusak, sampah, lampu mati, atau fasilitas umum lainnya. Setiap suara membawa perubahan nyata.</p>
                <div class="cta-buttons">
                    <a href="adu.php" class="btn btn-primary">
                        <img src="https://cdn-icons-png.flaticon.com/128/10748/10748869.png" alt="">
                        Ajukan Pengaduan
                    </a>
                    <a href="cek-status.php" class="btn btn-secondary">
                        <img src="https://cdn-icons-png.flaticon.com/128/154/154379.png" alt="">
                        Cek Status Laporan
                    </a>
                </div>
            </div>
        </div>

        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <img src="https://cdn-icons-png.flaticon.com/128/16180/16180178.png" alt="">
                </div>
                <h3>Tanpa Login</h3>
                <p>Tidak perlu daftar akun. Langsung laporkan dalam 1 menit.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <img src="https://cdn-icons-png.flaticon.com/128/6356/6356470.png" alt="">
                </div>
                <h3>Lacak Status</h3>
                <p>Simpan nomor tiket untuk pantau progres penanganan kapan saja.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <img src="https://cdn-icons-png.flaticon.com/128/711/711319.png" alt="">
                </div>
                <h3>Transparan</h3>
                <p>Laporan selesai ditampilkan publik sebagai bentuk akuntabilitas.</p>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>
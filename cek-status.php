<?php
session_start();

$laporan = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nomor_tiket'])) {
    $nomor_tiket = trim($_POST['nomor_tiket']);

    require_once 'config/koneksi.php';
    $stmt = $pdo->prepare("SELECT * FROM pengaduan WHERE nomor_tiket = ?");
    $stmt->execute([$nomor_tiket]);
    $laporan = $stmt->fetch();

    if (!$laporan) {
        $error = "Nomor tiket tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cek Status Pengaduan</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Averia+Sans+Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Dancing+Script:wght@400..700&family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Averia+Sans+Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Dancing+Script:wght@400..700&family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Consolas', 'Courier New', monospace;
            background: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            padding: 50px 40px 40px;
            max-width: 760px;
            margin: 0 auto;
            width: 100%;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 36px;
            font-weight: bold;
            font-family: Open Sans, sans-serif;
            margin-bottom: 20px;
            margin-top: 25px;
            opacity: 0.8;
            color: #0f172a;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 17px;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-bottom: 12px;
            font-weight: 700;
            opacity: 0.8;
            color: #1e293b;
            font-size: 15px;
        }

        input {
            width: 100%;
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            font-family: inherit;
            font-size: 15px;
            transition: all 0.25s ease;
            background: #fafcff;
        }

        input:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .submit-btn {
            background: white;
            color: #1d4ed8;
            /* teks biru */
            border: 2px solid #1d4ed8;
            padding: 15px 30px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 17px;
            cursor: pointer;
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .submit-icon {
            width: 20px;
            filter: none;
            /* ikon biru */
            z-index: 2;
        }

        /* Blobs gooey — biru solid */
        .submit-btn__blobs {
            height: 100%;
            filter: url(#goo);
            overflow: hidden;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
        }

        .submit-btn__blobs div {
            background-color: #1d4ed8;
            /* biru solid */
            width: 34%;
            height: 100%;
            border-radius: 100%;
            position: absolute;
            transform: scale(1.4) translateY(125%) translateZ(0);
            transition: transform 0.7s ease;
        }

        .submit-btn__blobs div:nth-child(1) {
            left: -5%;
        }

        .submit-btn__blobs div:nth-child(2) {
            left: 30%;
            transition-delay: 60ms;
        }

        .submit-btn__blobs div:nth-child(3) {
            left: 66%;
            transition-delay: 25ms;
        }

        /* Hover Effect */
        .submit-btn:hover {
            background: #1d4ed8;
            /* background jadi biru */
            color: white;
            /* teks jadi putih */
            border-color: #1d4ed8;
        }

        .submit-btn:hover .submit-icon {
            filter: brightness(0) invert(1);
            /* ikon jadi putih */
        }

        .submit-btn:hover .submit-btn__blobs div {
            transform: scale(1.4) translateY(0) translateZ(0);
        }

        .c-button {
            color: #000;
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
            padding: 0.7em 1.4em;
            cursor: pointer;
            display: inline-block;
            vertical-align: middle;
            position: relative;
            z-index: 1;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .c-button--gooey {
            color: #1d4ed8;
            letter-spacing: 1px;
            border: 2px solid #1d4ed8;
            border-radius: 30px;
            position: relative;
            transition: all 700ms ease;
        }

        .c-button--gooey .c-button__blobs {
            height: 100%;
            border-radius: 30px;
            filter: url(#goo);
            overflow: hidden;
            position: absolute;
            top: 0;
            left: 0;
            bottom: -3px;
            right: -1px;
            z-index: -1;
        }

        .c-button--gooey .c-button__blobs div {
            background-color: #1d4ed8;
            width: 34%;
            height: 100%;
            border-radius: 100%;
            position: absolute;
            transform: scale(1.4) translateY(125%) translateZ(0);
            transition: all 700ms ease;
        }

        .c-button--gooey .c-button__blobs div:nth-child(1) {
            left: -5%;
        }

        .c-button--gooey .c-button__blobs div:nth-child(2) {
            left: 30%;
            transition-delay: 60ms;
        }

        .c-button--gooey .c-button__blobs div:nth-child(3) {
            left: 66%;
            transition-delay: 25ms;
        }

        .c-button--gooey:hover {
            color: #fff;
        }

        .c-button--gooey:hover .c-button__blobs div {
            transform: scale(1.4) translateY(0) translateZ(0);
        }

        .result-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .result-header {
            padding: 40px 24px 32px;
            margin: -32px -32px 24px -32px;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-radius: 20px 20px 0 0;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
            text-align: center;
            position: relative;
        }

        .result-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, #cbd5e1, transparent);
        }

        .result-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .header-icon {
            width: 55px;
            margin-top: 20px;
            height: 55px;
        }

        .result-title h1 {
            font-size: 1.6em;
            font-weight: 700;
            margin: 0;
            color: #1e293b;
        }

        .result-header p {
            color: #475569;
            max-width: 420px;
            margin: 0 auto;
            font-size: 0.95em;
            opacity: 0.9;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            padding: 32px 40px;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 13px;
            color: #94a3b8;
            font-weight: 600;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.4;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 14px;
        }

        .status-diterima {
            background: #fffbeb;
            color: #d97706;
        }

        .status-diproses {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-selesai {
            background: #dcfce7;
            color: #16a34a;
        }

        .content-section {
            padding: 32px 40px;
        }

        .content-section h3 {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .content-section h3 img {
            width: 20px;
            opacity: 0.8;
        }

        .content-text {
            color: #334155;
            line-height: 1.65;
            white-space: pre-wrap;
        }

        .foto-preview {
            max-width: 100%;
            border-radius: 14px;
            margin-top: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
            border: 1px solid #f1f5f9;
        }

        .alert-error {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            color: #c53030;
            padding: 16px;
            border-radius: 14px;
            margin-top: 20px;
            font-weight: 600;
        }

        footer {
            margin-top: auto;
        }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <main>
        <div class="page-header">
            <h1 class="page-title">Cek Status Pengaduan</h1>
            <p class="page-subtitle">Masukkan nomor tiket untuk melihat progres penanganan laporan Anda.</p>
        </div>

        <?php if ($laporan): ?>
            <div class="result-card">
                <div class="result-header">
                    <div class="result-title">
                        <img src="https://cdn-icons-png.flaticon.com/128/2541/2541984.png" alt="Rekap" class="header-icon">
                    </div>
                    <p style="text-align: center;">Detail Laporan Anda</p>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nomor Tiket</div>
                        <div class="info-value"><?= htmlspecialchars($laporan['nomor_tiket']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="status-badge status-<?= $laporan['status'] ?>">
                                <?= ucfirst($laporan['status']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal</div>
                        <div class="info-value"><?= date('d M Y H:i', strtotime($laporan['created_at'])) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Kategori</div>
                        <div class="info-value"><?= ucfirst(str_replace('_', ' ', $laporan['kategori'])) ?></div>
                    </div>
                </div>

                <div class="content-section">
                    <h3>
                        <img src="https://cdn-icons-png.flaticon.com/128/3666/3666228.png" alt="">
                        Judul Laporan
                    </h3>
                    <div class="content-text"><?= htmlspecialchars($laporan['judul']) ?></div>
                </div>

                <div class="content-section">
                    <h3>
                        <img src="https://cdn-icons-png.flaticon.com/128/2487/2487861.png" alt="">
                        Isi Laporan
                    </h3>
                    <div class="content-text"><?= htmlspecialchars($laporan['isi']) ?></div>
                </div>

                <?php if (!empty($laporan['tanggapan'])): ?>
                    <div class="content-section">
                        <h3>
                            <img src="https://cdn-icons-png.flaticon.com/128/17580/17580647.png" alt="">
                            Tanggapan Admin
                        </h3>
                        <div class="content-text"><?= htmlspecialchars($laporan['tanggapan']) ?></div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($laporan['foto'])): ?>
                    <div class="content-section">
                        <h3>
                            <img src="https://cdn-icons-png.flaticon.com/128/4904/4904233.png" alt="">
                            Foto Pendukung
                        </h3>
                        <img src="uploads/<?= htmlspecialchars($laporan['foto']) ?>" alt="Foto Laporan" class="foto-preview">
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="form-card">
                <form method="POST">
                    <div class="form-group">
                        <label for="nomor_tiket">
                            <img src="https://cdn-icons-png.flaticon.com/128/17692/17692862.png" alt="" style="width:18px; opacity:0.8; margin-right:8px; vertical-align:middle;">
                            Nomor Tiket
                        </label>
                        <input type="text" id="nomor_tiket" name="nomor_tiket" placeholder="Contoh: ADU-2026-0019" required>
                    </div>
                    <button class="c-button c-button--gooey"> Cek Status
                        <div class="c-button__blobs">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </button>
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" style="display: block; height: 0; width: 0;">
                        <defs>
                            <filter id="goo">
                                <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"></feGaussianBlur>
                                <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo"></feColorMatrix>
                                <feBlend in="SourceGraphic" in2="goo"></feBlend>
                            </filter>
                        </defs>
                    </svg>
                </form>

                <?php if ($error): ?>
                    <div class="alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>
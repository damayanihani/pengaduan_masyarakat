<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: dashboard.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM pengaduan WHERE id = ?");
$stmt->execute([$id]);
$laporan = $stmt->fetch();

if (!$laporan) {
    header("Location: dashboard.php");
    exit;
}

$pesan_sukses = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? '';
    $tanggapan = trim($_POST['tanggapan'] ?? '');

    if (!in_array($status, ['diterima', 'diproses', 'selesai'])) {
        $error = "Status tidak valid.";
    } else {
        $stmt = $pdo->prepare("UPDATE pengaduan SET status = ?, tanggapan = ? WHERE id = ?");
        $stmt->execute([$status, $tanggapan, $id]);
        $pesan_sukses = "Laporan berhasil diperbarui!";
        $stmt = $pdo->prepare("SELECT * FROM pengaduan WHERE id = ?");
        $stmt->execute([$id]);
        $laporan = $stmt->fetch();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Laporan</title>
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
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
        }

        .status-badge {
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

        .detail-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            overflow: hidden;
            margin-bottom: 32px;
        }

        .detail-header {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            padding: 32px 40px;
            border-bottom: 1px solid #f1f5f9;
        }

        .detail-title {
            font-size: 25px;
            opacity: 0.8;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .detail-meta {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .detail-tiket {
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 700;
            color: #1e293b;
            font-size: 15px;
        }

        select,
        textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            font-family: inherit;
            font-size: 15px;
            background: #fafcff;
        }

        select:focus,
        textarea:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .submit-btn {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 6px 16px rgba(29, 78, 216, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(29, 78, 216, 0.4);
        }

        .alert {
            padding: 16px;
            border-radius: 14px;
            margin-bottom: 24px;
            font-weight: 600;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fff5f5;
            color: #c53030;
            border: 1px solid #fed7d7;
        }

        footer {
            margin-top: auto;
        }
    </style>
</head>

<body>

    <?php include '../includes/header_admin.php'; ?>

    <main>
        <?php if ($pesan_sukses): ?>
            <div class="alert alert-success"><?= $pesan_sukses ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="detail-container">
            <div class="detail-header">
                <h2 class="detail-title"><?= htmlspecialchars($laporan['judul']) ?></h2>
                <div class="detail-meta">
                    <span class="detail-tiket"><?= htmlspecialchars($laporan['nomor_tiket']) ?></span>
                    <span class="status-badge status-<?= $laporan['status'] ?>">
                        <?= ucfirst($laporan['status']) ?>
                    </span>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Pelapor</div>
                    <div class="info-value"><?= htmlspecialchars($laporan['nama_pelapor']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Telepon</div>
                    <div class="info-value"><?= htmlspecialchars($laporan['telepon'] ?: '–') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Lokasi</div>
                    <div class="info-value"><?= htmlspecialchars($laporan['alamat']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Kategori</div>
                    <div class="info-value"><?= ucfirst(str_replace('_', ' ', $laporan['kategori'])) ?></div>
                </div>
            </div>

            <div class="content-section">
                <h3>
                    <img src="https://cdn-icons-png.flaticon.com/128/2487/2487861.png" alt="">
                    Isi Laporan
                </h3>
                <div class="content-text"><?= htmlspecialchars($laporan['isi']) ?></div>
            </div>

            <?php if (!empty($laporan['foto'])): ?>
                <div class="content-section">
                    <h3>
                        <img src="https://cdn-icons-png.flaticon.com/128/2991/2991131.png" alt="">
                        Foto Pendukung
                    </h3>
                    <img src="../uploads/<?= htmlspecialchars($laporan['foto']) ?>" alt="Foto Laporan" class="foto-preview">
                </div>
            <?php endif; ?>

            <?php if (!empty($laporan['tanggapan'])): ?>
                <div class="content-section">
                    <h3>
                        <img src="https://cdn-icons-png.flaticon.com/128/17580/17580647.png" alt="">
                        Tanggapan Admin
                    </h3>
                    <div class="content-text"><?= htmlspecialchars($laporan['tanggapan']) ?></div>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-card">
            <h3 style="font-size:20px; font-weight:800; margin-bottom:24px;">Perbarui Status & Tanggapan</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        <option value="diterima" <?= $laporan['status'] === 'diterima' ? 'selected' : '' ?>>Diterima</option>
                        <option value="diproses" <?= $laporan['status'] === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                        <option value="selesai" <?= $laporan['status'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tanggapan">Tanggapan (Opsional)</label>
                    <textarea name="tanggapan" id="tanggapan"><?= htmlspecialchars($laporan['tanggapan']) ?></textarea>
                </div>
                <button type="submit" class="submit-btn">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>

</body>

</html>
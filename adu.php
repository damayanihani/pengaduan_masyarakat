<?php
session_start();

// Handle form submission
$pesan_sukses = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $kategori = $_POST['kategori'] ?? '';
    $judul = trim($_POST['judul'] ?? '');
    $isi = trim($_POST['isi'] ?? '');

    if (empty($nama) || empty($alamat) || empty($kategori) || empty($judul) || empty($isi)) {
        $error = "Semua field bertanda * wajib diisi.";
    } elseif (!in_array($kategori, ['jalan_rusak', 'sampah', 'penerangan', 'keamanan', 'drainase', 'fasilitas_umum', 'lainnya'])) {
        $error = "Kategori tidak valid.";
    } else {
        $nomor_tiket = 'ADU-' . date('Y') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $foto_nama = null;

        if (!empty($_FILES['foto']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $_FILES['foto']['size'] <= 5 * 1024 * 1024) {
                $foto_nama = 'adu_' . uniqid() . '.' . $ext;
                if (!is_dir('uploads')) mkdir('uploads', 0777, true);
                move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto_nama);
            } else {
                $error = "Foto harus JPG/PNG dan maks. 5 MB.";
            }
        }

        if (!$error) {
            require_once 'config/koneksi.php';
            $stmt = $pdo->prepare("
                INSERT INTO pengaduan 
                (nomor_tiket, nama_pelapor, telepon, alamat, kategori, judul, isi, foto, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'diterima')
            ");
            $stmt->execute([$nomor_tiket, $nama, $telepon, $alamat, $kategori, $judul, $isi, $foto_nama]);
            $pesan_sukses = "Simpan nomor tiket ini untuk cek status:<br><strong>$nomor_tiket</strong>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Ajukan Pengaduan</title>
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
            color: #0f172a;
            opacity: 0.8;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 17px;
            opacity: 0.7;
        }

        .success-card {
            text-align: center;
            padding: 30px;
        }

        .success-icon {
            width: 56px;
            margin-bottom: 16px;
        }

        .success-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 12px;
            color: #0f172a;
        }

        .success-message {
            margin-bottom: 20px;
            line-height: 1.6;
            opacity: 0.6;
        }

        .btn-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 16px;
        }

        .btn-check {
            background: #1d4ed8;
            color: white;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 30px;
            font-weight: 700;
            display: inline-block;
            transition: all 0.25s ease;
            box-shadow: 0 4px 10px rgba(29, 78, 216, 0.25);
        }

        .btn-check:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
        }

        .btn-home {
            background: #f1f5f9;
            color: #334155;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 30px;
            font-weight: 700;
            display: inline-block;
            transition: all 0.25s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
            margin-left: 12px;
        }

        .btn-home:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
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
            margin-bottom: 28px;
        }

        label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            font-weight: 700;
            color: #1e293b;
            font-size: 15px;
            opacity: 0.8;
        }

        label.required::after {
            content: "*";
            color: #ef4444;
            margin-left: 4px;
        }

        label img {
            width: 18px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            font-family: inherit;
            font-size: 15px;
            transition: all 0.25s ease;
            background: #fafcff;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .select-wrapper {
            position: relative;
            width: 100%;
        }

        .select-wrapper::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid #64748b;
            pointer-events: none;
        }

        select {
            width: 100%;
            padding: 14px 48px 14px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #fafcff;
            font-family: inherit;
            font-size: 15px;
            color: #1e293b;
            appearance: none;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        select:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        select option {
            padding: 10px 16px;
            color: #1e293b;
            background: white;
        }

        textarea {
            min-height: 140px;
            resize: vertical;
        }

        .upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            padding: 32px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fafcff;
        }

        .upload-area:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .upload-icon {
            width: 56px;
            margin-bottom: 16px;
            opacity: 0.7;
        }

        .upload-text {
            color: #64748b;
            font-size: 15px;
            font-weight: 500;
        }

        #file-preview {
            margin-top: 20px;
            max-width: 220px;
            border-radius: 12px;
            display: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .submit-btn {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 17px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(29, 78, 216, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(29, 78, 216, 0.4);
        }

        .submit-btn img {
            width: 24px;
            filter: brightness(0) invert(1);
        }

        .form-error {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 18px 20px;
            border-radius: 14px;
            margin: 28px 0;
            background: #fff5f5;
            border: 1px solid #fed7d7;
            color: #c53030;
            font-weight: 600;
            font-size: 15px;
            line-height: 1.55;
            box-shadow: 0 2px 6px rgba(197, 48, 48, 0.08);
        }

        .form-error-icon {
            width: 26px;
            flex-shrink: 0;
            opacity: 0.9;
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
            <h1 class="page-title">Form Pengaduan</h1>
            <p class="page-subtitle">Laporkan masalah di sekitarmu cepat, aman, dan ditindaklanjuti.</p>
        </div>

        <?php if ($pesan_sukses): ?>
            <div class="form-card">
                <div class="success-card">
                    <img src="https://cdn-icons-png.flaticon.com/128/14090/14090371.png" alt="Sukses" class="success-icon">
                    <div class="success-title">Pengaduan Terkirim!</div>
                    <div class="success-message"><?= $pesan_sukses ?></div>
                    <div class="btn-actions">
                        <a href="home.php" class="btn-home">Kembali ke Beranda</a>
                        <a href="cek-status.php" class="btn-check">Cek Status</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="form-card">
                <form method="POST" enctype="multipart/form-data" id="pengaduan-form">
                    <div class="form-group">
                        <label class="required">
                            <img src="https://cdn-icons-png.flaticon.com/128/17877/17877291.png" alt=""> Nama Lengkap
                        </label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label>
                            <img src="https://cdn-icons-png.flaticon.com/128/733/733585.png" alt=""> Telepon
                        </label>
                        <input type="text" name="telepon" value="<?= htmlspecialchars($_POST['telepon'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label class="required">
                            <img src="https://cdn-icons-png.flaticon.com/128/2642/2642502.png" alt=""> Lokasi Kejadian
                        </label>
                        <input type="text" name="alamat" value="<?= htmlspecialchars($_POST['alamat'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="required">
                            <img src="https://cdn-icons-png.flaticon.com/128/13984/13984052.png" alt=""> Kategori
                        </label>
                        <div class="select-wrapper">
                            <select name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="jalan_rusak" <?= (($_POST['kategori'] ?? '') === 'jalan_rusak') ? 'selected' : '' ?>>Jalan Rusak</option>
                                <option value="sampah" <?= (($_POST['kategori'] ?? '') === 'sampah') ? 'selected' : '' ?>>Sampah Menumpuk</option>
                                <option value="penerangan" <?= (($_POST['kategori'] ?? '') === 'penerangan') ? 'selected' : '' ?>>Penerangan Jalan</option>
                                <option value="keamanan" <?= (($_POST['kategori'] ?? '') === 'keamanan') ? 'selected' : '' ?>>Keamanan</option>
                                <option value="drainase" <?= (($_POST['kategori'] ?? '') === 'drainase') ? 'selected' : '' ?>>Drainase / Banjir</option>
                                <option value="fasilitas_umum" <?= (($_POST['kategori'] ?? '') === 'fasilitas_umum') ? 'selected' : '' ?>>Fasilitas Umum</option>
                                <option value="lainnya" <?= (($_POST['kategori'] ?? '') === 'lainnya') ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required">
                            <img src="https://cdn-icons-png.flaticon.com/128/3666/3666228.png" alt=""> Judul Pengaduan
                        </label>
                        <input type="text" name="judul" value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="required">
                            <img src="https://cdn-icons-png.flaticon.com/128/2487/2487861.png" alt=""> Isi Pengaduan
                        </label>
                        <textarea name="isi" required><?= htmlspecialchars($_POST['isi'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>
                            <img src="https://cdn-icons-png.flaticon.com/128/2991/2991131.png" alt=""> Lampirkan Foto (Opsional)
                        </label>
                        <div class="upload-area" onclick="document.getElementById('foto').click()">
                            <img src="https://cdn-icons-png.flaticon.com/128/2716/2716054.png" alt="Upload" class="upload-icon">
                            <div class="upload-text">Klik untuk unggah foto (maks. 5 MB, JPG/PNG)</div>
                        </div>
                        <input type="file" name="foto" id="foto" accept="image/*" style="display: none;" onchange="previewImage(event)">
                        <img id="file-preview" />
                    </div>

                    <?php if ($error): ?>
                        <div class="form-error">
                            <img src="https://cdn-icons-png.flaticon.com/128/14090/14090276.png" alt="Error" class="form-error-icon">
                            <div><?= htmlspecialchars($error) ?></div>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="submit-btn">
                        <img src="https://cdn-icons-png.flaticon.com/128/7046/7046131.png" alt="">
                        Kirim Pengaduan
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('file-preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>

</body>

</html>
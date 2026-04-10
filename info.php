<?php
require_once 'config/koneksi.php';

$stmt = $pdo->prepare("
    SELECT nomor_tiket, judul, alamat, kategori, created_at 
    FROM pengaduan 
    WHERE status = 'selesai' 
    ORDER BY created_at DESC 
    LIMIT 6
");
$stmt->execute();
$laporan_selesai = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Informasi & Tim</title>
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
            background: #ffffff;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            padding: 50px 40px 50px;
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
        }

        .hero {
            position: relative;
            height: 320px;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 50px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(30, 64, 175, 0.7) 100%);
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
            font-size: 42px;
            font-weight: 900;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero p {
            font-size: 1em;
            max-width: 600px;
            line-height: 1.6;
            opacity: 0.7;
            font-family: Poppins, sans-serif;
        }

        .section {
            margin-bottom: 60px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 24px;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title img {
            width: 24px;
        }

        .search-section {
            margin-bottom: 24px;
        }

        .search-box {
            position: relative;
            display: flex;
            align-items: center;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            padding: 0 12px;
            transition: all 0.25s ease;
        }

        .search-box:focus-within {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .search-icon {
            width: 20px;
            opacity: 0.7;
            margin-right: 12px;
        }

        #searchInput {
            flex: 1;
            padding: 14px 0;
            border: none;
            outline: none;
            font-family: inherit;
            font-size: 15px;
            background: transparent;
        }

        .laporan-list {
            display: grid;
            gap: 20px;
        }

        .laporan-scroll-container {
            max-height: none;
            overflow-y: visible;
        }

        .laporan-scroll-container.has-scroll {
            max-height: 420px;
            overflow-y: auto;
            padding-right: 8px;
        }

        .laporan-scroll-container.has-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .laporan-scroll-container.has-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .laporan-scroll-container.has-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .laporan-scroll-container.has-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .laporan-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #f1f5f9;
            transition: all 0.25s ease;
        }

        .laporan-card:hover {
            border-color: #dbeafe;
            transform: translateY(-2px);
        }

        .laporan-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .laporan-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 16px;
            font-size: 14px;
            color: #64748b;
        }

        .laporan-lokasi,
        .laporan-tanggal {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .laporan-kategori {
            display: inline-block;
            background: #dcfce7;
            color: #16a34a;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 24px;
            background: white;
            border-radius: 18px;
            border: 1px solid #f1f5f9;
            text-align: center;
            color: #64748b;
        }

        .empty-icon {
            width: 64px;
            opacity: 0.7;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
        }

        .empty-state p {
            max-width: 400px;
            line-height: 1.6;
        }

        .tim-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
            gap: 24px;
        }

        .tim-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-top: 20px;
            border: 1px solid #f1f5f9;
            text-align: center;
            transition: all 0.25s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .tim-card:hover {
            border-color: #dbeafe;
            transform: translateY(-2px);
        }

        .tim-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #eff6ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-weight: bold;
            color: #1d4ed8;
            font-size: 28px;
        }

        .foto-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 16px;
            border: 2px solid #dbeafe;
        }

        .tim-nama {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 6px;
            color: #0f172a;
        }

        .tim-peran {
            color: #64748b;
            font-size: 14px;
        }

        footer {
            margin-top: auto;
        }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <main>
        <div class="hero" style="background: url('https://glints.com/id/lowongan/wp-content/uploads/2019/02/tim-yang-baik-1.jpg') center/cover no-repeat;">
            <div class="hero-content">
                <h1>Informasi & Tim</h1>
                <p>Sistem pengaduan transparan untuk masyarakat. Dikembangkan oleh tim kami demi pelayanan publik yang lebih baik.</p>
            </div>
        </div>

        <div class="section">
            <div class="search-section">
                <div class="search-box">
                    <img src="https://cdn-icons-png.flaticon.com/128/3128/3128287.png" alt="" class="search-icon">
                    <input type="text" id="searchInput" placeholder="Search Laporan">
                </div>
            </div>

            <?php if ($laporan_selesai): ?>
                <div class="laporan-scroll-container <?= count($laporan_selesai) >= 3 ? 'has-scroll' : '' ?>">
                    <div class="laporan-list">
                        <?php foreach ($laporan_selesai as $lap): ?>
                            <div class="laporan-card"
                                data-judul="<?= htmlspecialchars($lap['judul'], ENT_QUOTES, 'UTF-8') ?>"
                                data-lokasi="<?= htmlspecialchars($lap['alamat'], ENT_QUOTES, 'UTF-8') ?>"
                                data-kategori="<?= htmlspecialchars($lap['kategori'], ENT_QUOTES, 'UTF-8') ?>">
                                <div class="laporan-title"><?= htmlspecialchars($lap['judul']) ?></div>
                                <div class="laporan-meta">
                                    <div class="laporan-lokasi">
                                        <img src="https://cdn-icons-png.flaticon.com/128/2642/2642502.png" alt="" style="width:14px;">
                                        <?= htmlspecialchars($lap['alamat']) ?>
                                    </div>
                                    <div class="laporan-tanggal">
                                        <img src="https://cdn-icons-png.flaticon.com/128/7874/7874372.png" alt="" style="width:14px;">
                                        <?= date('d M Y', strtotime($lap['created_at'])) ?>
                                    </div>
                                </div>
                                <div class="laporan-kategori">
                                    <?= ucfirst(str_replace('_', ' ', $lap['kategori'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div id="noResults" class="empty-state" style="display: none;">
                    <img src="https://cdn-icons-png.flaticon.com/128/725/725302.png" alt="" class="empty-icon">
                    <h3>Tidak Ada Laporan</h3>
                    <p>Tidak ada laporan yang sesuai dengan kata kunci pencarian.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="section">
            <div class="tim-list">
                <div class="tim-card">
                    <img src="https://cdn-icons-png.flaticon.com/128/3177/3177440.png" alt="Ardi" class="foto-img">
                    <div class="tim-nama">Hani</div>
                    <div class="tim-peran">Full-Stack Developer</div>
                </div>
                <div class="tim-card">
                    <img src="https://cdn-icons-png.flaticon.com/128/3177/3177440.png" alt="Ardi" class="foto-img">
                    <div class="tim-nama">Nabila</div>
                    <div class="tim-peran">UI/UX Designer</div>
                </div>
                <div class="tim-card">
                    <img src="https://cdn-icons-png.flaticon.com/128/3177/3177440.png" alt="Ardi" class="foto-img">
                    <div class="tim-nama">Nabil</div>
                    <div class="tim-peran">Database Engineer</div>
                </div>
                <div class="tim-card">
                    <img src="https://cdn-icons-png.flaticon.com/128/3177/3177440.png" alt="Ardi" class="foto-img">
                    <div class="tim-nama">Faisal</div>
                    <div class="tim-peran">QA Tester</div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('.laporan-card').forEach(card => {
                const judul = card.getAttribute('data-judul')?.toLowerCase() || '';
                const lokasi = card.getAttribute('data-lokasi')?.toLowerCase() || '';
                const kategori = card.getAttribute('data-kategori')?.toLowerCase() || '';

                const match =
                    judul.includes(query) ||
                    lokasi.includes(query) ||
                    kategori.includes(query);

                card.style.display = match ? 'block' : 'none';
            });
        });
    </script>

</body>

</html>
<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

$total = $pdo->query("SELECT COUNT(*) FROM pengaduan")->fetchColumn();
$diterima = $pdo->query("SELECT COUNT(*) FROM pengaduan WHERE status = 'diterima'")->fetchColumn();
$diproses = $pdo->query("SELECT COUNT(*) FROM pengaduan WHERE status = 'diproses'")->fetchColumn();
$selesai = $pdo->query("SELECT COUNT(*) FROM pengaduan WHERE status = 'selesai'")->fetchColumn();

$stmt = $pdo->query("
    SELECT id, nomor_tiket, nama_pelapor, judul, kategori, status, created_at 
    FROM pengaduan 
    ORDER BY created_at DESC 
    LIMIT 6
");
$laporan_terbaru = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
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
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
        }

        .hero-header {
            padding: 40px 24px 32px;
            margin: -32px -32px 24px -32px;
            margin-top: 20px;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-radius: 20px 20px 0 0;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
            text-align: center;
            position: relative;
        }

        .hero-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, #cbd5e1, transparent);
        }

        .hero-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .header-icon {
            width: 56px;
            margin-top: 10px;
            height: 56px;
        }

        .hero-title h1 {
            font-size: 1.6em;
            font-weight: 700;
            margin: 0;
            color: #1e293b;
        }

        .hero-header p {
            color: #475569;
            max-width: 420px;
            margin: 0 auto;
            font-size: 0.95em;
            opacity: 0.9;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            text-align: center;
            transition: all 0.25s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            margin: 12px 0;
            background: linear-gradient(135deg, #1d4ed8 0%, #0ea5e9 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
        }

        .quick-actions {
            display: flex;
            gap: 20px;
            margin-bottom: 40px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            padding: 14px 24px;
            text-decoration: none;
            color: #334155;
            font-weight: 600;
            transition: all 0.25s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .action-btn:hover {
            border-color: #1d4ed8;
            background: #eff6ff;
            transform: translateY(-2px);
        }

        .action-btn img {
            width: 22px;
        }

        .section-title {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 24px;
            color: #0f172a;
        }

        .search-section {
            margin-bottom: 24px;
        }

        .search-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            width: 20px;
            opacity: 0.7;
        }

        #searchInput {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            font-family: inherit;
            font-size: 15px;
            background: #fafcff;
            transition: all 0.25s ease;
        }

        #searchInput:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
        }

        .report-scroll-container {
            max-height: none;
            overflow-y: visible;
        }

        .report-scroll-container.has-scroll {
            max-height: 420px;
            overflow-y: auto;
            padding-right: 8px;
        }

        .report-scroll-container.has-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .report-scroll-container.has-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .report-scroll-container.has-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .report-scroll-container.has-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .report-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .report-card {
            background: white;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            transition: all 0.25s ease;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 24px;
            align-items: center;
        }

        .report-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
        }

        .report-main {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
        }

        .report-item {
            display: flex;
            flex-direction: column;
        }

        .report-label {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 600;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-value {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.4;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
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

        .view-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #eff6ff;
            color: #1d4ed8;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .view-btn:hover {
            background: #dbeafe;
            transform: translateY(-2px);
        }

        .view-btn img {
            width: 18px;
        }

        .section-title {
            font-size: 22px;
            font-weight: 800;
            margin: 40px 0 24px;
            color: #0f172a;
        }

        footer {
            margin-top: auto;
        }
    </style>
</head>

<body>

    <?php include '../includes/header_admin.php'; ?>

    <main>
        <div class="hero-header">
            <div class="hero-title">
                <img src="https://cdn-icons-png.flaticon.com/128/17877/17877291.png" alt="Dashboard" class="header-icon">
            </div>
            <p style="text-align: center;">Dashboard Admin</p>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-label">TOTAL LAPORAN</div>
                <div class="stat-value"><?= $total ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">DITERIMA</div>
                <div class="stat-value"><?= $diterima ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">DIPROSES</div>
                <div class="stat-value"><?= $diproses ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">SELESAI</div>
                <div class="stat-value"><?= $selesai ?></div>
            </div>
        </div>

        <div class="quick-actions">
            <a href="dashboard.php" class="action-btn">
                <img src="https://cdn-icons-png.flaticon.com/128/2805/2805355.png" alt="">
                Refresh
            </a>
        </div>

        <?php if ($laporan_terbaru): ?>
            <div class="report-scroll-container <?= count($laporan_terbaru) >= 3 ? 'has-scroll' : '' ?>">
                <div class="report-list">
                    <div class="search-section">
                        <div class="search-box">
                            <img src="https://cdn-icons-png.flaticon.com/128/3128/3128287.png" alt="" class="search-icon">
                            <input type="text" id="searchInput" placeholder="Search Laporan">
                        </div>
                    </div>
                    <?php foreach ($laporan_terbaru as $lap): ?>
                        <div class="report-card"
                            data-tiket="<?= htmlspecialchars($lap['nomor_tiket'], ENT_QUOTES, 'UTF-8') ?>"
                            data-nama="<?= htmlspecialchars($lap['nama_pelapor'], ENT_QUOTES, 'UTF-8') ?>"
                            data-judul="<?= htmlspecialchars($lap['judul'], ENT_QUOTES, 'UTF-8') ?>"
                            data-kategori="<?= htmlspecialchars($lap['kategori'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="report-main">
                                <div class="report-item">
                                    <div class="report-label">Nomor Tiket</div>
                                    <div style="color: #1d4ed8;" class="report-value"><?= htmlspecialchars($lap['nomor_tiket']) ?></div>
                                </div>
                                <div class="report-item">
                                    <div class="report-label">Pelapor</div>
                                    <div class="report-value"><?= htmlspecialchars($lap['nama_pelapor']) ?></div>
                                </div>
                                <div class="report-item">
                                    <div class="report-label">Judul</div>
                                    <div class="report-value"><?= htmlspecialchars($lap['judul']) ?></div>
                                </div>
                                <div class="report-item">
                                    <div class="report-label">Status</div>
                                    <div class="report-value">
                                        <span class="status-badge status-<?= $lap['status'] ?>">
                                            <?= ucfirst($lap['status']) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="report-item">
                                    <div class="report-label">Tanggal</div>
                                    <div class="report-value"><?= date('d M Y', strtotime($lap['created_at'])) ?></div>
                                </div>
                            </div>
                            <a href="detail.php?id=<?= $lap['id'] ?>" class="view-btn">
                                <img src="https://cdn-icons-png.flaticon.com/128/6423/6423809.png" alt="">
                                Lihat
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div style="text-align: center; color: #94a3b8; padding: 32px; background: white; border-radius: 18px; border: 1px solid #f1f5f9;">
                Belum ada laporan.
            </div>
        <?php endif; ?>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.report-card');

            cards.forEach(card => {
                const tiket = card.getAttribute('data-tiket').toLowerCase();
                const nama = card.getAttribute('data-nama').toLowerCase();
                const judul = card.getAttribute('data-judul').toLowerCase();
                const kategori = card.getAttribute('data-kategori').toLowerCase();

                const match =
                    tiket.includes(query) ||
                    nama.includes(query) ||
                    judul.includes(query) ||
                    kategori.includes(query);

                card.style.display = match ? 'grid' : 'none';
            });
        });
    </script>
</body>

</html>
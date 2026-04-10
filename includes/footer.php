<?php
// Ambil data statistik dari database (jika tersedia)
$total_laporan = 0;
$selesai = 0;
$responsif_persen = 0;

try {
    // Cek apakah koneksi database tersedia
    if (file_exists(__DIR__ . '/../config/koneksi.php')) {
        include_once __DIR__ . '/../config/koneksi.php';
        if (isset($pdo)) {
            // Total laporan
            $stmt = $pdo->query("SELECT COUNT(*) FROM pengaduan");
            $total_laporan = (int) $stmt->fetchColumn();

            // Laporan selesai
            $stmt = $pdo->query("SELECT COUNT(*) FROM pengaduan WHERE status = 'selesai'");
            $selesai = (int) $stmt->fetchColumn();

            // Responsif = laporan yang bukan 'diterima' (sudah diproses/selesai)
            if ($total_laporan > 0) {
                $stmt = $pdo->query("SELECT COUNT(*) FROM pengaduan WHERE status IN ('diproses', 'selesai')");
                $responsif = (int) $stmt->fetchColumn();
                $responsif_persen = round(($responsif / $total_laporan) * 100);
            } else {
                $responsif_persen = 0;
            }
        }
    }
} catch (Exception $e) {
    // Jika error, biarkan angka tetap 0 (tidak crash halaman)
    $total_laporan = 0;
    $selesai = 0;
    $responsif_persen = 0;
}
?>

<style>
    .footer {
        background: white;
        border-top: 1px solid #f1f5f9;
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.1);
        padding: 36px 0 28px;
        margin-top: auto;
        font-family: 'Consolas', 'Courier New', monospace;
        color: #334155;
    }

    .footer-content {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 40px;
    }

    .footer-col {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .footer-col h3 {
        font-size: 14px;
        font-weight: bold;
        color: #64748b;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stats {
        display: flex;
        gap: 24px;
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-weight: bold;
        font-size: 18px;
        color: #1e40af;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 11px;
        color: #94a3b8;
        letter-spacing: 0.3px;
    }

    .social-icons {
        display: flex;
        gap: 16px;
    }

    .social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #f8fafc;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .social-link:hover {
        background: #eff6ff;
        transform: translateY(-1px);
    }

    .social-link img {
        width: 18px;
        opacity: 0.8;
    }

    .social-link:hover img {
        opacity: 1;
    }

    .copyright {
        color: #94a3b8;
        font-size: 12px;
        text-align: center;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid #f1f5f9;
    }
</style>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-col">
            <h3 style="margin-left: 10px">STATISTIK</h3>
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-value"><?= $total_laporan ?></div>
                    <div class="stat-label">LAPORAN</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?= $selesai ?></div>
                    <div class="stat-label">SELESAI</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?= $responsif_persen ?>%</div>
                    <div class="stat-label">RESPONSIF</div>
                </div>
            </div>
        </div>

        <div class="footer-col">
            <h3 style="margin-left: 10px">IKUTI KAMI</h3>
            <div class="social-icons">
                <a href="" target="_blank" class="social-link" title="WhatsApp">
                    <img src="https://cdn-icons-png.flaticon.com/128/4423/4423697.png" alt="WhatsApp">
                </a>
                <a href="https://instagram.com/pengaduan_resmi" target="_blank" class="social-link" title="Instagram">
                    <img src="https://cdn-icons-png.flaticon.com/128/174/174855.png" alt="Instagram">
                </a>
                <a href="mailto:info@pengaduanmasyarakat.id" class="social-link" title="Email">
                    <img src="https://cdn-icons-png.flaticon.com/128/15047/15047587.png" alt="Email">
                </a>
            </div>
        </div>
    </div>

    <div class="copyright">&copy; <?= date('Y') ?> RPL Pengaduan Masyarakat. Data pelapor dijamin kerahasiaannya.</div>
</footer>
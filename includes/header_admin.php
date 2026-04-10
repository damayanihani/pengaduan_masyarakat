<style>
    @import url('https://fonts.googleapis.com/css2?family=Averia+Sans+Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Dancing+Script:wght@400..700&family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Averia+Sans+Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Dancing+Script:wght@400..700&family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');

    html,
    body {
        margin: 0;
        padding: 0;
        width: 100%;
    }

    .navbar-user {
        background: white;
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.1);
        height: 60px;
        width: 100%;
        display: flex;
        align-items: center;
        position: sticky;
        top: 0;
        left: 0;
        z-index: 1000;
        padding: 0 40px;
        border-bottom: 1px solid #f1f5f9;
        font-family: 'Consolas', 'Courier New', monospace;
    }

    .logo-user {
        display: flex;
        align-items: center;
        gap: 14px;
        text-decoration: none;
    }

    .logo-user img {
        width: 110px;
        height: 110px;
    }

    .logo-user span {
        font-weight: bold;
        font-size: 20px;
        color: #0f172a;
        margin: 0;
        padding: 0;
        margin-left: -22px;
    }

    .nav-links-user {
        display: flex;
        gap: 32px;
        list-style: none;
        margin-left: 20cm;
    }

    .nav-links-user a {
        text-decoration: none;
        color: #334155;
        font-size: 15px;
        font-family: Open Sans, sans-serif;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 30px;
        transition: all 0.2s ease;
    }

    .nav-links-user a:hover,
    .nav-links-user a.active {
        color: #2563eb;
        background: #eff6ff;
    }
</style>

<nav class="navbar-user">
    <a href="index.php" class="logo-user">
        <img src="https://ardinurarief.github.io/logo-icon.png" alt="Pengaduan">
        <span>LaporPak!</span>
    </a>

    <ul class="nav-links-user">
        <li>
            <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
                Laporan
            </a>
        </li>
        <li>
            <a href="logout.php" class="logout">
                <img src="https://cdn-icons-png.flaticon.com/128/9650/9650243.png" alt="" class="nav-icon" style="width: 20px; height: 20px; opacity: 0.85; margin-bottom: -4px;">
                Keluar
            </a>
        </li>
    </ul>
</nav>
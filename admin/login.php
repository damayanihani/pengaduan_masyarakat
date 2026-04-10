<?php
session_start();
require_once '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, nama, password FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nama'] = $admin['nama'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}

$success = isset($_GET['registered']) ? "Akun admin berhasil dibuat. Silakan login." : "";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Consolas', 'Courier New', monospace;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 48px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            width: 100%;
            max-width: 520px;
        }

        .card-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .card-header h2 {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 12px;
        }

        .card-header h2 img {
            width: 36px;
        }

        .card-header p {
            color: #64748b;
            font-size: 15px;
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
            opacity: 0.8;
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

        .btn {
            width: 100%;
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 6px 16px rgba(29, 78, 216, 0.3);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(29, 78, 216, 0.4);
        }

        .btn img {
            width: 22px;
            filter: brightness(0) invert(1);
        }

        .alert {
            padding: 16px;
            border-radius: 14px;
            margin-bottom: 24px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-error {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            color: #c53030;
        }

        .alert img {
            width: 20px;
            opacity: 0.9;
        }

        .register-link {
            display: block;
            text-align: center;
            margin-top: 24px;
            color: #1d4ed8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .register-link:hover {
            color: #1e40af;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="card-header">
            <img src="https://cdn-icons-png.flaticon.com/128/17877/17877291.png" alt="" style="width: 60px; height: 60px;">
            <p style="margin-top: 15px;">Login Admin</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <img src="https://cdn-icons-png.flaticon.com/128/8832/8832119.png" alt="">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <img src="https://cdn-icons-png.flaticon.com/128/14090/14090276.png" alt="">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">
                <img src="https://cdn-icons-png.flaticon.com/128/9217/9217728.png" alt="">
                Masuk ke Dashboard
            </button>
        </form>

        <a href="register.php" class="register-link">Buat Akun Admin Baru</a>
    </div>

</body>

</html>
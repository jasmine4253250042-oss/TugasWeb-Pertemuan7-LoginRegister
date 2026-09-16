<?php
require 'functions.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Semua field harus diisi!';
    } elseif (!isValidEmail($email)) {
        $error = 'Format email tidak valid!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif ($password !== $confirmPassword) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        $result = registerUser($name, $email, $password);
        if ($result['success']) {
            $_SESSION['message'] = $result['message'];
            header('Location: index.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Tugas Rutin 7</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Buat Akun Baru </h1>
                <p>Daftar untuk mulai menggunakan sistem</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" placeholder="John Doe" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="nama@email.com" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi password" required>
                </div>

                <button type="submit" class="btn">Daftar Sekarang</button>
            </form>

            <div class="link">
                Sudah punya akun? <a href="index.php">Login di sini</a>
            </div>
        </div>
    </div>
        
        <script src="assets/script.js"></script>
    </body>
    </html>
    
</body>
</html>
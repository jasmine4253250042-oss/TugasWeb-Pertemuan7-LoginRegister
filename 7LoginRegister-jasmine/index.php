<?php
require 'functions.php';

// Jika sudah login, langsung lempar ke dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

// Fitur Remember Me (Auto login jika ada cookie)
if (!isLoggedIn() && isset($_COOKIE['remember_me'])) {
    $email = base64_decode($_COOKIE['remember_me']);
    $users = getUsers();
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: dashboard.php');
            exit;
        }
    }
}

$error = '';
$success = '';

// Tampilkan pesan dari session (flash message)
if (isset($_SESSION['message'])) {
    $success = $_SESSION['message'];
    unset($_SESSION['message']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi!';
    } elseif (!isValidEmail($email)) {
        $error = 'Format email tidak valid!';
    } else {
        $result = loginUser($email, $password, $remember);
        if ($result['success']) {
            header('Location: dashboard.php');
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
    <title>Login - Tugas Rutin 7</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Selamat Datang! 👋</h1>
                <p>Silakan login untuk melanjutkan</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="nama@email.com" value="<?= isset($_COOKIE['remember_me']) ? base64_decode($_COOKIE['remember_me']) : '' ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember" <?= isset($_COOKIE['remember_me']) ? 'checked' : '' ?>>
                    <label for="remember">Ingat saya (Remember Me)</label>
                </div>

                <button type="submit" class="btn">Masuk</button>
            </form>

            <div class="link">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </div>
        </div>
    </div>
</body>
</html>
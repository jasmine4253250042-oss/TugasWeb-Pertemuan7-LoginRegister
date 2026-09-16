<?php
require 'functions.php';
requireLogin();

$user = getCurrentUser();
$error = '';
$success = '';

if (isset($_SESSION['message'])) {
    $success = $_SESSION['message'];
    unset($_SESSION['message']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($name)) {
        $error = 'Nama tidak boleh kosong!';
    } elseif (!empty($newPassword) && strlen($newPassword) < 6) {
        $error = 'Password baru minimal 6 karakter!';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        $result = updateProfile($user['id'], $name, $newPassword);
        if ($result['success']) {
            // Update session name jika berubah
            $_SESSION['user_name'] = $name;
            $_SESSION['message'] = $result['message'];
            header('Location: profile.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Tugas Rutin 7</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Edit Profile ⚙️</h1>
                <p>Ubah data akun kamu di sini</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST" action="" class="profile-form">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email (Tidak bisa diubah)</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled style="background: #f0f0f0; cursor: not-allowed;">
                </div>

                <hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;">
                <p style="margin-bottom: 15px; color: #666; font-size: 0.9em;">* Kosongkan jika tidak ingin mengubah password</p>

                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Minimal 6 karakter">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password Baru</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="btn">Simpan Perubahan</button>
                <a href="dashboard.php" class="btn btn-secondary" style="text-decoration: none; text-align: center; display: block;">Kembali</a>
            </form>
        </div>
    </div>
       
        <script src="assets/script.js"></script>
    </body>
    </html>
    
</body>
</html>
<?php
require 'functions.php';
requireLogin(); // Proteksi halaman

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Tugas Rutin 7</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container dashboard-container">
        <div class="dashboard-card">
            <div class="dashboard-header">
                <h1>Dashboard 🏠</h1>
                <div>
                    <a href="profile.php" class="btn btn-secondary" style="width: auto; padding: 10px 20px; text-decoration: none; display: inline-block;">Edit Profile</a>
                    <a href="logout.php" class="btn btn-danger" style="width: auto; padding: 10px 20px; text-decoration: none; display: inline-block;">Logout</a>
                </div>
            </div>

            <div class="user-info">
                <div class="user-avatar">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                </div>
                <h2>Halo, <?= htmlspecialchars($user['name']) ?>!</h2>
                <p>📧 <?= htmlspecialchars($user['email']) ?></p>
                <p> Bergabung sejak: <?= date('d M Y', strtotime($user['created_at'])) ?></p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>✅</h3>
                    <p>Status Login</p>
                </div>
                <div class="stat-card">
                    <h3>🔒</h3>
                    <p>Keamanan Terjaga</p>
                </div>
                <div class="stat-card">
                    <h3>📊</h3>
                    <p>Data JSON</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
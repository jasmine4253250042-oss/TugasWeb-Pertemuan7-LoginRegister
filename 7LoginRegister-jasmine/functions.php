<?php
session_start();

// Fungsi untuk membaca data dari JSON
function getUsers() {
    $file = 'data/users.json';
    if (!file_exists($file)) {
        return [];
    }
    $json = file_get_contents($file);
    return json_decode($json, true) ?? [];
}

// Fungsi untuk menyimpan data ke JSON
function saveUsers($users) {
    $file = 'data/users.json';
    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($file, $json);
}

// Fungsi untuk registrasi user
function registerUser($name, $email, $password) {
    $users = getUsers();
    
    // Cek apakah email sudah ada
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return ['success' => false, 'message' => 'Email sudah terdaftar!'];
        }
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Buat user baru
    $newUser = [
        'id' => uniqid(),
        'name' => htmlspecialchars($name),
        'email' => htmlspecialchars($email),
        'password' => $hashedPassword,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // Simpan ke array
    $users[] = $newUser;
    saveUsers($users);
    
    return ['success' => true, 'message' => 'Registrasi berhasil! Silakan login.'];
}

// Fungsi untuk login
function loginUser($email, $password, $remember = false) {
    $users = getUsers();
    
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                
                // Remember me (cookie 30 hari)
                if ($remember) {
                    $cookieValue = base64_encode($user['email']);
                    setcookie('remember_me', $cookieValue, time() + (30 * 24 * 60 * 60), '/');
                }
                
                return ['success' => true, 'message' => 'Login berhasil!'];
            } else {
                return ['success' => false, 'message' => 'Password salah!'];
            }
        }
    }
    
    return ['success' => false, 'message' => 'Email tidak ditemukan!'];
}

// Fungsi untuk cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fungsi untuk redirect jika belum login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}

// Fungsi untuk logout
function logoutUser() {
    session_destroy();
    setcookie('remember_me', '', time() - 3600, '/');
}

// Fungsi untuk update profile
function updateProfile($userId, $name, $newPassword = null) {
    $users = getUsers();
    
    foreach ($users as &$user) {
        if ($user['id'] === $userId) {
            $user['name'] = htmlspecialchars($name);
            if ($newPassword && !empty($newPassword)) {
                $user['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            }
            break;
        }
    }
    
    saveUsers($users);
    return ['success' => true, 'message' => 'Profile berhasil diupdate!'];
}

// Fungsi untuk mendapatkan data user yang sedang login
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $users = getUsers();
    foreach ($users as $user) {
        if ($user['id'] === $_SESSION['user_id']) {
            return $user;
        }
    }
    
    return null;
}

// Fungsi untuk validasi email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Fungsi untuk sanitasi input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
?>
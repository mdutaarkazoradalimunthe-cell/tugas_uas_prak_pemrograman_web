<?php
session_start();

if (!isset($_SESSION['id_user']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $token_hash = hash('sha256', $token);

    require_once __DIR__ . '/koneksi.php';

    $stmt = mysqli_prepare($koneksi, "SELECT id_user, expires_at FROM remember_tokens WHERE token_hash = ?");
    mysqli_stmt_bind_param($stmt, 's', $token_hash);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($row && strtotime($row['expires_at']) > time()) {
        $stmt = mysqli_prepare($koneksi, "SELECT id, nama, email FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $row['id_user']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($user) {
            $_SESSION['id_user'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];

            mysqli_query($koneksi, "DELETE FROM remember_tokens WHERE id_user = $user[id]");

            $token_baru = bin2hex(random_bytes(32));
            $token_baru_hash = hash('sha256', $token_baru);
            $expires_at = date('Y-m-d H:i:s', strtotime('+5 years'));
            $stmt = mysqli_prepare($koneksi, "INSERT INTO remember_tokens (id_user, token_hash, expires_at) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'iss', $user['id'], $token_baru_hash, $expires_at);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            setcookie('remember_token', $token_baru, time() + 157680000, '/', '', true, true);
        }
    } else {
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

if (!isset($_SESSION['id_user'])) {
    header('Location: ../login.php');
    exit;
}

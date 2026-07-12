<?php
session_start();

// Kalau sudah login, redirect ke halaman resep
if (isset($_SESSION['id_user'])) {
    header('Location: resep/index.php');
    exit;
}

// Kalau belum login, redirect ke halaman landing
header('Location: landing.php');
exit;

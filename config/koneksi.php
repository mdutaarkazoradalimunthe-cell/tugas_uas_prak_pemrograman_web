<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'resep_gizi_db';

$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Set charset ke utf8 biar nama bahan yang ada karakter unik nggak error
mysqli_set_charset($koneksi, "utf8mb4");
?>
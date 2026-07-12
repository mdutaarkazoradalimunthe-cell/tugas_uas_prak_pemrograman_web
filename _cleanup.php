<?php
require 'koneksi.php';

echo "=== CEK DATA ===\n";

// Cek max ID bahan_makanan
$result = mysqli_query($koneksi, "SELECT MAX(id) FROM bahan_makanan");
$row = mysqli_fetch_row($result);
echo "Max ID bahan_makanan: {$row[0]}\n";

// Cek total resep
$result = mysqli_query($koneksi, "SELECT COUNT(*) FROM resep");
$row = mysqli_fetch_row($result);
echo "Total resep: {$row[0]}\n";

// Cek bahan di range 1478-1530
echo "\nBahan ID >= 1478:\n";
$result = mysqli_query($koneksi, "SELECT id, nama_bahan FROM bahan_makanan WHERE id >= 1478 ORDER BY id");
while ($row = mysqli_fetch_assoc($result)) {
    echo "  {$row['id']}: {$row['nama_bahan']}\n";
}

echo "\n=== RENCANA HAPUS ===\n";
echo "Daftar bahan yang akan DIHAPUS (buatan seeder):\n";
echo "  - Semua resep + resep_bahan\n";
echo "  - Bahan ID >= 1478 (kecuali yang asli database user)\n";
echo "\nApakah ingin lanjut hapus? (y/n): ";

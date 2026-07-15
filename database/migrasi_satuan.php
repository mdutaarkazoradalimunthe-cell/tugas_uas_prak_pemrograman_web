<?php
require_once __DIR__ . '/../config/koneksi.php';

echo "=== MIGRASI SATUAN ===\n";

mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS satuan_konversi (
    id_bahan INT NOT NULL,
    satuan VARCHAR(20) NOT NULL,
    gram_per_satuan DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id_bahan, satuan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
echo "✅ CREATE TABLE satuan_konversi\n";

$r1 = mysqli_query($koneksi, "SHOW COLUMNS FROM resep_pribadi_bahan LIKE 'jumlah_asli'");
if (mysqli_num_rows($r1) == 0) {
    mysqli_query($koneksi, "ALTER TABLE resep_pribadi_bahan ADD COLUMN jumlah_asli DECIMAL(10,2) DEFAULT NULL, ADD COLUMN satuan VARCHAR(20) DEFAULT NULL");
    echo "✅ ALTER resep_pribadi_bahan (added jumlah_asli, satuan)\n";
} else {
    echo "⏭  resep_pribadi_bahan already has columns\n";
}

$r2 = mysqli_query($koneksi, "SHOW COLUMNS FROM resep_bahan LIKE 'jumlah_asli'");
if (mysqli_num_rows($r2) == 0) {
    mysqli_query($koneksi, "ALTER TABLE resep_bahan ADD COLUMN jumlah_asli DECIMAL(10,2) DEFAULT NULL, ADD COLUMN satuan VARCHAR(20) DEFAULT NULL");
    echo "✅ ALTER resep_bahan (added jumlah_asli, satuan)\n";
} else {
    echo "⏭  resep_bahan already has columns\n";
}

echo "\n=== SELESAI ===\n";

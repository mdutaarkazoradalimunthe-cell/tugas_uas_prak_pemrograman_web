<?php
// File sementara: seed data kategori resep
// Jalankan sekali saja: http://localhost/tugas_uas_prak_pemrograman_web/seed_kategori.php

require __DIR__ . '/../config/koneksi.php';

// Daftar kategori yang akan di-insert
$kategori = [
    'Makanan Pembuka (Appetizer)',
    'Makanan Utama (Main Course)',
    'Makanan Penutup (Dessert)',
    'Minuman',
    'Cemilan & Camilan',
    'Sup & Soto'
];

$berhasil = 0;
$gagal = 0;

foreach ($kategori as $nama) {
    $stmt = mysqli_prepare($koneksi, "INSERT IGNORE INTO kategori_resep (nama_kategori) VALUES (?)");
    mysqli_stmt_bind_param($stmt, 's', $nama);
    if (mysqli_stmt_execute($stmt)) {
        $berhasil++;
    } else {
        $gagal++;
    }
    mysqli_stmt_close($stmt);
}

echo "✅ Seed kategori selesai!<br>";
echo "Berhasil: $berhasil kategori<br>";
echo "Gagal: $gagal kategori<br>";
echo "<br><a href='index.php'>Lanjut ke halaman utama</a>";
?>

<?php
require_once __DIR__ . '/../config/koneksi.php';

$missing = [
    ['Daun Pandan', 1, 0.1, 0.2, 0.0],
    ['Tepung Ketan Putih', 364, 2.5, 80.0, 0.5],
    ['Air Soda', 0, 0, 0, 0],
    ['Nata De Coco', 10, 0.1, 2.0, 0.0],
    ['Kismis', 299, 3.0, 79.0, 0.5],
    ['Daun Suji', 1, 0.1, 0.2, 0.0],
    ['Es Batu', 0, 0, 0, 0],
    ['Tape Ketan Putih', 173, 3.0, 40.0, 0.5],
    ['Pisang', 89, 1.1, 23.0, 0.3],
    ['Mangga', 60, 0.8, 15.0, 0.4],
    ['Anggur', 69, 0.7, 18.0, 0.2],
    ['Stroberi', 32, 0.7, 8.0, 0.3],
    ['Sirsak', 66, 1.0, 16.8, 0.3],
    ['Melon', 34, 0.8, 8.2, 0.2],
    ['Singkong', 160, 1.4, 38.0, 0.3],
    ['Ubi Jalar', 86, 1.6, 20.0, 0.1],
    ['Roti Tawar', 265, 8.0, 49.0, 3.0],
    ['Bakso Sapi', 200, 14.0, 8.0, 12.0],
    ['Ikan Lele Segar', 120, 18.0, 0, 5.0],
    ['Susu Cair', 61, 3.3, 4.8, 3.3],
    ['Daun Bawang Merah', 32, 1.8, 7.3, 0.4],
    ['Air Kelapa', 19, 0.2, 3.7, 0.2],
    ['Selasih', 57, 3.0, 12.0, 0.5],
    ['Pasta Pandan', 100, 0, 25.0, 0],
    ['Pewarna Makanan', 0, 0, 0, 0],
];

$stmt = mysqli_prepare($koneksi, "INSERT IGNORE INTO bahan_makanan (nama_bahan, kalori_per_100g, protein_per_100g, karbohidrat_per_100g, lemak_per_100g) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sdddd", $nama, $kal, $pro, $kar, $lem);

$added = 0;
foreach ($missing as $item) {
    $nama = $item[0];
    $kal = $item[1];
    $pro = $item[2];
    $kar = $item[3];
    $lem = $item[4];
    
    // Check if exists
    $cek = mysqli_query($koneksi, "SELECT id FROM bahan_makanan WHERE nama_bahan = '$nama'");
    if (mysqli_num_rows($cek) > 0) {
        echo "Exists: $nama\n";
        continue;
    }
    
    $stmt->execute();
    $added++;
    echo "Added: $nama (ID: " . mysqli_insert_id($koneksi) . ")\n";
}

echo "\nTotal added: $added\n";
echo "Done!\n";

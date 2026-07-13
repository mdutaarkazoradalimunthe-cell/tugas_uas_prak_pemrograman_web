<?php
require 'koneksi.php';
$result = mysqli_query($koneksi, "SELECT id, nama_bahan FROM bahan_makanan ORDER BY nama_bahan");
$map = [];
while ($row = mysqli_fetch_assoc($result)) {
    $map[$row['nama_bahan']] = (int)$row['id'];
}
$json = json_encode($map, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
file_put_contents(__DIR__ . '/bahan_map.json', $json);
echo "Total: " . count($map) . " ingredients dumped to bahan_map.json\n";

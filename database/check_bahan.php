<?php
require_once __DIR__ . '/../config/koneksi.php';

$result = mysqli_query($koneksi, 'SELECT id, nama_bahan FROM bahan_makanan');
$map = [];
while ($row = mysqli_fetch_assoc($result)) {
    $map[$row['nama_bahan']] = (int)$row['id'];
}

$content = file_get_contents('seed_250_resep.php');
preg_match_all('/b\("([^"]+)"\)/', $content, $matches);

$names = array_unique($matches[1]);
sort($names);

$missing = [];
foreach ($names as $name) {
    if (!isset($map[$name])) {
        $missing[] = $name;
    }
}
echo 'Total unique b() names: ' . count($names) . "\n";
echo 'Missing (' . count($missing) . '):' . "\n";
foreach ($missing as $m) {
    echo "  \"$m\"\n";
}

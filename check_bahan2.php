<?php
require_once 'koneksi.php';

$result = mysqli_query($koneksi, 'SELECT id, nama_bahan FROM bahan_makanan');
$map = [];
while ($row = mysqli_fetch_assoc($result)) {
    $map[mb_strtolower($row['nama_bahan'])] = true;
}

$content = file_get_contents('seed_250_resep.php');
preg_match_all('/b\("([^"]+)"\)/', $content, $matches);

$names = array_unique($matches[1]);
sort($names);

$missing = [];
foreach ($names as $name) {
    $lower = mb_strtolower($name);
    if (!isset($map[$lower])) {
        $missing[] = $name;
    }
}
echo 'Total unique b() calls: ' . count($names) . "\n";
echo 'Missing: ' . count($missing) . "\n";
echo "---\n";
foreach ($missing as $m) {
    echo "  $m\n";
}

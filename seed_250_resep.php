<?php
require_once 'koneksi.php';

$success = [];
$errors = [];

// ========== LOAD BAHAN MAP ==========
$bahan_map = [];
$bahan_map_lower = [];
$result = mysqli_query($koneksi, "SELECT id, nama_bahan FROM bahan_makanan");
while ($row = mysqli_fetch_assoc($result)) {
    $bahan_map[$row['nama_bahan']] = (int)$row['id'];
    $bahan_map_lower[mb_strtolower($row['nama_bahan'])] = (int)$row['id'];
}

// Common alias map for ingredient names
$alias_map = [
    'cabai merah' => 'Cabai merah segar',
    'cabai rawit' => 'Cabai rawit segar',
    'cabai hijau' => 'Cabai hijau segar',
    'daun jeruk' => 'Daun Jeruk Segar',
    'daun salam' => 'Daun Salam',
    'vanili' => 'Vanilla Bubuk',
    'kecap manis' => 'Kecap Manis',
    'minyak goreng' => 'Minyak Goreng',
    'garam' => 'Garam',
    'gula pasir' => 'Gula Pasir',
    'gula merah' => 'Gula Merah',
    'bawang merah' => 'Bawang Merah',
    'bawang putih' => 'Bawang Putih',
    'jahe' => 'Jahe',
    'kunyit' => 'Kunyit',
    'lengkuas' => 'Lengkuas',
    'sereh' => 'Sereh',
    'daun bawang' => 'Daun Bawang',
    'seledri' => 'Seledri',
    'kemiri' => 'Kemiri',
    'ketumbar' => 'Ketumbar',
    'merica bubuk' => 'Merica Bubuk',
    'kacang mede' => 'Kacang mete/biji jambu monyet goreng',
    'kacang tanah' => 'Kacang Tanah atom',
    'kelapa' => 'Kelapa',
    'tomat' => 'Tomat',
    'timun' => 'Timun',
    'toge' => 'Toge',
    'kangkung' => 'Kangkung',
    'sawi hijau' => 'Sawi Hijau',
    'wortel' => 'Wortel',
    'kol' => 'Kol',
    'bayam' => 'Bayam',
    'buncis' => 'Buncis',
    'kacang panjang' => 'Kacang Panjang',
    'labu siam' => 'Labu Siam',
    'terong' => 'Terong',
    'jagung manis' => 'Jagung Manis',
    'nasi putih' => 'Nasi Putih',
    'tempe' => 'Tempe',
    'tahu putih' => 'Tahu Putih',
    'daging ayam' => 'Ayam',
    'ikan tuna' => 'Ikan Tuna',
    'ikan nila' => 'Ikan Nila',
    'ikan patin' => 'Ikan Patin',
    'santan kelapa' => 'Santan Kelapa',
    'kecap asin' => 'Kecap Asin',
    'saus tiram' => 'Saus Tiram',
    'terasi' => 'Terasi',
    'asam jawa' => 'Asam Jawa',
    'jeruk nipis' => 'Jeruk Nipis',
    'cuka' => 'Cuka',
    'minyak wijen' => 'Minyak Wijen',
    'margarin' => 'Margarin',
    'mentega' => 'Mentega',
    'kerupuk' => 'Kerupuk',
    'tepung terigu' => 'Tepung Terigu',
    'tepung beras' => 'Tepung Beras',
    'tepung tapioka' => 'Tepung Tapioka',
    'tepung maizena' => 'Maizena tepung',
    'kaldu ayam' => 'Kaldu Ayam Bubuk',
    'tepung roti' => 'Tepung Roti',
    'telur ayam' => 'Telur Ayam',
    'keju cheddar' => 'Keju Cheddar',
    'susu sapi' => 'Susu Sapi',
    'susu kental manis' => 'Susu Kental Manis',
    'ikan gurame' => 'Fillet Ikan Gurame',
    'ikan kakap' => 'Ikan kakap segar',
    'ikan bawal' => 'Ikan Bawal',
    'ikan tenggiri' => 'Ikan Tenggiri',
    'ikan baronang' => 'Ikan baronang segar',
    'ikan kembung' => 'Ikan Kembung',
    'ikan bandeng' => 'Ikan Bandeng',
    'ikan cakalang' => 'Ikan cakalang segar',
    'ikan lele' => 'Ikan Lele goreng',
    'cumi-cumi' => 'Cumi-cumi segar',
    'udang' => 'Udang segar',
    'kerang hijau' => 'Kerang Hijau Segar',
    'kerang dara' => 'Kerang',
    'nangka muda' => 'Nangka Muda',
    'nanas' => 'Nanas',
    'pepaya' => 'Pepaya',
    'jambu biji' => 'Jambu Biji',
    'kemangi' => 'Daun Kemangi',
    'bawang bombay' => 'Bawang Bombay',
    'lada hitam' => 'Merica Hitam Bubuk',
    'kari bubuk' => 'Kunyit Bubuk',
    'cin cau' => 'Daun Cincau',
    'oyong' => 'Gambas (Oyong)',
    'tape singkong' => 'Singkong tape',
    'selasih' => 'Daun selasih segar',
    'sirsak' => 'Sirsak',
    'makaroni' => 'Makaroni',
    'mie bihun' => 'Mie Bihun',
    'lobak' => 'Lobak',
    'kentang' => 'Kentang',
    'saus tomat' => 'Saus Tomat',
    'mayones' => 'Mayonnaise',
    'es krim' => 'Es krim',
    'coklat bubuk' => 'Coklat bubuk',
    'pisang' => 'Pisang',
    'mangga' => 'Mangga',
    'semangka' => 'Semangka',
    'melon' => 'Melon',
    'alpukat segar' => 'Alpukat segar',
    'roti tawar' => 'Roti Tawar',
    'kelapa muda daging' => 'Kelapa Muda daging',
    'kelapa muda air' => 'Kelapa Muda air',
    'singkong' => 'Singkong',
    'ubi jalar' => 'Ubi Jalar',
    'stroberi' => 'Stroberi Segar',
    'anggur' => 'Anggur hutan segar',
    'kopi bubuk instant' => 'Kopi bubuk instant',
    'teh' => 'Teh',
    'sirup' => 'Sirup',
    'es batu' => 'Es Batu',
    'es serut' => 'Es Batu',
    'air soda' => 'Air Soda',
    'sari tebu' => 'Sari Tebu',
    'nata de coco' => 'Nata De Coco',
    'tape ketan putih' => 'Tape Ketan Putih',
    'jamur sagu' => 'Jamur sagu',
    'bakso sapi' => 'Bakso Sapi',
    'kacang tanah goreng' => 'Kacang tanah goreng',
    'kacang tanah atom' => 'Kacang Tanah atom',
    'kacang mete biji jambu monyet goreng' => 'Kacang mete/biji jambu monyet goreng',
    'kacang mete/biji jambu monyet goreng' => 'Kacang mete/biji jambu monyet goreng',
    'kacang hijau kering' => 'Kacang hijau kering',
    'kacang merah' => 'Kacang Merah',
    'kacang bogor goreng' => 'Kacang Bogor goreng',
    'vanilla bubuk' => 'Vanilla Bubuk',
    'daun kemangi' => 'Daun Kemangi',
    'daun kemangi segar' => 'Daun Kemangi Segar',
    'daun pandan' => 'Daun Pandan',
    'daun suji' => 'Daun Suji',
    'kayu manis bubuk' => 'Kayu Manis Bubuk',
    'cengkeh kering' => 'Cengkeh kering',
    'cengkeh bubuk' => 'Cengkeh Bubuk',
    'pala biji' => 'Pala biji',
    'pala bubuk' => 'Pala Bubuk',
    'tepung ketan' => 'Tepung Ketan',
    'tepung ketan putih' => 'Tepung Ketan Putih',
    'beras ketan putih' => 'Beras Ketan Putih',
    'beras ketan hitam' => 'Beras Ketan Hitam',
    'air kelapa' => 'Air Kelapa',
    'selasih' => 'Selasih',
    'yogurt' => 'Yogurt',
    'mesies' => 'Coklat bubuk',
    'pewarna merah' => 'Pewarna Makanan',
    'sambal' => 'Sambal',
    'sambal goreng' => 'Sambal',
    'daun jati' => 'Daun Pisang',
    'santan' => 'Santan Kelapa',
    'bawang goreng' => 'Bawang Goreng',
    'air' => 'Air',
    'beras' => 'Beras',
    'daun pisang' => 'Daun Pisang',
];

function b($nama) {
    global $bahan_map, $bahan_map_lower, $alias_map;
    
    // Try exact match
    if (isset($bahan_map[$nama])) {
        return $bahan_map[$nama];
    }
    
    // Try lowercase
    $lower = mb_strtolower($nama);
    if (isset($bahan_map_lower[$lower])) {
        return $bahan_map_lower[$lower];
    }
    
    // Try alias
    if (isset($alias_map[$lower])) {
        $alias = $alias_map[$lower];
        if (isset($bahan_map[$alias])) {
            return $bahan_map[$alias];
        }
        $alias_lower = mb_strtolower($alias);
        if (isset($bahan_map_lower[$alias_lower])) {
            return $bahan_map_lower[$alias_lower];
        }
    }
    
    echo "<div style='color:red;'>WARNING: Bahan '$nama' tidak ditemukan di database!</div>\n";
    return 0;
}

// ========== GET KATEGORI MAP ==========
$kategori_map = [];
$kat_result = mysqli_query($koneksi, "SELECT id, nama_kategori FROM kategori_resep");
while ($k = $kat_result->fetch_assoc()) {
    $kategori_map[$k['nama_kategori']] = $k['id'];
}

// ========== GET USER IDS ==========
$user_ids = [];
$user_result = mysqli_query($koneksi, "SELECT id FROM users ORDER BY id LIMIT 5 OFFSET 2");
while ($u = $user_result->fetch_assoc()) {
    $user_ids[] = (int)$u['id'];
}

function langkah($judul, $steps) {
    $text = "Sumber: https://www.google.com/search?q=resep+" . urlencode(strtolower($judul)) . "\nDiolah dari berbagai referensi resep Nusantara.\n\n";
    foreach ($steps as $i => $step) {
        $text .= ($i + 1) . ". " . $step . "\n";
    }
    return $text;
}

function deskripsi($judul) {
    return "Resep $judul khas Nusantara. Cocok untuk hidangan sehari-hari keluarga.";
}

// ========== RECIPE DATA ==========
$resep_list = [];

// ============================================================
// KATEGORI 1: APPETIZER (25)
// ============================================================
$kat = 'Makanan Pembuka';
$resep_list[] = ['Lumpia Semarang', $kat, [
    [b('Tepung Terigu'), 50], [b('Telur Ayam'), 25], [b('Daging Ayam'), 50],
    [b('Rebung'), 30], [b('Bawang Putih'), 5], [b('Minyak Goreng'), 10],
    [b('Daun Bawang'), 5], [b('Gula Pasir'), 3], [b('Garam'), 1],
], langkah('Lumpia Semarang', [
    'Campur tepung terigu, telur, dan air hingga adonan kental untuk kulit lumpia.',
    'Panaskan wajan anti lengket, buat kulit lumpia tipis-tipis hingga habis.',
    'Tumis bawang putih hingga harum, masukkan daging ayam cincang dan rebung.',
    'Tambahkan daun bawang, gula pasir, dan garam. Aduk rata, masak hingga matang.',
    'Ambil selembar kulit lumpia, isi dengan tumisan ayam rebung.',
    'Lipat kulit lumpia seperti amplop, rekatkan dengan sisa adonan tepung.',
    'Goreng lumpia dalam minyak panas hingga kuning kecoklatan.',
    'Tiriskan dan sajikan dengan saus sambal atau cabai rawit hijau.',
    'Lumpia Semarang siap dinikmati selagi hangat.',
    'Hidangkan sebagai camilan atau lauk pendamping nasi.',
])];

$resep_list[] = ['Lumpia Basah', $kat, [
    [b('Tepung Terigu'), 50], [b('Telur Ayam'), 25], [b('Daging Ayam'), 40],
    [b('Toge'), 30], [b('Bawang Putih'), 5], [b('Gula Merah'), 10],
    [b('Kacang Tanah'), 20], [b('Minyak Goreng'), 8], [b('Daun Bawang'), 5],
], langkah('Lumpia Basah', [
    'Buat adonan kulit dari tepung terigu dan telur, masak di wajan anti lengket tipis-tipis.',
    'Tumis bawang putih, masukkan daging ayam cincang hingga berubah warna.',
    'Tambahkan toge dan daun bawang, aduk sebentar, angkat.',
    'Haluskan kacang tanah goreng dengan gula merah untuk saus.',
    'Ambil kulit lumpia, beri isian tumisan ayam toge.',
    'Lipat lumpia seperti amplop tanpa digoreng.',
    'Siram dengan saus kacang di atasnya.',
    'Taburi dengan bawang goreng sebagai pelengkap.',
    'Sajikan selagi kulit masih lembut.',
])];

$resep_list[] = ['Lumpia Goreng Isi Sayur', $kat, [
    [b('Tepung Terigu'), 60], [b('Wortel'), 30], [b('Kol'), 20], [b('Toge'), 20],
    [b('Bawang Putih'), 5], [b('Minyak Goreng'), 12], [b('Garam'), 1], [b('Daun Bawang'), 5],
], langkah('Lumpia Goreng Isi Sayur', [
    'Buat adonan kulit dari tepung terigu, air, dan sedikit garam hingga kental.',
    'Masak adonan di wajan anti lengket menjadi lembaran tipis.',
    'Iris wortel dan kol tipis memanjang.',
    'Tumis bawang putih hingga harum, masukkan wortel, kol, dan toge.',
    'Tambahkan garam dan daun bawang, masak hingga sayuran layu.',
    'Ambil kulit lumpia, isi dengan tumisan sayur, lipat rapi.',
    'Rekatkan ujung lumpia dengan larutan tepung.',
    'Goreng dalam minyak panas hingga kuning keemasan.',
    'Tiriskan dan sajikan dengan saus sambal.',
])];

$resep_list[] = ['Pastel Tutup', $kat, [
    [b('Tepung Terigu'), 100], [b('Margarin'), 30], [b('Telur Ayam'), 25],
    [b('Daging Ayam'), 50], [b('Wortel'), 20], [b('Kentang'), 30],
    [b('Bawang Putih'), 5], [b('Daun Bawang'), 5], [b('Minyak Goreng'), 8],
], langkah('Pastel Tutup', [
    'Rebus kentang hingga empuk, haluskan.',
    'Tumis bawang putih dan daging ayam cincang hingga matang.',
    'Campur kentang halus dengan tumisan ayam, tambahkan wortel serut dan daun bawang.',
    'Buat adonan kulit dari tepung terigu, margarin, telur, dan sedikit air.',
    'Uleni hingga kalis, gilas tipis, cetak bundar.',
    'Isi adonan kulit dengan campuran kentang ayam, lipat setengah lingkaran.',
    'Tekan tepi pastel dengan garpu untuk merekatkan.',
    'Goreng pastel dalam minyak panas hingga kuning kecoklatan.',
    'Angkat dan tiriskan. Sajikan dengan cabai rawit hijau.',
])];

$resep_list[] = ['Pastel Isi Daging', $kat, [
    [b('Tepung Terigu'), 100], [b('Margarin'), 25], [b('Telur Ayam'), 25],
    [b('Daging Sapi'), 60], [b('Wortel'), 20], [b('Buncis'), 15],
    [b('Bawang Putih'), 5], [b('Merica Bubuk'), 1], [b('Minyak Goreng'), 12],
], langkah('Pastel Isi Daging', [
    'Buat kulit pastel: campur tepung terigu, margarin, telur, dan air hangat.',
    'Uleni adonan hingga kalis, diamkan 15 menit.',
    'Tumis bawang putih cincang hingga harum, masukkan daging sapi cincang.',
    'Tambahkan wortel dan buncis yang dipotong dadu kecil.',
    'Beri merica bubuk dan garam, masak hingga sayuran empuk.',
    'Ambil adonan kulit, gilas tipis, cetak bundar.',
    'Isi dengan tumisan daging, lipat setengah lingkaran, tekan tepi dengan garpu.',
    'Goreng pastel dalam minyak panas sedang hingga kuning keemasan.',
    'Tiriskan minyak berlebih, sajikan hangat.',
])];

$resep_list[] = ['Risoles Mayo', $kat, [
    [b('Tepung Terigu'), 50], [b('Telur Ayam'), 25], [b('Susu Cair'), 30],
    [b('Daging Ayam'), 40], [b('Mayones'), 20], [b('Bawang Putih'), 3],
    [b('Minyak Goreng'), 10], [b('Tepung Roti'), 30], [b('Daun Bawang'), 5],
], langkah('Risoles Mayo', [
    'Buat kulit risoles: campur tepung terigu, telur, dan susu cair hingga licin.',
    'Masak adonan di wajan anti lengket tipis-tipis hingga matang, sisihkan.',
    'Tumis bawang putih cincang, masukkan daging ayam cincang hingga matang.',
    'Campur tumisan ayam dengan mayones dan daun bawang.',
    'Ambil selembar kulit risoles, beri isian mayones ayam.',
    'Lipat amplop, celup ke kocokan telur, gulingkan di tepung roti.',
    'Goreng risoles dalam minyak panas hingga kuning kecoklatan.',
    'Tiriskan dan sajikan selagi hangat.',
    'Risoles mayo cocok sebagai camilan atau lauk pelengkap.',
])];

$resep_list[] = ['Risoles Ragout', $kat, [
    [b('Tepung Terigu'), 50], [b('Telur Ayam'), 25], [b('Susu Cair'), 50],
    [b('Daging Ayam'), 40], [b('Wortel'), 20], [b('Bawang Bombay'), 10],
    [b('Minyak Goreng'), 10], [b('Tepung Roti'), 30], [b('Margarin'), 10],
], langkah('Risoles Ragout', [
    'Buat kulit risoles dari campuran tepung, telur, dan susu cair.',
    'Panaskan margarin, tumis bawang bombay cincang hingga harum.',
    'Masukkan daging ayam cincang, masak hingga berubah warna.',
    'Tambahkan wortel serut dan susu cair, aduk hingga mengental sebagai ragout.',
    'Dinginkan ragout sebentar agar mudah diisi.',
    'Ambil kulit risoles, isi dengan ragout ayam wortel.',
    'Lipat amplop, celup telur, gulingkan di tepung roti.',
    'Goreng hingga kuning kecoklatan, angkat dan tiriskan.',
    'Sajikan hangat dengan saus sambal atau saus tomat.',
])];

$resep_list[] = ['Risoles Sayur', $kat, [
    [b('Tepung Terigu'), 50], [b('Telur Ayam'), 25], [b('Susu Cair'), 30],
    [b('Wortel'), 25], [b('Buncis'), 15], [b('Kol'), 15], [b('Bawang Putih'), 3],
    [b('Minyak Goreng'), 10], [b('Tepung Roti'), 30], [b('Daun Bawang'), 5],
], langkah('Risoles Sayur', [
    'Buat adonan kulit dari tepung terigu, telur, dan susu cair.',
    'Masak di wajan anti lengket menjadi lembaran tipis.',
    'Potong wortel, buncis, dan kol kecil-kecil.',
    'Tumis bawang putih, masukkan semua sayuran dan daun bawang.',
    'Tambahkan sedikit garam, masak hingga sayuran layu.',
    'Ambil kulit risoles, beri isian sayur, lipat amplop.',
    'Celup ke kocokan telur, balut dengan tepung roti.',
    'Goreng dalam minyak panas hingga keemasan.',
    'Sajikan hangat dengan saus kacang atau sambal.',
])];

$resep_list[] = ['Samosa Daging', $kat, [
    [b('Tepung Terigu'), 80], [b('Daging Sapi'), 60], [b('Kentang'), 50],
    [b('Bawang Bombay'), 10], [b('Bawang Putih'), 5], [b('Kari Bubuk'), 3],
    [b('Minyak Goreng'), 15], [b('Daun Bawang'), 5], [b('Garam'), 1],
], langkah('Samosa Daging', [
    'Rebus kentang hingga empuk, haluskan kasar.',
    'Tumis bawang bombay dan bawang putih cincang hingga harum.',
    'Masukkan daging sapi cincang, masak hingga berubah warna.',
    'Tambahkan kentang halus, bubuk kari, garam, dan daun bawang. Aduk rata.',
    'Buat adonan kulit dari tepung terigu, minyak, air, dan garam.',
    'Uleni hingga kalis, gilas tipis, potong segitiga.',
    'Letakkan isian di tengah segitiga, lipat membentuk segitiga samosa.',
    'Rekatkan tepi dengan air, tekan rapat.',
    'Goreng samosa dalam minyak panas hingga kuning kecoklatan.',
    'Sajikan dengan saus sambal atau chutney.',
])];

$resep_list[] = ['Pangsit Goreng Isi', $kat, [
    [b('Tepung Terigu'), 60], [b('Daging Ayam'), 50], [b('Bawang Putih'), 5],
    [b('Daun Bawang'), 5], [b('Telur Ayam'), 25], [b('Minyak Goreng'), 15],
    [b('Merica Bubuk'), 1], [b('Garam'), 1], [b('Tepung Tapioka'), 10],
], langkah('Pangsit Goreng Isi', [
    'Buat kulit pangsit dari tepung terigu, telur, dan air, uleni hingga kalis.',
    'Gilas adonan tipis-tipis, potong kotak-kotak.',
    'Campur daging ayam cincang dengan bawang putih halus, daun bawang, dan tepung tapioka.',
    'Tambahkan merica dan garam ke dalam isian, aduk rata.',
    'Ambil selembar kulit pangsit, isi dengan adonan ayam.',
    'Lipat diagonal membentuk segitiga, rekatkan tepi dengan air.',
    'Goreng pangsit dalam minyak panas hingga kuning kecoklatan.',
    'Tiriskan, sajikan dengan saus sambal.',
    'Pangsit goreng cocok untuk camilan atau pelengkap bakso.',
])];

$resep_list[] = ['Pangsit Rebus', $kat, [
    [b('Tepung Terigu'), 60], [b('Daging Ayam'), 50], [b('Bawang Putih'), 5],
    [b('Daun Bawang'), 5], [b('Telur Ayam'), 25], [b('Minyak Goreng'), 5],
    [b('Merica Bubuk'), 1], [b('Garam'), 1], [b('Tepung Tapioka'), 10],
], langkah('Pangsit Rebus', [
    'Campur tepung terigu, telur, air, dan garam, uleni hingga kalis.',
    'Gilas tipis, potong kotak untuk kulit pangsit.',
    'Campur daging ayam cincang, bawang putih halus, daun bawang, dan tepung tapioka.',
    'Beri merica dan garam, aduk hingga rata.',
    'Isi kulit pangsit dengan adonan ayam, lipat segitiga, rekatkan.',
    'Didihkan air dalam panci, rebus pangsit hingga mengapung.',
    'Angkat pangsit, tiriskan.',
    'Sajikan dengan kuah kaldu ayam dan taburan daun bawang.',
    'Pangsit rebus nikmat disantap selagi hangat.',
])];

$resep_list[] = ['Siomay Ayam', $kat, [
    [b('Daging Ayam'), 80], [b('Tepung Tapioka'), 20], [b('Telur Ayam'), 25],
    [b('Bawang Putih'), 5], [b('Daun Bawang'), 5], [b('Minyak Goreng'), 5],
    [b('Garam'), 1], [b('Merica Bubuk'), 1], [b('Wortel'), 10],
], langkah('Siomay Ayam', [
    'Haluskan daging ayam bersama bawang putih dalam food processor.',
    'Campur dengan tepung tapioka, telur, garam, dan merica.',
    'Tambahkan daun bawang iris dan wortel serut, aduk rata.',
    'Bentuk adonan bulat-bulat atau sesuai selera.',
    'Kukus siomay dalam dandang panas selama 15-20 menit hingga matang.',
    'Siapkan bumbu kacang: haluskan kacang tanah goreng dengan gula merah dan cabai.',
    'Siram siomay dengan bumbu kacang, tambahkan kecap manis.',
    'Taburi dengan bawang goreng dan jeruk nipis.',
    'Sajikan selagi hangat.',
])];

$resep_list[] = ['Siomay Ikan', $kat, [
    [b('Ikan Tuna'), 80], [b('Tepung Tapioka'), 20], [b('Telur Ayam'), 25],
    [b('Bawang Putih'), 5], [b('Daun Bawang'), 5], [b('Minyak Goreng'), 5],
    [b('Garam'), 1], [b('Merica Bubuk'), 1], [b('Labu Siam'), 30],
], langkah('Siomay Ikan', [
    'Kukus atau rebus labu siam hingga empuk, haluskan.',
    'Haluskan daging ikan tuna bersama bawang putih.',
    'Campur ikan halus dengan labu siam halus, tepung tapioka, dan telur.',
    'Beri garam dan merica, aduk rata hingga bisa dibentuk.',
    'Bentuk adonan bulat lonjong atau sesuai selera.',
    'Kukus dalam dandang panas selama 20 menit hingga matang.',
    'Haluskan kacang tanah goreng dengan cabai dan gula merah untuk saus.',
    'Siram siomay dengan saus kacang dan kecap manis.',
    'Taburi bawang goreng, sajikan hangat.',
])];

$resep_list[] = ['Batagor', $kat, [
    [b('Ikan Tuna'), 60], [b('Tepung Tapioka'), 25], [b('Telur Ayam'), 25],
    [b('Tahu Putih'), 50], [b('Bawang Putih'), 5], [b('Daun Bawang'), 5],
    [b('Minyak Goreng'), 15], [b('Garam'), 1], [b('Merica Bubuk'), 1],
], langkah('Batagor', [
    'Haluskan daging ikan tuna bersama bawang putih.',
    'Campur dengan tepung tapioka, telur, garam, dan merica.',
    'Tambahkan daun bawang iris, aduk rata.',
    'Potong tahu putih segitiga, belah tengahnya.',
    'Isi tahu dengan adonan ikan, rapikan.',
    'Kukus tahu isi dan sisa adonan dibentuk bakso selama 15 menit.',
    'Goreng tahu isi dan siomay dalam minyak panas hingga kecoklatan.',
    'Siapkan bumbu kacang: haluskan kacang tanah, cabai, gula merah.',
    'Potong-potong batagor, siram dengan bumbu kacang dan kecap.',
    'Taburi jeruk nipis dan bawang goreng, sajikan hangat.',
])];

$resep_list[] = ['Tahu Sumedang', $kat, [
    [b('Tahu Putih'), 100], [b('Bawang Putih'), 3], [b('Garam'), 1],
    [b('Minyak Goreng'), 15], [b('Cabai Rawit'), 5], [b('Kecap Manis'), 10],
], langkah('Tahu Sumedang', [
    'Potong tahu putih bentuk dadu atau segitiga.',
    'Rendam tahu dalam air garam dan bawang putih halus selama 15 menit.',
    'Panaskan minyak goreng dalam wajan.',
    'Goreng tahu dalam minyak panas hingga kulitnya kering dan renyah.',
    'Balik tahu sesekali agar matang merata.',
    'Angkat dan tiriskan minyak berlebih.',
    'Sajikan tahu sumedang dengan cabai rawit hijau dan kecap manis.',
    'Tahu sumedang enak dinikmati selagi hangat dan renyah.',
])];

$resep_list[] = ['Tahu Cabe Garam', $kat, [
    [b('Tahu Putih'), 100], [b('Cabai Rawit'), 10], [b('Bawang Putih'), 8],
    [b('Daun Bawang'), 5], [b('Minyak Goreng'), 12], [b('Garam'), 1],
    [b('Tepung Terigu'), 20], [b('Merica Bubuk'), 1],
], langkah('Tahu Cabe Garam', [
    'Potong tahu putih bentuk dadu kecil.',
    'Baluri tahu dengan campuran tepung terigu, garam, dan merica.',
    'Goreng tahu dalam minyak panas hingga renyah, angkat.',
    'Cincang kasar bawang putih dan cabai rawit.',
    'Panaskan sedikit minyak, tumis bawang putih hingga harum kekuningan.',
    'Masukkan cabai rawit dan daun bawang, aduk cepat.',
    'Masukkan tahu goreng, aduk rata dengan bumbu.',
    'Sajikan tahu cabe garam selagi hangat.',
])];

$resep_list[] = ['Tahu Crispy', $kat, [
    [b('Tahu Putih'), 100], [b('Tepung Terigu'), 30], [b('Tepung Beras'), 15],
    [b('Bawang Putih'), 3], [b('Garam'), 1], [b('Merica Bubuk'), 1],
    [b('Minyak Goreng'), 15], [b('Daun Bawang'), 5],
], langkah('Tahu Crispy', [
    'Potong tahu putih tipis-tipis atau bentuk stik.',
    'Campur tepung terigu, tepung beras, garam, merica, dan bawang putih bubuk.',
    'Buat adonan basah dengan mencampur sebagian tepung dengan air.',
    'Celupkan tahu ke adonan basah, lalu gulingkan di adonan kering.',
    'Remas-remas agar tepung menempel sempurna.',
    'Panaskan minyak, goreng tahu hingga kuning keemasan.',
    'Angkat dan tiriskan.',
    'Sajikan dengan saus sambal atau mayones.',
])];

$resep_list[] = ['Tempe Mendoan', $kat, [
    [b('Tempe'), 100], [b('Tepung Terigu'), 30], [b('Tepung Beras'), 15],
    [b('Daun Bawang'), 5], [b('Bawang Putih'), 3], [b('Kunyit'), 2],
    [b('Garam'), 1], [b('Minyak Goreng'), 12], [b('Cabai Rawit'), 5],
], langkah('Tempe Mendoan', [
    'Potong tempe tipis-tipis melebar.',
    'Campur tepung terigu, tepung beras, bawang putih halus, kunyit bubuk, dan garam.',
    'Tambahkan air dan daun bawang iris, aduk hingga adonan kental.',
    'Panaskan minyak dalam wajan.',
    'Celupkan potongan tempe ke dalam adonan tepung hingga terbalut rata.',
    'Goreng dalam minyak panas sebentar saja (tidak terlalu kering).',
    'Angkat saat tepung mulai menguning, mendoan khas setengah matang.',
    'Sajikan dengan cabai rawit hijau atau kecap pedas.',
])];

$resep_list[] = ['Cireng Isi', $kat, [
    [b('Tepung Tapioka'), 75], [b('Tepung Terigu'), 20], [b('Bawang Putih'), 5],
    [b('Daun Bawang'), 5], [b('Minyak Goreng'), 15], [b('Garam'), 1],
    [b('Merica Bubuk'), 1], [b('Telur Ayam'), 25],
], langkah('Cireng Isi', [
    'Campur tepung tapioka dan tepung terigu dengan perbandingan 3:1.',
    'Tumis bawang putih cincang dan daun bawang, masukkan telur orak-arik.',
    'Tambahkan garam dan merica untuk isian telur.',
    'Rebus sedikit air hingga mendidih, tuang ke campuran tepung sambil diaduk.',
    'Uleni adonan hingga kalis, ambil sedikit, pipihkan.',
    'Isi dengan tumisan telur, tutup rapat, bentuk bulat lonjong.',
    'Goreng cireng dalam minyak panas hingga kuning kecoklatan.',
    'Angkat dan tiriskan.',
    'Sajikan cireng isi dengan bumbu rujak atau saus sambal.',
])];

$resep_list[] = ['Cilok Bumbu Kacang', $kat, [
    [b('Tepung Tapioka'), 60], [b('Tepung Terigu'), 20], [b('Bawang Putih'), 5],
    [b('Daun Bawang'), 5], [b('Kacang Tanah'), 25], [b('Gula Merah'), 10],
    [b('Cabai Rawit'), 5], [b('Minyak Goreng'), 5], [b('Garam'), 1],
], langkah('Cilok Bumbu Kacang', [
    'Campur tepung tapioka dan tepung terigu, tambahkan bawang putih halus.',
    'Tuang air panas sedikit demi sedikit sambil diaduk, uleni hingga kalis.',
    'Tambahkan daun bawang iris, bentuk bulat-bulat kecil.',
    'Rebus cilok dalam air mendidih hingga mengapung, angkat.',
    'Haluskan kacang tanah goreng, gula merah, cabai rawit, dan garam.',
    'Tambahkan air hangat ke bumbu kacang hingga kekentalan yang diinginkan.',
    'Campur cilok dengan bumbu kacang, aduk rata.',
    'Taburi bawang goreng dan kecap manis sesuai selera.',
    'Sajikan cilok bumbu kacang selagi hangat.',
])];

$resep_list[] = ['Cilok Kuah', $kat, [
    [b('Tepung Tapioka'), 60], [b('Tepung Terigu'), 20], [b('Bawang Putih'), 5],
    [b('Daun Bawang'), 5], [b('Seledri'), 3], [b('Kaldu Ayam'), 200],
    [b('Garam'), 1], [b('Merica Bubuk'), 1], [b('Minyak Goreng'), 3],
], langkah('Cilok Kuah', [
    'Campur tepung tapioka dan terigu dengan bawang putih halus dan garam.',
    'Tuang air panas, uleni hingga kalis.',
    'Bentuk adonan menjadi bulatan-bulatan kecil.',
    'Rebus cilok dalam air mendidih hingga mengapung, tiriskan.',
    'Didihkan kaldu ayam, tambahkan merica bubuk dan garam.',
    'Masukkan cilok ke dalam kuah kaldu mendidih.',
    'Taburi daun bawang dan seledri iris.',
    'Sajikan cilok kuah selagi hangat.',
    'Tambahkan saus sambal dan kecap manis sesuai selera.',
])];

$resep_list[] = ['Pisang Goreng Keju', $kat, [
    [b('Pisang'), 100], [b('Tepung Terigu'), 30], [b('Tepung Beras'), 15],
    [b('Gula Pasir'), 10], [b('Minyak Goreng'), 12], [b('Keju Cheddar'), 15],
    [b('Susu Kental Manis'), 10],
], langkah('Pisang Goreng Keju', [
    'Kupas pisang, potong belah memanjang.',
    'Campur tepung terigu, tepung beras, gula pasir, dan air hingga adonan kental.',
    'Celupkan potongan pisang ke dalam adonan tepung.',
    'Goreng dalam minyak panas hingga kuning keemasan.',
    'Angkat dan tiriskan sebentar.',
    'Parut keju cheddar di atas pisang goreng.',
    'Kucuri dengan susu kental manis.',
    'Sajikan pisang goreng keju selagi hangat.',
])];

$resep_list[] = ['Ubi Goreng', $kat, [
    [b('Ubi Jalar'), 150], [b('Tepung Terigu'), 20], [b('Gula Pasir'), 10],
    [b('Vanili'), 1], [b('Minyak Goreng'), 12],
], langkah('Ubi Goreng', [
    'Kupas ubi jalar, cuci bersih, potong tipis memanjang atau bulat.',
    'Campur tepung terigu, gula pasir, vanili, dan sedikit air.',
    'Celupkan ubi ke adonan tepung.',
    'Panaskan minyak dalam wajan.',
    'Goreng ubi hingga matang dan kuning kecoklatan.',
    'Balik sekali agar matang merata.',
    'Angkat dan tiriskan minyak.',
    'Sajikan ubi goreng hangat sebagai camilan.',
])];

$resep_list[] = ['Singkong Goreng', $kat, [
    [b('Singkong'), 150], [b('Bawang Putih'), 3], [b('Garam'), 1],
    [b('Minyak Goreng'), 12], [b('Daun Bawang'), 5],
], langkah('Singkong Goreng', [
    'Kupas singkong, potong-potong sesuai selera.',
    'Rebus singkong dengan bawang putih halus dan garam hingga empuk.',
    'Tiriskan singkong, biarkan dingin.',
    'Panaskan minyak dalam wajan.',
    'Goreng singkong hingga kuning kecoklatan dan renyah di luar.',
    'Angkat dan tiriskan.',
    'Taburi dengan daun bawang iris atau bumbu tabur.',
    'Sajikan selagi hangat sebagai camilan.',
])];

// ============================================================
// KATEGORI 2: MAIN COURSE - AYAM (25)
// ============================================================
$kat = 'Makanan Utama';
$resep_list[] = ['Ayam Bakar Taliwang', $kat, [
    [b('Daging Ayam'), 200], [b('Cabai Merah'), 15], [b('Cabai Rawit'), 10],
    [b('Bawang Merah'), 15], [b('Bawang Putih'), 8], [b('Terasi'), 5],
    [b('Gula Merah'), 10], [b('Minyak Goreng'), 10], [b('Jeruk Nipis'), 5],
], langkah('Ayam Bakar Taliwang', [
    'Haluskan cabai merah, cabai rawit, bawang merah, bawang putih, dan terasi.',
    'Lumuri ayam dengan bumbu halus, tambahkan gula merah dan garam.',
    'Kucuri jeruk nipis, diamkan 30 menit agar bumbu meresap.',
    'Panaskan panggangan atau grill pan.',
    'Bakar ayam sambil sesekali dioles sisa bumbu.',
    'Balik ayam agar matang merata, bakar hingga kecoklatan.',
    'Angkat dan sajikan dengan lalapan dan sambal.',
    'Ayam bakar Taliwang khas Lombok siap dinikmati.',
])];

$resep_list[] = ['Ayam Betutu', $kat, [
    [b('Daging Ayam'), 200], [b('Bawang Merah'), 20], [b('Bawang Putih'), 10],
    [b('Cabai Merah'), 10], [b('Cabai Rawit'), 8], [b('Kunyit'), 5],
    [b('Jahe'), 5], [b('Lengkuas'), 5], [b('Daun Salam'), 2],
    [b('Sereh'), 5], [b('Minyak Goreng'), 8],
], langkah('Ayam Betutu', [
    'Haluskan bawang merah, bawang putih, cabai, kunyit, jahe, dan lengkuas.',
    'Tumis bumbu halus hingga harum, masukkan daun salam dan sereh geprek.',
    'Lumuri ayam dengan bumbu tumis, pijat-pijat agar meresap.',
    'Bungkus ayam dengan daun pisang.',
    'Kukus ayam dalam dandang panas selama 45 menit.',
    'Panggang sebentar ayam betutu di atas grill atau oven.',
    'Angkat dan sajikan dengan nasi hangat.',
    'Ayam betutu khas Bali siap disantap.',
])];

$resep_list[] = ['Ayam Pop', $kat, [
    [b('Daging Ayam'), 200], [b('Bawang Putih'), 8], [b('Jahe'), 5],
    [b('Kunyit'), 3], [b('Daun Salam'), 2], [b('Sereh'), 5],
    [b('Minyak Goreng'), 5], [b('Garam'), 1], [b('Air Kelapa'), 100],
], langkah('Ayam Pop', [
    'Rebus ayam dengan air kelapa, bawang putih, jahe, kunyit, dan garam.',
    'Tambahkan daun salam dan sereh geprek.',
    'Rebus hingga ayam empuk dan air menyusut.',
    'Angkat ayam, tiriskan dari kuah.',
    'Panaskan minyak, goreng ayam sebentar saja hingga kulit agak kering.',
    'Ayam pop tidak digoreng hingga coklat, cukup sebentar.',
    'Sajikan dengan sambal merah dan sayur daun singkong.',
    'Ayam pop khas Padang siap dinikmati.',
])];

$resep_list[] = ['Ayam Cincane', $kat, [
    [b('Daging Ayam'), 200], [b('Cabai Merah'), 15], [b('Bawang Merah'), 15],
    [b('Bawang Putih'), 8], [b('Jahe'), 5], [b('Kunyit'), 3],
    [b('Gula Merah'), 10], [b('Minyak Goreng'), 10], [b('Jeruk Nipis'), 5],
], langkah('Ayam Cincane', [
    'Potong ayam menjadi beberapa bagian, cuci bersih.',
    'Kucuri ayam dengan jeruk nipis, diamkan 10 menit.',
    'Haluskan cabai merah, bawang merah, bawang putih, jahe, dan kunyit.',
    'Tumis bumbu halus hingga harum, masukkan gula merah.',
    'Masukkan ayam, aduk rata dengan bumbu.',
    'Tambahkan air, masak hingga ayam empuk dan bumbu meresap.',
    'Panggang ayam di atas bara api atau grill hingga kecoklatan.',
    'Sajikan dengan nasi hangat dan lalapan.',
    'Ayam cincane khas Kalimantan Timur siap disantap.',
])];

$resep_list[] = ['Ayam Woku', $kat, [
    [b('Daging Ayam'), 200], [b('Cabai Rawit'), 15], [b('Bawang Merah'), 15],
    [b('Bawang Putih'), 8], [b('Jahe'), 5], [b('Kunyit'), 5],
    [b('Daun Jeruk'), 3], [b('Daun Bawang'), 10], [b('Minyak Goreng'), 8],
], langkah('Ayam Woku', [
    'Potong ayam kecil-kecil, cuci bersih.',
    'Haluskan cabai rawit, bawang merah, bawang putih, jahe, dan kunyit.',
    'Tumis bumbu halus hingga harum, masukkan daun jeruk.',
    'Masukkan ayam, aduk hingga berubah warna.',
    'Tambahkan air, masak hingga ayam empuk.',
    'Masukkan daun bawang iris, aduk sebentar.',
    'Koreksi rasa, angkat.',
    'Sajikan ayam woku dengan nasi hangat.',
    'Ayam woku khas Manado siap dinikmati.',
])];

$resep_list[] = ['Ayam Rica-rica', $kat, [
    [b('Daging Ayam'), 200], [b('Cabai Rawit'), 20], [b('Cabai Merah'), 10],
    [b('Bawang Merah'), 15], [b('Bawang Putih'), 8], [b('Jahe'), 5],
    [b('Daun Jeruk'), 3], [b('Sereh'), 5], [b('Minyak Goreng'), 10],
], langkah('Ayam Rica-rica', [
    'Potong ayam sesuai selera, cuci bersih.',
    'Haluskan cabai rawit, cabai merah, bawang merah, bawang putih, dan jahe.',
    'Tumis bumbu halus hingga harum.',
    'Masukkan daun jeruk dan sereh geprek, aduk.',
    'Masukkan ayam, aduk rata dengan bumbu.',
    'Tambahkan air, masak hingga ayam empuk dan bumbu meresap.',
    'Koreksi rasa, masak hingga kuah mengental.',
    'Sajikan ayam rica-rica dengan nasi hangat.',
    'Ayam rica-rica khas Manado siap disantap.',
])];

$resep_list[] = ['Ayam Bumbu Rujak', $kat, [
    [b('Daging Ayam'), 200], [b('Cabai Merah'), 15], [b('Bawang Merah'), 15],
    [b('Bawang Putih'), 8], [b('Kemiri'), 10], [b('Gula Merah'), 15],
    [b('Santan Kelapa'), 50], [b('Minyak Goreng'), 8], [b('Daun Salam'), 2],
], langkah('Ayam Bumbu Rujak', [
    'Potong ayam menjadi beberapa bagian.',
    'Haluskan cabai merah, bawang merah, bawang putih, dan kemiri.',
    'Tumis bumbu halus hingga harum, masukkan daun salam.',
    'Masukkan ayam, aduk rata dengan bumbu.',
    'Tambahkan gula merah dan santan kelapa.',
    'Masak dengan api kecil hingga ayam empuk dan bumbu meresap.',
    'Aduk sesekali agar santan tidak pecah.',
    'Koreksi rasa, angkat.',
    'Sajikan ayam bumbu rujak dengan nasi hangat.',
])];

$resep_list[] = ['Ayam Lodho', $kat, [
    [b('Daging Ayam'), 200], [b('Santan Kelapa'), 60], [b('Bawang Merah'), 15],
    [b('Bawang Putih'), 8], [b('Cabai Merah'), 10], [b('Kunyit'), 5],
    [b('Jahe'), 5], [b('Kemiri'), 8], [b('Daun Jeruk'), 3],
], langkah('Ayam Lodho', [
    'Bakar ayam sebentar hingga kulit kecoklatan.',
    'Haluskan bawang merah, bawang putih, cabai, kunyit, jahe, dan kemiri.',
    'Tumis bumbu halus hingga harum, masukkan daun jeruk.',
    'Masukkan ayam bakar, aduk rata.',
    'Tuang santan, masak dengan api kecil.',
    'Aduk perlahan agar santan tidak pecah.',
    'Masak hingga ayam empuk dan kuah mengental.',
    'Koreksi rasa, angkat.',
    'Sajikan ayam lodho khas Tulungagung dengan nasi hangat.',
])];

$resep_list[] = ['Ayam Bakakak', $kat, [
    [b('Daging Ayam'), 200], [b('Bawang Merah'), 15], [b('Bawang Putih'), 8],
    [b('Kecap Manis'), 15], [b('Cabai Rawit'), 10], [b('Minyak Goreng'), 10],
    [b('Jahe'), 5], [b('Gula Merah'), 10],
], langkah('Ayam Bakakak', [
    'Belah ayam tanpa putus, pipihkan.',
    'Haluskan bawang merah, bawang putih, jahe, dan cabai rawit.',
    'Lumuri ayam dengan bumbu halus, kecap manis, dan gula merah.',
    'Diamkan selama 30 menit agar bumbu meresap.',
    'Panaskan panggangan atau grill.',
    'Bakar ayam sambil dioles sisa bumbu.',
    'Balik ayam agar matang merata.',
    'Sajikan ayam bakakak dengan lalapan dan sambal.',
])];

$resep_list[] = ['Ayam Panggang Bumbu Kuning', $kat, [
    [b('Daging Ayam'), 200], [b('Kunyit'), 8], [b('Bawang Putih'), 8],
    [b('Kemiri'), 8], [b('Jahe'), 5], [b('Daun Salam'), 2],
    [b('Sereh'), 5], [b('Minyak Goreng'), 8], [b('Garam'), 1],
], langkah('Ayam Panggang Bumbu Kuning', [
    'Haluskan kunyit, bawang putih, kemiri, jahe, dan garam.',
    'Lumuri ayam dengan bumbu halus hingga rata.',
    'Tumis sisa bumbu dengan sedikit minyak, masukkan daun salam dan sereh.',
    'Masukkan ayam, tambahkan air, rebus hingga empuk.',
    'Angkat ayam dari kuah.',
    'Panggang ayam di oven atau grill hingga kecoklatan.',
    'Oles sisa bumbu saat memanggang.',
    'Sajikan dengan nasi hangat dan lalapan.',
])];

$resep_list[] = ['Ayam Serundeng', $kat, [
    [b('Daging Ayam'), 200], [b('Kelapa'), 30], [b('Bawang Merah'), 15],
    [b('Bawang Putih'), 8], [b('Kunyit'), 5], [b('Kemiri'), 8],
    [b('Daun Salam'), 2], [b('Minyak Goreng'), 12], [b('Garam'), 1],
], langkah('Ayam Serundeng', [
    'Parut kelapa, sangrai setengah kering.',
    'Haluskan bawang merah, bawang putih, kunyit, kemiri, dan garam.',
    'Lumuri ayam dengan bumbu halus, tambahkan daun salam.',
    'Rebus ayam hingga empuk, angkat.',
    'Goreng ayam dalam minyak panas hingga kecoklatan.',
    'Campur kelapa sangrai dengan sisa bumbu, goreng hingga kering.',
    'Taburkan serundeng kelapa di atas ayam goreng.',
    'Sajikan dengan nasi hangat dan sambal terasi.',
])];

$resep_list[] = ['Ayam Kalasan', $kat, [
    [b('Daging Ayam'), 200], [b('Gula Merah'), 15], [b('Bawang Putih'), 8],
    [b('Kemiri'), 8], [b('Kunyit'), 3], [b('Daun Salam'), 2],
    [b('Minyak Goreng'), 10], [b('Air Kelapa'), 100],
], langkah('Ayam Kalasan', [
    'Haluskan bawang putih, kemiri, dan kunyit.',
    'Lumuri ayam dengan bumbu halus dan gula merah.',
    'Rebus ayam dengan air kelapa dan daun salam hingga empuk.',
    'Biarkan air rebusan menyusut.',
    'Panaskan minyak, goreng ayam hingga kecoklatan.',
    'Angkat dan tiriskan.',
    'Sajikan ayam kalasan dengan nasi hangat dan lalapan.',
    'Ayam kalasan khas Jogja siap dinikmati.',
])];

$resep_list[] = ['Ayam Kecap', $kat, [
    [b('Daging Ayam'), 200], [b('Kecap Manis'), 20], [b('Bawang Merah'), 15],
    [b('Bawang Putih'), 8], [b('Jahe'), 5], [b('Cabai Merah'), 5],
    [b('Minyak Goreng'), 8], [b('Daun Bawang'), 5], [b('Merica Bubuk'), 1],
], langkah('Ayam Kecap', [
    'Potong ayam kecil-kecil, cuci bersih.',
    'Haluskan bawang merah, bawang putih, jahe, dan cabai merah.',
    'Tumis bumbu halus hingga harum.',
    'Masukkan ayam, aduk hingga berubah warna.',
    'Tambahkan kecap manis dan merica bubuk.',
    'Beri sedikit air, masak hingga ayam empuk.',
    'Masukkan daun bawang iris, aduk sebentar.',
    'Koreksi rasa, angkat dan sajikan.',
])];

$resep_list[] = ['Ayam Geprek', $kat, [
    [b('Daging Ayam'), 200], [b('Tepung Terigu'), 40], [b('Telur Ayam'), 25],
    [b('Bawang Putih'), 10], [b('Cabai Rawit'), 15], [b('Minyak Goreng'), 15],
    [b('Garam'), 1], [b('Merica Bubuk'), 1],
], langkah('Ayam Geprek', [
    'Haluskan bawang putih, garam, dan merika untuk marinasi.',
    'Lumuri ayam dengan bumbu, diamkan 15 menit.',
    'Campur tepung terigu dengan sedikit garam dan merica.',
    'Celupkan ayam ke kocokan telur, gulingkan di tepung.',
    'Goreng ayam dalam minyak panas hingga kuning kecoklatan.',
    'Angkat dan tiriskan.',
    'Haluskan cabai rawit dan bawang putih untuk sambal.',
    'Geprek ayam dengan ulekan, campur dengan sambal.',
    'Sajikan dengan nasi hangat dan lalapan.',
])];

$resep_list[] = ['Ayam Balado', $kat, [
    [b('Daging Ayam'), 200], [b('Cabai Merah'), 20], [b('Bawang Merah'), 15],
    [b('Bawang Putih'), 8], [b('Tomat'), 15], [b('Minyak Goreng'), 12],
    [b('Garam'), 1], [b('Gula Pasir'), 3],
], langkah('Ayam Balado', [
    'Potong ayam, rebus hingga empuk lalu goreng hingga kecoklatan.',
    'Haluskan cabai merah, bawang merah, bawang putih, dan tomat.',
    'Panaskan minyak, tumis bumbu halus hingga harum.',
    'Tambahkan garam dan gula pasir.',
    'Masukkan ayam goreng, aduk rata dengan bumbu balado.',
    'Masak sebentar hingga bumbu meresap.',
    'Angkat dan sajikan.',
    'Ayam balado khas Padang siap dinikmati.',
])];

$resep_list[] = ['Ayam Asam Manis', $kat, [
    [b('Daging Ayam'), 200], [b('Tepung Terigu'), 30], [b('Telur Ayam'), 25],
    [b('Saus Tomat'), 20], [b('Bawang Bombay'), 10], [b('Nanas'), 30],
    [b('Minyak Goreng'), 12], [b('Gula Pasir'), 5], [b('Cuka'), 3],
], langkah('Ayam Asam Manis', [
    'Potong ayam dadu, lumuri dengan garam dan merica.',
    'Celup ayam ke kocokan telur, gulingkan di tepung terigu.',
    'Goreng ayam hingga kuning kecoklatan, angkat.',
    'Potong nanas kecil-kecil, iris bawang bombay.',
    'Tumis bawang bombay hingga harum.',
    'Masukkan saus tomat, gula pasir, cuka, dan sedikit air.',
    'Masukkan nanas dan ayam goreng, aduk rata.',
    'Masak hingga saus mengental, angkat.',
    'Sajikan ayam asam manis dengan nasi hangat.',
])];

$resep_list[] = ['Ayam Kung Pao', $kat, [
    [b('Daging Ayam'), 200], [b('Kacang Mede'), 20], [b('Cabai Merah'), 10],
    [b('Bawang Putih'), 8], [b('Jahe'), 5], [b('Kecap Asin'), 10],
    [b('Minyak Goreng'), 10], [b('Daun Bawang'), 5], [b('Cuka'), 3],
], langkah('Ayam Kung Pao', [
    'Potong ayam dadu, marinasi dengan kecap asin dan jahe.',
    'Goreng ayam hingga matang, angkat.',
    'Sangrai kacang mede sebentar.',
    'Potong cabai merah kering atau segar.',
    'Tumis bawang putih cabai hingga harum.',
    'Masukkan ayam goreng, aduk rata.',
    'Tambahkan kecap asin, cuka, dan daun bawang.',
    'Masukkan kacang mede, aduk sebentar.',
    'Sajikan ayam kung pao dengan nasi hangat.',
])];

$resep_list[] = ['Ayam Cabai Hijau', $kat, [
    [b('Daging Ayam'), 200], [b('Cabai Hijau'), 20], [b('Bawang Merah'), 15],
    [b('Bawang Putih'), 8], [b('Jahe'), 5], [b('Daun Salam'), 2],
    [b('Minyak Goreng'), 8], [b('Garam'), 1], [b('Gula Pasir'), 3],
], langkah('Ayam Cabai Hijau', [
    'Potong ayam kecil-kecil, rebus setengah matang.',
    'Iris serong cabai hijau.',
    'Haluskan bawang merah, bawang putih, jahe.',
    'Tumis bumbu halus hingga harum.',
    'Masukkan cabai hijau dan daun salam, aduk.',
    'Masukkan ayam, tambahkan garam dan gula.',
    'Beri sedikit air, masak hingga bumbu meresap.',
    'Angkat dan sajikan dengan nasi hangat.',
])];

$resep_list[] = ['Ayam Kari', $kat, [
    [b('Daging Ayam'), 200], [b('Santan Kelapa'), 80], [b('Bawang Merah'), 15],
    [b('Bawang Putih'), 8], [b('Kari Bubuk'), 5], [b('Kunyit'), 3],
    [b('Daun Salam'), 2], [b('Sereh'), 5], [b('Minyak Goreng'), 8],
], langkah('Ayam Kari', [
    'Potong ayam menjadi beberapa bagian.',
    'Haluskan bawang merah, bawang putih, dan kunyit.',
    'Tumis bumbu halus dengan kari bubuk hingga harum.',
    'Masukkan daun salam dan sereh geprek.',
    'Masukkan ayam, aduk hingga berubah warna.',
    'Tuang santan, masak dengan api kecil.',
    'Aduk sesekali agar santan tidak pecah.',
    'Masak hingga ayam empuk dan kuah mengental.',
    'Sajikan ayam kari dengan nasi hangat.',
])];

$resep_list[] = ['Ayam Suwir', $kat, [
    [b('Daging Ayam'), 200], [b('Cabai Merah'), 10], [b('Bawang Merah'), 15],
    [b('Bawang Putih'), 8], [b('Kunyit'), 3], [b('Daun Jeruk'), 3],
    [b('Minyak Goreng'), 8], [b('Garam'), 1], [b('Gula Pasir'), 3],
], langkah('Ayam Suwir', [
    'Rebus ayam hingga empuk, suwir-suwir kasar.',
    'Haluskan cabai merah, bawang merah, bawang putih, dan kunyit.',
    'Tumis bumbu halus hingga harum.',
    'Masukkan daun jeruk.',
    'Masukkan ayam suwir, aduk rata.',
    'Tambahkan garam dan gula pasir.',
    'Beri sedikit air, masak hingga bumbu meresap.',
    'Angkat dan sajikan.',
    'Ayam suwir cocok untuk isian nasi bakar atau nasi campur.',
])];

$resep_list[] = ['Ayam Bakar Madu', $kat, [
    [b('Daging Ayam'), 200], [b('Madu'), 20], [b('Kecap Manis'), 10],
    [b('Bawang Putih'), 8], [b('Jahe'), 5], [b('Minyak Goreng'), 8],
    [b('Jeruk Nipis'), 5], [b('Merica Bubuk'), 1],
], langkah('Ayam Bakar Madu', [
    'Haluskan bawang putih dan jahe.',
    'Campur ayam dengan bumbu halus, madu, kecap, jeruk nipis, dan merica.',
    'Diamkan minimal 30 menit dalam kulkas.',
    'Panaskan panggangan atau oven.',
    'Bakar ayam sambil dioles sisa bumbu madu.',
    'Balik ayam dan oles bumbu secara berkala.',
    'Bakar hingga ayam matang kecoklatan.',
    'Angkat dan sajikan dengan nasi hangat.',
    'Ayam bakar madu cocok untuk acara spesial.',
])];

$resep_list[] = ['Ayam Cabe Ijo', $kat, [
    [b('Daging Ayam'), 200], [b('Cabai Hijau'), 25], [b('Bawang Merah'), 15],
    [b('Bawang Putih'), 8], [b('Daun Jeruk'), 3], [b('Minyak Goreng'), 10],
    [b('Garam'), 1], [b('Gula Pasir'), 3], [b('Santan Kelapa'), 30],
], langkah('Ayam Cabe Ijo', [
    'Potong ayam kecil-kecil, cuci bersih.',
    'Haluskan cabai hijau besar, bawang merah, bawang putih.',
    'Tumis bumbu halus hingga harum.',
    'Masukkan daun jeruk.',
    'Masukkan ayam, aduk hingga berubah warna.',
    'Tambahkan garam, gula, dan santan.',
    'Masak hingga ayam empuk dan kuah mengental.',
    'Koreksi rasa, angkat.',
    'Sajikan ayam cabe ijo dengan nasi hangat.',
])];

$resep_list[] = ['Ayam Saus Padang', $kat, [
    [b('Daging Ayam'), 200], [b('Cabai Merah'), 15], [b('Cabai Rawit'), 8],
    [b('Bawang Merah'), 15], [b('Bawang Putih'), 8], [b('Saus Tomat'), 15],
    [b('Saus Tiram'), 10], [b('Minyak Goreng'), 10], [b('Daun Bawang'), 5],
], langkah('Ayam Saus Padang', [
    'Goreng ayam hingga setengah matang, angkat.',
    'Haluskan cabai merah, cabai rawit, bawang merah, bawang putih.',
    'Tumis bumbu halus hingga harum.',
    'Masukkan saus tomat dan saus tiram.',
    'Tambahkan air, aduk rata.',
    'Masukkan ayam, masak hingga saus mengental.',
    'Masukkan daun bawang iris, aduk sebentar.',
    'Sajikan ayam saus padang dengan nasi hangat.',
])];

$resep_list[] = ['Ayam Mentega', $kat, [
    [b('Daging Ayam'), 200], [b('Margarin'), 20], [b('Bawang Bombay'), 10],
    [b('Kecap Manis'), 15], [b('Kecap Asin'), 5], [b('Daun Bawang'), 5],
    [b('Minyak Goreng'), 5], [b('Merica Bubuk'), 1],
], langkah('Ayam Mentega', [
    'Potong ayam kecil-kecil, lumuri dengan garam dan merica.',
    'Goreng ayam hingga matang kecoklatan, angkat.',
    'Lelehkan margarin dalam wajan.',
    'Tumis bawang bombay hingga harum dan layu.',
    'Masukkan kecap manis dan kecap asin, aduk.',
    'Masukkan ayam goreng, aduk rata dengan saus.',
    'Tambahkan daun bawang iris, aduk sebentar.',
    'Sajikan ayam mentega dengan nasi hangat.',
])];

$resep_list[] = ['Ayam Bakar Pedas', $kat, [
    [b('Daging Ayam'), 200], [b('Cabai Rawit'), 20], [b('Cabai Merah'), 10],
    [b('Bawang Merah'), 15], [b('Bawang Putih'), 8], [b('Terasi'), 5],
    [b('Gula Merah'), 10], [b('Minyak Goreng'), 10], [b('Jeruk Nipis'), 5],
], langkah('Ayam Bakar Pedas', [
    'Haluskan cabai rawit, cabai merah, bawang merah, bawang putih, dan terasi.',
    'Lumuri ayam dengan bumbu halus, tambahkan gula merah dan garam.',
    'Kucuri jeruk nipis, diamkan 30 menit.',
    'Panaskan panggangan atau grill.',
    'Bakar ayam sambil sesekali dioles sisa bumbu.',
    'Balik agar matang merata.',
    'Sajikan dengan lalapan dan nasi hangat.',
    'Ayam bakar pedas siap dinikmati.',
])];

// ============================================================
// KATEGORI 2: MAIN COURSE - IKAN & SEAFOOD (25)
// ============================================================
$resep_list[] = ['Ikan Gurame Asam Manis', $kat, [
    [b("Ikan Gurame"), 200], [b("Tepung Terigu"), 30], [b("Bawang Bombay"), 10],
    [b("Saus Tomat"), 20], [b("Nanas"), 30], [b("Cuka"), 5],
    [b("Gula Pasir"), 5], [b("Minyak Goreng"), 15], [b("Daun Bawang"), 5],
], langkah("Ikan Gurame Asam Manis", [
    "Bersihkan ikan gurame, kerat-kerat badannya, lumuri jeruk nipis dan garam.",
    "Goreng ikan dalam minyak panas hingga kuning kecoklatan dan kering.",
    "Angkat dan tiriskan, sisihkan.",
    "Iris bawang bombay dan nanas kecil-kecil.",
    "Tumis bawang bombay hingga harum dan layu.",
    "Masukkan saus tomat, cuka, gula pasir, dan sedikit air, aduk rata.",
    "Masukkan nanas, masak hingga saus mengental.",
    "Masukkan ikan goreng, aduk perlahan dengan saus.",
    "Taburi daun bawang, sajikan selagi hangat.",
])];

$resep_list[] = ['Ikan Bawal Bakar', $kat, [
    [b("Ikan Bawal"), 200], [b("Kecap Manis"), 15], [b("Bawang Putih"), 8],
    [b("Jahe"), 5], [b("Kunyit"), 3], [b("Minyak Goreng"), 8],
    [b("Jeruk Nipis"), 5], [b("Cabai Merah"), 5],
], langkah("Ikan Bawal Bakar", [
    "Bersihkan ikan bawal, kerat-kerat badannya.",
    "Haluskan bawang putih, jahe, kunyit, dan cabai merah.",
    "Lumuri ikan dengan bumbu halus, kecap manis, dan jeruk nipis.",
    "Diamkan 20 menit agar bumbu meresap.",
    "Panaskan panggangan atau grill.",
    "Bakar ikan bawal sambil dioles sisa bumbu.",
    "Balik ikan agar matang merata.",
    "Sajikan dengan sambal dan lalapan segar.",
])];

$resep_list[] = ['Ikan Tenggiri Balado', $kat, [
    [b("Ikan Tenggiri"), 200], [b("Cabai Merah"), 20], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Tomat"), 15], [b("Minyak Goreng"), 12],
    [b("Garam"), 1], [b("Gula Pasir"), 3],
], langkah("Ikan Tenggiri Balado", [
    "Potong ikan tenggiri menjadi beberapa bagian.",
    "Lumuri dengan garam dan jeruk nipis, diamkan sebentar.",
    "Goreng ikan hingga matang kecoklatan, angkat.",
    "Haluskan cabai merah, bawang merah, bawang putih, dan tomat.",
    "Tumis bumbu halus hingga harum dan matang.",
    "Tambahkan garam dan gula pasir, aduk rata.",
    "Masukkan ikan tenggiri goreng, aduk dengan bumbu balado.",
    "Sajikan dengan nasi hangat.",
])];

$resep_list[] = ['Ikan Kuah Kuning', $kat, [
    [b("Ikan Mas"), 200], [b("Kunyit"), 5], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Jahe"), 5], [b("Sereh"), 5],
    [b("Daun Salam"), 2], [b("Minyak Goreng"), 5], [b("Garam"), 1],
], langkah("Ikan Kuah Kuning", [
    "Bersihkan ikan mas, lumuri dengan jeruk nipis dan garam.",
    "Haluskan bawang merah, bawang putih, kunyit, dan jahe.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan sereh geprek dan daun salam.",
    "Tambahkan air, didihkan.",
    "Masukkan ikan mas, masak dengan api kecil.",
    "Beri garam, koreksi rasa.",
    "Masak hingga ikan matang dan kuah menguning.",
    "Sajikan ikan kuah kuning dengan nasi hangat.",
])];

$resep_list[] = ['Ikan Cakalang Suwir', $kat, [
    [b("Ikan Cakalang"), 200], [b("Cabai Merah"), 10], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Kunyit"), 3], [b("Daun Jeruk"), 3],
    [b("Minyak Goreng"), 8], [b("Garam"), 1],
], langkah("Ikan Cakalang Suwir", [
    "Kukus atau rebus ikan cakalang hingga matang.",
    "Suwir-suwir daging ikan, buang durinya.",
    "Haluskan cabai merah, bawang merah, bawang putih, dan kunyit.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan daun jeruk sobek-sobek.",
    "Masukkan ikan cakalang suwir, aduk rata.",
    "Tambahkan garam, masak hingga bumbu meresap.",
    "Sajikan dengan nasi hangat.",
    "Ikan cakalang suwir khas Manado siap dinikmati.",
])];

$resep_list[] = ['Ikan Baronang Bakar', $kat, [
    [b("Ikan Baronang"), 200], [b("Kecap Manis"), 15], [b("Bawang Putih"), 8],
    [b("Kunyit"), 3], [b("Jahe"), 5], [b("Minyak Goreng"), 8],
    [b("Jeruk Nipis"), 5], [b("Cabai Rawit"), 5],
], langkah("Ikan Baronang Bakar", [
    "Bersihkan ikan baronang, kerat-kerat badannya.",
    "Haluskan bawang putih, jahe, kunyit, dan cabai rawit.",
    "Lumuri ikan dengan bumbu halus, kecap manis, dan jeruk nipis.",
    "Diamkan 20 menit agar bumbu meresap.",
    "Panaskan panggangan atau grill.",
    "Bakar ikan sambil dioles sisa bumbu.",
    "Balik ikan agar matang sempurna.",
    "Sajikan dengan sambal dabu-dabu dan lalapan.",
])];

$resep_list[] = ['Ikan Kakap Saus Padang', $kat, [
    [b("Ikan Kakap"), 200], [b("Cabai Merah"), 15], [b("Cabai Rawit"), 8],
    [b("Bawang Merah"), 15], [b("Bawang Putih"), 8], [b("Saus Tomat"), 15],
    [b("Saus Tiram"), 10], [b("Minyak Goreng"), 12], [b("Daun Bawang"), 5],
], langkah("Ikan Kakap Saus Padang", [
    "Fillet ikan kakap, potong-potong, lumuri garam dan jeruk nipis.",
    "Goreng ikan hingga matang kecoklatan, angkat.",
    "Haluskan cabai merah, cabai rawit, bawang merah, dan bawang putih.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan saus tomat dan saus tiram, tambahkan air sedikit.",
    "Masak hingga saus mendidih dan mengental.",
    "Masukkan ikan kakap goreng, aduk rata dengan saus.",
    "Taburi daun bawang, sajikan dengan nasi hangat.",
])];

$resep_list[] = ['Ikan Rica-rica', $kat, [
    [b("Ikan Mas"), 200], [b("Cabai Rawit"), 20], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Jahe"), 5], [b("Daun Jeruk"), 3],
    [b("Sereh"), 5], [b("Minyak Goreng"), 10], [b("Garam"), 1],
], langkah("Ikan Rica-rica", [
    "Bersihkan ikan mas, potong-potong, lumuri garam dan jeruk nipis.",
    "Goreng ikan hingga setengah matang, angkat.",
    "Haluskan cabai rawit, bawang merah, bawang putih, dan jahe.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan daun jeruk dan sereh geprek.",
    "Tambahkan air, masukkan ikan, masak hingga matang.",
    "Beri garam, koreksi rasa.",
    "Sajikan ikan rica-rica dengan nasi hangat.",
])];

$resep_list[] = ['Ikan Woku Belanga', $kat, [
    [b("Ikan Patin"), 200], [b("Cabai Rawit"), 15], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Kunyit"), 5], [b("Daun Jeruk"), 3],
    [b("Kemangi"), 10], [b("Minyak Goreng"), 8], [b("Garam"), 1],
], langkah("Ikan Woku Belanga", [
    "Potong ikan patin menjadi beberapa bagian, cuci bersih.",
    "Haluskan cabai rawit, bawang merah, bawang putih, dan kunyit.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan daun jeruk sobek-sobek.",
    "Masukkan ikan patin, aduk perlahan.",
    "Tambahkan air, masak hingga ikan matang.",
    "Masukkan kemangi, aduk sebentar hingga layu.",
    "Koreksi rasa, angkat dan sajikan.",
])];

$resep_list[] = ['Ikan Bakar Acar', $kat, [
    [b("Ikan Nila"), 200], [b("Timun"), 30], [b("Wortel"), 20],
    [b("Bawang Merah"), 15], [b("Cabai Merah"), 10], [b("Cuka"), 5],
    [b("Gula Pasir"), 5], [b("Minyak Goreng"), 10], [b("Bawang Putih"), 8],
], langkah("Ikan Bakar Acar", [
    "Bersihkan ikan nila, kerat-kerat, lumuri garam dan jeruk nipis.",
    "Haluskan bawang putih dan kunyit, lumuri ke ikan.",
    "Bakar ikan di atas panggangan hingga matang kecoklatan.",
    "Buat acar: iris timun, wortel, bawang merah, dan cabai merah.",
    "Campur sayuran dengan cuka, gula pasir, dan sedikit garam.",
    "Diamkan acar sebentar agar meresap.",
    "Sajikan ikan bakar dengan acar segar.",
    "Ikan bakar acar cocok sebagai hidangan makan siang.",
])];

$resep_list[] = ['Ikan Bumbu Kuning', $kat, [
    [b("Ikan Bandeng"), 200], [b("Kunyit"), 8], [b("Bawang Putih"), 8],
    [b("Kemiri"), 8], [b("Jahe"), 5], [b("Daun Salam"), 2],
    [b("Sereh"), 5], [b("Minyak Goreng"), 10], [b("Garam"), 1],
], langkah("Ikan Bumbu Kuning", [
    "Bersihkan ikan bandeng, potong-potong.",
    "Haluskan kunyit, bawang putih, kemiri, jahe, dan garam.",
    "Lumuri ikan dengan bumbu halus.",
    "Panaskan minyak, tumis bumbu sisa bersama daun salam dan sereh.",
    "Masukkan ikan, tambahkan air.",
    "Masak dengan api kecil hingga ikan matang dan bumbu meresap.",
    "Koreksi rasa, angkat.",
    "Sajikan ikan bumbu kuning dengan nasi hangat.",
])];

$resep_list[] = ['Ikan Kembung Goreng', $kat, [
    [b("Ikan Kembung"), 200], [b("Bawang Putih"), 8], [b("Kunyit"), 3],
    [b("Jahe"), 5], [b("Minyak Goreng"), 15], [b("Jeruk Nipis"), 5],
    [b("Garam"), 1],
], langkah("Ikan Kembung Goreng", [
    "Bersihkan ikan kembung, buang insang dan isi perut.",
    "Lumuri ikan dengan garam, bawang putih halus, kunyit, dan jahe.",
    "Kucuri jeruk nipis, diamkan 15 menit.",
    "Panaskan minyak dalam wajan.",
    "Goreng ikan kembung hingga matang kecoklatan.",
    "Balik sekali agar matang merata.",
    "Angkat dan tiriskan.",
    "Sajikan dengan nasi hangat dan sambal terasi.",
])];

$resep_list[] = ['Ikan Patin Kuah Kuning', $kat, [
    [b("Ikan Patin"), 200], [b("Kunyit"), 5], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Jahe"), 5], [b("Sereh"), 5],
    [b("Daun Jeruk"), 3], [b("Minyak Goreng"), 5], [b("Garam"), 1],
], langkah("Ikan Patin Kuah Kuning", [
    "Potong ikan patin, cuci bersih, lumuri jeruk nipis.",
    "Haluskan bawang merah, bawang putih, kunyit, dan jahe.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan sereh geprek dan daun jeruk.",
    "Tambahkan air, didihkan.",
    "Masukkan ikan patin, masak dengan api kecil.",
    "Beri garam, koreksi rasa.",
    "Masak hingga ikan matang dan kuah menguning.",
    "Sajikan selagi hangat dengan nasi.",
])];

$resep_list[] = ['Udang Saus Padang', $kat, [
    [b("Udang"), 150], [b("Cabai Merah"), 15], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Saus Tomat"), 15], [b("Saus Tiram"), 10],
    [b("Minyak Goreng"), 10], [b("Daun Bawang"), 5], [b("Telur Ayam"), 25],
], langkah("Udang Saus Padang", [
    "Kupas udang, buang kepala, cuci bersih.",
    "Haluskan cabai merah, bawang merah, dan bawang putih.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan saus tomat dan saus tiram, aduk rata.",
    "Tambahkan air sedikit, masak hingga mendidih.",
    "Masukkan udang, masak hingga berubah warna.",
    "Kocok lepas telur, masukkan ke saus sambil diaduk cepat.",
    "Taburi daun bawang, sajikan dengan nasi hangat.",
])];

$resep_list[] = ['Udang Balado', $kat, [
    [b("Udang"), 150], [b("Cabai Merah"), 20], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Tomat"), 15], [b("Minyak Goreng"), 12],
    [b("Garam"), 1], [b("Gula Pasir"), 3],
], langkah("Udang Balado", [
    "Kupas udang, cuci bersih, goreng sebentar hingga kemerahan.",
    "Haluskan cabai merah, bawang merah, bawang putih, dan tomat.",
    "Tumis bumbu halus hingga harum dan matang.",
    "Tambahkan garam dan gula pasir.",
    "Masukkan udang goreng, aduk rata dengan bumbu balado.",
    "Masak sebentar hingga bumbu meresap.",
    "Angkat dan sajikan.",
    "Udang balado khas Padang siap dinikmati.",
])];

$resep_list[] = ['Udang Asam Manis', $kat, [
    [b("Udang"), 150], [b("Saus Tomat"), 20], [b("Bawang Bombay"), 10],
    [b("Bawang Putih"), 5], [b("Jahe"), 3], [b("Cuka"), 5],
    [b("Gula Pasir"), 5], [b("Minyak Goreng"), 10], [b("Daun Bawang"), 5],
], langkah("Udang Asam Manis", [
    "Kupas udang, buang kepala, cuci bersih.",
    "Goreng udang sebentar hingga berubah warna, angkat.",
    "Iris bawang bombay dan bawang putih.",
    "Tumis bawang bombay dan bawang putih dengan jahe hingga harum.",
    "Masukkan saus tomat, cuka, gula pasir, dan sedikit air.",
    "Masak hingga saus mendidih dan mengental.",
    "Masukkan udang, aduk rata dengan saus.",
    "Taburi daun bawang, sajikan selagi hangat.",
])];

$resep_list[] = ['Cumi Hitam', $kat, [
    [b("Cumi-cumi"), 200], [b("Bawang Merah"), 15], [b("Bawang Putih"), 8],
    [b("Cabai Merah"), 10], [b("Jahe"), 5], [b("Minyak Goreng"), 8],
    [b("Garam"), 1], [b("Gula Pasir"), 3],
], langkah("Cumi Hitam", [
    "Bersihkan cumi, jangan buang tinta hitamnya.",
    "Potong cumi bentuk cincin, lumuri jeruk nipis.",
    "Haluskan bawang merah, bawang putih, cabai merah, dan jahe.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan cumi beserta tintanya, aduk rata.",
    "Tambahkan garam dan gula pasir.",
    "Masak dengan api besar sebentar saja agar cumi tidak alot.",
    "Sajikan cumi hitam dengan nasi hangat.",
])];

$resep_list[] = ['Cumi Saus Padang', $kat, [
    [b("Cumi-cumi"), 200], [b("Cabai Merah"), 15], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Saus Tomat"), 15], [b("Saus Tiram"), 10],
    [b("Minyak Goreng"), 10], [b("Daun Bawang"), 5],
], langkah("Cumi Saus Padang", [
    "Bersihkan cumi, potong cincin, cuci bersih.",
    "Goreng cumi sebentar, angkat dan tiriskan.",
    "Haluskan cabai merah, bawang merah, dan bawang putih.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan saus tomat dan saus tiram.",
    "Tambahkan air, masak hingga mendidih.",
    "Masukkan cumi, aduk rata, masak sebentar.",
    "Taburi daun bawang, sajikan dengan nasi hangat.",
])];

$resep_list[] = ['Cumi Goreng Tepung', $kat, [
    [b("Cumi-cumi"), 200], [b("Tepung Terigu"), 40], [b("Tepung Beras"), 20],
    [b("Telur Ayam"), 25], [b("Bawang Putih"), 5], [b("Merica Bubuk"), 1],
    [b("Minyak Goreng"), 20], [b("Garam"), 1],
], langkah("Cumi Goreng Tepung", [
    "Bersihkan cumi, potong cincin, lumuri jeruk nipis dan garam.",
    "Campur tepung terigu, tepung beras, garam, dan merica.",
    "Kocok telur, celupkan cumi ke telur.",
    "Gulingkan cumi di campuran tepung hingga terbalut rata.",
    "Panaskan minyak dalam wajan.",
    "Goreng cumi dalam minyak panas hingga kuning keemasan.",
    "Angkat dan tiriskan.",
    "Sajikan dengan saus sambal atau mayones.",
])];

$resep_list[] = ['Kepiting Saus Padang', $kat, [
    [b("Kepiting"), 250], [b("Cabai Merah"), 15], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Saus Tomat"), 20], [b("Saus Tiram"), 10],
    [b("Minyak Goreng"), 10], [b("Telur Ayam"), 25], [b("Daun Bawang"), 5],
], langkah("Kepiting Saus Padang", [
    "Bersihkan kepiting, sikat cangkang, potong menjadi dua.",
    "Kukus atau rebus kepiting hingga matang, sisihkan.",
    "Haluskan cabai merah, bawang merah, dan bawang putih.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan saus tomat dan saus tiram.",
    "Tambahkan air, masak hingga mendidih.",
    "Kocok telur, masukkan ke saus sambil diaduk.",
    "Masukkan kepiting, aduk rata dengan saus.",
    "Taburi daun bawang, sajikan selagi hangat.",
])];

$resep_list[] = ['Kepiting Lada Hitam', $kat, [
    [b("Kepiting"), 250], [b("Lada Hitam"), 5], [b("Bawang Bombay"), 10],
    [b("Bawang Putih"), 8], [b("Kecap Manis"), 10], [b("Kecap Asin"), 5],
    [b("Minyak Goreng"), 10], [b("Margarin"), 10], [b("Daun Bawang"), 5],
], langkah("Kepiting Lada Hitam", [
    "Bersihkan kepiting, potong, kukus hingga matang.",
    "Tumbuk kasar lada hitam.",
    "Cincang bawang bombay dan bawang putih.",
    "Lelehkan margarin, tumis bawang bombay dan bawang putih hingga harum.",
    "Masukkan lada hitam, kecap manis, dan kecap asin.",
    "Tambahkan air sedikit, masak hingga mengental.",
    "Masukkan kepiting, aduk rata dengan saus.",
    "Taburi daun bawang, sajikan selagi hangat.",
])];

$resep_list[] = ['Kerang Hijau Saus Tiram', $kat, [
    [b("Kerang Hijau"), 200], [b("Saus Tiram"), 15], [b("Bawang Putih"), 8],
    [b("Cabai Merah"), 10], [b("Jahe"), 5], [b("Minyak Goreng"), 8],
    [b("Daun Bawang"), 5], [b("Merica Bubuk"), 1],
], langkah("Kerang Hijau Saus Tiram", [
    "Bersihkan kerang hijau, rebus hingga cangkang terbuka.",
    "Tiriskan kerang, buka cangkang, ambil dagingnya.",
    "Cincang bawang putih dan jahe, iris cabai merah.",
    "Tumis bawang putih dan jahe hingga harum.",
    "Masukkan cabai merah, aduk sebentar.",
    "Masukkan saus tiram dan merica bubuk.",
    "Masukkan daging kerang, aduk rata, masak sebentar.",
    "Taburi daun bawang, sajikan hangat.",
])];

$resep_list[] = ['Kerang Dara Rebus', $kat, [
    [b("Kerang Dara"), 200], [b("Bawang Putih"), 5], [b("Jahe"), 5],
    [b("Sereh"), 5], [b("Daun Jeruk"), 3], [b("Cabai Rawit"), 10],
    [b("Kecap Manis"), 10], [b("Jeruk Nipis"), 5],
], langkah("Kerang Dara Rebus", [
    "Bersihkan kerang dara, cuci hingga air cucian bersih.",
    "Didihkan air dalam panci.",
    "Masukkan bawang putih geprek, jahe, sereh, dan daun jeruk.",
    "Masukkan kerang dara, rebus hingga semua cangkang terbuka.",
    "Angkat dan tiriskan.",
    "Sajikan dengan sambal kecap dari cabai rawit, kecap manis, dan jeruk nipis.",
    "Kerang dara rebus siap dinikmati sebagai camilan atau lauk.",
])];

$resep_list[] = ['Ikan Bandeng Presto', $kat, [
    [b("Ikan Bandeng"), 250], [b("Bawang Putih"), 8], [b("Kunyit"), 5],
    [b("Jahe"), 5], [b("Daun Salam"), 2], [b("Sereh"), 5],
    [b("Minyak Goreng"), 10], [b("Garam"), 1],
], langkah("Ikan Bandeng Presto", [
    "Bersihkan ikan bandeng, buang insang dan isi perut.",
    "Haluskan bawang putih, kunyit, jahe, dan garam.",
    "Lumuri ikan dengan bumbu halus hingga rata.",
    "Masukkan daun salam dan sereh ke dalam perut ikan.",
    "Masak dalam panci presto selama 30-45 menit hingga duri empuk.",
    "Setelah matang, angkat dan tiriskan.",
    "Goreng ikan sebentar jika suka tekstur lebih kering.",
    "Sajikan dengan nasi hangat dan sambal.",
])];

$resep_list[] = ['Ikan Lele Goreng Sambal', $kat, [
    [b("Ikan Lele"), 200], [b("Bawang Putih"), 8], [b("Kunyit"), 3],
    [b("Cabai Rawit"), 15], [b("Cabai Merah"), 10], [b("Terasi"), 3],
    [b("Minyak Goreng"), 15], [b("Jeruk Nipis"), 5], [b("Garam"), 1],
], langkah("Ikan Lele Goreng Sambal", [
    "Bersihkan ikan lele, lumuri jeruk nipis dan garam.",
    "Haluskan bawang putih dan kunyit, lumuri ke ikan lele.",
    "Diamkan 15 menit agar bumbu meresap.",
    "Goreng ikan lele dalam minyak panas hingga kecoklatan.",
    "Angkat dan tiriskan.",
    "Haluskan cabai rawit, cabai merah, terasi, dan garam untuk sambal.",
    "Siram sambal dengan minyak panas sisa gorengan.",
    "Sajikan ikan lele dengan sambal dan lalapan.",
])];

// ============================================================
// KATEGORI 2: MAIN COURSE - LAINNYA (30)
// ============================================================
$resep_list[] = ['Semur Daging Sapi', $kat, [
    [b("Daging Sapi"), 150], [b("Kecap Manis"), 20], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Jahe"), 5], [b("Kemiri"), 8],
    [b("Daun Salam"), 2], [b("Minyak Goreng"), 8], [b("Gula Merah"), 10],
], langkah("Semur Daging Sapi", [
    "Potong daging sapi ukuran dadu, cuci bersih.",
    "Haluskan bawang merah, bawang putih, kemiri, jahe, dan gula merah.",
    "Tumis bumbu halus hingga harum, masukkan daun salam.",
    "Masukkan daging sapi, aduk hingga berubah warna.",
    "Tambahkan kecap manis, aduk rata.",
    "Tuang air hingga daging terendam, masak dengan api kecil.",
    "Masak hingga daging empuk dan kuah mengental.",
    "Koreksi rasa, angkat dan sajikan.",
    "Semur daging sapi cocok disantap dengan nasi hangat.",
])];

$resep_list[] = ['Daging Bumbu Bali', $kat, [
    [b("Daging Sapi"), 150], [b("Cabai Merah"), 15], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Kemiri"), 8], [b("Kunyit"), 3],
    [b("Jahe"), 5], [b("Minyak Goreng"), 10], [b("Gula Merah"), 10],
], langkah("Daging Bumbu Bali", [
    "Rebus daging sapi hingga empuk, potong dadu.",
    "Goreng daging sebentar, sisihkan.",
    "Haluskan cabai merah, bawang merah, bawang putih, kemiri, kunyit, dan jahe.",
    "Tumis bumbu halus hingga harum dan matang.",
    "Masukkan daging, aduk rata.",
    "Tambahkan gula merah, garam, dan sedikit air.",
    "Masak hingga bumbu meresap dan mengering.",
    "Sajikan dengan nasi hangat.",
])];

$resep_list[] = ['Tongseng Sapi', $kat, [
    [b("Daging Sapi"), 150], [b("Kol"), 50], [b("Tomat"), 20],
    [b("Bawang Merah"), 15], [b("Bawang Putih"), 8], [b("Kecap Manis"), 15],
    [b("Cabai Merah"), 10], [b("Minyak Goreng"), 8], [b("Daun Bawang"), 5],
], langkah("Tongseng Sapi", [
    "Potong daging sapi tipis-tipis, iris kol dan tomat.",
    "Haluskan bawang merah, bawang putih, dan cabai merah.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan daging sapi, aduk hingga berubah warna.",
    "Tambahkan kecap manis dan sedikit air.",
    "Masukkan kol dan tomat, aduk rata.",
    "Masak hingga sayuran layu dan daging matang.",
    "Taburi daun bawang, sajikan hangat.",
])];

$resep_list[] = ['Empal Gepuk', $kat, [
    [b("Daging Sapi"), 150], [b("Bawang Putih"), 8], [b("Kemiri"), 8],
    [b("Kunyit"), 3], [b("Gula Merah"), 10], [b("Minyak Goreng"), 12],
    [b("Daun Salam"), 2], [b("Sereh"), 5], [b("Garam"), 1],
], langkah("Empal Gepuk", [
    "Rebus daging sapi hingga setengah empuk, iris tipis melebar.",
    "Haluskan bawang putih, kemiri, kunyit, dan garam.",
    "Lumuri daging dengan bumbu halus, tambahkan gula merah.",
    "Masukkan daun salam dan sereh geprek, kukus hingga empuk.",
    "Pukul-pukul daging hingga melebar dan pipih.",
    "Goreng dalam minyak panas hingga kecoklatan.",
    "Angkat dan tiriskan.",
    "Sajikan empal gepuk dengan nasi hangat dan sambal.",
])];

$resep_list[] = ['Sapi Lada Hitam', $kat, [
    [b("Daging Sapi"), 150], [b("Lada Hitam"), 5], [b("Bawang Bombay"), 10],
    [b("Bawang Putih"), 8], [b("Kecap Manis"), 10], [b("Kecap Asin"), 5],
    [b("Minyak Goreng"), 8], [b("Margarin"), 10], [b("Daun Bawang"), 5],
], langkah("Sapi Lada Hitam", [
    "Potong daging sapi tipis melebar, lumuri kecap asin dan lada hitam.",
    "Tumbuk kasar lada hitam.",
    "Lelehkan margarin, tumis bawang bombay dan bawang putih hingga harum.",
    "Masukkan daging sapi, masak hingga berubah warna.",
    "Tambahkan kecap manis dan lada hitam tumbuk.",
    "Beri sedikit air, masak hingga daging empuk.",
    "Masukkan daun bawang, aduk sebentar.",
    "Sajikan dengan nasi hangat.",
])];

$resep_list[] = ['Kari Kambing', $kat, [
    [b("Daging Kambing"), 150], [b("Santan Kelapa"), 80], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Kari Bubuk"), 5], [b("Kunyit"), 3],
    [b("Daun Salam"), 2], [b("Sereh"), 5], [b("Minyak Goreng"), 8],
], langkah("Kari Kambing", [
    "Potong daging kambing ukuran dadu, cuci bersih.",
    "Haluskan bawang merah, bawang putih, dan kunyit.",
    "Tumis bumbu halus dengan kari bubuk hingga harum.",
    "Masukkan daun salam dan sereh geprek.",
    "Masukkan daging kambing, aduk hingga berubah warna.",
    "Tuang santan, masak dengan api kecil.",
    "Aduk sesekali, masak hingga daging empuk.",
    "Koreksi rasa, sajikan dengan nasi hangat.",
])];

$resep_list[] = ['Gulai Nangka', $kat, [
    [b("Nangka Muda"), 150], [b("Santan Kelapa"), 80], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Cabai Merah"), 10], [b("Kunyit"), 5],
    [b("Jahe"), 5], [b("Daun Salam"), 2], [b("Minyak Goreng"), 8],
], langkah("Gulai Nangka", [
    "Potong nangka muda, rebus hingga setengah empuk, tiriskan.",
    "Haluskan bawang merah, bawang putih, cabai merah, kunyit, dan jahe.",
    "Tumis bumbu halus hingga harum, masukkan daun salam.",
    "Masukkan nangka, aduk rata.",
    "Tuang santan, masak dengan api kecil.",
    "Aduk sesekali agar santan tidak pecah.",
    "Masak hingga nangka empuk dan kuah mengental.",
    "Koreksi rasa, sajikan gulai nangka selagi hangat.",
])];

$resep_list[] = ['Lodeh Terong', $kat, [
    [b("Terong"), 100], [b("Labu Siam"), 50], [b("Kacang Panjang"), 30],
    [b("Santan Kelapa"), 60], [b("Bawang Merah"), 15], [b("Bawang Putih"), 8],
    [b("Cabai Merah"), 5], [b("Daun Salam"), 2], [b("Minyak Goreng"), 5],
], langkah("Lodeh Terong", [
    "Potong terong, labu siam, dan kacang panjang sesuai selera.",
    "Haluskan bawang merah, bawang putih, dan cabai merah.",
    "Tumis bumbu halus hingga harum, masukkan daun salam.",
    "Masukkan sayuran keras (labu siam) terlebih dahulu.",
    "Tambahkan air, masak hingga setengah matang.",
    "Tuang santan, aduk perlahan.",
    "Masukkan terong dan kacang panjang, masak hingga matang.",
    "Koreksi rasa, sajikan lodeh terong selagi hangat.",
])];

$resep_list[] = ['Sayur Asem', $kat, [
    [b("Kacang Panjang"), 30], [b("Labu Siam"), 50], [b("Jagung Manis"), 50],
    [b("Bayam"), 20], [b("Asam Jawa"), 5], [b("Gula Merah"), 10],
    [b("Bawang Merah"), 10], [b("Daun Salam"), 2], [b("Garam"), 1],
], langkah("Sayur Asem", [
    "Potong kacang panjang, labu siam, dan jagung manis.",
    "Didihkan air dalam panci.",
    "Masukkan jagung dan labu siam, rebus hingga empuk.",
    "Haluskan bawang merah dan garam.",
    "Masukkan bumbu halus, daun salam, dan asam Jawa.",
    "Tambahkan gula merah.",
    "Masukkan kacang panjang, masak sebentar.",
    "Terakhir masukkan bayam, masak sebentar hingga layu.",
    "Koreksi rasa, sajikan sayur asem hangat.",
])];

$resep_list[] = ['Tahu Bacem', $kat, [
    [b("Tahu Putih"), 100], [b("Gula Merah"), 15], [b("Bawang Putih"), 5],
    [b("Kemiri"), 5], [b("Ketumbar"), 3], [b("Daun Salam"), 2],
    [b("Minyak Goreng"), 8], [b("Garam"), 1],
], langkah("Tahu Bacem", [
    "Potong tahu putih agak tebal, goreng sebentar hingga berkulit.",
    "Haluskan bawang putih, kemiri, ketumbar, dan garam.",
    "Didihkan air, masukkan bumbu halus, gula merah, dan daun salam.",
    "Masukkan tahu, masak dengan api kecil.",
    "Biarkan hingga bumbu meresap dan air menyusut.",
    "Panaskan minyak, goreng tahu bacem sebentar.",
    "Angkat dan tiriskan.",
    "Sajikan tahu bacem dengan nasi hangat.",
])];

$resep_list[] = ['Tempe Bacem', $kat, [
    [b("Tempe"), 100], [b("Gula Merah"), 15], [b("Bawang Putih"), 5],
    [b("Kemiri"), 5], [b("Ketumbar"), 3], [b("Daun Salam"), 2],
    [b("Minyak Goreng"), 8], [b("Garam"), 1],
], langkah("Tempe Bacem", [
    "Potong tempe agak tebal, kerat-kerat permukaannya.",
    "Haluskan bawang putih, kemiri, ketumbar, dan garam.",
    "Didihkan air, masukkan bumbu halus, gula merah, dan daun salam.",
    "Masukkan tempe, masak dengan api kecil.",
    "Biarkan hingga bumbu meresap dan air menyusut.",
    "Panaskan minyak, goreng tempe bacem hingga kecoklatan.",
    "Angkat dan tiriskan.",
    "Sajikan tempe bacem sebagai lauk pendamping.",
])];

$resep_list[] = ['Tahu Gejrot', $kat, [
    [b("Tahu Putih"), 100], [b("Gula Merah"), 10], [b("Cabai Rawit"), 10],
    [b("Bawang Merah"), 10], [b("Kecap Manis"), 5], [b("Asam Jawa"), 3],
    [b("Daun Bawang"), 5], [b("Minyak Goreng"), 10],
], langkah("Tahu Gejrot", [
    "Potong tahu putih kotak-kotak kecil.",
    "Goreng tahu hingga kecoklatan, angkat dan tiriskan.",
    "Iris tipis bawang merah dan cabai rawit.",
    "Larutkan gula merah dengan sedikit air hangat, tambahkan asam Jawa.",
    "Masukkan irisan bawang merah, cabai rawit, dan kecap manis.",
    "Aduk rata bumbu gejrot.",
    "Letakkan tahu goreng di piring, siram dengan bumbu gejrot.",
    "Taburi daun bawang, sajikan.",
])];

$resep_list[] = ['Sayur Lodeh', $kat, [
    [b("Labu Siam"), 50], [b("Terong"), 50], [b("Kacang Panjang"), 30],
    [b("Santan Kelapa"), 60], [b("Bawang Merah"), 15], [b("Bawang Putih"), 8],
    [b("Cabai Merah"), 5], [b("Daun Salam"), 2], [b("Minyak Goreng"), 5],
], langkah("Sayur Lodeh", [
    "Potong labu siam, terong, dan kacang panjang.",
    "Haluskan bawang merah, bawang putih, dan cabai merah.",
    "Tumis bumbu halus hingga harum, masukkan daun salam.",
    "Masukkan labu siam, aduk, tambahkan air.",
    "Masak hingga labu siam setengah empuk.",
    "Tuang santan, aduk perlahan.",
    "Masukkan terong dan kacang panjang, masak hingga matang.",
    "Koreksi rasa, sajikan sayur lodeh hangat.",
])];

$resep_list[] = ['Oseng Kacang Panjang', $kat, [
    [b("Kacang Panjang"), 100], [b("Bawang Merah"), 10], [b("Bawang Putih"), 5],
    [b("Cabai Merah"), 5], [b("Minyak Goreng"), 5], [b("Garam"), 1],
    [b("Gula Pasir"), 2],
], langkah("Oseng Kacang Panjang", [
    "Potong kacang panjang sepanjang 3 cm.",
    "Iris tipis bawang merah, bawang putih, dan cabai merah.",
    "Panaskan minyak, tumis bawang merah dan bawang putih hingga harum.",
    "Masukkan cabai merah, aduk sebentar.",
    "Masukkan kacang panjang, aduk rata.",
    "Tambahkan garam dan gula pasir.",
    "Beri sedikit air, masak hingga kacang panjang matang.",
    "Angkat dan sajikan sebagai lauk pendamping.",
])];

$resep_list[] = ['Tumis Buncis', $kat, [
    [b("Buncis"), 100], [b("Bawang Putih"), 5], [b("Bawang Merah"), 10],
    [b("Cabai Merah"), 3], [b("Minyak Goreng"), 5], [b("Garam"), 1],
    [b("Gula Pasir"), 2], [b("Daun Bawang"), 5],
], langkah("Tumis Buncis", [
    "Bersihkan buncis, potong serong tipis.",
    "Iris bawang merah, bawang putih, dan cabai merah.",
    "Panaskan minyak, tumis bawang merah dan bawang putih hingga harum.",
    "Masukkan cabai merah, aduk sebentar.",
    "Masukkan buncis, aduk rata.",
    "Tambahkan garam dan gula pasir.",
    "Beri sedikit air, masak hingga buncis matang namun tetap renyah.",
    "Taburi daun bawang, sajikan.",
])];

$resep_list[] = ['Tumis Bayam', $kat, [
    [b("Bayam"), 100], [b("Bawang Putih"), 5], [b("Daun Bawang"), 5],
    [b("Minyak Goreng"), 3], [b("Garam"), 1], [b("Merica Bubuk"), 1],
], langkah("Tumis Bayam", [
    "Bersihkan bayam, petik daunnya, cuci bersih.",
    "Cincang bawang putih dan iris daun bawang.",
    "Panaskan minyak, tumis bawang putih hingga harum.",
    "Masukkan bayam, aduk cepat.",
    "Tambahkan garam dan merica bubuk.",
    "Masukkan daun bawang, aduk hingga bayam layu.",
    "Angkat dan sajikan segera.",
])];

$resep_list[] = ['Tumis Labu Siam', $kat, [
    [b("Labu Siam"), 100], [b("Bawang Putih"), 5], [b("Bawang Merah"), 10],
    [b("Cabai Merah"), 3], [b("Minyak Goreng"), 5], [b("Garam"), 1],
    [b("Daun Bawang"), 5],
], langkah("Tumis Labu Siam", [
    "Kupas labu siam, potong korek api, cuci bersih.",
    "Iris bawang merah, bawang putih, dan cabai merah.",
    "Panaskan minyak, tumis bawang merah dan bawang putih hingga harum.",
    "Masukkan cabai merah, aduk sebentar.",
    "Masukkan labu siam, aduk rata.",
    "Tambahkan garam, beri sedikit air.",
    "Masak hingga labu siam matang dan empuk.",
    "Taburi daun bawang, sajikan selagi hangat.",
])];

$resep_list[] = ['Cah Brokoli', $kat, [
    [b("Buncis"), 80], [b("Wortel"), 30], [b("Bawang Putih"), 5],
    [b("Jahe"), 3], [b("Minyak Goreng"), 5], [b("Garam"), 1],
    [b("Merica Bubuk"), 1],
], langkah("Cah Brokoli", [
    "Potong buncis serong, iris wortel tipis.",
    "Cincang bawang putih dan jahe.",
    "Panaskan minyak, tumis bawang putih dan jahe hingga harum.",
    "Masukkan wortel, aduk sebentar.",
    "Masukkan buncis, aduk rata.",
    "Tambahkan garam dan merica bubuk.",
    "Tuang sedikit air, masak hingga sayuran matang namun tetap renyah.",
    "Angkat dan sajikan.",
])];

$resep_list[] = ['Cah Jagung Muda', $kat, [
    [b("Jagung Manis"), 100], [b("Bawang Putih"), 5], [b("Minyak Goreng"), 5],
    [b("Garam"), 1], [b("Merica Bubuk"), 1], [b("Daun Bawang"), 5],
], langkah("Cah Jagung Muda", [
    "Potong jagung muda serong tipis-tipis.",
    "Cincang bawang putih.",
    "Panaskan minyak, tumis bawang putih hingga harum.",
    "Masukkan jagung muda, aduk rata.",
    "Tambahkan garam dan merica bubuk.",
    "Beri sedikit air, masak hingga matang.",
    "Taburi daun bawang, angkat dan sajikan.",
])];

$resep_list[] = ['Cah Sawi Putih', $kat, [
    [b("Sawi Hijau"), 100], [b("Bawang Putih"), 5], [b("Minyak Goreng"), 5],
    [b("Garam"), 1], [b("Gula Pasir"), 2], [b("Daun Bawang"), 5],
], langkah("Cah Sawi Putih", [
    "Potong sawi hijau ukuran 3 cm, cuci bersih.",
    "Cincang bawang putih.",
    "Panaskan minyak, tumis bawang putih hingga harum.",
    "Masukkan sawi hijau, aduk cepat.",
    "Tambahkan garam dan gula pasir.",
    "Beri sedikit air, masak hingga sawi layu.",
    "Taburi daun bawang, angkat dan sajikan.",
])];

$resep_list[] = ['Nasi Liwet', $kat, [
    [b("Nasi Putih"), 250], [b("Santan Kelapa"), 30], [b("Bawang Merah"), 10],
    [b("Bawang Putih"), 5], [b("Daun Salam"), 2], [b("Sereh"), 5],
    [b("Minyak Goreng"), 5], [b("Garam"), 1],
], langkah("Nasi Liwet", [
    "Cuci beras hingga bersih, masukkan ke panci.",
    "Iris bawang merah dan bawang putih.",
    "Tumis bawang hingga harum, masukkan daun salam dan sereh.",
    "Masukkan tumisan ke dalam panci berisi beras.",
    "Tambahkan santan, air, dan garam.",
    "Masak dengan api kecil hingga air terserap.",
    "Kukus atau lanjutkan masak hingga matang sempurna.",
    "Sajikan nasi liwet dengan lauk pelengkap.",
])];

$resep_list[] = ['Nasi Kuning', $kat, [
    [b("Nasi Putih"), 250], [b("Santan Kelapa"), 30], [b("Kunyit"), 8],
    [b("Daun Salam"), 2], [b("Sereh"), 5], [b("Garam"), 1],
    [b("Minyak Goreng"), 5],
], langkah("Nasi Kuning", [
    "Cuci beras hingga bersih.",
    "Parut kunyit, peras airnya atau gunakan kunyit bubuk.",
    "Campur santan, air kunyit, garam, daun salam, dan sereh.",
    "Masukkan campuran ke beras, masak hingga air terserap.",
    "Kukus beras hingga matang sempurna.",
    "Aduk nasi agar tercampur rata.",
    "Sajikan nasi kuning dengan lauk pauk.",
])];

$resep_list[] = ['Nasi Tumpeng', $kat, [
    [b("Nasi Putih"), 300], [b("Santan Kelapa"), 40], [b("Kunyit"), 10],
    [b("Daun Salam"), 2], [b("Sereh"), 5], [b("Garam"), 1],
    [b("Telur Ayam"), 50], [b("Tempe"), 50], [b("Tahu Putih"), 50],
], langkah("Nasi Tumpeng", [
    "Buat nasi kuning seperti resep nasi kuning.",
    "Bentuk nasi kuning menjadi kerucut menggunakan cetakan tumpeng.",
    "Siapkan lauk: goreng telur, tempe, dan tahu.",
    "Siapkan sambal dan lalapan.",
    "Tata lauk di sekeliling nasi tumpeng.",
    "Hias dengan sayuran segar dan kerupuk.",
    "Sajikan untuk acara syukuran atau selamatan.",
])];

$resep_list[] = ['Nasi Pecel', $kat, [
    [b("Nasi Putih"), 250], [b("Kacang Tanah"), 30], [b("Gula Merah"), 10],
    [b("Cabai Rawit"), 5], [b("Terasi"), 3], [b("Kangkung"), 30],
    [b("Toge"), 20], [b("Bayam"), 20], [b("Minyak Goreng"), 5],
], langkah("Nasi Pecel", [
    "Rebus kangkung, bayam, dan toge hingga matang, tiriskan.",
    "Goreng kacang tanah hingga matang.",
    "Haluskan kacang tanah goreng, cabai rawit, terasi, dan gula merah.",
    "Tambahkan air hangat ke bumbu pecel hingga kekentalan yang diinginkan.",
    "Letakkan nasi di piring, tata sayuran di sekelilingnya.",
    "Siram bumbu pecel di atas sayuran.",
    "Taburi bawang goreng dan kerupuk.",
    "Sajikan nasi pecel dengan lauk tambahan seperti tempe.",
])];

$resep_list[] = ['Nasi Bakar', $kat, [
    [b("Nasi Putih"), 250], [b("Daging Ayam"), 50], [b("Kemangi"), 10],
    [b("Bawang Merah"), 10], [b("Bawang Putih"), 5], [b("Cabai Merah"), 5],
    [b("Daun Pisang"), 1], [b("Minyak Goreng"), 5],
], langkah("Nasi Bakar", [
    "Tumis bawang merah dan bawang putih hingga harum.",
    "Masukkan cabai merah dan daging ayam cincang, masak hingga matang.",
    "Campur tumisan dengan nasi putih dan kemangi, aduk rata.",
    "Siapkan daun pisang, layukan di atas api sebentar.",
    "Letakkan nasi di atas daun pisang, bungkus rapi.",
    "Semat kedua ujungnya dengan lidi.",
    "Bakar di atas panggangan hingga daun pisang kecoklatan.",
    "Sajikan nasi bakar selagi hangat.",
])];

$resep_list[] = ['Nasi Megono', $kat, [
    [b("Nasi Putih"), 250], [b("Nangka Muda"), 50], [b("Kelapa"), 20],
    [b("Bawang Merah"), 10], [b("Bawang Putih"), 5], [b("Kunyit"), 3],
    [b("Cabai Merah"), 5], [b("Minyak Goreng"), 5],
], langkah("Nasi Megono", [
    "Potong nangka muda kecil-kecil, rebus hingga empuk.",
    "Parut kelapa, sangrai sebentar.",
    "Haluskan bawang merah, bawang putih, kunyit, dan cabai merah.",
    "Campur nangka rebus dengan bumbu halus dan kelapa parut.",
    "Kukus campuran nangka selama 15 menit.",
    "Sajikan nasi dengan megono di atasnya.",
    "Lengkapi dengan lauk seperti telur goreng dan sambal.",
    "Nasi megono khas Pekalongan siap dinikmati.",
])];

$resep_list[] = ['Nasi Jamblang', $kat, [
    [b("Nasi Putih"), 250], [b("Daun Jati"), 1], [b("Tempe"), 50],
    [b("Tahu Putih"), 50], [b("Sambal Goreng"), 10], [b("Minyak Goreng"), 5],
], langkah("Nasi Jamblang", [
    "Bungkus nasi putih dengan daun jati, tekan-tekan hingga padat.",
    "Goreng tempe dan tahu hingga kecoklatan.",
    "Siapkan sambal goreng atau lauk pelengkap lainnya.",
    "Buka bungkusan nasi jamblang di piring.",
    "Tata tempe goreng, tahu goreng, dan lauk lainnya.",
    "Tambahkan sambal dan lalapan.",
    "Nasi jamblang khas Cirebon siap disantap.",
])];

$resep_list[] = ['Nasi Ulam', $kat, [
    [b("Nasi Putih"), 250], [b("Kelapa"), 20], [b("Daun Kemangi"), 10],
    [b("Bawang Merah"), 10], [b("Cabai Rawit"), 5], [b("Minyak Goreng"), 5],
    [b("Garam"), 1],
], langkah("Nasi Ulam", [
    "Parut kelapa, sangrai hingga setengah kering.",
    "Iris tipis bawang merah dan cabai rawit.",
    "Campur nasi putih dengan kelapa sangrai.",
    "Tambahkan bawang merah, cabai rawit, dan kemangi.",
    "Beri garam, aduk hingga semua tercampur rata.",
    "Sajikan nasi ulam dengan lauk gorengan.",
    "Nasi ulam khas Betawi siap dinikmati.",
])];

$resep_list[] = ['Nasi Rames', $kat, [
    [b("Nasi Putih"), 250], [b("Daging Ayam"), 50], [b("Tempe"), 30],
    [b("Tahu Putih"), 30], [b("Labu Siam"), 30], [b("Santan Kelapa"), 20], [b("Sambal"), 10],
    [b("Minyak Goreng"), 5], [b("Daun Bawang"), 5],
], langkah("Nasi Rames", [
    "Goreng ayam, tempe, dan tahu hingga matang.",
    "Siapkan sayur lodeh dan sambal.",
    "Letakkan nasi di piring saji.",
    "Tata ayam goreng, tempe, tahu, dan sayur lodeh di sekeliling nasi.",
    "Tambahkan sambal dan taburan bawang goreng.",
    "Sajikan nasi rames selagi hangat.",
    "Nasi rames cocok untuk menu makan siang praktis.",
])];

$resep_list[] = ['Nasi Bakar Ayam', $kat, [
    [b("Nasi Putih"), 250], [b("Daging Ayam"), 60], [b("Kemangi"), 10],
    [b("Bawang Merah"), 10], [b("Bawang Putih"), 5], [b("Cabai Merah"), 5],
    [b("Daun Pisang"), 1], [b("Minyak Goreng"), 5],
], langkah("Nasi Bakar Ayam", [
    "Suwir daging ayam yang sudah direbus.",
    "Tumis bawang merah dan bawang putih hingga harum.",
    "Masukkan cabai merah dan suwiran ayam.",
    "Campur tumisan ayam dengan nasi dan kemangi.",
    "Siapkan daun pisang, layukan sebentar.",
    "Bungkus nasi dengan daun pisang, semat lidi.",
    "Bakar di atas panggangan hingga daun pisang berbau harum.",
    "Sajikan nasi bakar ayam selagi hangat.",
])];

// ============================================================
// KATEGORI 3: DESSERT (30)
// ============================================================
$kat_des = "Makanan Penutup";
$resep_list[] = ["Es Campur", $kat_des, [
    [b("Es Serut"), 100], [b("Sirup"), 20], [b("Susu Kental Manis"), 15],
    [b("Alpukat segar"), 30], [b("Nanas"), 20], [b("Kelapa Muda daging"), 20],
    [b("Selasih"), 5], [b("Roti Tawar"), 15],
], langkah("Es Campur", [
    "Serut es batu hingga halus, masukkan ke mangkuk saji.",
    "Potong alpukat, nanas, dan kelapa muda kecil-kecil.",
    "Potong roti tawar dadu kecil.",
    "Tata buah-buahan di atas es serut.",
    "Tambahkan roti tawar dan selasih yang sudah direndam.",
    "Siram dengan sirup merah dan susu kental manis.",
    "Aduk rata sebelum dinikmati.",
    "Es campur siap disajikan sebagai pencuci mulut.",
])];

$resep_list[] = ["Es Teler", $kat_des, [
    [b("Es Serut"), 100], [b("Susu Kental Manis"), 15], [b("Alpukat segar"), 30],
    [b("Kelapa Muda daging"), 30], [b("Nanas"), 20], [b("Sirup"), 15],
    [b("Cin Cau"), 20],
], langkah("Es Teler", [
    "Serut es batu hingga halus.",
    "Potong alpukat, kelapa muda, dan nanas kotak-kotak.",
    "Potong cincau kecil-kecil.",
    "Tata semua bahan di dalam mangkuk.",
    "Siram dengan sirup dan susu kental manis.",
    "Tambahkan es serut di atasnya.",
    "Sajikan segera selagi dingin.",
    "Es teler khas Bali siap dinikmati.",
])];

$resep_list[] = ["Es Buah", $kat_des, [
    [b("Es Serut"), 100], [b("Sirup"), 15], [b("Susu Kental Manis"), 15],
    [b("Semangka"), 30], [b("Melon"), 30], [b("Nanas"), 20],
    [b("Anggur hutan segar"), 20],
], langkah("Es Buah", [
    "Serut es batu, sisihkan.",
    "Potong semangka, melon, nanas, dan anggur kecil-kecil.",
    "Campur semua buah dalam mangkuk.",
    "Tambahkan es serut di atasnya.",
    "Siram dengan sirup dan susu kental manis.",
    "Aduk rata sebelum disajikan.",
    "Es buah segar siap dinikmati.",
])];

$resep_list[] = ["Es Doger", $kat_des, [
    [b("Es Serut"), 100], [b("Susu Kental Manis"), 15], [b("Alpukat segar"), 20],
    [b("Kelapa Muda daging"), 20], [b("Tape Singkong"), 20], [b("Sirup"), 10],
    [b("Roti Tawar"), 15],
], langkah("Es Doger", [
    "Serut es batu hingga halus.",
    "Potong alpukat, kelapa muda, dan tape singkong.",
    "Potong roti tawar dadu kecil.",
    "Tata semua bahan dalam mangkuk saji.",
    "Tambahkan es serut di atasnya.",
    "Siram dengan sirup merah dan susu kental manis.",
    "Sajikan segera selagi dingin.",
    "Es doger khas Bandung siap dinikmati.",
])];

$resep_list[] = ["Es Cendol", $kat_des, [
    [b("Tepung Beras"), 30], [b("Tepung Tapioka"), 10], [b("Daun Suji"), 5],
    [b("Santan Kelapa"), 50], [b("Gula Merah"), 30], [b("Es Serut"), 100],
    [b("Garam"), 1],
], langkah("Es Cendol", [
    "Campur tepung beras, tepung tapioka, air daun suji, dan garam.",
    "Masak adonan hingga mengental, lalu saring dengan cetakan cendol.",
    "Tampung cendol dalam air es hingga mengeras.",
    "Rebus gula merah dengan sedikit air hingga mengental.",
    "Rebus santan dengan daun pandan dan sedikit garam.",
    "Siapkan mangkuk, masukkan cendol, siram dengan kuah santan.",
    "Tambahkan es serut dan kuah gula merah di atasnya.",
    "Es cendol siap dinikmati selagi dingin.",
])];

$resep_list[] = ["Es Dawet", $kat_des, [
    [b("Tepung Beras"), 30], [b("Santan Kelapa"), 50], [b("Gula Merah"), 30],
    [b("Daun Suji"), 5], [b("Es Serut"), 100], [b("Garam"), 1],
], langkah("Es Dawet", [
    "Buat cendol/dawet dari tepung beras dan air daun suji.",
    "Masak gula merah dengan sedikit air hingga kental.",
    "Rebus santan dengan garam hingga mendidih.",
    "Siapkan gelas saji, masukkan dawet.",
    "Tambahkan es serut.",
    "Siram dengan kuah gula merah dan santan.",
    "Sajikan es dawet selagi dingin.",
    "Es dawet khas Banjarnegara siap dinikmati.",
])];

$resep_list[] = ["Es Lilin", $kat_des, [
    [b("Susu Kental Manis"), 30], [b("Sirup"), 20], [b("Air"), 100],
    [b("Es Batu"), 50],
], langkah("Es Lilin", [
    "Campur susu kental manis, sirup, dan air dalam wadah.",
    "Aduk rata hingga tercampur sempurna.",
    "Tuang ke dalam cetakan es lilin.",
    "Masukkan stik es krim di tengahnya.",
    "Bekukan dalam freezer selama 6-8 jam hingga keras.",
    "Keluarkan dari cetakan dengan merendam sebentar di air hangat.",
    "Es lilin siap dinikmati sebagai camilan segar.",
])];

$resep_list[] = ["Es Krim Goreng", $kat_des, [
    [b("Es krim"), 100], [b("Tepung Roti"), 30], [b("Telur Ayam"), 25],
    [b("Minyak Goreng"), 20], [b("Tepung Terigu"), 20],
], langkah("Es Krim Goreng", [
    "Siapkan es krim dalam bentuk kotak atau bulat dari freezer.",
    "Balut es krim dengan tepung terigu tipis-tipis.",
    "Celupkan ke kocokan telur.",
    "Gulingkan di tepung roti hingga terbalut rata.",
    "Simpan dalam freezer selama 1 jam hingga keras.",
    "Panaskan minyak dalam wajan.",
    "Goreng es krim dalam minyak panas selama 10-15 detik saja.",
    "Angkat dan sajikan segera.",
])];

$resep_list[] = ["Puding Coklat", $kat_des, [
    [b("Agar-agar"), 7], [b("Susu Sapi"), 200], [b("Gula Pasir"), 40],
    [b("Coklat bubuk"), 15], [b("Vanilla Bubuk"), 2],
], langkah("Puding Coklat", [
    "Campur agar-agar, gula pasir, dan coklat bubuk dalam panci.",
    "Tuang susu sapi, aduk rata.",
    "Masak dengan api sedang sambil terus diaduk.",
    "Tambahkan vanilla bubuk, aduk hingga mendidih.",
    "Angkat dan tuang ke cetakan puding.",
    "Biarkan suhu ruang, lalu dinginkan di kulkas.",
    "Sajikan puding coklat selagi dingin.",
    "Hias dengan parutan keju atau coklat sesuai selera.",
])];

$resep_list[] = ["Puding Buah", $kat_des, [
    [b("Agar-agar"), 7], [b("Gula Pasir"), 40], [b("Nanas"), 30],
    [b("Pepaya"), 30], [b("Anggur hutan segar"), 20], [b("Susu Sapi"), 200],
], langkah("Puding Buah", [
    "Potong nanas, pepaya, dan anggur dadu kecil.",
    "Campur agar-agar, gula pasir, dan susu sapi dalam panci.",
    "Masak hingga mendidih sambil diaduk.",
    "Tata buah-buahan di dasar cetakan.",
    "Tuang agar-agar cair ke cetakan.",
    "Biarkan setengah mengeras, tambahkan buah lagi jika suka.",
    "Dinginkan di kulkas hingga set sempurna.",
    "Keluarkan dari cetakan dan sajikan.",
])];

$resep_list[] = ["Puding Lumut", $kat_des, [
    [b("Agar-agar"), 7], [b("Gula Pasir"), 40], [b("Santan Kelapa"), 200],
    [b("Telur Ayam"), 50], [b("Vanilla Bubuk"), 2], [b("Daun Suji"), 5],
], langkah("Puding Lumut", [
    "Campur agar-agar, gula pasir, santan, dan air daun suji.",
    "Kocok telur hingga berbusa, masukkan ke campuran agar-agar.",
    "Tambahkan vanilla bubuk, aduk rata.",
    "Masak dengan api kecil sambil terus diaduk.",
    "Telur akan membentuk gumpalan seperti lumut.",
    "Angkat setelah mendidih, tuang ke cetakan.",
    "Dinginkan hingga set.",
    "Sajikan puding lumut selagi dingin.",
])];

$resep_list[] = ["Puding Karamel", $kat_des, [
    [b("Agar-agar"), 7], [b("Gula Pasir"), 60], [b("Susu Sapi"), 200],
    [b("Telur Ayam"), 50], [b("Vanilla Bubuk"), 2],
], langkah("Puding Karamel", [
    "Lelehkan gula pasir dalam panci hingga menjadi karamel keemasan.",
    "Tuang karamel ke dasar cetakan puding, ratakan.",
    "Campur agar-agar, sisa gula, susu sapi, dan telur kocok.",
    "Tambahkan vanilla bubuk, aduk rata.",
    "Masak hingga mendidih sambil diaduk.",
    "Tuang ke cetakan berlapis karamel.",
    "Dinginkan hingga set sempurna.",
    "Sajikan puding karamel dengan saus karamel di atasnya.",
])];

$resep_list[] = ["Bubur Sumsum", $kat_des, [
    [b("Tepung Beras"), 50], [b("Santan Kelapa"), 150], [b("Gula Merah"), 30],
    [b("Daun Pandan"), 2], [b("Garam"), 1],
], langkah("Bubur Sumsum", [
    "Campur tepung beras dengan santan dan air, aduk hingga larut.",
    "Masak dengan api kecil sambil terus diaduk.",
    "Tambahkan garam dan daun pandan.",
    "Masak hingga mengental dan matang, angkat.",
    "Rebus gula merah dengan sedikit air hingga mengental.",
    "Sajikan bubur sumsum dalam mangkuk.",
    "Siram dengan kuah gula merah di atasnya.",
    "Bubur sumsum siap dinikmati selagi hangat.",
])];

$resep_list[] = ["Bubur Ketan Hitam", $kat_des, [
    [b("Beras Ketan Hitam"), 100], [b("Gula Pasir"), 30], [b("Santan Kelapa"), 100],
    [b("Daun Pandan"), 2], [b("Garam"), 1],
], langkah("Bubur Ketan Hitam", [
    "Cuci beras ketan hitam, rendam semalaman.",
    "Rebus ketan hitam dengan air hingga empuk dan pecah.",
    "Tambahkan gula pasir dan daun pandan.",
    "Masak hingga menjadi bubur kental.",
    "Rebus santan dengan garam hingga mendidih.",
    "Sajikan bubur ketan hitam dalam mangkuk.",
    "Siram dengan kuah santan di atasnya.",
    "Bubur ketan hitam siap dinikmati.",
])];

$resep_list[] = ["Klepon", $kat_des, [
    [b("Tepung Ketan"), 75], [b("Gula Merah"), 20], [b("Kelapa"), 20],
    [b("Daun Suji"), 5], [b("Air"), 30], [b("Garam"), 1],
], langkah("Klepon", [
    "Campur tepung ketan dengan air daun suji hingga hijau.",
    "Uleni hingga kalis.",
    "Isi adonan dengan potongan gula merah.",
    "Bentuk bulatan-bulatan kecil.",
    "Rebus dalam air mendidih hingga mengapung.",
    "Angkat dan tiriskan.",
    "Gulingkan klepon di atas kelapa parut yang sudah dikukus dengan garam.",
    "Sajikan klepon sebagai camilan manis.",
])];

$resep_list[] = ["Onde-onde", $kat_des, [
    [b("Tepung Ketan"), 75], [b("Gula Pasir"), 20], [b("Kacang tanah goreng"), 20],
    [b("Wijen"), 10], [b("Minyak Goreng"), 15], [b("Air"), 30],
], langkah("Onde-onde", [
    "Campur tepung ketan dengan air, uleni hingga kalis.",
    "Haluskan kacang tanah goreng dengan gula pasir untuk isian.",
    "Ambil adonan, pipihkan, isi dengan kacang manis.",
    "Bentuk bulat, gulingkan di wijen hingga terbalut rata.",
    "Panaskan minyak dengan api kecil.",
    "Goreng onde-onde hingga kuning kecoklatan.",
    "Angkat dan tiriskan.",
    "Sajikan onde-onde selagi hangat.",
])];

$resep_list[] = ["Kue Lapis", $kat_des, [
    [b("Tepung Beras"), 50], [b("Tepung Tapioka"), 30], [b("Gula Pasir"), 40],
    [b("Santan Kelapa"), 150], [b("Vanilla Bubuk"), 2], [b("Garam"), 1],
    [b("Pasta Pandan"), 3],
], langkah("Kue Lapis", [
    "Campur tepung beras, tepung tapioka, gula pasir, dan garam.",
    "Tuang santan sedikit demi sedikit sambil diaduk.",
    "Saring adonan hingga halus, bagi menjadi dua bagian.",
    "Satu bagian beri pasta pandan, satu bagian biarkan putih.",
    "Panaskan kukusan, oles loyang dengan minyak.",
    "Tuang adonan hijau tipis, kukus 5 menit hingga set.",
    "Tuang adonan putih tipis, kukus lagi 5 menit.",
    "Ulangi hingga adonan habis, kukus terakhir 15 menit.",
    "Potong setelah dingin, sajikan.",
])];

$resep_list[] = ["Kue Cubit", $kat_des, [
    [b("Tepung Terigu"), 60], [b("Gula Pasir"), 30], [b("Telur Ayam"), 25],
    [b("Susu Sapi"), 50], [b("Margarin"), 10], [b("Vanilla Bubuk"), 2],
    [b("Coklat bubuk"), 5], [b("Minyak Goreng"), 5],
], langkah("Kue Cubit", [
    "Kocok telur dan gula pasir hingga mengembang.",
    "Masukkan tepung terigu, susu sapi, dan vanilla bubuk.",
    "Aduk rata hingga adonan licin.",
    "Panaskan cetakan kue cubit dengan margarin.",
    "Tuang adonan setengah cetakan.",
    "Taburi dengan coklat bubuk atau meses.",
    "Tutup dan masak hingga matang.",
    "Angkat dan sajikan hangat.",
])];

$resep_list[] = ["Kue Pancong", $kat_des, [
    [b("Tepung Terigu"), 50], [b("Tepung Beras"), 30], [b("Gula Pasir"), 30],
    [b("Santan Kelapa"), 100], [b("Garam"), 1], [b("Minyak Goreng"), 5],
], langkah("Kue Pancong", [
    "Campur tepung terigu, tepung beras, gula pasir, dan garam.",
    "Tuang santan sedikit demi sedikit, aduk hingga licin.",
    "Diamkan adonan selama 15 menit.",
    "Panaskan cetakan kue pancong, oles dengan minyak.",
    "Tuang adonan ke cetakan hingga penuh.",
    "Masak dengan api kecil hingga matang kecoklatan.",
    "Angkat dan sajikan hangat.",
    "Kue pancong khas Betawi siap dinikmati.",
])];

$resep_list[] = ["Kue Pukis", $kat_des, [
    [b("Tepung Terigu"), 60], [b("Gula Pasir"), 30], [b("Telur Ayam"), 25],
    [b("Santan Kelapa"), 80], [b("Margarin"), 10], [b("Vanilla Bubuk"), 2],
    [b("Minyak Goreng"), 5], [b("Keju Kacang Tanah"), 10],
], langkah("Kue Pukis", [
    "Kocok telur dan gula pasir hingga mengembang.",
    "Masukkan tepung terigu bergantian dengan santan.",
    "Tambahkan margarin cair dan vanilla bubuk, aduk rata.",
    "Diamkan adonan 30 menit.",
    "Panaskan cetakan pukis, oles minyak.",
    "Tuang adonan ke cetakan, taburi topping keju.",
    "Tutup dan masak hingga matang.",
    "Angkat dan sajikan hangat.",
])];

$resep_list[] = ["Kue Rangin", $kat_des, [
    [b("Tepung Beras"), 60], [b("Santan Kelapa"), 100], [b("Gula Pasir"), 20],
    [b("Garam"), 1], [b("Minyak Goreng"), 5], [b("Daun Bawang"), 5],
], langkah("Kue Rangin", [
    "Campur tepung beras, garam, dan gula pasir.",
    "Tuang santan sedikit demi sedikit, aduk hingga licin.",
    "Panaskan cetakan kue rangin, oles dengan minyak.",
    "Tuang adonan ke cetakan.",
    "Masak hingga pinggirnya kering dan kecoklatan.",
    "Angkat dan sajikan hangat.",
    "Kue rangin khas Betawi siap dinikmati.",
])];

$resep_list[] = ["Dadar Gulung", $kat_des, [
    [b("Tepung Terigu"), 50], [b("Telur Ayam"), 25], [b("Santan Kelapa"), 80],
    [b("Gula Merah"), 20], [b("Kelapa"), 20], [b("Daun Suji"), 5],
    [b("Garam"), 1], [b("Minyak Goreng"), 5],
], langkah("Dadar Gulung", [
    "Campur tepung terigu, santan, telur, air daun suji, dan garam.",
    "Aduk hingga licin, saring.",
    "Buat kulit dadar tipis di wajan anti lengket.",
    "Campur kelapa parut dengan gula merah sisir untuk isian.",
    "Kukus isian kelapa sebentar hingga gula larut.",
    "Ambil selembar kulit, isi dengan campuran kelapa.",
    "Gulung rapat seperti amplop.",
    "Sajikan dadar gulung selagi hangat.",
])];

$resep_list[] = ["Putu Ayu", $kat_des, [
    [b("Tepung Terigu"), 50], [b("Telur Ayam"), 50], [b("Gula Pasir"), 40],
    [b("Santan Kelapa"), 30], [b("Maizena tepung"), 10], [b("Vanilla Bubuk"), 2],
    [b("Kelapa"), 20], [b("Pasta Pandan"), 3],
], langkah("Putu Ayu", [
    "Kocok telur dan gula pasir hingga mengembang putih.",
    "Masukkan tepung terigu dan maizena bergantian dengan santan.",
    "Tambahkan pasta pandan dan vanilla bubuk, aduk rata.",
    "Oles cetakan dengan minyak, taburi kelapa parut di dasar.",
    "Tuang adonan ke cetakan hingga penuh.",
    "Kukus dalam dandang panas selama 15 menit.",
    "Angkat dan keluarkan dari cetakan.",
    "Sajikan putu ayu selagi hangat.",
])];

$resep_list[] = ["Nagasari", $kat_des, [
    [b("Tepung Beras"), 60], [b("Santan Kelapa"), 150], [b("Gula Pasir"), 30],
    [b("Pisang"), 50], [b("Daun Pandan"), 2], [b("Garam"), 1],
], langkah("Nagasari", [
    "Campur tepung beras, gula pasir, garam, dan santan.",
    "Masak hingga mengental sambil diaduk, angkat.",
    "Potong pisang menjadi beberapa bagian.",
    "Ambil daun pisang, oles dengan minyak.",
    "Letakkan adonan tepung di daun, tambahkan pisang.",
    "Bungkus rapi seperti lontong.",
    "Kukus selama 20 menit hingga matang.",
    "Sajikan nagasari selagi hangat.",
])];

$resep_list[] = ["Lemang", $kat_des, [
    [b("Beras Ketan Putih"), 100], [b("Santan Kelapa"), 100], [b("Daun Pisang"), 1],
    [b("Daun Pisang"), 1], [b("Garam"), 1],
], langkah("Lemang", [
    "Cuci beras ketan, rendam semalaman.",
    "Campur ketan dengan santan dan garam, aduk rata.",
    "Siapkan potongan bambu, lapisi dalamnya dengan daun pisang.",
    "Masukkan campuran ketan ke dalam bambu.",
    "Panggang di atas bara api sambil diputar.",
    "Masak hingga ketan matang dan harum.",
    "Keluarkan lemang dari bambu.",
    "Potong dan sajikan dengan rendang atau serundeng.",
])];

$resep_list[] = ["Wajik", $kat_des, [
    [b("Beras Ketan Putih"), 100], [b("Gula Merah"), 50], [b("Santan Kelapa"), 50],
    [b("Daun Pandan"), 2], [b("Garam"), 1],
], langkah("Wajik", [
    "Kukus beras ketan hingga setengah matang.",
    "Rebus gula merah dengan santan, daun pandan, dan garam.",
    "Masak hingga gula larut dan mengental.",
    "Masukkan ketan kukus ke dalam kuah gula.",
    "Aduk rata, masak dengan api kecil hingga meresap.",
    "Angkat dan tuang ke loyang yang dioles minyak.",
    "Ratakan dan tekan-tekan hingga padat.",
    "Potong setelah dingin, sajikan.",
])];

$resep_list[] = ["Dodol", $kat_des, [
    [b("Tepung Ketan"), 50], [b("Gula Merah"), 50], [b("Santan Kelapa"), 150],
    [b("Daun Pandan"), 2], [b("Garam"), 1],
], langkah("Dodol", [
    "Campur tepung ketan dengan santan, aduk rata.",
    "Masukkan gula merah sisir dan garam.",
    "Masak dengan api kecil sambil terus diaduk.",
    "Tambahkan daun pandan.",
    "Aduk terus hingga adonan mengental dan tidak lengket.",
    "Proses ini memakan waktu 1-2 jam.",
    "Tuang ke loyang yang dioles minyak, ratakan.",
    "Potong setelah dingin dan set.",
])];

$resep_list[] = ["Wingko Babat", $kat_des, [
    [b("Tepung Ketan"), 50], [b("Kelapa"), 30], [b("Gula Pasir"), 30],
    [b("Santan Kelapa"), 50], [b("Vanilla Bubuk"), 2], [b("Garam"), 1],
], langkah("Wingko Babat", [
    "Campur tepung ketan, kelapa parut, gula pasir, dan garam.",
    "Tuang santan sedikit demi sedikit, aduk rata.",
    "Tambahkan vanilla bubuk, uleni hingga bisa dibentuk.",
    "Panaskan wajan anti lengket dengan sedikit minyak.",
    "Cetak adonan bulat pipih, panggang dengan api kecil.",
    "Balik sekali hingga kedua sisi kecoklatan.",
    "Angkat dan sajikan.",
    "Wingko babat khas Semarang siap dinikmati.",
])];

$resep_list[] = ["Serabi", $kat_des, [
    [b("Tepung Beras"), 50], [b("Santan Kelapa"), 100], [b("Gula Pasir"), 20],
    [b("Telur Ayam"), 25], [b("Garam"), 1], [b("Minyak Goreng"), 5],
], langkah("Serabi", [
    "Campur tepung beras, gula pasir, garam, dan santan.",
    "Kocok telur, masukkan ke adonan, aduk rata.",
    "Diamkan adonan selama 30 menit.",
    "Panaskan cetakan serabi, oles dengan minyak.",
    "Tuang adonan ke cetakan, jangan penuh.",
    "Tutup dan masak dengan api kecil hingga matang.",
    "Angkat dan sajikan hangat.",
    "Serabi nikmat disantap dengan kuah kinca gula merah.",
])];

$resep_list[] = ["Kue Putu", $kat_des, [
    [b("Tepung Beras"), 75], [b("Gula Merah"), 20], [b("Kelapa"), 20],
    [b("Daun Pandan"), 2], [b("Garam"), 1],
], langkah("Kue Putu", [
    "Campur tepung beras dengan garam, basahi sedikit air.",
    "Ayak tepung hingga butiran halus.",
    "Siapkan cetakan kue putu bambu.",
    "Isi setengah cetakan dengan tepung beras.",
    "Tambahkan potongan gula merah di tengah.",
    "Tutup dengan tepung beras lagi hingga penuh.",
    "Kukus dalam dandang panas selama 10 menit.",
    "Keluarkan dari cetakan, sajikan dengan kelapa parut.",
])];

// ============================================================
// KATEGORI 4: BEVERAGES / MINUMAN (35)
// ============================================================
$kat_min = "Minuman";
$resep_list[] = ["Es Teh Manis", $kat_min, [
    [b("Teh"), 5], [b("Gula Pasir"), 20], [b("Es Batu"), 50], [b("Air"), 200],
], langkah("Es Teh Manis", [
    "Rebus air hingga mendidih.",
    "Seduh teh dalam gelas dengan air panas.",
    "Tambahkan gula pasir, aduk hingga larut.",
    "Diamkan hingga suhu ruang.",
    "Siapkan gelas berisi es batu.",
    "Tuang teh manis ke dalam gelas.",
    "Sajikan es teh manis selagi dingin.",
    "Tambahkan irisan lemon jika suka.",
])];

$resep_list[] = ["Es Jeruk", $kat_min, [
    [b("Jeruk Manis"), 100], [b("Gula Pasir"), 15], [b("Es Batu"), 50], [b("Air"), 100],
], langkah("Es Jeruk", [
    "Peras jeruk manis, ambil airnya.",
    "Campur air perasan jeruk dengan gula pasir.",
    "Aduk hingga gula larut.",
    "Tambahkan air dan es batu.",
    "Aduk rata.",
    "Sajikan es jeruk segar.",
    "Hias dengan irisan jeruk di pinggir gelas.",
])];

$resep_list[] = ["Es Kopi Susu", $kat_min, [
    [b("Kopi bubuk instant"), 5], [b("Susu Kental Manis"), 15], [b("Es Batu"), 50],
    [b("Air"), 150],
], langkah("Es Kopi Susu", [
    "Seduh kopi bubuk dengan air panas.",
    "Tambahkan susu kental manis, aduk rata.",
    "Diamkan hingga suhu ruang.",
    "Siapkan gelas berisi es batu.",
    "Tuang kopi susu ke dalam gelas.",
    "Aduk rata sebelum dinikmati.",
    "Es kopi susu siap disajikan.",
])];

$resep_list[] = ["Es Cincau", $kat_min, [
    [b("Cin Cau"), 50], [b("Susu Kental Manis"), 15], [b("Sirup"), 15],
    [b("Es Batu"), 50], [b("Air"), 100],
], langkah("Es Cincau", [
    "Potong cincau kotak-kotak kecil.",
    "Masukkan ke dalam gelas saji.",
    "Tambahkan es batu.",
    "Siram dengan sirup dan susu kental manis.",
    "Tambahkan air matang.",
    "Aduk rata sebelum dinikmati.",
    "Es cincau segar siap disajikan.",
])];

$resep_list[] = ["Es Kelapa Muda", $kat_min, [
    [b("Kelapa Muda air"), 100], [b("Kelapa Muda daging"), 50],
    [b("Sirup"), 15], [b("Es Batu"), 50],
], langkah("Es Kelapa Muda", [
    "Siapkan air kelapa muda dan daging kelapa.",
    "Potong daging kelapa muda kecil-kecil.",
    "Masukkan ke gelas saji.",
    "Tambahkan es batu.",
    "Siram dengan sirup merah.",
    "Tuang air kelapa muda.",
    "Aduk rata, sajikan selagi dingin.",
])];

$resep_list[] = ["Es Soda Gembira", $kat_min, [
    [b("Air Soda"), 150], [b("Susu Kental Manis"), 15], [b("Sirup"), 15],
    [b("Es Batu"), 50],
], langkah("Es Soda Gembira", [
    "Siapkan gelas saji berisi es batu.",
    "Tuang sirup merah ke dalam gelas.",
    "Tambahkan susu kental manis.",
    "Tuang air soda perlahan.",
    "Aduk rata sebelum dinikmati.",
    "Es soda gembira siap disajikan.",
])];

$resep_list[] = ["Es Krim Soda", $kat_min, [
    [b("Air Soda"), 150], [b("Es krim"), 50], [b("Susu Kental Manis"), 10],
    [b("Es Batu"), 30],
], langkah("Es Krim Soda", [
    "Siapkan gelas saji berisi es batu.",
    "Tuang susu kental manis.",
    "Tuang air soda perlahan.",
    "Letakkan satu scoop es krim di atasnya.",
    "Sajikan segera selagi es krim belum meleleh.",
    "Es krim soda siap dinikmati.",
])];

$resep_list[] = ["Es Campur Soda", $kat_min, [
    [b("Air Soda"), 150], [b("Sirup"), 15], [b("Susu Kental Manis"), 10],
    [b("Selasih"), 10], [b("Es Batu"), 50],
], langkah("Es Campur Soda", [
    "Rendam selasih dalam air hingga mengembang.",
    "Siapkan gelas saji berisi es batu.",
    "Masukkan selasih ke dalam gelas.",
    "Tuang sirup dan susu kental manis.",
    "Tuang air soda perlahan.",
    "Aduk rata, sajikan selagi dingin.",
])];

$resep_list[] = ["Es Tebu", $kat_min, [
    [b("Sari Tebu"), 150], [b("Es Batu"), 50], [b("Jeruk Nipis"), 5],
], langkah("Es Tebu", [
    "Siapkan sari tebu segar.",
    "Siapkan gelas berisi es batu.",
    "Tuang sari tebu ke dalam gelas.",
    "Kucuri dengan jeruk nipis.",
    "Aduk rata.",
    "Sajikan es tebu selagi dingin.",
])];

$resep_list[] = ["Es Selasih", $kat_min, [
    [b("Selasih"), 10], [b("Sirup"), 20], [b("Susu Kental Manis"), 15],
    [b("Es Batu"), 50], [b("Air"), 100],
], langkah("Es Selasih", [
    "Rendam selasih dalam air hingga mengembang.",
    "Siapkan gelas saji.",
    "Masukkan selasih ke dalam gelas.",
    "Tambahkan es batu.",
    "Siram dengan sirup dan susu kental manis.",
    "Tuang air matang, aduk rata.",
    "Sajikan es selasih selagi dingin.",
])];

$resep_list[] = ["Es Sirup", $kat_min, [
    [b("Sirup"), 30], [b("Es Batu"), 50], [b("Air"), 150],
], langkah("Es Sirup", [
    "Siapkan gelas saji.",
    "Tuang sirup ke dalam gelas.",
    "Tambahkan es batu.",
    "Tuang air matang.",
    "Aduk rata.",
    "Sajikan es sirup selagi dingin.",
])];

$resep_list[] = ["Es Lemon Tea", $kat_min, [
    [b("Teh"), 5], [b("Jeruk Nipis"), 15], [b("Gula Pasir"), 15],
    [b("Es Batu"), 50], [b("Air"), 200],
], langkah("Es Lemon Tea", [
    "Seduh teh dengan air panas.",
    "Tambahkan gula pasir, aduk hingga larut.",
    "Diamkan hingga suhu ruang.",
    "Peras jeruk nipis, ambil airnya.",
    "Campur air jeruk nipis ke dalam teh.",
    "Siapkan gelas berisi es batu.",
    "Tuang lemon tea ke dalam gelas.",
    "Sajikan selagi dingin.",
])];

$resep_list[] = ["Es Susu Coklat", $kat_min, [
    [b("Susu Sapi"), 200], [b("Coklat bubuk"), 10], [b("Gula Pasir"), 15],
    [b("Es Batu"), 50],
], langkah("Es Susu Coklat", [
    "Campur susu sapi, coklat bubuk, dan gula pasir.",
    "Aduk rata hingga coklat larut.",
    "Siapkan gelas berisi es batu.",
    "Tuang susu coklat ke dalam gelas.",
    "Aduk rata.",
    "Sajikan es susu coklat selagi dingin.",
])];

$resep_list[] = ["Es Yogurt", $kat_min, [
    [b("Yogurt"), 100], [b("Susu Kental Manis"), 10], [b("Stroberi"), 30],
    [b("Es Batu"), 30],
], langkah("Es Yogurt", [
    "Potong stroberi kecil-kecil.",
    "Campur yogurt dengan susu kental manis.",
    "Siapkan gelas saji.",
    "Masukkan potongan stroberi.",
    "Tuang campuran yogurt.",
    "Tambahkan es batu.",
    "Sajikan es yogurt selagi dingin.",
])];

$resep_list[] = ["Es Nata De Coco", $kat_min, [
    [b("Nata De Coco"), 50], [b("Sirup"), 15], [b("Susu Kental Manis"), 10],
    [b("Es Batu"), 50], [b("Air"), 100],
], langkah("Es Nata De Coco", [
    "Potong nata de coco kotak-kotak kecil.",
    "Masukkan ke dalam gelas saji.",
    "Tambahkan es batu.",
    "Tuang sirup dan susu kental manis.",
    "Tambahkan air matang.",
    "Aduk rata, sajikan selagi dingin.",
])];

$resep_list[] = ["Wedang Jahe", $kat_min, [
    [b("Jahe"), 20], [b("Gula Merah"), 15], [b("Air"), 250],
    [b("Sereh"), 5],
], langkah("Wedang Jahe", [
    "Kupas jahe, memarkan atau iris tipis.",
    "Rebus air bersama jahe dan sereh geprek.",
    "Tambahkan gula merah.",
    "Rebus hingga mendidih dan harum.",
    "Saring ke dalam gelas atau cangkir.",
    "Sajikan wedang jahe selagi hangat.",
    "Wedang jahe cocok dinikmati saat cuaca dingin.",
])];

$resep_list[] = ["Wedang Uwuh", $kat_min, [
    [b("Jahe"), 15], [b("Kayu Manis Bubuk"), 2], [b("Cengkeh kering"), 3],
    [b("Pala biji"), 3], [b("Gula Merah"), 15], [b("Air"), 250],
], langkah("Wedang Uwuh", [
    "Kupas jahe, memarkan atau iris.",
    "Rebus air dengan jahe, kayu manis, cengkeh, dan pala.",
    "Tambahkan gula merah.",
    "Rebus hingga mendidih dan rempah meresap.",
    "Saring ke dalam cangkir.",
    "Sajikan wedang uwuh selagi hangat.",
    "Wedang uwuh khas Jogja siap dinikmati.",
])];

$resep_list[] = ["Wedang Ronde", $kat_min, [
    [b("Tepung Ketan"), 50], [b("Kacang tanah goreng"), 20], [b("Gula Merah"), 10],
    [b("Jahe"), 15], [b("Santan Kelapa"), 30], [b("Gula Pasir"), 10],
], langkah("Wedang Ronde", [
    "Buat isian: haluskan kacang tanah goreng dengan gula merah.",
    "Campur tepung ketan dengan air hangat, uleni hingga kalis.",
    "Pipihkan adonan, isi dengan kacang manis, bulatkan.",
    "Rebus ronde dalam air mendidih hingga mengapung.",
    "Rebus jahe dengan gula pasir hingga harum untuk kuah.",
    "Siapkan mangkuk, masukkan ronde.",
    "Tuang kuah jahe dan sedikit santan.",
    "Sajikan wedang ronde selagi hangat.",
])];

$resep_list[] = ["Bajigur", $kat_min, [
    [b("Santan Kelapa"), 100], [b("Gula Merah"), 20], [b("Kopi bubuk instant"), 3],
    [b("Jahe"), 10], [b("Daun Pandan"), 2], [b("Garam"), 1],
], langkah("Bajigur", [
    "Kupas jahe, memarkan.",
    "Rebus santan, gula merah, kopi, jahe, dan daun pandan.",
    "Tambahkan garam.",
    "Masak dengan api kecil hingga mendidih sambil diaduk.",
    "Saring ke dalam gelas atau cangkir.",
    "Sajikan bajigur selagi hangat.",
    "Bajigur khas Sunda siap dinikmati.",
])];

$resep_list[] = ["Bandrek", $kat_min, [
    [b("Jahe"), 20], [b("Gula Merah"), 20], [b("Santan Kelapa"), 50],
    [b("Kayu Manis Bubuk"), 2], [b("Cengkeh kering"), 3], [b("Air"), 200],
], langkah("Bandrek", [
    "Kupas jahe, memarkan.",
    "Rebus air dengan jahe, kayu manis, dan cengkeh.",
    "Tambahkan gula merah dan santan.",
    "Masak hingga mendidih sambil diaduk.",
    "Saring ke dalam gelas saji.",
    "Sajikan bandrek selagi hangat.",
    "Bandrek khas Sunda siap dinikmati.",
])];

$resep_list[] = ["Sekoteng", $kat_min, [
    [b("Jahe"), 15], [b("Gula Pasir"), 15], [b("Kacang tanah goreng"), 15],
    [b("Mie Bihun"), 20], [b("Kol"), 10], [b("Air"), 250],
], langkah("Sekoteng", [
    "Rebus jahe dengan gula pasir hingga harum.",
    "Seduh mie bihun dengan air panas hingga lunak.",
    "Rebus kacang tanah hingga empuk.",
    "Siapkan mangkuk saji.",
    "Masukkan bihun, kacang tanah, dan potongan kol.",
    "Tuang kuah jahe panas.",
    "Sajikan sekoteng selagi hangat.",
])];

$resep_list[] = ["Bir Pletok", $kat_min, [
    [b("Jahe"), 20], [b("Sereh"), 5], [b("Kayu Manis Bubuk"), 2],
    [b("Gula Pasir"), 20], [b("Air"), 250], [b("Pewarna Merah"), 2],
], langkah("Bir Pletok", [
    "Kupas jahe, memarkan.",
    "Rebus air dengan jahe, sereh, dan kayu manis.",
    "Tambahkan gula pasir.",
    "Rebus hingga mendidih dan harum.",
    "Angkat, saring, beri pewarna merah.",
    "Dinginkan dalam kulkas.",
    "Sajikan bir pletok dingin.",
    "Bir pletok khas Betawi siap dinikmati.",
])];

$resep_list[] = ["Teh Tarik", $kat_min, [
    [b("Teh"), 5], [b("Susu Kental Manis"), 20], [b("Air"), 200],
], langkah("Teh Tarik", [
    "Seduh teh dengan air panas, biarkan 3 menit.",
    "Saring teh ke dalam gelas lain.",
    "Tambahkan susu kental manis.",
    "Tarik teh dengan menuangkan dari tinggi ke rendah.",
    "Ulangi beberapa kali hingga berbusa.",
    "Sajikan teh tarik selagi hangat.",
    "Teh tarik khas Melayu siap dinikmati.",
])];

$resep_list[] = ["Kopi Tubruk", $kat_min, [
    [b("Kopi bubuk instant"), 10], [b("Gula Pasir"), 15], [b("Air"), 200],
], langkah("Kopi Tubruk", [
    "Masukkan kopi bubuk dan gula pasir ke dalam gelas.",
    "Didihkan air.",
    "Tuang air panas ke dalam gelas.",
    "Aduk rata hingga gula larut.",
    "Diamkan sebentar hingga ampas kopi mengendap.",
    "Sajikan kopi tubruk selagi hangat.",
])];

$resep_list[] = ["Kopi Jahe", $kat_min, [
    [b("Kopi bubuk instant"), 8], [b("Jahe"), 15], [b("Gula Merah"), 15],
    [b("Air"), 200],
], langkah("Kopi Jahe", [
    "Kupas jahe, memarkan atau iris tipis.",
    "Rebus air dengan jahe hingga mendidih.",
    "Masukkan kopi bubuk dan gula merah.",
    "Aduk rata, angkat.",
    "Saring ke dalam cangkir.",
    "Sajikan kopi jahe selagi hangat.",
])];

$resep_list[] = ["Jus Alpukat", $kat_min, [
    [b("Alpukat segar"), 100], [b("Susu Kental Manis"), 15], [b("Es Batu"), 50],
    [b("Air"), 50],
], langkah("Jus Alpukat", [
    "Belah alpukat, ambil dagingnya.",
    "Masukkan alpukat, susu kental manis, es batu, dan air ke blender.",
    "Blender hingga halus dan tercampur rata.",
    "Tuang ke gelas saji.",
    "Sajikan jus alpukat selagi dingin.",
    "Tambahkan topping coklat atau keju jika suka.",
])];

$resep_list[] = ["Jus Mangga", $kat_min, [
    [b("Mangga"), 100], [b("Gula Pasir"), 10], [b("Es Batu"), 50],
    [b("Air"), 50],
], langkah("Jus Mangga", [
    "Kupas mangga, potong dagingnya.",
    "Masukkan mangga, gula pasir, es batu, dan air ke blender.",
    "Blender hingga halus.",
    "Tuang ke gelas saji.",
    "Sajikan jus mangga selagi dingin.",
])];

$resep_list[] = ["Jus Jambu", $kat_min, [
    [b("Jambu Biji"), 100], [b("Gula Pasir"), 10], [b("Es Batu"), 50],
    [b("Air"), 50],
], langkah("Jus Jambu", [
    "Potong jambu biji, buang bijinya.",
    "Masukkan jambu, gula pasir, es batu, dan air ke blender.",
    "Blender hingga halus.",
    "Saring jika suka tekstur lembut.",
    "Tuang ke gelas saji.",
    "Sajikan jus jambu selagi dingin.",
])];

$resep_list[] = ["Jus Tomat", $kat_min, [
    [b("Tomat"), 100], [b("Gula Pasir"), 10], [b("Es Batu"), 50],
    [b("Air"), 50], [b("Jeruk Nipis"), 5],
], langkah("Jus Tomat", [
    "Potong tomat, buang bijinya.",
    "Masukkan tomat, gula pasir, es batu, dan air ke blender.",
    "Blender hingga halus.",
    "Kucuri jeruk nipis, aduk rata.",
    "Tuang ke gelas saji.",
    "Sajikan jus tomat selagi dingin.",
])];

$resep_list[] = ["Jus Wortel", $kat_min, [
    [b("Wortel"), 100], [b("Susu Sapi"), 50], [b("Gula Pasir"), 10],
    [b("Es Batu"), 50],
], langkah("Jus Wortel", [
    "Kupas wortel, potong kecil-kecil.",
    "Masukkan wortel, susu sapi, gula pasir, dan es batu ke blender.",
    "Blender hingga halus.",
    "Saring jika perlu.",
    "Tuang ke gelas saji.",
    "Sajikan jus wortel selagi dingin.",
])];

$resep_list[] = ["Jus Sirsak", $kat_min, [
    [b("Sirsak"), 100], [b("Susu Kental Manis"), 15], [b("Es Batu"), 50],
    [b("Air"), 50],
], langkah("Jus Sirsak", [
    "Kupas sirsak, ambil daging buahnya.",
    "Masukkan daging sirsak, susu kental manis, es batu, dan air ke blender.",
    "Blender hingga halus.",
    "Saring untuk memisahkan biji.",
    "Tuang ke gelas saji.",
    "Sajikan jus sirsak selagi dingin.",
])];

$resep_list[] = ["Jus Strawberry", $kat_min, [
    [b("Stroberi"), 100], [b("Gula Pasir"), 10], [b("Yogurt"), 50],
    [b("Es Batu"), 30],
], langkah("Jus Strawberry", [
    "Bersihkan stroberi, buang tangkainya.",
    "Masukkan stroberi, gula pasir, yogurt, dan es batu ke blender.",
    "Blender hingga halus.",
    "Tuang ke gelas saji.",
    "Sajikan jus strawberry selagi dingin.",
])];

$resep_list[] = ["Jus Melon", $kat_min, [
    [b("Melon"), 100], [b("Gula Pasir"), 10], [b("Es Batu"), 50],
    [b("Air"), 50],
], langkah("Jus Melon", [
    "Kupas melon, potong dagingnya.",
    "Masukkan melon, gula pasir, es batu, dan air ke blender.",
    "Blender hingga halus.",
    "Tuang ke gelas saji.",
    "Sajikan jus melon selagi dingin.",
])];

$resep_list[] = ["Jus Semangka", $kat_min, [
    [b("Semangka"), 100], [b("Gula Pasir"), 5], [b("Es Batu"), 50],
], langkah("Jus Semangka", [
    "Potong semangka, buang bijinya.",
    "Masukkan semangka, gula pasir, dan es batu ke blender.",
    "Blender hingga halus.",
    "Saring jika suka tekstur lembut.",
    "Tuang ke gelas saji.",
    "Sajikan jus semangka selagi dingin.",
])];

$resep_list[] = ["Es Kopyor", $kat_min, [
    [b("Kelapa Muda air"), 100], [b("Kelapa Muda daging"), 50],
    [b("Sirup"), 20], [b("Susu Kental Manis"), 10], [b("Es Batu"), 50],
], langkah("Es Kopyor", [
    "Keruk daging kelapa muda memanjang seperti kopyor.",
    "Siapkan gelas saji.",
    "Masukkan kerukan kelapa.",
    "Tambahkan es batu.",
    "Tuang air kelapa, sirup, dan susu kental manis.",
    "Aduk rata, sajikan selagi dingin.",
    "Es kopyor siap dinikmati.",
])];

// ============================================================
// KATEGORI 5: CEMILAN & CAMILAN (40)
// ============================================================
$kat_cem = "Cemilan & Camilan";
$resep_list[] = ["Bakwan Jagung", $kat_cem, [
    [b("Jagung Manis"), 100], [b("Tepung Terigu"), 30], [b("Telur Ayam"), 25],
    [b("Daun Bawang"), 5], [b("Bawang Putih"), 3], [b("Minyak Goreng"), 12],
    [b("Garam"), 1], [b("Merica Bubuk"), 1],
], langkah("Bakwan Jagung", [
    "Serut jagung manis dari bonggolnya.",
    "Campur jagung dengan tepung terigu, telur, dan bawang putih halus.",
    "Tambahkan daun bawang iris, garam, dan merica.",
    "Aduk rata hingga adonan kental.",
    "Panaskan minyak dalam wajan.",
    "Sendok adonan ke minyak panas, goreng hingga kecoklatan.",
    "Angkat dan tiriskan.",
    "Sajikan bakwan jagung selagi hangat.",
])];

$resep_list[] = ["Bakwan Sayur", $kat_cem, [
    [b("Tepung Terigu"), 50], [b("Wortel"), 30], [b("Kol"), 20],
    [b("Daun Bawang"), 5], [b("Bawang Putih"), 3], [b("Minyak Goreng"), 15],
    [b("Garam"), 1], [b("Merica Bubuk"), 1],
], langkah("Bakwan Sayur", [
    "Iris wortel dan kol tipis-tipis.",
    "Campur tepung terigu dengan air, bawang putih halus, garam, dan merica.",
    "Masukkan sayuran dan daun bawang, aduk rata.",
    "Panaskan minyak dalam wajan.",
    "Sendok adonan ke minyak panas.",
    "Goreng hingga kuning kecoklatan.",
    "Angkat dan tiriskan.",
    "Sajikan bakwan sayur hangat dengan cabai rawit.",
])];

$resep_list[] = ["Combro", $kat_cem, [
    [b("Singkong"), 100], [b("Oncom Kacang Tanah pepes"), 30],
    [b("Cabai Rawit"), 5], [b("Daun Bawang"), 5], [b("Minyak Goreng"), 12],
    [b("Garam"), 1],
], langkah("Combro", [
    "Kupas singkong, parut halus, peras airnya.",
    "Tumis oncom dengan cabai rawit dan daun bawang.",
    "Campur singkong parut dengan garam.",
    "Ambil adonan singkong, pipihkan.",
    "Isi dengan tumisan oncom, bentuk bulat lonjong.",
    "Goreng dalam minyak panas hingga kecoklatan.",
    "Angkat dan tiriskan.",
    "Sajikan combro selagi hangat.",
])];

$resep_list[] = ["Misro", $kat_cem, [
    [b("Singkong"), 100], [b("Gula Merah"), 20], [b("Minyak Goreng"), 12],
    [b("Garam"), 1],
], langkah("Misro", [
    "Kupas singkong, parut halus, peras airnya.",
    "Campur singkong parut dengan garam.",
    "Ambil adonan, pipihkan.",
    "Isi dengan potongan gula merah.",
    "Bentuk bulat lonjong.",
    "Goreng dalam minyak panas hingga kecoklatan.",
    "Angkat dan tiriskan.",
    "Sajikan misro selagi hangat, gula di dalam akan meleleh.",
])];

$resep_list[] = ["Lemper", $kat_cem, [
    [b("Beras Ketan Putih"), 100], [b("Santan Kelapa"), 50],
    [b("Daging Ayam"), 40], [b("Daun Pisang"), 1], [b("Minyak Goreng"), 5],
    [b("Garam"), 1],
], langkah("Lemper", [
    "Kukus beras ketan hingga setengah matang.",
    "Rebus santan dengan garam, tuang ke ketan, kukus lagi hingga matang.",
    "Tumis daging ayam cincang dengan bumbu hingga matang.",
    "Ambil ketan, pipihkan di atas daun pisang.",
    "Beri isian ayam cincang di tengah.",
    "Gulung dan padatkan.",
    "Potong-potong dan sajikan.",
    "Lemper siap dinikmati sebagai camilan.",
])];

$resep_list[] = ["Lontong Sayur", $kat_cem, [
    [b("Beras"), 100], [b("Daun Pisang"), 1], [b("Labu Siam"), 50],
    [b("Santan Kelapa"), 50], [b("Bawang Merah"), 10], [b("Bawang Putih"), 5],
    [b("Cabai Merah"), 3], [b("Minyak Goreng"), 5],
], langkah("Lontong Sayur", [
    "Cuci beras, masukkan ke dalam daun pisang bentuk silinder.",
    "Rebus lontong hingga matang, potong-potong.",
    "Potong labu siam kecil-kecil.",
    "Haluskan bawang merah, bawang putih, dan cabai merah.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan labu siam dan santan, masak hingga matang.",
    "Sajikan lontong dengan sayur dan taburan bawang goreng.",
])];

$resep_list[] = ["Ketoprak", $kat_cem, [
    [b("Mie Bihun"), 50], [b("Tahu Putih"), 50], [b("Toge"), 30],
    [b("Kacang Tanah atom"), 25], [b("Gula Merah"), 10], [b("Cabai Rawit"), 5],
    [b("Kecap Manis"), 10], [b("Daun Bawang"), 5],
], langkah("Ketoprak", [
    "Seduh bihun dengan air panas hingga lunak, tiriskan.",
    "Goreng tahu hingga kecoklatan, potong dadu.",
    "Goreng kacang tanah, haluskan dengan cabai rawit dan gula merah.",
    "Campur bihun, tahu, dan toge.",
    "Siram dengan bumbu kacang dan kecap manis.",
    "Taburi daun bawang dan bawang goreng.",
    "Sajikan ketoprak dengan kerupuk.",
])];

$resep_list[] = ["Tahu Goreng", $kat_cem, [
    [b("Tahu Putih"), 100], [b("Minyak Goreng"), 12], [b("Garam"), 1],
    [b("Bawang Putih"), 3],
], langkah("Tahu Goreng", [
    "Potong tahu putih sesuai selera.",
    "Lumuri tahu dengan garam dan bawang putih halus.",
    "Diamkan 10 menit.",
    "Panaskan minyak.",
    "Goreng tahu hingga kecoklatan.",
    "Balik agar matang merata.",
    "Angkat dan tiriskan.",
    "Sajikan dengan cabai rawit hijau.",
])];

$resep_list[] = ["Tempe Goreng", $kat_cem, [
    [b("Tempe"), 100], [b("Bawang Putih"), 3], [b("Garam"), 1],
    [b("Minyak Goreng"), 10],
], langkah("Tempe Goreng", [
    "Potong tempe tipis-tipis.",
    "Haluskan bawang putih dan garam.",
    "Lumuri tempe dengan bumbu halus.",
    "Panaskan minyak.",
    "Goreng tempe hingga kecoklatan.",
    "Balik agar matang merata.",
    "Angkat dan tiriskan.",
    "Sajikan selagi hangat.",
])];

$resep_list[] = ["Tahu Bulat", $kat_cem, [
    [b("Tahu Putih"), 100], [b("Bawang Putih"), 3], [b("Garam"), 1],
    [b("Merica Bubuk"), 1], [b("Minyak Goreng"), 15], [b("Tepung Terigu"), 10],
], langkah("Tahu Bulat", [
    "Hancurkan tahu putih hingga halus.",
    "Campur tahu dengan bawang putih halus, garam, dan merica.",
    "Tambahkan tepung terigu, aduk rata.",
    "Bentuk adonan bulat-bulat kecil.",
    "Panaskan minyak.",
    "Goreng tahu bulat hingga kuning kecoklatan.",
    "Angkat dan tiriskan.",
    "Sajikan dengan cabai rawit atau saus sambal.",
])];

$resep_list[] = ["Cimol", $kat_cem, [
    [b("Tepung Tapioka"), 60], [b("Tepung Terigu"), 20], [b("Daun Bawang"), 5],
    [b("Minyak Goreng"), 15], [b("Garam"), 1], [b("Merica Bubuk"), 1],
], langkah("Cimol", [
    "Campur tepung tapioka dan tepung terigu.",
    "Tambahkan daun bawang iris, garam, dan merica.",
    "Tuang air panas sedikit demi sedikit, uleni hingga kalis.",
    "Bentuk adonan bulat-bulat kecil.",
    "Panaskan minyak, goreng cimol hingga mengembang dan kecoklatan.",
    "Angkat dan tiriskan.",
    "Taburi dengan bumbu bubuk sesuai selera.",
    "Sajikan cimol selagi hangat.",
])];

$resep_list[] = ["Cireng", $kat_cem, [
    [b("Tepung Tapioka"), 75], [b("Tepung Terigu"), 20], [b("Bawang Putih"), 3],
    [b("Daun Bawang"), 5], [b("Minyak Goreng"), 15], [b("Garam"), 1],
    [b("Merica Bubuk"), 1],
], langkah("Cireng", [
    "Campur tepung tapioka dan tepung terigu.",
    "Tambahkan bawang putih halus, daun bawang, garam, dan merica.",
    "Tuang air panas, uleni hingga kalis.",
    "Bentuk adonan pipih bulat.",
    "Panaskan minyak.",
    "Goreng cireng hingga mengembang dan kecoklatan.",
    "Angkat dan tiriskan.",
    "Sajikan dengan bumbu rujak atau saus sambal.",
])];

$resep_list[] = ["Pisang Goreng Pasir", $kat_cem, [
    [b("Pisang"), 100], [b("Tepung Terigu"), 30], [b("Tepung Beras"), 15],
    [b("Gula Pasir"), 10], [b("Minyak Goreng"), 12],
], langkah("Pisang Goreng Pasir", [
    "Kupas pisang, potong tipis memanjang.",
    "Campur tepung terigu, tepung beras, gula pasir, dan air.",
    "Celupkan pisang ke adonan tepung.",
    "Gulingkan di tepung kering (tepung pasir).",
    "Panaskan minyak.",
    "Goreng pisang hingga kuning keemasan.",
    "Angkat dan tiriskan.",
    "Sajikan selagi hangat.",
])];

$resep_list[] = ["Singkong Keju", $kat_cem, [
    [b("Singkong"), 150], [b("Keju Cheddar"), 15], [b("Minyak Goreng"), 15],
    [b("Bawang Putih"), 3], [b("Garam"), 1],
], langkah("Singkong Keju", [
    "Kupas singkong, potong bentuk stik.",
    "Rebus singkong dengan bawang putih dan garam hingga empuk.",
    "Tiriskan dan dinginkan.",
    "Panaskan minyak.",
    "Goreng singkong hingga kuning kecoklatan.",
    "Angkat dan tiriskan.",
    "Taburi keju parut di atas singkong goreng.",
    "Sajikan selagi hangat.",
])];

$resep_list[] = ["Ubi Cilembu", $kat_cem, [
    [b("Ubi Jalar"), 150], [b("Margarin"), 5],
], langkah("Ubi Cilembu", [
    "Cuci bersih ubi cilembu, jangan dikupas.",
    "Panggang ubi dalam oven suhu 180 derajat selama 45 menit.",
    "Atau bakar di atas bara api hingga matang.",
    "Ubi akan mengeluarkan cairan manis seperti madu.",
    "Belah ubi, oles dengan margarin.",
    "Sajikan selagi hangat.",
    "Ubi cilembu khas Sumedang siap dinikmati.",
])];

$resep_list[] = ["Kentang Goreng", $kat_cem, [
    [b("Kentang"), 150], [b("Minyak Goreng"), 15], [b("Garam"), 1],
    [b("Bawang Putih Bubuk"), 2],
], langkah("Kentang Goreng", [
    "Kupas kentang, potong bentuk stik.",
    "Rendam dalam air garam selama 15 menit.",
    "Tiriskan dan keringkan.",
    "Panaskan minyak.",
    "Goreng kentang hingga kuning kecoklatan.",
    "Angkat dan tiriskan.",
    "Taburi garam dan bawang putih bubuk.",
    "Sajikan selagi hangat dengan saus sambal.",
])];

$resep_list[] = ["Macaroni Schotel", $kat_cem, [
    [b("Makaroni"), 50], [b("Susu Sapi"), 100], [b("Telur Ayam"), 50],
    [b("Keju Cheddar"), 20], [b("Margarin"), 10], [b("Bawang Putih"), 3],
    [b("Merica Bubuk"), 1], [b("Garam"), 1],
], langkah("Macaroni Schotel", [
    "Rebus makaroni hingga empuk, tiriskan.",
    "Lelehkan margarin, tumis bawang putih cincang.",
    "Campur susu sapi, telur kocok, keju parut, garam, dan merica.",
    "Masukkan makaroni dan tumisan bawang ke campuran susu.",
    "Tuang ke loyang yang dioles margarin.",
    "Taburi keju parut di atasnya.",
    "Panggang dalam oven 180 derajat selama 30 menit.",
    "Potong dan sajikan selagi hangat.",
])];

$resep_list[] = ["Martabak Telur", $kat_cem, [
    [b("Tepung Terigu"), 50], [b("Telur Ayam"), 50], [b("Daun Bawang"), 5],
    [b("Daging Sapi"), 30], [b("Minyak Goreng"), 15], [b("Garam"), 1],
    [b("Merica Bubuk"), 1],
], langkah("Martabak Telur", [
    "Campur tepung terigu dengan air dan garam, uleni hingga kalis.",
    "Gilas adonan tipis hingga tembus pandang.",
    "Kocok telur dengan daging cincang, daun bawang, garam, dan merica.",
    "Panaskan wajan dengan minyak.",
    "Masukkan kulit martabak, tuang isian telur di tengah.",
    "Lipat kulit ke tengah membentuk amplop.",
    "Goreng hingga kecoklatan, balik.",
    "Potong dan sajikan dengan acar.",
])];

$resep_list[] = ["Martabak Manis", $kat_cem, [
    [b("Tepung Terigu"), 60], [b("Telur Ayam"), 25], [b("Gula Pasir"), 20],
    [b("Susu Sapi"), 80], [b("Margarin"), 10], [b("Coklat bubuk"), 5],
    [b("Keju Cheddar"), 10], [b("Susu Kental Manis"), 10],
], langkah("Martabak Manis", [
    "Campur tepung terigu, telur, gula pasir, dan susu sapi.",
    "Aduk hingga licin, diamkan 15 menit.",
    "Panaskan cetakan martabak dengan margarin.",
    "Tuang adonan, putar agar menempel di tepi.",
    "Taburi gula pasir di atasnya, tutup hingga matang.",
    "Oles margarin, taburi coklat bubuk dan keju parut.",
    "Kucuri susu kental manis.",
    "Lipat dan potong, sajikan hangat.",
])];

$resep_list[] = ["Roti Bakar", $kat_cem, [
    [b("Roti Tawar"), 50], [b("Margarin"), 10], [b("Coklat bubuk"), 5],
    [b("Susu Kental Manis"), 10], [b("Keju Cheddar"), 10],
], langkah("Roti Bakar", [
    "Oles margarin di kedua sisi roti tawar.",
    "Panggang roti di teflon hingga kecoklatan.",
    "Balik roti agar matang merata.",
    "Angkat roti bakar.",
    "Oles coklat bubuk atau meses di atasnya.",
    "Taburi keju parut.",
    "Kucuri susu kental manis.",
    "Sajikan roti bakar selagi hangat.",
])];

$resep_list[] = ["Roti Goreng", $kat_cem, [
    [b("Roti Tawar"), 50], [b("Telur Ayam"), 25], [b("Minyak Goreng"), 12],
    [b("Gula Pasir"), 5], [b("Mesies"), 5],
], langkah("Roti Goreng", [
    "Celupkan roti tawar ke kocokan telur.",
    "Pastikan roti terbalut telur merata.",
    "Panaskan minyak.",
    "Goreng roti sebentar hingga kecoklatan.",
    "Angkat dan tiriskan.",
    "Taburi gula pasir dan meses.",
    "Sajikan roti goreng selagi hangat.",
])];

$resep_list[] = ["Sosis Solo", $kat_cem, [
    [b("Daging Ayam"), 80], [b("Tepung Terigu"), 30], [b("Telur Ayam"), 25],
    [b("Susu Sapi"), 50], [b("Bawang Putih"), 5], [b("Minyak Goreng"), 12],
    [b("Garam"), 1], [b("Merica Bubuk"), 1],
], langkah("Sosis Solo", [
    "Rebus daging ayam hingga empuk, cincang halus.",
    "Campur tepung terigu, telur, susu sapi, garam, dan merica.",
    "Masak adonan kulit di wajan anti lengket tipis-tipis.",
    "Campur daging ayam cincang dengan sisa adonan untuk isian.",
    "Ambil kulit, isi dengan adonan daging.",
    "Lipat amplop, goreng dalam minyak panas.",
    "Angkat dan tiriskan.",
    "Sajikan sosis solo dengan cabai rawit hijau.",
])];

$resep_list[] = ["Arem-arem", $kat_cem, [
    [b("Beras"), 100], [b("Santan Kelapa"), 50], [b("Daging Ayam"), 30],
    [b("Daun Pisang"), 1], [b("Bawang Putih"), 3], [b("Garam"), 1],
], langkah("Arem-arem", [
    "Cuci beras, kukus setengah matang.",
    "Rebus santan dengan garam, tuang ke beras, aduk rata.",
    "Kukus kembali hingga matang.",
    "Tumis daging ayam cincang sebagai isian.",
    "Ambil adonan beras, pipihkan.",
    "Beri isian ayam, bulatkan lonjong.",
    "Bungkus dengan daun pisang, semat lidi.",
    "Kukus kembali 15 menit, sajikan.",
])];

$resep_list[] = ["Lontong Balap", $kat_cem, [
    [b("Beras"), 100], [b("Tahu Putih"), 30], [b("Toge"), 20],
    [b("Kecap Manis"), 10], [b("Bawang Goreng"), 5], [b("Kerupuk"), 10],
], langkah("Lontong Balap", [
    "Buat lontong dari beras yang dibungkus daun pisang, rebus hingga matang.",
    "Potong lontong kecil-kecil.",
    "Goreng tahu hingga kecoklatan, potong dadu.",
    "Seduh toge dengan air panas, tiriskan.",
    "Tata lontong, tahu, dan toge di piring.",
    "Siram dengan kecap manis.",
    "Taburi bawang goreng dan kerupuk.",
    "Sajikan lontong balap selagi hangat.",
])];

$resep_list[] = ["Tahu Isi", $kat_cem, [
    [b("Tahu Putih"), 100], [b("Toge"), 20], [b("Wortel"), 20],
    [b("Tepung Terigu"), 30], [b("Bawang Putih"), 5], [b("Minyak Goreng"), 15],
    [b("Garam"), 1], [b("Daun Bawang"), 5],
], langkah("Tahu Isi", [
    "Belah tahu putih segitiga, goreng sebentar, kerok isinya.",
    "Campur tahu yang dikerok dengan toge, wortel serut, dan daun bawang.",
    "Tumis bawang putih, masukkan campuran sayur, beri garam.",
    "Isi tahu dengan tumisan sayur.",
    "Buat adonan tepung dari tepung terigu dan air.",
    "Celupkan tahu isi ke adonan tepung.",
    "Goreng dalam minyak panas hingga kecoklatan.",
    "Angkat dan sajikan hangat.",
])];

$resep_list[] = ["Tempe Mendoan", $kat_cem, [
    [b("Tempe"), 100], [b("Tepung Terigu"), 30], [b("Tepung Beras"), 15],
    [b("Daun Bawang"), 5], [b("Bawang Putih"), 3], [b("Kunyit"), 2],
    [b("Garam"), 1], [b("Minyak Goreng"), 12],
], langkah("Tempe Mendoan", [
    "Potong tempe tipis lebar.",
    "Campur tepung terigu, tepung beras, bawang putih halus, kunyit, garam.",
    "Tambahkan air dan daun bawang iris, aduk kental.",
    "Panaskan minyak.",
    "Celupkan tempe ke adonan tepung.",
    "Goreng sebentar hingga setengah matang.",
    "Angkat saat tepung mulai menguning.",
    "Sajikan dengan kecap pedas atau sambal.",
])];

$resep_list[] = ["Tahu Aci", $kat_cem, [
    [b("Tahu Putih"), 100], [b("Tepung Tapioka"), 30], [b("Daun Bawang"), 5],
    [b("Bawang Putih"), 3], [b("Minyak Goreng"), 15], [b("Garam"), 1],
], langkah("Tahu Aci", [
    "Potong tahu putih segitiga, goreng setengah matang.",
    "Belah tahu, ambil sebagian isinya.",
    "Campur tahu yang diambil dengan tepung tapioka, daun bawang, bawang putih, garam.",
    "Isi adonan tahu aci ke dalam tahu.",
    "Kukus sebentar hingga isian set.",
    "Panaskan minyak.",
    "Goreng tahu aci hingga kecoklatan.",
    "Sajikan dengan cabai rawit atau saus sambal.",
])];

$resep_list[] = ["Batagor", $kat_cem, [
    [b("Ikan Tuna"), 50], [b("Tepung Tapioka"), 25], [b("Tahu Putih"), 50],
    [b("Telur Ayam"), 25], [b("Bawang Putih"), 5], [b("Daun Bawang"), 5],
    [b("Minyak Goreng"), 15], [b("Kacang Tanah atom"), 20],
], langkah("Batagor", [
    "Haluskan ikan tuna dengan bawang putih.",
    "Campur dengan tepung tapioka, telur, dan daun bawang.",
    "Potong tahu segitiga, belah tengah, isi dengan adonan ikan.",
    "Kukus tahu isi selama 15 menit.",
    "Goreng tahu isi hingga kecoklatan.",
    "Haluskan kacang tanah goreng dengan cabai dan gula merah.",
    "Potong batagor, siram dengan bumbu kacang.",
    "Sajikan dengan kecap manis dan jeruk nipis.",
])];

$resep_list[] = ["Siomay", $kat_cem, [
    [b("Ikan Tuna"), 60], [b("Tepung Tapioka"), 25], [b("Telur Ayam"), 25],
    [b("Labu Siam"), 30], [b("Bawang Putih"), 5], [b("Daun Bawang"), 5],
    [b("Kacang Tanah atom"), 20], [b("Kecap Manis"), 10],
], langkah("Siomay", [
    "Kukus labu siam hingga empuk, haluskan.",
    "Campur ikan tuna halus dengan labu siam, tapioka, telur, bawang, daun bawang.",
    "Bentuk adonan bulat lonjong.",
    "Kukus siomay selama 20 menit hingga matang.",
    "Haluskan kacang tanah goreng dengan cabai dan gula merah.",
    "Potong siomay, siram dengan bumbu kacang.",
    "Tambahkan kecap manis dan sambal.",
    "Sajikan siomay dengan tahu goreng dan lontong.",
])];

$resep_list[] = ["Lemper Ayam", $kat_cem, [
    [b("Beras Ketan Putih"), 100], [b("Santan Kelapa"), 50],
    [b("Daging Ayam"), 50], [b("Daun Pisang"), 1], [b("Minyak Goreng"), 5],
    [b("Garam"), 1],
], langkah("Lemper Ayam", [
    "Kukus beras ketan hingga setengah matang.",
    "Rebus santan dengan garam, campur ke ketan, kukus lagi hingga matang.",
    "Suwir daging ayam, tumis dengan bumbu hingga kering.",
    "Ambil ketan, pipihkan di atas daun pisang.",
    "Beri isian ayam suwir di tengah.",
    "Gulung dan padatkan silinder panjang.",
    "Potong-potong dan bungkus lagi dengan daun pisang.",
    "Sajikan lemper ayam sebagai camilan.",
])];

$resep_list[] = ["Kue Lumpur", $kat_cem, [
    [b("Tepung Terigu"), 50], [b("Telur Ayam"), 25], [b("Gula Pasir"), 30],
    [b("Susu Sapi"), 80], [b("Margarin"), 10], [b("Vanilla Bubuk"), 2],
    [b("Kismis"), 10],
], langkah("Kue Lumpur", [
    "Kocok telur dan gula pasir hingga mengembang.",
    "Masukkan tepung terigu bergantian dengan susu sapi.",
    "Tambahkan margarin cair dan vanilla bubuk.",
    "Panaskan cetakan kue lumpur, oles margarin.",
    "Tuang adonan ke cetakan hampir penuh.",
    "Taburi kismis di atasnya.",
    "Tutup dan masak dengan api kecil hingga matang.",
    "Angkat dan sajikan hangat.",
])];

$resep_list[] = ["Kue Bawang", $kat_cem, [
    [b("Tepung Terigu"), 50], [b("Tepung Tapioka"), 20], [b("Bawang Merah"), 10],
    [b("Bawang Putih"), 5], [b("Daun Bawang"), 5], [b("Minyak Goreng"), 20],
    [b("Garam"), 1], [b("Telur Ayam"), 25],
], langkah("Kue Bawang", [
    "Campur tepung terigu, tepung tapioka, dan garam.",
    "Haluskan bawang merah dan bawang putih, campur ke adonan.",
    "Tambahkan telur dan daun bawang iris, uleni hingga kalis.",
    "Gilas adonan tipis, potong kecil-kecil.",
    "Panaskan minyak.",
    "Goreng adonan hingga kuning kecoklatan.",
    "Angkat dan tiriskan.",
    "Simpan dalam toples kedap udara.",
])];

$resep_list[] = ["Stik Keju", $kat_cem, [
    [b("Tepung Terigu"), 50], [b("Keju Cheddar"), 20], [b("Margarin"), 15],
    [b("Telur Ayam"), 25], [b("Garam"), 1],
], langkah("Stik Keju", [
    "Parut keju cheddar halus.",
    "Campur tepung terigu, keju parut, margarin, telur, dan garam.",
    "Uleni hingga kalis.",
    "Gilas adonan tipis, potong memanjang seperti stik.",
    "Tata di loyang yang dioles margarin.",
    "Panggang dalam oven suhu 160 derajat selama 20 menit.",
    "Angkat dan dinginkan.",
    "Simpan dalam toples kedap udara.",
])];

$resep_list[] = ["Putri Salju", $kat_cem, [
    [b("Tepung Terigu"), 50], [b("Margarin"), 25], [b("Gula Halus"), 20],
    [b("Keju Cheddar"), 15],
], langkah("Putri Salju", [
    "Kocok margarin dengan gula halus hingga lembut.",
    "Masukkan tepung terigu dan keju parut, aduk rata.",
    "Uleni hingga bisa dibentuk.",
    "Bentuk adonan bulan sabit atau sesuai selera.",
    "Tata di loyang, panggang oven 150 derajat selama 25 menit.",
    "Angkat dan dinginkan.",
    "Taburi gula halus di atasnya.",
    "Simpan dalam toples kedap udara.",
])];

$resep_list[] = ["Kastengel", $kat_cem, [
    [b("Tepung Terigu"), 50], [b("Keju Cheddar"), 25], [b("Margarin"), 25],
    [b("Telur Ayam"), 25], [b("Garam"), 1],
], langkah("Kastengel", [
    "Parut keju cheddar, sisihkan sebagian untuk taburan.",
    "Kocok margarin dan telur hingga tercampur.",
    "Masukkan tepung terigu, keju parut, dan garam.",
    "Uleni hingga kalis, gilas tipis.",
    "Potong bentuk persegi panjang atau batang.",
    "Oles dengan kuning telur, taburi keju parut.",
    "Panggang oven 150 derajat selama 20 menit.",
    "Dinginkan dan simpan dalam toples.",
])];

// ============================================================
// KATEGORI 6: SUP & SOTO (40)
// ============================================================
$kat_sup = "Sup & Soto";
$resep_list[] = ["Soto Ayam Lamongan", $kat_sup, [
    [b("Daging Ayam"), 150], [b("Nasi Putih"), 200], [b("Toge"), 20],
    [b("Telur Ayam"), 50], [b("Bawang Putih"), 8], [b("Kunyit"), 5],
    [b("Jahe"), 5], [b("Daun Salam"), 2], [b("Sereh"), 5],
], langkah("Soto Ayam Lamongan", [
    "Rebus ayam hingga empuk, angkat dan suwir-suwir.",
    "Haluskan bawang putih, kunyit, jahe, dan garam.",
    "Tumis bumbu halus hingga harum, masukkan daun salam dan sereh.",
    "Tuang kaldu ayam, masak hingga mendidih.",
    "Siapkan mangkuk saji, isi nasi, toge, dan suwiran ayam.",
    "Siram dengan kuah soto panas.",
    "Taburi bawang goreng dan daun bawang.",
    "Sajikan dengan telur rebus dan sambal.",
])];

$resep_list[] = ["Soto Daging Sapi", $kat_sup, [
    [b("Daging Sapi"), 150], [b("Nasi Putih"), 200], [b("Toge"), 20],
    [b("Bawang Putih"), 8], [b("Kunyit"), 5], [b("Jahe"), 5],
    [b("Daun Salam"), 2], [b("Sereh"), 5], [b("Daun Bawang"), 5],
], langkah("Soto Daging Sapi", [
    "Rebus daging sapi hingga empuk, potong dadu.",
    "Haluskan bawang putih, kunyit, jahe, dan garam.",
    "Tumis bumbu halus, masukkan daun salam dan sereh.",
    "Tuang air kaldu daging, masak hingga mendidih.",
    "Siapkan mangkuk, isi nasi, toge, dan potongan daging.",
    "Siram dengan kuah soto panas.",
    "Taburi daun bawang dan bawang goreng.",
    "Sajikan dengan sambal dan jeruk nipis.",
])];

$resep_list[] = ["Soto Kudus", $kat_sup, [
    [b("Daging Ayam"), 150], [b("Nasi Putih"), 200], [b("Daun Bawang"), 10],
    [b("Bawang Putih"), 8], [b("Kemiri"), 8], [b("Jahe"), 5],
    [b("Daun Salam"), 2], [b("Sereh"), 5], [b("Minyak Goreng"), 5],
], langkah("Soto Kudus", [
    "Rebus ayam hingga empuk, suwir-suwir.",
    "Haluskan bawang putih, kemiri, jahe, dan garam.",
    "Tumis bumbu halus, masukkan daun salam dan sereh.",
    "Tuang kaldu ayam, biarkan mendidih.",
    "Siapkan mangkuk, isi nasi, suwiran ayam, dan daun bawang.",
    "Siram kuah soto panas.",
    "Taburi bawang goreng.",
    "Sajikan dengan sambal dan krupuk.",
])];

$resep_list[] = ["Soto Padang", $kat_sup, [
    [b("Daging Sapi"), 150], [b("Nasi Putih"), 200], [b("Kentang"), 30],
    [b("Bawang Putih"), 8], [b("Kunyit"), 5], [b("Jahe"), 5],
    [b("Sereh"), 5], [b("Daun Salam"), 2], [b("Minyak Goreng"), 5],
], langkah("Soto Padang", [
    "Rebus daging sapi hingga empuk, iris tipis.",
    "Goreng kentang iris tipis untuk keripik kentang.",
    "Haluskan bawang putih, kunyit, jahe, dan garam.",
    "Tumis bumbu halus dengan sereh dan daun salam.",
    "Tuang air kaldu daging, masak hingga mendidih.",
    "Siapkan mangkuk, isi nasi, irisan daging, dan keripik kentang.",
    "Siram kuah soto panas.",
    "Sajikan dengan sambal dan perasan jeruk nipis.",
])];

$resep_list[] = ["Soto Betawi", $kat_sup, [
    [b("Daging Sapi"), 150], [b("Santan Kelapa"), 60], [b("Susu Sapi"), 50],
    [b("Kentang"), 30], [b("Bawang Putih"), 8], [b("Kemiri"), 8],
    [b("Jahe"), 5], [b("Daun Salam"), 2], [b("Minyak Goreng"), 5],
], langkah("Soto Betawi", [
    "Rebus daging sapi hingga empuk, potong dadu.",
    "Goreng kentang potong dadu.",
    "Haluskan bawang putih, kemiri, jahe, dan garam.",
    "Tumis bumbu halus, masukkan daun salam.",
    "Tuang kaldu daging, santan, dan susu sapi.",
    "Aduk rata, masak hingga mendidih.",
    "Siapkan mangkuk, isi nasi, daging, dan kentang goreng.",
    "Siram kuah soto, taburi bawang goreng dan daun bawang.",
])];

$resep_list[] = ["Soto Banjar", $kat_sup, [
    [b("Daging Ayam"), 150], [b("Nasi Putih"), 200], [b("Telur Ayam"), 50],
    [b("Mie Bihun"), 20], [b("Bawang Putih"), 8], [b("Kemiri"), 8],
    [b("Jahe"), 5], [b("Daun Salam"), 2], [b("Minyak Goreng"), 5],
], langkah("Soto Banjar", [
    "Rebus ayam hingga empuk, suwir-suwir.",
    "Seduh bihun dengan air panas, tiriskan.",
    "Rebus telur, belah dua.",
    "Haluskan bawang putih, kemiri, jahe, dan garam.",
    "Tumis bumbu halus, masukkan daun salam.",
    "Tuang kaldu ayam, masak hingga mendidih.",
    "Siapkan mangkuk, isi nasi, bihun, suwiran ayam, dan telur.",
    "Siram kuah soto, sajikan hangat.",
])];

$resep_list[] = ["Soto Medan", $kat_sup, [
    [b("Daging Sapi"), 100], [b("Daging Ayam"), 50], [b("Santan Kelapa"), 60],
    [b("Nasi Putih"), 200], [b("Bawang Putih"), 8], [b("Kemiri"), 8],
    [b("Jahe"), 5], [b("Daun Salam"), 2], [b("Minyak Goreng"), 5],
], langkah("Soto Medan", [
    "Rebus daging sapi dan ayam hingga empuk.",
    "Potong daging sapi dadu, suwir ayam.",
    "Haluskan bawang putih, kemiri, jahe, dan garam.",
    "Tumis bumbu halus, masukkan daun salam.",
    "Tuang kaldu dan santan, masak hingga mendidih.",
    "Aduk perlahan agar santan tidak pecah.",
    "Siapkan mangkuk, isi nasi, daging, ayam suwir.",
    "Siram kuah soto, sajikan dengan kerupuk.",
])];

$resep_list[] = ["Soto Sulung", $kat_sup, [
    [b("Daging Ayam"), 150], [b("Santan Kelapa"), 50], [b("Bawang Putih"), 8],
    [b("Kunyit"), 5], [b("Kemiri"), 8], [b("Jahe"), 5],
    [b("Daun Salam"), 2], [b("Minyak Goreng"), 5], [b("Daun Bawang"), 5],
], langkah("Soto Sulung", [
    "Rebus ayam dengan air hingga empuk, suwir-suwir.",
    "Haluskan bawang putih, kunyit, kemiri, jahe, dan garam.",
    "Tumis bumbu halus, masukkan daun salam.",
    "Tuang kaldu ayam dan santan.",
    "Masak hingga mendidih sambil diaduk.",
    "Siapkan mangkuk, isi nasi dan suwiran ayam.",
    "Siram kuah soto, taburi daun bawang dan bawang goreng.",
    "Sajikan hangat dengan sambal.",
])];

$resep_list[] = ["Soto Pekalongan", $kat_sup, [
    [b("Daging Ayam"), 150], [b("Santan Kelapa"), 50], [b("Nasi Putih"), 200],
    [b("Tempe"), 30], [b("Bawang Putih"), 8], [b("Kunyit"), 5],
    [b("Kemiri"), 8], [b("Daun Salam"), 2], [b("Minyak Goreng"), 5],
], langkah("Soto Pekalongan", [
    "Rebus ayam hingga empuk, suwir-suwir.",
    "Goreng tempe, potong dadu.",
    "Haluskan bawang putih, kunyit, kemiri, dan garam.",
    "Tumis bumbu halus, masukkan daun salam.",
    "Tuang kaldu ayam dan santan, aduk rata.",
    "Masak hingga mendidih.",
    "Siapkan mangkuk, isi nasi, suwiran ayam, dan tempe.",
    "Siram kuah soto, sajikan hangat.",
])];

$resep_list[] = ["Soto Ambengan", $kat_sup, [
    [b("Daging Ayam"), 150], [b("Nasi Putih"), 200], [b("Toge"), 20],
    [b("Bawang Putih"), 8], [b("Kunyit"), 5], [b("Jahe"), 5],
    [b("Kemiri"), 8], [b("Daun Salam"), 2], [b("Minyak Goreng"), 5],
], langkah("Soto Ambengan", [
    "Rebus ayam hingga empuk, goreng sebentar, suwir-suwir.",
    "Haluskan bawang putih, kunyit, jahe, kemiri, dan garam.",
    "Tumis bumbu halus, masukkan daun salam.",
    "Tuang kaldu ayam, masak hingga mendidih.",
    "Siapkan mangkuk, isi nasi, toge, dan suwiran ayam.",
    "Siram kuah soto panas.",
    "Taburi daun bawang dan bawang goreng.",
    "Sajikan dengan sambal dan krupuk.",
])];

$resep_list[] = ["Soto Tangkar", $kat_sup, [
    [b("Daging Sapi"), 150], [b("Santan Kelapa"), 60], [b("Nasi Putih"), 200],
    [b("Kentang"), 30], [b("Bawang Putih"), 8], [b("Kemiri"), 8],
    [b("Jahe"), 5], [b("Daun Salam"), 2], [b("Minyak Goreng"), 5],
], langkah("Soto Tangkar", [
    "Rebus daging iga sapi hingga empuk.",
    "Potong kentang dadu, goreng.",
    "Haluskan bawang putih, kemiri, jahe, dan garam.",
    "Tumis bumbu halus, masukkan daun salam.",
    "Tuang kaldu dan santan, aduk rata.",
    "Masak hingga mendidih.",
    "Siapkan mangkuk, isi nasi, daging iga, dan kentang goreng.",
    "Siram kuah soto, sajikan dengan sambal.",
])];

$resep_list[] = ["Sup Ayam Bening", $kat_sup, [
    [b("Daging Ayam"), 150], [b("Wortel"), 30], [b("Kentang"), 30],
    [b("Daun Bawang"), 5], [b("Seledri"), 5], [b("Bawang Putih"), 5],
    [b("Merica Bubuk"), 1], [b("Garam"), 1],
], langkah("Sup Ayam Bening", [
    "Potong ayam kecil-kecil, buang kulitnya.",
    "Iris wortel dan kentang dadu kecil.",
    "Didihkan air, masukkan ayam dan bawang putih geprek.",
    "Masak hingga ayam setengah matang.",
    "Masukkan wortel dan kentang.",
    "Tambahkan garam dan merica bubuk.",
    "Masak hingga sayuran empuk.",
    "Taburi daun bawang dan seledri, sajikan hangat.",
])];

$resep_list[] = ["Sup Ayam Rempah", $kat_sup, [
    [b("Daging Ayam"), 150], [b("Wortel"), 30], [b("Kentang"), 30],
    [b("Bawang Putih"), 5], [b("Jahe"), 5], [b("Daun Salam"), 2],
    [b("Sereh"), 5], [b("Merica Bubuk"), 1], [b("Garam"), 1],
], langkah("Sup Ayam Rempah", [
    "Potong ayam kecil-kecil, cuci bersih.",
    "Didihkan air, masukkan ayam, bawang putih, jahe, daun salam, dan sereh.",
    "Iris wortel dan kentang dadu.",
    "Masukkan sayuran ke dalam sup.",
    "Tambahkan garam dan merica.",
    "Masak hingga ayam dan sayuran empuk.",
    "Koreksi rasa.",
    "Sajikan sup ayam rempah selagi hangat.",
])];

$resep_list[] = ["Sup Ayam Jahe", $kat_sup, [
    [b("Daging Ayam"), 150], [b("Jahe"), 15], [b("Daun Bawang"), 5],
    [b("Wortel"), 20], [b("Jamur Sagu"), 20], [b("Garam"), 1],
    [b("Merica Bubuk"), 1],
], langkah("Sup Ayam Jahe", [
    "Potong ayam kecil-kecil.",
    "Iris jahe tipis-tipis.",
    "Didihkan air, masukkan ayam dan jahe.",
    "Tambahkan irisan wortel dan jamur sagu.",
    "Beri garam dan merica.",
    "Masak hingga ayam empuk dan kaldu harum.",
    "Taburi daun bawang.",
    "Sajikan sup ayam jahe selagi hangat.",
])];

$resep_list[] = ["Sup Ayam Sayur", $kat_sup, [
    [b("Daging Ayam"), 150], [b("Wortel"), 30], [b("Buncis"), 20],
    [b("Kol"), 20], [b("Daun Bawang"), 5], [b("Bawang Putih"), 5],
    [b("Merica Bubuk"), 1], [b("Garam"), 1],
], langkah("Sup Ayam Sayur", [
    "Potong ayam kecil-kecil.",
    "Iris wortel, buncis, dan kol sesuai selera.",
    "Didihkan air, masukkan ayam dan bawang putih geprek.",
    "Masak hingga ayam empuk.",
    "Masukkan wortel dan buncis.",
    "Tambahkan kol, garam, dan merica.",
    "Masak hingga semua sayuran matang.",
    "Taburi daun bawang, sajikan hangat.",
])];

$resep_list[] = ["Sup Daging Sapi", $kat_sup, [
    [b("Daging Sapi"), 150], [b("Wortel"), 30], [b("Kentang"), 30],
    [b("Daun Bawang"), 5], [b("Seledri"), 5], [b("Bawang Putih"), 5],
    [b("Merica Bubuk"), 1], [b("Garam"), 1],
], langkah("Sup Daging Sapi", [
    "Potong daging sapi dadu kecil.",
    "Didihkan air, masukkan daging sapi dan bawang putih geprek.",
    "Masak hingga daging empuk.",
    "Masukkan wortel dan kentang potong dadu.",
    "Tambahkan garam dan merica.",
    "Masak hingga sayuran empuk.",
    "Taburi daun bawang dan seledri.",
    "Sajikan sup daging sapi selagi hangat.",
])];

$resep_list[] = ["Sup Kambing", $kat_sup, [
    [b("Daging Kambing"), 150], [b("Santan Kelapa"), 30], [b("Susu Sapi"), 30],
    [b("Tomat"), 20], [b("Bawang Putih"), 5], [b("Jahe"), 5],
    [b("Merica Bubuk"), 1], [b("Daun Bawang"), 5],
], langkah("Sup Kambing", [
    "Potong daging kambing dadu kecil.",
    "Didihkan air, masukkan daging kambing dan jahe geprek.",
    "Masak hingga daging empuk.",
    "Haluskan bawang putih, tumis hingga harum.",
    "Tuang tumisan ke dalam kuah sup.",
    "Tambahkan santan, susu sapi, tomat, garam, dan merica.",
    "Aduk rata, masak hingga mendidih.",
    "Taburi daun bawang, sajikan hangat.",
])];

$resep_list[] = ["Sup Iga Sapi", $kat_sup, [
    [b("Daging Sapi"), 150], [b("Wortel"), 30], [b("Kentang"), 30],
    [b("Daun Bawang"), 5], [b("Bawang Putih"), 5], [b("Merica Bubuk"), 1],
    [b("Garam"), 1], [b("Seledri"), 5],
], langkah("Sup Iga Sapi", [
    "Rebus iga sapi hingga empuk, buang lemak berlebih.",
    "Potong wortel dan kentang dadu.",
    "Tumis bawang putih cincang hingga harum.",
    "Masukkan tumisan ke dalam rebusan iga.",
    "Tambahkan wortel dan kentang.",
    "Beri garam dan merica.",
    "Masak hingga sayuran empuk.",
    "Taburi daun bawang dan seledri, sajikan hangat.",
])];

$resep_list[] = ["Sup Buntut", $kat_sup, [
    [b("Daging Sapi"), 150], [b("Wortel"), 30], [b("Kentang"), 30],
    [b("Daun Bawang"), 5], [b("Bawang Putih"), 5], [b("Merica Bubuk"), 1],
    [b("Garam"), 1], [b("Minyak Goreng"), 3],
], langkah("Sup Buntut", [
    "Bersihkan buntut sapi, rebus hingga empuk.",
    "Potong wortel dan kentang dadu.",
    "Tumis bawang putih cincang hingga harum.",
    "Masukkan ke dalam kaldu buntut.",
    "Tambahkan wortel dan kentang.",
    "Beri garam dan merica.",
    "Masak hingga sayuran empuk.",
    "Taburi daun bawang, sajikan hangat.",
])];

$resep_list[] = ["Sup Jagung Ayam", $kat_sup, [
    [b("Jagung Manis"), 80], [b("Daging Ayam"), 50], [b("Telur Ayam"), 25],
    [b("Daun Bawang"), 5], [b("Bawang Putih"), 3], [b("Merica Bubuk"), 1],
    [b("Garam"), 1], [b("Minyak Goreng"), 3],
], langkah("Sup Jagung Ayam", [
    "Serut jagung manis dari bonggolnya.",
    "Potong ayam dadu kecil.",
    "Didihkan air, masukkan ayam dan jagung.",
    "Tambahkan bawang putih geprek.",
    "Kocok telur, tuang perlahan sambil diaduk.",
    "Beri garam dan merica.",
    "Masak hingga matang.",
    "Taburi daun bawang, sajikan hangat.",
])];

$resep_list[] = ["Sup Tomat", $kat_sup, [
    [b("Tomat"), 80], [b("Daging Ayam"), 50], [b("Telur Ayam"), 25],
    [b("Daun Bawang"), 5], [b("Bawang Putih"), 3], [b("Garam"), 1],
    [b("Merica Bubuk"), 1], [b("Minyak Goreng"), 3],
], langkah("Sup Tomat", [
    "Potong tomat kecil-kecil.",
    "Potong ayam dadu kecil.",
    "Tumis bawang putih hingga harum.",
    "Masukkan tomat, masak hingga layu.",
    "Tuang air, masukkan ayam.",
    "Kocok telur, tuang perlahan sambil diaduk.",
    "Beri garam dan merica, masak hingga matang.",
    "Taburi daun bawang, sajikan hangat.",
])];

$resep_list[] = ["Sup Brokoli", $kat_sup, [
    [b("Wortel"), 30], [b("Daging Ayam"), 50], [b("Bawang Putih"), 3],
    [b("Merica Bubuk"), 1], [b("Garam"), 1], [b("Minyak Goreng"), 3],
], langkah("Sup Brokoli", [
    "Potong wortel tipis.",
    "Potong ayam dadu kecil.",
    "Didihkan air, masukkan ayam dan bawang putih.",
    "Masukkan wortel.",
    "Beri garam dan merica.",
    "Masak hingga semua bahan empuk.",
    "Angkat dan sajikan hangat.",
    "Taburi bawang goreng jika suka.",
])];

$resep_list[] = ["Sup Sayur Bakso", $kat_sup, [
    [b("Bakso Sapi"), 50], [b("Wortel"), 30], [b("Kol"), 20],
    [b("Daun Bawang"), 5], [b("Bawang Putih"), 3], [b("Merica Bubuk"), 1],
    [b("Garam"), 1],
], langkah("Sup Sayur Bakso", [
    "Potong wortel dan kol sesuai selera.",
    "Didihkan air, masukkan bawang putih geprek.",
    "Masukkan wortel, masak hingga setengah matang.",
    "Masukkan bakso sapi, masak hingga mengapung.",
    "Tambahkan kol, garam, dan merica.",
    "Masak sebentar hingga kol layu.",
    "Taburi daun bawang.",
    "Sajikan sup sayur bakso selagi hangat.",
])];

$resep_list[] = ["Sup Tahu", $kat_sup, [
    [b("Tahu Putih"), 80], [b("Daging Ayam"), 50], [b("Daun Bawang"), 5],
    [b("Bawang Putih"), 3], [b("Merica Bubuk"), 1], [b("Garam"), 1],
    [b("Minyak Goreng"), 3],
], langkah("Sup Tahu", [
    "Potong tahu putih dadu kecil.",
    "Potong ayam dadu kecil.",
    "Didihkan air, masukkan ayam dan bawang putih.",
    "Masak hingga ayam empuk.",
    "Masukkan tahu, garam, dan merica.",
    "Masak sebentar hingga tahu panas.",
    "Taburi daun bawang.",
    "Sajikan sup tahu selagi hangat.",
])];

$resep_list[] = ["Sup Tahu Telur", $kat_sup, [
    [b("Tahu Putih"), 80], [b("Telur Ayam"), 50], [b("Daun Bawang"), 5],
    [b("Bawang Putih"), 3], [b("Merica Bubuk"), 1], [b("Garam"), 1],
    [b("Minyak Goreng"), 3],
], langkah("Sup Tahu Telur", [
    "Potong tahu putih dadu kecil.",
    "Kocok telur lepas.",
    "Didihkan air, masukkan bawang putih geprek.",
    "Masukkan tahu, garam, dan merica.",
    "Tuang telur perlahan sambil diaduk.",
    "Masak hingga telur matang.",
    "Taburi daun bawang.",
    "Sajikan sup tahu telur selagi hangat.",
])];

$resep_list[] = ["Sup Oyong", $kat_sup, [
    [b("Oyong"), 80], [b("Wortel"), 20], [b("Bawang Putih"), 3],
    [b("Merica Bubuk"), 1], [b("Garam"), 1], [b("Minyak Goreng"), 3],
], langkah("Sup Oyong", [
    "Kupas oyong, potong bulat-bulat.",
    "Iris wortel tipis.",
    "Didihkan air, masukkan bawang putih geprek.",
    "Masukkan wortel, masak sebentar.",
    "Masukkan oyong, garam, dan merica.",
    "Masak sebentar saja hingga oyong layu.",
    "Angkat dan sajikan.",
    "Sup oyong ringan dan segar.",
])];

$resep_list[] = ["Sup Lobak", $kat_sup, [
    [b("Lobak"), 80], [b("Daging Ayam"), 50], [b("Daun Bawang"), 5],
    [b("Bawang Putih"), 3], [b("Merica Bubuk"), 1], [b("Garam"), 1],
], langkah("Sup Lobak", [
    "Kupas lobak, potong tipis bulat.",
    "Potong ayam dadu kecil.",
    "Didihkan air, masukkan ayam dan bawang putih.",
    "Masak hingga ayam setengah matang.",
    "Masukkan lobak, garam, dan merica.",
    "Masak hingga lobak empuk dan transparan.",
    "Taburi daun bawang.",
    "Sajikan sup lobak selagi hangat.",
])];

$resep_list[] = ["Sup Ikan", $kat_sup, [
    [b("Ikan Nila"), 150], [b("Daun Bawang"), 5], [b("Seledri"), 5],
    [b("Bawang Putih"), 5], [b("Jahe"), 5], [b("Merica Bubuk"), 1],
    [b("Garam"), 1], [b("Jeruk Nipis"), 5],
], langkah("Sup Ikan", [
    "Bersihkan ikan nila, potong beberapa bagian.",
    "Lumuri ikan dengan jeruk nipis dan garam.",
    "Didihkan air, masukkan bawang putih geprek dan jahe.",
    "Masukkan ikan, masak dengan api kecil.",
    "Tambahkan garam dan merica.",
    "Masak hingga ikan matang.",
    "Taburi daun bawang dan seledri.",
    "Sajikan sup ikan selagi hangat.",
])];

$resep_list[] = ["Sup Udang", $kat_sup, [
    [b("Udang segar"), 100], [b("Wortel"), 20], [b("Daun Bawang"), 5],
    [b("Bawang Putih"), 3], [b("Jahe"), 3], [b("Merica Bubuk"), 1],
    [b("Garam"), 1],
], langkah("Sup Udang", [
    "Kupas udang, buang kepala dan kulit.",
    "Iris wortel tipis.",
    "Didihkan air, masukkan bawang putih dan jahe geprek.",
    "Masukkan wortel, masak sebentar.",
    "Masukkan udang, garam, dan merica.",
    "Masak hingga udang berubah warna dan matang.",
    "Taburi daun bawang.",
    "Sajikan sup udang selagi hangat.",
])];

$resep_list[] = ["Sup Oyong Bakso", $kat_sup, [
    [b("Oyong"), 80], [b("Wortel"), 20], [b("Bakso Sapi"), 30],
    [b("Bawang Putih"), 3], [b("Merica Bubuk"), 1], [b("Garam"), 1],
], langkah("Sup Oyong Bakso", [
    "Kupas oyong, potong bulat.",
    "Iris wortel tipis, potong bakso jadi dua.",
    "Didihkan air, masukkan bawang putih geprek.",
    "Masukkan wortel dan bakso.",
    "Tambahkan garam dan merica.",
    "Masukkan oyong, masak sebentar.",
    "Angkat dan sajikan.",
    "Sup oyong bakso siap dinikmati.",
])];

$resep_list[] = ["Sup Makaroni", $kat_sup, [
    [b("Makaroni"), 30], [b("Daging Ayam"), 50], [b("Wortel"), 20],
    [b("Daun Bawang"), 5], [b("Bawang Putih"), 3], [b("Merica Bubuk"), 1],
    [b("Garam"), 1],
], langkah("Sup Makaroni", [
    "Rebus makaroni hingga empuk, tiriskan.",
    "Potong ayam dan wortel dadu kecil.",
    "Didihkan air, masukkan ayam dan bawang putih.",
    "Masukkan wortel dan makaroni.",
    "Beri garam dan merica.",
    "Masak hingga semua matang.",
    "Taburi daun bawang.",
    "Sajikan sup makaroni selagi hangat.",
])];

$resep_list[] = ["Sup Kacang Merah", $kat_sup, [
    [b("Kacang Merah"), 50], [b("Daging Sapi"), 50], [b("Wortel"), 20],
    [b("Daun Bawang"), 5], [b("Bawang Putih"), 3], [b("Merica Bubuk"), 1],
    [b("Garam"), 1],
], langkah("Sup Kacang Merah", [
    "Rendam kacang merah semalaman, rebus hingga empuk.",
    "Potong daging sapi dadu kecil.",
    "Didihkan air, masukkan daging dan bawang putih.",
    "Masak hingga daging empuk.",
    "Masukkan kacang merah dan wortel.",
    "Beri garam dan merica.",
    "Masak hingga wortel matang.",
    "Taburi daun bawang, sajikan hangat.",
])];

$resep_list[] = ["Sup Kacang Hijau", $kat_sup, [
    [b("Kacang hijau kering"), 50], [b("Gula Merah"), 15], [b("Jahe"), 5],
    [b("Santan Kelapa"), 30], [b("Garam"), 1],
], langkah("Sup Kacang Hijau", [
    "Rendam kacang hijau, rebus hingga pecah dan empuk.",
    "Kupas jahe, memarkan.",
    "Masukkan jahe dan garam ke dalam rebusan kacang hijau.",
    "Tambahkan gula merah, aduk rata.",
    "Masukkan santan, aduk perlahan.",
    "Masak hingga mendidih.",
    "Angkat dan sajikan hangat.",
    "Sup kacang hijau cocok untuk cuaca dingin.",
])];

$resep_list[] = ["Sayur Bening Bayam", $kat_sup, [
    [b("Bayam"), 80], [b("Jagung Manis"), 30], [b("Bawang Putih"), 3],
    [b("Garam"), 1], [b("Gula Pasir"), 3],
], langkah("Sayur Bening Bayam", [
    "Petik daun bayam, cuci bersih.",
    "Potong jagung manis kecil-kecil.",
    "Didihkan air, masukkan jagung dan bawang putih.",
    "Masak hingga jagung empuk.",
    "Masukkan bayam, aduk sebentar.",
    "Tambahkan garam dan gula pasir.",
    "Angkat segera, sajikan.",
    "Sayur bening bayam siap dinikmati.",
])];

$resep_list[] = ["Sayur Bening Oyong", $kat_sup, [
    [b("Oyong"), 80], [b("Wortel"), 20], [b("Bawang Putih"), 3],
    [b("Garam"), 1], [b("Gula Pasir"), 3],
], langkah("Sayur Bening Oyong", [
    "Kupas oyong, potong bulat.",
    "Iris wortel tipis.",
    "Didihkan air, masukkan bawang putih dan wortel.",
    "Masukkan oyong, garam, dan gula.",
    "Masak sebentar hingga oyong layu.",
    "Angkat dan sajikan.",
    "Sayur bening oyong segar dan ringan.",
])];

$resep_list[] = ["Sayur Bayam Jagung", $kat_sup, [
    [b("Bayam"), 80], [b("Jagung Manis"), 40], [b("Wortel"), 20],
    [b("Bawang Putih"), 3], [b("Garam"), 1], [b("Gula Pasir"), 3],
], langkah("Sayur Bayam Jagung", [
    "Petik bayam, cuci bersih.",
    "Potong jagung dan wortel kecil-kecil.",
    "Didihkan air, masukkan jagung dan wortel.",
    "Masak hingga empuk.",
    "Masukkan bayam, garam, dan gula.",
    "Aduk sebentar, angkat.",
    "Sajikan sayur bayam jagung selagi hangat.",
])];

$resep_list[] = ["Sayur Asem", $kat_sup, [
    [b("Kacang Panjang"), 30], [b("Labu Siam"), 30], [b("Jagung Manis"), 30],
    [b("Bayam"), 20], [b("Asam Jawa"), 5], [b("Gula Merah"), 10],
    [b("Bawang Merah"), 5], [b("Garam"), 1],
], langkah("Sayur Asem", [
    "Potong kacang panjang, labu siam, dan jagung.",
    "Didihkan air, masukkan jagung dan labu siam.",
    "Masukkan kacang panjang, asam jawa, dan gula merah.",
    "Beri garam.",
    "Masak hingga sayuran matang.",
    "Terakhir masukkan bayam, aduk hingga layu.",
    "Angkat dan sajikan.",
    "Sayur asem segar siap dinikmati.",
])];

$resep_list[] = ["Sop Ubi", $kat_sup, [
    [b("Singkong"), 80], [b("Daging Sapi"), 50], [b("Daun Bawang"), 5],
    [b("Bawang Putih"), 3], [b("Merica Bubuk"), 1], [b("Garam"), 1],
    [b("Minyak Goreng"), 5],
], langkah("Sop Ubi", [
    "Kupas singkong, potong dadu, goreng hingga kecoklatan.",
    "Potong daging sapi dadu kecil, rebus hingga empuk.",
    "Haluskan bawang putih, tumis hingga harum.",
    "Masukkan tumisan ke dalam rebusan daging.",
    "Tambahkan garam dan merica.",
    "Masukkan singkong goreng ke dalam sup.",
    "Taburi daun bawang, sajikan hangat.",
    "Sop ubi khas Makassar siap dinikmati.",
])];

$resep_list[] = ["Sop Konro", $kat_sup, [
    [b("Daging Sapi"), 150], [b("Kluwek"), 10], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Kunyit"), 3], [b("Jahe"), 5],
    [b("Sereh"), 5], [b("Minyak Goreng"), 8],
], langkah("Sop Konro", [
    "Rebus iga sapi hingga empuk, buang lemak.",
    "Haluskan kluwek, bawang merah, bawang putih, kunyit, dan jahe.",
    "Tumis bumbu halus dengan sereh hingga harum.",
    "Masukkan tumisan ke dalam rebusan iga.",
    "Tambahkan garam, masak hingga bumbu meresap.",
    "Koreksi rasa, angkat.",
    "Sajikan sop konro dengan nasi hangat dan sambal.",
    "Sop konro khas Makassar siap dinikmati.",
])];

$resep_list[] = ["Coto Makassar", $kat_sup, [
    [b("Daging Sapi"), 150], [b("Kacang Tanah atom"), 30], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Kunyit"), 3], [b("Jahe"), 5],
    [b("Sereh"), 5], [b("Minyak Goreng"), 8],
], langkah("Coto Makassar", [
    "Rebus daging sapi hingga empuk, potong dadu.",
    "Sangrai kacang tanah, haluskan.",
    "Haluskan bawang merah, bawang putih, kunyit, dan jahe.",
    "Tumis bumbu halus dengan sereh hingga harum.",
    "Masukkan kacang tanah halus dan air kaldu.",
    "Masukkan daging, masak hingga mendidih.",
    "Koreksi rasa, sajikan dengan ketupat.",
    "Coto Makassar siap dinikmati.",
])];

$resep_list[] = ["Pallubasa", $kat_sup, [
    [b("Daging Sapi"), 150], [b("Kelapa"), 20], [b("Bawang Merah"), 15],
    [b("Bawang Putih"), 8], [b("Kunyit"), 3], [b("Jahe"), 5],
    [b("Sereh"), 5], [b("Minyak Goreng"), 8],
], langkah("Pallubasa", [
    "Rebus daging sapi hingga empuk, potong dadu.",
    "Sangrai kelapa parut, haluskan.",
    "Haluskan bawang merah, bawang putih, kunyit, dan jahe.",
    "Tumis bumbu halus dengan sereh hingga harum.",
    "Masukkan kelapa sangrai halus dan air kaldu.",
    "Masukkan daging, masak hingga mendidih.",
    "Koreksi rasa, sajikan dengan nasi hangat.",
    "Pallubasa khas Makassar siap dinikmati.",
])];

$resep_list[] = ["Sup Krim Jagung", $kat_sup, [
    [b("Jagung Manis"), 100], [b("Susu Sapi"), 100], [b("Telur Ayam"), 25],
    [b("Bawang Putih"), 3], [b("Margarin"), 5], [b("Garam"), 1],
    [b("Merica Bubuk"), 1],
], langkah("Sup Krim Jagung", [
    "Serut jagung manis, blender setengah halus.",
    "Lelehkan margarin, tumis bawang putih cincang.",
    "Masukkan jagung blender dan susu sapi.",
    "Masak dengan api kecil sambil diaduk.",
    "Kocok telur, tuang perlahan sambil diaduk.",
    "Beri garam dan merica.",
    "Masak hingga mengental, sajikan hangat.",
])];

$resep_list[] = ["Sup Merah", $kat_sup, [
    [b("Tomat"), 80], [b("Daging Ayam"), 50], [b("Wortel"), 20],
    [b("Bawang Putih"), 3], [b("Saus Tomat"), 15], [b("Minyak Goreng"), 5],
    [b("Garam"), 1], [b("Gula Pasir"), 3],
], langkah("Sup Merah", [
    "Blender tomat hingga halus.",
    "Potong ayam dan wortel dadu kecil.",
    "Tumis bawang putih hingga harum.",
    "Masukkan tomat blender dan saus tomat.",
    "Tambahkan air, masukkan ayam dan wortel.",
    "Beri garam dan gula pasir.",
    "Masak hingga ayam empuk, sajikan hangat.",
])];

$resep_list[] = ["Sup Tahu Putih", $kat_sup, [
    [b("Tahu Putih"), 80], [b("Daging Ayam"), 50], [b("Daun Bawang"), 5],
    [b("Bawang Putih"), 3], [b("Merica Bubuk"), 1], [b("Garam"), 1],
], langkah("Sup Tahu Putih", [
    "Potong tahu putih dadu kecil.",
    "Potong ayam dadu kecil.",
    "Didihkan air, masukkan ayam dan bawang putih.",
    "Masak hingga ayam empuk.",
    "Masukkan tahu, garam, dan merica.",
    "Masak sebentar hingga tahu panas.",
    "Taburi daun bawang, sajikan hangat.",
])];

$resep_list[] = ["Sup Tomat Ayam", $kat_sup, [
    [b("Tomat"), 80], [b("Daging Ayam"), 50], [b("Telur Ayam"), 25],
    [b("Bawang Putih"), 3], [b("Minyak Goreng"), 5], [b("Garam"), 1],
    [b("Merica Bubuk"), 1],
], langkah("Sup Tomat Ayam", [
    "Blender tomat hingga halus.",
    "Potong ayam dadu kecil.",
    "Tumis bawang putih hingga harum.",
    "Masukkan tomat blender dan air.",
    "Masukkan ayam, masak hingga empuk.",
    "Kocok telur, tuang perlahan sambil diaduk.",
    "Beri garam dan merica, sajikan hangat.",
])];

$resep_list[] = ["Sup Jamur", $kat_sup, [
    [b("Jamur sagu"), 80], [b("Daging Ayam"), 50], [b("Daun Bawang"), 5],
    [b("Bawang Putih"), 3], [b("Merica Bubuk"), 1], [b("Garam"), 1],
    [b("Minyak Goreng"), 5],
], langkah("Sup Jamur", [
    "Seduh jamur sagu dengan air panas hingga lunak, tiriskan.",
    "Potong ayam dadu kecil.",
    "Tumis bawang putih hingga harum.",
    "Masukkan ayam, masak hingga berubah warna.",
    "Tambahkan air, masak hingga ayam empuk.",
    "Masukkan jamur sagu, garam, dan merica.",
    "Taburi daun bawang, sajikan hangat.",
])];

// ============================================================
// INSERT ALL RECIPES
// ============================================================
$total_recipes = 0;
$total_ingredients = 0;
$duplicates = 0;

$stmt_cek = mysqli_prepare($koneksi, "SELECT id FROM resep WHERE judul = ?");
$stmt_cek->bind_param("s", $judul_cek);

$stmt_resep = mysqli_prepare($koneksi, "INSERT INTO resep (id_user, id_kategori, judul, deskripsi, langkah_memasak, jumlah_porsi) VALUES (?, ?, ?, ?, ?, 1)");
$stmt_resep->bind_param("iisss", $id_user_r, $id_kat_r, $judul_r, $deskripsi_r, $langkah_r);

$stmt_bahan = mysqli_prepare($koneksi, "INSERT INTO resep_bahan (id_resep, id_bahan, jumlah_gram) VALUES (?, ?, ?)");
$stmt_bahan->bind_param("iid", $id_resep_baru, $id_bahan_r, $gram_r);

$user_count = count($user_ids);
$idx = 0;

foreach ($resep_list as $recipe) {
    $judul_r = $recipe[0];
    $kategori_name = $recipe[1];
    $bahan_list = $recipe[2];
    $langkah_r = $recipe[3];

    // Check duplicate
    $judul_cek = $judul_r;
    $stmt_cek->execute();
    $stmt_cek->store_result();
    if ($stmt_cek->num_rows > 0) {
        $stmt_cek->free_result();
        $duplicates++;
        continue;
    }
    $stmt_cek->free_result();

    // Assign user round-robin
    $id_user_r = $user_ids[$idx % $user_count];
    $idx++;

    // Get kategori id
    $id_kat_r = $kategori_map[$kategori_name] ?? 2;

    $deskripsi_r = deskripsi($judul_r);

    $stmt_resep->execute();
    $id_resep_baru = mysqli_insert_id($koneksi);
    $total_recipes++;

    foreach ($bahan_list as $b_item) {
        $id_bahan_r = $b_item[0];
        $gram_r = $b_item[1];
        if ($id_bahan_r > 0 && $gram_r > 0) {
            $stmt_bahan->execute();
            $total_ingredients++;
        }
    }

    if ($total_recipes % 25 === 0) {
        echo "<div>? $total_recipes resep tersimpan...</div>\n";
        ob_flush();
        flush();
    }
}

$stmt_cek->close();
$stmt_resep->close();
$stmt_bahan->close();

$success[] = "Resep: $total_recipes resep baru berhasil ditambahkan.";
$success[] = "Bahan: $total_ingredients bahan terhubung ke resep.";
if ($duplicates > 0) {
    $errors[] = "Duplikat: $duplicates resep dilewati (sudah ada).";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Seeder 250 Resep - Selesai</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow p-8">
        <h1 class="text-2xl font-bold text-green-700 mb-4">? Seeder 250 Resep Selesai!</h1>
        <?php foreach ($success as $msg): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-3"><?= $msg ?></div>
        <?php endforeach; ?>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $msg): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-3"><?= $msg ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div class="mt-6 flex gap-3">
            <a href="rekomendasi.php" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">Coba Rekomendasi Resep</a>
            <a href="resep/index.php" class="bg-gray-500 text-white px-5 py-2 rounded hover:bg-gray-500 transition">Ke Resep Saya</a>
        </div>
    </div>
</body>
</html>

<?php
require_once 'koneksi.php';

$success = [];
$errors = [];

// ============================================================
// MAPPING BAHAN KE ID ASLI DATABASE USER (ID <= 1476)
// ============================================================
$bahan_id = [
    'Daging Sapi'          => 362,  'Daging Sapi Berlemak' => 1211,
    'Daging Ayam'          => 30,   'Daging Ayam Kulit'    => 30,
    'Daging Kambing'       => 359,  'Ikan Tuna'            => 654,
    'Ikan Mas'             => 603,  'Ikan Nila'            => 603,
    'Ikan Patin'           => 616,  'Udang'                => 1452,
    'Cumi-cumi'            => 353,  'Telur Ayam'           => 1345,
    'Telur Bebek'          => 1356, 'Tahu Putih'           => 1316,
    'Tempe'                => 1369, 'Nasi Putih'           => 1051,
    'Mie Kuning Basah'     => 1022, 'Mie Bihun'            => 267,
    'Kentang'              => 843,  'Jagung Manis'         => 667,
    'Kangkung'             => 803,  'Sawi Hijau'           => 1235,
    'Kol'                  => 394,  'Wortel'               => 1472,
    'Bayam'                => 84,   'Toge'                 => 1329,
    'Buncis'               => 311,  'Timun'                => 891,
    'Tomat'                => 1435, 'Daun Bawang'          => 364,
    'Seledri'              => 1256, 'Mangga Muda'          => 991,
    'Nanas'                => 1046, 'Pepaya'               => 1112,
    'Jambu Biji'           => 685,  'Pete'                 => 1115,
    'Daun Singkong'        => 428,
    'Bawang Merah'         => 78,   'Bawang Putih'         => 79,
    'Cabai Merah'          => 326,  'Cabai Rawit'          => 327,
    'Jahe'                 => 680,  'Lengkuas'             => 285,
    'Kunyit'               => 944,  'Daun Salam'           => 422,
    'Kemiri'               => 840,  'Ketumbar'             => 894,
    'Merica Bubuk'         => 1018, 'Gula Pasir'           => 520,
    'Gula Merah'           => 522,  'Kecap Manis'          => 820,
    'Terasi'               => 1403,
    'Santan Kelapa'        => 1203, 'Minyak Goreng'        => 1037,
    'Minyak Wijen'         => 1038, 'Margarin'             => 1002,
    'Mentega'              => 1016,
    'Tepung Terigu'        => 1401, 'Tepung Tapioka'       => 1096,
    'Tepung Beras'         => 1379, 'Tepung Roti'          => 1401,
    'Kacang Tanah'         => 774,  'Kacang Tanah Goreng'  => 775,
    'Keju Cheddar'         => 827,  'Susu Cair'            => 1307,
    'Jeruk Nipis'          => 710,  'Cuka'                 => 351,
    'Kerupuk'              => 867,  'Babat'                => 49,
    'Kluwek'               => 897,  'Asam Jawa'            => 26,
    'Kelapa'               => 829,  'Kentang Goreng'       => 843,
    'Kikil Sapi'           => 798,  'Sambal'               => 326,
    'Kayu Manis'           => 422,
];

function b($nama) {
    global $bahan_id;
    return $bahan_id[$nama] ?? 0;
}

// ============================================================
// CEK & SEED USERS
// ============================================================
$dummy_users = [
    ['nama' => 'Andi Pratama', 'email' => 'andi@mail.com'],
    ['nama' => 'Budi Santoso', 'email' => 'budi@mail.com'],
    ['nama' => 'Citra Dewi',   'email' => 'citra@mail.com'],
    ['nama' => 'Dwi Lestari',  'email' => 'dwi@mail.com'],
    ['nama' => 'Eko Putra',    'email' => 'eko@mail.com'],
];
$default_password = password_hash('password123', PASSWORD_DEFAULT);

$user_id_map = [];
$stmt_cek = mysqli_prepare($koneksi, "SELECT id FROM users WHERE email = ?");
$stmt_cek->bind_param('s', $email_cek);
$stmt_ins = mysqli_prepare($koneksi, "INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
$stmt_ins->bind_param('sss', $nama_u, $email_u, $default_password);

$inserted_users = 0;
foreach ($dummy_users as $u) {
    $email_cek = $u['email'];
    $stmt_cek->execute();
    $stmt_cek->store_result();
    if ($stmt_cek->num_rows > 0) {
        $stmt_cek->bind_result($id_existing);
        $stmt_cek->fetch();
        $user_id_map[$u['nama']] = $id_existing;
    } else {
        $stmt_cek->free_result();
        $nama_u = $u['nama'];
        $email_u = $u['email'];
        $stmt_ins->execute();
        $user_id_map[$u['nama']] = mysqli_insert_id($koneksi);
        $inserted_users++;
    }
}
$stmt_cek->close();
$stmt_ins->close();
$success[] = "User: $inserted_users baru ditambahkan, total " . count($dummy_users) . " user dummy tersedia.";

// ============================================================
// AMBIL KATEGORI
// ============================================================
$kategori_map = [];
$kat_result = mysqli_query($koneksi, "SELECT id, nama_kategori FROM kategori_resep");
while ($k = $kat_result->fetch_assoc()) {
    $kategori_map[$k['nama_kategori']] = $k['id'];
}
$user_names = array_keys($user_id_map);

// ============================================================
// DEFINISI 20 JENIS MAKANAN × 5 VARIAN
// ============================================================
function deskripsi_resep($judul, $jenis) {
    $awal = [
        'Bakso' => 'Bakso ', 'Nasi Goreng' => 'Nasi goreng ',
        'Sate' => 'Sate ', 'Soto' => 'Soto ',
        'Mie Goreng' => 'Mie goreng ', 'Gado-gado' => 'Gado-gado ',
        'Rendang' => 'Rendang ', 'Ayam Goreng' => 'Ayam goreng ',
        'Ikan Bakar' => 'Ikan bakar ', 'Nasi Uduk' => 'Nasi uduk ',
        'Gulai' => 'Gulai ', 'Opor Ayam' => 'Opor ayam ',
        'Rawon' => 'Rawon ', 'Sop Iga' => 'Sop iga ',
        'Tumis Kangkung' => 'Tumis kangkung ', 'Capcay' => 'Capcay ',
        'Pepes Ikan' => 'Pepes ikan ', 'Perkedel' => 'Perkedel ',
        'Rujak Buah' => 'Rujak buah ', 'Bubur Ayam' => 'Bubur ayam ',
    ];
    return ($awal[$jenis] ?? 'Resep ') . 'lezat khas Nusantara. Cocok untuk hidangan sehari-hari keluarga.';
}

$food_types = [];

// ----- 1. BAKSO -----
$food_types['bakso'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Bakso Ayam Kukus', 4, [
            [b('Daging Ayam'), 120], [b('Tepung Tapioka'), 30], [b('Bawang Putih'), 5],
            [b('Daun Bawang'), 10], [b('Merica Bubuk'), 1],
        ]],
        ['Bakso Ikan Kuah', 4, [
            [b('Ikan Tuna'), 130], [b('Tepung Tapioka'), 35], [b('Bawang Putih'), 5],
            [b('Seledri'), 5], [b('Jeruk Nipis'), 5],
        ]],
        ['Bakso Sapi Rebus', 4, [
            [b('Daging Sapi'), 150], [b('Tepung Tapioka'), 40], [b('Bawang Putih'), 8],
            [b('Merica Bubuk'), 1], [b('Daun Bawang'), 10],
        ]],
        ['Bakso Goreng', 3, [
            [b('Daging Sapi'), 150], [b('Tepung Terigu'), 50], [b('Telur Ayam'), 25],
            [b('Minyak Goreng'), 20], [b('Bawang Putih'), 5],
        ]],
        ['Bakso Keju Goreng', 3, [
            [b('Daging Sapi'), 150], [b('Keju Cheddar'), 50], [b('Tepung Terigu'), 50],
            [b('Telur Ayam'), 25], [b('Minyak Goreng'), 30], [b('Tepung Roti'), 30],
            [b('Bawang Putih'), 5],
        ]],
    ]
];

// ----- 2. NASI GORENG -----
$food_types['nasi goreng'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Nasi Goreng Sayur', 3, [
            [b('Nasi Putih'), 250], [b('Wortel'), 30], [b('Buncis'), 20], [b('Kol'), 20],
            [b('Bawang Merah'), 10], [b('Bawang Putih'), 5], [b('Minyak Goreng'), 5],
        ]],
        ['Nasi Goreng Ayam', 3, [
            [b('Nasi Putih'), 300], [b('Daging Ayam'), 60], [b('Telur Ayam'), 25],
            [b('Bawang Merah'), 10], [b('Bawang Putih'), 5], [b('Kecap Manis'), 10],
            [b('Minyak Goreng'), 8],
        ]],
        ['Nasi Goreng Sapi', 3, [
            [b('Nasi Putih'), 300], [b('Daging Sapi'), 70], [b('Telur Ayam'), 50],
            [b('Bawang Merah'), 10], [b('Bawang Putih'), 5], [b('Kecap Manis'), 15],
            [b('Minyak Goreng'), 10],
        ]],
        ['Nasi Goreng Pete', 3, [
            [b('Nasi Putih'), 350], [b('Pete'), 20], [b('Daging Ayam'), 60],
            [b('Telur Ayam'), 50], [b('Bawang Merah'), 15], [b('Bawang Putih'), 8],
            [b('Kecap Manis'), 15], [b('Minyak Goreng'), 12], [b('Terasi'), 5],
        ]],
        ['Nasi Goreng Kambing', 3, [
            [b('Nasi Putih'), 350], [b('Daging Kambing'), 80], [b('Telur Ayam'), 50],
            [b('Bawang Merah'), 15], [b('Bawang Putih'), 8], [b('Kecap Manis'), 15],
            [b('Minyak Goreng'), 15], [b('Cabai Merah'), 10], [b('Margarin'), 10],
        ]],
    ]
];

// ----- 3. SATE -----
$food_types['sate'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Sate Ayam Bumbu Kacang', 4, [
            [b('Daging Ayam'), 150], [b('Kacang Tanah'), 30], [b('Bawang Merah'), 10],
            [b('Bawang Putih'), 5], [b('Cabai Merah'), 5], [b('Kecap Manis'), 10],
            [b('Gula Merah'), 10], [b('Jeruk Nipis'), 5],
        ]],
        ['Sate Lilit Ikan', 4, [
            [b('Ikan Tuna'), 160], [b('Kelapa'), 20], [b('Bawang Merah'), 10],
            [b('Bawang Putih'), 5], [b('Cabai Rawit'), 3], [b('Kunyit'), 3],
            [b('Garam'), 1], [b('Gula Pasir'), 2],
        ]],
        ['Sate Sapi Madura', 4, [
            [b('Daging Sapi'), 160], [b('Kacang Tanah'), 35], [b('Kecap Manis'), 15],
            [b('Bawang Merah'), 15], [b('Bawang Putih'), 5], [b('Cabai Merah'), 5],
            [b('Gula Merah'), 10],
        ]],
        ['Sate Kambing', 4, [
            [b('Daging Kambing'), 180], [b('Kecap Manis'), 15], [b('Bawang Merah'), 15],
            [b('Bawang Putih'), 5], [b('Cabai Rawit'), 5], [b('Minyak Goreng'), 10],
            [b('Tomat'), 30],
        ]],
        ['Sate Kulit Ayam', 4, [
            [b('Daging Ayam Kulit'), 150], [b('Kecap Manis'), 20], [b('Minyak Goreng'), 15],
            [b('Bawang Merah'), 15], [b('Bawang Putih'), 8], [b('Cabai Rawit'), 5],
            [b('Kacang Tanah Goreng'), 40],
        ]],
    ]
];

// ----- 4. SOTO -----
$food_types['soto'] = [
    'kategori' => 'Sup & Soto',
    'variants' => [
        ['Soto Ayam Bening', 4, [
            [b('Daging Ayam'), 120], [b('Nasi Putih'), 200], [b('Toge'), 30],
            [b('Daun Bawang'), 10], [b('Seledri'), 5], [b('Bawang Putih'), 5],
            [b('Kunyit'), 3], [b('Jahe'), 3],
        ]],
        ['Soto Ayam Kuning', 4, [
            [b('Daging Ayam'), 130], [b('Nasi Putih'), 250], [b('Toge'), 30],
            [b('Daun Bawang'), 10], [b('Seledri'), 5], [b('Bawang Putih'), 5],
            [b('Kunyit'), 10], [b('Kemiri'), 10], [b('Santan Kelapa'), 30],
        ]],
        ['Soto Betawi', 4, [
            [b('Daging Sapi'), 150], [b('Nasi Putih'), 250], [b('Santan Kelapa'), 60],
            [b('Susu Cair'), 50], [b('Kentang'), 50], [b('Tomat'), 20],
            [b('Bawang Putih'), 8], [b('Kemiri'), 10],
        ]],
        ['Soto Banjar', 4, [
            [b('Daging Ayam'), 150], [b('Nasi Putih'), 300], [b('Telur Ayam'), 50],
            [b('Mie Bihun'), 30], [b('Kentang'), 50], [b('Santan Kelapa'), 40],
            [b('Bawang Putih'), 8], [b('Kayu Manis'), 2],
        ]],
        ['Soto Medan', 4, [
            [b('Daging Sapi'), 150], [b('Daging Ayam'), 60], [b('Santan Kelapa'), 80],
            [b('Nasi Putih'), 300], [b('Toge'), 30], [b('Kentang Goreng'), 40],
            [b('Kerupuk'), 20], [b('Telur Ayam'), 50], [b('Kacang Tanah Goreng'), 20],
        ]],
    ]
];

// ----- 5. MIE GORENG -----
$food_types['mie goreng'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Mie Goreng Sayur', 3, [
            [b('Mie Kuning Basah'), 200], [b('Sawi Hijau'), 30], [b('Wortel'), 30],
            [b('Kol'), 20], [b('Bawang Merah'), 10], [b('Bawang Putih'), 5],
            [b('Minyak Goreng'), 5], [b('Daun Bawang'), 10],
        ]],
        ['Mie Goreng Ayam', 3, [
            [b('Mie Kuning Basah'), 250], [b('Daging Ayam'), 50], [b('Telur Ayam'), 25],
            [b('Sawi Hijau'), 20], [b('Bawang Merah'), 10], [b('Bawang Putih'), 5],
            [b('Kecap Manis'), 10], [b('Minyak Goreng'), 8], [b('Daun Bawang'), 10],
        ]],
        ['Mie Goreng Seafood', 3, [
            [b('Mie Kuning Basah'), 250], [b('Udang'), 40], [b('Cumi-cumi'), 40],
            [b('Telur Ayam'), 25], [b('Sawi Hijau'), 20], [b('Bawang Merah'), 10],
            [b('Bawang Putih'), 8], [b('Minyak Goreng'), 10],
        ]],
        ['Mie Goreng Sapi', 3, [
            [b('Mie Kuning Basah'), 300], [b('Daging Sapi'), 60], [b('Telur Ayam'), 50],
            [b('Bawang Merah'), 10], [b('Bawang Putih'), 8], [b('Kecap Manis'), 15],
            [b('Minyak Goreng'), 12], [b('Cabai Merah'), 5], [b('Daun Bawang'), 10],
        ]],
        ['Mie Goreng Kari', 3, [
            [b('Mie Kuning Basah'), 300], [b('Daging Ayam'), 60], [b('Telur Ayam'), 50],
            [b('Bawang Merah'), 15], [b('Bawang Putih'), 8], [b('Kunyit'), 5],
            [b('Santan Kelapa'), 40], [b('Minyak Goreng'), 15], [b('Cabai Merah'), 10],
        ]],
    ]
];

// ----- 6. GADO-GADO -----
$food_types['gado-gado'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Gado-gado Tahu', 3, [
            [b('Tahu Putih'), 100], [b('Toge'), 40], [b('Kangkung'), 30], [b('Kol'), 30],
            [b('Kacang Tanah'), 25], [b('Gula Merah'), 10], [b('Cabai Rawit'), 5],
            [b('Bawang Putih'), 3], [b('Terasi'), 3], [b('Jeruk Nipis'), 5],
        ]],
        ['Gado-gado Tempe', 3, [
            [b('Tempe'), 80], [b('Tahu Putih'), 50], [b('Toge'), 40], [b('Kangkung'), 30],
            [b('Kacang Tanah'), 30], [b('Gula Merah'), 10], [b('Cabai Rawit'), 5],
            [b('Bawang Putih'), 3], [b('Terasi'), 3], [b('Kerupuk'), 10],
        ]],
        ['Gado-gado Telur', 3, [
            [b('Telur Ayam'), 100], [b('Tahu Putih'), 50], [b('Tempe'), 50],
            [b('Toge'), 40], [b('Kangkung'), 30], [b('Kol'), 30],
            [b('Kacang Tanah'), 35], [b('Gula Merah'), 15], [b('Cabai Merah'), 5],
            [b('Bawang Putih'), 5], [b('Kerupuk'), 15],
        ]],
        ['Gado-gado Komplit', 3, [
            [b('Telur Ayam'), 100], [b('Tahu Putih'), 60], [b('Tempe'), 60],
            [b('Toge'), 50], [b('Kangkung'), 40], [b('Kol'), 40], [b('Kentang'), 50],
            [b('Kacang Tanah'), 40], [b('Gula Merah'), 15], [b('Bawang Putih'), 5],
            [b('Kerupuk'), 20],
        ]],
        ['Gado-gado Goreng', 3, [
            [b('Tahu Putih'), 80], [b('Tempe'), 80], [b('Telur Ayam'), 100],
            [b('Kacang Tanah Goreng'), 50], [b('Minyak Goreng'), 15], [b('Toge'), 30],
            [b('Kerupuk'), 20], [b('Cabai Merah'), 10],
        ]],
    ]
];

// ----- 7. RENDANG -----
$food_types['rendang'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Rendang Ayam', 5, [
            [b('Daging Ayam'), 200], [b('Santan Kelapa'), 50], [b('Bawang Merah'), 20],
            [b('Bawang Putih'), 10], [b('Cabai Merah'), 10], [b('Kunyit'), 5],
            [b('Jahe'), 5], [b('Lengkuas'), 10], [b('Daun Salam'), 3],
        ]],
        ['Rendang Sapi Tanpa Santan', 5, [
            [b('Daging Sapi'), 220], [b('Bawang Merah'), 25], [b('Bawang Putih'), 10],
            [b('Cabai Merah'), 15], [b('Kunyit'), 5], [b('Jahe'), 5],
            [b('Lengkuas'), 10], [b('Kemiri'), 10],
        ]],
        ['Rendang Sapi', 5, [
            [b('Daging Sapi'), 250], [b('Santan Kelapa'), 100], [b('Bawang Merah'), 25],
            [b('Bawang Putih'), 10], [b('Cabai Merah'), 15], [b('Kunyit'), 5],
            [b('Jahe'), 5], [b('Lengkuas'), 10], [b('Daun Salam'), 3],
        ]],
        ['Rendang Padang', 5, [
            [b('Daging Sapi'), 300], [b('Santan Kelapa'), 150], [b('Bawang Merah'), 30],
            [b('Bawang Putih'), 15], [b('Cabai Merah'), 20], [b('Kunyit'), 5],
            [b('Jahe'), 5], [b('Lengkuas'), 10], [b('Daun Salam'), 3],
            [b('Gula Merah'), 15],
        ]],
        ['Rendang Jeroan', 5, [
            [b('Daging Sapi Berlemak'), 250], [b('Babat'), 100], [b('Kikil Sapi'), 50],
            [b('Santan Kelapa'), 150], [b('Bawang Merah'), 30], [b('Bawang Putih'), 15],
            [b('Cabai Merah'), 25], [b('Kunyit'), 5], [b('Jahe'), 5],
            [b('Lengkuas'), 10], [b('Daun Salam'), 3], [b('Minyak Goreng'), 15],
        ]],
    ]
];

// ----- 8. AYAM GORENG -----
$food_types['ayam goreng'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Ayam Goreng Tanpa Kulit', 4, [
            [b('Daging Ayam'), 200], [b('Bawang Putih'), 8], [b('Kunyit'), 5],
            [b('Jahe'), 5], [b('Merica Bubuk'), 1], [b('Minyak Goreng'), 5],
        ]],
        ['Ayam Goreng Tepung', 4, [
            [b('Daging Ayam Kulit'), 200], [b('Tepung Terigu'), 40], [b('Telur Ayam'), 25],
            [b('Bawang Putih'), 8], [b('Merica Bubuk'), 1], [b('Minyak Goreng'), 12],
        ]],
        ['Ayam Goreng Mentega', 4, [
            [b('Daging Ayam Kulit'), 200], [b('Margarin'), 20], [b('Bawang Putih'), 10],
            [b('Kecap Manis'), 10], [b('Gula Pasir'), 5], [b('Daun Bawang'), 10],
            [b('Minyak Goreng'), 10],
        ]],
        ['Ayam Goreng Kremes', 4, [
            [b('Daging Ayam Kulit'), 250], [b('Tepung Beras'), 30], [b('Tepung Tapioka'), 20],
            [b('Bawang Putih'), 10], [b('Kunyit'), 5], [b('Minyak Goreng'), 20],
            [b('Telur Ayam'), 25],
        ]],
        ['Ayam Goreng Crispy', 4, [
            [b('Daging Ayam Kulit'), 250], [b('Tepung Terigu'), 60], [b('Tepung Roti'), 50],
            [b('Telur Ayam'), 50], [b('Bawang Putih'), 10], [b('Merica Bubuk'), 1],
            [b('Minyak Goreng'), 30], [b('Cabai Merah'), 10],
        ]],
    ]
];

// ----- 9. IKAN BAKAR -----
$food_types['ikan bakar'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Ikan Bakar Bumbu Kuning', 4, [
            [b('Ikan Nila'), 200], [b('Kunyit'), 8], [b('Bawang Putih'), 5],
            [b('Jahe'), 5], [b('Minyak Goreng'), 5],
        ]],
        ['Ikan Bakar Kecap', 4, [
            [b('Ikan Mas'), 200], [b('Kecap Manis'), 20], [b('Bawang Merah'), 10],
            [b('Bawang Putih'), 5], [b('Cabai Merah'), 5], [b('Minyak Goreng'), 8],
            [b('Jeruk Nipis'), 5],
        ]],
        ['Ikan Bakar Padang', 4, [
            [b('Ikan Patin'), 200], [b('Santan Kelapa'), 50], [b('Kunyit'), 5],
            [b('Bawang Merah'), 10], [b('Bawang Putih'), 5], [b('Cabai Merah'), 10],
            [b('Jahe'), 5], [b('Lengkuas'), 5],
        ]],
        ['Ikan Bakar Jimbaran', 4, [
            [b('Ikan Tuna'), 220], [b('Bawang Merah'), 10], [b('Bawang Putih'), 8],
            [b('Cabai Rawit'), 5], [b('Jeruk Nipis'), 10], [b('Minyak Goreng'), 10],
            [b('Kecap Manis'), 15],
        ]],
        ['Ikan Bakar Sambal Matah', 4, [
            [b('Ikan Mas'), 220], [b('Bawang Merah'), 20], [b('Cabai Rawit'), 10],
            [b('Jeruk Nipis'), 10], [b('Minyak Goreng'), 15], [b('Nasi Putih'), 200],
        ]],
    ]
];

// ----- 10. NASI UDUK -----
$food_types['nasi uduk'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Nasi Uduk Telur', 3, [
            [b('Nasi Putih'), 300], [b('Santan Kelapa'), 30], [b('Telur Ayam'), 50],
            [b('Daun Salam'), 2], [b('Gula Pasir'), 1],
        ]],
        ['Nasi Uduk Ayam', 3, [
            [b('Nasi Putih'), 300], [b('Santan Kelapa'), 40], [b('Daging Ayam'), 80],
            [b('Telur Ayam'), 25], [b('Daun Salam'), 2], [b('Kerupuk'), 10],
        ]],
        ['Nasi Uduk Semur', 3, [
            [b('Nasi Putih'), 350], [b('Santan Kelapa'), 40], [b('Daging Sapi'), 80],
            [b('Telur Ayam'), 50], [b('Kecap Manis'), 15], [b('Bawang Merah'), 10],
            [b('Bawang Putih'), 5], [b('Daun Salam'), 2], [b('Kerupuk'), 10],
        ]],
        ['Nasi Uduk Komplit', 3, [
            [b('Nasi Putih'), 350], [b('Santan Kelapa'), 50], [b('Daging Ayam'), 60],
            [b('Telur Ayam'), 50], [b('Tempe'), 50], [b('Tahu Putih'), 50],
            [b('Daun Salam'), 2], [b('Kerupuk'), 15], [b('Kecap Manis'), 10],
        ]],
        ['Nasi Uduk Goreng', 3, [
            [b('Nasi Putih'), 400], [b('Santan Kelapa'), 60], [b('Daging Ayam'), 80],
            [b('Telur Ayam'), 50], [b('Minyak Goreng'), 10], [b('Bawang Merah'), 15],
            [b('Bawang Putih'), 8], [b('Kerupuk'), 20], [b('Kacang Tanah Goreng'), 20],
            [b('Cabai Merah'), 10],
        ]],
    ]
];

// ----- 11. GULAI -----
$food_types['gulai'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Gulai Ayam', 4, [
            [b('Daging Ayam'), 150], [b('Santan Kelapa'), 50], [b('Bawang Merah'), 15],
            [b('Bawang Putih'), 8], [b('Cabai Merah'), 5], [b('Kunyit'), 5],
            [b('Jahe'), 5], [b('Daun Salam'), 2],
        ]],
        ['Gulai Ikan', 4, [
            [b('Ikan Tuna'), 180], [b('Santan Kelapa'), 50], [b('Bawang Merah'), 15],
            [b('Bawang Putih'), 8], [b('Cabai Merah'), 5], [b('Kunyit'), 5],
            [b('Asam Jawa'), 5],
        ]],
        ['Gulai Kambing', 4, [
            [b('Daging Kambing'), 180], [b('Santan Kelapa'), 80], [b('Bawang Merah'), 20],
            [b('Bawang Putih'), 10], [b('Cabai Merah'), 10], [b('Kunyit'), 5],
            [b('Jahe'), 5], [b('Lengkuas'), 5], [b('Daun Salam'), 2],
            [b('Minyak Goreng'), 10],
        ]],
        ['Gulai Cumi', 4, [
            [b('Cumi-cumi'), 200], [b('Santan Kelapa'), 100], [b('Bawang Merah'), 20],
            [b('Bawang Putih'), 10], [b('Cabai Merah'), 10], [b('Kunyit'), 5],
            [b('Jahe'), 5], [b('Daun Salam'), 2], [b('Minyak Goreng'), 10],
        ]],
        ['Gulai Babat', 4, [
            [b('Babat'), 200], [b('Santan Kelapa'), 120], [b('Bawang Merah'), 20],
            [b('Bawang Putih'), 10], [b('Cabai Merah'), 15], [b('Kunyit'), 5],
            [b('Jahe'), 5], [b('Lengkuas'), 10], [b('Daun Salam'), 2],
            [b('Minyak Goreng'), 15],
        ]],
    ]
];

// ----- 12. OPOR AYAM -----
$food_types['opor ayam'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Opor Ayam Tanpa Santan', 4, [
            [b('Daging Ayam'), 180], [b('Susu Cair'), 50], [b('Bawang Merah'), 15],
            [b('Bawang Putih'), 8], [b('Jahe'), 5], [b('Lengkuas'), 5],
            [b('Daun Salam'), 2],
        ]],
        ['Opor Ayam Putih', 4, [
            [b('Daging Ayam'), 200], [b('Santan Kelapa'), 50], [b('Bawang Merah'), 15],
            [b('Bawang Putih'), 8], [b('Jahe'), 5], [b('Lengkuas'), 5],
            [b('Daun Salam'), 2],
        ]],
        ['Opor Ayam Kuning', 4, [
            [b('Daging Ayam'), 200], [b('Santan Kelapa'), 60], [b('Kunyit'), 8],
            [b('Bawang Merah'), 15], [b('Bawang Putih'), 8], [b('Kemiri'), 10],
            [b('Jahe'), 5], [b('Lengkuas'), 5], [b('Daun Salam'), 2],
        ]],
        ['Opor Ayam Kampung', 4, [
            [b('Daging Ayam Kulit'), 220], [b('Santan Kelapa'), 80], [b('Kunyit'), 8],
            [b('Bawang Merah'), 20], [b('Bawang Putih'), 10], [b('Kemiri'), 10],
            [b('Jahe'), 5], [b('Lengkuas'), 10], [b('Daun Salam'), 2],
            [b('Gula Pasir'), 5],
        ]],
        ['Opor Ayam Kremesan', 4, [
            [b('Daging Ayam Kulit'), 250], [b('Santan Kelapa'), 100], [b('Kunyit'), 10],
            [b('Bawang Merah'), 20], [b('Bawang Putih'), 10], [b('Kemiri'), 15],
            [b('Jahe'), 5], [b('Lengkuas'), 10], [b('Daun Salam'), 2],
            [b('Minyak Goreng'), 10],
        ]],
    ]
];

// ----- 13. RAWON -----
$food_types['rawon'] = [
    'kategori' => 'Sup & Soto',
    'variants' => [
        ['Rawon Daging Sapi', 4, [
            [b('Daging Sapi'), 150], [b('Kluwek'), 15], [b('Bawang Merah'), 15],
            [b('Bawang Putih'), 8], [b('Jahe'), 5], [b('Daun Salam'), 2],
            [b('Toge'), 30],
        ]],
        ['Rawon Ayam', 4, [
            [b('Daging Ayam'), 180], [b('Kluwek'), 15], [b('Bawang Merah'), 15],
            [b('Bawang Putih'), 8], [b('Jahe'), 5], [b('Daun Salam'), 2],
            [b('Toge'), 30], [b('Nasi Putih'), 200],
        ]],
        ['Rawon Gajebo', 4, [
            [b('Daging Sapi Berlemak'), 180], [b('Kluwek'), 20], [b('Bawang Merah'), 20],
            [b('Bawang Putih'), 10], [b('Lengkuas'), 10], [b('Daun Salam'), 2],
            [b('Nasi Putih'), 250], [b('Toge'), 30], [b('Telur Ayam'), 50],
        ]],
        ['Rawon Iga', 4, [
            [b('Daging Sapi'), 200], [b('Kikil Sapi'), 50], [b('Kluwek'), 20],
            [b('Bawang Merah'), 20], [b('Bawang Putih'), 10], [b('Lengkuas'), 10],
            [b('Daun Salam'), 2], [b('Nasi Putih'), 300], [b('Toge'), 40], [b('Telur Ayam'), 50],
        ]],
        ['Rawon Babat', 4, [
            [b('Babat'), 150], [b('Daging Sapi'), 100], [b('Kluwek'), 20],
            [b('Bawang Merah'), 20], [b('Bawang Putih'), 10], [b('Lengkuas'), 10],
            [b('Daun Salam'), 2], [b('Nasi Putih'), 300], [b('Toge'), 40],
            [b('Kerupuk'), 20],
        ]],
    ]
];

// ----- 14. SOP IGA -----
$food_types['sop iga'] = [
    'kategori' => 'Sup & Soto',
    'variants' => [
        ['Sop Iga Bening', 4, [
            [b('Daging Sapi'), 150], [b('Wortel'), 40], [b('Kentang'), 40],
            [b('Daun Bawang'), 10], [b('Seledri'), 5], [b('Bawang Putih'), 5],
            [b('Merica Bubuk'), 1],
        ]],
        ['Sop Iga Sapi', 4, [
            [b('Daging Sapi'), 180], [b('Wortel'), 40], [b('Kentang'), 50],
            [b('Daun Bawang'), 10], [b('Seledri'), 5], [b('Bawang Putih'), 8],
            [b('Merica Bubuk'), 1],
        ]],
        ['Sop Iga Kambing', 4, [
            [b('Daging Kambing'), 200], [b('Wortel'), 40], [b('Kentang'), 50],
            [b('Tomat'), 20], [b('Daun Bawang'), 10], [b('Seledri'), 5],
            [b('Bawang Putih'), 8], [b('Merica Bubuk'), 1], [b('Susu Cair'), 30],
        ]],
        ['Sop Iga Tulang', 4, [
            [b('Daging Sapi'), 150], [b('Daging Sapi Berlemak'), 80], [b('Wortel'), 40],
            [b('Kentang'), 60], [b('Daun Bawang'), 10], [b('Seledri'), 5],
            [b('Bawang Putih'), 8], [b('Merica Bubuk'), 1], [b('Minyak Goreng'), 5],
        ]],
        ['Sop Iga Kremes', 4, [
            [b('Daging Sapi Berlemak'), 250], [b('Kentang'), 80], [b('Wortel'), 40],
            [b('Daun Bawang'), 15], [b('Seledri'), 10], [b('Bawang Putih'), 10],
            [b('Merica Bubuk'), 2], [b('Margarin'), 10], [b('Kerupuk'), 20],
        ]],
    ]
];

// ----- 15. TUMIS KANGKUNG -----
$food_types['tumis kangkung'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Tumis Kangkung Bawang', 3, [
            [b('Kangkung'), 200], [b('Bawang Merah'), 10], [b('Bawang Putih'), 5],
            [b('Cabai Merah'), 3], [b('Minyak Goreng'), 3],
        ]],
        ['Tumis Kangkung Tahu', 3, [
            [b('Kangkung'), 200], [b('Tahu Putih'), 80], [b('Bawang Merah'), 10],
            [b('Bawang Putih'), 5], [b('Cabai Merah'), 5], [b('Minyak Goreng'), 5],
        ]],
        ['Tumis Kangkung Kecap', 3, [
            [b('Kangkung'), 200], [b('Bawang Merah'), 10], [b('Bawang Putih'), 5],
            [b('Cabai Merah'), 5], [b('Kecap Manis'), 10], [b('Minyak Goreng'), 8],
            [b('Gula Pasir'), 3],
        ]],
        ['Tumis Kangkung Terasi', 3, [
            [b('Kangkung'), 250], [b('Terasi'), 10], [b('Bawang Merah'), 15],
            [b('Bawang Putih'), 8], [b('Cabai Merah'), 10], [b('Cabai Rawit'), 5],
            [b('Minyak Goreng'), 10],
        ]],
        ['Tumis Kangkung Belacan', 3, [
            [b('Kangkung'), 250], [b('Terasi'), 10], [b('Bawang Merah'), 15],
            [b('Bawang Putih'), 8], [b('Cabai Merah'), 15], [b('Cabai Rawit'), 5],
            [b('Minyak Goreng'), 12], [b('Gula Pasir'), 5], [b('Kacang Tanah Goreng'), 20],
        ]],
    ]
];

// ----- 16. CAPCAY -----
$food_types['capcay'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Capcay Sayur', 3, [
            [b('Wortel'), 40], [b('Sawi Hijau'), 30], [b('Kol'), 30], [b('Buncis'), 20],
            [b('Bawang Putih'), 5], [b('Jahe'), 3], [b('Minyak Goreng'), 5],
            [b('Merica Bubuk'), 1],
        ]],
        ['Capcay Ayam', 3, [
            [b('Daging Ayam'), 50], [b('Wortel'), 40], [b('Sawi Hijau'), 30],
            [b('Kol'), 30], [b('Buncis'), 20], [b('Bawang Putih'), 5],
            [b('Jahe'), 3], [b('Minyak Goreng'), 8], [b('Kecap Manis'), 10],
        ]],
        ['Capcay Seafood', 3, [
            [b('Udang'), 40], [b('Cumi-cumi'), 40], [b('Wortel'), 40], [b('Sawi Hijau'), 30],
            [b('Kol'), 30], [b('Buncis'), 20], [b('Bawang Putih'), 8],
            [b('Jahe'), 3], [b('Minyak Goreng'), 10], [b('Kecap Manis'), 15],
        ]],
        ['Capcay Full Goreng', 3, [
            [b('Daging Ayam'), 50], [b('Udang'), 30], [b('Telur Ayam'), 50],
            [b('Wortel'), 40], [b('Sawi Hijau'), 30], [b('Kol'), 30],
            [b('Bawang Putih'), 8], [b('Jahe'), 3], [b('Minyak Goreng'), 15],
            [b('Kecap Manis'), 10],
        ]],
        ['Capcay Kuah Santan', 3, [
            [b('Daging Ayam'), 60], [b('Udang'), 30], [b('Telur Ayam'), 50],
            [b('Wortel'), 50], [b('Sawi Hijau'), 30], [b('Santan Kelapa'), 60],
            [b('Bawang Putih'), 8], [b('Jahe'), 5], [b('Minyak Goreng'), 10],
            [b('Gula Pasir'), 5],
        ]],
    ]
];

// ----- 17. PEPES IKAN -----
$food_types['pepes ikan'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Pepes Ikan Mas', 3, [
            [b('Ikan Mas'), 200], [b('Bawang Merah'), 15], [b('Bawang Putih'), 8],
            [b('Cabai Merah'), 5], [b('Kunyit'), 5], [b('Jahe'), 5],
            [b('Daun Salam'), 2], [b('Kemiri'), 5],
        ]],
        ['Pepes Ikan Nila', 3, [
            [b('Ikan Nila'), 200], [b('Bawang Merah'), 15], [b('Bawang Putih'), 8],
            [b('Cabai Merah'), 5], [b('Kunyit'), 5], [b('Jahe'), 5],
            [b('Kemiri'), 5], [b('Daun Bawang'), 10], [b('Minyak Goreng'), 5],
        ]],
        ['Pepes Patin', 3, [
            [b('Ikan Patin'), 220], [b('Bawang Merah'), 15], [b('Bawang Putih'), 8],
            [b('Cabai Merah'), 5], [b('Kunyit'), 5], [b('Jahe'), 5],
            [b('Kemiri'), 10], [b('Daun Salam'), 2], [b('Minyak Goreng'), 8],
        ]],
        ['Pepes Tuna', 3, [
            [b('Ikan Tuna'), 200], [b('Bawang Merah'), 20], [b('Bawang Putih'), 8],
            [b('Cabai Merah'), 10], [b('Kunyit'), 5], [b('Jahe'), 5],
            [b('Kemiri'), 10], [b('Santan Kelapa'), 30], [b('Daun Salam'), 2],
            [b('Minyak Goreng'), 8],
        ]],
        ['Pepes Cumi', 3, [
            [b('Cumi-cumi'), 200], [b('Telur Ayam'), 50], [b('Bawang Merah'), 20],
            [b('Bawang Putih'), 8], [b('Cabai Merah'), 10], [b('Kunyit'), 5],
            [b('Jahe'), 5], [b('Kemiri'), 10], [b('Santan Kelapa'), 30],
            [b('Daun Salam'), 2], [b('Minyak Goreng'), 10],
        ]],
    ]
];

// ----- 18. PERKEDEL -----
$food_types['perkedel'] = [
    'kategori' => 'Cemilan & Camilan',
    'variants' => [
        ['Perkedel Kentang', 3, [
            [b('Kentang'), 200], [b('Telur Ayam'), 25], [b('Daun Bawang'), 10],
            [b('Bawang Putih'), 5], [b('Merica Bubuk'), 1], [b('Minyak Goreng'), 8],
        ]],
        ['Perkedel Tahu', 3, [
            [b('Tahu Putih'), 150], [b('Telur Ayam'), 25], [b('Daun Bawang'), 10],
            [b('Bawang Putih'), 5], [b('Merica Bubuk'), 1], [b('Tepung Terigu'), 20],
            [b('Minyak Goreng'), 10],
        ]],
        ['Perkedel Jagung', 3, [
            [b('Jagung Manis'), 150], [b('Tepung Terigu'), 30], [b('Telur Ayam'), 25],
            [b('Daun Bawang'), 10], [b('Bawang Putih'), 5], [b('Minyak Goreng'), 12],
        ]],
        ['Perkedel Daging', 3, [
            [b('Daging Sapi'), 100], [b('Kentang'), 100], [b('Telur Ayam'), 50],
            [b('Daun Bawang'), 10], [b('Bawang Putih'), 8], [b('Merica Bubuk'), 1],
            [b('Minyak Goreng'), 15],
        ]],
        ['Perkedel Mie', 3, [
            [b('Mie Kuning Basah'), 200], [b('Telur Ayam'), 50], [b('Daun Bawang'), 10],
            [b('Bawang Putih'), 8], [b('Merica Bubuk'), 1], [b('Minyak Goreng'), 20],
            [b('Tepung Terigu'), 30], [b('Cabai Merah'), 10],
        ]],
    ]
];

// ----- 19. RUJAK BUAH -----
$food_types['rujak buah'] = [
    'kategori' => 'Makanan Penutup',
    'variants' => [
        ['Rujak Mangga', 2, [
            [b('Mangga Muda'), 150], [b('Gula Merah'), 30], [b('Cabai Rawit'), 5],
            [b('Terasi'), 3], [b('Asam Jawa'), 5],
        ]],
        ['Rujak Nanas', 2, [
            [b('Nanas'), 150], [b('Gula Merah'), 30], [b('Cabai Rawit'), 5],
            [b('Terasi'), 3], [b('Asam Jawa'), 5],
        ]],
        ['Rujak Pepaya', 2, [
            [b('Pepaya'), 150], [b('Mangga Muda'), 50], [b('Gula Merah'), 35],
            [b('Cabai Rawit'), 5], [b('Terasi'), 3], [b('Asam Jawa'), 5],
        ]],
        ['Rujak Jambu', 2, [
            [b('Jambu Biji'), 100], [b('Mangga Muda'), 80], [b('Nanas'), 50],
            [b('Gula Merah'), 35], [b('Cabai Rawit'), 8], [b('Terasi'), 5],
            [b('Asam Jawa'), 5],
        ]],
        ['Rujak Komplit', 2, [
            [b('Mangga Muda'), 80], [b('Nanas'), 50], [b('Pepaya'), 50],
            [b('Jambu Biji'), 50], [b('Timun'), 40], [b('Gula Merah'), 45],
            [b('Cabai Rawit'), 10], [b('Terasi'), 5], [b('Asam Jawa'), 5],
            [b('Kacang Tanah Goreng'), 20],
        ]],
    ]
];

// ----- 20. BUBUR AYAM -----
$food_types['bubur ayam'] = [
    'kategori' => 'Makanan Utama',
    'variants' => [
        ['Bubur Ayam Polos', 3, [
            [b('Nasi Putih'), 200], [b('Daging Ayam'), 60], [b('Daun Bawang'), 10],
            [b('Bawang Putih'), 5], [b('Jahe'), 5], [b('Merica Bubuk'), 1],
        ]],
        ['Bubur Ayam Telur', 3, [
            [b('Nasi Putih'), 200], [b('Daging Ayam Kulit'), 70], [b('Telur Ayam'), 50],
            [b('Daun Bawang'), 10], [b('Bawang Putih'), 5], [b('Jahe'), 5],
            [b('Kecap Manis'), 5], [b('Gula Pasir'), 1],
        ]],
        ['Bubur Ayam Cakwe', 3, [
            [b('Nasi Putih'), 250], [b('Daging Ayam Kulit'), 80], [b('Telur Ayam'), 50],
            [b('Daun Bawang'), 10], [b('Bawang Putih'), 8], [b('Jahe'), 5],
            [b('Kecap Manis'), 10], [b('Gula Pasir'), 1],
        ]],
        ['Bubur Ayam Komplit', 3, [
            [b('Nasi Putih'), 300], [b('Daging Ayam Kulit'), 80], [b('Telur Ayam'), 50],
            [b('Kerupuk'), 20], [b('Daun Bawang'), 10], [b('Bawang Putih'), 8],
            [b('Jahe'), 5], [b('Kecap Manis'), 10], [b('Gula Pasir'), 1],
            [b('Kacang Tanah Goreng'), 15],
        ]],
        ['Bubur Ayam Gorengan', 3, [
            [b('Nasi Putih'), 300], [b('Daging Ayam Kulit'), 100], [b('Telur Ayam'), 50],
            [b('Telur Bebek'), 50], [b('Kerupuk'), 20], [b('Daun Bawang'), 10],
            [b('Bawang Putih'), 8], [b('Jahe'), 5], [b('Kecap Manis'), 10],
            [b('Gula Pasir'), 1], [b('Kacang Tanah Goreng'), 20], [b('Minyak Goreng'), 10],
        ]],
    ]
];

// ============================================================
// INSERT RESEP
// ============================================================
$total_recipes = 0;

$stmt_resep = mysqli_prepare($koneksi, "INSERT INTO resep (id_user, id_kategori, judul, deskripsi, langkah_memasak, jumlah_porsi) VALUES (?, ?, ?, ?, ?, ?)");
$stmt_resep->bind_param('iisssi', $id_user_r, $id_kat_r, $judul_r, $deskripsi_r, $langkah_r, $porsi_r);

$stmt_bahan = mysqli_prepare($koneksi, "INSERT INTO resep_bahan (id_resep, id_bahan, jumlah_gram) VALUES (?, ?, ?)");
$stmt_bahan->bind_param('iid', $id_resep_baru, $id_bahan_r, $gram_r);

$food_type_index = 0;

foreach ($food_types as $jenis => $ft) {
    $food_type_index++;
    $kat_name = $ft['kategori'];
    $id_kat_r = $kategori_map[$kat_name] ?? 2;
    $user_idx = ($food_type_index - 1) % count($user_names);
    $id_user_r = $user_id_map[$user_names[$user_idx]];

    foreach ($ft['variants'] as $vidx => $v) {
        $judul_r = $v[0];
        $porsi_r = $v[1];
        $bahan_list = $v[2];

        $deskripsi_r = deskripsi_resep($judul_r, $jenis);

        if (stripos($judul_r, 'goreng') !== false) {
            $teknik = 'Goreng dalam minyak panas hingga matang kecoklatan';
        } elseif (stripos($judul_r, 'rebus') !== false || stripos($judul_r, 'kuah') !== false || stripos($judul_r, 'bening') !== false) {
            $teknik = 'Rebus dalam air mendidih hingga matang dan empuk';
        } elseif (stripos($judul_r, 'bakar') !== false) {
            $teknik = 'Bakar di atas bara api sambil dibolak-balik hingga matang';
        } elseif (stripos($judul_r, 'kukus') !== false) {
            $teknik = 'Kukus dalam dandang panas hingga matang';
        } elseif (stripos($judul_r, 'tumis') !== false) {
            $teknik = 'Tumis dengan sedikit minyak hingga harum dan matang';
        } else {
            $teknik = 'Masak dengan api sedang hingga matang sempurna';
        }
        $langkah_r = "1. Siapkan semua bahan dan cuci bersih.\n2. Olah $judul_r sesuai selera dengan bumbu yang sudah disiapkan.\n3. $teknik. Sajikan selagi hangat.";

        $stmt_resep->execute();
        $id_resep_baru = mysqli_insert_id($koneksi);
        $total_recipes++;

        foreach ($bahan_list as $b_item) {
            $id_bahan_r = $b_item[0];
            $gram_r = $b_item[1];
            if ($id_bahan_r > 0 && $gram_r > 0) {
                $stmt_bahan->execute();
            }
        }
    }
}

$stmt_resep->close();
$stmt_bahan->close();
$success[] = "Resep: $total_recipes resep berhasil ditambahkan (20 jenis × 5 varian).";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Seeder Resep - Selesai</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow p-8">
        <h1 class="text-2xl font-bold text-green-700 mb-4">✅ Seeder Selesai!</h1>
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
            <a href="resep/index.php" class="bg-gray-500 text-white px-5 py-2 rounded hover:bg-gray-600 transition">Ke Resep Saya</a>
        </div>
        <p class="text-xs text-gray-400 mt-4">Login: andi@mail.com / budi@mail.com / citra@mail.com / dwi@mail.com / eko@mail.com — password: password123</p>
    </div>
</body>
</html>

<?php
require_once __DIR__ . '/koneksi.php';

function langkah($judul, $steps) {
    $link = "https://www.google.com/search?q=resep+" . urlencode($judul);
    $text = "";
    $no = 1;
    foreach ($steps as $s) {
        $text .= "$no. $s\n";
        $no++;
    }
    $text .= "\n\nSumber: $link";
    return $text;
}

$stmt = mysqli_prepare($koneksi, "UPDATE resep SET langkah_memasak = ? WHERE id = ?");
$stmt->bind_param("si", $langkah, $id);

$updated = 0;
$total = 0;

$recipes = [];

// Bakso series (207-211)
$recipes[207] = langkah("Bakso Ayam Kukus", [
    "Campur daging ayam giling dengan tepung tapioka, bawang putih halus, dan garam.",
    "Tambahkan putih telur dan es batu, uleni hingga kalis.",
    "Bentuk bulat-bulat kecil.",
    "Letakkan di wadah tahan panas yang sudah dioles minyak.",
    "Kukus selama 20-25 menit hingga matang.",
    "Angkat dan sajikan dengan saus sambal atau kuah kaldu.",
    "Bakso ayam kukus siap dinikmati.",
]);

$recipes[208] = langkah("Bakso Ikan Kuah", [
    "Campur daging ikan giling dengan tepung tapioka, bawang putih, dan garam.",
    "Uleni hingga adonan kalis dan bisa dibentuk.",
    "Didihkan air kaldu ayam untuk kuah.",
    "Bentuk adonan bakso bulat-bulat.",
    "Masukkan bakso ke dalam air mendidih.",
    "Masak hingga bakso mengapung, tandanya sudah matang.",
    "Sajikan dengan kuah, mi, dan taburan bawang goreng.",
    "Bakso ikan kuah siap dinikmati.",
]);

$recipes[209] = langkah("Bakso Sapi Rebus", [
    "Campur daging sapi giling dengan tepung tapioka, bawang putih halus, dan garam.",
    "Tambahkan es batu sedikit demi sedikit sambil diuleni.",
    "Pastikan adonan kalis dan tidak lengket.",
    "Didihkan air dalam panci besar.",
    "Bentuk adonan bulat-bulat, masukkan ke air mendidih.",
    "Rebus hingga bakso mengapung, angkat.",
    "Sajikan dengan kuah kaldu sapi dan pelengkap.",
    "Bakso sapi rebus siap dinikmati.",
]);

$recipes[210] = langkah("Bakso Goreng", [
    "Campur daging ayam giling dengan tepung tapioka dan bumbu halus.",
    "Tambahkan daun bawang iris dan sedikit air.",
    "Uleni hingga rata.",
    "Bentuk bulat-bulat kecil.",
    "Panaskan minyak goreng dengan api sedang.",
    "Goreng bakso hingga kuning keemasan.",
    "Angkat dan tiriskan.",
    "Sajikan dengan saus sambal atau mayones.",
]);

$recipes[211] = langkah("Bakso Keju Goreng", [
    "Campur daging ayam giling dengan tepung tapioka dan bumbu.",
    "Potong keju cheddar kotak kecil untuk isian.",
    "Ambil sedikit adonan, pipihkan, isi dengan keju.",
    "Bentuk bulat hingga keju tertutup rapat.",
    "Panaskan minyak goreng.",
    "Goreng hingga kuning kecoklatan.",
    "Angkat dan tiriskan.",
    "Sajikan selagi hangat agar keju meleleh.",
]);

// Nasi Goreng series (212-216)
$recipes[212] = langkah("Nasi Goreng Sayur", [
    "Iris wortel, buncis, dan kol tipis-tipis.",
    "Haluskan bawang merah, bawang putih, dan cabai.",
    "Panaskan minyak, tumis bumbu halus hingga harum.",
    "Masukkan sayuran, tumis hingga layu.",
    "Tambahkan nasi putih, aduk rata.",
    "Beri kecap manis, garam, dan merica.",
    "Aduk hingga semua tercampur rata dan nasi panas.",
    "Sajikan dengan telur ceplok dan kerupuk.",
]);

$recipes[213] = langkah("Nasi Goreng Ayam", [
    "Potong dadu daging ayam.",
    "Haluskan bawang merah, bawang putih, dan cabai.",
    "Panaskan minyak, tumis bumbu halus hingga harum.",
    "Masukkan ayam, masak hingga berubah warna.",
    "Tambahkan nasi putih, aduk rata.",
    "Beri kecap manis, garam, dan merica.",
    "Aduk hingga nasi panas dan bumbu merata.",
    "Sajikan dengan telur ceplok dan acar.",
]);

$recipes[214] = langkah("Nasi Goreng Sapi", [
    "Potong kecil daging sapi.",
    "Haluskan bawang merah, bawang putih, dan cabai.",
    "Panaskan minyak, tumis bumbu halus hingga harum.",
    "Masukkan daging sapi, masak hingga matang.",
    "Tambahkan nasi putih, aduk rata.",
    "Beri kecap manis, garam, dan merica.",
    "Aduk hingga merata dan nasi panas.",
    "Sajikan dengan taburan bawang goreng.",
]);

$recipes[215] = langkah("Nasi Goreng Pete", [
    "Kupas dan belah pete menjadi dua bagian.",
    "Haluskan bawang merah, bawang putih, dan cabai.",
    "Panaskan minyak, tumis bumbu halus hingga harum.",
    "Masukkan pete, tumis sebentar.",
    "Tambahkan nasi putih, aduk rata.",
    "Beri kecap manis, garam, dan merica.",
    "Aduk hingga tercampur rata dan nasi panas.",
    "Sajikan dengan kerupuk dan telur dadar.",
]);

$recipes[216] = langkah("Nasi Goreng Kambing", [
    "Potong dadu daging kambing.",
    "Haluskan bawang merah, bawang putih, dan cabai.",
    "Panaskan minyak, tumis bumbu halus hingga harum.",
    "Masukkan daging kambing, masak hingga empuk.",
    "Tambahkan nasi putih, aduk rata.",
    "Beri kecap manis, garam, dan merica.",
    "Aduk hingga merata dan nasi panas.",
    "Sajikan dengan acar timun dan kerupuk.",
]);

// Sate series (217-221)
$recipes[217] = langkah("Sate Ayam Bumbu Kacang", [
    "Potong daging ayam dadu kecil.",
    "Campur dengan kecap manis, bawang putih halus, dan ketumbar.",
    "Marinasi selama 30 menit.",
    "Tusuk daging ayam ke tusukan sate.",
    "Bakar sate di atas bara api sambil dibolak-balik.",
    "Haluskan kacang tanah goreng dengan cabai dan bawang.",
    "Sajikan sate dengan bumbu kacang dan kecap manis.",
    "Sate ayam bumbu kacang siap dinikmati.",
]);

$recipes[218] = langkah("Sate Lilit Ikan", [
    "Campur daging ikan giling dengan kelapa parut dan bumbu halus.",
    "Tambahkan daun jeruk iris dan garam.",
    "Ambil adonan, lilitkan pada tusukan sate atau batang serai.",
    "Panggang di atas bara api hingga matang.",
    "Bakar sambil sesekali dibalik agar matang merata.",
    "Angkat ketika sudah kecoklatan.",
    "Sajikan dengan sambal matah.",
    "Sate lilit ikan khas Bali siap dinikmati.",
]);

$recipes[219] = langkah("Sate Sapi Madura", [
    "Potong daging sapi tipis melebar.",
    "Campur dengan kecap manis, bawang putih, dan ketumbar.",
    "Marinasi minimal 30 menit.",
    "Tusuk daging pada tusukan sate.",
    "Bakar di atas bara api hingga matang.",
    "Sajikan dengan bumbu kacang yang dicampur kecap.",
    "Taburi bawang goreng dan irisan cabai.",
    "Sate sapi Madura siap dinikmati.",
]);

$recipes[220] = langkah("Sate Kambing", [
    "Potong daging kambing dadu kecil.",
    "Campur dengan kecap manis, jahe parut, dan bawang putih.",
    "Marinasi selama 1 jam agar empuk.",
    "Tusuk daging ke tusukan sate.",
    "Bakar di atas bara api hingga matang.",
    "Balik sesekali agar tidak gosong.",
    "Sajikan dengan kecap manis, irisan cabai, dan bawang merah.",
    "Sate kambing siap dinikmati.",
]);

$recipes[221] = langkah("Sate Kulit Ayam", [
    "Cuci bersih kulit ayam, potong kotak.",
    "Campur dengan kecap manis, bawang putih, dan merica.",
    "Marinasi 15 menit.",
    "Tusuk kulit ayam pada tusukan sate.",
    "Bakar di atas bara api hingga mengeluarkan minyak.",
    "Panggang hingga kecokelatan.",
    "Sajikan dengan sambal kecap.",
    "Sate kulit ayam siap dinikmati.",
]);

// Soto series (222-226)
$recipes[222] = langkah("Soto Ayam Bening", [
    "Rebus ayam dengan air, bawang putih, jahe, dan garam.",
    "Angkat ayam, suwir-suwir dagingnya.",
    "Saring kaldu untuk kuah bening.",
    "Tumis bumbu halus (bawang merah, bawang putih, kunyit).",
    "Masukkan tumisan ke dalam kaldu.",
    "Sajikan dengan suwiran ayam, bihun, dan telur rebus.",
    "Taburi bawang goreng dan seledri.",
    "Soto ayam bening siap dinikmati.",
]);

$recipes[223] = langkah("Soto Ayam Kuning", [
    "Haluskan bawang merah, bawang putih, kunyit, jahe, dan kemiri.",
    "Tumis bumbu halus hingga harum.",
    "Rebus ayam dengan air dan bumbu tumis.",
    "Angkat ayam, goreng sebentar, suwir-suwir.",
    "Tambahkan santan ke dalam kuah, aduk rata.",
    "Sajikan dengan suwiran ayam, kol iris, dan toge.",
    "Tambahkan sambal dan kecap manis.",
    "Soto ayam kuning siap dinikmati.",
]);

$recipes[224] = langkah("Soto Betawi", [
    "Rebus daging sapi hingga empuk, potong dadu.",
    "Tumis bumbu halus (bawang merah, bawang putih, kunyit, jahe).",
    "Masukkan tumisan ke dalam kaldu sapi.",
    "Tambahkan santan dan susu, aduk rata.",
    "Beri garam, merica, dan gula pasir.",
    "Sajikan dengan potongan daging, tomat, dan daun bawang.",
    "Tambahkan emping dan sambal.",
    "Soto Betawi siap dinikmati.",
]);

$recipes[225] = langkah("Soto Banjar", [
    "Rebus ayam dengan air hingga empuk.",
    "Tumis bumbu halus (bawang merah, bawang putih, jahe, pala).",
    "Masukkan bumbu ke dalam rebusan ayam.",
    "Tambahkan kayu manis dan cengkeh.",
    "Angkat ayam, suwir-suwir.",
    "Sajikan kuah dengan suwiran ayam, perkedel, dan ketupat.",
    "Tambahkan telur rebus dan bawang goreng.",
    "Soto Banjar siap dinikmati.",
]);

$recipes[226] = langkah("Soto Medan", [
    "Rebus ayam dengan air hingga empuk.",
    "Tumis bumbu halus (bawang merah, bawang putih, kunyit, lengkuas).",
    "Masukkan tumisan ke dalam rebusan ayam.",
    "Tambahkan santan kental, aduk rata.",
    "Beri garam dan merica.",
    "Sajikan dengan suwiran ayam, toge, dan telur rebus.",
    "Tambahkan emping dan sambal bajak.",
    "Soto Medan siap dinikmati.",
]);

// Mie Goreng series (227-231)
$recipes[227] = langkah("Mie Goreng Sayur", [
    "Rebus mie hingga matang, tiriskan.",
    "Iris wortel, kol, dan sawi hijau.",
    "Haluskan bawang merah, bawang putih, dan cabai.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan sayuran, tumis hingga layu.",
    "Tambahkan mie, kecap manis, garam, dan merica.",
    "Aduk rata hingga mie panas.",
    "Sajikan dengan acar dan kerupuk.",
]);

$recipes[228] = langkah("Mie Goreng Ayam", [
    "Rebus mie hingga matang, tiriskan.",
    "Potong dadu daging ayam.",
    "Haluskan bawang merah, bawang putih, dan cabai.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan ayam, masak hingga berubah warna.",
    "Tambahkan mie, kecap manis, garam, dan merica.",
    "Aduk rata hingga mie panas.",
    "Sajikan dengan acar dan kerupuk.",
]);

$recipes[229] = langkah("Mie Goreng Seafood", [
    "Rebus mie hingga matang, tiriskan.",
    "Bersihkan udang, cumi, dan bakso ikan.",
    "Haluskan bawang merah, bawang putih, dan cabai.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan seafood, masak hingga matang.",
    "Tambahkan mie, kecap manis, garam, dan merica.",
    "Aduk rata hingga mie panas.",
    "Sajikan dengan jeruk nipis dan acar.",
]);

$recipes[230] = langkah("Mie Goreng Sapi", [
    "Rebus mie hingga matang, tiriskan.",
    "Iris tipis daging sapi.",
    "Haluskan bawang merah, bawang putih, dan cabai.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan daging sapi, masak hingga matang.",
    "Tambahkan mie, kecap manis, garam, dan merica.",
    "Aduk rata hingga mie panas.",
    "Sajikan dengan acar timun dan kerupuk.",
]);

$recipes[231] = langkah("Mie Goreng Kari", [
    "Rebus mie hingga matang, tiriskan.",
    "Haluskan bawang merah, bawang putih, kunyit, dan ketumbar.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan udang dan bakso, masak sebentar.",
    "Tambahkan mie, bubuk kari, garam, dan merica.",
    "Aduk rata hingga mie panas.",
    "Tambahkan potongan cabai merah.",
    "Sajikan dengan acar dan kerupuk.",
]);

// Gado-gado series (232-236)
$recipes[232] = langkah("Gado-gado Tahu", [
    "Potong tahu kotak, goreng hingga kecoklatan.",
    "Rebus bayam, toge, dan kacang panjang sebentar.",
    "Potong timun dan kol.",
    "Haluskan kacang tanah goreng dengan cabai dan bawang putih.",
    "Campur bumbu kacang dengan air hangat dan kecap manis.",
    "Tata sayuran dan tahu di piring.",
    "Siram dengan bumbu kacang.",
    "Taburi bawang goreng dan kerupuk.",
]);

$recipes[233] = langkah("Gado-gado Tempe", [
    "Potong tempe kotak, goreng hingga kecoklatan.",
    "Rebus bayam, toge, dan kacang panjang sebentar.",
    "Potong timun dan kol.",
    "Haluskan kacang tanah goreng dengan cabai dan bawang putih.",
    "Campur bumbu kacang dengan air hangat dan kecap manis.",
    "Tata sayuran dan tempe di piring.",
    "Siram dengan bumbu kacang.",
    "Sajikan dengan kerupuk dan lontong.",
]);

$recipes[234] = langkah("Gado-gado Telur", [
    "Rebus telur hingga matang, kupas, belah dua.",
    "Rebus bayam, toge, dan kacang panjang sebentar.",
    "Potong timun dan kol.",
    "Haluskan kacang tanah goreng dengan cabai dan bawang putih.",
    "Campur bumbu kacang dengan air hangat.",
    "Tata sayuran dan telur di piring.",
    "Siram dengan bumbu kacang.",
    "Taburi bawang goreng dan kerupuk.",
]);

$recipes[235] = langkah("Gado-gado Komplit", [
    "Rebus telur, kupas, belah dua.",
    "Goreng tahu dan tempe hingga kecoklatan.",
    "Rebus bayam, toge, kacang panjang, dan kentang.",
    "Potong timun dan kol.",
    "Haluskan kacang tanah goreng dengan bumbu.",
    "Tata semua bahan di piring besar.",
    "Siram dengan bumbu kacang.",
    "Taburi bawang goreng dan kerupuk.",
]);

$recipes[236] = langkah("Gado-gado Goreng", [
    "Goreng tahu dan tempe hingga kecoklatan.",
    "Goreng kentang yang sudah diiris tipis.",
    "Rebus bayam dan toge sebentar.",
    "Haluskan kacang tanah goreng dengan cabai dan bawang.",
    "Campur bumbu kacang dengan air hangat.",
    "Campur semua bahan goreng dan rebus.",
    "Siram dengan bumbu kacang.",
    "Sajikan dengan kerupuk.",
]);

// Rendang series (237-241)
$recipes[237] = langkah("Rendang Ayam", [
    "Potong ayam menjadi beberapa bagian.",
    "Haluskan bawang merah, bawang putih, cabai, kunyit, jahe, dan lengkuas.",
    "Tumis bumbu halus dengan serai dan daun jeruk.",
    "Masukkan santan, aduk rata.",
    "Tambahkan ayam, masak dengan api kecil.",
    "Aduk sesekali hingga kuah menyusut dan mengental.",
    "Masak terus hingga bumbu meresap dan ayam empuk.",
    "Rendang ayam siap dinikmati.",
]);

$recipes[238] = langkah("Rendang Sapi Tanpa Santan", [
    "Potong daging sapi ukuran besar.",
    "Haluskan bawang merah, bawang putih, cabai, dan rempah.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan daging sapi, aduk rata.",
    "Tambahkan air secukupnya.",
    "Masak dengan api kecil hingga daging empuk.",
    "Beri garam dan penyedap alami.",
    "Masak hingga kuah habis dan bumbu meresap.",
]);

$recipes[239] = langkah("Rendang Sapi", [
    "Potong daging sapi ukuran besar.",
    "Haluskan bawang merah, bawang putih, cabai, kunyit, jahe, lengkuas.",
    "Tumis bumbu halus dengan serai, daun jeruk, dan daun kunyit.",
    "Masukkan santan kental, aduk rata.",
    "Tambahkan daging sapi.",
    "Masak dengan api kecil sambil sesekali diaduk.",
    "Masak hingga kuah mengental dan daging empuk.",
    "Rendang sapi siap dinikmati.",
]);

$recipes[240] = langkah("Rendang Padang", [
    "Potong daging sapi ukuran besar.",
    "Haluskan bawang merah, bawang putih, cabai keriting, kunyit, jahe, lengkuas.",
    "Tumis bumbu halus dengan serai, daun jeruk, dan daun kunyit.",
    "Masukkan santan kelapa, aduk hingga mendidih.",
    "Masukkan daging sapi, kecilkan api.",
    "Masak selama 3-4 jam sambil sesekali diaduk.",
    "Masak hingga kuah mengering dan berwarna gelap.",
    "Rendang Padang siap dinikmati.",
]);

$recipes[241] = langkah("Rendang Jeroan", [
    "Bersihkan jeroan sapi, rebus hingga empuk, potong kecil.",
    "Haluskan bawang merah, bawang putih, cabai, dan rempah.",
    "Tumis bumbu halus hingga harum.",
    "Masukkan jeroan, aduk rata.",
    "Tambahkan santan, masak dengan api kecil.",
    "Aduk sesekali hingga kuah menyusut.",
    "Beri garam dan gula merah.",
    "Rendang jeroan siap dinikmati.",
]);

// Ayam Goreng series (242-246)
$recipes[242] = langkah("Ayam Goreng Tanpa Kulit", [
    "Buang kulit ayam, cuci bersih.",
    "Haluskan bawang putih, kunyit, jahe, dan garam.",
    "Lumuri ayam dengan bumbu halus.",
    "Diamkan selama 15 menit.",
    "Panaskan minyak goreng.",
    "Goreng ayam dengan api sedang hingga matang.",
    "Balik agar matang merata.",
    "Angkat dan tiriskan, sajikan hangat.",
]);

$recipes[243] = langkah("Ayam Goreng Tepung", [
    "Potong ayam menjadi beberapa bagian.",
    "Campur tepung terigu, tepung maizena, garam, dan merica.",
    "Kocok telur dengan sedikit garam.",
    "Celupkan ayam ke telur, lalu gulingkan di tepung.",
    "Remas-remas agar tepung menempel.",
    "Panaskan minyak goreng.",
    "Goreng ayam hingga kuning kecoklatan.",
    "Angkat dan tiriskan, sajikan dengan saus.",
]);

$recipes[244] = langkah("Ayam Goreng Mentega", [
    "Potong ayam bagian dada, lumuri garam dan merica.",
    "Goreng ayam hingga setengah matang, tiriskan.",
    "Lelehkan mentega, tumis bawang putih cincang.",
    "Masukkan kecap manis dan saus tiram.",
    "Tambahkan sedikit air dan ayam.",
    "Aduk rata, masak hingga bumbu meresap.",
    "Tambahkan daun bawang iris.",
    "Ayam goreng mentega siap dinikmati.",
]);

$recipes[245] = langkah("Ayam Goreng Kremes", [
    "Rebus ayam dengan bumbu halus hingga empuk.",
    "Angkat ayam, tiriskan.",
    "Goreng ayam hingga kecoklatan.",
    "Campur sisa kaldu dengan tepung sagu dan telur.",
    "Goreng adonan kremes dengan cara mencipratkan ke minyak panas.",
    "Angkat kremes ketika sudah kuning.",
    "Sajikan ayam dengan taburan kremes.",
    "Ayam goreng kremes siap dinikmati.",
]);

$recipes[246] = langkah("Ayam Goreng Crispy", [
    "Potong ayam kecil-kecil.",
    "Campur tepung terigu, maizena, baking powder, garam, dan merica.",
    "Celupkan ayam ke dalam air es.",
    "Gulingkan ke campuran tepung.",
    "Remas hingga terbentuk lapisan keriting.",
    "Panaskan minyak banyak.",
    "Goreng ayam hingga kuning kecoklatan.",
    "Angkat dan tiriskan, sajikan dengan saus sambal.",
]);

// Ikan Bakar series (247-251)
$recipes[247] = langkah("Ikan Bakar Bumbu Kuning", [
    "Bersihkan ikan, kerat kedua sisinya.",
    "Haluskan bawang merah, bawang putih, kunyit, jahe, dan ketumbar.",
    "Lumuri ikan dengan bumbu halus dan garam.",
    "Diamkan 30 menit.",
    "Bakar ikan di atas bara api.",
    "Olesi sisa bumbu saat membakar.",
    "Balik ikan agar matang merata.",
    "Sajikan dengan sambal dan lalapan.",
]);

$recipes[248] = langkah("Ikan Bakar Kecap", [
    "Bersihkan ikan, kerat kedua sisinya.",
    "Campur kecap manis, bawang putih cincang, jahe, dan merica.",
    "Lumuri ikan dengan bumbu kecap.",
    "Diamkan 15 menit.",
    "Bakar ikan di atas bara api.",
    "Olesi sisa bumbu kecap saat membakar.",
    "Balik ikan, bakar hingga matang.",
    "Sajikan dengan sambal kecap dan jeruk nipis.",
]);

$recipes[249] = langkah("Ikan Bakar Padang", [
    "Bersihkan ikan, kerat kedua sisinya.",
    "Haluskan bawang merah, bawang putih, cabai, kunyit, jahe.",
    "Lumuri ikan dengan bumbu halus dan air jeruk nipis.",
    "Diamkan 30 menit.",
    "Bakar ikan di atas bara api.",
    "Sajikan dengan sambal hijau khas Padang.",
    "Tambahkan lalapan segar.",
    "Ikan bakar Padang siap dinikmati.",
]);

$recipes[250] = langkah("Ikan Bakar Jimbaran", [
    "Bersihkan ikan, kerat kedua sisinya.",
    "Haluskan bawang merah, bawang putih, cabai, terasi, dan gula merah.",
    "Lumuri ikan dengan bumbu halus.",
    "Diamkan 30 menit.",
    "Bakar ikan di atas bara api.",
    "Olesi sisa bumbu saat membakar.",
    "Balik ikan hingga matang sempurna.",
    "Sajikan dengan sambal matah dan nasi hangat.",
]);

$recipes[251] = langkah("Ikan Bakar Sambal Matah", [
    "Bersihkan ikan, lumuri garam dan jeruk nipis.",
    "Bakar ikan di atas bara api hingga matang.",
    "Iris tipis bawang merah, cabai, serai, dan daun jeruk.",
    "Campur irisan dengan minyak kelapa dan perasan jeruk.",
    "Tambahkan garam dan gula.",
    "Aduk rata sambal matah.",
    "Siram sambal matah di atas ikan bakar.",
    "Sajikan dengan nasi hangat dan lalapan.",
]);

// Nasi Uduk series (252-256)
$recipes[252] = langkah("Nasi Uduk Telur", [
    "Cuci beras hingga bersih.",
    "Rebus santan dengan serai, daun salam, dan garam.",
    "Masukkan beras, masak hingga air terserap.",
    "Kukus nasi hingga matang sempurna.",
    "Goreng telur dadar, iris tipis.",
    "Sajikan nasi uduk dengan telur dadar.",
    "Taburi bawang goreng dan timun iris.",
    "Nasi uduk telur siap dinikmati.",
]);

$recipes[253] = langkah("Nasi Uduk Ayam", [
    "Cuci beras, masak dengan santan berbumbu.",
    "Kukus hingga matang.",
    "Goreng ayam yang sudah dibumbui kuning.",
    "Suwir-suwir daging ayam.",
    "Sajikan nasi uduk dengan suwiran ayam.",
    "Tambahkan telur dadar iris dan timun.",
    "Taburi bawang goreng dan sambal.",
    "Nasi uduk ayam siap dinikmati.",
]);

$recipes[254] = langkah("Nasi Uduk Semur", [
    "Cuci beras, masak dengan santan berbumbu.",
    "Kukus hingga matang.",
    "Rebus daging sapi untuk semur hingga empuk.",
    "Tumis bawang merah, bawang putih, pala, dan cengkeh.",
    "Masukkan daging, kecap manis, dan air.",
    "Masak hingga kuah mengental.",
    "Sajikan nasi uduk dengan semur daging.",
    "Taburi bawang goreng.",
]);

$recipes[255] = langkah("Nasi Uduk Komplit", [
    "Cuci beras, masak dengan santan berbumbu.",
    "Kukus hingga matang.",
    "Goreng ayam berbumbu kuning, suwir.",
    "Goreng tempe dan tahu.",
    "Buat sambal goreng kentang.",
    "Sajikan nasi uduk dengan semua lauk.",
    "Tambahkan telur dadar dan timun.",
    "Taburi bawang goreng dan kerupuk.",
]);

$recipes[256] = langkah("Nasi Uduk Goreng", [
    "Cuci beras, masak dengan santan berbumbu, kukus.",
    "Diamkan nasi uduk hingga dingin.",
    "Panaskan minyak, tumis bawang putih dan cabai.",
    "Masukkan nasi uduk, aduk rata.",
    "Beri kecap manis dan garam.",
    "Aduk hingga panas merata.",
    "Tambahkan telur dadar iris.",
    "Sajikan dengan acar dan bawang goreng.",
]);

// Gulai series (257-261)
$recipes[257] = langkah("Gulai Ayam", [
    "Potong ayam menjadi beberapa bagian.",
    "Haluskan bawang merah, bawang putih, cabai, kunyit, jahe, lengkuas.",
    "Tumis bumbu halus dengan serai, daun salam, dan daun jeruk.",
    "Masukkan santan, aduk rata.",
    "Tambahkan ayam, masak dengan api sedang.",
    "Beri garam dan gula.",
    "Masak hingga ayam empuk dan kuah mengental.",
    "Gulai ayam siap dinikmati dengan nasi hangat.",
]);

$recipes[258] = langkah("Gulai Ikan", [
    "Bersihkan ikan, potong sesuai selera.",
    "Haluskan bawang merah, bawang putih, cabai, kunyit, jahe.",
    "Tumis bumbu halus dengan serai dan daun salam.",
    "Masukkan santan, aduk rata.",
    "Tambahkan ikan, masak dengan api kecil.",
    "Beri garam dan asam jawa.",
    "Masak hingga ikan matang dan kuah mengental.",
    "Gulai ikan siap dinikmati.",
]);

$recipes[259] = langkah("Gulai Kambing", [
    "Potong daging kambing ukuran kecil.",
    "Haluskan bawang merah, bawang putih, cabai, kunyit, jahe, lengkuas.",
    "Tumis bumbu halus dengan serai dan daun kari.",
    "Masukkan daging kambing, aduk rata.",
    "Tambahkan santan, masak dengan api kecil.",
    "Beri garam dan gula.",
    "Masak hingga daging empuk dan kuah mengental.",
    "Gulai kambing siap dinikmati.",
]);

$recipes[260] = langkah("Gulai Cumi", [
    "Bersihkan cumi, potong cincin.",
    "Haluskan bawang merah, bawang putih, cabai, kunyit, jahe.",
    "Tumis bumbu halus dengan serai dan daun jeruk.",
    "Masukkan santan, aduk rata.",
    "Tambahkan cumi, masak sebentar.",
    "Beri garam dan gula.",
    "Masak hingga cumi matang (jangan terlalu lama agar tidak alot).",
    "Gulai cumi siap dinikmati.",
]);

$recipes[261] = langkah("Gulai Babat", [
    "Bersihkan babat sapi, rebus hingga empuk, potong kecil.",
    "Haluskan bawang merah, bawang putih, cabai, kunyit, jahe, lengkuas.",
    "Tumis bumbu halus dengan serai dan daun salam.",
    "Masukkan santan, aduk rata.",
    "Tambahkan babat, masak dengan api kecil.",
    "Beri garam dan gula.",
    "Masak hingga kuah mengental dan meresap.",
    "Gulai babat siap dinikmati.",
]);

// Opor series (262-266)
$recipes[262] = langkah("Opor Ayam Tanpa Santan", [
    "Potong ayam menjadi beberapa bagian.",
    "Haluskan bawang merah, bawang putih, kemiri, ketumbar, kunyit.",
    "Tumis bumbu halus dengan serai, daun salam, dan lengkuas.",
    "Masukkan ayam, aduk rata.",
    "Tambahkan air, garam, dan gula.",
    "Masak dengan api sedang hingga ayam empuk.",
    "Koreksi rasa, angkat.",
    "Opor ayam tanpa santan siap dinikmati.",
]);

$recipes[263] = langkah("Opor Ayam Putih", [
    "Potong ayam menjadi beberapa bagian.",
    "Haluskan bawang merah, bawang putih, kemiri, ketumbar, jahe.",
    "Tumis bumbu halus dengan serai, daun salam, dan lengkuas.",
    "Masukkan ayam, aduk hingga berubah warna.",
    "Tambahkan santan, garam, dan gula.",
    "Masak dengan api kecil hingga ayam empuk.",
    "Aduk sesekali agar santan tidak pecah.",
    "Opor ayam putih siap dinikmati.",
]);

$recipes[264] = langkah("Opor Ayam Kuning", [
    "Potong ayam menjadi beberapa bagian.",
    "Haluskan bawang merah, bawang putih, kemiri, kunyit, ketumbar.",
    "Tumis bumbu halus dengan serai, daun salam, dan lengkuas.",
    "Masukkan ayam, aduk rata.",
    "Tambahkan santan, garam, dan gula.",
    "Masak dengan api kecil hingga ayam empuk.",
    "Aduk sesekali agar santan tidak pecah.",
    "Opor ayam kuning siap dinikmati.",
]);

$recipes[265] = langkah("Opor Ayam Kampung", [
    "Potong ayam kampung menjadi beberapa bagian.",
    "Haluskan bawang merah, bawang putih, kemiri, kunyit, jahe.",
    "Tumis bumbu halus dengan serai dan daun salam.",
    "Masukkan ayam kampung, aduk rata.",
    "Tambahkan santan dan air, garam, gula.",
    "Masak dengan api kecil hingga ayam empuk (lebih lama).",
    "Aduk sesekali.",
    "Opor ayam kampung siap dinikmati.",
]);

$recipes[266] = langkah("Opor Ayam Kremesan", [
    "Potong ayam, rebus dengan bumbu opor hingga empuk.",
    "Angkat ayam, goreng sebentar.",
    "Sisa kuah opor dicampur tepung beras dan telur.",
    "Goreng adonan kremes dalam minyak panas.",
    "Sajikan ayam opor dengan taburan kremes.",
    "Siram dengan kuah opor.",
    "Taburi bawang goreng.",
    "Opor ayam kremesan siap dinikmati.",
]);

// Rawon series (267-271)
$recipes[267] = langkah("Rawon Daging Sapi", [
    "Potong daging sapi ukuran sedang.",
    "Haluskan bawang merah, bawang putih, kluwek, jahe, kunyit, ketumbar.",
    "Tumis bumbu halus dengan serai, daun jeruk, dan lengkuas.",
    "Masukkan daging sapi, aduk rata.",
    "Tambahkan air, masak hingga daging empuk.",
    "Beri garam dan gula.",
    "Masak hingga kuah berwarna hitam pekat.",
    "Sajikan dengan telur asin, tauge, dan sambal terasi.",
]);

$recipes[268] = langkah("Rawon Ayam", [
    "Potong ayam menjadi beberapa bagian.",
    "Haluskan bawang merah, bawang putih, kluwek, jahe, kunyit.",
    "Tumis bumbu halus dengan serai dan daun jeruk.",
    "Masukkan ayam, aduk rata.",
    "Tambahkan air, masak hingga ayam empuk.",
    "Beri garam dan gula.",
    "Masak hingga kuah berwarna gelap.",
    "Sajikan dengan nasi hangat dan tauge.",
]);

$recipes[269] = langkah("Rawon Gajebo", [
    "Potong daging sapi (gajebo/sandung lamur) ukuran besar.",
    "Haluskan bawang merah, bawang putih, kluwek, jahe, kunyit.",
    "Tumis bumbu halus dengan serai dan daun jeruk.",
    "Masukkan daging, aduk rata.",
    "Tambahkan air, masak hingga daging empuk.",
    "Beri garam dan gula merah.",
    "Masak hingga kuah mengental dan gelap.",
    "Sajikan dengan nasi hangat dan sambal.",
]);

$recipes[270] = langkah("Rawon Iga", [
    "Rebus iga sapi hingga empuk.",
    "Haluskan bawang merah, bawang putih, kluwek, jahe, kunyit.",
    "Tumis bumbu halus dengan serai dan daun jeruk.",
    "Masukkan iga dan air kaldu.",
    "Beri garam dan gula.",
    "Masak hingga kuah berwarna hitam pekat.",
    "Koreksi rasa.",
    "Sajikan dengan tauge dan telur asin.",
]);

$recipes[271] = langkah("Rawon Babat", [
    "Bersihkan babat, rebus hingga empuk, potong kecil.",
    "Haluskan bawang merah, bawang putih, kluwek, jahe, kunyit.",
    "Tumis bumbu halus dengan serai dan daun jeruk.",
    "Masukkan babat, aduk rata.",
    "Tambahkan air kaldu, garam, dan gula.",
    "Masak hingga kuah gelap dan meresap.",
    "Koreksi rasa.",
    "Sajikan dengan nasi hangat dan sambal.",
]);

// Sop Iga series (272-276)
$recipes[272] = langkah("Sop Iga Bening", [
    "Rebus iga sapi hingga empuk, buang lemak.",
    "Iris wortel dan kentang dadu.",
    "Masukkan sayuran ke dalam rebusan iga.",
    "Tambahkan bawang putih, garam, dan merica.",
    "Masak hingga sayuran matang.",
    "Taburi daun bawang dan seledri.",
    "Sajikan hangat dengan sambal dan emping.",
    "Sop iga bening siap dinikmati.",
]);

$recipes[273] = langkah("Sop Iga Sapi", [
    "Rebus iga sapi hingga empuk.",
    "Tumis bawang putih dan bawang merah iris.",
    "Masukkan tumisan ke dalam rebusan iga.",
    "Tambahkan wortel dan kentang.",
    "Beri garam, merica, dan pala bubuk.",
    "Masak hingga sayuran matang.",
    "Taburi bawang goreng dan seledri.",
    "Sajikan dengan sambal dan nasi hangat.",
]);

$recipes[274] = langkah("Sop Iga Kambing", [
    "Rebus iga kambing hingga empuk.",
    "Tumis bawang putih, bawang merah, dan jahe.",
    "Masukkan tumisan ke dalam rebusan iga.",
    "Tambahkan kentang dan wortel.",
    "Beri garam, merica, dan kayu manis.",
    "Masak hingga sayuran empuk.",
    "Taburi daun bawang dan bawang goreng.",
    "Sajikan hangat dengan emping.",
]);

$recipes[275] = langkah("Sop Iga Tulang", [
    "Rebus iga tulang sapi hingga empuk.",
    "Tumis bawang putih cincang hingga harum.",
    "Masukkan tumisan ke dalam rebusan.",
    "Tambahkan potongan kentang dan wortel.",
    "Beri garam, merica, dan kaldu sapi.",
    "Masak hingga sayuran lunak.",
    "Taburi seledri dan bawang goreng.",
    "Sajikan hangat.",
]);

$recipes[276] = langkah("Sop Iga Kremes", [
    "Rebus iga sapi hingga empuk, tiriskan.",
    "Goreng iga sebentar hingga kecoklatan.",
    "Tumis bawang putih, bawang merah, jahe.",
    "Masukkan tumisan ke dalam air kaldu.",
    "Tambahkan sayuran, garam, dan merica.",
    "Sajikan iga goreng dengan kuah sop.",
    "Taburi kremes tepung di atasnya.",
    "Sop iga kremes siap dinikmati.",
]);

// Tumis Kangkung series (277-281)
$recipes[277] = langkah("Tumis Kangkung Bawang", [
    "Siangi kangkung, cuci bersih.",
    "Iris bawang merah dan bawang putih.",
    "Panaskan minyak, tumis bawang hingga harum.",
    "Masukkan kangkung, aduk cepat.",
    "Beri garam dan gula pasir.",
    "Tambahkan sedikit air.",
    "Masak hingga kangkung layu.",
    "Angkat dan sajikan segera.",
]);

$recipes[278] = langkah("Tumis Kangkung Tahu", [
    "Siangi kangkung, cuci bersih.",
    "Potong tahu kotak, goreng setengah matang.",
    "Iris bawang merah dan bawang putih.",
    "Tumis bawang hingga harum, masukkan kangkung.",
    "Masukkan tahu, aduk rata.",
    "Beri garam, gula, dan kecap manis.",
    "Aduk hingga layu dan merata.",
    "Angkat dan sajikan.",
]);

$recipes[279] = langkah("Tumis Kangkung Kecap", [
    "Siangi kangkung, cuci bersih.",
    "Iris bawang merah, bawang putih, dan cabai.",
    "Panaskan minyak, tumis bumbu iris hingga harum.",
    "Masukkan kangkung, aduk cepat.",
    "Tambahkan kecap manis dan garam.",
    "Aduk rata hingga kangkung layu.",
    "Tambahkan sedikit air.",
    "Angkat dan sajikan selagi hangat.",
]);

$recipes[280] = langkah("Tumis Kangkung Terasi", [
    "Siangi kangkung, cuci bersih.",
    "Haluskan bawang merah, bawang putih, cabai, dan terasi.",
    "Panaskan minyak, tumis bumbu halus hingga harum.",
    "Masukkan kangkung, aduk cepat.",
    "Beri garam dan gula.",
    "Aduk hingga kangkung layu.",
    "Angkat dan sajikan segera.",
    "Tumis kangkung terasi siap dinikmati.",
]);

$recipes[281] = langkah("Tumis Kangkung Belacan", [
    "Siangi kangkung, cuci bersih.",
    "Haluskan bawang merah, bawang putih, cabai, dan belacan.",
    "Panaskan minyak, tumis bumbu halus hingga harum.",
    "Masukkan kangkung, aduk cepat.",
    "Beri garam dan gula pasir.",
    "Aduk hingga kangkung layu.",
    "Angkat dan sajikan segera.",
    "Tumis kangkung belacan siap dinikmati.",
]);

// Capcay series (282-286)
$recipes[282] = langkah("Capcay Sayur", [
    "Iris wortel, kol, sawi hijau, dan kembang kol.",
    "Panaskan minyak, tumis bawang putih cincang.",
    "Masukkan wortel, masak hingga setengah matang.",
    "Masukkan sayuran lainnya.",
    "Tambahkan garam, merica, dan saus tiram.",
    "Tuang sedikit air, masak hingga sayuran matang.",
    "Kentalkan dengan larutan maizena jika suka.",
    "Angkat dan sajikan hangat.",
]);

$recipes[283] = langkah("Capcay Ayam", [
    "Potong dadu daging ayam.",
    "Iris wortel, kol, sawi, dan kembang kol.",
    "Tumis bawang putih cincang hingga harum.",
    "Masukkan ayam, masak hingga berubah warna.",
    "Masukkan sayuran, aduk rata.",
    "Tambahkan garam, merica, dan saus tiram.",
    "Tuang sedikit air, masak hingga matang.",
    "Angkat dan sajikan.",
]);

$recipes[284] = langkah("Capcay Seafood", [
    "Bersihkan udang, cumi, dan bakso ikan.",
    "Iris wortel, kol, sawi, dan kembang kol.",
    "Tumis bawang putih cincang hingga harum.",
    "Masukkan seafood, masak sebentar.",
    "Masukkan sayuran, aduk rata.",
    "Tambahkan garam, merica, dan saus tiram.",
    "Tuang sedikit air, masak hingga matang.",
    "Angkat dan sajikan hangat.",
]);

$recipes[285] = langkah("Capcay Full Goreng", [
    "Potong semua sayuran (wortel, kol, sawi, kembang kol).",
    "Potong bakso dan ayam tipis.",
    "Tumis bawang putih cincang hingga harum.",
    "Masukkan bakso dan ayam, masak hingga matang.",
    "Masukkan sayuran, aduk cepat.",
    "Tambahkan garam, merica, dan saus tiram.",
    "Masak dengan api besar hingga sayuran setengah matang.",
    "Angkat dan sajikan.",
]);

$recipes[286] = langkah("Capcay Kuah Santan", [
    "Iris wortel, kol, sawi, dan kembang kol.",
    "Tumis bawang putih dan bawang merah cincang.",
    "Masukkan sayuran, aduk rata.",
    "Tambahkan santan, garam, merica, dan gula.",
    "Masak dengan api sedang sambil diaduk.",
    "Masukkan udang atau ayam jika suka.",
    "Masak hingga sayuran matang dan kuah mengental.",
    "Angkat dan sajikan hangat.",
]);

// Pepes series (287-291)
$recipes[287] = langkah("Pepes Ikan Mas", [
    "Bersihkan ikan mas, lumuri air jeruk nipis dan garam.",
    "Haluskan bawang merah, bawang putih, cabai, kunyit, jahe.",
    "Campur bumbu halus dengan daun kemangi dan irisan tomat.",
    "Ambil selembar daun pisang, letakkan ikan.",
    "Bungkus ikan dengan bumbu, semat lidi.",
    "Kukus pepes selama 30 menit.",
    "Bakar sebentar di atas bara untuk aroma.",
    "Pepes ikan mas siap dinikmati.",
]);

$recipes[288] = langkah("Pepes Ikan Nila", [
    "Bersihkan ikan nila, kerat kedua sisi.",
    "Haluskan bawang merah, bawang putih, cabai, kunyit, kemiri.",
    "Lumuri ikan dengan bumbu halus.",
    "Tambahkan daun kemangi dan tomat iris.",
    "Bungkus dengan daun pisang.",
    "Kukus selama 25-30 menit.",
    "Bakar sebentar hingga daun pisang kering.",
    "Pepes ikan nila siap dinikmati.",
]);

$recipes[289] = langkah("Pepes Patin", [
    "Potong ikan patin ukuran sedang, lumuri jeruk nipis.",
    "Haluskan bawang merah, bawang putih, cabai, kunyit, jahe.",
    "Campur bumbu dengan daun kemangi dan serai iris.",
    "Bungkus ikan patin dengan daun pisang.",
    "Semat dengan lidi.",
    "Kukus selama 30 menit hingga matang.",
    "Bakar sebentar untuk aroma.",
    "Pepes patin siap dinikmati.",
]);

$recipes[290] = langkah("Pepes Tuna", [
    "Potong tuna dadu, lumuri air jeruk nipis.",
    "Haluskan bawang merah, bawang putih, cabai, kunyit, kemiri.",
    "Campur tuna dengan bumbu, daun kemangi, dan tomat.",
    "Bungkus dengan daun pisang.",
    "Semat lidi di kedua ujung.",
    "Kukus selama 25 menit.",
    "Bakar sebentar.",
    "Pepes tuna siap dinikmati.",
]);

$recipes[291] = langkah("Pepes Cumi", [
    "Bersihkan cumi, potong cincin.",
    "Haluskan bawang merah, bawang putih, cabai, kunyit, jahe.",
    "Campur cumi dengan bumbu halus dan daun kemangi.",
    "Tambahkan potongan tomat dan cabai rawit utuh.",
    "Bungkus dengan daun pisang.",
    "Kukus selama 20 menit.",
    "Bakar sebentar.",
    "Pepes cumi siap dinikmati.",
]);

// Perkedel series (292-296)
$recipes[292] = langkah("Perkedel Kentang", [
    "Kupas kentang, potong dadu, goreng hingga empuk.",
    "Haluskan kentang selagi hangat.",
    "Campur dengan bawang goreng, seledri, garam, dan merica.",
    "Tambahkan kuning telur, aduk rata.",
    "Bentuk bulat pipih.",
    "Kocok putih telur untuk lapisan.",
    "Celupkan perkedel ke putih telur.",
    "Goreng hingga kuning keemasan.",
]);

$recipes[293] = langkah("Perkedel Tahu", [
    "Hancurkan tahu putih hingga halus.",
    "Campur dengan irisan daun bawang, garam, dan merica.",
    "Tambahkan telur dan sedikit tepung terigu.",
    "Aduk rata hingga bisa dibentuk.",
    "Bentuk bulat pipih.",
    "Panaskan minyak goreng.",
    "Goreng perkedel hingga kecoklatan.",
    "Angkat dan tiriskan, sajikan hangat.",
]);

$recipes[294] = langkah("Perkedel Jagung", [
    "Serut jagung manis dari bonggolnya.",
    "Haluskan jagung kasar dengan blender.",
    "Campur jagung dengan telur, tepung terigu, dan daun bawang.",
    "Beri garam dan merica.",
    "Aduk rata hingga menjadi adonan.",
    "Sendok adonan, goreng dalam minyak panas.",
    "Goreng hingga kuning keemasan.",
    "Angkat dan tiriskan, sajikan dengan saus.",
]);

$recipes[295] = langkah("Perkedel Daging", [
    "Campur daging giling dengan kentang halus.",
    "Tambahkan bawang goreng, seledri, garam, dan merica.",
    "Masukkan telur dan sedikit pala bubuk.",
    "Aduk rata hingga kalis.",
    "Bentuk bulat pipih.",
    "Celupkan ke kocokan telur.",
    "Goreng dalam minyak panas hingga matang.",
    "Angkat dan tiriskan.",
]);

$recipes[296] = langkah("Perkedel Mie", [
    "Rebus mie instan hingga matang, tiriskan.",
    "Hancurkan mie dengan garpu.",
    "Campur dengan telur, irisan daun bawang, dan bumbu mie.",
    "Tambahkan sedikit tepung terigu.",
    "Aduk rata hingga bisa dibentuk.",
    "Bentuk bulat pipih.",
    "Goreng dalam minyak panas hingga kecoklatan.",
    "Angkat dan sajikan hangat.",
]);

// Rujak series (297-301)
$recipes[297] = langkah("Rujak Mangga", [
    "Kupas mangga muda, potong memanjang.",
    "Haluskan gula merah, cabai, garam, dan terasi.",
    "Tambahkan air asam jawa, aduk rata.",
    "Campur bumbu dengan potongan mangga.",
    "Aduk hingga tercampur rata.",
    "Diamkan sebentar agar bumbu meresap.",
    "Sajikan dengan taburan kacang tanah goreng.",
    "Rujak mangga siap dinikmati.",
]);

$recipes[298] = langkah("Rujak Nanas", [
    "Kupas nanas, potong dadu kecil.",
    "Haluskan gula merah, cabai, garam, dan terasi.",
    "Tambahkan air asam jawa.",
    "Campur bumbu dengan nanas.",
    "Aduk rata.",
    "Diamkan sebentar.",
    "Sajikan dengan taburan kacang tanah.",
    "Rujak nanas siap dinikmati.",
]);

$recipes[299] = langkah("Rujak Pepaya", [
    "Kupas pepaya muda, potong tipis panjang.",
    "Haluskan gula merah, cabai rawit, garam, dan terasi.",
    "Tambahkan air asam jawa.",
    "Campur bumbu dengan pepaya.",
    "Aduk hingga bumbu merata.",
    "Tambahkan potongan timun.",
    "Sajikan dengan taburan kacang.",
    "Rujak pepaya siap dinikmati.",
]);

$recipes[300] = langkah("Rujak Jambu", [
    "Potong jambu air dan jambu biji sesuai selera.",
    "Haluskan gula merah, cabai, garam, dan terasi.",
    "Tambahkan air asam jawa.",
    "Campur potongan jambu dengan bumbu.",
    "Aduk rata.",
    "Diamkan sebentar.",
    "Sajikan dengan taburan kacang tanah.",
    "Rujak jambu siap dinikmati.",
]);

$recipes[301] = langkah("Rujak Komplit", [
    "Potong mangga muda, pepaya muda, nanas, timun, dan jambu.",
    "Haluskan gula merah, cabai, garam, terasi, dan kacang tanah.",
    "Tambahkan air asam jawa.",
    "Campur semua buah dengan bumbu.",
    "Aduk hingga rata.",
    "Diamkan sebentar agar meresap.",
    "Sajikan rujak komplit segar.",
    "Rujak komplit siap dinikmati.",
]);

// Bubur Ayam series (302-306)
$recipes[302] = langkah("Bubur Ayam Polos", [
    "Cuci beras, masak dengan air hingga menjadi bubur.",
    "Tambahkan santan dan garam, aduk rata.",
    "Rebus ayam hingga empuk, suwir-suwir.",
    "Sajikan bubur di mangkuk.",
    "Tambahkan suwiran ayam di atasnya.",
    "Taburi bawang goreng dan seledri.",
    "Tambahkan kecap manis dan sambal.",
    "Bubur ayam polos siap dinikmati.",
]);

$recipes[303] = langkah("Bubur Ayam Telur", [
    "Masak beras hingga menjadi bubur dengan santan.",
    "Rebus ayam hingga empuk, suwir-suwir.",
    "Rebus telur, kupas, belah dua.",
    "Sajikan bubur di mangkuk.",
    "Tambahkan suwiran ayam dan telur rebus.",
    "Taburi bawang goreng dan seledri.",
    "Tambahkan kecap manis dan sambal.",
    "Bubur ayam telur siap dinikmati.",
]);

$recipes[304] = langkah("Bubur Ayam Cakwe", [
    "Masak beras hingga menjadi bubur dengan santan.",
    "Rebus ayam hingga empuk, suwir-suwir.",
    "Potong cakwe kecil-kecil.",
    "Sajikan bubur di mangkuk.",
    "Tambahkan suwiran ayam dan potongan cakwe.",
    "Taburi bawang goreng dan seledri.",
    "Tambahkan kecap manis dan sambal.",
    "Bubur ayam cakwe siap dinikmati.",
]);

$recipes[305] = langkah("Bubur Ayam Komplit", [
    "Masak beras hingga menjadi bubur dengan santan.",
    "Rebus ayam hingga empuk, suwir-suwir.",
    "Rebus telur, kupas.",
    "Potong cakwe dan siapkan kerupuk.",
    "Sajikan bubur di mangkuk besar.",
    "Tambahkan suwiran ayam, telur, cakwe, dan kerupuk.",
    "Taburi bawang goreng, seledri, kecap manis, dan sambal.",
    "Bubur ayam komplit siap dinikmati.",
]);

$recipes[306] = langkah("Bubur Ayam Gorengan", [
    "Masak beras hingga menjadi bubur dengan santan.",
    "Rebus ayam hingga empuk, suwir-suwir.",
    "Goreng tahu, tempe, dan bakwan untuk gorengan.",
    "Sajikan bubur di mangkuk.",
    "Tambahkan suwiran ayam.",
    "Taburi bawang goreng, seledri, kecap manis, dan sambal.",
    "Sajikan dengan aneka gorengan di samping.",
    "Bubur ayam gorengan siap dinikmati.",
]);

// Start transaction
mysqli_begin_transaction($koneksi);

foreach ($recipes as $id => $langkah) {
    $stmt->execute();
    $updated++;
}

mysqli_commit($koneksi);
$stmt->close();

echo "✅ $updated resep berhasil diperbarui dengan langkah detail.\n";
echo "Selesai!\n";

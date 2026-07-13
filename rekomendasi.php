<?php
require_once 'cek_login.php';
require_once 'koneksi.php';
require_once 'fungsi_gizi.php';

$keyword = trim($_GET['cari'] ?? '');
$results = [];

if (!empty($keyword)) {
    $search_param = '%' . $keyword . '%';

    $stmt = mysqli_prepare($koneksi, "
        SELECT r.*, kr.nama_kategori, u.nama AS nama_pembuat
        FROM resep r
        LEFT JOIN kategori_resep kr ON r.id_kategori = kr.id
        LEFT JOIN users u ON r.id_user = u.id
        WHERE r.judul LIKE ? OR r.deskripsi LIKE ?
        ORDER BY r.created_at DESC
    ");
    mysqli_stmt_bind_param($stmt, 'ss', $search_param, $search_param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resep_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    foreach ($resep_list as $resep) {
        $gizi = hitung_gizi_resep($resep['id']);
        if ($gizi) {
            $results[] = [
                'id' => $resep['id'],
                'judul' => $resep['judul'],
                'deskripsi' => $resep['deskripsi'],
                'jumlah_porsi' => $resep['jumlah_porsi'],
                'nama_kategori' => $resep['nama_kategori'],
                'nama_pembuat' => $resep['nama_pembuat'],
                'created_at' => $resep['created_at'],
                'kalori_per_porsi' => $gizi['per_porsi']['kalori'],
                'protein_per_porsi' => $gizi['per_porsi']['protein'],
                'karbo_per_porsi' => $gizi['per_porsi']['karbohidrat'],
                'lemak_per_porsi' => $gizi['per_porsi']['lemak'],
            ];
        }
    }

    usort($results, function($a, $b) {
        return $a['kalori_per_porsi'] <=> $b['kalori_per_porsi'];
    });
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Resep â€” Rasa dan Gizi</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FAF7F2] text-[#2C2620] font-sans antialiased min-h-screen">

<?php $base_path = ''; $active_page = 'rekomendasi'; require __DIR__ . '/partials/navbar.php'; ?>

<div class="max-w-6xl mx-auto px-6 py-8">
    <div class="flex items-start gap-4 md:gap-6 mb-6 md:mb-8">
        <div class="flex-1">
            <span class="text-[#A3492D] text-[11px] md:text-[12px] tracking-[0.15em] uppercase block mb-1">Rekomendasi</span>
            <h1 class="font-serif text-2xl sm:text-3xl text-[#2C2620] font-normal mb-2">Rekomendasi Resep</h1>
            <p class="text-[13px] md:text-[14px] text-[#4A4438]">Cari resep dari seluruh pengguna, urutkan dari yang paling rendah kalori.</p>
        </div>
        <div class="hidden md:block w-20 h-20 md:w-28 md:h-28 shrink-0">
            <img src="assets/images/rekomendasi.jpg" alt="" class="w-full h-full object-cover">
        </div>
    </div>

    <form method="GET" action="" class="bg-white p-5 mb-8 shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]">
        <div class="flex flex-col sm:flex-row gap-3">
            <input type="search" name="cari" id="searchInput" value="<?= htmlspecialchars($keyword) ?>"
                   class="flex-1 px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all"
                   placeholder="Cari resep... (contoh: bakso, nasi goreng)" autofocus autocomplete="off">
            <button type="submit"
                    class="py-2.5 px-6 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all">
                Cari
            </button>
            <?php if (!empty($keyword)): ?>
                <a href="rekomendasi.php"
                   class="py-2.5 px-6 border border-[#D1C4B0] bg-white text-[13px] tracking-[0.1em] uppercase text-[#4A4438] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all text-center no-underline">
                    Reset
                </a>
            <?php endif; ?>
        </div>
    </form>

    <?php if (!empty($keyword)): ?>
        <div class="bg-[#F5F0E8] px-4 py-3 mb-6 text-[14px] text-[#4A4438]">
            Menampilkan <span class="font-medium"><?= count($results) ?></span> hasil untuk "<?= htmlspecialchars($keyword) ?>" &mdash; diurutkan dari kalori terendah
        </div>

        <?php if (empty($results)): ?>
            <div class="bg-[#E4DBC8] p-16 text-center">
                <p class="text-[#4A4438] text-base mb-2">Tidak ada resep yang cocok dengan "<?= htmlspecialchars($keyword) ?>"</p>
                <p class="text-[14px] text-[#4A4438] mb-5">Coba gunakan kata kunci lain atau tambah resep baru</p>
                <a href="resep/tambah.php" class="py-2.5 px-5 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all no-underline inline-block">Tambah Resep Baru</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($results as $r): ?>
                    <div class="bg-white shadow-[0_6px_20px_rgba(0,0,0,0.14)] flex flex-col rounded-[2px]" style="border-top: 2px solid #A3492D;">
                        <div class="p-5 flex-1">
                            <div class="flex justify-between items-start gap-2 mb-2">
                                <h2 class="font-serif text-lg sm:text-xl text-[#2C2620] font-normal"><?= htmlspecialchars($r['judul']) ?></h2>
                                <?php if ($r['kalori_per_porsi'] <= 100): ?>
                                    <span class="text-[#A3492D] text-[9px] sm:text-[10px] tracking-[0.1em] uppercase whitespace-nowrap border border-[#A3492D] px-1.5 sm:px-2 py-0.5">Rendah</span>
                                <?php elseif ($r['kalori_per_porsi'] <= 200): ?>
                                    <span class="text-[#6B6154] text-[9px] sm:text-[10px] tracking-[0.1em] uppercase whitespace-nowrap border border-[#DFD5C4] px-1.5 sm:px-2 py-0.5">Sedang</span>
                                <?php else: ?>
                                    <span class="text-[#6B6154] text-[9px] sm:text-[10px] tracking-[0.1em] uppercase whitespace-nowrap border border-[#DFD5C4] px-1.5 sm:px-2 py-0.5">Tinggi</span>
                                <?php endif; ?>
                            </div>

                            <?php if ($r['nama_kategori']): ?>
                                <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-3"><?= htmlspecialchars($r['nama_kategori']) ?></span>
                            <?php endif; ?>

                            <p class="text-[14px] text-[#4A4438] mb-2 line-clamp-2"><?= htmlspecialchars(substr($r['deskripsi'] ?? '', 0, 100)) ?></p>
                            <p class="text-[11px] text-[#6B6154] mb-4">
                                Oleh: <?= htmlspecialchars($r['nama_pembuat']) ?> &middot;
                                Porsi: <?= $r['jumlah_porsi'] ?> &middot;
                                <?= date('d/m/Y', strtotime($r['created_at'])) ?>
                            </p>

                            <div class="bg-white p-3 grid grid-cols-2 gap-2 text-center shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]">
                                <div>
                                    <span class="block font-serif text-lg text-[#A3492D]"><?= $r['kalori_per_porsi'] ?></span>
                                    <span class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154]">Kalori</span>
                                </div>
                                <div>
                                    <span class="block font-serif text-lg text-[#A3492D]"><?= $r['protein_per_porsi'] ?></span>
                                    <span class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154]">Protein</span>
                                </div>
                                <div>
                                    <span class="block font-serif text-lg text-[#A3492D]"><?= $r['karbo_per_porsi'] ?></span>
                                    <span class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154]">Karbo</span>
                                </div>
                                <div>
                                    <span class="block font-serif text-lg text-[#A3492D]"><?= $r['lemak_per_porsi'] ?></span>
                                    <span class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154]">Lemak</span>
                                </div>
                            </div>
                        </div>
                        <div class="border-t border-[#E4DBC8] px-5 py-3">
                            <a href="resep/gunakan.php?sumber=<?= $r['id'] ?>"
                               class="block w-full text-center py-2.5 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all no-underline">
                                Gunakan Resep Ini
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="bg-[#E4DBC8] p-16 text-center">
            <p class="text-[#4A4438] text-base mb-2">Masukkan kata kunci untuk mencari resep</p>
            <p class="text-[14px] text-[#4A4438]">Cari berbagai resep Nusantara dari seluruh pengguna, lalu gunakan sebagai inspirasi!</p>
        </div>
    <?php endif; ?>
</div>

<script>
(function() {
    var trigger = document.querySelector('.user-dropdown-trigger');
    var menu = document.querySelector('.user-dropdown-menu');
    if (trigger && menu) {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            menu.classList.toggle('hidden');
        });
        document.addEventListener('click', function() {
            if (!menu.classList.contains('hidden')) menu.classList.add('hidden');
        });
        menu.addEventListener('click', function(e) { e.stopPropagation(); });
    }
})();
</script>
</body>
</html>

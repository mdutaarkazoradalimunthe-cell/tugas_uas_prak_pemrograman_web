<?php
require_once '../config/cek_login.php';
require_once '../config/koneksi.php';
require_once '../includes/fungsi_gizi.php';

$id_resep = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_resep <= 0) {
    header('Location: index.php');
    exit;
}

$bahan_user = [];
if (isset($_GET['bahan']) && $_GET['bahan'] !== '') {
    $bahan_user = array_map('intval', explode(',', $_GET['bahan']));
}

$id_user = $_SESSION['id_user'];
$is_private = false;

$stmt = mysqli_prepare($koneksi, "
    SELECT r.*, kr.nama_kategori 
    FROM resep_pribadi r
    LEFT JOIN kategori_resep kr ON r.id_kategori = kr.id
    WHERE r.id = ? AND r.id_user = ?
");
mysqli_stmt_bind_param($stmt, 'ii', $id_resep, $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$resep = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($resep) {
    $is_private = true;
    $gizi = hitung_gizi_resep_pribadi($id_resep);
    $bahan = get_bahan_resep_pribadi($id_resep);
} else {
    $stmt = mysqli_prepare($koneksi, "
        SELECT r.*, kr.nama_kategori 
        FROM resep r
        LEFT JOIN kategori_resep kr ON r.id_kategori = kr.id
        WHERE r.id = ?
    ");
    mysqli_stmt_bind_param($stmt, 'i', $id_resep);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resep = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$resep) {
        header('Location: index.php');
        exit;
    }

    $gizi = hitung_gizi_resep($id_resep);
    $bahan = get_bahan_resep($id_resep);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($resep['judul']) ?> â€” Rasa dan Gizi</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FAF7F2] text-[#2C2620] font-sans antialiased min-h-screen">

<?php $base_path = '../'; $active_page = 'resep'; require __DIR__ . '/../includes/partials/navbar.php'; ?>

<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="flex items-start gap-4 md:gap-6 mb-6 md:mb-8">
        <div class="flex-1">
            <span class="text-[#A3492D] text-[11px] md:text-[12px] tracking-[0.15em] uppercase block mb-1">
                <?= htmlspecialchars($resep['nama_kategori'] ?? 'Resep') ?>
            </span>
            <h1 class="font-serif text-2xl sm:text-3xl text-[#2C2620] font-normal mb-2"><?= htmlspecialchars($resep['judul']) ?></h1>
            <p class="text-[13px] md:text-[14px] text-[#4A4438]"><?= $resep['jumlah_porsi'] ?> porsi &middot; Dibuat <?= date('d/m/Y H:i', strtotime($resep['created_at'])) ?></p>
            <?php if ($resep['deskripsi']): ?>
                <p class="text-[13px] md:text-[14px] text-[#4A4438] mt-3 leading-relaxed"><?= nl2br(htmlspecialchars($resep['deskripsi'])) ?></p>
            <?php endif; ?>
        </div>
        <div class="hidden md:block w-24 h-24 md:w-32 md:h-32 shrink-0">
            <img src="../assets/images/detail.jpg" alt="" class="w-full h-full object-cover">
        </div>
    </div>

    <?php if ($gizi): ?>
    <div class="bg-white p-6 mb-8 shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]">
        <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-3">Informasi Gizi</span>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 text-center shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]" style="border-top: 3px solid #D9733E;">
                <div class="font-serif text-2xl text-[#A3492D]"><?= $gizi['per_porsi']['kalori'] ?></div>
                <div class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154] mt-1">Kalori (kkal)</div>
            </div>
            <div class="bg-white p-4 text-center shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]" style="border-top: 3px solid #A3492D;">
                <div class="font-serif text-2xl text-[#A3492D]"><?= $gizi['per_porsi']['protein'] ?></div>
                <div class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154] mt-1">Protein (g)</div>
            </div>
            <div class="bg-white p-4 text-center shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]" style="border-top: 3px solid #B5A642;">
                <div class="font-serif text-2xl text-[#A3492D]"><?= $gizi['per_porsi']['karbohidrat'] ?></div>
                <div class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154] mt-1">Karbohidrat (g)</div>
            </div>
            <div class="bg-white p-4 text-center shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]" style="border-top: 3px solid #6B8F71;">
                <div class="font-serif text-2xl text-[#A3492D]"><?= $gizi['per_porsi']['lemak'] ?></div>
                <div class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154] mt-1">Lemak (g)</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="bg-white p-6 mb-8 shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]">
        <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-4">Bahan-Bahan</span>
        <div class="overflow-x-auto">
            <table class="w-full text-[13px] min-w-[500px]">
                <thead>
                    <tr class="border-b border-[#DFD5C4] text-[12px] tracking-[0.15em] uppercase text-[#6B6154]">
                        <th class="text-left py-2.5 pr-4 font-normal">Bahan</th>
                        <th class="text-right py-2.5 px-4 font-normal">Jumlah</th>
                        <th class="text-right py-2.5 px-4 font-normal">Kalori</th>
                        <th class="text-right py-2.5 px-4 font-normal">Protein</th>
                        <th class="text-right py-2.5 px-4 font-normal">Karbo</th>
                        <th class="text-right py-2.5 px-4 font-normal">Lemak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bahan as $b):
                        $punya = in_array((int)$b['id_bahan'], $bahan_user);
                    ?>
                    <tr class="border-b border-[#DFD5C4] <?= $punya ? '' : '' ?>">
                        <td class="py-2.5 pr-4 <?= $punya ? 'text-[#A3492D]' : '' ?>"><?php if ($punya): ?><span class="mr-1">&checkmark;</span><?php endif; ?><?= htmlspecialchars($b['nama_bahan']) ?></td>
                        <td class="text-right py-2.5 px-4 text-[#6B6154]"><?= ($b['satuan'] && $b['jumlah_asli']) ? htmlspecialchars($b['jumlah_asli'] . ' ' . $b['satuan']) . ' (' . $b['jumlah_gram'] . ' g)' : $b['jumlah_gram'] . ' g' ?></td>
                        <td class="text-right py-2.5 px-4"><?= $b['kalori'] ?></td>
                        <td class="text-right py-2.5 px-4"><?= $b['protein'] ?></td>
                        <td class="text-right py-2.5 px-4"><?= $b['karbohidrat'] ?></td>
                        <td class="text-right py-2.5 px-4"><?= $b['lemak'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <?php if ($gizi): ?>
                <tfoot>
                    <tr class="border-t-2 border-[#2C2620] font-medium">
                        <td class="py-2.5 pr-4">Total</td>
                        <td class="text-right py-2.5 px-4 text-[#6B6154]">-</td>
                        <td class="text-right py-2.5 px-4"><?= $gizi['total_kalori'] ?></td>
                        <td class="text-right py-2.5 px-4"><?= $gizi['total_protein'] ?></td>
                        <td class="text-right py-2.5 px-4"><?= $gizi['total_karbohidrat'] ?></td>
                        <td class="text-right py-2.5 px-4"><?= $gizi['total_lemak'] ?></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <div class="bg-white p-6 mb-8 shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]">
        <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-4">Langkah Memasak</span>
        <div class="text-[14px] text-[#4A4438] leading-relaxed whitespace-pre-line"><?= nl2br(htmlspecialchars($resep['langkah_memasak'])) ?></div>
    </div>

    <div class="flex gap-2 md:gap-3 flex-wrap">
        <?php if ($resep['id_user'] == $_SESSION['id_user']): ?>
            <a href="cetak_pdf.php?id=<?= $resep['id'] ?>" target="_blank" class="py-2 px-4 sm:py-2.5 sm:px-5 border border-[#6B8F71] bg-[#6B8F71] text-white text-[12px] sm:text-[13px] tracking-[0.1em] uppercase hover:bg-[#5A7A60] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(107,143,113,0.35)] hover:shadow-[0_8px_22px_rgba(107,143,113,0.45)] transition-all no-underline">Cetak PDF</a>
            <a href="edit.php?id=<?= $resep['id'] ?>" class="py-2 px-4 sm:py-2.5 sm:px-5 border border-[#A3492D] bg-[#A3492D] text-white text-[12px] sm:text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all no-underline">Edit Resep</a>
            <a href="hapus.php?id=<?= $resep['id'] ?>" class="py-2 px-4 sm:py-2.5 sm:px-5 border border-[#D1C4B0] bg-white text-[12px] sm:text-[13px] tracking-[0.1em] uppercase text-[#4A4438] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all no-underline" onclick="confirmHapus(event, this)">Hapus Resep</a>
        <?php else: ?>
            <a href="gunakan.php?sumber=<?= $resep['id'] ?><?= isset($_GET['bahan']) ? '&bahan=' . urlencode($_GET['bahan']) : '' ?>"
               class="py-2 px-4 sm:py-2.5 sm:px-5 border border-[#A3492D] bg-[#A3492D] text-white text-[12px] sm:text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all no-underline">
                Gunakan Resep Ini
            </a>
        <?php endif; ?>
        <a href="index.php" class="py-2 px-4 sm:py-2.5 sm:px-5 border border-[#D1C4B0] bg-white text-[12px] sm:text-[13px] tracking-[0.1em] uppercase text-[#4A4438] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all no-underline">Kembali</a>
    </div>
</div>

<div id="hapusModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/40"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px] max-w-[90%] sm:max-w-sm w-full" style="border-top: 2px solid #A3492D;">
            <div class="p-4 md:p-6">
                <h3 class="font-serif text-lg md:text-xl text-[#2C2620] font-normal mb-2">Hapus Resep</h3>
                <p class="text-[13px] md:text-[14px] text-[#4A4438] mb-4 md:mb-6">Yakin ingin menghapus resep ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex gap-2 md:gap-3 justify-end">
                    <button id="batalHapus" class="py-1.5 px-3 md:py-2 md:px-4 border border-[#D1C4B0] bg-white text-[12px] md:text-[13px] tracking-[0.1em] uppercase text-[#4A4438] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all">Batal</button>
                    <a id="konfirmasiHapus" href="#" class="py-1.5 px-3 md:py-2 md:px-4 border border-[#A3492D] bg-[#A3492D] text-white text-[12px] md:text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all no-underline">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var t = document.querySelector('.user-dropdown-trigger');
    var m = document.querySelector('.user-dropdown-menu');
    if (t && m) {
        t.addEventListener('click', function(e) { e.stopPropagation(); m.classList.toggle('hidden'); });
        document.addEventListener('click', function() { if (!m.classList.contains('hidden')) m.classList.add('hidden'); });
        m.addEventListener('click', function(e) { e.stopPropagation(); });
    }

    var modal = document.getElementById('hapusModal');
    var batal = document.getElementById('batalHapus');
    var konfirmasi = document.getElementById('konfirmasiHapus');
    if (modal && batal && konfirmasi) {
        window.confirmHapus = function(e, el) {
            e.preventDefault();
            konfirmasi.href = el.href;
            modal.classList.remove('hidden');
        };
        batal.addEventListener('click', function() { modal.classList.add('hidden'); });
        modal.querySelector('div:first-child').addEventListener('click', function() { modal.classList.add('hidden'); });
        konfirmasi.addEventListener('click', function() { modal.classList.add('hidden'); });
    }
})();
</script>
</body>
</html>

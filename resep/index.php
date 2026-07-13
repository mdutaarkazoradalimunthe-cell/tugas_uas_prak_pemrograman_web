<?php
require_once '../cek_login.php';require_once '../koneksi.php';require_once '../fungsi_gizi.php';$id_user = $_SESSION['id_user'];$stmt = mysqli_prepare($koneksi, "    SELECT r.*, kr.nama_kategori     FROM resep_pribadi r    LEFT JOIN kategori_resep kr ON r.id_kategori = kr.id    WHERE r.id_user = ?    ORDER BY r.created_at DESC");mysqli_stmt_bind_param($stmt, 'i', $id_user);mysqli_stmt_execute($stmt);$result = mysqli_stmt_get_result($stmt);$resep_list = mysqli_fetch_all($result, MYSQLI_ASSOC);mysqli_stmt_close($stmt);
?>
<!DOCTYPE html><html lang="id"><head>    <meta charset="UTF-8">    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title>Resep Saya — Rasa dan Gizi</title>    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">    <script src="https://cdn.tailwindcss.com"></script></head><body class="bg-[#FAF7F2] text-[#2C2620] font-sans antialiased min-h-screen">
<?php $base_path = '../'; $active_page = 'resep'; require __DIR__ . '/../partials/navbar.php'; ?>
<div class="max-w-6xl mx-auto px-6 py-8">    <div class="flex items-start justify-between mb-10">        <div>            <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-1">Dashboard</span>            <h1 class="font-serif text-3xl text-[#2C2620] font-normal">Resep Saya</h1>        </div>        <a href="tambah.php" class="py-2.5 px-5 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all no-underline inline-block">            + Tambah Resep        </a>    </div>    <?php if (empty($resep_list)): ?>        <div class="bg-[#E4DBC8] p-16 text-center">            <p class="text-[#4A4438] text-base mb-5">Belum ada resep. Yuk buat resep pertama kamu!</p>            <a href="tambah.php" class="py-2.5 px-5 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all no-underline inline-block">Buat Resep Sekarang</a>        </div>    <?php else: ?>        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">            <?php foreach ($resep_list as $resep):                 $gizi = hitung_gizi_resep_pribadi($resep['id']);
?>                <div class="bg-white shadow-[0_6px_20px_rgba(0,0,0,0.14)] flex flex-col rounded-[2px]" style="border-top: 2px solid #A3492D;">                    <div class="p-5 flex-1">                        <h2 class="font-serif text-xl text-[#2C2620] font-normal mb-2"><?= htmlspecialchars($resep['judul']) ?>
</h2>                        <?php if ($resep['nama_kategori']): ?>                            <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-3"><?= htmlspecialchars($resep['nama_kategori']) ?>
</span>                        <?php endif;
?>                        <p class="text-[14px] text-[#4A4438] mb-2 line-clamp-2"><?= htmlspecialchars(substr($resep['deskripsi'] ?? '', 0, 100)) ?>
</p>                        <p class="text-[11px] text-[#6B6154]"><?= $resep['jumlah_porsi'] ?> porsi &middot;
 <?= date('d/m/Y', strtotime($resep['created_at'])) ?>
</p>                        <?php if ($gizi): ?>                            <div class="mt-4 pt-4 border-t border-[#E4DBC8] grid grid-cols-2 gap-3 text-center">                                <div>                                    <div class="font-serif text-lg text-[#A3492D]"><?= $gizi['per_porsi']['kalori'] ?>
</div>                                    <div class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154]">Kalori</div>                                </div>                                <div>                                    <div class="font-serif text-lg text-[#A3492D]"><?= $gizi['per_porsi']['protein'] ?>
</div>                                    <div class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154]">Protein</div>                                </div>                                <div>                                    <div class="font-serif text-lg text-[#A3492D]"><?= $gizi['per_porsi']['karbohidrat'] ?>
</div>                                    <div class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154]">Karbo</div>                                </div>                                <div>                                    <div class="font-serif text-lg text-[#A3492D]"><?= $gizi['per_porsi']['lemak'] ?>
</div>                                    <div class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154]">Lemak</div>                                </div>                            </div>                        <?php endif;
?>                    </div>                    <div class="border-t border-[#E4DBC8] px-5 py-3 flex gap-2 text-[13px]">                        <a href="detail.php?id=<?= $resep['id'] ?>" class="py-1.5 px-3 border border-[#A3492D] bg-white text-[#A3492D] no-underline hover:bg-[#A3492D] hover:text-white hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all">Detail</a>                        <a href="edit.php?id=<?= $resep['id'] ?>" class="py-1.5 px-3 border border-[#D1C4B0] bg-white text-[#6B6154] no-underline hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all">Edit</a>                        <a href="hapus.php?id=<?= $resep['id'] ?>" class="py-1.5 px-3 border border-[#D1C4B0] bg-white text-[#6B6154] no-underline hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all" onclick="confirmHapus(event, this)">Hapus</a>                    </div>                </div>            <?php endforeach;
?>        </div>    <?php endif;
?>
</div>

<div id="hapusModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/40"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px] max-w-sm w-full" style="border-top: 2px solid #A3492D;">
            <div class="p-6">
                <h3 class="font-serif text-xl text-[#2C2620] font-normal mb-2">Hapus Resep</h3>
                <p class="text-[14px] text-[#4A4438] mb-6">Yakin ingin menghapus resep ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex gap-3 justify-end">
                    <button id="batalHapus" class="py-2 px-4 border border-[#D1C4B0] bg-white text-[13px] tracking-[0.1em] uppercase text-[#4A4438] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all">Batal</button>
                    <a id="konfirmasiHapus" href="#" class="py-2 px-4 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all no-underline">Ya, Hapus</a>
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

<?php
// Variabel yang harus di-set sebelum require:
//   $base_path   = '' untuk file root, '../' untuk file di folder resep/
//   $active_page = 'resep' | 'rekomendasi' | 'cari_bahan' | 'pustaka_gizi' | '' (default kosong = tidak ada yg aktif)
if (!isset($base_path)) $base_path = '';
if (!isset($active_page)) $active_page = '';
?>
<nav class="sticky top-0 z-40 border-b border-[#DFD5C4] bg-[#FAF7F2]">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="<?= $base_path ?>resep/index.php" class="block"><img src="<?= $base_path ?>assets/images/logo.png" alt="Rasa dan Gizi" class="h-14 w-auto"></a>
        <div class="flex items-center gap-6 text-[11px] tracking-[0.15em] uppercase text-[#6B6154]">
            <a href="<?= $base_path ?>resep/index.php" class="no-underline transition-colors duration-200 <?= $active_page === 'resep' ? 'text-[#A3492D] hover:text-[#8B3D25]' : 'text-[#6B6154] hover:text-[#2C2620]' ?>">Resep</a>
            <a href="<?= $base_path ?>rekomendasi.php" class="no-underline transition-colors duration-200 <?= $active_page === 'rekomendasi' ? 'text-[#A3492D] hover:text-[#8B3D25]' : 'text-[#6B6154] hover:text-[#2C2620]' ?>">Rekomendasi</a>
            <a href="<?= $base_path ?>resep_by_bahan.php" class="no-underline transition-colors duration-200 <?= $active_page === 'cari_bahan' ? 'text-[#A3492D] hover:text-[#8B3D25]' : 'text-[#6B6154] hover:text-[#2C2620]' ?>">Cari Bahan</a>
            <a href="<?= $base_path ?>pustaka_gizi.php" class="no-underline transition-colors duration-200 <?= $active_page === 'pustaka_gizi' ? 'text-[#A3492D] hover:text-[#8B3D25]' : 'text-[#6B6154] hover:text-[#2C2620]' ?>">Pustaka Gizi</a>
            <div class="relative">
                <span class="text-[#6B6154] cursor-pointer user-dropdown-trigger">Halo, <?= htmlspecialchars($_SESSION['nama']) ?> <span class="text-[10px] ml-0.5">▾</span></span>
                <div class="user-dropdown-menu absolute right-0 top-full mt-1 w-36 bg-[#FAF7F2] border border-[#D1C4B0] hidden z-50">
                    <a href="<?= $base_path ?>profil.php" class="block px-4 py-2.5 text-[13px] text-[#2C2620] no-underline hover:bg-[#E4DBC8] transition-colors">Profil</a>
                    <a href="<?= $base_path ?>logout.php" class="block px-4 py-2.5 text-[13px] text-[#A3492D] no-underline hover:bg-[#E4DBC8] transition-colors border-t border-[#D1C4B0]">Keluar</a>
                </div>
            </div>
        </div>
    </div>
</nav>

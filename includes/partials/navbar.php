<?php
// Variabel yang harus di-set sebelum require:
//   $base_path   = '' untuk file root, '../' untuk file di folder resep/
//   $active_page = 'resep' | 'rekomendasi' | 'cari_bahan' | 'pustaka_gizi' | '' (default kosong = tidak ada yg aktif)
if (!isset($base_path)) $base_path = '';
if (!isset($active_page)) $active_page = '';
?>
<nav class="sticky top-0 z-40 border-b border-[#DFD5C4] bg-[#FAF7F2]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 sm:py-4 flex flex-wrap items-center justify-between gap-2">
        <a href="<?= $base_path ?>resep/index.php" class="block shrink-0"><img src="<?= $base_path ?>assets/images/logo.png" alt="Rasa dan Gizi" class="h-12 md:h-14 w-auto"></a>
        <div class="flex flex-wrap items-center justify-end gap-0.5 md:gap-1 text-[11px] md:text-[13px] tracking-[0.05em] uppercase">
            <a href="<?= $base_path ?>resep/index.php" class="no-underline px-2 py-1.5 md:px-4 md:py-2 rounded font-medium transition-all duration-300 ease-out <?= $active_page === 'resep' ? 'text-white bg-[#A3492D] shadow-[0_4px_10px_rgba(163,73,45,0.35)]' : 'text-black hover:text-white hover:bg-[#A3492D] hover:shadow-[0_4px_10px_rgba(163,73,45,0.35)] hover:-translate-y-0.5' ?>">Resep</a>
            <a href="<?= $base_path ?>pages/rekomendasi.php" class="no-underline px-2 py-1.5 md:px-4 md:py-2 rounded font-medium transition-all duration-300 ease-out <?= $active_page === 'rekomendasi' ? 'text-white bg-[#A3492D] shadow-[0_4px_10px_rgba(163,73,45,0.35)]' : 'text-black hover:text-white hover:bg-[#A3492D] hover:shadow-[0_4px_10px_rgba(163,73,45,0.35)] hover:-translate-y-0.5' ?>">Rekomendasi</a>
            <a href="<?= $base_path ?>pages/resep_by_bahan.php" class="no-underline px-2 py-1.5 md:px-4 md:py-2 rounded font-medium transition-all duration-300 ease-out <?= $active_page === 'cari_bahan' ? 'text-white bg-[#A3492D] shadow-[0_4px_10px_rgba(163,73,45,0.35)]' : 'text-black hover:text-white hover:bg-[#A3492D] hover:shadow-[0_4px_10px_rgba(163,73,45,0.35)] hover:-translate-y-0.5' ?>">Cari Bahan</a>
            <a href="<?= $base_path ?>pages/pustaka_gizi.php" class="no-underline px-2 py-1.5 md:px-4 md:py-2 rounded font-medium transition-all duration-300 ease-out <?= $active_page === 'pustaka_gizi' ? 'text-white bg-[#A3492D] shadow-[0_4px_10px_rgba(163,73,45,0.35)]' : 'text-black hover:text-white hover:bg-[#A3492D] hover:shadow-[0_4px_10px_rgba(163,73,45,0.35)] hover:-translate-y-0.5' ?>">Pustaka Gizi</a>
            <div class="relative">
                <span class="text-[#6B6154] cursor-pointer user-dropdown-trigger text-[11px] md:text-[13px] truncate max-w-[100px] md:max-w-none inline-block">Halo, <?= htmlspecialchars($_SESSION['nama']) ?> <span class="text-[10px] ml-0.5">▾</span></span>
                <div class="user-dropdown-menu absolute right-0 top-full mt-1 w-36 bg-[#FAF7F2] border border-[#D1C4B0] hidden z-50">
                    <a href="<?= $base_path ?>pages/profil.php" class="block px-4 py-2.5 text-[13px] text-[#2C2620] no-underline hover:bg-[#E4DBC8] transition-colors">Profil</a>
                    <a href="<?= $base_path ?>pages/logout.php" class="block px-4 py-2.5 text-[13px] text-[#A3492D] no-underline hover:bg-[#E4DBC8] transition-colors border-t border-[#D1C4B0]">Keluar</a>
                </div>
            </div>
        </div>
    </div>
</nav>

<?php
session_start();
if (isset($_SESSION['id_user'])) {
    header('Location: ../resep/index.php');
    exit;
}
require '../config/koneksi.php';

$result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM bahan_makanan");
$total_bahan = 0;
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_bahan = (int)$row['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/favicon.png">
    <title>Rasa dan Gizi — Manajemen Resep & Pustaka Gizi</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FAF7F2] text-[#2C2620] font-sans antialiased">

<nav class="sticky top-0 z-40 bg-[#FAF7F2] max-w-6xl mx-auto px-4 sm:px-6 py-4 sm:py-5 flex items-center justify-between">
    <a href="landing.php" class="block shrink-0"><img src="../assets/images/logo.png" alt="Rasa dan Gizi" class="h-12 sm:h-16 w-auto"></a>
    <div class="flex items-center gap-4 md:gap-8 text-[11px] md:text-[12px] tracking-[0.15em] uppercase text-[#4A4438]">
        <a href="../register.php" class="hover:text-[#2C2620] no-underline">Daftar</a>
        <a href="../login.php" class="hover:text-[#2C2620] no-underline">Masuk</a>
    </div>
</nav>

<main class="max-w-6xl mx-auto px-6">
    <div class="mb-2">
        <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase">Majalah Gizi</span>
    </div>
    <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl leading-tight text-[#2C2620] font-normal mb-8 max-w-3xl">
        Jelajahi Dunia Rasa<br>dan Ilmu Gizi
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-10 mb-16">
        <div class="md:col-span-2 relative">
            <img src="../assets/images/hero.jpg" alt="Hidangan sehat" class="w-full h-[280px] sm:h-[380px] md:h-[520px] object-cover" style="object-position: 50% 30%;">
            <div class="absolute bottom-2 left-2 sm:bottom-4 sm:left-4 bg-[#FAF7F2]/90 border border-[#DFD5C4] px-3 py-3 sm:px-5 sm:py-4 max-w-[90%] sm:max-w-[260px]">
                <span class="text-[#A3492D] text-[11px] sm:text-[12px] tracking-[0.15em] uppercase block mb-1">Bahan Pangan</span>
                <p class="text-[13px] sm:text-sm text-[#2C2620] leading-snug">
                    <span class="font-medium"><?= number_format($total_bahan) ?> bahan</span> makanan tersedia untuk eksplorasi gizi harian Anda.
                </p>
            </div>
        </div>
        <div class="md:col-span-1 flex flex-col justify-end pb-2">
            <p class="text-[14px] text-[#2C2620] leading-relaxed mb-6">
              Dari pustaka gizi hingga resep harian, temukan keseimbangan antara cita rasa dan kebutuhan nutrisi keluarga.
            </p>
            <a href="../register.php" class="py-2.5 px-5 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase no-underline inline-block hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all">
                Mulai Jelajahi
            </a>
        </div>
    </div>

    <section class="py-14 border-t border-[#DFD5C4]">
        <div class="mb-2">
            <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase">Fitur</span>
        </div>
        <h2 class="font-serif text-3xl md:text-4xl text-[#2C2620] font-normal mb-10">Mengapa Gizi?</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10">
            <div>
                <img src="../assets/images/fitur-1.jpg" alt="Koleksi resep" class="w-full aspect-[4/5] object-cover mb-4">
                <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-1">Resep</span>
                <p class="text-[14px] text-[#4A4438] leading-relaxed">Katalog resep dengan kalkulasi gizi otomatis tiap hidangan.</p>
            </div>
            <div>
                <img src="../assets/images/fitur-2.jpg" alt="Pustaka gizi" class="w-full aspect-[4/5] object-cover mb-4">
                <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-1">Pustaka</span>
                <p class="text-[14px] text-[#4A4438] leading-relaxed">Data lengkap 1.500+ bahan makanan dan nilai gizinya.</p>
            </div>
            <div>
                <img src="../assets/images/fitur-3.jpg" alt="Rekomendasi cerdas" class="w-full aspect-[4/5] object-cover mb-4">
                <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-1">Rekomendasi</span>
                <p class="text-[14px] text-[#4A4438] leading-relaxed">Temukan resep berdasarkan bahan yang tersedia di dapur.</p>
            </div>
        </div>
    </section>
</main>

<footer class="max-w-6xl mx-auto px-6 py-8 border-t border-[#DFD5C4] mt-8">
    <p class="text-[12px] tracking-[0.1em] uppercase text-[#6B6154]">&copy; <?= date('Y') ?> Rasa dan Gizi</p>
</footer>

</body>
</html>

<?php
require_once '../config/cek_login.php';
require_once '../config/koneksi.php';

$id_user = $_SESSION['id_user'];
$error = '';
$success = '';

$kategori_result = mysqli_query($koneksi, "SELECT id, nama_kategori FROM kategori_resep ORDER BY nama_kategori ASC");
$kategori_list = mysqli_fetch_all($kategori_result, MYSQLI_ASSOC);

$bahan_result = mysqli_query($koneksi, "SELECT id, nama_bahan, ROUND((protein_per_100g * 4) + (karbohidrat_per_100g * 4) + (lemak_per_100g * 9), 2) AS kalori_per_100g, protein_per_100g, karbohidrat_per_100g, lemak_per_100g FROM bahan_makanan ORDER BY nama_bahan ASC");
$bahan_list = mysqli_fetch_all($bahan_result, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $id_kategori = !empty($_POST['id_kategori']) ? (int)$_POST['id_kategori'] : null;
    $jumlah_porsi = (int)$_POST['jumlah_porsi'];
    $langkah_memasak = trim($_POST['langkah_memasak'] ?? '');
    $id_bahan = $_POST['id_bahan'] ?? [];
    $jumlah_gram = $_POST['jumlah_gram'] ?? [];

    if (empty($judul)) {
        $error = 'Judul resep wajib diisi!';
    } elseif ($jumlah_porsi < 1) {
        $error = 'Jumlah porsi minimal 1!';
    } elseif (empty($langkah_memasak)) {
        $error = 'Langkah memasak wajib diisi!';
    } elseif (empty($id_bahan) || count($id_bahan) === 0) {
        $error = 'Pilih minimal 1 bahan!';
    } else {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO resep_pribadi (id_user, id_kategori, judul, deskripsi, langkah_memasak, jumlah_porsi) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'iisssi', $id_user, $id_kategori, $judul, $deskripsi, $langkah_memasak, $jumlah_porsi);

        if (mysqli_stmt_execute($stmt)) {
            $id_resep = mysqli_insert_id($koneksi);
            mysqli_stmt_close($stmt);

            $stmt_bahan = mysqli_prepare($koneksi, "INSERT INTO resep_pribadi_bahan (id_resep_pribadi, id_bahan, jumlah_gram) VALUES (?, ?, ?)");

            $sukses_bahan = true;
            for ($i = 0; $i < count($id_bahan); $i++) {
                if (!empty($id_bahan[$i]) && !empty($jumlah_gram[$i]) && $jumlah_gram[$i] > 0) {
                    mysqli_stmt_bind_param($stmt_bahan, 'iid', $id_resep, $id_bahan[$i], $jumlah_gram[$i]);
                    if (!mysqli_stmt_execute($stmt_bahan)) {
                        $sukses_bahan = false;
                        break;
                    }
                }
            }
            mysqli_stmt_close($stmt_bahan);

            if ($sukses_bahan) {
                $success = 'Resep berhasil disimpan!';
                header("Refresh: 2; URL=detail.php?id=$id_resep");
            } else {
                $error = 'Gagal menyimpan bahan: ' . mysqli_error($koneksi);
            }
        } else {
            $error = 'Gagal menyimpan resep: ' . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Resep â€” Rasa dan Gizi</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FAF7F2] text-[#2C2620] font-sans antialiased min-h-screen">

<?php $base_path = '../'; $active_page = 'resep'; require __DIR__ . '/../includes/partials/navbar.php'; ?>

<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="flex items-start gap-4 md:gap-6 mb-6 md:mb-8">
        <div class="flex-1">
            <span class="text-[#A3492D] text-[11px] md:text-[12px] tracking-[0.15em] uppercase block mb-1">Resep Baru</span>
            <h1 class="font-serif text-2xl sm:text-3xl text-[#2C2620] font-normal">Tambah Resep Baru</h1>
        </div>
        <div class="hidden md:block w-20 h-20 md:w-24 md:h-24 shrink-0">
            <img src="../assets/images/tambah.jpg" alt="" class="w-full h-full object-cover">
        </div>
    </div>

    <?php if ($error): ?>
        <div class="border border-[#A3492D] bg-[#FAF7F2] text-[#A3492D] text-[13px] px-4 py-3 mb-6"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-[#F5F0E8] text-[#2C2620] text-[13px] px-4 py-3 mb-6"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" action="" class="bg-white p-6 shadow-[0_2px_8px_rgba(0,0,0,0.06)] rounded-[2px]">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
            <div>
                <label for="judul" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Judul Resep</label>
                <input type="text" id="judul" name="judul" required
                       class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all"
                       placeholder="Contoh: Nasi Goreng Spesial">
            </div>
            <div>
                <label for="id_kategori" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Kategori</label>
                <select id="id_kategori" name="id_kategori"
                        class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori_list as $k): ?>
                        <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="jumlah_porsi" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Jumlah Porsi</label>
                <input type="number" id="jumlah_porsi" name="jumlah_porsi" required min="1" value="1"
                       class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
            </div>
        </div>

        <div class="mb-6">
            <label for="deskripsi" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="2"
                      class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all"
                      placeholder="Deskripsi singkat tentang resep ini"></textarea>
        </div>

        <div class="mb-6">
            <div class="flex justify-between items-center mb-3">
                <span class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154]">Bahan-Bahan</span>
            <button type="button" id="tambahBahan" class="py-1.5 px-2 sm:px-3 border border-[#D1C4B0] bg-white text-[10px] sm:text-[11px] tracking-[0.1em] uppercase text-[#2C2620] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all">
                + Tambah Bahan
            </button>
            </div>

            <div id="daftarBahan">
                <div class="bahan-row grid grid-cols-1 md:grid-cols-3 gap-3 mb-3 p-3 bg-[#F5F0E8] rounded-[2px]">
                    <div>
                        <label class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154] block mb-1">Nama Bahan</label>
                        <select name="id_bahan[]" required class="bahan-select w-full px-3 py-2 bg-white border border-[#D1C4B0] text-[13px] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
                            <option value="">-- Pilih Bahan --</option>
                            <?php foreach ($bahan_list as $b): ?>
                                <option value="<?= $b['id'] ?>" 
                                        data-kalori="<?= $b['kalori_per_100g'] ?>"
                                        data-protein="<?= $b['protein_per_100g'] ?>"
                                        data-karbo="<?= $b['karbohidrat_per_100g'] ?>"
                                        data-lemak="<?= $b['lemak_per_100g'] ?>">
                                    <?= htmlspecialchars($b['nama_bahan']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154] block mb-1">Jumlah (gram)</label>
                        <input type="number" name="jumlah_gram[]" required min="0" step="0.01"
                               class="w-full px-3 py-2 bg-white border border-[#D1C4B0] text-[13px] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all"
                               placeholder="100">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="hapusBahan py-2 px-2 sm:px-3 border border-[#D1C4B0] bg-white text-[10px] sm:text-[11px] tracking-[0.1em] uppercase text-[#6B6154] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all">Hapus</button>
                    </div>
                </div>
            </div>
            <p class="text-[10px] text-[#6B6154] mt-1">Klik dropdown dan ketik nama bahan untuk mencari</p>
        </div>

        <div class="mb-6">
            <label for="langkah_memasak" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Langkah Memasak</label>
            <textarea id="langkah_memasak" name="langkah_memasak" rows="6" required
                      class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all"
                      placeholder="Tulis langkah-langkah memasak di sini..."></textarea>
        </div>

        <div class="flex flex-wrap gap-2 md:gap-3">
            <button type="submit" class="w-full sm:w-auto py-2 px-4 sm:py-2.5 sm:px-6 border border-[#A3492D] bg-[#A3492D] text-white text-[12px] sm:text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all">Simpan Resep</button>
            <a href="index.php" class="w-full sm:w-auto text-center py-2 px-4 sm:py-2.5 sm:px-6 border border-[#D1C4B0] bg-white text-[12px] sm:text-[13px] tracking-[0.1em] uppercase text-[#4A4438] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all no-underline">Batal</a>
        </div>
    </form>
</div>

<script>
    document.getElementById('tambahBahan').addEventListener('click', function() {
        const container = document.getElementById('daftarBahan');
        const firstRow = container.querySelector('.bahan-row');
        const newRow = firstRow.cloneNode(true);
        newRow.querySelector('select').value = '';
        newRow.querySelector('input[type="number"]').value = '';
        container.appendChild(newRow);
        newRow.querySelector('.hapusBahan').addEventListener('click', function() {
            if (container.querySelectorAll('.bahan-row').length > 1) {
                newRow.remove();
            } else {
                alert('Minimal harus ada 1 bahan!');
            }
        });
    });

    document.querySelectorAll('.hapusBahan').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const container = document.getElementById('daftarBahan');
            if (container.querySelectorAll('.bahan-row').length > 1) {
                this.closest('.bahan-row').remove();
            } else {
                alert('Minimal harus ada 1 bahan!');
            }
        });
    });
</script>
<script>
(function() {
    var t = document.querySelector('.user-dropdown-trigger');
    var m = document.querySelector('.user-dropdown-menu');
    if (t && m) {
        t.addEventListener('click', function(e) { e.stopPropagation(); m.classList.toggle('hidden'); });
        document.addEventListener('click', function() { if (!m.classList.contains('hidden')) m.classList.add('hidden'); });
        m.addEventListener('click', function(e) { e.stopPropagation(); });
    }
})();
</script>
</body>
</html>

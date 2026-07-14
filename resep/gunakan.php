<?php
require_once '../config/cek_login.php';
require_once '../config/koneksi.php';
require_once '../includes/fungsi_gizi.php';

$id_user = $_SESSION['id_user'];
$error = '';
$success = '';

$sumber_id = isset($_GET['sumber']) ? (int)$_GET['sumber'] : 0;
if ($sumber_id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = mysqli_prepare($koneksi, "SELECT * FROM resep WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $sumber_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$resep_sumber = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$resep_sumber) {
    header('Location: index.php');
    exit;
}

$kategori_result = mysqli_query($koneksi, "SELECT id, nama_kategori FROM kategori_resep ORDER BY nama_kategori ASC");
$kategori_list = mysqli_fetch_all($kategori_result, MYSQLI_ASSOC);

$bahan_result = mysqli_query($koneksi, "SELECT id, nama_bahan, ROUND((protein_per_100g * 4) + (karbohidrat_per_100g * 4) + (lemak_per_100g * 9), 2) AS kalori_per_100g, protein_per_100g, karbohidrat_per_100g, lemak_per_100g FROM bahan_makanan ORDER BY nama_bahan ASC");
$bahan_list = mysqli_fetch_all($bahan_result, MYSQLI_ASSOC);

$stmt = mysqli_prepare($koneksi, "SELECT rb.id, rb.id_bahan, rb.jumlah_gram, bm.nama_bahan, ROUND((bm.protein_per_100g * 4) + (bm.karbohidrat_per_100g * 4) + (bm.lemak_per_100g * 9), 2) AS kalori_per_100g, bm.protein_per_100g, bm.karbohidrat_per_100g, bm.lemak_per_100g FROM resep_bahan rb JOIN bahan_makanan bm ON rb.id_bahan = bm.id WHERE rb.id_resep = ? ORDER BY bm.nama_bahan ASC");
mysqli_stmt_bind_param($stmt, 'i', $sumber_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$bahan_sumber = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

$bahan_user = [];
if (isset($_GET['bahan']) && $_GET['bahan'] !== '') {
    $bahan_user = array_map('intval', explode(',', $_GET['bahan']));
}

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
        $stmt = mysqli_prepare($koneksi, "INSERT INTO resep_pribadi (id_user, id_kategori, judul, deskripsi, langkah_memasak, jumlah_porsi, sumber_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'iisssii', $id_user, $id_kategori, $judul, $deskripsi, $langkah_memasak, $jumlah_porsi, $sumber_id);

        if (mysqli_stmt_execute($stmt)) {
            $id_resep_baru = mysqli_insert_id($koneksi);
            mysqli_stmt_close($stmt);

            $stmt_bahan = mysqli_prepare($koneksi, "INSERT INTO resep_pribadi_bahan (id_resep_pribadi, id_bahan, jumlah_gram) VALUES (?, ?, ?)");
            $sukses_bahan = true;

            for ($i = 0; $i < count($id_bahan); $i++) {
                if (!empty($id_bahan[$i]) && !empty($jumlah_gram[$i]) && $jumlah_gram[$i] > 0) {
                    mysqli_stmt_bind_param($stmt_bahan, 'iid', $id_resep_baru, $id_bahan[$i], $jumlah_gram[$i]);
                    if (!mysqli_stmt_execute($stmt_bahan)) {
                        $sukses_bahan = false;
                        break;
                    }
                }
            }
            mysqli_stmt_close($stmt_bahan);

            if ($sukses_bahan) {
                $success = 'Resep berhasil disimpan sebagai resep baru!';
                header("Refresh: 2; URL=detail.php?id=$id_resep_baru");
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
    <title>Gunakan Resep â€” <?= htmlspecialchars($resep_sumber['judul']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FAF7F2] text-[#2C2620] font-sans antialiased min-h-screen">

<?php $base_path = '../'; $active_page = 'resep'; require __DIR__ . '/../partials/navbar.php'; ?>

<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="flex items-start gap-6 mb-8">
        <div class="flex-1">
            <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-1">Inspirasi</span>
            <h1 class="font-serif text-3xl text-[#2C2620] font-normal mb-2">Gunakan Resep sebagai Inspirasi</h1>
            <p class="text-[14px] text-[#4A4438]">Sumber: <span class="font-medium"><?= htmlspecialchars($resep_sumber['judul']) ?></span> &mdash; Edit sesuai kebutuhan, lalu simpan sebagai resep barumu.</p>
        </div>
        <div class="hidden md:block w-24 h-24 shrink-0">
            <img src="../assets/images/detail.jpg" alt="" class="w-full h-full object-cover">
        </div>
    </div>

    <?php if ($error): ?>
        <div class="border border-[#A3492D] bg-[#FAF7F2] text-[#A3492D] text-[13px] px-4 py-3 mb-6"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="border border-[#DFD5C4] bg-[#FAF7F2] text-[#2C2620] text-[13px] px-4 py-3 mb-6"><?= $success ?></div>
    <?php endif; ?>

    <div id="previewGizi" class="bg-white p-6 mb-6 shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px] <?= $success ? 'hidden' : '' ?>">
        <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-3">Live Preview Gizi per Porsi</span>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="giziCards">
            <div class="bg-white p-4 text-center shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]" style="border-top: 3px solid #D9733E;">
                <p class="font-serif text-2xl text-[#A3492D]" id="previewKalori">0</p>
                <p class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154] mt-1">Kalori (kkal)</p>
            </div>
            <div class="bg-white p-4 text-center shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]" style="border-top: 3px solid #A3492D;">
                <p class="font-serif text-2xl text-[#A3492D]" id="previewProtein">0</p>
                <p class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154] mt-1">Protein (g)</p>
            </div>
            <div class="bg-white p-4 text-center shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]" style="border-top: 3px solid #B5A642;">
                <p class="font-serif text-2xl text-[#A3492D]" id="previewKarbo">0</p>
                <p class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154] mt-1">Karbohidrat (g)</p>
            </div>
            <div class="bg-white p-4 text-center shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]" style="border-top: 3px solid #6B8F71;">
                <p class="font-serif text-2xl text-[#A3492D]" id="previewLemak">0</p>
                <p class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154] mt-1">Lemak (g)</p>
            </div>
        </div>
    </div>

    <form method="POST" action="" class="bg-white p-6 shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px] <?= $success ? 'hidden' : '' ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
            <div>
                <label for="judul" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Judul Resep</label>
                <input type="text" id="judul" name="judul" required
                       value="<?= htmlspecialchars($resep_sumber['judul'] . ' (Modifikasi)') ?>"
                       class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
            </div>
            <div>
                <label for="id_kategori" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Kategori</label>
                <select id="id_kategori" name="id_kategori"
                        class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori_list as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $resep_sumber['id_kategori'] == $k['id'] ? 'selected' : '' ?>><?= htmlspecialchars($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="jumlah_porsi" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Jumlah Porsi</label>
                <input type="number" id="jumlah_porsi" name="jumlah_porsi" required min="1"
                        value="1"
                       class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
            </div>
        </div>

        <div class="mb-6">
            <label for="deskripsi" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="2"
                      class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all"><?= htmlspecialchars($resep_sumber['deskripsi'] ?? '') ?></textarea>
        </div>

        <div class="mb-6">
            <div class="flex justify-between items-center mb-3">
                <span class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154]">Bahan-Bahan</span>
                <button type="button" id="tambahBahan" class="py-1.5 px-3 border border-[#D1C4B0] bg-white text-[11px] tracking-[0.1em] uppercase text-[#2C2620] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all">
                    + Tambah Bahan
                </button>
            </div>

            <div id="daftarBahan">
                <?php if (!empty($bahan_sumber)): ?>
                    <?php foreach ($bahan_sumber as $br):
                        $punya = in_array((int)$br['id_bahan'], $bahan_user);
                    ?>
                    <div class="bahan-row grid grid-cols-1 md:grid-cols-3 gap-3 mb-3 p-3 border border-[#DFD5C4] <?= $punya ? 'border-l-[3px] border-l-[#A3492D]' : '' ?>">
                        <div>
                            <?php if ($punya): ?>
                                <span class="text-[10px] tracking-[0.1em] uppercase text-[#A3492D] block mb-1">Bahan tersedia</span>
                            <?php endif; ?>
                            <label class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154] block mb-1">Nama Bahan</label>
                            <select name="id_bahan[]" required class="bahan-select w-full px-3 py-2 bg-white border border-[#D1C4B0] text-[13px] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
                                <option value="">-- Pilih Bahan --</option>
                                <?php foreach ($bahan_list as $b): ?>
                                    <option value="<?= $b['id'] ?>"
                                            data-kalori="<?= $b['kalori_per_100g'] ?>"
                                            data-protein="<?= $b['protein_per_100g'] ?>"
                                            data-karbo="<?= $b['karbohidrat_per_100g'] ?>"
                                            data-lemak="<?= $b['lemak_per_100g'] ?>"
                                            <?= $br['id_bahan'] == $b['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($b['nama_bahan']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] tracking-[0.1em] uppercase text-[#6B6154] block mb-1">Jumlah (gram)</label>
                            <input type="number" name="jumlah_gram[]" required min="0" step="0.01"
                                   value="<?= $br['jumlah_gram'] ?>"
                                   class="w-full px-3 py-2 bg-white border border-[#D1C4B0] text-[13px] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all gram-input">
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="hapusBahan py-2 px-3 border border-[#D1C4B0] bg-white text-[11px] tracking-[0.1em] uppercase text-[#6B6154] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all">Hapus</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
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
                                   class="w-full px-3 py-2 bg-white border border-[#D1C4B0] text-[13px] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all gram-input" placeholder="100">
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="hapusBahan py-2 px-3 border border-[#D1C4B0] bg-white text-[11px] tracking-[0.1em] uppercase text-[#6B6154] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all">Hapus</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <p class="text-[10px] text-[#6B6154] mt-1">Klik dropdown dan ketik nama bahan untuk mencari</p>
        </div>

        <div class="mb-6">
            <label for="langkah_memasak" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Langkah Memasak</label>
            <textarea id="langkah_memasak" name="langkah_memasak" rows="6" required
                      class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all"><?= htmlspecialchars($resep_sumber['langkah_memasak']) ?></textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="py-2.5 px-6 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all">Simpan Sebagai Resep Baru</button>
            <a href="../pages/rekomendasi.php" class="py-2.5 px-6 border border-[#D1C4B0] bg-white text-[13px] tracking-[0.1em] uppercase text-[#4A4438] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all no-underline">Batal</a>
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
                hitungGizi();
            } else {
                alert('Minimal harus ada 1 bahan!');
            }
        });
        newRow.querySelector('.bahan-select').addEventListener('change', hitungGizi);
        newRow.querySelector('.gram-input').addEventListener('input', hitungGizi);
    });

    document.querySelectorAll('.hapusBahan').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const container = document.getElementById('daftarBahan');
            if (container.querySelectorAll('.bahan-row').length > 1) {
                this.closest('.bahan-row').remove();
                hitungGizi();
            } else {
                alert('Minimal harus ada 1 bahan!');
            }
        });
    });

    function hitungGizi() {
        const rows = document.querySelectorAll('.bahan-row');
        const porsi = parseInt(document.getElementById('jumlah_porsi').value) || 1;
        let totalKalori = 0, totalProtein = 0, totalKarbo = 0, totalLemak = 0;

        rows.forEach(function(row) {
            const select = row.querySelector('.bahan-select');
            const gram = parseFloat(row.querySelector('.gram-input').value) || 0;
            const option = select.options[select.selectedIndex];

            if (option && option.value && gram > 0) {
                const kalPer100 = parseFloat(option.dataset.kalori) || 0;
                const protPer100 = parseFloat(option.dataset.protein) || 0;
                const karboPer100 = parseFloat(option.dataset.karbo) || 0;
                const lemakPer100 = parseFloat(option.dataset.lemak) || 0;

                totalKalori += (gram / 100) * kalPer100;
                totalProtein += (gram / 100) * protPer100;
                totalKarbo += (gram / 100) * karboPer100;
                totalLemak += (gram / 100) * lemakPer100;
            }
        });

        document.getElementById('previewKalori').textContent = porsi > 0 ? (totalKalori / porsi).toFixed(2) : '0';
        document.getElementById('previewProtein').textContent = porsi > 0 ? (totalProtein / porsi).toFixed(2) : '0';
        document.getElementById('previewKarbo').textContent = porsi > 0 ? (totalKarbo / porsi).toFixed(2) : '0';
        document.getElementById('previewLemak').textContent = porsi > 0 ? (totalLemak / porsi).toFixed(2) : '0';
    }

    document.querySelectorAll('.bahan-select').forEach(function(el) {
        el.addEventListener('change', hitungGizi);
    });
    document.querySelectorAll('.gram-input').forEach(function(el) {
        el.addEventListener('input', hitungGizi);
    });
    document.getElementById('jumlah_porsi').addEventListener('input', hitungGizi);

    hitungGizi();
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

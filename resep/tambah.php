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
$bahan_json = json_encode(array_map(function($b) {
    return ['id' => (int)$b['id'], 'nama' => $b['nama_bahan']];
}, $bahan_list));

$satuan_result = mysqli_query($koneksi, "SELECT id_bahan, satuan, gram_per_satuan FROM satuan_konversi");
$satuan_rows = mysqli_fetch_all($satuan_result, MYSQLI_ASSOC);
$satuan_data = [];
foreach ($satuan_rows as $s) {
    $satuan_data[$s['id_bahan']][] = ['satuan' => $s['satuan'], 'gram' => (float)$s['gram_per_satuan']];
}
$satuan_json = json_encode($satuan_data);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $id_kategori = !empty($_POST['id_kategori']) ? (int)$_POST['id_kategori'] : null;
    $jumlah_porsi = (int)$_POST['jumlah_porsi'];
    $langkah_memasak = trim($_POST['langkah_memasak'] ?? '');
    $id_bahan = $_POST['id_bahan'] ?? [];
    $jumlah_gram = $_POST['jumlah_gram'] ?? [];
    $jumlah_asli = $_POST['jumlah_asli'] ?? [];
    $satuan = $_POST['satuan'] ?? [];

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

            $stmt_bahan = mysqli_prepare($koneksi, "INSERT INTO resep_pribadi_bahan (id_resep_pribadi, id_bahan, jumlah_gram, jumlah_asli, satuan) VALUES (?, ?, ?, ?, ?)");

            $sukses_bahan = true;
            for ($i = 0; $i < count($id_bahan); $i++) {
                if (!empty($id_bahan[$i]) && !empty($jumlah_gram[$i]) && $jumlah_gram[$i] > 0) {
                    $gram = (float)$jumlah_gram[$i];
                    $asli = !empty($jumlah_asli[$i]) ? (float)$jumlah_asli[$i] : null;
                    $sat = !empty($satuan[$i]) ? $satuan[$i] : null;
                    if ($asli !== null && $sat === 'gram') $asli = null;
                    mysqli_stmt_bind_param($stmt_bahan, 'iidds', $id_resep, $id_bahan[$i], $gram, $asli, $sat);
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
    <link rel="icon" type="image/png" href="../assets/images/favicon.png">
    <title>Tambah Resep — Rasa dan Gizi</title>
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
        <script>sessionStorage.removeItem('tambahResepDraft');</script>
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
                <div class="bahan-row grid grid-cols-2 md:grid-cols-5 gap-2 mb-3 p-3 bg-[#F5F0E8] rounded-[2px] items-end">
                    <div class="col-span-2 md:col-span-1">
                        <label class="text-[10px] tracking-[0.1em] uppercase text-[#6B6154] block mb-1">Nama Bahan</label>
                        <div class="autocomplete-wrapper relative">
                            <input type="text" class="bahan-search w-full px-2 py-2 bg-white border border-[#D1C4B0] text-[12px] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all" placeholder="Cari bahan..." autocomplete="off">
                            <input type="hidden" name="id_bahan[]" class="bahan-id" value="">
                            <ul class="bahan-dropdown absolute left-0 right-0 z-50 bg-white border border-[#D1C4B0] max-h-40 overflow-y-auto hidden shadow-md" style="top: calc(100% + 1px);"></ul>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] tracking-[0.1em] uppercase text-[#6B6154] block mb-1">Satuan</label>
                        <select name="satuan[]" class="bahan-satuan w-full px-2 py-2 bg-white border border-[#D1C4B0] text-[12px] focus:outline-none focus:border-[#A3492D] transition-all">
                            <option value="gram">gram</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] tracking-[0.1em] uppercase text-[#6B6154] block mb-1">Jumlah</label>
                        <input type="number" name="jumlah_asli[]" class="bahan-jumlah w-full px-2 py-2 bg-white border border-[#D1C4B0] text-[12px] focus:outline-none focus:border-[#A3492D] transition-all" step="any" min="0" placeholder="—">
                    </div>
                    <div>
                        <label class="text-[10px] tracking-[0.1em] uppercase text-[#6B6154] block mb-1">Gram</label>
                        <input type="number" name="jumlah_gram[]" required class="bahan-gram w-full px-2 py-2 bg-white border border-[#D1C4B0] text-[12px] focus:outline-none focus:border-[#A3492D] transition-all" step="0.01" min="0" placeholder="0">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="hapusBahan w-full py-2 px-2 border border-[#D1C4B0] bg-white text-[10px] tracking-[0.1em] uppercase text-[#6B6154] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all">Hapus</button>
                    </div>
                </div>
            </div>
            <p class="text-[10px] text-[#6B6154] mt-1">Pilih bahan, atur satuan (opsional) dan gram. Satuan hanya alat bantu — gram tetap patokan utama.</p>
        </div>

        <div class="mb-6">
            <label for="langkah_memasak" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Langkah Memasak</label>
            <textarea id="langkah_memasak" name="langkah_memasak" rows="6" required
                      class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all"
                      placeholder="Tulis langkah-langkah memasak di sini..."></textarea>
        </div>

        <div class="flex flex-wrap gap-2 md:gap-3">
            <button type="submit" class="w-full sm:w-auto py-2 px-4 sm:py-2.5 sm:px-6 border border-[#A3492D] bg-[#A3492D] text-white text-[12px] sm:text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all">Simpan Resep</button>
            <a href="index.php" onclick="sessionStorage.removeItem('tambahResepDraft')" class="w-full sm:w-auto text-center py-2 px-4 sm:py-2.5 sm:px-6 border border-[#D1C4B0] bg-white text-[12px] sm:text-[13px] tracking-[0.1em] uppercase text-[#4A4438] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all no-underline">Batal</a>
        </div>
    </form>
</div>

<script id="bahanData" type="application/json"><?= $bahan_json ?></script>
<script id="satuanData" type="application/json"><?= $satuan_json ?></script>

<script>
var bahanData = JSON.parse(document.getElementById('bahanData').textContent);
var satuanData = JSON.parse(document.getElementById('satuanData').textContent);

function hitungKonversi(row) {
    var hidden = row.querySelector('.bahan-id');
    var satuan = row.querySelector('.bahan-satuan');
    var jumlah = row.querySelector('.bahan-jumlah');
    var gram = row.querySelector('.bahan-gram');
    var id = parseInt(hidden.value);
    var sat = satuan.value;
    var jml = parseFloat(jumlah.value) || 0;
    var g = parseFloat(gram.value) || 0;

    if (!id) return;
    var p = getKonversi(id, sat);
    if (!p || p <= 0) return;

    if (row._updating) return;
    row._updating = true;

    if (row._lastEdit === 'jumlah') {
        gram.value = (jml * p).toFixed(2);
    } else if (row._lastEdit === 'gram') {
        jumlah.value = (g / p).toFixed(2);
    }

    row._updating = false;
}

function getKonversi(id, satuan) {
    if (satuan === 'gram') return 1;
    var list = satuanData[id];
    if (!list) return null;
    for (var i = 0; i < list.length; i++) {
        if (list[i].satuan === satuan) return list[i].gram;
    }
    return null;
}

// --- localStorage persistence ---
var STORAGE_KEY = 'tambahResepDraft';

function saveFormState() {
    var data = {
        form: {
            judul: document.getElementById('judul').value,
            deskripsi: document.getElementById('deskripsi').value,
            id_kategori: document.getElementById('id_kategori').value,
            jumlah_porsi: document.getElementById('jumlah_porsi').value,
            langkah_memasak: document.getElementById('langkah_memasak').value
        },
        bahan: []
    };
    document.querySelectorAll('.bahan-row').forEach(function(r) {
        data.bahan.push({
            nama: r.querySelector('.bahan-search').value,
            id: r.querySelector('.bahan-id').value,
            satuan: r.querySelector('.bahan-satuan').value,
            jumlah: r.querySelector('.bahan-jumlah').value,
            gram: r.querySelector('.bahan-gram').value
        });
    });
    try { sessionStorage.setItem(STORAGE_KEY, JSON.stringify(data)); } catch(e) {}
}

var _saveTimer = null;
function scheduleSave() {
    if (_saveTimer) clearTimeout(_saveTimer);
    _saveTimer = setTimeout(saveFormState, 300);
}

function restoreFormState() {
    var raw = sessionStorage.getItem(STORAGE_KEY);
    if (!raw) return false;
    var data;
    try { data = JSON.parse(raw); } catch(e) { return false; }
    if (!data.bahan || data.bahan.length === 0) return false;

    if (data.form) {
        document.getElementById('judul').value = data.form.judul || '';
        document.getElementById('deskripsi').value = data.form.deskripsi || '';
        if (data.form.id_kategori) document.getElementById('id_kategori').value = data.form.id_kategori;
        document.getElementById('jumlah_porsi').value = data.form.jumlah_porsi || '1';
        document.getElementById('langkah_memasak').value = data.form.langkah_memasak || '';
    }

    var container = document.getElementById('daftarBahan');
    var tempHTML = container.querySelector('.bahan-row').outerHTML;
    container.innerHTML = '';

    data.bahan.forEach(function(b) {
        var div = document.createElement('div');
        div.innerHTML = tempHTML;
        var row = div.firstElementChild;
        row.querySelector('.bahan-search').value = b.nama || '';
        row.querySelector('.bahan-id').value = b.id || '';
        row.querySelector('.bahan-satuan').innerHTML = '<option value="gram">gram</option>';
        row.querySelector('.bahan-satuan').value = b.satuan || 'gram';
        row.querySelector('.bahan-jumlah').value = b.jumlah || '';
        row.querySelector('.bahan-jumlah').disabled = !b.satuan || b.satuan === 'gram';
        row.querySelector('.bahan-gram').value = b.gram || '';
        container.appendChild(row);
    });

    document.querySelectorAll('.bahan-row').forEach(initRow);
    return true;
}

function initSatuan(wrapper) {
    var row = wrapper.closest('.bahan-row');
    var hidden = wrapper.querySelector('.bahan-id');
    var satuan = row.querySelector('.bahan-satuan');
    var jumlah = row.querySelector('.bahan-jumlah');
    var gram = row.querySelector('.bahan-gram');

    function updateSatuanOptions() {
        var id = parseInt(hidden.value);
        var current = satuan.value;
        satuan.innerHTML = '<option value="gram">gram</option>';
        if (id && satuanData[id]) {
            var list = satuanData[id];
            for (var i = 0; i < list.length; i++) {
                var opt = document.createElement('option');
                opt.value = list[i].satuan;
                opt.textContent = list[i].satuan;
                if (list[i].satuan === current) opt.selected = true;
                satuan.appendChild(opt);
            }
        }
        satuan.value = current;
        if (current !== 'gram' && !getKonversi(id, current)) {
            satuan.value = 'gram';
        }
        toggleJumlahVisibility();
    }

    function toggleJumlahVisibility() {
        if (satuan.value === 'gram') {
            jumlah.disabled = true;
            jumlah.placeholder = '—';
            jumlah.value = '';
        } else {
            jumlah.disabled = false;
            jumlah.placeholder = '0';
        }
    }

    satuan.addEventListener('change', function() {
        toggleJumlahVisibility();
        hitungKonversi(wrapper.closest('.bahan-row'));
        scheduleSave();
    });

    jumlah.addEventListener('input', function() {
        wrapper.closest('.bahan-row')._lastEdit = 'jumlah';
        hitungKonversi(wrapper.closest('.bahan-row'));
        scheduleSave();
    });

    gram.addEventListener('input', function() {
        wrapper.closest('.bahan-row')._lastEdit = 'gram';
        hitungKonversi(wrapper.closest('.bahan-row'));
        scheduleSave();
    });

    updateSatuanOptions();
}

function initAutocomplete(wrapper) {
    var input = wrapper.querySelector('.bahan-search');
    var hidden = wrapper.querySelector('.bahan-id');
    var dropdown = wrapper.querySelector('.bahan-dropdown');

    function filterBahan() {
        var keyword = input.value.toLowerCase().trim();
        dropdown.innerHTML = '';

        if (keyword === '') {
            dropdown.classList.add('hidden');
            if (!wrapper._selecting) hidden.value = '';
            return;
        }

        var prefixMatches = [];
        var includesMatches = [];

        for (var i = 0; i < bahanData.length; i++) {
            var nama = bahanData[i].nama.toLowerCase();
            if (nama.startsWith(keyword)) {
                prefixMatches.push(bahanData[i]);
            } else if (nama.includes(keyword)) {
                includesMatches.push(bahanData[i]);
            }
        }

        var matches = prefixMatches.concat(includesMatches);

        if (matches.length === 0) {
            dropdown.classList.add('hidden');
            return;
        }

        if (matches.length > 20) matches = matches.slice(0, 20);

        for (var j = 0; j < matches.length; j++) {
            var li = document.createElement('li');
            li.className = 'px-3 py-2 text-[13px] cursor-pointer hover:bg-[#F5F0E8] transition-colors border-b border-[#DFD5C4]/50';
            li.textContent = matches[j].nama;
            li.dataset.id = matches[j].id;
            li.addEventListener('mousedown', function(e) {
                e.preventDefault();
                var row = wrapper.closest('.bahan-row');
                input.value = this.textContent;
                hidden.value = this.dataset.id;
                dropdown.classList.add('hidden');
                initSatuan(wrapper);
                scheduleSave();
            });
            dropdown.appendChild(li);
        }

        dropdown.classList.remove('hidden');
    }

    input.addEventListener('input', filterBahan);

    input.addEventListener('blur', function() {
        setTimeout(function() {
            dropdown.classList.add('hidden');
        }, 200);
    });

    input.addEventListener('focus', function() {
        if (dropdown.children.length > 0) dropdown.classList.remove('hidden');
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            dropdown.classList.add('hidden');
        } else if (e.key === 'Enter') {
            if (!dropdown.classList.contains('hidden') && dropdown.children.length > 0) {
                e.preventDefault();
                var first = dropdown.children[0];
                var row = wrapper.closest('.bahan-row');
                input.value = first.textContent;
                hidden.value = first.dataset.id;
                dropdown.classList.add('hidden');
                initSatuan(wrapper);
                scheduleSave();
            }
        }
    });

    if (hidden.value) {
        for (var k = 0; k < bahanData.length; k++) {
            if (bahanData[k].id == hidden.value) {
                input.value = bahanData[k].nama;
                break;
            }
        }
        initSatuan(wrapper);
    }
}

function initRow(row) {
    initAutocomplete(row.querySelector('.autocomplete-wrapper'));
    var wrapper = row.querySelector('.autocomplete-wrapper');
    var hidden = wrapper.querySelector('.bahan-id');
    if (hidden && hidden.value) {
        initSatuan(wrapper);
    }
}

if (!restoreFormState()) {
    document.querySelectorAll('.bahan-row').forEach(initRow);
}

document.getElementById('tambahBahan').addEventListener('click', function() {
    var container = document.getElementById('daftarBahan');
    var firstRow = container.querySelector('.bahan-row');
    var newRow = firstRow.cloneNode(true);
    newRow.querySelector('.bahan-search').value = '';
    newRow.querySelector('.bahan-id').value = '';
    newRow.querySelector('.bahan-dropdown').innerHTML = '';
    newRow.querySelector('.bahan-dropdown').classList.add('hidden');
    newRow.querySelector('.bahan-gram').value = '';
    newRow.querySelector('.bahan-jumlah').value = '';
    newRow.querySelector('.bahan-jumlah').disabled = true;
    newRow.querySelector('.bahan-satuan').innerHTML = '<option value="gram">gram</option>';
    newRow.querySelector('.bahan-satuan').value = 'gram';
    container.appendChild(newRow);
    initAutocomplete(newRow.querySelector('.autocomplete-wrapper'));
    scheduleSave();
    newRow.querySelector('.hapusBahan').addEventListener('click', function() {
        if (container.querySelectorAll('.bahan-row').length > 1) {
            newRow.remove();
            scheduleSave();
        } else {
            alert('Minimal harus ada 1 bahan!');
        }
    });
});

document.querySelectorAll('.hapusBahan').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var container = document.getElementById('daftarBahan');
        if (container.querySelectorAll('.bahan-row').length > 1) {
            this.closest('.bahan-row').remove();
            scheduleSave();
        } else {
            alert('Minimal harus ada 1 bahan!');
        }
    });
});

// --- Save triggers for form fields ---
document.getElementById('judul').addEventListener('input', scheduleSave);
document.getElementById('deskripsi').addEventListener('input', scheduleSave);
document.getElementById('id_kategori').addEventListener('change', scheduleSave);
document.getElementById('jumlah_porsi').addEventListener('input', scheduleSave);
document.getElementById('langkah_memasak').addEventListener('input', scheduleSave);

// --- Clear draft on form submit ---
document.querySelector('form').addEventListener('submit', function() {
    sessionStorage.removeItem(STORAGE_KEY);
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

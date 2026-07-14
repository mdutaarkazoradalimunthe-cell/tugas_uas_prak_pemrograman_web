<?php
require_once '../config/cek_login.php';
require_once '../config/koneksi.php';

$semua_bahan = [];
$bahan_stmt = mysqli_prepare($koneksi, "SELECT id, nama_bahan FROM bahan_makanan ORDER BY nama_bahan ASC");
mysqli_stmt_execute($bahan_stmt);
$bahan_result = mysqli_stmt_get_result($bahan_stmt);
$semua_bahan = mysqli_fetch_all($bahan_result, MYSQLI_ASSOC);
mysqli_stmt_close($bahan_stmt);

$bahan_lookup = [];
foreach ($semua_bahan as $b) {
    $bahan_lookup[$b['id']] = $b['nama_bahan'];
}

function cari_resep_cocok($selected_bahan, $koneksi, $bahan_lookup) {
    $results = [];
    if (empty($selected_bahan)) return $results;

    $resep_stmt = mysqli_prepare($koneksi, "
        SELECT r.id, r.judul, r.deskripsi, r.jumlah_porsi, r.created_at, r.id_user,
               kr.nama_kategori
        FROM resep r
        LEFT JOIN kategori_resep kr ON r.id_kategori = kr.id
        ORDER BY r.judul ASC
    ");
    mysqli_stmt_execute($resep_stmt);
    $resep_result = mysqli_stmt_get_result($resep_stmt);
    $all_resep = mysqli_fetch_all($resep_result, MYSQLI_ASSOC);
    mysqli_stmt_close($resep_stmt);

    $rb_stmt = mysqli_prepare($koneksi, "SELECT id_resep, id_bahan FROM resep_bahan");
    mysqli_stmt_execute($rb_stmt);
    $rb_result = mysqli_stmt_get_result($rb_stmt);
    $rb_rows = mysqli_fetch_all($rb_result, MYSQLI_ASSOC);
    mysqli_stmt_close($rb_stmt);

    $bahan_per_resep = [];
    foreach ($rb_rows as $rb) {
        $bahan_per_resep[$rb['id_resep']][] = (int)$rb['id_bahan'];
    }

    foreach ($all_resep as $resep) {
        $id_resep = (int)$resep['id'];
        $bahan_resep = $bahan_per_resep[$id_resep] ?? [];
        $total_bahan = count($bahan_resep);
        if ($total_bahan === 0) continue;

        $matched_ids = array_intersect($bahan_resep, $selected_bahan);
        $matched = count($matched_ids);
        if ($matched === 0) continue;

        $missing_ids = array_diff($bahan_resep, $selected_bahan);
        $matched_names = [];
        foreach ($matched_ids as $id) {
            $matched_names[] = $bahan_lookup[$id] ?? '';
        }
        $missing_names = [];
        foreach ($missing_ids as $id) {
            $missing_names[] = $bahan_lookup[$id] ?? '';
        }

        $persen = round(($matched / $total_bahan) * 100);

        $results[] = [
            'id' => $resep['id'],
            'judul' => $resep['judul'],
            'deskripsi' => $resep['deskripsi'],
            'jumlah_porsi' => $resep['jumlah_porsi'],
            'created_at' => $resep['created_at'],
            'nama_kategori' => $resep['nama_kategori'],
            'id_user' => $resep['id_user'],
            'match_persen' => $persen,
            'matched_bahan' => $matched,
            'total_bahan' => $total_bahan,
            'matched_names' => $matched_names,
            'missing_names' => $missing_names,
        ];
    }

    usort($results, function($a, $b) {
        return $b['match_persen'] <=> $a['match_persen'];
    });

    return $results;
}

$results = [];
$selected_bahan = [];
$error = '';
$bahan_str = '';

if (isset($_REQUEST['json']) && $_REQUEST['json'] == 1) {
    header('Content-Type: application/json; charset=utf-8');

    $raw = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $raw = $_POST['bahan'] ?? [];
    } else {
        $bahan_str_get = $_GET['bahan'] ?? '';
        $raw = $bahan_str_get !== '' ? explode(',', $bahan_str_get) : [];
    }

    $selected_bahan = array_map('intval', (array)$raw);
    $selected_bahan = array_values(array_filter($selected_bahan, function($id) { return $id > 0; }));
    $bahan_str = implode(',', $selected_bahan);

    $results = cari_resep_cocok($selected_bahan, $koneksi, $bahan_lookup);

    echo json_encode([
        'success' => true,
        'results' => $results,
        'count' => count($results),
        'bahan_str' => $bahan_str,
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_bahan = $_POST['bahan'] ?? [];
    $selected_bahan = array_map('intval', $selected_bahan);
    $selected_bahan = array_values(array_filter($selected_bahan, function($id) { return $id > 0; }));
    $bahan_str = implode(',', $selected_bahan);

    if (empty($selected_bahan)) {
        $error = 'Pilih minimal 1 bahan terlebih dahulu.';
    } else {
        $results = cari_resep_cocok($selected_bahan, $koneksi, $bahan_lookup);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Berdasarkan Bahan â€” Rasa dan Gizi</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FAF7F2] text-[#2C2620] font-sans antialiased min-h-screen">

<?php $base_path = '../'; $active_page = 'cari_bahan'; require __DIR__ . '/../includes/partials/navbar.php'; ?>

<div class="max-w-6xl mx-auto px-6 py-8">
    <div class="flex items-start gap-4 md:gap-6 mb-6 md:mb-8">
        <div class="flex-1">
            <span class="text-[#A3492D] text-[11px] md:text-[12px] tracking-[0.15em] uppercase block mb-1">Cari Bahan</span>
            <h1 class="font-serif text-2xl sm:text-3xl text-[#2C2620] font-normal mb-2">Cari Resep dari Bahan yang Tersedia</h1>
            <p class="text-[13px] md:text-[14px] text-[#4A4438]">Pilih bahan yang kamu punya, lalu sistem akan mencari resep yang paling cocok!</p>
        </div>
        <div class="hidden md:block w-20 h-20 md:w-28 md:h-28 shrink-0">
            <img src="../assets/images/cari-bahan.jpg" alt="" class="w-full h-full object-cover">
        </div>
    </div>

    <form method="POST" action="" class="bg-white p-6 mb-6 shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]">
        <div class="mb-4">
            <label for="filterBahan" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Cari Bahan</label>
            <input type="search" id="filterBahan" value=""
                   class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all"
                   placeholder="Ketik nama bahan... (contoh: telur, nasi, ayam)" autocomplete="off">
            <div id="filterInfo" class="text-[12px] text-[#6B6154] mt-2 hidden"></div>
        </div>

        <div class="flex items-center gap-3 mb-3">
            <span class="text-[14px] text-[#4A4438]">Pilihan Bahan:</span>
            <button type="button" id="selectAll" class="text-[#A3492D] text-[11px] tracking-[0.1em] uppercase hover:opacity-70">Pilih Semua</button>
            <button type="button" id="deselectAll" class="text-[#6B6154] text-[11px] tracking-[0.1em] uppercase hover:opacity-70">Hapus Semua</button>
            <span class="text-[10px] text-[#6B6154]">(centang bahan yang kamu punya)</span>
        </div>

        <div id="checklistContainer" class="max-h-96 overflow-y-auto bg-white p-3 mb-4 shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]">
            <?php if (empty($semua_bahan)): ?>
                <p class="text-[#6B6154] text-center py-4">Tidak ada data bahan.</p>
            <?php else: ?>
                <?php foreach ($semua_bahan as $bahan): ?>
                <div class="bahan-item flex items-center gap-2 py-0.5">
                    <input type="checkbox" name="bahan[]" value="<?= $bahan['id'] ?>" id="bahan_<?= $bahan['id'] ?>"
                           class="appearance-none w-4 h-4 border border-[#D1C4B0] rounded bg-white checked:bg-[#A3492D] checked:border-[#A3492D] focus:ring-2 focus:ring-[#A3492D] focus:ring-opacity-30 transition-all cursor-pointer"
                           <?= in_array((int)$bahan['id'], $selected_bahan) ? 'checked' : '' ?>>
                    <label for="bahan_<?= $bahan['id'] ?>" class="text-[12px] sm:text-[14px] text-[#4A4438] cursor-pointer hover:text-[#2C2620]"><?= htmlspecialchars($bahan['nama_bahan']) ?></label>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="selectedSummary" class="bg-[#F5F0E8] p-4 mb-4 <?= empty($selected_bahan) ? 'hidden' : '' ?>">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-[13px] text-[#2C2620]">Bahan yang dipilih:</span>
                <span id="selectedCount" class="font-medium text-[#A3492D]"><?= count($selected_bahan) ?></span>
                <span class="text-[14px] text-[#4A4438]">bahan</span>
            </div>
            <div id="selectedList" class="flex flex-wrap gap-1.5">
                <?php if (!empty($selected_bahan)): ?>
                    <?php foreach ($selected_bahan as $id): ?>
                        <?php $nama = $bahan_lookup[$id] ?? ''; ?>
                        <?php if ($nama): ?>
                        <span class="inline-block text-[#A3492D] text-[11px] tracking-[0.1em] uppercase border border-[#A3492D] px-2 py-0.5">
                            <?= htmlspecialchars($nama) ?>
                        </span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <button type="submit"
                class="w-full sm:w-auto py-2 px-4 sm:py-2.5 sm:px-6 border border-[#A3492D] bg-[#A3492D] text-white text-[12px] sm:text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all">
            Cari Resep
        </button>
    </form>

    <?php if ($error): ?>
        <div class="border border-[#A3492D] bg-[#FAF7F2] text-[#A3492D] text-[13px] px-4 py-3 mb-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div id="resultsContainer">
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)): ?>
            <?php if (empty($results)): ?>
                <div class="bg-[#E4DBC8] p-16 text-center">
                    <p class="text-[#4A4438] text-base mb-2">Tidak ada resep yang cocok dengan bahan yang dipilih.</p>
                    <p class="text-[14px] text-[#4A4438] mb-5">Coba pilih bahan lain atau tambah resep baru.</p>
                    <a href="../resep/tambah.php" class="py-2.5 px-5 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all no-underline inline-block">Tambah Resep Baru</a>
                </div>
            <?php else: ?>
                <div class="bg-[#F5F0E8] px-4 py-3 mb-6 text-[14px] text-[#4A4438]">
                    Menampilkan <strong><?= count($results) ?></strong> resep yang cocok &mdash; diurutkan dari persentase kecocokan tertinggi
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($results as $r):
                        $match = $r['match_persen'];
                        if ($match >= 75) $badge_border = 'border-[#A3492D] text-[#A3492D]';
                        elseif ($match >= 50) $badge_border = 'border-[#6B6154] text-[#6B6154]';
                        elseif ($match >= 25) $badge_border = 'border-[#8A7B63] text-[#6B6154]';
                        else $badge_border = 'border-[#DFD5C4] text-[#6B6154]';
                    ?>
                        <div class="bg-white shadow-[0_6px_20px_rgba(0,0,0,0.14)] flex flex-col rounded-[2px]" style="border-top: 2px solid #A3492D;">
                            <div class="p-5 flex-1">
                                <div class="flex justify-between items-start gap-2 mb-2">
                                    <h2 class="font-serif text-xl text-[#2C2620] font-normal"><?= htmlspecialchars($r['judul']) ?></h2>
                                    <span class="inline-block whitespace-nowrap border <?= $badge_border ?> text-[11px] tracking-[0.1em] uppercase px-2 py-0.5 shrink-0">
                                        <?= $match ?>%
                                    </span>
                                </div>

                                <?php if ($r['nama_kategori']): ?>
                                    <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-3"><?= htmlspecialchars($r['nama_kategori']) ?></span>
                                <?php endif; ?>

                                <p class="text-[14px] text-[#4A4438] mb-2 line-clamp-2"><?= htmlspecialchars(substr($r['deskripsi'] ?? '', 0, 100)) ?></p>
                                <p class="text-[11px] text-[#6B6154] mb-3">Porsi: <?= $r['jumlah_porsi'] ?> &middot; <?= date('d/m/Y', strtotime($r['created_at'])) ?></p>

                                <?php if (!empty($r['matched_names'])): ?>
                                <div class="text-[12px] text-[#A3492D] mb-1">&checkmark; <?= htmlspecialchars(implode(', ', $r['matched_names'])) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($r['missing_names'])): ?>
                                <div class="text-[12px] text-[#6B6154] mb-1">&times; <?= htmlspecialchars(implode(', ', $r['missing_names'])) ?></div>
                                <?php endif; ?>
                                <div class="text-[11px] text-[#6B6154]"><?= $r['matched_bahan'] ?> dari <?= $r['total_bahan'] ?> bahan cocok</div>
                            </div>
                            <div class="border-t border-[#E4DBC8] px-5 py-3">
                                <a href="../resep/detail.php?id=<?= $r['id'] ?>&bahan=<?= $bahan_str ?>"
                                   class="block w-full text-center py-2.5 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all no-underline">
                                    Lihat Resep
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php elseif ($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
            <div class="bg-[#E4DBC8] p-16 text-center">
                <p class="text-[#4A4438] text-base mb-2">Pilih bahan yang kamu punya di atas</p>
                <p class="text-[14px] text-[#4A4438]">Centang bahan-bahan yang tersedia di dapurmu, nanti hasil akan muncul otomatis!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
var filterInput = document.getElementById('filterBahan');
var searchTimeout;

filterInput.addEventListener('input', function() {
    var keyword = this.value.toLowerCase().trim();
    var items = document.querySelectorAll('#checklistContainer .bahan-item');
    var visibleCount = 0;
    var totalCount = items.length;
    items.forEach(function(item) {
        var label = item.querySelector('label').textContent.toLowerCase();
        if (keyword === '' || label.indexOf(keyword) !== -1) {
            item.style.display = '';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    var info = document.getElementById('filterInfo');
    if (keyword !== '') {
        info.textContent = visibleCount + ' dari ' + totalCount + ' bahan ditemukan untuk "' + keyword + '"';
        info.classList.remove('hidden');
    } else {
        info.classList.add('hidden');
    }

    scheduleSearch();
});

document.getElementById('selectAll').addEventListener('click', function() {
    var items = document.querySelectorAll('#checklistContainer .bahan-item');
    items.forEach(function(item) {
        if (item.style.display !== 'none') {
            item.querySelector('input[type="checkbox"]').checked = true;
        }
    });
    updateSelectedSummary();
    scheduleSearch();
});

document.getElementById('deselectAll').addEventListener('click', function() {
    var items = document.querySelectorAll('#checklistContainer .bahan-item');
    items.forEach(function(item) {
        item.querySelector('input[type="checkbox"]').checked = false;
    });
    updateSelectedSummary();
    scheduleSearch();
});

function scheduleSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        fetchRecipes();
    }, 200);
}

function fetchRecipes() {
    var checkedItems = document.querySelectorAll('#checklistContainer input[type="checkbox"]:checked');
    var bahanIds = [];
    checkedItems.forEach(function(cb) {
        bahanIds.push(cb.value);
    });

    var container = document.getElementById('resultsContainer');

    if (bahanIds.length === 0) {
        container.innerHTML = '<div class="bg-[#E4DBC8] p-16 text-center">' +
            '<p class="text-[#4A4438] text-base mb-2">Pilih bahan yang kamu punya di atas</p>' +
            '<p class="text-[14px] text-[#4A4438]">Centang bahan-bahan yang tersedia di dapurmu, nanti hasil akan muncul otomatis!</p>' +
            '</div>';
        return;
    }

    container.innerHTML = '<div class="text-center py-8 text-[#6B6154] text-[13px]">Mencari resep...</div>';

    var params = new URLSearchParams();
    params.set('json', '1');
    bahanIds.forEach(function(id) {
        params.append('bahan[]', id);
    });

    fetch('resep_by_bahan.php', {
        method: 'POST',
        body: params
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            renderResults(data);
        }
    })
    .catch(function() {
        container.innerHTML = '<div class="border border-[#A3492D] bg-[#FAF7F2] text-[#A3492D] text-[13px] px-4 py-3 rounded">Gagal mencari resep. Coba lagi.</div>';
    });
}

function renderResults(data) {
    var container = document.getElementById('resultsContainer');

    if (data.count === 0) {
        container.innerHTML = '<div class="bg-[#E4DBC8] p-16 text-center">' +
            '<p class="text-[#4A4438] text-base mb-2">Tidak ada resep yang cocok dengan bahan yang dipilih.</p>' +
            '<p class="text-[14px] text-[#4A4438] mb-5">Coba pilih bahan lain atau tambah resep baru.</p>' +
            '<a href="../resep/tambah.php" class="py-2.5 px-5 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all no-underline inline-block">Tambah Resep Baru</a>' +
            '</div>';
        return;
    }

    var html = '<div class="bg-[#F5F0E8] px-4 py-3 mb-6 text-[14px] text-[#4A4438]">' +
        'Menampilkan <strong>' + data.count + '</strong> resep yang cocok &mdash; diurutkan dari persentase kecocokan tertinggi' +
        '</div>';

    html += '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';

    data.results.forEach(function(r) {
        var badgeBorder = 'border-[#DFD5C4] text-[#6B6154]';
        if (r.match_persen >= 75) badgeBorder = 'border-[#A3492D] text-[#A3492D]';
        else if (r.match_persen >= 50) badgeBorder = 'border-[#6B6154] text-[#6B6154]';
        else if (r.match_persen >= 25) badgeBorder = 'border-[#8A7B63] text-[#6B6154]';

        html += '<div class="bg-white shadow-[0_6px_20px_rgba(0,0,0,0.14)] flex flex-col rounded-[2px]" style="border-top: 2px solid #A3492D;">' +
            '<div class="p-5 flex-1">' +
            '<div class="flex justify-between items-start gap-2 mb-2">' +
            '<h2 class="font-serif text-xl text-[#2C2620] font-normal">' + escapeHtml(r.judul) + '</h2>' +
            '<span class="inline-block whitespace-nowrap border ' + badgeBorder + ' text-[11px] tracking-[0.1em] uppercase px-2 py-0.5 shrink-0">' +
            r.match_persen + '%</span></div>';

        if (r.nama_kategori) {
            html += '<span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-3">' +
                escapeHtml(r.nama_kategori) + '</span>';
        }

        var deskripsi = (r.deskripsi || '').substring(0, 100);
        html += '<p class="text-[14px] text-[#4A4438] mb-2 line-clamp-2">' + escapeHtml(deskripsi) + '</p>' +
            '<p class="text-[11px] text-[#6B6154] mb-3">Porsi: ' + r.jumlah_porsi + ' &middot; ' + formatDate(r.created_at) + '</p>';

        if (r.matched_names && r.matched_names.length > 0) {
            html += '<div class="text-[12px] text-[#A3492D] mb-1">&checkmark; ' + escapeHtml(r.matched_names.join(', ')) + '</div>';
        }
        if (r.missing_names && r.missing_names.length > 0) {
            html += '<div class="text-[12px] text-[#6B6154] mb-1">&times; ' + escapeHtml(r.missing_names.join(', ')) + '</div>';
        }

        html += '<div class="text-[11px] text-[#6B6154]">' + r.matched_bahan + ' dari ' + r.total_bahan + ' bahan cocok</div>' +
            '</div>' +
            '<div class="border-t border-[#E4DBC8] px-5 py-3">' +
            '<a href="../resep/detail.php?id=' + r.id + '&bahan=' + data.bahan_str + '" ' +
            'class="block w-full text-center py-2.5 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all no-underline">' +
            'Lihat Resep</a></div></div>';
    });

    html += '</div>';
    container.innerHTML = html;
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    var d = new Date(dateStr);
    var day = String(d.getDate()).padStart(2, '0');
    var month = String(d.getMonth() + 1).padStart(2, '0');
    var year = d.getFullYear();
    return day + '/' + month + '/' + year;
}

function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function updateSelectedSummary() {
    var checkedItems = document.querySelectorAll('#checklistContainer input[type="checkbox"]:checked');
    var container = document.getElementById('selectedSummary');
    document.getElementById('selectedCount').textContent = checkedItems.length;

    if (checkedItems.length > 0) {
        container.classList.remove('hidden');
        var html = '';
        checkedItems.forEach(function(cb) {
            var label = document.querySelector('label[for="' + cb.id + '"]');
            if (label) {
                html += '<span data-id="' + cb.value + '" onclick="uncheckBahan(this)" ' +
                    'class="inline-block text-[#A3492D] text-[11px] tracking-[0.1em] uppercase border border-[#A3492D] px-2 py-0.5 cursor-pointer hover:bg-[#E4DBC8]">' +
                    escapeHtml(label.textContent) + ' &times;</span>';
            }
        });
        document.getElementById('selectedList').innerHTML = html;
    } else {
        container.classList.add('hidden');
    }
}

function uncheckBahan(el) {
    var id = el.getAttribute('data-id');
    document.getElementById('bahan_' + id).checked = false;
    updateSelectedSummary();
    scheduleSearch();
}

document.querySelectorAll('#checklistContainer input[type="checkbox"]').forEach(function(cb) {
    cb.addEventListener('change', function() {
        updateSelectedSummary();
        scheduleSearch();
    });
});

updateSelectedSummary();

if (document.querySelectorAll('#checklistContainer input[type="checkbox"]:checked').length > 0) {
    fetchRecipes();
}
</script>
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

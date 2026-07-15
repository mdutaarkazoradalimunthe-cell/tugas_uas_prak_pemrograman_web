<?php
require_once '../config/cek_login.php';
require_once '../config/koneksi.php';

if (isset($_GET['json']) && $_GET['json'] == 1) {
    header('Content-Type: application/json; charset=utf-8');

    $keyword = trim($_GET['cari'] ?? '');
    $page = max(1, (int)($_GET['halaman'] ?? 1));
    $limit = 50;
    $offset = ($page - 1) * $limit;
    $search_param = '%' . $keyword . '%';

    if (!empty($keyword)) {
        $count_stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) FROM bahan_makanan WHERE nama_bahan LIKE ?");
        mysqli_stmt_bind_param($count_stmt, 's', $search_param);
    } else {
        $count_stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) FROM bahan_makanan");
    }
    mysqli_stmt_execute($count_stmt);
    mysqli_stmt_bind_result($count_stmt, $total_data);
    mysqli_stmt_fetch($count_stmt);
    mysqli_stmt_close($count_stmt);

    $total_pages = ceil($total_data / $limit);

    if (!empty($keyword)) {
        $exact_param = $keyword;
        $starts_with_param = $keyword . '%';
        $data_stmt = mysqli_prepare($koneksi, "
            SELECT nama_bahan, ROUND((protein_per_100g * 4) + (karbohidrat_per_100g * 4) + (lemak_per_100g * 9), 2) AS kalori_per_100g, protein_per_100g, karbohidrat_per_100g, lemak_per_100g
            FROM bahan_makanan WHERE nama_bahan LIKE ?
            ORDER BY
                CASE
                    WHEN nama_bahan = ? THEN 0
                    WHEN nama_bahan LIKE ? THEN 1
                    ELSE 2
                END,
                nama_bahan ASC
            LIMIT ? OFFSET ?
        ");
        mysqli_stmt_bind_param($data_stmt, 'sssii', $search_param, $exact_param, $starts_with_param, $limit, $offset);
    } else {
        $data_stmt = mysqli_prepare($koneksi, "
            SELECT nama_bahan, ROUND((protein_per_100g * 4) + (karbohidrat_per_100g * 4) + (lemak_per_100g * 9), 2) AS kalori_per_100g, protein_per_100g, karbohidrat_per_100g, lemak_per_100g
            FROM bahan_makanan
            ORDER BY nama_bahan ASC LIMIT ? OFFSET ?
        ");
        mysqli_stmt_bind_param($data_stmt, 'ii', $limit, $offset);
    }
    mysqli_stmt_execute($data_stmt);
    $result = mysqli_stmt_get_result($data_stmt);
    $bahan_list = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $bahan_list[] = $row;
    }
    mysqli_stmt_close($data_stmt);

    $start_page = max(1, $page - 2);
    $end_page = min($total_pages, $page + 2);

    echo json_encode([
        'success' => true,
        'data' => $bahan_list,
        'pagination' => [
            'current'        => $page,
            'total_pages'    => $total_pages,
            'total_data'     => $total_data,
            'keyword'        => $keyword,
            'offset'         => $offset,
            'has_prev'       => $page > 1,
            'has_next'       => $page < $total_pages,
            'pages'          => range($start_page, $end_page),
            'show_first'     => $start_page > 1,
            'show_ellipsis_start' => $start_page > 2,
            'show_last'      => $end_page < $total_pages,
            'show_ellipsis_end'   => $end_page < $total_pages - 1,
        ]
    ]);
    exit;
}

$keyword = trim($_GET['cari'] ?? '');
$page = max(1, (int)($_GET['halaman'] ?? 1));
$limit = 50;
$offset = ($page - 1) * $limit;
$search_param = '%' . $keyword . '%';

if (!empty($keyword)) {
    $count_stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) FROM bahan_makanan WHERE nama_bahan LIKE ?");
    mysqli_stmt_bind_param($count_stmt, 's', $search_param);
} else {
    $count_stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) FROM bahan_makanan");
}
mysqli_stmt_execute($count_stmt);
mysqli_stmt_bind_result($count_stmt, $total_data);
mysqli_stmt_fetch($count_stmt);
mysqli_stmt_close($count_stmt);

$total_pages = ceil($total_data / $limit);

if (!empty($keyword)) {
    $exact_param = $keyword;
    $starts_with_param = $keyword . '%';
    $data_stmt = mysqli_prepare($koneksi, "
        SELECT nama_bahan, ROUND((protein_per_100g * 4) + (karbohidrat_per_100g * 4) + (lemak_per_100g * 9), 2) AS kalori_per_100g, protein_per_100g, karbohidrat_per_100g, lemak_per_100g
        FROM bahan_makanan WHERE nama_bahan LIKE ?
        ORDER BY
            CASE
                WHEN nama_bahan = ? THEN 0
                WHEN nama_bahan LIKE ? THEN 1
                ELSE 2
            END,
            nama_bahan ASC
        LIMIT ? OFFSET ?
    ");
    mysqli_stmt_bind_param($data_stmt, 'sssii', $search_param, $exact_param, $starts_with_param, $limit, $offset);
} else {
    $data_stmt = mysqli_prepare($koneksi, "
        SELECT nama_bahan, ROUND((protein_per_100g * 4) + (karbohidrat_per_100g * 4) + (lemak_per_100g * 9), 2) AS kalori_per_100g, protein_per_100g, karbohidrat_per_100g, lemak_per_100g
        FROM bahan_makanan
        ORDER BY nama_bahan ASC LIMIT ? OFFSET ?
    ");
    mysqli_stmt_bind_param($data_stmt, 'ii', $limit, $offset);
}
mysqli_stmt_execute($data_stmt);
$result = mysqli_stmt_get_result($data_stmt);
$bahan_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($data_stmt);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/favicon.png">
    <title>Pustaka Gizi — Rasa dan Gizi</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FAF7F2] text-[#2C2620] font-sans antialiased min-h-screen">

<?php $base_path = '../'; $active_page = 'pustaka_gizi'; require __DIR__ . '/../includes/partials/navbar.php'; ?>

<div class="max-w-6xl mx-auto px-6 py-8">
    <div class="flex items-start gap-4 md:gap-6 mb-6 md:mb-8">
        <div class="flex-1">
            <span class="text-[#A3492D] text-[11px] md:text-[12px] tracking-[0.15em] uppercase block mb-1">Database</span>
            <h1 class="font-serif text-2xl sm:text-3xl text-[#2C2620] font-normal mb-2">Pustaka Gizi</h1>
            <p class="text-[13px] md:text-[14px] text-[#4A4438]">Database referensi <span id="totalCount"><?= number_format($total_data) ?></span> bahan makanan beserta informasi gizi per 100 gram.</p>
        </div>
        <div class="hidden md:block w-20 h-20 md:w-28 md:h-28 shrink-0">
            <img src="../assets/images/pustaka.jpg" alt="" class="w-full h-full object-cover">
        </div>
    </div>

    <form method="GET" action="" class="bg-white p-5 mb-5 shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]" id="searchForm">
        <div class="flex flex-col sm:flex-row gap-3">
            <input type="search" name="cari" id="searchInput" value="<?= htmlspecialchars($keyword) ?>"
                   class="flex-1 px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all"
                   placeholder="Ketik nama bahan... (contoh: nasi, ayam, telur)" autofocus autocomplete="off">
            <button type="submit"
                    class="py-2.5 px-6 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all">
                Cari
            </button>
            <a href="pustaka_gizi.php" id="resetBtn"
               class="py-2.5 px-6 border border-[#D1C4B0] bg-white text-[13px] tracking-[0.1em] uppercase text-[#4A4438] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all text-center no-underline <?= empty($keyword) ? 'hidden' : '' ?>">
                Reset
            </a>
        </div>
    </form>

    <div id="infoArea">
        <?php if (!empty($keyword)): ?>
            <div class="bg-[#F5F0E8] px-4 py-3 mb-5 text-[14px] text-[#4A4438]">
                Menampilkan <strong><?= number_format($total_data) ?></strong> hasil untuk "<?= htmlspecialchars($keyword) ?>"
            </div>
        <?php else: ?>
            <div class="text-[14px] text-[#4A4438] mb-5">
                Menampilkan halaman <?= $page ?> dari <?= $total_pages ?> (total <?= number_format($total_data) ?> bahan)
            </div>
        <?php endif; ?>
    </div>

    <div id="tableContainer">
        <?php if (empty($bahan_list)): ?>
            <div class="bg-[#E4DBC8] p-16 text-center">
                <p class="text-[#4A4438] text-base mb-2">Tidak ada bahan yang cocok dengan "<?= htmlspecialchars($keyword) ?>"</p>
                <p class="text-[14px] text-[#4A4438]">Coba gunakan kata kunci lain</p>
            </div>
        <?php else: ?>
            <div class="bg-white overflow-hidden shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]">
                <div class="overflow-x-auto">
                    <table class="w-full text-[13px] min-w-[600px]" id="bahanTable">
                        <thead>
                            <tr class="border-b border-[#DFD5C4] text-[12px] tracking-[0.15em] uppercase text-[#6B6154]">
                                <th class="text-left py-3 px-4 w-12 font-normal">No</th>
                                <th class="text-left py-3 px-4 font-normal">Nama Bahan</th>
                                <th class="text-right py-3 px-4 font-normal">Kalori (kkal)</th>
                                <th class="text-right py-3 px-4 font-normal">Protein (g)</th>
                                <th class="text-right py-3 px-4 font-normal">Karbohidrat (g)</th>
                                <th class="text-right py-3 px-4 font-normal">Lemak (g)</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php $no = $offset + 1; ?>
                            <?php foreach ($bahan_list as $bahan): ?>
                            <tr class="border-b border-[#DFD5C4]">
                                <td class="py-2.5 px-4 text-[#6B6154]"><?= $no++ ?></td>
                                <td class="py-2.5 px-4 text-[#2C2620]"><?= htmlspecialchars($bahan['nama_bahan']) ?></td>
                                <td class="py-2.5 px-4 text-right"><?= number_format($bahan['kalori_per_100g'], 2) ?></td>
                                <td class="py-2.5 px-4 text-right"><?= number_format($bahan['protein_per_100g'], 2) ?></td>
                                <td class="py-2.5 px-4 text-right"><?= number_format($bahan['karbohidrat_per_100g'], 2) ?></td>
                                <td class="py-2.5 px-4 text-right"><?= number_format($bahan['lemak_per_100g'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if ($total_pages > 1): ?>
            <div class="flex flex-wrap justify-center items-center gap-1 md:gap-2 mt-6 text-[11px] md:text-[13px]" id="paginationArea">
                <?php if ($page > 1): ?>
                    <a href="?cari=<?= urlencode($keyword) ?>&halaman=<?= $page - 1 ?>" data-page="<?= $page - 1 ?>"
                       class="page-link px-2 py-1 md:px-3 md:py-1.5 border border-[#D1C4B0] bg-white text-[#6B6154] hover:bg-[#F5F0E8] transition-all no-underline shadow-[0_2px_5px_rgba(0,0,0,0.08)]">← Prev</a>
                <?php else: ?>
                    <span class="px-2 py-1 md:px-3 md:py-1.5 border border-[#D1C4B0] bg-white text-[#6B6154] cursor-not-allowed shadow-[0_2px_5px_rgba(0,0,0,0.08)]">← Prev</span>
                <?php endif; ?>

                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                if ($start_page > 1): ?>
                    <a href="?cari=<?= urlencode($keyword) ?>&halaman=1" data-page="1" class="page-link px-2 py-1 md:px-3 md:py-1.5 border border-[#D1C4B0] bg-white text-[#6B6154] hover:bg-[#F5F0E8] transition-all no-underline shadow-[0_2px_5px_rgba(0,0,0,0.08)]">1</a>
                    <?php if ($start_page > 2): ?>
                        <span class="px-2 text-[#6B6154]">...</span>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="px-2 py-1 md:px-3 md:py-1.5 border border-[#A3492D] bg-[#A3492D] text-white"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?cari=<?= urlencode($keyword) ?>&halaman=<?= $i ?>" data-page="<?= $i ?>"
                           class="page-link px-2 py-1 md:px-3 md:py-1.5 border border-[#D1C4B0] bg-white text-[#6B6154] hover:bg-[#F5F0E8] transition-all no-underline shadow-[0_2px_5px_rgba(0,0,0,0.08)]"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($end_page < $total_pages): ?>
                    <?php if ($end_page < $total_pages - 1): ?>
                        <span class="px-2 text-[#6B6154]">...</span>
                    <?php endif; ?>
                    <a href="?cari=<?= urlencode($keyword) ?>&halaman=<?= $total_pages ?>" data-page="<?= $total_pages ?>"
                       class="page-link px-2 py-1 md:px-3 md:py-1.5 border border-[#D1C4B0] bg-white text-[#6B6154] hover:bg-[#F5F0E8] transition-all no-underline shadow-[0_2px_5px_rgba(0,0,0,0.08)]"><?= $total_pages ?></a>
                <?php endif; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?cari=<?= urlencode($keyword) ?>&halaman=<?= $page + 1 ?>" data-page="<?= $page + 1 ?>"
                       class="page-link px-2 py-1 md:px-3 md:py-1.5 border border-[#D1C4B0] bg-white text-[#6B6154] hover:bg-[#F5F0E8] transition-all no-underline shadow-[0_2px_5px_rgba(0,0,0,0.08)]">Next &rarr;</a>
                <?php else: ?>
                    <span class="px-2 py-1 md:px-3 md:py-1.5 border border-[#D1C4B0] bg-white text-[#6B6154] cursor-not-allowed shadow-[0_2px_5px_rgba(0,0,0,0.08)]">Next &rarr;</span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
const searchInput = document.getElementById('searchInput');
let debounceTimer;

function fetchData(keyword, page = 1) {
    const url = '?cari=' + encodeURIComponent(keyword) + '&halaman=' + page + '&json=1';
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;
            updateTableAndInfo(data);
        })
        .catch(err => console.error('Fetch error:', err));
}

function updateTableAndInfo(data) {
    const p = data.pagination;
    const keyword = p.keyword;
    const page = p.current;
    const totalPages = p.total_pages;
    const totalData = p.total_data;

    document.getElementById('totalCount').textContent = totalData.toLocaleString('id-ID');

    const infoArea = document.getElementById('infoArea');
    if (keyword) {
        infoArea.innerHTML = `
            <div class="border border-[#DFD5C4] px-4 py-3 mb-5 text-[14px] text-[#4A4438]">
                Menampilkan <strong>${totalData.toLocaleString('id-ID')}</strong> hasil untuk "${escapeHtml(keyword)}"
            </div>
        `;
    } else {
        infoArea.innerHTML = `
            <div class="text-[14px] text-[#4A4438] mb-5">
                Menampilkan halaman ${page} dari ${totalPages} (total ${totalData.toLocaleString('id-ID')} bahan)
            </div>
        `;
    }

    const resetBtn = document.getElementById('resetBtn');
    if (keyword) {
        resetBtn.classList.remove('hidden');
    } else {
        resetBtn.classList.add('hidden');
    }

    const tbody = document.getElementById('tableBody');
    const container = document.querySelector('#tableContainer');

    if (data.data.length === 0) {
        container.innerHTML = `
            <div class="bg-[#E4DBC8] p-16 text-center">
                <p class="text-[#4A4438] text-base mb-2">Tidak ada bahan yang cocok dengan "${escapeHtml(keyword)}"</p>
                <p class="text-[14px] text-[#4A4438]">Coba gunakan kata kunci lain</p>
            </div>
        `;
        return;
    }

    let html = '<div class="bg-white overflow-hidden shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]"><div class="overflow-x-auto">';
    html += '<table class="w-full text-[13px]" id="bahanTable">';
    html += '<thead><tr class="border-b border-[#DFD5C4] text-[12px] tracking-[0.15em] uppercase text-[#6B6154]">';
    html += '<th class="text-left py-3 px-4 w-12 font-normal">No</th>';
    html += '<th class="text-left py-3 px-4 font-normal">Nama Bahan</th>';
    html += '<th class="text-right py-3 px-4 font-normal">Kalori (kkal)</th>';
    html += '<th class="text-right py-3 px-4 font-normal">Protein (g)</th>';
    html += '<th class="text-right py-3 px-4 font-normal">Karbohidrat (g)</th>';
    html += '<th class="text-right py-3 px-4 font-normal">Lemak (g)</th>';
    html += '</tr></thead><tbody>';

    data.data.forEach(function(item, index) {
        const no = p.offset + index + 1;
        html += `<tr class="border-b border-[#DFD5C4]">`;
        html += `<td class="py-2.5 px-4 text-[#6B6154]">${no}</td>`;
        html += `<td class="py-2.5 px-4 text-[#2C2620]">${escapeHtml(item.nama_bahan)}</td>`;
        html += `<td class="py-2.5 px-4 text-right">${formatNum(item.kalori_per_100g)}</td>`;
        html += `<td class="py-2.5 px-4 text-right">${formatNum(item.protein_per_100g)}</td>`;
        html += `<td class="py-2.5 px-4 text-right">${formatNum(item.karbohidrat_per_100g)}</td>`;
        html += `<td class="py-2.5 px-4 text-right">${formatNum(item.lemak_per_100g)}</td>`;
        html += `</tr>`;
    });

    html += '</tbody></table></div></div>';

    if (totalPages > 1) {
        html += buildPaginationHtml(p, keyword);
    }

    container.innerHTML = html;

    document.querySelectorAll('.page-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const pageNum = parseInt(this.dataset.page);
            if (!isNaN(pageNum)) {
                fetchData(keyword, pageNum);
            }
        });
    });
}

function buildPaginationHtml(p, keyword) {
    let h = '<div class="flex justify-center items-center gap-2 mt-6 text-[13px]" id="paginationArea">';
    const q = keyword ? '&cari=' + encodeURIComponent(keyword) : '';

    if (p.has_prev) {
        h += `<a href="#" data-page="${p.current - 1}" class="page-link px-3 py-1.5 border border-[#D1C4B0] bg-white text-[#6B6154] hover:bg-[#F5F0E8] transition-all no-underline shadow-[0_2px_5px_rgba(0,0,0,0.08)]">â† Prev</a>`;
    } else {
        h += `<span class="px-3 py-1.5 border border-[#D1C4B0] bg-white text-[#6B6154] cursor-not-allowed shadow-[0_2px_5px_rgba(0,0,0,0.08)]">â† Prev</span>`;
    }

    if (p.show_first) {
        h += `<a href="#" data-page="1" class="page-link px-3 py-1.5 border border-[#D1C4B0] bg-white text-[#6B6154] hover:bg-[#F5F0E8] transition-all no-underline shadow-[0_2px_5px_rgba(0,0,0,0.08)]">1</a>`;
        if (p.show_ellipsis_start) {
            h += `<span class="px-2 text-[#6B6154]">...</span>`;
        }
    }

    p.pages.forEach(function(i) {
        if (i === p.current) {
            h += `<span class="px-3 py-1.5 border border-[#A3492D] bg-[#A3492D] text-white">${i}</span>`;
        } else {
            h += `<a href="#" data-page="${i}" class="page-link px-3 py-1.5 border border-[#D1C4B0] bg-white text-[#6B6154] hover:bg-[#F5F0E8] transition-all no-underline shadow-[0_2px_5px_rgba(0,0,0,0.08)]">${i}</a>`;
        }
    });

    if (p.show_last) {
        if (p.show_ellipsis_end) {
            h += `<span class="px-2 text-[#6B6154]">...</span>`;
        }
        h += `<a href="#" data-page="${p.total_pages}" class="page-link px-3 py-1.5 border border-[#D1C4B0] bg-white text-[#6B6154] hover:bg-[#F5F0E8] transition-all no-underline shadow-[0_2px_5px_rgba(0,0,0,0.08)]">${p.total_pages}</a>`;
    }

    if (p.has_next) {
        h += `<a href="#" data-page="${p.current + 1}" class="page-link px-3 py-1.5 border border-[#D1C4B0] bg-white text-[#6B6154] hover:bg-[#F5F0E8] transition-all no-underline shadow-[0_2px_5px_rgba(0,0,0,0.08)]">Next â†’</a>`;
    } else {
        h += `<span class="px-3 py-1.5 border border-[#D1C4B0] bg-white text-[#6B6154] cursor-not-allowed shadow-[0_2px_5px_rgba(0,0,0,0.08)]">Next â†’</span>`;
    }

    h += '</div>';
    return h;
}

function formatNum(val) {
    return parseFloat(val).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

searchInput.addEventListener('input', function() {
    clearTimeout(debounceTimer);
    const keyword = this.value;
    debounceTimer = setTimeout(function() {
        fetchData(keyword, 1);
    }, 300);
});

document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    fetchData(searchInput.value, 1);
});

document.querySelectorAll('.page-link').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const pageNum = parseInt(this.dataset.page);
        if (!isNaN(pageNum)) {
            fetchData(searchInput.value, pageNum);
        }
    });
});
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

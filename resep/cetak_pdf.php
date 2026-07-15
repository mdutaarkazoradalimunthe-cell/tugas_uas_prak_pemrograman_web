<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/cek_login.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/fungsi_gizi.php';

use Dompdf\Dompdf;

$id_resep = (int) ($_GET['id'] ?? 0);
$id_user = (int) $_SESSION['id_user'];

if ($id_resep <= 0) {
    die('ID resep tidak valid.');
}

$stmt = mysqli_prepare($koneksi, "
    SELECT r.*, kr.nama_kategori
    FROM resep_pribadi r
    LEFT JOIN kategori_resep kr ON r.id_kategori = kr.id
    WHERE r.id = ? AND r.id_user = ?
");
mysqli_stmt_bind_param($stmt, 'ii', $id_resep, $id_user);
mysqli_stmt_execute($stmt);
$resep = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$resep) {
    die('Resep tidak ditemukan.');
}

$gizi = hitung_gizi_resep_pribadi($id_resep);
$bahan = get_bahan_resep_pribadi($id_resep);

$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
    @page { margin: 18mm 14mm; }
    body { font-family: DejaVu Sans, sans-serif; color: #2C2620; font-size: 10pt; line-height: 1.6; margin: 0; padding: 0; }

    .header { text-align: center; border-bottom: 2px solid #A3492D; padding: 0 0 14px 0; margin-bottom: 22px; }
    .header-brand { font-size: 16pt; color: #A3492D; font-weight: bold; letter-spacing: 2px; }
    .header-sub { font-size: 7pt; color: #B0A898; letter-spacing: 1px; margin-top: 2px; }
    .kategori { font-size: 7pt; letter-spacing: 2.5px; text-transform: uppercase; color: #A3492D; margin-top: 12px; }
    .judul { font-size: 22pt; font-weight: bold; margin: 4px 0 3px 0; line-height: 1.2; }
    .porsi { font-size: 8pt; color: #6B6154; }

    .gizi-table { width: 100%; margin: 18px 0; page-break-inside: avoid; border-collapse: collapse; }
    .gizi-table td { width: 25%; text-align: center; padding: 10px 6px; border: 1px solid #E4DBC8; }
    .gizi-table .c-kalori { border-top: 3px solid #D9733E; }
    .gizi-table .c-protein { border-top: 3px solid #A3492D; }
    .gizi-table .c-karbo { border-top: 3px solid #B5A642; }
    .gizi-table .c-lemak { border-top: 3px solid #6B8F71; }
    .gizi-nilai { font-size: 18pt; color: #A3492D; }
    .gizi-label { font-size: 6pt; letter-spacing: 1.5px; text-transform: uppercase; color: #6B6154; margin-top: 2px; }

    .section-title { font-size: 8pt; letter-spacing: 2.5px; text-transform: uppercase; color: #A3492D; margin: 22px 0 8px 0; }

    table.bahan { width: 100%; border-collapse: collapse; font-size: 8.5pt; page-break-inside: avoid; }
    table.bahan thead th { text-transform: uppercase; font-size: 6.5pt; letter-spacing: 1.5px; color: #6B6154; border-bottom: 1px solid #DFD5C4; padding: 6px 8px; text-align: left; font-weight: normal; }
    table.bahan thead th.r { text-align: right; }
    table.bahan tbody td { padding: 5px 8px; border-bottom: 1px solid #F0EBE0; }
    table.bahan tbody td.r { text-align: right; }
    table.bahan tfoot td { padding: 6px 8px; border-top: 2px solid #2C2620; font-weight: bold; }
    table.bahan tfoot td.r { text-align: right; }

    .langkah-section { page-break-inside: avoid; }
    .langkah { margin: 8px 0; font-size: 9pt; color: #4A4438; line-height: 2; }

    .footer { text-align: center; font-size: 7pt; color: #B0A898; margin-top: 35px; padding-top: 12px; border-top: 1px solid #E4DBC8; }
</style></head><body>';

$html .= '<div class="header">
    <div class="header-brand">RASA &amp; GIZI</div>
    <div class="header-sub">Cetak Resep</div>
    <div class="kategori">' . htmlspecialchars($resep['nama_kategori'] ?? 'Resep') . '</div>
    <div class="judul">' . htmlspecialchars($resep['judul']) . '</div>
    <div class="porsi">' . (int) $resep['jumlah_porsi'] . ' porsi';
if (!empty($resep['deskripsi'])) {
    $html .= ' &middot; ' . htmlspecialchars(substr($resep['deskripsi'], 0, 120));
}
$html .= '</div></div>';

if ($gizi) {
    $html .= '<table class="gizi-table"><tr>
        <td class="c-kalori">
            <div class="gizi-nilai">' . number_format($gizi['per_porsi']['kalori'], 1) . '</div>
            <div class="gizi-label">Kalori (kkal)</div>
        </td>
        <td class="c-protein">
            <div class="gizi-nilai">' . number_format($gizi['per_porsi']['protein'], 1) . '</div>
            <div class="gizi-label">Protein (g)</div>
        </td>
        <td class="c-karbo">
            <div class="gizi-nilai">' . number_format($gizi['per_porsi']['karbohidrat'], 1) . '</div>
            <div class="gizi-label">Karbohidrat (g)</div>
        </td>
        <td class="c-lemak">
            <div class="gizi-nilai">' . number_format($gizi['per_porsi']['lemak'], 1) . '</div>
            <div class="gizi-label">Lemak (g)</div>
        </td>
    </tr></table>';
}

$html .= '<div class="section-title">Bahan-Bahan</div>';
$html .= '<table class="bahan">
<thead><tr>
    <th>Bahan</th><th class="r">Jumlah</th><th class="r">Kalori</th>
    <th class="r">Protein</th><th class="r">Karbo</th><th class="r">Lemak</th>
</tr></thead><tbody>';

if ($bahan && count($bahan) > 0) {
    foreach ($bahan as $b) {
        $display = ($b['satuan'] && $b['jumlah_asli']) ? htmlspecialchars($b['jumlah_asli'] . ' ' . $b['satuan']) . ' (' . (int)$b['jumlah_gram'] . ' g)' : (int)$b['jumlah_gram'] . ' g';
        $html .= '<tr>
            <td>' . htmlspecialchars($b['nama_bahan']) . '</td>
            <td class="r">' . $display . '</td>
            <td class="r">' . number_format($b['kalori'], 1) . '</td>
            <td class="r">' . number_format($b['protein'], 1) . '</td>
            <td class="r">' . number_format($b['karbohidrat'], 1) . '</td>
            <td class="r">' . number_format($b['lemak'], 1) . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="6" style="color:#B0A898;text-align:center;padding:10px;">Tidak ada bahan</td></tr>';
}

$html .= '</tbody>';
if ($gizi) {
    $html .= '<tfoot><tr>
        <td>Total</td>
        <td class="r">-</td>
        <td class="r">' . number_format($gizi['total_kalori'], 1) . '</td>
        <td class="r">' . number_format($gizi['total_protein'], 1) . '</td>
        <td class="r">' . number_format($gizi['total_karbohidrat'], 1) . '</td>
        <td class="r">' . number_format($gizi['total_lemak'], 1) . '</td>
    </tr></tfoot>';
}
$html .= '</table>';

if (!empty(trim($resep['langkah_memasak'] ?? ''))) {
    $langkah = htmlspecialchars($resep['langkah_memasak']);
    $langkah = nl2br($langkah);
    $html .= '<div class="langkah-section">
        <div class="section-title">Langkah Memasak</div>
        <div class="langkah">' . $langkah . '</div>
    </div>';
}

$html .= '<div class="footer">
    Dicetak dari Rasa &amp; Gizi &middot; ' . date('d/m/Y H:i') . '
</div>';

$html .= '</body></html>';

try {
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('resep-' . $id_resep . '.pdf', ['Attachment' => true]);
} catch (Exception $e) {
    echo '<h3>Gagal membuat PDF</h3>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><a href="detail.php?id=' . $id_resep . '">Kembali ke resep</a></p>';
}

<?php
// File berisi fungsi-fungsi untuk kalkulasi gizi
require_once 'koneksi.php';

/**
 * Hitung total gizi untuk suatu resep
 * 
 * @param int $id_resep ID resep yang akan dihitung
 * @return array Associative array berisi total gizi dan info per porsi
 */
function hitung_gizi_resep($id_resep) {
    global $koneksi;

    // Ambil jumlah porsi dari tabel resep
    $stmt = mysqli_prepare($koneksi, "SELECT jumlah_porsi, judul FROM resep WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id_resep);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resep = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$resep) {
        return null; // Resep tidak ditemukan
    }

    $jumlah_porsi = (int)$resep['jumlah_porsi'];

    // Hitung total gizi dari semua bahan dalam resep
    // Rumus: (jumlah_gram / 100) * nilai_per_100g
    $query = "
        SELECT 
            SUM((rb.jumlah_gram / 100) * ((bm.protein_per_100g * 4) + (bm.karbohidrat_per_100g * 4) + (bm.lemak_per_100g * 9))) AS total_kalori,
            SUM((rb.jumlah_gram / 100) * bm.protein_per_100g) AS total_protein,
            SUM((rb.jumlah_gram / 100) * bm.karbohidrat_per_100g) AS total_karbohidrat,
            SUM((rb.jumlah_gram / 100) * bm.lemak_per_100g) AS total_lemak
        FROM resep_bahan rb
        JOIN bahan_makanan bm ON rb.id_bahan = bm.id
        WHERE rb.id_resep = ?
    ";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id_resep);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $total = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Hitung per porsi
    return [
        'judul' => $resep['judul'],
        'jumlah_porsi' => $jumlah_porsi,
        'total_kalori' => round($total['total_kalori'] ?? 0, 2),
        'total_protein' => round($total['total_protein'] ?? 0, 2),
        'total_karbohidrat' => round($total['total_karbohidrat'] ?? 0, 2),
        'total_lemak' => round($total['total_lemak'] ?? 0, 2),
        'per_porsi' => [
            'kalori' => $jumlah_porsi > 0 ? round(($total['total_kalori'] ?? 0) / $jumlah_porsi, 2) : 0,
            'protein' => $jumlah_porsi > 0 ? round(($total['total_protein'] ?? 0) / $jumlah_porsi, 2) : 0,
            'karbohidrat' => $jumlah_porsi > 0 ? round(($total['total_karbohidrat'] ?? 0) / $jumlah_porsi, 2) : 0,
            'lemak' => $jumlah_porsi > 0 ? round(($total['total_lemak'] ?? 0) / $jumlah_porsi, 2) : 0,
        ]
    ];
}

/**
 * Ambil detail bahan-bahan dari suatu resep (untuk ditampilkan di tabel)
 * 
 * @param int $id_resep ID resep
 * @return array Daftar bahan dengan informasi gizi masing-masing
 */
function get_bahan_resep($id_resep) {
    global $koneksi;

    $query = "
        SELECT 
            rb.id AS id_rb,
            rb.id_bahan,
            bm.nama_bahan,
            rb.jumlah_gram,
            ROUND((rb.jumlah_gram / 100) * ((bm.protein_per_100g * 4) + (bm.karbohidrat_per_100g * 4) + (bm.lemak_per_100g * 9)), 2) AS kalori,
            ROUND((rb.jumlah_gram / 100) * bm.protein_per_100g, 2) AS protein,
            ROUND((rb.jumlah_gram / 100) * bm.karbohidrat_per_100g, 2) AS karbohidrat,
            ROUND((rb.jumlah_gram / 100) * bm.lemak_per_100g, 2) AS lemak,
            (bm.protein_per_100g * 4) + (bm.karbohidrat_per_100g * 4) + (bm.lemak_per_100g * 9) AS kalori_per_100g,
            bm.protein_per_100g,
            bm.karbohidrat_per_100g,
            bm.lemak_per_100g
        FROM resep_bahan rb
        JOIN bahan_makanan bm ON rb.id_bahan = bm.id
        WHERE rb.id_resep = ?
        ORDER BY bm.nama_bahan ASC
    ";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id_resep);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $bahan = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $bahan[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $bahan;
}

function hitung_gizi_resep_pribadi($id_resep_pribadi) {
    global $koneksi;

    $stmt = mysqli_prepare($koneksi, "SELECT jumlah_porsi, judul FROM resep_pribadi WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id_resep_pribadi);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resep = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$resep) {
        return null;
    }

    $jumlah_porsi = (int)$resep['jumlah_porsi'];

    $query = "
        SELECT 
            SUM((rb.jumlah_gram / 100) * ((bm.protein_per_100g * 4) + (bm.karbohidrat_per_100g * 4) + (bm.lemak_per_100g * 9))) AS total_kalori,
            SUM((rb.jumlah_gram / 100) * bm.protein_per_100g) AS total_protein,
            SUM((rb.jumlah_gram / 100) * bm.karbohidrat_per_100g) AS total_karbohidrat,
            SUM((rb.jumlah_gram / 100) * bm.lemak_per_100g) AS total_lemak
        FROM resep_pribadi_bahan rb
        JOIN bahan_makanan bm ON rb.id_bahan = bm.id
        WHERE rb.id_resep_pribadi = ?
    ";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id_resep_pribadi);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $total = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return [
        'judul' => $resep['judul'],
        'jumlah_porsi' => $jumlah_porsi,
        'total_kalori' => round($total['total_kalori'] ?? 0, 2),
        'total_protein' => round($total['total_protein'] ?? 0, 2),
        'total_karbohidrat' => round($total['total_karbohidrat'] ?? 0, 2),
        'total_lemak' => round($total['total_lemak'] ?? 0, 2),
        'per_porsi' => [
            'kalori' => $jumlah_porsi > 0 ? round(($total['total_kalori'] ?? 0) / $jumlah_porsi, 2) : 0,
            'protein' => $jumlah_porsi > 0 ? round(($total['total_protein'] ?? 0) / $jumlah_porsi, 2) : 0,
            'karbohidrat' => $jumlah_porsi > 0 ? round(($total['total_karbohidrat'] ?? 0) / $jumlah_porsi, 2) : 0,
            'lemak' => $jumlah_porsi > 0 ? round(($total['total_lemak'] ?? 0) / $jumlah_porsi, 2) : 0,
        ]
    ];
}

function get_bahan_resep_pribadi($id_resep_pribadi) {
    global $koneksi;

    $query = "
        SELECT 
            rb.id AS id_rb,
            rb.id_bahan,
            bm.nama_bahan,
            rb.jumlah_gram,
            ROUND((rb.jumlah_gram / 100) * ((bm.protein_per_100g * 4) + (bm.karbohidrat_per_100g * 4) + (bm.lemak_per_100g * 9)), 2) AS kalori,
            ROUND((rb.jumlah_gram / 100) * bm.protein_per_100g, 2) AS protein,
            ROUND((rb.jumlah_gram / 100) * bm.karbohidrat_per_100g, 2) AS karbohidrat,
            ROUND((rb.jumlah_gram / 100) * bm.lemak_per_100g, 2) AS lemak,
            (bm.protein_per_100g * 4) + (bm.karbohidrat_per_100g * 4) + (bm.lemak_per_100g * 9) AS kalori_per_100g,
            bm.protein_per_100g,
            bm.karbohidrat_per_100g,
            bm.lemak_per_100g
        FROM resep_pribadi_bahan rb
        JOIN bahan_makanan bm ON rb.id_bahan = bm.id
        WHERE rb.id_resep_pribadi = ?
        ORDER BY bm.nama_bahan ASC
    ";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id_resep_pribadi);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $bahan = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $bahan[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $bahan;
}

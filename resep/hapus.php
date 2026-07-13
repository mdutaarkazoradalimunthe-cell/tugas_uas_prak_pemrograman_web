<?php
require_once '../cek_login.php';
require_once '../koneksi.php';

$id_resep = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$id_user = $_SESSION['id_user'];

if ($id_resep > 0) {
    // Hapus resep pribadi (pastikan milik user yang login)
    // ON DELETE CASCADE akan otomatis menghapus data di resep_pribadi_bahan
    $stmt = mysqli_prepare($koneksi, "DELETE FROM resep_pribadi WHERE id = ? AND id_user = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $id_resep, $id_user);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header('Location: index.php');
exit;

<?php
session_start();

if (isset($_SESSION['id_user'])) {
    require_once 'koneksi.php';
    mysqli_query($koneksi, "DELETE FROM remember_tokens WHERE id_user = $_SESSION[id_user]");
}

setcookie('remember_token', '', time() - 3600, '/');
session_destroy();
header('Location: login.php');
exit;

<?php
// Proteksi halaman: include file ini di awal setiap halaman yang butuh login
session_start();

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

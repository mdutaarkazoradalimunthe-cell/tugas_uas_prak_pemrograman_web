<?php
session_start();
require 'config/koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi_password'];

    if (empty($nama) || empty($email) || empty($password)) {
        $error = 'Semua field wajib diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif ($password !== $konfirmasi) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        $stmt = mysqli_prepare($koneksi, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'Email sudah terdaftar!';
        } else {
            mysqli_stmt_close($stmt);

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($koneksi, "INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'sss', $nama, $email, $password_hash);

            if (mysqli_stmt_execute($stmt)) {
                $success = 'Pendaftaran berhasil! Silakan <a href="login.php" class="text-[#A3492D] underline">masuk</a>.';
            } else {
                $error = 'Gagal mendaftar: ' . mysqli_error($koneksi);
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Rasa dan Gizi</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FAF7F2] text-[#2C2620] font-sans antialiased min-h-screen">

<div class="flex min-h-screen">
    <div class="w-full md:w-1/2 flex items-center justify-center px-6 py-8 md:px-8 md:py-12">
        <div class="w-full max-w-sm">
            <a href="pages/landing.php" class="block mb-8 md:mb-10"><img src="assets/images/logo.png" alt="Rasa dan Gizi" class="h-12 sm:h-16 w-auto"></a>

            <h1 class="font-serif text-2xl sm:text-3xl text-[#2C2620] font-normal mb-6 md:mb-8">Daftar</h1>

            <?php if ($error): ?>
                <div class="border border-[#A3492D] bg-[#FAF7F2] text-[#A3492D] text-[13px] px-4 py-3 mb-6"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="border border-[#DFD5C4] bg-[#FAF7F2] text-[#2C2620] text-[13px] px-4 py-3 mb-6"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-5">
                    <label for="nama" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Nama</label>
                    <input type="text" id="nama" name="nama" required
                           class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
                </div>

                <div class="mb-5">
                    <label for="email" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Email</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
                </div>

                <div class="mb-5">
                    <label for="password" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Kata Sandi</label>
                    <input type="password" id="password" name="password" required minlength="6"
                           class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
                </div>

                <div class="mb-8">
                    <label for="konfirmasi_password" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Konfirmasi Kata Sandi</label>
                    <input type="password" id="konfirmasi_password" name="konfirmasi_password" required
                           class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
                </div>

                <button type="submit"
                        class="w-full py-2.5 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all">
                    Daftar
                </button>
            </form>

            <p class="text-[14px] text-[#4A4438] mt-6 text-center">
                Sudah punya akun? <a href="login.php" class="text-[#A3492D] no-underline border-b border-[#A3492D] pb-0.5">Masuk</a>
            </p>
        </div>
    </div>

    <div class="hidden md:block w-1/2 relative overflow-hidden">
        <img src="assets/images/register.jpg" alt="Hidangan pasta" class="absolute inset-0 w-full h-full object-cover">
    </div>
</div>

</body>
</html>

<?php
session_start();
if (isset($_SESSION['id_user'])) {
    header('Location: resep/index.php');
    exit;
}

require 'config/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi!';
    } else {
        $stmt = mysqli_prepare($koneksi, "SELECT id, nama, password FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id_user'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $email;

            if (isset($_POST['remember'])) {
                $token = bin2hex(random_bytes(32));
                $token_hash = hash('sha256', $token);
                $expires_at = date('Y-m-d H:i:s', strtotime('+5 years'));

                mysqli_query($koneksi, "DELETE FROM remember_tokens WHERE id_user = $user[id]");
                $stmt = mysqli_prepare($koneksi, "INSERT INTO remember_tokens (id_user, token_hash, expires_at) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($stmt, 'iss', $user['id'], $token_hash, $expires_at);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                setcookie('remember_token', $token, time() + 157680000, '/', '', true, true);
            }

            header('Location: resep/index.php');
            exit;
        } else {
            $error = 'Email atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Rasa dan Gizi</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FAF7F2] text-[#2C2620] font-sans antialiased min-h-screen">

<div class="flex min-h-screen">
    <div class="hidden md:block w-1/2 relative overflow-hidden">
        <img src="assets/images/login.jpg" alt="Hidangan" class="absolute inset-0 w-full h-full object-cover">
    </div>

    <div class="w-full md:w-1/2 flex items-center justify-center px-6 py-8 md:px-8 md:py-12">
        <div class="w-full max-w-sm">
            <a href="landing.php" class="block mb-8 md:mb-10"><img src="assets/images/logo.png" alt="Rasa dan Gizi" class="h-12 sm:h-16 w-auto"></a>

            <h1 class="font-serif text-2xl sm:text-3xl text-[#2C2620] font-normal mb-6 md:mb-8">Masuk</h1>

            <?php if ($error): ?>
                <div class="border border-[#A3492D] bg-[#FAF7F2] text-[#A3492D] text-[13px] px-4 py-3 mb-6"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-5">
                    <label for="email" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Email</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
                </div>

                <div class="mb-5">
                    <label for="password" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Kata Sandi</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
                </div>

                <div class="mb-8">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 border border-[#D1C4B0] text-[#A3492D] focus:ring-[#A3492D]">
                        <span class="text-[13px] text-[#4A4438]">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-2.5 border border-[#A3492D] bg-[#A3492D] text-white text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all">
                    Masuk
                </button>
            </form>

            <p class="text-[14px] text-[#4A4438] mt-6 text-center">
                Belum punya akun? <a href="register.php" class="text-[#A3492D] no-underline border-b border-[#A3492D] pb-0.5">Daftar</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>

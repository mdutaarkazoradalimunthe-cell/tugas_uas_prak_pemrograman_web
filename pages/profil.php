<?php
require_once '../config/cek_login.php';
require_once '../config/koneksi.php';

$success = '';
$error = '';

$id_user = $_SESSION['id_user'];

// Ambil data user dari DB
$stmt = mysqli_prepare($koneksi, "SELECT nama, email FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$nama  = $user['nama'] ?? $_SESSION['nama'];
$email = $user['email'] ?? $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_baru  = trim($_POST['nama'] ?? '');
    $email_baru = trim($_POST['email'] ?? '');
    $pass_lama  = $_POST['password_lama'] ?? '';
    $pass_baru  = $_POST['password_baru'] ?? '';
    $pass_konf  = $_POST['konfirmasi_password'] ?? '';

    // Validasi nama
    if (empty($nama_baru)) {
        $error = 'Nama tidak boleh kosong.';
    }
    // Validasi email
    elseif (empty($email_baru)) {
        $error = 'Email tidak boleh kosong.';
    }
    elseif (!filter_var($email_baru, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    }
    else {
        // Cek apakah email sudah dipakai user lain
        $stmt = mysqli_prepare($koneksi, "SELECT id FROM users WHERE email = ? AND id != ?");
        mysqli_stmt_bind_param($stmt, 'si', $email_baru, $id_user);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'Email sudah terdaftar. Gunakan email lain.';
        }
        mysqli_stmt_close($stmt);
    }

    // Validasi password hanya jika diisi
    if (empty($error) && !empty($pass_baru)) {
        if (empty($pass_lama)) {
            $error = 'Masukkan password lama untuk mengubah password.';
        }
        elseif (strlen($pass_baru) < 6) {
            $error = 'Password baru minimal 6 karakter.';
        }
        elseif ($pass_baru !== $pass_konf) {
            $error = 'Konfirmasi password baru tidak cocok.';
        }
        else {
            // Verifikasi password lama
            $stmt = mysqli_prepare($koneksi, "SELECT password FROM users WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'i', $id_user);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if (!password_verify($pass_lama, $row['password'])) {
                $error = 'Password lama salah.';
            }
        }
    }

    // Simpan perubahan
    if (empty($error)) {
        if (!empty($pass_baru)) {
            $hash_baru = password_hash($pass_baru, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($koneksi, "UPDATE users SET nama = ?, email = ?, password = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'sssi', $nama_baru, $email_baru, $hash_baru, $id_user);
        } else {
            $stmt = mysqli_prepare($koneksi, "UPDATE users SET nama = ?, email = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'ssi', $nama_baru, $email_baru, $id_user);
        }

        if (mysqli_stmt_execute($stmt)) {
            // Update session
            $_SESSION['nama'] = $nama_baru;
            $_SESSION['email'] = $email_baru;
            $nama = $nama_baru;
            $email = $email_baru;
            $success = 'Profil berhasil diperbarui.';
        } else {
            $error = 'Gagal menyimpan perubahan. Coba lagi.';
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
    <title>Profil â€” Rasa dan Gizi</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FAF7F2] text-[#2C2620] font-sans antialiased min-h-screen">

<?php $base_path = '../'; $active_page = ''; require __DIR__ . '/../includes/partials/navbar.php'; ?>

<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="flex items-start gap-6 mb-8">
        <div class="flex-1">
            <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-1">Profil</span>
            <h1 class="font-serif text-2xl sm:text-3xl text-[#2C2620] font-normal mb-2">Pengaturan Akun</h1>
            <p class="text-[14px] text-[#4A4438]">Kelola data diri dan password kamu.</p>
        </div>
        <div class="hidden md:block w-20 h-20 md:w-28 md:h-28 shrink-0">
            <img src="../assets/images/dashboard.jpg" alt="" class="w-full h-full object-cover">
        </div>
    </div>

    <?php if ($success): ?>
        <div class="border border-[#DFD5C4] bg-[#FAF7F2] text-[#2C2620] text-[13px] px-4 py-3 mb-6"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="border border-[#A3492D] bg-[#FAF7F2] text-[#A3492D] text-[13px] px-4 py-3 mb-6"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="" class="bg-white p-6 shadow-[0_6px_20px_rgba(0,0,0,0.14)] rounded-[2px]">
        <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-5">Data Diri</span>

        <div class="mb-5">
            <label for="nama" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Nama</label>
            <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($nama) ?>"
                   class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
        </div>

        <div class="mb-5">
            <label for="email" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>"
                   class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
        </div>

        <hr class="border-[#DFD5C4] my-6">

        <span class="text-[#A3492D] text-[12px] tracking-[0.15em] uppercase block mb-5">Ubah Password</span>
        <p class="text-[13px] text-[#6B6154] mb-4">Kosongkan jika tidak ingin mengubah password.</p>

        <div class="mb-5">
            <label for="password_lama" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Password Lama</label>
            <input type="password" name="password_lama" id="password_lama"
                   class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
        </div>

        <div class="mb-5">
            <label for="password_baru" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Password Baru</label>
            <input type="password" name="password_baru" id="password_baru"
                   class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
        </div>

        <div class="mb-6">
            <label for="konfirmasi_password" class="text-[12px] tracking-[0.15em] uppercase text-[#6B6154] block mb-2">Konfirmasi Password Baru</label>
            <input type="password" name="konfirmasi_password" id="konfirmasi_password"
                   class="w-full px-3 py-2.5 bg-white border border-[#D1C4B0] text-[13px] text-[#2C2620] focus:outline-none focus:border-[#A3492D] focus:shadow-[0_0_0_2px_rgba(163,73,45,0.1)] transition-all">
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit"
                    class="w-full sm:w-auto py-2 px-4 sm:py-2.5 sm:px-6 border border-[#A3492D] bg-[#A3492D] text-white text-[12px] sm:text-[13px] tracking-[0.1em] uppercase hover:bg-[#8B3D25] hover:-translate-y-0.5 shadow-[0_6px_14px_rgba(163,73,45,0.35)] hover:shadow-[0_8px_22px_rgba(163,73,45,0.45)] transition-all">
                Simpan Perubahan
            </button>
            <a href="../pages/logout.php"
               class="w-full sm:w-auto text-center py-2 px-4 sm:py-2.5 sm:px-6 border border-[#D1C4B0] bg-white text-[12px] sm:text-[13px] tracking-[0.1em] uppercase text-[#A3492D] hover:bg-[#F5F0E8] hover:-translate-y-0.5 shadow-[0_4px_10px_rgba(0,0,0,0.14)] hover:shadow-[0_7px_16px_rgba(0,0,0,0.2)] transition-all no-underline">
                Keluar
            </a>
        </div>
    </form>
</div>

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

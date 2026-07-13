# PLAN: Responsive Design - Website Rasa dan Gizi

**Tanggal:** 2026-07-14  
**Status:** Approved - Ready to Execute

## Tujuan
Membuat seluruh halaman website **fully responsive** di mobile (<768px), tablet (768-1024px), dan desktop (>1024px) **tanpa merusak fungsi apapun** yang sudah ada.

## Prinsip Utama
1. **Tidak mengubah warna, tipografi, atau visual design** yang sudah ada
2. **Hanya menambahkan class responsif Tailwind** (`sm:`, `md:`, `lg:`) dan menyesuaikan layout
3. **Semua fungsi tetap 100% berfungsi** (tombol, form, modal, dropdown, JavaScript)
4. **Tidak ada elemen overlap** di ukuran layar manapun
5. **Navbar TIDAK pakai hamburger** — menu tetap tampil, hanya diperkecil dan wrap jika perlu

---

## File yang Akan Diedit (10 file)

1. `partials/navbar.php` - Navbar logged-in
2. `landing.php` - Landing page public
3. `login.php` - Login page
4. `register.php` - Register page
5. `pustaka_gizi.php` - Database bahan dengan tabel
6. `resep/index.php` - Dashboard resep pribadi
7. `resep/detail.php` - Detail resep dengan tabel
8. `resep/edit.php` - Form edit resep
9. `resep/tambah.php` - Form tambah resep
10. `rekomendasi.php` - Search resep publik
11. `resep_by_bahan.php` - Cari resep by ingredient
12. `profil.php` - Edit profil

---

## Perubahan per File

### 1. `partials/navbar.php`
- Container: `flex items-center` → `flex flex-wrap items-center`
- Menu wrapper: `gap-1` → `gap-0.5 md:gap-1`, `flex items-center` → `flex flex-wrap items-center justify-end`
- Link menu: `px-4 py-2` → `px-2 py-1.5 md:px-4 md:py-2`, `text-[13px]` → `text-[11px] md:text-[13px]`
- Logo: `h-14` → `h-12 md:h-14`
- User dropdown: `text-[13px]` → `text-[11px] md:text-[13px]`, tambah `truncate max-w-[100px] md:max-w-none`

### 2. `landing.php`
- Nav: `gap-8` → `gap-4 md:gap-8`, link `text-[12px]` → `text-[11px] md:text-[12px]`
- Hero image: `h-[420px] md:h-[520px]` → `h-[280px] sm:h-[380px] md:h-[520px]`
- Info box: `max-w-[260px]` → `max-w-[90%] sm:max-w-[260px]`, `left-4 bottom-4` → `left-2 bottom-2 sm:left-4 sm:bottom-4`, padding responsif
- Title: `text-4xl md:text-5xl` → `text-3xl sm:text-4xl md:text-5xl`

### 3. `login.php` & `register.php`
- Container: `px-8 py-12` → `px-6 py-8 md:px-8 md:py-12`
- Logo: `h-16` → `h-12 sm:h-16`
- Title: `text-3xl` → `text-2xl sm:text-3xl`
- Button: tambah `w-full`

### 4. `pustaka_gizi.php`
- Title: `text-3xl` → `text-2xl sm:text-3xl`
- Image header: `w-28 h-28` → `w-20 h-20 md:w-28 md:h-28`
- Tabel: tambah `min-w-[600px]` pada `<table>`
- Pagination button: `px-3 py-1.5` → `px-2 py-1 text-[11px] md:px-3 md:py-1.5 md:text-[13px]`
- Pagination wrapper: tambah `flex-wrap gap-1`

### 5. `resep/index.php`
- Header: `flex justify-between` → `flex flex-col sm:flex-row gap-4 sm:gap-0`
- Title: `text-3xl` → `text-2xl sm:text-3xl`
- Button "Tambah": `py-2.5 px-5` → `py-2 px-4 text-[12px] sm:py-2.5 sm:px-5 sm:text-[13px]`, tambah `w-full sm:w-auto`
- Card button: `py-1.5 px-3` → `py-1.5 px-2 text-[12px] md:px-3 md:text-[13px]`
- Modal: `max-w-sm` → `max-w-[90%] sm:max-w-sm`, padding responsif

### 6. `resep/detail.php`
- Title: `text-3xl` → `text-2xl sm:text-3xl`
- Image header: `w-32 h-32` → `w-24 h-24 md:w-32 md:h-32`
- Tabel: tambah `min-w-[500px]`
- Button action: responsif size, tambah `flex-wrap`
- Modal: sama seperti index.php

### 7. `resep/edit.php` & `resep/tambah.php`
- Title: `text-3xl` → `text-2xl sm:text-3xl`
- Image header: `w-24 h-24` → `w-20 h-20 md:w-24 md:h-24`
- Button submit: `py-2.5 px-6` → `py-2 px-4 text-[12px] sm:py-2.5 sm:px-6 sm:text-[13px]`, tambah `w-full sm:w-auto`
- Button "Tambah Bahan": `py-1.5 px-3 text-[11px]` → `py-1.5 px-2 text-[10px] sm:px-3 sm:text-[11px]`

### 8. `rekomendasi.php`
- Title: `text-3xl` → `text-2xl sm:text-3xl`
- Image header: `w-28 h-28` → `w-20 h-20 md:w-28 md:h-28`
- Card title: `text-xl` → `text-lg sm:text-xl`
- Badge: `text-[10px] px-2` → `text-[9px] px-1.5 sm:text-[10px] sm:px-2`

### 9. `resep_by_bahan.php`
- Title: `text-3xl` → `text-2xl sm:text-3xl`
- Checkbox label: `text-[13px]` → `text-[12px] sm:text-[13px]`
- Checkbox grid gap: `gap-3` → `gap-2 sm:gap-3`
- Button submit: responsif, `w-full sm:w-auto`

### 10. `profil.php`
- Title: `text-3xl` → `text-2xl sm:text-3xl` ✅
- Image header: `w-28 h-28` → `w-20 h-20 md:w-28 md:h-28` ✅
- Button submit: responsif, `w-full sm:w-auto` ✅
- Button container: `flex` → `flex-col sm:flex-row` ✅

---

## Eksekusi Plan
- Simpan plan ini ke file ✅
- Edit file satu per satu ✅
- Test manual setiap file di 3 ukuran viewport ⏳
- Tidak commit sampai semua selesai dan di-review user ⏳

---

**Approved by User:** 2026-07-14  
**Status:** ✅ **COMPLETED** - All 12 files implemented (2026-07-14 00:52)  
**Next Step:** Manual testing on mobile/tablet/desktop viewports

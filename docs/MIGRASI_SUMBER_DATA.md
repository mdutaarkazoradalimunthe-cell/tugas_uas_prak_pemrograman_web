# Strategi Migrasi & Integrasi Sumber Data Gizi

## Situasi Saat Ini

Database `bahan_makanan` berisi **1.344 item** dari dataset TKPI (Tabel Komposisi
Pangan Indonesia). Setelah audit, ditemukan **54% baris (805 item)** memiliki
ketidaksesuaian antara kolom `kalori_per_100g` dengan nilai yang dihitung dari
makronutrien (Protein×4 + KH×4 + Lemak×9). Ini sudah diperbaiki via UPDATE SQL.

## Opsi Sumber Data Eksternal

### 1. Panganku Kemenkes (https://panganku.org)

| Aspek | Detail |
|---|---|
| **Cakupan** | ~1.300 bahan makanan Indonesia |
| **Akses** | Website + API (documentation limited) |
| **Keunggulan** | Paling relevan untuk masakan Nusantara |
| **Kekurangan** | API tidak terdokumentasi publik (scraping mungkin diperlukan) |
| **Rekomendasi** | **Primary source** untuk makanan Indonesia |

Dataset Anda kemungkinan besar berasal dari sini.

### 2. USDA FoodData Central (https://fdc.nal.usda.gov)

| Aspek | Detail |
|---|---|
| **Cakupan** | ~7.000+ item (Foundation, SR Legacy, Branded) |
| **Akses** | REST API gratis (rate limit: 1 req/dtk untuk DEMO_KEY, 3600/jam dengan API key) |
| **Keunggulan** | Data terverifikasi ilmiah, update berkala, terdokumentasi baik |
| **Kekurangan** | Makanan Indonesia terbatas, satuan imperial perlu konversi |
| **Rekomendasi** | **Supplementary source** untuk bahan non-Indonesia |

**Cara daftar API key:**
1. Buka https://fdc.nal.usda.gov/api-key-signup.html
2. Isi email dan institusi
3. Set sebagai environment variable: `USDA_API_KEY=your_key`

Script ETL sudah disediakan di `_fetch_usda.py`.

### 3. API Lain (Rekomendasi Alternatif)

| Sumber | Biaya | Cakupan | Catatan |
|---|---|---|---|
| **FatSecret** | Gratis (API key) | 500k+ item | Platform API stabil |
| **Edamam** | Freemium (10k req/bln gratis) | Global + resep | Termasuk analisis resep |
| **Open Food Facts** | Gratis (open data) | 3jt+ item | Crowdsourced, kualitas bervariasi |
| **Nutritionix** | Berbayar | 1jt+ item | Digunakan MyFitnessPal |

## Arsitektur yang Direkomendasikan

```
                    ┌──────────────────────┐
                    │  bahan_makanan_       │
                    │  override             │  ← manual curation
                    │  (id_bahan, nilai,    │    (prioritas tertinggi)
                    │   sumber, alasan)      │
                    └──────────┬───────────┘
                               │ COALESCE
                    ┌──────────▼───────────┐
                    │  bahan_makanan       │  ← data TKPI existing
                    │  (1.344 item)         │    (sudah diperbaiki kalorinya)
                    └──────────┬───────────┘
                               │ FALLBACK
                    ┌──────────▼───────────┐
                    │  usda_nutrition       │  ← data USDA (tabel baru)
                    │  (fdc_id, nama,       │    untuk bahan yang tidak ada
                    │   nilai_per_100g)     │    di TKPI
                    └──────────────────────┘
```

### Flow Query (di `fungsi_gizi.php`)

```sql
SELECT
    -- Kalori: override → TKPI → USDA
    COALESCE(
        bo.kalori_per_100g,
        bm.kalori_per_100g,
        un.kalori_per_100g
    ) AS kalori_per_100g,
    -- ... (sama untuk protein, karbo, lemak)
FROM resep_bahan rb
LEFT JOIN bahan_makanan bm ON rb.id_bahan = bm.id
LEFT JOIN bahan_makanan_override bo ON bo.id_bahan = bm.id
LEFT JOIN usda_nutrition un ON un.id_bahan = bm.id  -- atau mapping terpisah
```

## Langkah Implementasi

### Tahap 1: Perbaikan Data Existing ✅ (SELESAI)
- [x] UPDATE `kalori_per_100g` pakai rumus Atwater (805 baris diperbaiki)
- [x] Safety net di `fungsi_gizi.php` — hitung kalori dari makronutrien langsung

### Tahap 2: Override Manual (PRIORITAS)
- [ ] Buat tabel `bahan_makanan_override`
- [ ] Koreksi manual 3 bahan anomali terkonfirmasi (lihat tabel di bawah)
- [ ] Update `fungsi_gizi.php` untuk JOIN override

### Tahap 3: Integrasi USDA
- [ ] Daftar API key USDA
- [ ] Jalankan `_fetch_usda.py --batch export_bahan.csv`
- [ ] Buat tabel `usda_nutrition`
- [ ] Implementasi fallback di query

### Tahap 4: Integrasi Panganku
- [ ] Eksplorasi API Panganku (atau scraping)
- [ ] Cocokkan dengan dataset existing
- [ ] Update nilai yang lebih akurat

## Anomali Terkonfirmasi (Perlu Verifikasi Manual)

Dari 1.344 bahan, hanya **3 item** dengan nilai gizi tidak wajar secara matematis:

| ID | Nama | Masalah | Nilai Saat Ini | Nilai Wajar (Estimasi) |
|---|---|---|---|---|
| 881 | Kerupuk Udang berpati | KH = 332g per 100g (tidak mungkin) | Kal: 2010, P: 17.2, KH: 332, L: 68.2 | KH ~50-70g |
| 1080 | Opak Singkong | Kalori terlalu tinggi untuk beratnya | Kal: 938, P: 36, KH: 104, L: 42 | Kal ~400-500 |
| 784 | Kacang Telur | KH > 100g per 100g | Kal: 769.6, P: 20.8, KH: 124.8, L: 20.8 | KH ~30-50g |

Sisanya (1.341 bahan) sudah konsisten dengan rumus Atwater setelah UPDATE. Beberapa item seperti minyak murni (Kal=900), gula (KH=95-100), dan lemak hewani memang secara ilmiah memiliki nilai tersebut dan bukan anomali.

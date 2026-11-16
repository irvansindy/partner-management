# Task 9: Tender, EULA, home dan dokumen pendukung

Tanggal: 16 November 2026. Status implementasi: selesai.

## Perubahan

- Halaman tender vendor dan modal pembuatan menggunakan ID/EN untuk label, tab, kebutuhan produk, pilihan jenis, status kosong, notifikasi dan pesan validasi. Moment, Select2 dan DataTable mengikuti locale aktif; nilai pilihan yang dikirim tidak diterjemahkan.
- Halaman EULA, tabel, modal dan notifikasi menggunakan kamus bahasa. Toolbar editor memakai locale Indonesia bawaan Summernote yang sudah tersedia di proyek. Nama dan isi perjanjian tetap merupakan konten bisnis asli.
- Home, detail perusahaan pada `/detail-partner`, modal informasi/alamat/bank/pajak, lampiran dan aktivitas menggunakan teks lokal. Tanggal aktivitas mengikuti ID/EN; deskripsi log dan nama file dipertahankan.
- Form dokumen pendukung, baris dinamis, validasi unggahan, pesan duplikat, hasil upload/update dan respons backend mengikuti bahasa aktif. Batas unggahan dan pemeriksaan keselarasan baris tetap dipertahankan.
- Literal JavaScript menggunakan JSON, dan terjemahan dalam HTML dinamis di-escape. Fallback respons non-JSON menggunakan pesan aplikasi lokal. Detail exception dicatat di log.
- Regresi menemukan metadata statis pada `FormatResponseJson`: sukses setelah error dapat mewarisi status HTTP error. Respons sukses kini mereset code/status serta request ID lama; tes urutan error–success ditambahkan.

## Verifikasi

- **237 tes lulus** pada suite terkait Task 2–9, termasuk regresi form publik, unggahan dokumen, validasi, keamanan dan idempotensi.
- Tes Task 9 memeriksa kesamaan key, atribut validator, respons 422 tender/dokumen, penyimpanan EULA tanpa mengubah nama/isi bisnis dan pergantian bahasa pesan API.
- Home, form lampiran, detail perusahaan, EULA dan tender dirender dalam ID/EN tanpa raw translation key. Semua blok JavaScript inline hasil render kelima halaman lolos `node --check`.
- Sintaks PHP, kompilasi Blade dan `git diff --check` lulus. Peringatan deprecation dependensi pada PHP 8.5 tidak menggagalkan tes.
- Pengujian database memakai SQLite sementara atau resolver/mock; tidak mengubah database server dan tidak mengirim dokumen keluar.

## Batas pemeriksaan

Interaksi browser, toolbar editor secara visual, pagination/Select2 dan submit aktual pada server diteruskan ke Task 10. Perubahan ini melokalkan alur yang ada; tidak menambahkan alur edit tender yang belum lengkap atau mengubah pemetaan data tender. `company.index` tidak menjadi target karena tidak ditemukan route aktif ke method index tersebut; `/detail-partner` tetap dicakup. Konten EULA, deskripsi aktivitas dan data bisnis tidak diterjemahkan otomatis.

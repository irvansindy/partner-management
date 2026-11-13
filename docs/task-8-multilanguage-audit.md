# Task 8: Form publik, form internal dan autentikasi

Tanggal: 13 November 2026. Status implementasi: selesai.

## Perubahan

- Form publik dan create partner internal menggunakan kamus ID/EN untuk label, survey, dropdown wilayah, kontrol peta, aksesibilitas, plugin unggahan dan pesan validasi. Nilai database, nama wilayah dan data pengguna tetap dipertahankan.
- Pesan validasi backend, atribut field, persyaratan HTTPS, lampiran, pilihan Other dan respons JSON mengikuti locale aktif. Detail exception tetap dicatat di log; pengguna menerima pesan aplikasi.
- Halaman login, validasi autentikasi, katalog password dan notifikasi email menggunakan bahasa aktif. Route register, reset dan konfirmasi password yang sebelumnya dinonaktifkan tetap tidak diaktifkan.
- Halaman success/closed diperiksa dalam kedua bahasa. Judul form, tanggal kedaluwarsa dan loading global menggunakan terjemahan yang sesuai.
- Sintaks Blade untuk daftar label wizard internal diperbaiki agar halaman dapat dirender. Penyimpanan draft, posisi wizard, GPS, unggahan dan aturan keamanan tidak dilonggarkan.

## Verifikasi

- Suite terkait menghasilkan **228 tes lulus**, mencakup regresi Task 2–7 serta form publik, alamat, draft, HTTPS, lampiran, idempotensi dan keamanan.
- Tes baru memeriksa kesamaan key dan placeholder, pesan validasi publik/internal/autentikasi, respons JSON 422 dan render form publik/internal, login, success, closed serta verifikasi email dalam ID/EN.
- Sebanyak 24 blok JavaScript hasil render diperiksa menggunakan `node --check`. Sintaks PHP, kompilasi Blade dan `git diff --check` diperiksa.
- Tes menggunakan resolver database atau mock sesuai kebutuhan. Tidak ada submit registrasi ke database server maupun email keluar yang dikirim.
- Peringatan deprecation dependensi Laravel pada PHP 8.5 tidak menggagalkan tes.

## Batas pemeriksaan

Interaksi browser/device, izin GPS, pergantian bahasa pada draft aktual, tampilan plugin unggahan dan respons 413 dari proxy server belum diuji langsung; diteruskan ke Task 10. Validator dan katalog untuk alur autentikasi yang dinonaktifkan diuji tanpa mengaktifkan route tersebut. Tidak ada klaim pengujian produksi.

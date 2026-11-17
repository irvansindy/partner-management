# Portable Database Seeding Implementation Plan

**Goal:** Menjalankan `db:seed` di laptop baru mencakup seluruh data master dasar dan kode pos, serta dapat diulang tanpa menghapus data atau mereset akun.

**Architecture:** Gunakan seeder yang sudah tersedia dengan pencarian berdasarkan nama/identifier logis, bukan ID tetap. Snapshot CSV wilayah dipin dan dibundel bersama lisensi; importer yang ada dipanggil offline. Data transaksi dan template approval yang bergantung pengguna aktual tidak direkayasa oleh seeder.

**Tech Stack:** Laravel 8, Spatie Permission, SQLite untuk tes, CSV Valtera dan pelengkap kodepos-id.

## Tasks

- [x] Lengkapi urutan DatabaseSeeder dan jadikan seeder master/akun/menu aman diulang; pertahankan ID serta konfigurasi yang telah ada.
- [x] Tambahkan seeder wilayah offline dengan validasi snapshot sebelum penulisan, metadata sumber dan lisensi.
- [x] Uji seed dua kali pada database sementara, akun/password yang sudah diubah, ID yang bergeser, permission/menu serta kode pos; tulis panduan pindah laptop.

## Verifikasi

Seluruh 290 tes lulus. Tes seeder menggunakan SQLite sementara, termasuk dua kali seed snapshot lengkap, checksum CSV, akun yang telah diubah, dan kegagalan sumber tanpa penulisan parsial. Database aplikasi tidak dijalankan seed selama pengujian.

## Batas

- Tidak menjalankan seeder pada database pengguna.
- Tidak memasukkan ActivityLogDemoSeeder atau MasterApprovalSeeder lama yang memiliki referensi ID pengguna tetap.
- Seeder tidak menggantikan backup database, file upload dan APP_KEY untuk memindahkan data aktual.

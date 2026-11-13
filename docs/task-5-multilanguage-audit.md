# Task 5: Pengaturan dan organisasi

Tanggal: 13 November 2026. Status implementasi: selesai.

## Cakupan

Halaman aktif `/user-manage`, `/menu-setting`, `/role-permission`, `/office-setting`, `/department-setting`, `/division-setting`, `/ip-whitelist`, modal terkait, sidebar/topbar, controller CRUD masing-masing, serta kedua FormRequest divisi. Controller role aktif adalah `RoleAndPermissionController`; `RolePermissionController` lama tidak dipakai route ini.

## Perubahan

- Teks halaman yang tersisa, judul modal dinamis, placeholder, tooltip, aria-label, dialog hapus dan fallback notifikasi menggunakan locale ID/EN.
- DataTable memakai pesan lokal untuk panjang halaman, pencarian, loading, empty, info, filter, pagination dan sortir. Grid menu/divisi dan Select2 dilengkapi pesan lokal yang masih memakai default paket.
- Pesan CRUD, nama atribut dan fallback validasi backend diterjemahkan. Validasi batas ukuran memakai struktur nested yang didukung Laravel.
- Exception validasi role tetap dikembalikan sebagai error per kolom dengan HTTP 422. Pesan custom validasi pembuatan menu diselaraskan dengan nama field sebenarnya.
- Exception tak terduga dicatat ke log dan menampilkan pesan lokal umum, tanpa detail teknis database.
- Sidebar menerjemahkan nama menu bawaan saat render. Nama menu khusus tetap ditampilkan sebagaimana diisi pengguna; URL, permission, role dan deteksi menu aktif tidak diubah.
- Label Guest, Logout dan navigasi topbar memakai kamus. Identitas pengguna dan nama role/permission tetap merupakan data, bukan teks yang diterjemahkan otomatis.

## Verifikasi

- `APP_CONFIG_CACHE=/tmp/partner-no-config-cache.php php artisan test --filter='SettingsLocalization|Division|UserManagement|Menu|Office|RolePermission|Dashboard|Partner|Approval|FormLink|DataTableAction'`: 96 tes lulus.
- Tes baru memeriksa kesamaan key/placeholder, render ketujuh halaman beserta modal ID/EN, validasi CRUD kosong, FormRequest divisi, fallback error, serta sidebar bawaan/custom dengan URL yang dipertahankan.
- Render menu yang mengambil daftar role langsung di Blade menggunakan connection pengujian tanpa baris; tidak melakukan query database server.
- Ketujuh berkas JavaScript hasil render dalam dua bahasa (14 berkas) lolos `node --check`.
- Blade berhasil dikompilasi, sintaks PHP yang diubah diperiksa, dan `git diff --check` bersih.
- Tes sinkronisasi menu/permission, divisi, serta regresi Task 2–4 tetap dijalankan dan lulus.

## Batas pemeriksaan

Belum dilakukan interaksi browser atau penyimpanan CRUD pada database server. Pemeriksaan klik modal, Select2, Grid/DataTable, notifikasi, menu aktif dan pergantian bahasa diteruskan ke Task 10. Template backup dan form partner internal di folder users tidak termasuk Task 5; form partner internal tetap masuk Task 8. Peringatan deprecation dependensi Laravel pada PHP 8.5 tidak menggagalkan tes.

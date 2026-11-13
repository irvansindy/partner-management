# Task 7: System Maintenance

Tanggal: 13 November 2026. Status implementasi: selesai.

## Perubahan

- Label dan deskripsi seluruh 22 operasi serta lima kelompok menggunakan kamus ID/EN. Identifier operasi, nama perintah Artisan, opsi konfirmasi dan batas waktu tetap sama.
- Tombol jalankan, konfirmasi dengan placeholder nama operasi, atribut aksesibilitas hasil terakhir dan pesan validasi command mengikuti locale aktif.
- Hasil tanpa output, kegagalan aplikasi dan status sedang berjalan menggunakan pesan lokal. Metadata pesan aplikasi memungkinkan hasil flash diterjemahkan ulang saat bahasa berganti.
- Label hasil terakhir diambil kembali dari katalog sesuai locale saat halaman dibuka. Output teknis Artisan dipertahankan, dibersihkan dari karakter kontrol, dibatasi panjangnya, dan tetap di-escape di Blade.
- Allowlist, pembatasan akses super-admin, route POST, CSRF, lock eksekusi dan pencatatan log dipertahankan.

## Verifikasi

- `APP_CONFIG_CACHE=/tmp/partner-no-config-cache.php php artisan test --filter='SystemMaintenance|DataToolsLocalization|Import|Export|Localization|Division|UserManagement|Menu|Office|RolePermission|Dashboard|Partner|Approval|FormLink|DataTableAction'`: 124 tes lulus.
- Tes baru memeriksa key/placeholder, kestabilan katalog dan opsi operasinya, pesan no-output/busy/gagal, validasi command, render halaman ID/EN, perubahan locale pada flash result, serta escaping dan pelestarian output teknis.
- Eksekusi Artisan dan lock menggunakan mock dalam tes. Tidak ada perintah maintenance sebenarnya yang dijalankan oleh tes.
- JavaScript konfirmasi hasil render ID/EN lolos `node --check`; Blade terkompilasi, sintaks service/controller dan `git diff --check` lulus.
- Tes akses, provisioning menu dan regresi Task 2–6 tetap lulus. Peringatan deprecation dependensi Laravel pada PHP 8.5 tidak menggagalkan tes.

## Batas pemeriksaan

Belum dilakukan interaksi browser atau eksekusi operasi maintenance pada server. Pemeriksaan konfirmasi, penolakan konfirmasi, klik ganda, hasil eksekusi dan perubahan bahasa di browser diteruskan ke Task 10. Output mentah Artisan memang tidak diterjemahkan karena merupakan informasi diagnosis.

# Menyiapkan proyek di laptop baru

`DatabaseSeeder` kini menyiapkan data dasar secara offline dan dapat dijalankan ulang. Seeder tidak menyalin data aktual dari laptop lama.

## Database baru

1. Salin/clone proyek beserta `database/data/regions`. Pasang dependensi Composer dan siapkan database sesuai `.env`.
2. Untuk instalasi baru, siapkan `APP_KEY` melalui `php artisan key:generate`. Jika memulihkan database lama, pertahankan `APP_KEY` lama.
3. Jalankan:

```bash
php artisan config:clear
php artisan migrate --seed
php artisan storage:link
```

Jika memakai Docker, jalankan perintah Artisan di container PHP yang sesuai.

Untuk menambahkan data master yang belum ada pada database yang sudah dimigrasi:

```bash
php artisan db:seed
```

Tidak perlu download kode pos dari GitHub. Snapshot beserta sumber/lisensinya tersedia pada `database/data/regions` dan importer mempertahankan ID wilayah yang sudah ada.

## Data dasar yang disiapkan

- Provinsi, kabupaten/kota dan kode pos.
- Role, permission, permission dasar role dan menu, termasuk Division, My Approvals serta System Maintenance.
- Kantor, level jabatan, jabatan, divisi dan departemen.
- Klasifikasi usaha, kategori dokumen, master laba rugi dan neraca.
- Delapan akun awal dari `UserSeeder`, dengan kantor dicari berdasarkan nama, bukan ID tetap.

Password hanya diterapkan saat akun baru dibuat. Nilai awal mengikuti `SEED_USER_PASSWORD` di `.env`, dengan fallback yang sama seperti seeder sebelumnya (`pass12345`). Jika mengubah variabel ini, bersihkan config cache sebelum seeding. Seed ulang tidak mereset password, nama, kantor atau role akun yang sudah ada.

Permission dasar ditambahkan tanpa mencabut permission tambahan. Super-admin mendapatkan seluruh permission web; admin/super-user mendapatkan dashboard, Partner List, form publik, export/import dan inbox approval. Pembatasan route tetap berlaku. Master/menu yang sudah ada dipertahankan; URL Partner List lama diselaraskan dengan route aktif. Nama wilayah resmi mengikuti snapshot importer.

Departemen Finance terkait FAT. Penempatan organisasi pengguna, relasi departemen/divisi selain default yang sudah tersedia, PIC/GM dan template approval harus diisi sesuai organisasi sebenarnya. Seeder tidak menebak penanggung jawab bisnis.

`ActivityLogDemoSeeder` tidak dipanggil karena berisi transaksi demo. `MasterApprovalSeeder` lama tidak dipanggil karena bergantung pada ID/nama approver yang belum tentu tersedia. Template approval aktual dipindahkan melalui backup database atau dikonfigurasi lewat aplikasi.

## Memindahkan data aktual

Jika ingin isi laptop baru sama dengan laptop lama, bawa:

- Backup SQL database: submission, pengguna, organisasi, form link, approval, konfigurasi, EULA dan data bisnis lainnya.
- `.env` beserta `APP_KEY` lama. Sesuaikan host/kredensial database dan URL lokal di laptop baru.
- File pada `storage/app`, termasuk `storage/app/private/company_attachments` dan `storage/app/public`.
- File upload lama pada `public/storage/uploads` atau direktori upload lain yang benar-benar digunakan instalasi lama. Jika `public/storage` merupakan symlink, salin target datanya.

Pulihkan database dan file, lalu jalankan `php artisan migrate`, `php artisan config:clear`, serta `php artisan storage:link` bila symlink belum ada. `db:seed` dapat digunakan untuk melengkapi default yang belum ada, tetapi bukan syarat untuk memulihkan data aktual. Jangan gunakan `migrate:fresh` pada database yang ingin dipertahankan.

## Verifikasi perubahan

Pengujian menggunakan SQLite sementara, tidak menjalankan seeder pada database pengguna. Tes mencakup seed dua kali, ID yang bergeser, parent menu, permission, kode pos, password/role yang sudah diubah dan kegagalan snapshot tanpa penulisan master sebagian. Pengujian ini tidak menyatakan seluruh migrasi historis sudah diuji pada server MySQL laptop baru.

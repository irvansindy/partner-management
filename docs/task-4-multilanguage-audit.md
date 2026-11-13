# Task 4: Dashboard dan Partner List

Tanggal: 13 November 2026. Status implementasi: selesai.

## Cakupan dan perubahan

- Dashboard aktif: `admin.dashboard.index_live`, `live_js` dan endpoint pada `DashboardController`. Label grafik dan popup yang sudah menggunakan `messages.Dashboard …` dipertahankan serta diperiksa key dan placeholder ID/EN.
- Label status dari API ditampilkan sesuai locale tanpa mengubah enum database. Kontrol zoom, penutupan popup, teks alternatif marker, DataTable, error pemuatan dan format angka dashboard mengikuti locale.
- Pesan hasil/empty/error endpoint diterjemahkan setelah data diambil dari cache. Cache tetap berisi data mentah; scope pengguna, kantor dan departemen tetap dipertahankan.
- Partner List: judul, deskripsi, aksi ekspor, tambah mitra, judul modal, tombol tutup, loading/error dan DataTable menggunakan kamus ID/EN. Nama perusahaan dan nilai data pengguna tetap di-escape saat ditampilkan.
- Modal memakai panel submission bersama dengan `showApproval => false`. Respons tanpa panel detail menampilkan error lokal dan tidak menjalankan fallback form lama.
- Ekspor Excel/PDF: judul, header, jenis mitra, empty state, label pemilik kosong, nama file dan keterangan kerahasiaan mengikuti locale. Header kolom terakhir disesuaikan menjadi Website karena data yang diekspor memang `website_address`.
- PDF memakai direktori sementara mPDF di storage aplikasi seperti ekspor submission.
- Kegagalan query dicatat ke log; respons pengguna berisi pesan lokal tanpa detail exception database.

## Verifikasi

- `APP_CONFIG_CACHE=/tmp/partner-no-config-cache.php php artisan test --filter='Dashboard|Partner|Approval|FormLink|DataTableAction'`: 74 tes lulus.
- Tes baru memeriksa kesamaan key/placeholder, render Dashboard dan Partner List beserta modal dalam ID/EN, template PDF berisi/kosong, header dan jenis mitra Excel, pesan setelah pembacaan cache, parameter provinsi wajib, serta error backend tanpa detail SQL.
- Tes isolasi cache berdasarkan pengguna/scope dan distribusi provinsi tetap lulus. Tes Task 2/3 juga tetap lulus.
- JavaScript Dashboard dan Partner List hasil render ID/EN lolos `node --check`.
- `php artisan view:cache`, sintaks kedua controller dan `git diff --check` lulus.

## Batas pemeriksaan

- Belum dilakukan pengujian interaksi browser, query dengan data server, download Excel melalui HTTP, atau pemeriksaan visual layout cetak PDF. Verifikasi tersebut diteruskan ke Task 10.
- Template Dashboard lama (`index`, `dashboard_js`) dan form detail Partner List lama tidak termasuk tampilan aktif. Handler pembuatan/form lama dalam `partner_management_js` yang tidak memiliki kontrol pada halaman aktif tidak dihitung sebagai UI Task 4; form create partner internal tetap masuk Task 8.
- Endpoint/alur persetujuan lama pada `PartnerManagementController` tidak dipakai modal detail aktif; Task 4 tidak mengubah alur keputusan atau aturan aksesnya.
- Peringatan deprecation PHP 8.5 dari dependensi Laravel masih muncul; tidak menggagalkan tes.

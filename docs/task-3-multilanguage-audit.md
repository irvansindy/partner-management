# Task 3: Approval dan pengaturan approval

Tanggal: 13 November 2026. Status implementasi: selesai.

## Perubahan

- Daftar proses, My Approvals, detail/riwayat dan formulir keputusan menggunakan locale aktif untuk label, status, progres, tanggal, durasi, placeholder dan atribut aksesibilitas.
- Dialog SweetAlert untuk menyetujui/menolak, informasi FAT–Finance, catatan dan pesan backend menggunakan ID/EN.
- Pengaturan approval mencakup daftar DataTable, Select2, modal pembuatan templat, pengaturan tahap/PIC, loading, toast, error dan validasi.
- Status API menyertakan `is_active` untuk logika warna badge; label status diterjemahkan secara terpisah. Nilai enum dan struktur tahap tetap sama.
- Pesan akses, validasi dan exception domain service diterjemahkan. Kegagalan keputusan yang tidak terduga dicatat ke log dan menampilkan pesan lokal tanpa detail exception teknis.
- Validasi pemuatan approver dilakukan sebelum blok penanganan kegagalan server agar invalid request tidak berubah menjadi error 500.
- Pesan batas nilai menggunakan struktur nested sesuai validator Laravel. Perbaikan format yang sama diterapkan pada `submission_validation` dari Task 2 setelah masalah ditemukan melalui tes runtime.
- Catatan persetujuan pada detail di-escape sebelum ditampilkan.

## Verifikasi

- `APP_CONFIG_CACHE=/tmp/partner-no-config-cache.php php artisan test --filter='Approval|FormLink|DataTableAction'`: 60 tes lulus.
- Tes baru memeriksa kesamaan key/placeholder rekursif, render lima halaman beserta modal dan dialog ID/EN, tanggal, status, pesan validasi nested/batas kredit, respons validasi controller dan guard service.
- Tes yang ada untuk akses, alur paralel, FAT–Finance, pengaturan modal dan Task 2 tetap lulus.
- JavaScript pengaturan dan dialog keputusan hasil render ID/EN lolos `node --check`.
- Blade berhasil dikompilasi; sintaks controller/service dan `git diff --check` lulus.
- Peringatan deprecation PHP 8.5 pada dependensi Laravel tetap muncul dan tidak menggagalkan tes.

## Batas pemeriksaan

Belum dilakukan pengujian interaksi browser atau transaksi approve/reject pada database server. Pemeriksaan klik modal, pencarian, toast, pergantian bahasa dan alur keputusan dengan data server diteruskan ke Task 10. Tidak ada klaim pengujian browser atau perubahan data produksi.

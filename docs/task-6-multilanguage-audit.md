# Task 6: Ekspor, impor dan kode pos

Tanggal: 13 November 2026. Status implementasi: selesai.

## Perubahan

- Form ekspor, pilihan kategori/kolom, toggle-all, jumlah kolom terpilih, validasi, loading dan notifikasi memakai ID/EN.
- Unduhan ekspor memeriksa status serta tipe konten respons, lalu menyiapkan file dari blob. Notifikasi “siap diunduh” muncul setelah file diterima. Timer yang sebelumnya mengklaim file berhasil diunduh setelah tiga detik dihapus; error jaringan atau redirect HTML tidak dianggap sukses.
- Form impor, modal petunjuk, warnings/tips, preview berisi/kosong/error, tombol konfirmasi, pesan sesi/file hilang dan hasil impor menggunakan locale aktif.
- Preview menampilkan label kolom lokal. Header teknis spreadsheet dan CSV input serta enum vendor/customer tetap sama agar kompatibel dengan parser dan alur ekspor-impor ulang.
- Template XLSX menggunakan catatan header, instruksi sheet, prompt dan pesan kesalahan dropdown ID/EN. Data contoh dan formula vendor/customer tetap merupakan data, bukan label terjemahan.
- PDF memakai label kolom, tanggal, empty state, jenis perusahaan dan keterangan kerahasiaan lokal. Log tetap menyimpan exception teknis; respons pengguna menampilkan pesan lokal tanpa detail database.
- Error validasi per baris pada importer diterjemahkan dan tetap ditampilkan ketika proses impor melempar exception validasi.
- Impor kode pos mencakup label/form, validasi file, ringkasan hasil dan alasan penolakan. Hasil dengan baris ditolak memakai tampilan warning. Kode alasan disimpan bersama teks sehingga CSV error mengikuti bahasa saat diunduh; header teknis laporan tetap dipertahankan.
- Preview error tidak lagi memasang handler pada form konfirmasi yang tidak ada. Pemilihan nama file menggunakan text(), bukan HTML.

## Verifikasi

- `APP_CONFIG_CACHE=/tmp/partner-no-config-cache.php php artisan test --filter='DataToolsLocalization|Import|Export|Localization|Division|UserManagement|Menu|Office|RolePermission|Dashboard|Partner|Approval|FormLink|DataTableAction'`: 113 tes lulus.
- Tes baru memeriksa key/placeholder, render halaman ID/EN, preview berisi/kosong/error, ringkasan impor kode pos, validasi file/kolom, error per baris, sesi habis dan CSV error pada locale aktif.
- Workbook XLSX benar-benar dibuat lalu dibaca kembali; header mesin, catatan kolom, judul sheet instruksi dan formula dropdown diperiksa. PDF dihasilkan melalui mPDF dalam kedua bahasa.
- `node tests/js/company-export-download.test.cjs`: lulus untuk ID/EN, mencakup tanpa kolom, respons belum selesai, respons file sukses, redirect HTML, kegagalan jaringan dan pemulihan tombol.
- Enam hasil render JavaScript (tiga template × dua bahasa) lolos `node --check`; Blade terkompilasi, sintaks PHP diperiksa dan `git diff --check` bersih.
- Tes impor kode pos dengan SQLite serta regresi Task 2–5 tetap lulus. Peringatan deprecation dependensi pada PHP 8.5 tidak menggagalkan tes.

## Batas pemeriksaan

Belum dilakukan pengujian interaksi browser, unduhan melalui server produksi, pemeriksaan visual PDF atau pembukaan workbook dalam aplikasi Excel. Pengujian tersebut diteruskan ke Task 10. Tidak ada perubahan data produksi. Template/sheet export yang tidak dipanggil alur aktif tidak diubah.

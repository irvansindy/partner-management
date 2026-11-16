# Task 10: Verifikasi integrasi lanjutan

Tanggal: 16 November 2026. Melanjutkan batas pengujian pada `task-10-multilanguage-audit.md`.

## Hasil yang diselesaikan

| Area | Bukti |
| --- | --- |
| Persistensi approval | SQLite nyata dengan model, observer, transaksi dan `ApprovalService`, tanpa mock service/database. Observer membuat proses sekali; progres berjalan 0/3 sampai 3/3 dan status perusahaan menjadi approved. |
| Penolakan dan akses | Aktor yang salah ditolak tanpa mengubah step; approval ulang tidak memajukan proses dua kali; alasan penolakan tersimpan dan submission tidak dapat diedit setelah penolakan atau aktivasi tahap akhir. |
| Edit submission dan file | Controller, validator, service dan database memperbarui nama/NPWP, menghapus record/file lama serta menyimpan file pengganti. Filesystem memakai disk pengujian terisolasi. |
| Maintenance | Operasi diagnostik `queue:failed` benar-benar dieksekusi melalui service dalam ID/EN, exit code 0. Database failed jobs adalah SQLite pengujian, bukan antrean produksi. Tidak menjalankan perintah maintenance destruktif. |
| Email aplikasi | Notifikasi reset dirender dan diserahkan ke transport mail array lokal dalam ID/EN. Subject, body dan penerima sintetis diperiksa. Route reset yang dinonaktifkan tidak diaktifkan; URL aksi khusus hanya berlaku selama tes. |
| GPS browser | Chromium Geolocation API dengan koordinat emulasi -6.2, 106.8 mengisi input koordinat; penolakan izin menampilkan pesan lokal. Geocoding memakai respons fixture. |
| Draft alamat | Checkbox alamat operasional yang tidak dicentang tetap tidak dicentang setelah reload dan section operasional tetap terlihat setelah pemulihan draft selesai. |
| HTTP 413 | File PDF sintetis sekitar 2 MB dikirim sebagai multipart melalui relay lokal ke PHP dengan `post_max_size=1M`. Request 2.102.250 byte mendapat status 413 nyata; SweetAlert menampilkan pesan ID/EN. Ini bukan pengujian Nginx produksi. |
| PDF | Submission ID/EN dibuat dengan mPDF dan dirasterisasi memakai Poppler untuk inspeksi visual. Contoh submission menghasilkan dua halaman; deklarasi/tanda tangan berlanjut pada halaman kedua. Ekspor 160 perusahaan menghasilkan lima halaman dengan header tabel berulang dan baris terakhir tetap tersedia. |
| Workbook | XLSX ID/EN dibuat, dibaca kembali melalui PhpSpreadsheet, lalu sheet petunjuk dirender ke HTML untuk inspeksi visual. Ini tidak menggantikan pengujian pada Excel/LibreOffice asli. |

## Perbaikan dari pengujian lanjutan

1. PDF menambahkan satuan hari secara paksa pada semua termin pembayaran, sehingga `30 days` menjadi `30 days days` dan COD mendapat satuan yang salah. Satuan lokal sekarang hanya ditambahkan jika nilai termin berupa angka. Nilai teks bisnis dipertahankan. Tes regresi mencakup angka, teks, COD dan nilai kosong dalam kedua bahasa.
2. Plugin unggahan menonaktifkan input selama preview dibaca. Input yang dinonaktifkan tidak disertakan oleh `FormData`; submit terlalu cepat dapat kehilangan lampiran. Submit kini ditahan ketika input masih sibuk dan pengguna mendapat pesan ID/EN untuk menunggu. Skenario browser memakai state sibuk terkontrol untuk memeriksa bahwa tidak ada request yang dikirim; setelah siap, request multipart tetap berjalan dan penanganan 413 tetap lulus.

## Verifikasi

- Suite lengkap: **286 tes lulus**, 6,75 detik pada run akhir.
- `WorkflowPersistenceTest`: enam pengujian integrasi baru untuk approval, rollback/aktor/replay, rejection, edit/lampiran, maintenance dan mail transport lokal.
- `FormLinkLocalizationTest`: regresi format termin pembayaran PDF.
- Sepuluh skenario browser tambahan lulus: lima skenario per locale untuk GPS diizinkan, GPS ditolak, checkbox alamat, submit saat file sibuk dan HTTP 413.
- Render Blade dan JavaScript inline dalam ID/EN, kompilasi Blade serta `git diff --check` lulus. Deprecation dependensi pada PHP 8.5 tidak menggagalkan suite.
- Pengujian memakai skema SQLite sintetis, data QA, storage pengujian dan server lokal sementara. Ini tidak membuktikan migrasi atau perilaku locking MySQL produksi.
- Harness browser terbatas mencatat 404 untuk resource/endpoint yang tidak dilayani dan HTTP 413 yang sengaja dipicu; tidak diklaim sebagai console bersih untuk seluruh integrasi server. Penilaian lulus mengacu pada state dan request yang diperiksa di tabel.

## Artefak lokal

- `/tmp/release-full-tests-verified.log`: suite lengkap.
- `/tmp/release-browser-verified.log`: hasil browser akhir.
- `/tmp/partner-release-output/browser.json`: sepuluh skenario dan status 413.
- `/tmp/partner-release-output/submission-{id,en}.pdf`: PDF submission contoh.
- `/tmp/partner-release-output/companies-{id,en}.pdf`: ekspor 160 perusahaan.
- `/tmp/partner-release-output/template-{id,en}.xlsx`: workbook template.
- `/tmp/partner-release-output/instructions-{id,en}.png`: hasil render sheet petunjuk.
- `/tmp/partner-release-output/upload413-{id,en}.png`: pesan unggahan terlalu besar.

## Masih memerlukan lingkungan pengguna

- CRUD/approval dan otorisasi melalui HTTP pada database MySQL staging, termasuk konkurensi dan migrasi sebenarnya.
- Batas ukuran Nginx/FastCGI/PHP yang benar-benar terpasang pada server; relay lokal tidak membuktikan konfigurasi produksi.
- SMTP eksternal sampai ke inbox penerima. Transport lokal hanya membuktikan komposisi dan penyerahan pesan di aplikasi.
- Akurasi/izin GPS perangkat fisik, browser perangkat lain dan pembukaan workbook di aplikasi spreadsheet asli.

Docker daemon lokal tidak tersedia saat diperiksa. Ada konfigurasi SSH lokal, tetapi belum ada identifikasi/konfirmasi host mana yang merupakan staging. URL staging dan alias SSH yang sesuai sudah ditanyakan; tidak mencoba koneksi ke host yang belum diidentifikasi dan tidak mengubah data produksi.

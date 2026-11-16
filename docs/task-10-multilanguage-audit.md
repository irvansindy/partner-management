# Task 10: Audit akhir ID/EN

Tanggal: 16 November 2026. Status: audit implementasi dan QA lokal selesai. Bukan sertifikasi produksi.

## Temuan dan perbaikan

| Temuan | Perbaikan dan bukti |
| --- | --- |
| Katalog validasi bawaan Laravel dan pagination Indonesia belum tersedia | Menambahkan `lang/id/validation.php` dan `pagination.php`; pesan framework kini mengikuti locale. Kesamaan key/placeholder dan validator diuji. |
| Key dinamis `admin.Customer` tampil mentah | Menambahkan label Customer/Vendor ID/EN dan memakai label tersebut pada form pembuatan tautan; regresi render kini mendeteksi raw key admin. |
| Teks campuran pada keterangan jenis registrasi dan batas unggahan | Menggunakan `public_form.registration_type_hint` dengan placeholder `:type`, label jenis lokal dan `upload_hint`; enum/payload serta batas file tetap sama. |
| Teks sisa pada handler lama yang dimuat Partner List | Label alamat, ringkasan finansial, notifikasi dan pilihan Other memakai katalog yang sudah ada. Terjemahan HTML dinamis di-escape. |
| Label navigasi layout generik belum ada di JSON locale | Melengkapi Login/Register/Logout/Toggle navigation tanpa mengaktifkan route yang dinonaktifkan. |
| Halaman lampiran memanggil plugin registrasi yang tidak dimuat | Inisialisasi fileinput hanya dilakukan jika elemen registrasinya ada; halaman lampiran tidak lagi mengalami exception JavaScript. |
| Toolbar EULA tetap Inggris dalam locale Indonesia | Menginisialisasi editor dengan locale sebelum pemanggilan reset pertama. Browser membuktikan tooltip Tebal/Bold sesuai bahasa. Isi perjanjian tetap dipertahankan. |

## Verifikasi otomatis

- `APP_CONFIG_CACHE=/tmp/partner-no-config-cache.php php artisan test`: **279 tes lulus**, 7,34 detik pada run akhir.
- `MultilanguageAuditTest` memeriksa seluruh katalog PHP aplikasi dan JSON ID/EN, kesamaan key serta placeholder Laravel/plugin/DataTable. Pengujian framework mencakup required, numeric, tanggal dan pagination.
- Pemindaian 1.505 key terjemahan literal pada view/controller/service/request/rule tidak menemukan key yang hilang. Konkatenasi dinamis diperiksa melalui render dan browser; hasil regex bukan satu-satunya dasar kesimpulan.
- Sebanyak 35 halaman dirender dalam dua bahasa. Seluruh blok JavaScript inline hasil render diperiksa dengan `node --check`; Blade terkompilasi dan `git diff --check` lulus.
- PDF mPDF dan workbook tetap termasuk tes regresi Task 2/6. Tidak ada klaim inspeksi visual seluruh PDF/workbook pada aplikasi pembaca.
- Deprecation dependensi Laravel pada PHP 8.5 tidak menggagalkan tes.

## Pemeriksaan browser

Plugin Browser dan Playwright tidak tersedia. Pengujian menggunakan Chromium headless yang sudah terpasang dan Puppeteer dari cache lokal; tidak menambah dependensi proyek.

Login dijalankan melalui Laravel lokal `http://localhost:8084/login`, dengan database SQLite terisolasi. Pergantian ID → EN → ID serta reload membuktikan persistensi locale dan perubahan label/password/title.

Halaman admin dan publik menggunakan HTML hasil render view aplikasi di `http://localhost:8085`, aset aplikasi asli dan respons API fixture. Ini menguji DOM, JavaScript dan interaksi UI tanpa mengubah data server. Initial run mengalami CORS akibat aset lintas port dan mock wilayah yang tidak sesuai kontrak; harness diperbaiki ke origin yang sama dan array wilayah asli sebelum hasil akhir dicatat.

| Kelompok | Halaman yang diperiksa dalam ID/EN |
| --- | --- |
| Form Links | index, create, edit, show, submissions, detail submission, edit submission |
| Approval | index, my pending, show, action dan approval setting |
| Pengaturan | users, menu, role/permission, office, department, division, IP whitelist |
| Dashboard dan partner | dashboard aktif, Partner List |
| Data tools | export, import, preview import, impor kode pos |
| Maintenance | halaman katalog operasi; tidak mengeksekusi perintah |
| Form dan dokumen | home, detail perusahaan, supporting documents, EULA, tender, form publik, form internal, success, closed |

Hasil akhir: **70 render browser**, tanpa exception JavaScript, console error aplikasi, halaman kosong atau raw translation key pada DOM yang diperiksa. Identitas halaman dan locale cocok. Tidak ditemukan overflow horizontal pada viewport desktop yang diperiksa.

Sepuluh skenario interaksi (masing-masing ID/EN) lulus:

1. Pencarian DataTable menghasilkan empty state lokal.
2. Modal tambah divisi terbuka dan dapat ditutup.
3. Modal petunjuk impor terbuka dan dapat ditutup.
4. Editor EULA terbuka dengan toolbar sesuai locale.
5. Validasi langkah pertama form publik memakai bahasa aktif; nama perusahaan pada draft kembali setelah reload.

Viewport desktop: 1440 × 1000. Login dan form publik juga diperiksa pada 390 × 844; form publik tidak mengalami overflow horizontal dalam skenario tersebut. Screenshot dipakai untuk meninjau label jenis registrasi dan modal impor setelah transisi selesai.

## Bukti lokal

Artefak sementara berada di luar repository:

- `/tmp/task10-browser.json`: hasil per halaman dan interaksi.
- `/tmp/task10-browser-verified.log`: output verifikasi browser terakhir.
- `/tmp/task10-full-tests-verified.log`: hasil suite akhir.
- `/tmp/partner-task10-fixtures/manifest.json`: daftar 70 fixture render.
- `/tmp/task10-login-desktop.png`, `/tmp/task10-login-mobile.png`.
- `/tmp/task10-import-modal-id.png`, `/tmp/task10-import-modal-en.png`.
- `/tmp/task10-public-mobile-id.png`, `/tmp/task10-public-mobile-en.png`.

## Batas hasil

Pembaruan: pengujian lanjutan telah menutup sebagian batas lokal di bawah. Lihat `task-10-integration-followup.md` untuk transaksi SQLite nyata, eksekusi diagnostik, transport email lokal, inspeksi dokumen, GPS emulasi dan HTTP 413 lokal. Batas produksi dan perangkat fisik tetap berlaku.

Belum dilakukan CRUD/approval/maintenance nyata pada server, pengiriman email, GPS perangkat fisik, unggahan melalui proxy produksi termasuk HTTP 413, atau pengujian lintas browser/perangkat menyeluruh. API fixture tidak membuktikan otorisasi maupun integrasi database produksi; aturan terkait diuji melalui suite backend yang tersedia. Nama pengguna/perusahaan, filename, isi EULA, wilayah dan output teknis tidak diterjemahkan otomatis. View demo/backup yang tidak digunakan route aktif serta aset pihak ketiga dikecualikan dari audit teks aplikasi.

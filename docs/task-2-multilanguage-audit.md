# Audit akhir kode Task 2: Form Links dan Submission

Tanggal: 12 November 2026.

Status audit kode: selesai. Status implementasi Task 2: belum selesai, masih ada temuan terbuka.

## Cakupan

Seluruh template di `resources/views/admin/form_links/` termasuk panel detail bersama, row edit, PDF dan styles; komponen `submission-repeatable-section`; `Admin/FormLinkController`; `FormLinkStoreRequest`, `FormLinkUpdateRequest`; dan `FormLinkService`. Kamus yang digunakan: `messages`, `admin`, `form_links`, `submission`, `submission_validation`, `form_links_remaining`.

Audit membedakan teks UI dari CSS, identifier, enum, komentar, URL, nama perusahaan dan data pengguna. Panel bersama juga dipakai modal Partner List, sehingga perubahannya perlu diuji pada kedua konteks.

## Temuan terbuka

| ID | Prioritas | Lokasi | Temuan / perbaikan yang dibutuhkan |
| --- | --- | --- | --- |
| T2-01 | Tinggi | `components/submission-repeatable-section.blade.php:7` | Teks bantuan `Update, add, or remove records in this section.` masih literal. Key sudah ada tetapi komponen belum memakainya. |
| T2-02 | Tinggi | `form_links/submission_edit.blade.php:431` | Label, title dan aria-label fullscreen masih Inggris (`Exit fullscreen`, `Open fullscreen map`). Gunakan key yang sama pada kedua state. |
| T2-03 | Sedang | `form_links/submission_edit.blade.php:623` | aria-label dropdown wilayah yang dibuat ulang masih `City or regency` / `Province`. Placeholder sudah diterjemahkan. |
| T2-04 | Tinggi | `form_links/show.blade.php:198` | Aksi `Edit form` dan `Open public form` masih literal meskipun key tersedia. Periksa pula teks sesudah ikon pada tombol Back, Detail, Export PDF, Cancel, Create/Update. Penggantian node sebelumnya tidak mencakup semua spasi. |
| T2-05 | Tinggi | `form_links/submissions.blade.php:136` | Label dinamis view/edit/history dan tooltip masih Inggris. Gunakan placeholder `:name` dan key yang tersedia. |
| T2-06 | Tinggi | `app/Services/FormLinkService.php:114,158,182,212,227,275` | Pesan exception, 404/403 dan editability masih bahasa Indonesia literal. Ini dapat tampil pada error page atau flash error saat locale Inggris. |
| T2-07 | Tinggi | `form_links/export_submission_pdf.blade.php:370,392,611,623,627,663` | Label kategori material dan dokumen, jumlah lampiran dan `Diterapkan` belum seluruhnya diterjemahkan. Pertahankan nilai asli untuk pencocokan dokumen; terjemahkan hanya label yang dicetak. |
| T2-08 | Sedang | `form_links/_submission_detail_panels.blade.php` | Audit state/type dinamis: `ucfirst`, ownership status, `Address n`, Latitude/Longitude/Lat/Long, serta label sesudah ikon yang tidak terjangkau penggantian node. Enum tetap asli, display label mengikuti locale. |
| T2-09 | Tinggi | `submission_validation.php` dan nested rules di `FormLinkController` | Nama atribut belum lengkap: department/position kontak, country code telepon, fax, koordinat, id dan daftar penghapusan lampiran. Tipe validasi yang belum diberi pesan khusus dapat jatuh ke default Inggris karena `resources/lang/id/validation.php` tidak ada. Lengkapi aturan yang benar-benar digunakan. |
| T2-10 | Sedang | `FormLinkStoreRequest`, `FormLinkUpdateRequest` | Nama atribut `user_form` masih `user`; pesan date/integer/string yang tidak dioverride masih bergantung pada kamus validator default. Uji invalid date, non-integer limit, user invalid, field required dan max. |
| T2-11 | Sedang | PDF dan detail credit limit | Tanggal bernama sudah menggunakan `translatedFormat`; angka/desimal masih memakai `number_format` tetap. Tentukan format display locale untuk nilai uang/ukuran berkas tanpa mengubah data/input. |
| T2-12 | Tinggi | `tests/Unit/FormLink*`, `DataTableActionButtonStyleTest` dan render terkait | Sejumlah tes lama mencari literal Inggris pada source. Sesuaikan menjadi assertion hasil render ID/EN agar tidak gagal hanya karena `__()`. Jangan menghapus assertion akses/keamanan. |

## Verifikasi yang sudah dilakukan

- Kompilasi seluruh Blade berhasil dengan `APP_CONFIG_CACHE=/tmp/partner-no-config-cache.php php artisan view:cache`.
- Sintaks controller dan FormRequest telah diperiksa selama implementasi.
- Key tingkat atas kamus `form_links`, `submission` dan `submission_validation` cocok ID/EN pada pemeriksaan sebelumnya. Pemeriksaan akhir harus rekursif dan memeriksa placeholder.
- Runtime Laravel menghasilkan `Progres 1/3` / `Progress 1/3` dan pesan required yang berbeda sesuai locale.
- PDF template berhasil dirender menggunakan model contoh tanpa query database dalam ID/EN, dengan heading terjemahan yang sesuai. Ini membuktikan render template, bukan seluruh isi PDF sudah bebas literal.
- `git diff --check` bersih pada pemeriksaan terakhir.

## Verifikasi yang belum dilakukan

- Pemeriksaan browser untuk create/edit/show, empty/list DataTable, copy link, toggle status, submission detail/edit, validation errors dan download PDF dalam ID/EN.
- Render dan submit dengan dataset relasi lengkap serta error nested setiap jenis field.
- Pembuktian layout PDF aktual lewat mPDF dalam kedua bahasa.
- Regression suite setelah penyesuaian tes source yang terdampak terjemahan.

## Keputusan

Audit kode Task 2 selesai dan menemukan 12 kelompok temuan. Implementasi Task 2 tetap terbuka sampai T2-01–T2-12 diperbaiki dan pemeriksaan hasilnya dicatat. Jangan menandai task selesai hanya berdasarkan kompilasi Blade atau kesamaan key kamus.

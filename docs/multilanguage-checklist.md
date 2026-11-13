# Checklist penyelesaian multibahasa

Tanggal inventaris: 12 November 2026. Bahasa: Indonesia (`id`) dan Inggris (`en`).

## Status task

- [x] Task 1: inventaris route, kelompok tampilan, pesan JavaScript, dan respons backend.
- [x] Task 2: Form Links dan Submission (implementasi selesai; QA browser lanjutan pada Task 10).
- [x] Task 3: Approval dan pengaturan approval (implementasi selesai; QA browser lanjutan pada Task 10).
- [x] Task 4: Dashboard dan Partner List (implementasi selesai; QA browser lanjutan pada Task 10).
- [x] Task 5: pengaturan pengguna, menu, role/permission, organisasi dan IP whitelist (implementasi selesai; QA browser lanjutan pada Task 10).
- [x] Task 6: export/import, preview, PDF, template spreadsheet dan impor kode pos (implementasi selesai; QA browser lanjutan pada Task 10).
- [x] Task 7: System Maintenance (implementasi selesai; QA browser lanjutan pada Task 10).
- [x] Task 8: form public, create partner internal, success/closed dan autentikasi (implementasi selesai; QA browser lanjutan pada Task 10).
- [ ] Task 9: tender, EULA, home dan supporting documents.
- [ ] Task 10: audit sisa teks, kesamaan key, tes render dan pemeriksaan browser ID/EN.

Task 1 selesai berarti sumber dan kategori pekerjaan telah dipetakan, bukan seluruh teks sudah diterjemahkan. Pemindaian statis menghasilkan kandidat; komentar, identifier dan data pengguna tidak dihitung sebagai teks yang wajib diterjemahkan.

## Inventaris per modul

| Task | Route/alur | Sumber utama | Pekerjaan tersisa dan bukti |
| --- | --- | --- | --- |
| 2 | `/admin/form-links`, create/edit/show | `resources/views/admin/form_links/{index,create,edit,show}.blade.php`; `Admin/FormLinkController`; `FormLinkService` | Placeholder `Vendor Registration Form Q1 2025`, `Optional description for internal use`, `Leave empty for unlimited`; copy-link toast, konfirmasi, DataTable, flash controller dan validasi request. |
| 2 | submission list/detail/edit/PDF | `submissions`, `submission_detail`, `_submission_detail_panels`, `submission_edit`, `_submission_edit_row`, `export_submission_pdf` pada folder di atas | Search company/email; label Progress/Approved/Rejected dengan angka; judul loading `Menyimpan perubahan`, pesan error/success; atribut aksesibilitas; status/pesan kosong; PDF mengikuti locale. Panel juga digunakan Partner List. |
| 3 | `/admin/approvals`, `/my/pending`, detail, action, history | `resources/views/admin/approvals/{index,my_approvals,show,action}.blade.php`; `Admin/ApprovalProcessController`; `ApprovalService` | Placeholder `Search company or type`, `Add supporting notes or a rejection reason`, `Contoh: 30 hari`; tanggal relatif; current step, notes, progress; pesan keputusan/otorisasi/validasi. |
| 3 | `/approval-setting` | `resources/views/admin/approval_setting/*`; `ApprovalSettingController` | Status Active/Inactive dari backend, pengaturan parallel stage, tombol dinamis, dialog hapus, Select2 dan validation response. |
| 4 | `/dashboard` dan endpoint data | `resources/views/admin/dashboard/{index_live,live_js}.blade.php`; `DashboardController` | Implementasi selesai: status API, pesan backend, grafik, popup/kontrol peta, empty/error state dan DataTable ID/EN. Lihat audit Task 4. |
| 4 | `/partner-list` dan detail/export | `resources/views/partner/{index,partner_management_js,public_detail_modal,export_pdf}.blade.php`; panel submission bersama; `PartnerManagementController`; `PartnerExport` | Implementasi selesai: header, modal detail tanpa approval, aksesibilitas, loading/error, DataTable dan ekspor Excel/PDF ID/EN. QA browser pada Task 10. |
| 5 | `/user-manage`, `/role-permission`, `/menu-setting`, `/office-setting` | `resources/views/users/*`, `admin/role_and_permission/*`, `admin/menus/*`, `admin/office/*`; controller masing-masing | Copy utama sudah diterjemahkan; audit respons CRUD, validator default, DataTable length/info/search, tooltip, label dinamis dan fallback error. |
| 5 | `/department-setting`, `/division-setting`, `/ip-whitelist` | `admin/{department,division,api_white_list}/*`; `DepartmentManagementController`, `DivisionManagementController`, `APIWhiteListManageController` | JS `Action`, `Success`, `Error`, `Delete Division?`; backend `Division created/updated/deleted successfully.` dan `department created/updated successfully`; placeholder Description. |
| 5 | sidebar dan topbar | `components/layout/{sidebar,sidebar-menu-item,topbar}`; `MenuService` | Nama menu berasal dari `menus.name_text`; butuh pemetaan display name yang mempertahankan URL, role dan permission. Logout dan fallback user juga diperiksa. |
| 6 | `/admin/company/export` | `admin/company/{export_form,export_pdf}`; `CompanyExportController`; `app/Exports/*` | Instruksi panjang, kategori/kolom, toggle-all, jumlah terpilih, Swal `Pilih Minimal 1 Field`, `Export Berhasil!`; pesan controller `Export PDF gagal`; judul/heading output dokumen. Notifikasi sukses harus sesuai hasil sebenarnya. |
| 6 | `/admin/company/import`, preview/confirm | `admin/company/{import_form,import_preview}`; `CompanyImportController`; kelas import | Isi modal petunjuk, warnings/tips, ringkasan preview, error per baris, konfirmasi; flash `Session expired. Silakan upload ulang file.`, `Import berhasil disimpan!`; sheet petunjuk/template. |
| 6 | `/admin/postal-codes/import` | `admin/postal_codes/import`; `Admin/PostalCodeImportController` | Upload, petunjuk, hasil impor, download daftar error, validasi. |
| 7 | `/admin/system-maintenance` | `admin/system_maintenance/index`; `Admin/SystemMaintenanceController`; `SystemMaintenanceService` | Judul dan konfirmasi sudah mulai diterjemahkan. Grup/label/deskripsi command masih berasal dari service. Output mentah artisan dipertahankan; pesan aplikasi dan status harus diterjemahkan. |
| 8 | `/f/{token}`, success, closed | `resources/views/public/*`, `public/form/*`, `cs_vendor/form/*`, `cs_vendor/{partner_js,attachment}`; `PublicFormController`; request/rules/services terkait | Audit error 413/419/422/500, attachment, website, GPS/map, dropdown wilayah, draft, wizard, survey, repeaters. Sebagian pesan masih literal atau format gabungan ID/EN. |
| 8 | `/create-partner`, login/logout | `cs_vendor/index`; `PartnerController`; `resources/views/auth/*`; layout terkait | Konsistensi copy form internal/public, auth validation, password reset, flash session dan loading global. |
| 9 | `/tender-vendor` | `admin/tenders/vendor/*`; `TenderForVendorController` | Judul/kolom, detail/create, dropdown, upload, alert dan respons backend. |
| 9 | `/end-user-license-agreement` | `admin/eula/*`; `EndUserLicenseAgreementController` | Editor, toolbar, modal, pesan CRUD. Isi perjanjian adalah konten bisnis; jangan terjemahkan otomatis tanpa sumber bahasa yang disetujui. |
| 9 | `/home`, `/user-attachment`, supporting documents | `HomeController`, `PartnerController`; `company/*`, `cs_vendor/attachment` dan include terkait | Ikuti view aktual dari controller; rincian dokumen, financial ratio, upload/update dan pesan validasi. |

## Penelusuran route dan template lama

- Sumber route utama: `routes/web.php`. Dashboard aktif memakai `admin.dashboard.index_live`; bukan otomatis `admin/dashboard/index` atau `dashboard_js` lama.
- Detail Partner List aktif memakai `partner.public_detail_modal` dan `admin.form_links._submission_detail_panels`; `partner/detail_partner` dan `public_detail_content` perlu dicek referensinya sebelum diikutkan.
- `admin/form-links` dan `admin/form_links` merupakan folder berbeda; jangan berasumsi keduanya aktif.
- `Admin/ApprovalController` dan `Admin/ApprovalProcessController` hidup berdampingan; route proses saat ini menunjuk `ApprovalProcessController`.
- File `*backup*`, view vendor paket, demo plugin, komentar, log internal dan file CSS/JS pihak ketiga dikecualikan dari perubahan terjemahan aplikasi.
- Route `/user-attachment` terdaftar pada lebih dari satu grup; audit view yang benar dari controller sebelum perubahan.

## Aturan implementasi

- Gunakan native Laravel locale dan key ID/EN yang sama. Kamus yang ada: `messages.php`, `admin.php`, `tools.php`.
- Gunakan `__()` untuk Blade/backend dan `@json(__('...'))` untuk literal JavaScript. Jangan menyisipkan string terjemahan mentah ke HTML dinamis.
- Placeholder seperti `:name`, `:count`, `:current`, `:total` harus sama di kedua bahasa.
- Jangan menerjemahkan nilai enum/database (`approved`, `vendor`, dll.), route, permission, command artisan, nama pengguna/perusahaan, nomor rekening atau nama file. Terjemahkan label tampilannya.
- Bahasa nama wilayah dan konten bisnis tidak diubah otomatis. Format tanggal/angka dan relative time perlu mengikuti locale bila ditampilkan sebagai UI.
- Hindari mengganti semua string dengan regex tanpa meninjau konteks PHP, JavaScript dan atribut HTML.
- Backend harus memberi pesan aplikasi sesuai locale; detail exception teknis tetap di log dan tidak dijadikan teks terjemahan pengguna.

## Kriteria selesai setiap task

- [ ] Halaman utama, modal dan state kosong/loading/error memakai locale aktif.
- [ ] Notifikasi success/error, validasi backend dan JavaScript konsisten.
- [ ] DataTable/Select2, title/placeholder/aria-label diperiksa.
- [ ] Key ID/EN lengkap dengan placeholder identik dan tanpa raw translation key tampil.
- [ ] Blade terkompilasi; tes yang relevan lulus atau kegagalan dijelaskan.
- [ ] Pemeriksaan browser ID dan EN dicatat, termasuk halaman yang belum bisa diuji.

## Catatan verifikasi Task 1

- Inventaris berdasarkan pembacaan routes, pemetaan `return view`, include aktif, dan pencarian literal pada Blade/JavaScript/controller/service.
- Contoh bukti: `CompanyImportController` masih berisi flash session campuran bahasa; `division_js` masih berisi dialog hapus Inggris; `SystemMaintenanceService` memasok label/deskripsi command.
- Ini inventaris sumber dan kategori, bukan jumlah pasti string hardcode. Pemeriksaan render/browser seluruh halaman belum dilakukan dan menjadi Task 10 serta verifikasi tiap task.
- Urutan pengerjaan berikutnya: Task 2 sampai Task 9, kemudian Task 10. Jangan tandai modul selesai hanya berdasarkan kompilasi Blade.

## Progres Task 2

Status implementasi: selesai, 13 November 2026. Temuan T2-01–T2-12 ditangani; rincian dan bukti ada di `docs/task-2-multilanguage-audit.md`.

- [x] Label halaman, aksi, status/progres, tanggal, tooltip, aria-label dan JavaScript menggunakan locale aktif.
- [x] Flash/notifikasi dan pesan akses service diterjemahkan.
- [x] Validasi FormRequest dan nested submission menggunakan pesan dan nama atribut lokal.
- [x] PDF, kategori/dokumen, jumlah lampiran, tanggal dan angka mengikuti ID/EN.
- [x] Kesamaan key/placeholder rekursif, render row/panel bersama dan pembuatan PDF mPDF diuji ID/EN.
- [x] 35 tes terkait lulus; Blade terkompilasi dan diff bersih.
- [ ] QA interaksi browser ID/EN dan layout PDF secara visual diteruskan ke Task 10. Belum ada klaim pengujian browser atau submit pada database server.

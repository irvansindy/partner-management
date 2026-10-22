# Public Form Security Hardening Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menutup temuan keamanan public form terkait kebocoran secret, upload publik, race condition file, duplikasi submission, logging PII, respons error, resource exhaustion, rate limiting, dan penyimpanan data sensitif pada browser.

**Architecture:** `PublicFormController` tetap menjadi endpoint orkestrasi, tetapi validasi request, idempotency, attachment storage, dan redaksi log dipisahkan ke komponen yang memiliki satu tanggung jawab. Database menjadi sumber kebenaran untuk kuota dan idempotency, sedangkan file disimpan pada private disk dengan UUID dan hanya disajikan melalui endpoint terotorisasi.

**Tech Stack:** Laravel, Eloquent, database transactions, Laravel RateLimiter, Laravel Storage, PHPUnit.

**Spec:** `docs/superpowers/specs/2026-10-16-public-partner-form-hardening-design.md`

## Global Constraints

- Pertahankan `lockForUpdate()` pada row `form_links` ketika memeriksa dan menaikkan kuota submission.
- Backend menjadi sumber validasi utama. Validasi frontend hanya membantu pengguna.
- File yang diterima terbatas pada PDF, JPEG/JPG, dan PNG, maksimum 5 MB per file, maksimum 10 file, dan gambar maksimum 6000 x 6000 piksel.
- File baru harus memakai nama UUID dan private storage.
- Public response tidak boleh mengandung exception, SQL error, path filesystem, credential, atau stack trace.
- Log tidak boleh menyimpan CSRF token, NIK, nomor rekening, alamat, telepon, email, isi dokumen, atau seluruh request body.
- Perubahan tidak boleh menghapus atau memutus akses attachment lama sebelum migrasi dan verifikasi selesai.

## Review Focus

- Dua POST simultan dengan satu slot tersisa: tepat satu berhasil dan `submission_count` tidak melewati batas.
- Dua upload dengan nama file sama pada detik yang sama: kedua file tersimpan pada path UUID yang berbeda.
- Request yang sama dikirim ulang dengan idempotency key sama: hanya satu company dan satu approval process dibuat.
- File dengan ekstensi PDF/JPG/PNG tetapi signature tidak sesuai: server menolak dan tidak meninggalkan file.
- Validation, database, approval, atau storage failure setelah file ditulis: database rollback dan file baru dibersihkan.

---

## Urutan Prioritas

| Urutan | Task | Severity | Dependensi |
|---|---|---|---|
| 1 | Hapus secret browser dan tutup route legacy | P1 | Tidak ada |
| 2 | Redaksi request dan error log | P1 | Tidak ada |
| 3 | Respons error aman | P2 | Task 2 |
| 4 | Private attachment pipeline | P1 | Tidak ada |
| 5 | Idempotency submission | P1 | Tidak ada |
| 6 | Batas array, teks, dan angka | P2 | Tidak ada |
| 7 | Rate limiting khusus public form | P2 | Task 1 |
| 8 | Kurangi data sensitif di localStorage | P2 | Tidak ada |
| 9 | Security regression suite | P1 | Task 1 sampai 8 |
| 10 | Pulihkan verifikasi route dan release gate | P2 | Task 9 |

### Task 1: Hapus Secret Browser dan Tutup Jalur Submission Legacy

**Files:**
- Modify: `routes/web.php`
- Modify: `resources/views/public/form_index.blade.php`
- Modify: `resources/views/cs_vendor/partner_js.blade.php`
- Modify or remove: `app/Http/Middleware/PublicFormProtection.php`
- Test: `tests/Feature/PublicForm/PublicFormSecretExposureTest.php`

**Interfaces:**
- Consumes: route bertoken `POST /f/{token}` dan CSRF middleware web.
- Produces: public form yang tidak pernah mengirim `PUBLIC_FORM_SECRET` ke client dan tidak mempunyai browser-accessible bypass tanpa lifecycle Form Link.

- [ ] **Step 1: Tulis test yang memastikan HTML public form tidak mengandung nilai `PUBLIC_FORM_SECRET`, `public_key`, atau header setup `X-APP-KEY`.**
- [ ] **Step 2: Tulis test yang memastikan POST ke route legacy `/submit-partner` tidak dapat membuat company tanpa Form Link aktif.**
- [ ] **Step 3: Jalankan test dan verifikasi keduanya gagal pada implementasi saat ini.**

Run: `php artisan test tests/Feature/PublicForm/PublicFormSecretExposureTest.php`

- [ ] **Step 4: Hapus hidden input `public_key` dan header `X-APP-KEY` dari JavaScript.**
- [ ] **Step 5: Hapus route legacy public atau pindahkan jalur server-to-server ke autentikasi yang tidak pernah dirender pada browser.**
- [ ] **Step 6: Jalankan test dan pastikan PASS.**
- [ ] **Step 7: Commit.**

Commit: `fix(security): remove public form shared secret exposure`

### Task 2: Redaksi PII dari Request dan Error Log

**Files:**
- Create: `app/Support/SecurityLogSanitizer.php`
- Modify: `app/Http/Middleware/SanitizeInputMiddleware.php`
- Modify: `app/Http/Controllers/PublicFormController.php`
- Test: `tests/Unit/SecurityLogSanitizerTest.php`
- Test: `tests/Feature/PublicForm/PublicFormLoggingTest.php`

**Interfaces:**
- Produces: `SecurityLogSanitizer::requestMetadata(Request $request): array` yang hanya mengembalikan method, route name, status, request ID, IP terproteksi sesuai kebijakan, dan user-agent ringkas.
- Produces: `SecurityLogSanitizer::exceptionContext(Throwable $error, Request $request): array` tanpa body form dan credential.

- [ ] **Step 1: Tulis unit test yang memasukkan `_token`, NIK, rekening, email, telepon, alamat, serta nested array lalu memastikan tidak ada nilai tersebut pada hasil sanitizer.**
- [ ] **Step 2: Jalankan unit test dan verifikasi FAIL karena sanitizer belum ada.**
- [ ] **Step 3: Implementasikan sanitizer berbasis allowlist metadata, bukan daftar field yang dibuang.**
- [ ] **Step 4: Ganti `logRequest()` agar tidak mencatat `$request->all()` dan tidak menyimpan header cookie/authorization.**
- [ ] **Step 5: Ganti context error public form agar tidak mencatat request body.**
- [ ] **Step 6: Tulis feature test yang memicu validasi gagal dan memastikan record/log tidak memuat PII payload.**
- [ ] **Step 7: Jalankan kedua test dan pastikan PASS.**
- [ ] **Step 8: Commit.**

Commit: `fix(security): redact sensitive public form logs`

### Task 3: Respons Error Aman dan Correlation ID

**Files:**
- Modify: `app/Http/Controllers/PublicFormController.php`
- Modify: `app/Helpers/FormatResponseJson.php` jika response helper memerlukan field request ID.
- Test: `tests/Feature/PublicForm/PublicFormErrorResponseTest.php`

**Interfaces:**
- Consumes: metadata aman dari Task 2.
- Produces: HTTP 500 dengan pesan umum dan `request_id`; detail exception hanya berada di server log.

- [ ] **Step 1: Tulis test dengan dependency yang sengaja melempar exception berisi SQL/path palsu.**
- [ ] **Step 2: Assert response berstatus 500, mempunyai pesan umum dan request ID, serta tidak mengandung pesan exception.**
- [ ] **Step 3: Jalankan test dan verifikasi FAIL karena controller masih mengembalikan `$e->getMessage()`.**
- [ ] **Step 4: Implementasikan pesan generik dan correlation ID pada catch block.**
- [ ] **Step 5: Jalankan test dan pastikan PASS.**
- [ ] **Step 6: Commit.**

Commit: `fix(security): hide public form exception details`

### Task 4: Private Attachment Pipeline dan Race-Free Filename

**Files:**
- Create: `app/Services/CompanyAttachmentService.php`
- Create: `app/Http/Controllers/CompanyAttachmentController.php`
- Create: `app/Http/Requests/PublicFormSubmissionRequest.php`
- Create: `config/company_attachments.php`
- Modify: `config/filesystems.php`
- Modify: `app/Http/Controllers/PublicFormController.php`
- Modify: `app/Models/CompanyAttachment.php`
- Modify: `resources/views/admin/form_links/submission_detail.blade.php`
- Modify: `routes/web.php`
- Create: migration untuk metadata attachment/private storage yang kompatibel dengan data lama.
- Test: `tests/Feature/PublicForm/PublicFormAttachmentSecurityTest.php`
- Test: `tests/Feature/CompanyAttachmentAuthorizationTest.php`

**Interfaces:**
- Produces: `CompanyAttachmentService::validateAndStore(UploadedFile $file, int $companyId, int $sortOrder): StoredCompanyAttachment`.
- Produces: `CompanyAttachmentService::deleteStored(array $paths): void` untuk cleanup setelah rollback.
- Produces: route preview/download terautentikasi dan terotorisasi berdasarkan company.

- [ ] **Step 1: Tulis test untuk file valid, spoofed MIME/signature, file lebih dari 5 MB, lebih dari 10 file, gambar lebih dari 6000 x 6000, dan nama file identik.**
- [ ] **Step 2: Assert file valid memakai nama UUID dan dua upload bernama sama tidak mempunyai path sama.**
- [ ] **Step 3: Tulis test rollback yang memastikan file baru dihapus ketika persistence atau approval gagal.**
- [ ] **Step 4: Jalankan test dan verifikasi FAIL pada penyimpanan disk publik saat ini.**
- [ ] **Step 5: Definisikan private disk dan konfigurasi batas attachment.**
- [ ] **Step 6: Implementasikan inspeksi MIME/signature server-side, decoding gambar, batas dimensi, UUID filename, dan metadata nama asli yang disanitasi.**
- [ ] **Step 7: Integrasikan cleanup file dengan catch/rollback controller.**
- [ ] **Step 8: Buat endpoint delivery yang menetapkan Content-Type, `X-Content-Type-Options: nosniff`, serta disposition aman.**
- [ ] **Step 9: Ubah admin view agar memakai endpoint terotorisasi, bukan `asset('storage/...')`.**
- [ ] **Step 10: Tambahkan jalur kompatibilitas dan perintah migrasi attachment lama tanpa menghapus file sumber sebelum verifikasi checksum/path.**
- [ ] **Step 11: Jalankan seluruh test attachment dan pastikan PASS.**
- [ ] **Step 12: Commit.**

Commit: `fix(security): move partner attachments to private storage`

### Task 5: Idempotency dan Perlindungan Replay Submission

**Files:**
- Create: migration penambahan `idempotency_key` pada submission/company flow dengan unique constraint per Form Link.
- Create: `app/Services/PublicFormSubmissionService.php`
- Modify: `app/Http/Controllers/PublicFormController.php`
- Modify: `resources/views/public/form_index.blade.php`
- Modify: `resources/views/public/form/js.blade.php`
- Test: `tests/Feature/PublicForm/PublicFormIdempotencyTest.php`
- Test: `tests/Feature/PublicForm/PublicFormConcurrencyTest.php`

**Interfaces:**
- Produces: hidden nonce acak yang terikat ke Form Link dan disimpan sebagai idempotency key.
- Produces: `PublicFormSubmissionService::submit(FormLink $formLink, array $validated, array $files, string $idempotencyKey): CompanyInformation`.

- [ ] **Step 1: Tulis test dua POST dengan key sama dan assert hanya satu company, satu submission count, satu set attachment, dan satu approval process.**
- [ ] **Step 2: Tulis test dua key berbeda dengan satu slot tersisa dan assert tepat satu request berhasil.**
- [ ] **Step 3: Jalankan test dan verifikasi FAIL untuk replay, sementara test kuota membuktikan perilaku `lockForUpdate()`.**
- [ ] **Step 4: Tambahkan unique constraint idempotency per Form Link.**
- [ ] **Step 5: Pindahkan orkestrasi transaksi ke submission service dan tangani duplicate key sebagai hasil idempotent.**
- [ ] **Step 6: Pertahankan row lock sampai kenaikan submission count selesai, tetapi lakukan validasi file sebelum lock untuk mengurangi waktu tunggu.**
- [ ] **Step 7: Jalankan test dan pastikan PASS pada database engine produksi yang mendukung row lock.**
- [ ] **Step 8: Commit.**

Commit: `fix(security): make public submissions idempotent`

### Task 6: Batas Array, Field, dan Nilai Numerik

**Files:**
- Modify: `app/Http/Requests/PublicFormSubmissionRequest.php`
- Modify: `resources/views/cs_vendor/form/*.blade.php` untuk menyamakan `maxlength` dan limit frontend.
- Modify: `resources/views/public/form/js.blade.php` untuk membatasi penambahan row.
- Test: `tests/Feature/PublicForm/PublicFormResourceLimitTest.php`

**Interfaces:**
- Consumes: Form Request dari Task 4.
- Produces: batas eksplisit untuk semua array dan teks.

- [ ] **Step 1: Tulis data-driven test untuk jumlah maksimum liable person, contact, address, bank, survey product, dan attachment.**
- [ ] **Step 2: Tulis test panjang maksimum address, business classification detail, serta `total_employee` maksimum 1.000.000.**
- [ ] **Step 3: Jalankan test dan verifikasi payload berlebihan masih diterima atau tidak mempunyai pesan terarah.**
- [ ] **Step 4: Tambahkan rule `array|max:N`, `string|max:N`, range koordinat, dan limit numerik dengan nilai yang sama di konfigurasi/backend/frontend.**
- [ ] **Step 5: Pastikan array paralel memiliki jumlah elemen konsisten sebelum persistence.**
- [ ] **Step 6: Jalankan test dan pastikan PASS.**
- [ ] **Step 7: Commit.**

Commit: `fix(security): bound public form payload size`

### Task 7: Rate Limiting Khusus Token dan IP

**Files:**
- Modify: `app/Providers/RouteServiceProvider.php`
- Modify: `routes/web.php`
- Modify: `app/Http/Middleware/SanitizeInputMiddleware.php`
- Test: `tests/Feature/PublicForm/PublicFormRateLimitTest.php`

**Interfaces:**
- Produces: named limiter terpisah untuk GET dan POST berbasis hash token serta IP.
- Produces: respons 429 tanpa menulis body request ke log.

- [ ] **Step 1: Tulis test bahwa GET dan POST memiliki bucket terpisah serta token berbeda tidak memakai counter token yang sama.**
- [ ] **Step 2: Assert request yang melewati limit menghasilkan 429 dan tidak membuat company atau request-body log.**
- [ ] **Step 3: Jalankan test dan verifikasi FAIL karena route belum memakai limiter khusus.**
- [ ] **Step 4: Definisikan limiter dengan nilai yang dikonfigurasi, bukan angka tersebar pada controller/middleware.**
- [ ] **Step 5: Terapkan middleware limiter pada group route `f`.**
- [ ] **Step 6: Pindahkan pemeriksaan rate limit sebelum logging yang menulis ke database, atau hilangkan logging request umum sesuai Task 2.**
- [ ] **Step 7: Jalankan test dan pastikan PASS.**
- [ ] **Step 8: Commit.**

Commit: `fix(security): rate limit public form by token and ip`

### Task 8: Kurangi Penyimpanan Data Sensitif pada Browser

**Files:**
- Modify: `resources/views/public/form/js.blade.php`
- Test: `tests/Unit/PublicFormDraftStorageTest.php`

**Interfaces:**
- Produces: draft storage allowlist yang tidak menyimpan CSRF token, NIK, rekening, file, atau field sensitif lain.

- [ ] **Step 1: Tulis test source/JavaScript yang memastikan field sensitif tidak masuk payload draft.**
- [ ] **Step 2: Tentukan allowlist field non-sensitif yang memang perlu dipulihkan setelah refresh.**
- [ ] **Step 3: Ganti iterasi semua input dengan allowlist; gunakan `sessionStorage` jika draft hanya diperlukan selama tab aktif.**
- [ ] **Step 4: Pastikan storage dibersihkan setelah sukses, link/token berubah, dan halaman form ditutup.**
- [ ] **Step 5: Jalankan test dan pastikan PASS.**
- [ ] **Step 6: Commit.**

Commit: `fix(security): exclude sensitive fields from form drafts`

### Task 9: Security Regression Suite

**Files:**
- Create: `tests/Feature/PublicForm/PublicFormXssTest.php`
- Create: `tests/Feature/PublicForm/PublicFormSqlInjectionTest.php`
- Create: `tests/Feature/PublicForm/PublicFormSecurityHeadersTest.php`
- Extend: seluruh test pada Task 1 sampai 8.

**Interfaces:**
- Consumes: seluruh kontrol keamanan Task 1 sampai 8.
- Produces: regression gate yang membuktikan kontrol utama tanpa menyatakan scanner sebagai bukti keamanan lengkap.

- [ ] **Step 1: Tambahkan payload XSS pada title/description Form Link dan semua field teks; assert HTML menggunakan escaping dan tidak membentuk tag executable.**
- [ ] **Step 2: Tambahkan payload SQL injection pada token dan field input; assert tidak ada query bypass, data tidak rusak, dan response tidak membocorkan SQL.**
- [ ] **Step 3: Assert CSRF tetap wajib untuk POST, token acak tidak dapat menggantikan CSRF, dan Form Link tidak aktif/kedaluwarsa/kuota habis ditolak.**
- [ ] **Step 4: Assert security headers public response, termasuk `X-Content-Type-Options`, `Referrer-Policy`, dan CSP yang disepakati.**
- [ ] **Step 5: Jalankan semua public form test.**

Run: `php artisan test --filter=PublicForm`

- [ ] **Step 6: Jalankan seluruh test suite untuk regression.**

Run: `php artisan test`

- [ ] **Step 7: Commit.**

Commit: `test(security): cover public form attack paths`

### Task 10: Pulihkan Route Verification dan Release Gate

**Files:**
- Modify: `.env.example`
- Modify: konfigurasi Nominatim terkait user-agent.
- Create: `docs/security/public-form-release-checklist.md`
- Test: konfigurasi boot/route test yang mengikuti pola test project.

**Interfaces:**
- Produces: konfigurasi default valid yang memungkinkan Artisan boot dan route inspection.
- Produces: release checklist berbasis bukti untuk public form.

- [ ] **Step 1: Tulis test/config check bahwa `nominatim.services.nominatim.user_agent` selalu string pada environment test dan produksi.**
- [ ] **Step 2: Perbaiki default `.env.example` dan konfigurasi tanpa memasukkan credential nyata.**
- [ ] **Step 3: Jalankan `php artisan route:list --path=f` dan pastikan GET/POST `/f/{token}` menampilkan middleware web, CSRF, serta limiter khusus.**
- [ ] **Step 4: Jalankan `php artisan config:cache` dan `php artisan route:cache` pada environment staging.**
- [ ] **Step 5: Dokumentasikan bukti test concurrency, attachment, logging, rate limit, XSS, SQL injection, CSRF, rollback, serta prosedur migrasi attachment.**
- [ ] **Step 6: Verifikasi backup, rollback, monitoring 429/500, dan cleanup storage pada staging.**
- [ ] **Step 7: Commit.**

Commit: `chore(security): add public form release gate`

## Definition of Done

- Tidak ada server secret pada HTML, JavaScript, network request, atau browser storage.
- Route public hanya menerima submission melalui Form Link aktif dan CSRF valid.
- Quota dan idempotency aman terhadap request paralel serta replay.
- Attachment baru bersifat private, memakai UUID, lolos signature validation, dan mempunyai authorized delivery.
- Rollback database tidak meninggalkan file baru.
- Log dan error response tidak mengandung PII atau detail internal.
- Payload berlebihan mendapat 422, abuse mendapat 429, dan keduanya tidak membuat data parsial.
- XSS/SQL injection regression test, focused public form test, dan full suite lulus.
- `route:list`, `config:cache`, dan `route:cache` berhasil pada staging.

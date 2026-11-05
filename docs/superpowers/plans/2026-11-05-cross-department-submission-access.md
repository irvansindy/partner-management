# Cross-Department Submission Access Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Memberi assigned approver akses baca ke submission spesifik lintas department tanpa membuka pengelolaan Form Link atau submission lain.

**Architecture:** Authorization manajemen Form Link tetap utuh. `FormLinkService` mendapat jalur authorization terpisah untuk membaca satu company berdasarkan organisasi pemilik, super-admin, atau keanggotaan actor pada approval steps; controller detail, PDF, dan attachment memakai jalur tersebut.

**Tech Stack:** Laravel, Eloquent, Blade, PHPUnit

**Spec:** `docs/superpowers/specs/2026-11-05-cross-department-submission-access-design.md`

## Global Constraints

- Access assigned approver selalu scoped ke satu company.
- Form Link management, list submission, edit, update, toggle, dan delete tidak diperluas.
- Form Link/company mismatch tetap 404 sebelum authorization user.
- Attachment harus mengikuti company pemiliknya.

## Review Focus

- Approver yang step-nya sudah skipped/approved tetap dapat membaca histori submission.
- User ber-role benar tetapi tidak tercatat pada process tetap 403.
- Company ID dari Form Link lain tidak membocorkan data.
- Attachment dari company lain tidak ikut terbuka.
- Tombol navigasi read-only tidak mengarah ke halaman daftar yang akan 403.

---

### Task 1: Submission Read Authorization

**Files:**
- Modify: `app/Services/FormLinkService.php`
- Test: `tests/Unit/FormLinkSubmissionAccessTest.php`

**Interfaces:**
- Produces: `authorizeSubmissionRead(FormLink $formLink, CompanyInformation $company, User $user = null): void` dan `canManageFormLink(FormLink $formLink, User $user = null): bool`.

- [ ] Tulis test gagal untuk super-admin, owner organization, assigned approver, unrelated user, dan mismatch Form Link/company.
- [ ] Jalankan test dan pastikan gagal.
- [ ] Implementasikan policy dengan pemeriksaan mismatch 404 lebih dahulu dan query `approvalProcess.steps` yang scoped ke actor.
- [ ] Jalankan test dan pastikan lulus.

### Task 2: Detail, PDF, and Attachment Enforcement

**Files:**
- Modify: `app/Http/Controllers/Admin/FormLinkController.php`
- Modify: `app/Http/Controllers/Admin/CompanyAttachmentController.php`
- Test: `tests/Unit/FormLinkSubmissionAccessTest.php`
- Test: `tests/Feature/CompanyAttachmentDeliveryTest.php`

**Interfaces:**
- Consumes: authorization Task 1.
- Produces: detail/PDF/attachment read access scoped per company.

- [ ] Tulis test gagal bahwa detail dan PDF memakai submission-read authorization sedangkan edit/update tetap memakai management authorization.
- [ ] Tulis/ubah test attachment agar authorization menerima Form Link dan company pemilik.
- [ ] Jalankan test dan pastikan gagal.
- [ ] Implementasikan perubahan minimal pada tiga read endpoint.
- [ ] Jalankan test fokus dan pastikan lulus.

### Task 3: Read-Only Navigation

**Files:**
- Modify: `resources/views/admin/form_links/submission_detail.blade.php`
- Modify: `app/Http/Controllers/Admin/FormLinkController.php`
- Test: `tests/Unit/FormLinkSubmissionAccessTest.php`

**Interfaces:**
- Produces: boolean `canManageFormLink` dan navigasi aman bagi assigned approver.

- [ ] Tulis test gagal untuk tombol kembali owner versus assigned approver dan tidak adanya edit pada read-only mode.
- [ ] Jalankan test dan pastikan gagal.
- [ ] Teruskan flag management dari controller dan render navigasi sesuai akses.
- [ ] Jalankan test fokus dan pastikan lulus.

### Task 4: Verification

- [ ] Jalankan syntax check file PHP yang berubah.
- [ ] Jalankan seluruh suite dengan `rtk php artisan test`.
- [ ] Jalankan `rtk git diff --check`.
- [ ] Review diff terhadap seluruh access rule pada spec.


# Customer Finance Approval Fields Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Mewajibkan PIC FAT–Finance menetapkan term of payment dan credit limit ketika menyetujui customer.

**Architecture:** Kondisi organisasi dipusatkan pada model/user helper dan diverifikasi ulang di `ApprovalService` dalam transaksi yang sama dengan keputusan approval. Controller menangani validasi request dan Blade hanya menampilkan field ketika kondisi tersebut aktif.

**Tech Stack:** Laravel, Eloquent, Blade, PHPUnit, database migration/seeder

**Spec:** `docs/superpowers/specs/2026-11-03-customer-finance-approval-fields-design.md`

## Global Constraints

- Vendor dan PIC selain FAT–Finance mempertahankan perilaku lama.
- Reject tidak membutuhkan atau mengubah nilai finansial.
- Validasi service menjadi sumber kebenaran; UI tidak dipercaya sebagai kontrol keamanan.
- Update finansial dan keputusan approval berada dalam transaksi yang sama.
- Data master dibuat secara idempotent untuk database lama dan instalasi baru.

## Review Focus

- Perbedaan kapitalisasi atau spasi nama division/department tidak boleh melewati kondisi organisasi.
- Request field finansial dari PIC non-FAT–Finance tidak boleh mengubah company.
- Nilai `0` untuk credit limit harus diterima sebagai nilai valid.
- Kegagalan approval setelah update company harus me-rollback nilai finansial.
- Caller lama `ApprovalProcessService` harus tetap cocok dengan signature service baru.

---

### Task 1: FAT and Finance Master Data

**Files:**
- Create: `database/migrations/2026_11_03_000002_seed_fat_finance_organization.php`
- Modify: `database/seeders/MasterDivisionSeeder.php`
- Modify: `database/seeders/MasterDepartements.php`
- Test: `tests/Unit/RegionSeederCompatibilityTest.php` atau test master-data baru

**Interfaces:**
- Produces: division `FAT` dan department `Finance` dengan `division_id` FAT.

- [ ] Tulis test gagal untuk migration/seeder idempotent dan relasi FAT–Finance.
- [ ] Jalankan test dan pastikan gagal karena data migration belum ada.
- [ ] Implementasikan migration yang mencatat state sebelumnya untuk rollback aman serta seeder `updateOrCreate`.
- [ ] Jalankan test dan pastikan lulus.

### Task 2: Financial Approval Policy and Transaction

**Files:**
- Modify: `app/Models/User.php`
- Modify: `app/Services/ApprovalService.php`
- Modify: `app/Services/ApprovalProcessService.php`
- Test: `tests/Unit/ApprovalModuleIntegrityTest.php`
- Test: `tests/Unit/ParallelApprovalProcessTest.php`

**Interfaces:**
- Produces: `User::isFatFinanceApprover(): bool`, tambahan parameter finansial opsional pada `processApprovalAction()`, dan update customer yang aman dalam transaksi.

- [ ] Tulis test gagal untuk policy customer/FAT/Finance, approve wajib, reject tidak mengubah, actor lain tidak dapat menulis, dan credit limit nol.
- [ ] Jalankan test dan pastikan gagal karena policy belum tersedia.
- [ ] Implementasikan helper organisasi dan validasi ulang pada locked actor/company di service.
- [ ] Sesuaikan caller lama agar mengirim actor ID dan parameter pada posisi yang benar.
- [ ] Jalankan test fokus dan pastikan lulus.

### Task 3: Controller Validation and Conditional UI

**Files:**
- Modify: `app/Http/Controllers/Admin/ApprovalProcessController.php`
- Modify: `resources/views/admin/approvals/action.blade.php`
- Test: `tests/Unit/ApprovalModuleIntegrityTest.php`

**Interfaces:**
- Consumes: `User::isFatFinanceApprover()` dan signature service Task 2.
- Produces: flag `requiresFinancialTerms`, input `term_of_payment`, input `credit_limit`, serta request tervalidasi.

- [ ] Tulis test gagal untuk field kondisional, nilai default, rule required-if approve, dan forwarding ke service.
- [ ] Jalankan test dan pastikan gagal.
- [ ] Implementasikan flag di action page, rules kondisional di controller, dan teruskan nilai tervalidasi.
- [ ] Pertahankan SweetAlert2 serta tampilkan pesan konfirmasi bahwa nilai finansial akan disimpan.
- [ ] Jalankan test fokus dan pastikan lulus.

### Task 4: Verification

**Files:**
- Modify only if a failing test identifies a scoped defect.

- [ ] Jalankan syntax check seluruh PHP yang berubah.
- [ ] Jalankan seluruh test dengan `rtk php artisan test`.
- [ ] Jalankan `rtk git diff --check`.
- [ ] Review diff untuk memastikan vendor, reject, dan PIC lain tidak berubah perilakunya.


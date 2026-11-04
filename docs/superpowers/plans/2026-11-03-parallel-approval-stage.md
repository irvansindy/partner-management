# Parallel Approval Stage Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Mendukung beberapa PIC alternatif pada setiap tahap approval, dengan keputusan pertama menyelesaikan tahap dan membuka tahap berikutnya.

**Architecture:** Baris dengan `step_ordering` yang sama membentuk satu tahap pada template dan proses. Service mengunci seluruh baris tahap aktif ketika memproses keputusan, menandai PIC sejawat `SKIPPED`, lalu mengaktifkan semua PIC pada tahap berikutnya.

**Tech Stack:** Laravel, Eloquent, Blade, jQuery, Select2, PHPUnit, relational database migrations

**Spec:** `docs/superpowers/specs/2026-11-03-parallel-approval-stage-design.md`

## Global Constraints

- Tetap memakai tabel `approval_template_details` dan `approval_process_steps`.
- User hanya boleh muncul sekali dalam satu template.
- Template lama dengan satu PIC per tahap harus tetap kompatibel.
- Semua perubahan keputusan harus tetap atomik dengan row locking.
- UI mengikuti gaya modal aplikasi yang sudah ada dan aturan antislop selama pengerjaan.

## Review Focus

- Payload dengan user yang sama pada dua tahap harus ditolak sebelum penulisan database.
- Tahap kosong harus ditolak dengan error validasi yang dapat ditampilkan form.
- Dua PIC yang mengirim keputusan hampir bersamaan tidak boleh sama-sama menyelesaikan tahap.
- Tahap berikutnya yang memiliki beberapa PIC harus mengaktifkan semuanya, bukan hanya baris pertama.
- Template lama yang setiap tahapnya hanya memiliki satu PIC harus tetap menghasilkan urutan yang sama.

---

### Task 1: Database Constraints and Stage Status

**Files:**
- Create: `database/migrations/2026_11_03_000001_enable_parallel_approval_stages.php`
- Modify: `app/Models/ApprovalProcessStep.php`
- Test: `tests/Unit/ApprovalModuleIntegrityTest.php`

**Interfaces:**
- Produces: `ApprovalProcessStep::STATUS_SKIPPED = 4`, `skip(): void`, dan database yang mengizinkan beberapa baris pada satu `step_ordering`.

- [ ] **Step 1: Write the failing integrity tests**

Tambahkan test yang mengharuskan migrasi menghapus unique constraint `approval_template_steps_unique` dan `approval_process_steps_unique`, mempertahankan keunikan user per approval, serta model menyediakan `STATUS_SKIPPED`, `isSkipped()`, dan `skip()`.

- [ ] **Step 2: Run test to verify it fails**

Run: `rtk php artisan test tests/Unit/ApprovalModuleIntegrityTest.php`
Expected: FAIL karena migrasi dan status skipped belum ada.

- [ ] **Step 3: Implement the migration and model status**

Migration `up()` menjatuhkan dua constraint urutan; `down()` memulihkannya setelah memeriksa tidak ada urutan ganda. Tambahkan status dan helper skipped tanpa mengubah nilai status lama.

- [ ] **Step 4: Run test to verify it passes**

Run: `rtk php artisan test tests/Unit/ApprovalModuleIntegrityTest.php`
Expected: PASS.

### Task 2: Grouped Template API

**Files:**
- Modify: `app/Http/Controllers/ApprovalSettingController.php`
- Modify: `app/Models/ApprovalTemplate.php`
- Modify: `app/Models/ApprovalTemplateDetail.php`
- Create: `tests/Feature/ApprovalSettingParallelStageTest.php`

**Interfaces:**
- Consumes: repeated `step_ordering` support from Task 1.
- Produces: request field `approval_stages.*.user_ids`, grouped `existing_stages`, and atomic detail replacement.

- [ ] **Step 1: Write failing feature tests for grouped validation and persistence**

Cover successful storage of two users at stage 1 and three users at stage 2; rejection of an empty stage; rejection of a duplicated user across stages; rejection of unauthorized-role users; and compatibility with one user per stage.

- [ ] **Step 2: Run test to verify it fails**

Run: `rtk php artisan test tests/Feature/ApprovalSettingParallelStageTest.php`
Expected: FAIL because the controller only accepts flat `stagging_approval_name`.

- [ ] **Step 3: Implement grouped request validation and response**

Update `submitApprovalDetail(Request $request)` to validate 1–10 stages and 1–10 unique user IDs per stage, enforce global user uniqueness and allowed roles, then insert flattened rows with the array index as `step_ordering`. Update `fetchUserApproval()` to return details grouped and ordered as `existing_stages`.

- [ ] **Step 4: Run test to verify it passes**

Run: `rtk php artisan test tests/Feature/ApprovalSettingParallelStageTest.php`
Expected: PASS.

### Task 3: Runtime OR-stage Processing

**Files:**
- Modify: `app/Services/ApprovalService.php`
- Modify: `app/Models/ApprovalProcessStep.php`
- Create: `tests/Feature/ParallelApprovalProcessTest.php`

**Interfaces:**
- Consumes: `ApprovalProcessStep::skip()` and repeated step ordering.
- Produces: atomic OR-stage creation, approval, rejection, and progression behavior.

- [ ] **Step 1: Write failing process behavior tests**

Cover all members of the first stage becoming waiting; one approval skipping peers and activating every next-stage member; final-stage approval completing the process; rejection skipping peers and rejecting the process; a peer action after resolution being rejected; and the one-PIC legacy flow.

- [ ] **Step 2: Run test to verify it fails**

Run: `rtk php artisan test tests/Feature/ParallelApprovalProcessTest.php`
Expected: FAIL because creation/progression activates only one row and peers remain actionable.

- [ ] **Step 3: Implement locked group transitions**

In `createApprovalFromFormLink()`, set every minimum-order row to waiting. In `processApprovalAction()`, lock all rows for the active order, verify the process order and actor membership, resolve actor, skip waiting peers, and activate all rows at the next distinct order. Preserve current company organization assignment and terminal statuses.

- [ ] **Step 4: Run test to verify it passes**

Run: `rtk php artisan test tests/Feature/ParallelApprovalProcessTest.php`
Expected: PASS.

### Task 4: Multi-PIC Stage Editor and Full Verification

**Files:**
- Modify: `resources/views/admin/approval_setting/add_view_approval_detail_modal.blade.php`
- Modify: `resources/views/admin/approval_setting/approval_js.blade.php`
- Modify: `resources/views/admin/approval_setting/index.blade.php`
- Modify: `tests/Unit/ApprovalSettingModalUiTest.php`

**Interfaces:**
- Consumes: `existing_stages` and `approval_stages.*.user_ids` from Task 2.
- Produces: accessible responsive cards with a multi-select per stage.

- [ ] **Step 1: Write the failing UI contract test**

Require stage-oriented labels and help copy, multi-select names `approval_stages[index][user_ids][]`, Select2 multiple mode, add/remove-stage behavior, duplicate-user validation, loading/error states, and responsive styling.

- [ ] **Step 2: Run test to verify it fails**

Run: `rtk php artisan test tests/Unit/ApprovalSettingModalUiTest.php`
Expected: FAIL because the modal renders one single-select per row.

- [ ] **Step 3: Implement the stage editor**

Render each stage as one card with a labeled multiple Select2 control and remove button. Reindex field names and accessible labels after changes, hydrate grouped server data, require at least one unique PIC per stage, and keep the existing loading/error submission flow.

- [ ] **Step 4: Run focused tests**

Run: `rtk php artisan test tests/Unit/ApprovalSettingModalUiTest.php tests/Feature/ApprovalSettingParallelStageTest.php tests/Feature/ParallelApprovalProcessTest.php tests/Unit/ApprovalModuleIntegrityTest.php`
Expected: PASS.

- [ ] **Step 5: Run complete verification**

Run: `rtk php artisan test`
Expected: PASS with no failures; report any pre-existing failure by test name.


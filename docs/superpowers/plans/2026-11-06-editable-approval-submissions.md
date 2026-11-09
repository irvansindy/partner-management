# Editable Approval Submissions Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Allow organization-matched users to edit all submission sections only while an active approval has not reached its final stage.

**Architecture:** Add one editability rule to the form-link service and enforce it from both GET edit and PUT update endpoints. The list and detail views receive the same computed rule so their edit controls cannot drift from backend authorization. Approval stage comparisons use distinct `step_ordering` values so parallel approvers remain one stage.

**Tech Stack:** Laravel, Eloquent, Blade, PHPUnit.

**Spec:** User request in the 2026-11-06 conversation.

## Global Constraints

- Editing requires an exact department and division match between the authenticated user and the Form Link's department.
- Editing is allowed only for pending/in-progress approval processes whose active stage is before the maximum stage.
- A submission without an approval process, at the final active stage, approved, or rejected is not editable.
- The backend must enforce the same rule as the list and detail UI.
- Existing submission sections and the current visual system must be preserved.

## Review Focus

- Parallel approvers sharing the same final `step_ordering` must be treated as one final stage.
- A crafted PUT request must not bypass the locked state.
- A user from another division or department must receive a forbidden response.
- Missing approval steps must fail closed instead of enabling edits.
- Child-record and attachment IDs from another company must never be mutated.

---

### Task 1: Central submission editability rule

**Files:**
- Modify: `app/Models/FormLink.php`
- Modify: `app/Models/ApprovalProcess.php`
- Modify: `app/Services/FormLinkService.php`
- Test: `tests/Unit/FormLinkSubmissionEditabilityTest.php`

**Interfaces:**
- Produces: `ApprovalProcess::isBeforeFinalStage(): bool`
- Produces: `FormLinkService::canEditSubmission(FormLink $formLink, CompanyInformation $company, ?User $user = null): bool`
- Produces: `FormLinkService::authorizeSubmissionEdit(FormLink $formLink, CompanyInformation $company, ?User $user = null): void`

- [x] Write tests for organization matching, approval statuses, missing steps, final-stage locking, and parallel stages.
- [x] Run the focused tests and confirm they fail.
- [x] Add the Form Link department relation and stage-aware editability methods.
- [x] Run the focused tests and confirm they pass.

### Task 2: Enforce editability and secure nested updates

**Files:**
- Modify: `app/Http/Controllers/Admin/FormLinkController.php`
- Test: `tests/Unit/FormLinkSubmissionEditControllerTest.php`

**Interfaces:**
- Consumes: `FormLinkService::authorizeSubmissionEdit(...)`
- Produces: guarded edit/update endpoints and company-scoped nested mutations.

- [x] Write tests proving both endpoints use the centralized guard and nested IDs are company-scoped.
- [x] Run the focused tests and confirm they fail.
- [x] Replace the duplicated status checks with the service guard and harden nested record validation/mutation.
- [x] Run the focused tests and confirm they pass.

### Task 3: List and detail edit controls

**Files:**
- Modify: `app/Http/Controllers/Admin/FormLinkController.php`
- Modify: `resources/views/admin/form_links/submissions.blade.php`
- Modify: `resources/views/admin/form_links/submission_detail.blade.php`
- Test: `tests/Unit/FormLinkSubmissionEditUiTest.php`

**Interfaces:**
- Consumes: `FormLinkService::canEditSubmission(...)`
- Produces: accessible edit actions and an explicit locked state.

- [x] Write view/controller tests for conditional edit controls on list and detail pages.
- [x] Run the focused tests and confirm they fail.
- [x] Pass editability state to both views and add responsive, icon-led edit controls.
- [x] Run the focused tests and confirm they pass.

### Task 4: Regression verification

**Files:**
- Verify: `tests/Unit/FormLinkSubmissionEditabilityTest.php`
- Verify: `tests/Unit/FormLinkSubmissionEditControllerTest.php`
- Verify: `tests/Unit/FormLinkSubmissionEditUiTest.php`
- Verify: existing Form Link and approval tests.

- [x] Run all focused submission/approval tests.
- [x] Compile Blade views and run PHP syntax checks for modified PHP files.
- [x] Review the final diff for unrelated changes and report any pre-existing failures separately.

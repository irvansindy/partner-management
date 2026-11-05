# Approval Inbox Current Layout Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Render assigned pending approvals with the current Form Submissions layout while preserving assignment-scoped access.

**Architecture:** Keep `ApprovalProcessController::myApprovals()` and `ApprovalService::getPendingApprovalsForUser()` unchanged as the data boundary. Replace only the legacy Blade presentation with the current main layout and submission-table interaction pattern.

**Tech Stack:** Laravel Blade, Bootstrap styles already present in the application, jQuery already loaded by `layouts.main`.

**Spec:** `docs/superpowers/specs/2026-11-05-approval-inbox-current-layout-design.md`

## Global Constraints

- Never route assigned approvers to the owner-only full Form Submissions listing.
- Reuse the current product palette, spacing, typography, and action patterns.
- Keep the table usable on narrow screens through intentional horizontal scrolling and 44px mobile targets.
- Do not add dependencies.

## Review Focus

- Empty inbox renders a useful empty state without broken table numbering.
- Multiple pending steps across different Form Links remain in one assignment-scoped inbox.
- Parallel-stage assignments show the correct step and distinct stage progress.
- Detail and action URLs refer to the correct company, Form Link, and step.
- Search and action controls remain usable on mobile and by keyboard.

---

### Task 1: Replace the legacy approval inbox presentation

**Files:**
- Modify: `resources/views/admin/approvals/my_approvals.blade.php`
- Test: `tests/Unit/ApprovalInboxCurrentLayoutTest.php`

**Interfaces:**
- Consumes: `$pendingSteps` collection returned by `ApprovalService::getPendingApprovalsForUser(int $userId)`.
- Produces: current-layout inbox with detail and approval-action links.

- [ ] **Step 1: Write the failing view contract test**
  Assert the inbox extends `layouts.main`, includes `_alpha_styles`, renders a searchable table and both assignment-safe actions, and does not extend `adminlte::page`.
- [ ] **Step 2: Run the targeted test and verify it fails because the legacy view is still present**
  Run `rtk php artisan test tests/Unit/ApprovalInboxCurrentLayoutTest.php`.
- [ ] **Step 3: Replace the Blade markup with the current Form Submissions hierarchy**
  Preserve `$pendingSteps` as the only dataset; calculate each process's stage progress and render accessible detail/action links.
- [ ] **Step 4: Run targeted tests, syntax/view checks, and the full test suite**
  Expected: targeted test passes, Blade compiles, and all existing tests pass.
- [ ] **Step 5: Inspect the diff for accidental authorization or controller changes**
  Expected: this task changes only presentation, its test, and documentation.

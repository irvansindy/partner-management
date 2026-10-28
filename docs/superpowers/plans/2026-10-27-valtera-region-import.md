# Valtera Region Import Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add stable Indonesian region codes and an idempotent importer for Valtera CSV files without breaking existing internal primary keys.

**Architecture:** Keep existing numeric IDs as application foreign keys, add unique Kemendagri code columns, and map imported hierarchy by codes. Import four Valtera CSV files server-side, reject malformed/incomplete source data, and write postal codes in chunks.

**Tech Stack:** Laravel 8, PHP, MySQL, PHPUnit.

**Spec:** User request in this thread dated 2026-10-27.

## Global Constraints

- Existing primary keys and partner address data must remain valid.
- Import must be idempotent and observable.
- Valtera source files must be local inputs, not runtime frontend dependencies.
- Invalid or incomplete hierarchy rows must not be silently imported.

## Review Focus

- Missing source files produce an actionable error.
- Blank region names are rejected rather than creating unusable dropdown entries.
- Re-running the importer does not duplicate regions or postal codes.
- Postal rows cannot be attached to an unresolved parent.
- Rollback removes only newly added code columns.

---

### Task 1: Stable region codes

**Files:** migration, province/regency models, migration test.

- [ ] Write a failing test for code fillability and schema.
- [ ] Add nullable unique `code` columns without replacing internal IDs.
- [ ] Verify model and migration tests.

### Task 2: Valtera hierarchy and postal importer

**Files:** `app/Services/ValteraRegionImportService.php`, feature test.

- [ ] Write failing tests for a valid miniature dataset, idempotency, and invalid hierarchy data.
- [ ] Parse the four documented CSV files and resolve parents by codes.
- [ ] Upsert postal codes in bounded chunks and return counters/errors.
- [ ] Verify focused tests.

### Task 3: Artisan integration and operational documentation

**Files:** command and README documentation.

- [ ] Write a failing command test.
- [ ] Add `regions:import-valtera {directory}` with a dry-run validation option.
- [ ] Document source limitations and exact commands.
- [ ] Run the complete test suite and diff checks.

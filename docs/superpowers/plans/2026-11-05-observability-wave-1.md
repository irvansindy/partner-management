# Observability Wave 1 Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add a backward-compatible native observability foundation with correlation IDs, structured redacted audits, request telemetry, and resilient security logging.

**Architecture:** Expand the schema while retaining all legacy tables and readers. Focused services own audit context, redaction, diffs, and persistence; middleware owns request correlation and telemetry.

**Tech Stack:** PHP 8, Laravel 8, Eloquent, PHPUnit 9, MySQL/SQLite-compatible migrations.

**Spec:** `docs/superpowers/specs/2026-11-05-observability-wave-1-design.md`

## Global Constraints

- No new Composer dependency.
- No destructive schema contraction.
- No raw sensitive values in logs or audit records.
- Observability sink failure must not fail the business request.
- Preserve unrelated working-tree changes.

## Review Focus

- Invalid inbound request IDs must be replaced with UUIDs.
- Nested sensitive data must be redacted without mutating source arrays.
- Audit persistence failure must remain fail-open.
- Requests that throw before producing a response must still retain their request ID in exception context.
- Security-event database/channel/broadcast failures must not mask the intended rejection response.

---

### Task 1: Expand observability schema

**Files:** migrations for audit tables and compatibility columns; model classes; migration integrity test.

- [ ] Write failing schema-contract tests.
- [ ] Verify RED.
- [ ] Add reversible expand-only migrations and models.
- [ ] Verify GREEN.

### Task 2: Add request correlation

**Files:** `app/Http/Middleware/RequestIdMiddleware.php`, `app/Http/Kernel.php`, correlation feature test.

- [ ] Test generated, accepted, and rejected inbound IDs.
- [ ] Verify RED.
- [ ] Implement middleware and response header propagation.
- [ ] Verify GREEN.

### Task 3: Add audit services

**Files:** `config/audit.php`, `app/Audit/*`, audit service tests.

- [ ] Test nested redaction, diffs, context, successful persistence, and fail-open behavior.
- [ ] Verify RED.
- [ ] Implement the minimal service interfaces.
- [ ] Verify GREEN.

### Task 4: Enrich request telemetry

**Files:** `RequestLog`, `SecurityLogSanitizer`, `SanitizeInputMiddleware`, existing and new feature tests.

- [ ] Test actor, request ID, status, duration, response size, and absence of payload PII.
- [ ] Verify RED.
- [ ] Extend telemetry persistence without changing existing fields.
- [ ] Verify GREEN.

### Task 5: Repair security logging

**Files:** blocked-request compatibility migration, `config/logging.php`, `SanitizeInputMiddleware`, security tests.

- [ ] Test channel presence, schema compatibility, and fail-open sink behavior.
- [ ] Verify RED.
- [ ] Implement isolated safe sinks and rotating security channel.
- [ ] Verify GREEN.

### Task 6: Compatibility and release verification

- [ ] Run focused observability tests.
- [ ] Run migration dry checks on SQLite and migration status on Docker MySQL.
- [ ] Run the full test suite, Blade compilation, PHP syntax checks, and `git diff --check`.
- [ ] Document deployment and rollback commands.

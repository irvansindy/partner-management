# System Maintenance Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add a secure super-admin-only panel for running an explicit allowlist of Laravel maintenance commands.

**Architecture:** A focused service owns command metadata, locking, execution, output sanitization, and audit logging. A controller exposes only index and POST execute actions; a Blade page renders grouped commands. An idempotent data migration provisions the menu, permission, and super-admin assignment.

**Tech Stack:** Laravel 8, Spatie Laravel Permission, Blade, Bootstrap, PHPUnit.

**Spec:** `docs/superpowers/specs/2026-10-30-system-maintenance-design.md`

## Global Constraints

- No arbitrary command or argument input.
- Routes require `auth` and `role:super-admin`; execution is POST with CSRF.
- Commands are serialized with application locks and output is escaped and length-limited.
- Destructive database, secret, shell, generator, and maintenance-mode commands are excluded.

## Review Focus

- Unknown command identifiers must never reach Artisan.
- Double-click/concurrent execution must return a controlled busy result.
- Command exceptions must not expose stack traces or secrets to the browser.
- Backup output must not exceed the session payload limit.
- Migration reruns must not duplicate menu, permission, or role assignments.

---

### Task 1: Command execution service

**Files:**
- Create: `app/Services/SystemMaintenanceService.php`
- Test: `tests/Unit/SystemMaintenanceServiceTest.php`

**Interfaces:**
- Produces: `commands(): array`, `groupedCommands(): array`, `run(string $key, int $userId): array`.

- [ ] Write tests for the exact allowlist, rejection of unknown keys, lock use, output sanitization, and absence of forbidden commands.
- [ ] Run the focused test and verify RED.
- [ ] Implement the service with `Cache::lock`, `Artisan::call`, capped output, and structured logging.
- [ ] Run the focused test and verify GREEN.

### Task 2: Protected routes, controller, and interface

**Files:**
- Create: `app/Http/Controllers/Admin/SystemMaintenanceController.php`
- Create: `resources/views/admin/system_maintenance/index.blade.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/SystemMaintenanceAccessTest.php`

**Interfaces:**
- Consumes: `SystemMaintenanceService::groupedCommands()` and `run()`.

- [ ] Write tests asserting GET/POST routes, POST-only execution, super-admin middleware, allowlist validation, CSRF form markup, escaped output, confirmation, and accessible button states.
- [ ] Run the focused test and verify RED.
- [ ] Implement controller, routes, and grouped Blade UI.
- [ ] Run the focused test and Blade compilation; verify GREEN.

### Task 3: Menu and permission provisioning

**Files:**
- Create: `database/migrations/2026_10_30_000002_add_system_maintenance_menu.php`
- Modify: `database/seeders/MenusSeeder.php`
- Test: `tests/Unit/SystemMaintenanceProvisioningTest.php`

**Interfaces:**
- Produces: permission `system-maintenance` and menu URL `admin/system-maintenance`, assigned only to `super-admin`.

- [ ] Write an idempotency/assignment structure test and verify RED.
- [ ] Implement migration and seeder entry without fixed primary-key assumptions.
- [ ] Run focused tests, migration, permission cache reset, full suite, Blade compilation, and `git diff --check`.

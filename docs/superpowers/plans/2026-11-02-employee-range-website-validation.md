# Employee Range and Website Validation Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Store controlled employee ranges and validate that optional public website URLs are safely reachable over HTTPS.

**Architecture:** A shared employee-range catalog supplies UI and validation values. A dedicated validation rule validates DNS/IP safety and HTTPS reachability, while the controller remains responsible for normalization and request validation.

**Tech Stack:** Laravel 8, Blade, Select2, Laravel HTTP client, PHPUnit, MySQL.

**Spec:** `docs/superpowers/specs/2026-11-02-employee-range-website-validation-design.md`

## Global Constraints

- Accept exactly six employee ranges.
- Reject private/reserved network targets and unsafe redirects.
- Use HTTPS only and bounded timeouts.
- Keep validation messages bilingual for the active-locale renderer.

## Review Focus

- Existing numeric employee values must survive the schema migration.
- Forged employee values must fail server validation.
- Hosts resolving to mixed public and private addresses must be rejected.
- Redirect destinations must receive the same SSRF validation as initial URLs.
- Timeout or transport failures must become validation errors, not server errors.

---

### Task 1: Employee range contract and schema

**Files:**
- Create: `app/Support/EmployeeRange.php`
- Create: `database/migrations/2026_11_02_000001_change_total_employee_to_range_string.php`
- Modify: `resources/views/cs_vendor/form/master_information.blade.php`
- Modify: `resources/views/cs_vendor/partner_js.blade.php`
- Modify: `app/Http/Controllers/PublicFormController.php`
- Test: `tests/Unit/PublicFormEmployeeRangeTest.php`

**Interfaces:**
- Produces: `EmployeeRange::values(): array`.

- [ ] Write tests for the six options and server allowlist.
- [ ] Run the focused test and verify RED.
- [ ] Implement the catalog, migration, Select2 markup, and validation rule.
- [ ] Run focused tests and verify GREEN.

### Task 2: Safe website reachability rule

**Files:**
- Create: `app/Rules/ReachablePublicHttpsUrl.php`
- Modify: `app/Http/Controllers/PublicFormController.php`
- Test: `tests/Unit/ReachablePublicHttpsUrlTest.php`

**Interfaces:**
- Consumes: normalized URL from `PublicHttpsUrl::normalize()`.
- Produces: a Laravel validation rule that rejects invalid DNS, non-public IPs, unsafe redirects, HTTP failures, and timeouts.

- [ ] Write tests for invalid host, private IP, transport failure, success, and redirect safety.
- [ ] Run the focused test and verify RED.
- [ ] Implement the rule with injectable DNS and HTTP callbacks for deterministic tests.
- [ ] Integrate it after `PublicHttpsUrl` in submission validation.
- [ ] Run focused tests and verify GREEN.

### Task 3: Regression verification

**Files:**
- Modify tests only if an existing assertion intentionally changes.

- [ ] Compile Blade views.
- [ ] Run the full test suite.
- [ ] Run `git diff --check`.

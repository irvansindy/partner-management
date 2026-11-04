# Admin Language Switcher Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the floating admin locale dropdown with an accessible ID/EN switch in the AdminLTE top navbar for every role.

**Architecture:** A reusable Blade partial owns switch markup, styling, and change navigation. Both AdminLTE navbar variants include it before the user menu; the page shell removes the obsolete floating control.

**Tech Stack:** Laravel Blade, AdminLTE/Bootstrap 4, existing `locale.switch` route, PHPUnit.

**Spec:** `docs/superpowers/specs/2026-11-04-admin-language-switcher-design.md`

## Global Constraints

- No new frontend dependency.
- Switch is available without role or permission checks.
- Unchecked means `id`; checked means `en`.
- Every navigation includes the current internal path through `return_to`.
- Preserve keyboard operation and a visible focus state.

## Review Focus

- Indonesian and English active states must render with the correct checked value.
- Switch navigation must retain the current admin URL.
- Both standard and top-navigation AdminLTE navbars must include exactly one switch.
- The old fixed switch must not remain in the DOM or CSS.
- The control must have an accessible label and keyboard-focus treatment.

---

### Task 1: Admin Navbar Language Switch

**Files:**
- Create: `resources/views/vendor/adminlte/partials/navbar/locale-switch.blade.php`
- Modify: `resources/views/vendor/adminlte/partials/navbar/navbar.blade.php`
- Modify: `resources/views/vendor/adminlte/partials/navbar/navbar-layout-topnav.blade.php`
- Modify: `resources/views/vendor/adminlte/page.blade.php`
- Create: `tests/Unit/AdminLocaleSwitchUiTest.php`

**Interfaces:**
- Consumes: named route `locale.switch`, `app()->getLocale()`, and `request()->getRequestUri()`.
- Produces: one navbar `<li>` containing `#admin_locale_switch`, where checked maps to English and change navigation maps to ID/EN URLs.

- [ ] **Step 1: Write the failing UI source test**

Assert the partial contains `role="switch"`, an accessible label, ID/EN labels, correct checked-state expression, both locale URLs with `return_to`, and a change handler. Assert both navbar variants include the partial and `page.blade.php` no longer contains `floating-locale-btn`.

- [ ] **Step 2: Run the focused test and verify RED**

Run: `rtk php artisan test --filter=AdminLocaleSwitchUiTest`

Expected: FAIL because the partial and navbar includes do not exist.

- [ ] **Step 3: Implement the reusable switch and navbar integration**

Create the partial with a native checkbox using `role="switch"`, local scoped styles, ID/EN state labels, JSON-encoded internal locale URLs, and a change listener. Include it before the user/logout block in both navbar variants. Remove the floating control and styles from the page shell.

- [ ] **Step 4: Verify focused behavior and Blade compilation**

Run: `rtk php artisan test --filter='AdminLocaleSwitchUiTest|LocaleSwitchTest'`

Run: `rtk php artisan view:cache`

Expected: tests pass and Blade templates compile.

- [ ] **Step 5: Run regression checks**

Run: `rtk php artisan test`

Run: `git diff --check`

Expected: full suite passes and diff check is clean.

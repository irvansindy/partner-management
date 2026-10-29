# Public Address UX Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Improve international address, contact-number, website, and interactive map behavior in the public partner form.

**Architecture:** Extend the existing Laravel Nominatim proxy with a validated reverse endpoint, then enhance the existing Blade/JavaScript address component without introducing dependencies. Static regression tests pin the generated UI behavior while feature tests verify the endpoint contract.

**Tech Stack:** Laravel 8, Blade, jQuery, Select2, intl-tel-input, Leaflet, SweetAlert2, PHPUnit.

**Spec:** `docs/superpowers/specs/2026-10-28-public-address-ux-design.md`

## Global Constraints

- Server-side validation remains authoritative.
- No new dependency.
- Preserve current input names, dynamic rows, and draft restoration.
- Reverse-geocode only on map click or marker drag end.
- Preserve coordinates when reverse-geocoding fails.

## Review Focus

- Latitude and longitude bounds must reject forged reverse requests.
- Rapid pin changes must not allow an older response to overwrite a newer selection.
- Dynamic branch and operational rows must receive the same flag, phone, and map behavior.
- A blocked reverse lookup must leave the selected coordinates intact.
- Mobile phone inputs must not overlap flag controls.

---

### Task 1: Reverse-geocoding backend

**Files:**
- Modify: `routes/web.php`
- Modify: `app/Http/Controllers/GeocodeController.php`
- Test: `tests/Feature/PublicReverseGeocodeTest.php`

**Interfaces:**
- Produces: `POST reverse-map` accepting numeric `latitude` and `longitude`, returning `data.display_name`, `data.latitude`, and `data.longitude`.

- [ ] Write failing endpoint tests for route existence, coordinate bounds, successful response, and safe provider failure.
- [ ] Run the focused tests and confirm failure because the endpoint is absent.
- [ ] Implement the route and `GeocodeController::reverse(Request)` with validated coordinates and the existing geocoder service.
- [ ] Run focused tests to green.

### Task 2: Country and phone presentation

**Files:**
- Modify: `resources/views/cs_vendor/form/address.blade.php`
- Modify: `resources/views/cs_vendor/partner_js.blade.php`
- Modify: `resources/views/public/form/css.blade.php`
- Test: `tests/Unit/InternationalPhoneInputTest.php`
- Test: `tests/Unit/PublicAddressMarkupTest.php`

**Interfaces:**
- Produces: Select2 `templateResult/templateSelection` flag rendering and non-overlapping intl-tel-input controls.

- [ ] Add failing static assertions for flag rendering hooks, national placeholders, and reserved input padding.
- [ ] Run focused tests to confirm the hooks are absent.
- [ ] Implement shared country option rendering and phone placeholder/layout behavior.
- [ ] Run focused tests to green.

### Task 3: Website frontend guard

**Files:**
- Modify: `resources/views/cs_vendor/form/master_information.blade.php`
- Modify: `resources/views/cs_vendor/partner_js.blade.php`
- Modify: `resources/views/public/form/js.blade.php`
- Test: `tests/Unit/PublicHttpsUrlTest.php`

**Interfaces:**
- Produces: `data-public-website` input behavior aligned with `PublicHttpsUrl` server validation.

- [ ] Add failing assertions for typing sanitization, blur feedback, and wizard-step validation.
- [ ] Run the focused test to confirm failure.
- [ ] Implement the input hook and reuse `isValidWebsiteHost` in step validation.
- [ ] Run the focused test to green.

### Task 4: Address guidance and movable marker

**Files:**
- Modify: `resources/views/cs_vendor/form/address.blade.php`
- Modify: `resources/views/public/form/js.blade.php`
- Test: `tests/Unit/PublicAddressMarkupTest.php`

**Interfaces:**
- Consumes: `reverse-map` from Task 1.
- Produces: one-time Address guidance and map click/drag reverse-geocoding behavior.

- [ ] Add failing assertions for one-time guidance, draggable marker, map click, reverse lookup, request sequencing, and coordinate preservation.
- [ ] Run focused tests to confirm failure.
- [ ] Implement guidance event handling and per-row marker synchronization.
- [ ] Run focused tests, Blade compilation, and the complete test suite.


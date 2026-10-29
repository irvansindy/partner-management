# Address Map Fullscreen Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make every address map immediately draggable and fullscreen-capable without creating false submitted coordinates.

**Architecture:** Reuse the existing per-row Leaflet state and marker. Add a map shell containing an accessible fullscreen button, native Fullscreen API handling with a CSS fallback, and a temporary-marker state that becomes selected only after user interaction.

**Tech Stack:** Blade, Leaflet, vanilla JavaScript, CSS, PHPUnit.

**Spec:** `docs/superpowers/specs/2026-10-29-address-map-fullscreen-design.md`

## Global Constraints

- No new dependency.
- Initial marker coordinates are not submitted.
- Existing search, click, drag, draft restore, and reverse-geocoding behavior remains intact.
- Fullscreen works for dynamically added rows.

## Review Focus

- Leaflet tiles must resize after native and fallback fullscreen transitions.
- `Esc` must close the CSS fallback.
- Clearing a failed search must restore a temporary marker without coordinates.
- Fullscreen button must not trigger map click placement.
- Multiple address maps must maintain independent fullscreen state.

---

### Task 1: Flexible and fullscreen address map

**Files:**
- Modify: `resources/views/cs_vendor/form/address.blade.php`
- Modify: `resources/views/public/form/css.blade.php`
- Test: `tests/Unit/PublicAddressMarkupTest.php`
- Test: `tests/Unit/PublicFormResponsiveMarkupTest.php`

- [ ] Add failing assertions for the temporary draggable marker, fullscreen shell/button, native API, fallback, Escape handling, and Leaflet resize.
- [ ] Run focused tests and confirm failure.
- [ ] Implement the map interaction and presentation while preserving current field names and reverse-geocoding.
- [ ] Run focused tests, compile Blade, and run the complete suite.


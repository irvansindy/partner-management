# Bilingual Runtime Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make Indonesian and English switching consistent across the public registration form, JavaScript validation, dates, and translation administration.

**Architecture:** Laravel's native translator and session locale remain the runtime source of truth. Translation Manager edits and exports language files behind super-admin authorization, while Google Translate remains an optional administrative helper configured through the environment.

**Tech Stack:** Laravel 8, Blade, jQuery, PHPUnit 9, Barryvdh Translation Manager, Carbon, Tanmuhittin Laravel Google Translate

**Spec:** `docs/superpowers/specs/2026-11-03-bilingual-runtime-design.md`

## Global Constraints

- Runtime translation must not depend on an external API or database translation lookup.
- Supported locales remain exactly `id` and `en`.
- Google credentials must never be committed.
- Admin-created Form Link titles and descriptions remain untranslated data.
- Preserve the existing public-form visual system; add only a compact keyboard-accessible language control.
- Do not commit changes unless the user explicitly authorizes a commit.

## Review Focus

- A forged session locale must fall back to the configured default instead of reaching Blade or Carbon unchanged; covered in Task 1.
- Switching locale from a public token URL must return to that exact form URL; covered in Task 1.
- A non-super-admin authenticated user must receive 403 for every Translation Manager route, including POST endpoints; covered in Task 2.
- JavaScript-created rows and validation errors must use the active locale, not only initial static markup; covered in Task 4.
- Missing Google credentials must not break page rendering or ordinary language switching; covered in Task 5.

---

### Task 1: Locale Runtime and Carbon Synchronization

**Files:**
- Modify: `app/Http/Middleware/SetLocale.php`
- Modify: `app/Providers/AppServiceProvider.php`
- Modify: `app/Http/Controllers/LocaleController.php`
- Test: `tests/Feature/LocaleSwitchTest.php`

**Interfaces:**
- Consumes: `config('locales.supported')`, `config('app.locale')`, session key `locale`.
- Produces: `SetLocale::handle(Request $request, Closure $next)` setting both `App` and `Carbon` locale to an allowlisted value.

- [ ] **Step 1: Write failing feature tests**

Add tests named `it_persists_a_supported_locale_and_returns_to_the_previous_url`, `it_rejects_an_unsupported_locale`, and `it_falls_back_for_a_forged_session_locale`. Assert Laravel and Carbon resolve to the same expected locale inside a test route protected by `web` middleware.

- [ ] **Step 2: Run tests and verify RED**

Run: `rtk php artisan test --filter=LocaleSwitchTest`

Expected: forged locale or Carbon synchronization assertion fails.

- [ ] **Step 3: Implement the minimal runtime fix**

Remove the hardcoded locale mutation from `AppServiceProvider::boot()`. In `SetLocale::handle()`, allowlist the session value against `array_keys(config('locales.supported'))`, fall back to `config('app.locale')`, then call `App::setLocale($locale)` and `Carbon::setLocale($locale)`. Keep `LocaleController::switch()` allowlisted and redirecting back.

- [ ] **Step 4: Run focused tests and verify GREEN**

Run: `rtk php artisan test --filter=LocaleSwitchTest`

Expected: all locale-switch tests pass.

### Task 2: Secure Translation Administration

**Files:**
- Modify: `config/translation-manager.php`
- Delete: `app/Http/Controllers/CustomTranslationController.php`
- Test: `tests/Feature/TranslationManagerAccessTest.php`

**Interfaces:**
- Consumes: Spatie role middleware and role `super-admin`.
- Produces: all package routes under `/translations` using middleware `web`, `auth`, and `role:super-admin`.

- [ ] **Step 1: Write failing access tests**

Test guest redirect, authenticated non-super-admin 403, and super-admin access beyond authorization for both `GET /translations` and one POST route. Assert route actions still use the package controller.

- [ ] **Step 2: Run tests and verify RED**

Run: `rtk php artisan test --filter=TranslationManagerAccessTest`

Expected: authenticated non-super-admin is currently allowed past middleware.

- [ ] **Step 3: Add role middleware and remove dead controller**

Add `role:super-admin` to `translation-manager.route.middleware`. Remove `CustomTranslationController.php` only after a repository search confirms no references.

- [ ] **Step 4: Clear route/config cache and verify GREEN**

Run: `rtk php artisan optimize:clear`

Run: `rtk php artisan test --filter=TranslationManagerAccessTest`

Expected: all access tests pass and package routes remain registered.

### Task 3: Public Language Switcher and Static Copy

**Files:**
- Modify: `resources/lang/id/messages.php`
- Modify: `resources/lang/en/messages.php`
- Modify: `resources/views/layouts/public.blade.php`
- Modify: `resources/views/public/form_index.blade.php`
- Modify: `resources/views/public/form_success.blade.php`
- Modify: `resources/views/public/form_closed.blade.php`
- Modify: `resources/views/cs_vendor/index.blade.php`
- Test: `tests/Feature/PublicFormLocaleUiTest.php`
- Test: `tests/Unit/PublicFormLocalizedValidationTest.php`

**Interfaces:**
- Consumes: `route('locale.switch', $locale)` and `config('locales.supported')`.
- Produces: translation keys for public shell, wizard labels, actions, success/closed pages, and a topbar locale menu with `aria-current` on the active locale.

- [ ] **Step 1: Write failing rendered-UI tests**

Render a public form in `id` and `en`; assert the header, wizard labels, Previous/Continue buttons, and locale control show the selected language. Assert the active locale has `aria-current="true"` and links preserve the normal redirect-back flow.

- [ ] **Step 2: Run tests and verify RED**

Run: `rtk php artisan test --filter='PublicFormLocaleUiTest|PublicFormLocalizedValidationTest'`

Expected: hardcoded English/Indonesian copy and missing public switcher fail assertions.

- [ ] **Step 3: Add paired translation keys**

Add the same keys to both `messages.php` files for portal shell, registration heading, wizard steps, actions, success/closed states, survey controls, and accessible language labels. Keep key names semantic and grouped by a `Public ...` prefix consistent with the existing flat file.

- [ ] **Step 4: Replace static hardcoded copy and add the language control**

Use `__()` or `@lang` in the listed Blade views. Build the switcher from `config('locales.supported')`, expose the language name as visible text, set `lang`, `hreflang`, and `aria-current`, and retain existing topbar spacing and responsive behavior.

- [ ] **Step 5: Verify GREEN and compile Blade**

Run: `rtk php artisan test --filter='PublicFormLocaleUiTest|PublicFormLocalizedValidationTest'`

Run: `rtk php artisan view:cache`

Expected: tests pass and Blade compilation succeeds.

### Task 4: Dynamic JavaScript and Validation Copy

**Files:**
- Modify: `resources/lang/id/messages.php`
- Modify: `resources/lang/en/messages.php`
- Modify: `resources/views/public/form/js.blade.php`
- Modify: `resources/views/cs_vendor/partner_js.blade.php`
- Modify: `resources/views/cs_vendor/form/address.blade.php`
- Test: `tests/Unit/PublicFormBilingualJavascriptTest.php`

**Interfaces:**
- Consumes: server-rendered `@json(__('messages.key'))` copy.
- Produces: one `partnerFormCopy` JavaScript object used by wizard navigation, dynamic row templates, field validation, address search, map help, and website reachability states.

- [ ] **Step 1: Write failing source/render tests**

Assert both locales render different values for wizard labels and errors. Assert user-facing Indonesian/English literals no longer remain in the relevant JavaScript paths except server bilingual validation payload parsing.

- [ ] **Step 2: Run tests and verify RED**

Run: `rtk php artisan test --filter=PublicFormBilingualJavascriptTest`

Expected: hardcoded wizard, address, and validation strings fail the source assertions.

- [ ] **Step 3: Add translation copy and replace literals**

Populate `partnerFormCopy` through `@json()`. Replace hardcoded strings in wizard arrays, required/format validation, loading/error states, dynamic liable/contact/bank/address rows, map search notifications, and button labels. Preserve HTML escaping when interpolating labels into toast markup.

- [ ] **Step 4: Verify GREEN**

Run: `rtk php artisan test --filter='PublicFormBilingualJavascriptTest|PublicWebsiteStepValidationTest|PublicFormLocalizedValidationTest'`

Expected: all dynamic-copy and existing validation tests pass.

### Task 5: Optional Google Translate Configuration and Parity Guard

**Files:**
- Create: `config/laravel_google_translate.php`
- Modify: `.env.example`
- Create: `tests/Unit/BilingualConfigurationTest.php`

**Interfaces:**
- Consumes: nullable `GOOGLE_TRANSLATE_API_KEY` environment variable.
- Produces: package configuration that leaves runtime translation functional when the API key is absent, plus a test enforcing identical `messages.php` key sets.

- [ ] **Step 1: Write failing configuration tests**

Assert the config file exists, reads `GOOGLE_TRANSLATE_API_KEY`, defaults to null, defines base locale `id`, target locale `en`, and that ID/EN message key sets are identical.

- [ ] **Step 2: Run tests and verify RED**

Run: `rtk php artisan test --filter=BilingualConfigurationTest`

Expected: configuration-file assertions fail.

- [ ] **Step 3: Add environment-backed package configuration**

Publish the package-equivalent config into source without credentials and add an empty documented `GOOGLE_TRANSLATE_API_KEY=` entry to `.env.example`. Do not invoke the external API in tests.

- [ ] **Step 4: Run test and verify GREEN**

Run: `rtk php artisan test --filter=BilingualConfigurationTest`

Expected: configuration and parity assertions pass with no API key.

### Task 6: Regression Verification

**Files:**
- Verify all files modified by Tasks 1-5.

**Interfaces:**
- Consumes: completed bilingual runtime tasks.
- Produces: evidence that locale changes did not regress public submission or admin routes.

- [ ] **Step 1: Run the full automated suite**

Run: `rtk php artisan test`

Expected: all tests pass.

- [ ] **Step 2: Inspect routes and compile views**

Run: `rtk php artisan route:list --path=translations`

Run: `rtk php artisan route:list --name=locale.switch`

Run: `rtk php artisan view:cache`

Expected: translation routes carry authorization middleware, locale route exists, and Blade compilation succeeds.

- [ ] **Step 3: Run static checks**

Run: `git diff --check`

Run a repository search for `CustomTranslationController`, hardcoded public wizard copy, and missing language keys. Expected: no dead-controller references, no scoped hardcoded copy, and ID/EN parity remains exact.

- [ ] **Step 4: Manual browser checklist**

Open a public form, switch ID to EN and back, navigate every wizard step, trigger website and attachment validation, add dynamic rows, search and drag the address map, then verify success/closed pages. Confirm keyboard operation and focus visibility of the locale switcher.


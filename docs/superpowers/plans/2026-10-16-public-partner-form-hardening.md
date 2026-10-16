# Public Partner Form Hardening Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Harden the public partner registration flow with consistent validation, international phone/address support, controlled Indonesian postal codes, Super-Admin CSV import, and private signature-validated attachments.

**Architecture:** Keep the public controller as the transaction coordinator and move reusable decisions into focused rules/services: repository-owned country metadata, an HTTPS URL rule, a postal-code importer, and a private attachment service. Indonesian addresses reference local administrative masters while foreign addresses retain validated text; attachments use a private disk and authenticated streaming routes.

**Tech Stack:** Laravel 8, PHP 7.3+/8.x, Blade, jQuery, Select2, Bootstrap/AdminLTE, PHPUnit 9, Laravel filesystem and validation APIs.

**Spec:** `docs/superpowers/specs/2026-10-16-public-partner-form-hardening-design.md`

## Global Constraints

- Backend validation is authoritative; frontend rules and dynamic rows must mirror it.
- Established year is `1900..current year`; total employees is `1..1,000,000`.
- General single-line text is at most 255 characters; addresses are at most 1,000 characters.
- Website values must normalize to and remain public `https://` URLs; never perform an outbound request.
- Phone local numbers contain digits only; persisted values remain calling code plus local number.
- All countries are supported, but only Indonesia uses local province, regency, and postal-code masters.
- Postal import accepts UTF-8 CSV with `province,regency,district,village,postal_code` and is Super-Admin-only in the web UI.
- Files are limited to PDF/JPEG/PNG, 5 MB each, 10 per submission, and images at most 6000×6000 pixels.
- New attachments use UUID names on a private disk; client MIME, extension, and filename do not determine acceptance.
- Existing company/address/attachment records remain readable through additive migrations and a controlled legacy migration command.
- Public 500 responses never disclose exception messages, paths, or stack traces.
- Do not add a new third-party package when Laravel/PHP standard APIs already satisfy the requirement.

## Review Focus

- Unicode names with repeated/internal spaces must pass while punctuation and invisible control characters fail; pin this in Task 1 validation tests.
- URLs containing credentials, Unicode/punycode ambiguity, IP literals, ports, fragments, or whitespace must be handled deterministically; pin this in Task 1 URL-rule tests.
- Address arrays with missing, reordered, or forged role/country/master IDs must fail instead of persisting misaligned rows; pin this in Task 5 submission tests.
- CSV with BOM, duplicate rows, mixed casing, invalid UTF-8, and an unexpected database failure must produce deterministic counts or a full rollback; pin this in Task 3 importer tests.
- Polyglot/spoofed files, truncated images, cleanup after a post-storage database error, path traversal filenames, and unauthorized downloads must fail safely; pin these in Tasks 6 and 7 tests.

---

### Task 1: Canonical Text, Employee, Bank, and HTTPS Validation

**Files:**
- Create: `app/Rules/PublicHttpsUrl.php`
- Modify: `app/Http/Controllers/PublicFormController.php`
- Modify: `resources/views/cs_vendor/form/master_information.blade.php`
- Modify: `resources/views/cs_vendor/form/contact.blade.php`
- Modify: `resources/views/cs_vendor/form/bank.blade.php`
- Modify: `resources/views/cs_vendor/form/survey.blade.php`
- Modify: `resources/views/public/form/js.blade.php`
- Test: `tests/Unit/PublicHttpsUrlTest.php`
- Test: `tests/Unit/PublicFormTextValidationTest.php`

**Interfaces:**
- Consumes: Existing `PublicFormController::validateSubmission(Request $request, $formType)` and the form's `data-alpha-spaces`/year hooks.
- Produces: `PublicHttpsUrl implements Rule`; normalized `website_address`; one shared frontend contract for character filtering and maximum lengths.

- [ ] **Step 1: Write failing backend validation tests**

Add test cases asserting employee values `1` and `1000000` pass while `0` and `1000001` fail; bank names `Bank BCA 2`, `PT. Bank-ABC` pass while `_`, `/`, and control characters fail; all specified text fields reject over 255 characters; addresses reject over 1,000 characters; and Unicode letters/spaces remain valid.

- [ ] **Step 2: Write failing HTTPS rule tests**

Assert `example.com` normalizes to `https://example.com`, an existing HTTPS path/query remains stable, and `http`, FTP, localhost, single-label hosts, IPv4/IPv6 literals, credentials, whitespace, malformed ports, and malformed hosts fail. Explicitly cover fragments and internationalized hosts so their accepted/rejected behavior is fixed by the test rather than environment-dependent.

- [ ] **Step 3: Run the focused tests and confirm failure**

Run: `rtk php artisan test tests/Unit/PublicHttpsUrlTest.php tests/Unit/PublicFormTextValidationTest.php`

Expected: FAIL on the missing rule and the new limits/contracts.

- [ ] **Step 4: Implement `PublicHttpsUrl` and normalize before validation**

Implement `PublicHttpsUrl::normalize(?string $value): ?string`, `passes($attribute, $value): bool`, and `message(): string`. Use `parse_url`, `filter_var`, and explicit host checks; do not resolve DNS or fetch the URL. In `validateSubmission`, merge the normalized value into the request before applying the rule.

- [ ] **Step 5: Align backend rules and bilingual messages**

Set the exact numeric/length/character contracts from the spec, including bank regex `^[\p{L}\p{N} .-]+$` and address `max:1000`. Give any currently unbounded textarea an explicit field-specific backend maximum.

- [ ] **Step 6: Align initial and dynamic frontend fields**

Add matching `min`, `max`, `maxlength`, `pattern`, and input-filter hooks. Ensure cloned contact/bank/liable-person rows inherit the same attributes and field-level error targets.

- [ ] **Step 7: Run tests and commit**

Run: `rtk php artisan test tests/Unit/PublicHttpsUrlTest.php tests/Unit/PublicFormTextValidationTest.php`

Expected: PASS.

Commit: `git commit -m "fix: align public form validation contracts"`

### Task 2: Repository-Owned Country Catalog and Compact Phone Controls

**Files:**
- Create: `config/countries.php`
- Create: `app/Support/CountryCatalog.php`
- Modify: `app/Http/Controllers/PublicFormController.php`
- Modify: `resources/views/public/form_index.blade.php`
- Modify: `resources/views/cs_vendor/form/contact.blade.php`
- Modify: `resources/views/cs_vendor/form/address.blade.php`
- Modify: `resources/views/cs_vendor/partner_js.blade.php`
- Modify: `resources/views/public/form/css.blade.php`
- Test: `tests/Unit/CountryCatalogTest.php`
- Test: `tests/Unit/InternationalPhoneInputTest.php`

**Interfaces:**
- Consumes: Country metadata rows shaped as `['code' => 'ID', 'name' => 'Indonesia', 'calling_code' => '+62']`.
- Produces: `CountryCatalog::all(): array`, `CountryCatalog::find(string $iso): ?array`, and Blade JSON `window.partnerCountries` used by all phone and country Select2 controls.

- [ ] **Step 1: Write failing catalog and rendering tests**

Assert the catalog has unique ISO alpha-2 codes, valid `+` calling codes, includes every ISO country intended by the checked-in dataset, resolves `ID`, and renders flag/ISO/calling code/search-name hooks for initial and dynamic phone controls.

- [ ] **Step 2: Run focused tests and confirm failure**

Run: `rtk php artisan test tests/Unit/CountryCatalogTest.php tests/Unit/InternationalPhoneInputTest.php`

Expected: FAIL because the catalog and unified renderer do not exist.

- [ ] **Step 3: Add the country metadata source and catalog API**

Keep metadata static in `config/countries.php`; validate lookup keys inside `CountryCatalog`. Pass the catalog from `PublicFormController::show()` rather than duplicating country arrays in Blade/JavaScript.

- [ ] **Step 4: Use one Select2 phone renderer everywhere**

Implement `initializeInternationalPhone(container)` and `formatCountryOption(country)` in `partner_js.blade.php`. Render emoji flag + ISO + calling code + searchable country name; collapsed text is flag/ISO/calling code. Strip non-digits from the adjacent local-number input and compose the submitted hidden/full value.

- [ ] **Step 5: Make phone rows responsive and accessible**

Give the selector a bounded desktop width, let the number column use `min-width: 0`, and stack without clipping on small screens. Preserve labels, keyboard operation, unique IDs, and error targets for added rows.

- [ ] **Step 6: Run tests and commit**

Run: `rtk php artisan test tests/Unit/CountryCatalogTest.php tests/Unit/InternationalPhoneInputTest.php tests/Unit/PublicFormPhoneValidationTest.php`

Expected: PASS.

Commit: `git commit -m "feat: add international country and phone controls"`

### Task 3: Indonesian Postal-Code Schema, Lookup, and Import Service

**Files:**
- Create: `database/migrations/2026_10_16_000001_create_postal_codes_table.php`
- Create: `app/Models/PostalCode.php`
- Create: `app/Services/PostalCodeImportService.php`
- Create: `app/Console/Commands/ImportPostalCodesCommand.php`
- Modify: `app/Models/Provinces.php`
- Modify: `app/Models/Regencies.php`
- Create: `tests/Feature/PostalCodeImportTest.php`
- Create: `tests/Feature/ImportPostalCodesCommandTest.php`

**Interfaces:**
- Consumes: A readable CSV path/stream with the exact five-column header from the spec and existing `provinces.id`/`regencies.id`.
- Produces: `PostalCodeImportService::import(string $path): array` with keys `inserted`, `existing`, `rejected`, and `errors[]` containing `row`, `values`, `reason`; Artisan command `postal-codes:import {file}`.

- [ ] **Step 1: Write failing migration/model tests**

Assert the compound uniqueness contract, the `regency_id,postal_code` lookup index, relationships, five-digit storage, and deletion restrictions. Use database feature tests under the project's configured test database.

- [ ] **Step 2: Write failing importer tests**

Cover successful import, BOM-prefixed valid header, whitespace normalization, case-insensitive resolution, idempotent re-import, duplicate source rows, malformed header, invalid UTF-8, non-five-digit codes, unknown province/regency, mismatched province/regency, and row-numbered rejection reasons. Force a database exception and assert no rows persist.

- [ ] **Step 3: Run focused tests and confirm failure**

Run: `rtk php artisan test tests/Feature/PostalCodeImportTest.php tests/Feature/ImportPostalCodesCommandTest.php`

Expected: FAIL because schema/service/command are missing.

- [ ] **Step 4: Implement the additive schema and model relationships**

Create foreign keys using the actual unsigned type of the existing master IDs, restrict deletion, add the exact unique/index definitions, and add `PostalCode` fillable/relationship declarations.

- [ ] **Step 5: Implement transactional streaming CSV import**

Read with `SplFileObject`, strip UTF-8 BOM only from the first header, reject invalid encoding, normalize surrounding/repeated whitespace, cache case-folded province/regency resolution, and `firstOrCreate` on the compound key inside one transaction. Expected bad rows go into the result; unexpected failures abort the transaction and bubble as a safe import failure.

- [ ] **Step 6: Implement the CLI adapter**

`ImportPostalCodesCommand::handle(): int` validates the file, calls the service, prints summary/error rows without stack traces, and returns success for a completed import with row rejections but failure for invalid input or aborted transactions.

- [ ] **Step 7: Run tests and commit**

Run: `rtk php artisan test tests/Feature/PostalCodeImportTest.php tests/Feature/ImportPostalCodesCommandTest.php`

Expected: PASS.

Commit: `git commit -m "feat: add Indonesian postal code importer"`

### Task 4: Super-Admin Postal Import Page

**Files:**
- Create: `app/Http/Controllers/Admin/PostalCodeImportController.php`
- Create: `resources/views/admin/postal_codes/import.blade.php`
- Create: `resources/views/admin/postal_codes/errors_csv.blade.php`
- Modify: `routes/web.php`
- Modify: `app/Providers/EventServiceProvider.php`
- Test: `tests/Feature/AdminPostalCodeImportTest.php`

**Interfaces:**
- Consumes: `PostalCodeImportService::import(string $path): array` from Task 3.
- Produces: named routes `admin.postal-codes.import.form`, `.store`, and `.errors`; session-backed one-time error CSV token/data; a Super-Admin-only navigation item.

- [ ] **Step 1: Write failing authorization and workflow tests**

Assert guests redirect to login; `admin`, `super-user`, and regular users receive 403; `super-admin` can view/upload; non-CSV and files over 10 MB are rejected; a successful import shows counters; rejected rows expose a downloadable CSV with `row,province,regency,district,village,postal_code,reason` and no stack trace.

- [ ] **Step 2: Run the focused test and confirm failure**

Run: `rtk php artisan test tests/Feature/AdminPostalCodeImportTest.php`

Expected: FAIL because the routes/controller/views do not exist.

- [ ] **Step 3: Add server-enforced Super-Admin routes and controller**

Place all three routes inside `auth` + `role:super-admin`. Validate CSV MIME/extension and `max:10240` server-side, store it temporarily on the local private disk, call the shared service, and delete the temporary file in `finally`.

- [ ] **Step 4: Add the AdminLTE import form and error download**

Show required headers, file constraints, confirmation summary, rejected-row count, and actionable validation messages. Generate error CSV through a streamed response with attachment disposition and `nosniff`.

- [ ] **Step 5: Register a Super-Admin-only navigation entry**

Use the existing AdminLTE event/menu registration pattern in `EventServiceProvider`; apply an authorization check in addition to route middleware.

- [ ] **Step 6: Run tests and commit**

Run: `rtk php artisan test tests/Feature/AdminPostalCodeImportTest.php`

Expected: PASS.

Commit: `git commit -m "feat: add super admin postal import page"`

### Task 5: Hybrid Address Form, Postal Lookup, and Per-Address Persistence

**Files:**
- Create: `database/migrations/2026_10_16_000002_add_country_code_and_role_to_company_addresses.php`
- Modify: `app/Models/CompanyAddress.php`
- Modify: `app/Http/Controllers/PublicFormController.php`
- Modify: `routes/web.php`
- Modify: `resources/views/cs_vendor/form/address.blade.php`
- Modify: `resources/views/cs_vendor/partner_js.blade.php`
- Modify: `resources/views/public/form/js.blade.php`
- Modify: `resources/views/public/form/css.blade.php`
- Test: `tests/Feature/PublicAddressLookupTest.php`
- Test: `tests/Feature/PublicFormAddressSubmissionTest.php`
- Test: `tests/Unit/PublicAddressMarkupTest.php`

**Interfaces:**
- Consumes: `CountryCatalog`, `Provinces`, `Regencies`, and `PostalCode` from earlier tasks.
- Produces: public JSON endpoints `public.form.provinces`, `.regencies`, `.postal-codes`; aligned fields `address_role[]`, `country_code[]`, `country[]`, `province[]`, `city[]`, `zip_code[]`, `latitude[]`, `longitude[]`, `telephone[]`, `fax[]`.

- [ ] **Step 1: Write failing lookup tests**

Assert sorted province results, regencies scoped to their province, postal options scoped to their regency, empty-state responses, and rejection of forged/mismatched IDs. Responses expose only IDs and display labels needed by the form.

- [ ] **Step 2: Write failing address submission tests**

Cover legal-only with same-as-legal checked, a distinct operational address, multiple branches, Indonesian master relationships, a foreign address with text subdivisions/postal code, optional blank coordinates, independent per-row coordinates, duplicate/missing roles, mismatched array lengths, and forged Indonesian province/regency/postal combinations.

- [ ] **Step 3: Run focused tests and confirm failure**

Run: `rtk php artisan test tests/Feature/PublicAddressLookupTest.php tests/Feature/PublicFormAddressSubmissionTest.php tests/Unit/PublicAddressMarkupTest.php`

Expected: FAIL on missing endpoints/schema and current scalar coordinates.

- [ ] **Step 4: Add compatible address metadata**

Add nullable `country_code` and required-for-new-data `address_role` semantics without rewriting historical rows. Extend `CompanyAddress::$fillable`; retain `country`, `province`, `city`, and `zip_code` display values for compatibility.

- [ ] **Step 5: Add scoped lookup endpoints**

Implement controller methods with relationship-constrained queries and predictable `{data: [], message: ...}` states. Keep routes public because they contain master data only and apply Laravel's `throttle:60,1` middleware.

- [ ] **Step 6: Replace fixed addresses with role-based rows**

Render legal/tax first; enable “operational same as legal” by default; reveal operational when unchecked; add/remove explicit branch rows. Country is Select2. Indonesia swaps in province/regency/postal selects; foreign countries swap in length-limited text controls without changing submitted field names.

- [ ] **Step 7: Make map behavior optional and row-local**

Initialize maps lazily per visible address row; map click/search writes only that row's `latitude[]`, `longitude[]`, and address helper values. Display a local error on geocoder failure and leave manual fields usable/submittable.

- [ ] **Step 8: Validate aligned arrays and persist normalized rows**

Use the address array as the canonical count, reject missing parallel indexes and illegal roles, validate Indonesian relationships against DB, validate foreign text lengths, and persist every row's own coordinates. When same-as-legal is checked, persist only the legal row.

- [ ] **Step 9: Run tests and commit**

Run: `rtk php artisan test tests/Feature/PublicAddressLookupTest.php tests/Feature/PublicFormAddressSubmissionTest.php tests/Unit/PublicAddressMarkupTest.php`

Expected: PASS.

Commit: `git commit -m "feat: support hybrid international addresses"`

### Task 6: Signature-Validated Private Attachment Storage

**Files:**
- Create: `database/migrations/2026_10_16_000003_expand_company_attachment_metadata.php`
- Create: `app/Services/CompanyAttachmentService.php`
- Modify: `config/filesystems.php`
- Modify: `app/Models/CompanyAttachment.php`
- Modify: `app/Http/Controllers/PublicFormController.php`
- Modify: `resources/views/cs_vendor/form/file_upload.blade.php`
- Modify: `resources/views/public/form/js.blade.php`
- Test: `tests/Unit/CompanyAttachmentServiceTest.php`
- Test: `tests/Feature/PublicFormAttachmentTest.php`

**Interfaces:**
- Consumes: `UploadedFile[]` after Laravel count/file/size validation and a persisted `CompanyInformation` ID.
- Produces: `CompanyAttachmentService::validate(UploadedFile $file): array` with `mime`, `extension`, `size`, `width`, `height`, and `original_name`; `store(CompanyInformation $company, UploadedFile $file, array $metadata, int $sortOrder): CompanyAttachment`; `deleteStored(iterable $attachments): void`.

- [ ] **Step 1: Write failing signature and dimension tests**

Assert valid PDF/JPEG/PNG pass; spoofed extensions/client MIME, PDF without `%PDF-`, truncated/undecodable images, cross-format MIME mismatch, files over 5 MB, images over either 6000 dimension, and path-traversal/unusual original names fail with field-safe messages.

- [ ] **Step 2: Write failing submission/storage tests**

Assert 11 files fail, 10 valid files pass, UUID-based filenames use MIME-derived extensions, storage uses the private disk/company directory, full MIME fits the DB column, sanitized original names remain metadata only, and no invalid file reaches permanent storage. Simulate a database/approval failure after storage and assert all newly stored files are deleted.

- [ ] **Step 3: Run focused tests and confirm failure**

Run: `rtk php artisan test tests/Unit/CompanyAttachmentServiceTest.php tests/Feature/PublicFormAttachmentTest.php`

Expected: FAIL because the service/private pipeline is absent.

- [ ] **Step 4: Add private disk and attachment metadata migration**

Define a non-public `company_attachments` disk rooted below `storage/app/private`. Expand `filetype` to hold full MIME and add nullable metadata needed to distinguish private versus legacy-public paths without invalidating existing rows.

- [ ] **Step 5: Implement server-side inspection and storage**

Use `finfo`, binary prefix checks, and `getimagesize`/decode validation. Map accepted MIME to a fixed extension, generate `Str::uuid()`, sanitize the original basename for metadata, and store only after every file in the submission validates.

- [ ] **Step 6: Integrate rollback-safe submission handling**

Apply `array|max:10` plus per-file Laravel rules first. Track newly stored models/paths outside the DB transaction boundary and remove them on every exception path. Log an internal correlation context, but return a generic public error response.

- [ ] **Step 7: Align file-picker UX**

Set exact `accept` values, show 5 MB/10-file/6000×6000 guidance, reject obvious client-side count/size/type violations early, and keep backend messages authoritative.

- [ ] **Step 8: Run tests and commit**

Run: `rtk php artisan test tests/Unit/CompanyAttachmentServiceTest.php tests/Feature/PublicFormAttachmentTest.php`

Expected: PASS.

Commit: `git commit -m "feat: secure public form attachments"`

### Task 7: Authorized Attachment Delivery and Legacy Migration

**Files:**
- Create: `app/Http/Controllers/Admin/CompanyAttachmentController.php`
- Create: `app/Console/Commands/MigrateCompanyAttachmentsToPrivateCommand.php`
- Modify: `routes/web.php`
- Modify: `resources/views/admin/form_links/submission_detail.blade.php`
- Modify: `app/Models/CompanyAttachment.php`
- Test: `tests/Feature/CompanyAttachmentDeliveryTest.php`
- Test: `tests/Feature/MigrateCompanyAttachmentsToPrivateCommandTest.php`

**Interfaces:**
- Consumes: `CompanyAttachment` private/legacy storage metadata and existing access to the parent company/form-link submission.
- Produces: named routes `admin.company-attachments.show` and `.download`; command `company-attachments:migrate-private {--dry-run}`.

- [ ] **Step 1: Write failing delivery authorization/header tests**

Assert guests cannot access files, authenticated users without access receive 403, authorized admins receive correct bytes/MIME, every response has `X-Content-Type-Options: nosniff`, safe previews have `Content-Security-Policy: sandbox` and inline disposition, downloads use attachment disposition, missing storage returns 404, and client filenames cannot inject headers.

- [ ] **Step 2: Write failing legacy migration tests**

Cover dry-run, successful copy + byte-size/hash verification + DB update + source removal, already-migrated idempotence, missing source, target/write failure, and verification mismatch. Assert the public source is retained whenever verification or DB update fails.

- [ ] **Step 3: Run focused tests and confirm failure**

Run: `rtk php artisan test tests/Feature/CompanyAttachmentDeliveryTest.php tests/Feature/MigrateCompanyAttachmentsToPrivateCommandTest.php`

Expected: FAIL because routes/command do not exist.

- [ ] **Step 4: Implement authorized streaming**

Resolve attachments through their company, enforce the same submission visibility rules as `FormLinkController::submissionDetail`, stream from the correct private/legacy disk, and set safe response headers. Remove the public URL accessor as the default for migrated records.

- [ ] **Step 5: Replace direct public links in admin views**

Use authorized preview/download routes. Render images only for validated safe image MIME; represent PDF with a link/icon that opens the safe inline-preview route.

- [ ] **Step 6: Implement the verified migration command**

Process records in chunks. Copy first, verify target size/hash, update the row transactionally, then delete the old public file. Continue/report recoverable per-record failures without exposing secrets; never delete the source first.

- [ ] **Step 7: Run tests and commit**

Run: `rtk php artisan test tests/Feature/CompanyAttachmentDeliveryTest.php tests/Feature/MigrateCompanyAttachmentsToPrivateCommandTest.php`

Expected: PASS.

Commit: `git commit -m "feat: authorize attachment delivery and migration"`

### Task 8: End-to-End Error Handling, Rendered Form QA, and Regression Gate

**Files:**
- Modify: `app/Http/Controllers/PublicFormController.php`
- Modify: `resources/views/public/form_index.blade.php`
- Modify: `resources/views/public/form/js.blade.php`
- Modify: `resources/views/public/form/css.blade.php`
- Create: `tests/Feature/PublicFormEndToEndTest.php`
- Create: `tests/Unit/PublicFormResponsiveMarkupTest.php`

**Interfaces:**
- Consumes: All contracts/routes/services from Tasks 1–7.
- Produces: One coherent public submission response/error contract and the final release regression evidence.

- [ ] **Step 1: Write failing end-to-end regression tests**

Submit a complete Indonesian company and a complete foreign company, asserting normalized HTTPS URL, international phones, role-aligned addresses/coordinates, postal relationship, bank punctuation/digits, private attachments, submission count, and approval creation behavior. Force an unexpected exception and assert the response contains only the generic bilingual failure message—not the exception text, path, SQL, or trace.

- [ ] **Step 2: Write rendered-markup accessibility/responsiveness tests**

Assert unique IDs/labels for initial templates, loading/empty/failure state containers, Select2 hooks, same-as-legal semantics, branch controls, optional-map messaging, file constraints, and CSS hooks for phone/address rows at desktop/mobile breakpoints.

- [ ] **Step 3: Run focused tests and confirm failure**

Run: `rtk php artisan test tests/Feature/PublicFormEndToEndTest.php tests/Unit/PublicFormResponsiveMarkupTest.php`

Expected: FAIL until integration gaps and raw error responses are removed.

- [ ] **Step 4: Close integration and public-error gaps**

Make `PublicFormController::submit()` return field validation errors unchanged and a generic 500 JSON message for unexpected failures; retain detailed server logging without request file contents or sensitive values. Ensure loading controls re-enable after every success/failure path.

- [ ] **Step 5: Run all targeted public-form/import/attachment tests**

Run: `rtk php artisan test tests/Unit/PublicHttpsUrlTest.php tests/Unit/PublicFormTextValidationTest.php tests/Unit/CountryCatalogTest.php tests/Unit/InternationalPhoneInputTest.php tests/Unit/PublicFormPhoneValidationTest.php tests/Feature/PostalCodeImportTest.php tests/Feature/ImportPostalCodesCommandTest.php tests/Feature/AdminPostalCodeImportTest.php tests/Feature/PublicAddressLookupTest.php tests/Feature/PublicFormAddressSubmissionTest.php tests/Unit/PublicAddressMarkupTest.php tests/Unit/CompanyAttachmentServiceTest.php tests/Feature/PublicFormAttachmentTest.php tests/Feature/CompanyAttachmentDeliveryTest.php tests/Feature/MigrateCompanyAttachmentsToPrivateCommandTest.php tests/Feature/PublicFormEndToEndTest.php tests/Unit/PublicFormResponsiveMarkupTest.php`

Expected: PASS.

- [ ] **Step 6: Run project-wide verification**

Run: `rtk php artisan test`

Expected: PASS, or any pre-existing environment failure is recorded with its unchanged before/after evidence and all task-owned tests remain green.

- [ ] **Step 7: Perform browser QA at desktop and mobile widths**

Using the frontend testing workflow, verify keyboard-only country/phone selection, row add/remove, legal/operational toggle, Indonesian and foreign address switching, postal loading/errors, map failure fallback, 10-file boundary, exact field errors, successful SweetAlert countdown, and no console errors or horizontal overflow.

- [ ] **Step 8: Run final static checks and commit**

Run: `rtk git diff --check`

Expected: no whitespace errors.

Commit: `git commit -m "test: verify hardened public partner form"`


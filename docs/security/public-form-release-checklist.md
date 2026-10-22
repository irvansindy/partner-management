# Public Form Security Release Checklist

Use this checklist in staging before deploying the public partner form changes.

## Before deployment

- Back up the database and `storage/app/private/company_attachments`.
- Set a real `NOMINATIM_NOMINATIM_USER_AGENT` and contact email.
- Confirm the database supports row-level locks and run migrations, including the idempotency unique index.
- Run `php artisan company-attachments:migrate-private --dry-run`, review every missing/error row, then run the command without `--dry-run`.
- Verify migrated checksums before removing any independent backup of legacy files.

## Automated evidence

- `php artisan test --filter=PublicForm` covers replay/idempotency, final-slot quota locking, attachment signature and private delivery, PII-safe logging, rate limiting, XSS escaping, exact token queries, safe error responses, resource limits, and browser draft storage.
- `php artisan test tests/Feature/CompanyAttachmentDeliveryTest.php` verifies authorization and safe response headers.
- `php artisan test tests/Feature/MigrateCompanyAttachmentsToPrivateCommandTest.php` verifies dry-run and checksum-aware migration behavior.
- `php artisan route:list --path=f` must show the named view and submit throttles.
- `php artisan config:cache` and `php artisan route:cache` must complete successfully in the release environment.

## Staging checks

- Submit once, retry the identical request, and confirm one company, one submission count, one attachment set, and one approval workflow.
- Submit two different requests against a link with one slot remaining and confirm only one succeeds.
- Confirm POST without a valid CSRF token is rejected and inactive, expired, or exhausted links cannot create data.
- Upload valid PDF/JPEG/PNG files and reject spoofed, oversized, over-dimension, and eleventh files.
- Confirm attachments are inaccessible without authorization and return `nosniff` plus safe disposition headers.
- Confirm 422, 429, and 500 responses create no partial company data or orphaned files.
- Inspect application/security/request logs and confirm no CSRF token, NIK, account number, email, telephone, address, cookie, authorization header, SQL detail, or filesystem path is present.
- Confirm successful submission clears the session draft and the tab closes after the five-second notification.

## Monitoring and rollback

- Monitor 429 and 500 rates by route/request ID without collecting request bodies.
- Monitor private-storage growth and failed attachment cleanup.
- Roll back application code first if needed. Roll back the idempotency migration only after proving no duplicate keys and accepting loss of replay protection.
- Do not move private attachments back into the webroot. Restore database and private-storage backups together to keep metadata and files consistent.

# Public Partner Form Hardening Design

Date: 2026-10-16

## Objective

Harden the public partner registration form so its frontend and backend validation agree, international contact and address data can be captured without loading a global administrative dataset into the application, postal codes for Indonesian addresses come from controlled master data, and uploaded files are stored and served safely.

## Scope

This change covers:

- public form validation and field limits;
- HTTPS website normalization and validation;
- international phone selectors;
- hybrid Indonesian/international address entry;
- Indonesian postal-code master data and import workflows;
- map behavior and per-address coordinates;
- private attachment storage and authenticated delivery;
- automated tests for the new contracts.

Existing approval workflow and company submission semantics remain unchanged.

## Validation Contracts

Frontend validation provides immediate feedback. Backend validation is authoritative and repeats every security or integrity constraint.

### Master information

| Field | Contract |
|---|---|
| Established year | Optional integer from 1900 through the current year |
| Total employees | Optional integer from 1 through 1,000,000 |
| Company name | Required, Unicode letters and spaces, maximum 255 characters |
| Company group name | Optional, Unicode letters and spaces, maximum 255 characters |
| Liable-person name | Required, Unicode letters and spaces, maximum 255 characters |

### Contact information

Department, position, and contact name are required, accept Unicode letters and spaces only, and have a 255-character maximum. Email and phone rules remain separate.

### Bank information

Bank name is required and accepts Unicode letters, digits, spaces, periods, and hyphens, with a 255-character maximum. Account-holder name remains letters and spaces only with a 255-character maximum. Account-number validation remains numeric with its existing length bound.

### General text limits

Every single-line text field exposes a frontend `maxlength` matching its backend rule. Address lines accept up to 1,000 characters. Textareas keep field-specific limits and must gain a backend limit if they currently have none. Dynamic rows use exactly the same attributes as their initial row.

## Website Address

The UI accepts a hostname such as `example.com` and normalizes it to `https://example.com`. A full `https://` URL is also accepted. Validation rejects:

- the `http` scheme or any non-HTTPS scheme;
- localhost and hostnames without a public suffix;
- IPv4 and IPv6 literals;
- embedded username or password;
- whitespace or malformed hosts.

The normalized HTTPS URL is validated again on the server before persistence. This field is stored only and is not fetched by the server, so the rule prevents bad public-form data without introducing outbound requests.

## International Phone Input

One local country metadata source contains ISO alpha-2 code, display name, and calling code for all supported countries. Contact, address-phone, and fax controls all consume this source.

Select2 renders each option with:

- an emoji flag derived from the ISO code;
- ISO code;
- calling code;
- country name in the searchable dropdown.

The collapsed selection stays compact. The adjacent local-number input accepts digits only. Desktop and mobile layouts reserve a bounded width for the country selector while allowing the number input to shrink without clipping. The submitted value remains the calling code plus local number. Backend validation remains authoritative.

## Hybrid Address Model

### Address roles

The first address is the required legal/tax address. An “operational address is the same as the legal address” checkbox is enabled by default. When cleared, a second operational-address form appears. Additional branches are explicit optional rows.

Each submitted address keeps aligned array values for address role, country, administrative fields, postal code, latitude, longitude, telephone, and fax. Coordinates are per-address values rather than global scalar fields.

### Country behavior

Country is a searchable Select2 control backed by the same ISO country catalog.

For Indonesia (`ID`):

- province is loaded from the local `provinces` table;
- regency/city is loaded lazily for the selected province;
- postal code is selected from postal-code master data scoped to the selected regency;
- backend rules verify that the selected relationships are consistent.

For any other country:

- state/province, city, and postal code are text inputs;
- each field is length-limited and validated;
- no attempt is made to mirror every country's administrative hierarchy locally.

Persist both `country_code` and `country_name`. Existing `province`, `city`, and `zip_code` columns may continue storing their submitted display values for compatibility; a migration adds `country_code` and any missing per-address metadata without rewriting historical records.

### Map behavior

The map is an optional helper. Searching or clicking can populate the address and coordinates, but users can correct the address text and can submit if geocoding is unavailable. Map failures produce a local, actionable message and never block manual address entry. Each address row owns `latitude[]` and `longitude[]`.

## Indonesian Postal-Code Master

### Schema

Create a `postal_codes` table with:

- primary key;
- `province_id` foreign key;
- `regency_id` foreign key;
- `district`;
- `village`;
- five-character `postal_code`;
- timestamps.

Use a unique compound key across province, regency, district, village, and postal code. Add an index on `regency_id, postal_code` for form lookup. Foreign keys use the current province/regency IDs and restrict deletion so imported address masters cannot become orphaned silently.

### CSV importer

Accepted header:

```text
province,regency,district,village,postal_code
```

The importer:

1. validates the header and UTF-8 input;
2. validates non-empty administrative names and a five-digit postal code;
3. resolves province and regency case-insensitively and ensures the regency belongs to the province;
4. normalizes surrounding whitespace;
5. upserts on the compound unique key;
6. records the source row number and reason for every rejected row;
7. runs the database write in a transaction so an unexpected failure cannot leave a partial import.

The implementation exposes the same import service through an Artisan command and a Super-Admin-only admin page. The admin page accepts CSV only, shows a confirmation summary, and returns a downloadable error CSV when rows are rejected. Authorization is enforced server-side, not only by hiding navigation.

## Secure Attachment Pipeline

### Accepted files

- PDF, JPEG/JPG, and PNG only;
- maximum 5 MB per file;
- maximum 10 files per submission;
- raster images no larger than 6000 by 6000 pixels.

### Validation

Laravel file rules provide the first boundary. A dedicated attachment service then determines MIME from server-side file inspection and validates known signatures:

- PDF starts with a valid PDF signature and is detected as `application/pdf`;
- JPEG and PNG must decode as images and match their detected MIME;
- image dimensions are read server-side and checked against the limit.

Client filenames, extensions, and Content-Type headers never determine acceptance. Validation failures return per-file messages and do not expose internal paths.

### Storage

Store attachments on a private filesystem disk under a company-scoped directory. Generate storage names with UUIDs and an extension derived from the accepted server-side MIME. Preserve a sanitized original filename only as database metadata. Increase the database MIME column length if needed to hold complete MIME values.

If submission fails after files are stored, delete the newly stored files during rollback handling. Database and filesystem cleanup must be explicitly tested because filesystem writes are not covered by the database transaction.

### Delivery

Remove direct public `/storage` URLs for company attachments. An authenticated controller authorizes access to the company submission and streams the file with:

- the validated stored MIME;
- `X-Content-Type-Options: nosniff`;
- `Content-Security-Policy: sandbox` for inline previews where applicable;
- inline disposition only for explicitly safe PDF/JPEG/PNG previews;
- attachment disposition for every other case.

The existing admin submission view uses these authorized routes for previews and downloads.

## Error Handling and UX

- Validation errors appear beside the exact field and in the existing step summary.
- Loading, empty, and failure states are present for country, province, regency, and postal-code selectors.
- Dynamic rows preserve unique IDs and accessible labels.
- Phone, address, and file controls remain keyboard-operable.
- Import errors identify rows without exposing stack traces.
- Public submission responses never return raw exception messages.

## Migration and Compatibility

- Add new columns and tables without destructive alteration of existing company-address data.
- Historical attachments remain readable during a controlled migration to private storage; a migration command copies files, updates paths, verifies the target, and only then removes the old public file.
- Existing public attachment URLs must not be removed until stored files and application references have migrated successfully.
- Country and calling-code metadata is versioned in the repository and does not require runtime network access.

## Testing

Automated coverage includes:

- year, employee-count, character-set, length, bank-name, and website validation;
- phone formatting and country metadata rendering;
- Indonesian and international address branches;
- aligned coordinates for multiple addresses;
- postal-code lookup relationship checks;
- CSV import success, idempotent re-import, malformed rows, wrong province/regency relationship, and rollback;
- Super Admin authorization and denial for other roles;
- spoofed extensions/MIME, oversized files, excessive file count, excessive image dimensions, UUID storage names, and cleanup after failure;
- authenticated/unauthorized file delivery and required security headers;
- Blade compilation and rendered desktop/mobile form behavior.

## Acceptance Criteria

1. Frontend and backend enforce the same documented limits.
2. The QA examples fail or pass with accurate field-level messages.
3. All countries are selectable without loading global subdivision data.
4. Indonesian postal codes cannot be entered as arbitrary free text.
5. Map/geocoder failure does not prevent a valid manual address submission.
6. Postal-code imports are repeatable and restricted to Super Admin.
7. Invalid or spoofed files never reach permanent storage.
8. Stored attachments are not directly web-accessible and authorized responses include safe headers.
9. Existing company/address/attachment data remains accessible through the migration path.

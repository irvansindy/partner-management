# Employee Range and Website Validation Design

## Intent

Replace free-text employee counts with six controlled ranges and reject submitted website URLs that do not resolve to public infrastructure or cannot be reached over HTTPS.

## Decisions

- Store `total_employee` as one of: `1-100`, `101-500`, `501-1000`, `1001-5000`, `5001-10000`, `10000+`.
- Change both company and form-submission columns from integer to string without rewriting existing values.
- Render the employee control as Select2 and keep draft restoration compatible with the selected value.
- Normalize website input to HTTPS, resolve every host address, reject non-public addresses, then make a short HTTPS request.
- Do not follow redirects automatically. A redirect is accepted only after its destination is validated and checked as a separate public HTTPS URL.
- Return a bilingual validation message so the existing locale-aware public-form error renderer selects the active language.

## Constraints

- No browser-side reachability request because CORS would make valid sites appear unavailable.
- Reject localhost, IP literals, credentials, private/reserved IPs, and redirects to those targets.
- Use short connection and request timeouts so an external site cannot block submission for long.
- Preserve all existing website syntax validation.

## Approaches Considered

- DNS-only validation: stable but does not prove the website responds.
- DNS plus HTTPS request: selected because it matches the requested existence check while remaining safe with SSRF controls.
- Store a numeric representative for each employee range: rejected because it loses the user's actual selection.

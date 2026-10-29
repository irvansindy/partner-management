# Public Address UX Design

## Intent

Make international address entry understandable and reliable: country choices should be recognizable, telephone and fax fields must remain readable, invalid websites should be stopped before submission, and map selection should support both search and precise manual correction.

## Chosen approach

Reverse-geocoding is proxied through Laravel using the existing Nominatim client. The browser sends validated coordinates only after a map click or marker drag ends. This keeps external integration, logging, and failure handling on the server.

Direct browser calls were rejected because they expose the integration to CSP, privacy, and rate-limit problems. Keeping coordinates without refreshing the address was rejected because it does not meet the requirement that the discovered address follow the chosen pin.

## Behavior

- Country Select2 options and selections render the existing `intl-tel-input` flag sprite with country name and ISO code.
- Telephone and fax reserve enough horizontal space for flag and dial code; their placeholder follows the selected country's national example and typed input remains digits only.
- Website host input filters protocols, whitespace, credentials, and unsupported characters while typing, then performs public HTTPS domain validation on blur and before leaving the step.
- The first entry to Address shows one SweetAlert per page lifecycle explaining that common landmarks or nearby addresses work best for map search.
- Search creates a draggable marker. Clicking the map moves it. Drag end and map click update latitude and longitude, call reverse-geocoding, and replace the read-only discovered-address value.
- Reverse-geocoding failure preserves the chosen coordinates and reports that only the address label could not be refreshed.

## Constraints

- Preserve server-side validation as the authority.
- Do not add a frontend or backend dependency.
- Do not reverse-geocode continuously during dragging.
- Keep all dynamic address rows and draft restoration working.
- Maintain keyboard focus, mobile layout, and existing submission field names.


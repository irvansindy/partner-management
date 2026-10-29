# Address Map Fullscreen Design

## Intent

Allow users to choose a map location without first performing a text search, and provide a true fullscreen map for precise positioning.

## Chosen approach

Each address map starts with a temporary draggable marker at its initial center. The marker does not populate coordinates until the user drags it or clicks the map. Text search remains available and moves the same marker. A dedicated button uses the browser Fullscreen API with a CSS fallback; `Esc` exits either mode and Leaflet is resized after every transition.

The alternative center-crosshair workflow was rejected because it would replace the existing marker interaction and require an additional confirmation action.

## Constraints

- Do not submit the initial temporary marker coordinates.
- Preserve click-to-place, drag-end reverse-geocoding, and text search.
- Fullscreen controls must be keyboard accessible and available on every dynamic address row.
- Do not add a Leaflet plugin or other dependency.


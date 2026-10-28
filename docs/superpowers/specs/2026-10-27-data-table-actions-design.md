# DataTable Action Buttons Design

## Objective

Replace the cramped segmented action controls in administrative data tables with a consistent, reusable action pattern while preserving every existing route, permission, form method, and JavaScript handler.

## Visual direction

- Actions are separate controls, not a segmented button group.
- Every control has a minimum 44 by 44 pixel target.
- Controls use an 8 pixel corner radius and a 6 pixel gap.
- Semantic treatments distinguish view, edit, enable or disable, delete, and copy actions.
- Colors remain restrained and fit the existing Partner Management palette.
- Existing text actions remain readable; icon-only actions expose a tooltip and accessible name.

## Responsive and accessibility behavior

- Action groups wrap when a table column becomes narrow.
- Keyboard focus is clearly visible.
- Disabled controls remain recognizable without appearing interactive.
- Icon-only controls use `aria-label`, `title`, and decorative icons hidden from assistive technology.

## Scope

The shared layout supplies the DataTable action styles globally. The Form Links listing adopts the explicit semantic action classes as the reference implementation. Existing DataTable action renderers inherit the global treatment without changing their behavior.


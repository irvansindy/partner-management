# Admin Language Switcher Design

## Intent

Make language switching easy to find on every authenticated AdminLTE page and available to every role, while keeping the user on the current admin URL.

## Chosen approach

Add one reusable two-state language switch to the right side of both AdminLTE navbar variants, immediately before the user menu. The switch shows `ID` when unchecked and `EN` when checked. Changing it opens the existing public `locale.switch` route with a validated `return_to` path. Remove the old fixed bottom-right language button and its CSS.

Alternatives considered:

- Use a dropdown: rejected because switching between exactly two languages does not need a menu.
- Keep the floating control: rejected because it obscures page content and is detached from other account controls.
- Configure the dropdown through `config/adminlte.php`: rejected because the return URL is request-specific and the current project already overrides the navbar Blade partials.

## Interaction and accessibility

- Available to all authenticated roles; no role or permission condition.
- Native checkbox semantics with `role="switch"`, a programmatic label, and a visible focus state.
- The checked state always reflects the active locale: unchecked for `id`, checked for `en`.
- Compact on narrow screens: the two locale abbreviations and switch remain visible without covering content.

## Scope

- Add a reusable AdminLTE navbar locale switch partial with a small local script that navigates on change.
- Include it in standard and top-navigation navbar variants before the user menu.
- Remove the floating language control from `resources/views/vendor/adminlte/page.blade.php`.
- Add source/render regression tests and compile Blade views.

No admin page content translation is added in this change; this only exposes the already implemented locale runtime consistently in the admin shell.

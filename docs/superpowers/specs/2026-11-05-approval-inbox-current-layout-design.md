# Approval Inbox Current Layout Design

## Intent

Replace the legacy AdminLTE presentation of My Pending Approvals with the current Form Submissions visual system. Assigned approvers must see a familiar table-based page without receiving access to every submission in another department's Form Link.

## Chosen approach

Keep the existing `myApprovals()` query and route as the authorization boundary, then rebuild its view with `layouts.main`, `_alpha_styles`, the Form Submissions page hierarchy, and equivalent responsive table/actions. Directly reusing the owner-only Form Submissions route was rejected because that route exposes all submissions belonging to a Form Link.

## Behavior

- Display only waiting approval steps assigned to the authenticated user.
- Show a pending count, searchable table, submission progress, View Details, and Take Action.
- Use the current sidebar/header/layout and remove AdminLTE page dependencies.
- Preserve empty, desktop, mobile overflow, keyboard, and accessible-label states.


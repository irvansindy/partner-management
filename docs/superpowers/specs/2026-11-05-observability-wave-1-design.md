# Observability Wave 1 Design

## Intent

Build a backward-compatible observability foundation for partner-management. Every HTTP request receives a correlation ID, audit records are structured and redacted, request telemetry identifies its actor and duration, and security logging cannot turn a rejected request into a server error.

## Goals

- Add append-only audit, audit-change, and operation-run storage without removing legacy activity tables.
- Provide native services for audit context, value redaction, and before/after diffs.
- Propagate one request ID through request attributes, response headers, logs, request telemetry, and audit events.
- Repair security event persistence and add a rotating security log channel.
- Keep normal application behavior available when observability persistence fails.

## Non-goals

- No module-specific CRUD instrumentation beyond the existing observer in Wave 1.
- No audit administration UI, pruning command, external provider, or legacy-table contraction.
- No raw request/response bodies, passwords, tokens, cookies, authorization headers, or binary files in observability records.

## Constraints

- Laravel 8 and the current dependency set remain unchanged.
- Schema changes use expand-only migrations and support rollback.
- Existing `activity_logs`, `master_activity_logs`, and `request_logs` readers continue working.
- Existing unrelated working-tree changes remain untouched.

## Approaches

1. Install a third-party audit package. Rejected because it introduces migration and behavioral risk before the current native logging is stabilized.
2. Replace legacy activity tables immediately. Rejected because it is destructive and prevents a mixed-version rollout.
3. Extend the native implementation with new append-only tables and compatibility columns. Selected because it is reversible, testable, and preserves current readers.

## Design

`RequestIdMiddleware` is the first application middleware after trusted proxies. It accepts a valid correlation identifier or creates a UUID, stores it on the request, shares it with the logging context, and adds it to the response.

`AuditContext`, `AuditRedactor`, `AuditDiff`, and `AuditLogger` are focused native services. The logger writes an `audit_events` record plus optional `audit_event_changes`, catches persistence failures, and emits a sanitized warning. The existing `UserActivityLogger` remains operational during the compatibility window.

`SanitizeInputMiddleware` continues recording request telemetry after the response but adds actor, correlation, status, duration, and response size fields. Security-event persistence becomes fail-open at each sink and uses the configured rotating security channel.

## Rollout and rollback

Forward rollout: deploy code and expand-only migration, run migrations, clear caches, then verify request headers and new table writes. Old readers continue using legacy tables.

Rollback: deploy the previous code first, then optionally roll back only the Wave 1 migration. No legacy table or column is removed.

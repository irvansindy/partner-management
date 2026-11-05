<?php

namespace App\Audit;

class AuditDiff
{
    public function __construct(private AuditRedactor $redactor)
    {
    }

    public function between(array $oldValues, array $newValues): array
    {
        $changes = [];
        $ignored = config('audit.ignored_fields', []);

        foreach (array_unique(array_merge(array_keys($oldValues), array_keys($newValues))) as $field) {
            if (in_array($field, $ignored, true)) {
                continue;
            }

            $old = $oldValues[$field] ?? null;
            $new = $newValues[$field] ?? null;
            if ($old === $new) {
                continue;
            }

            $sensitive = $this->redactor->isSensitive((string) $field);
            $changes[] = [
                'field' => (string) $field,
                'old_value' => $sensitive ? '[REDACTED]' : $old,
                'new_value' => $sensitive ? '[REDACTED]' : $new,
                'sensitive' => $sensitive,
            ];
        }

        return $changes;
    }
}

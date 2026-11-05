<?php

namespace App\Audit;

class AuditRedactor
{
    private array $sensitiveFields;

    public function __construct(?array $sensitiveFields = null)
    {
        $this->sensitiveFields = array_map(
            'strtolower',
            $sensitiveFields ?? config('audit.sensitive_fields', [])
        );
    }

    public function redact(array $data): array
    {
        $redacted = [];

        foreach ($data as $key => $value) {
            if ($this->isSensitive((string) $key)) {
                $redacted[$key] = '[REDACTED]';
                continue;
            }

            $redacted[$key] = is_array($value) ? $this->redact($value) : $value;
        }

        return $redacted;
    }

    public function isSensitive(string $field): bool
    {
        return in_array(strtolower($field), $this->sensitiveFields, true);
    }
}

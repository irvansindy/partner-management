<?php

namespace App\Audit;

use App\Models\AuditEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuditLogger
{
    public function __construct(
        private AuditContext $context,
        private AuditDiff $diff,
        private AuditRedactor $redactor
    ) {
    }

    public function log(
        string $event,
        string $action,
        ?Model $model = null,
        array $oldValues = [],
        array $newValues = [],
        ?string $description = null,
        array $metadata = []
    ): ?AuditEvent {
        $context = $this->context->values();

        try {
            return DB::transaction(function () use (
                $event,
                $action,
                $model,
                $oldValues,
                $newValues,
                $description,
                $metadata,
                $context
            ) {
                $auditEvent = AuditEvent::create(array_merge($context, [
                    'event' => $event,
                    'action' => $action,
                    'model_type' => $model ? get_class($model) : null,
                    'model_id' => $model?->getKey(),
                    'description' => $description,
                    'metadata' => $this->redactor->redact($metadata),
                ]));

                foreach ($this->diff->between($oldValues, $newValues) as $change) {
                    $auditEvent->changes()->create($change);
                }

                return $auditEvent->load('changes');
            });
        } catch (Throwable $error) {
            Log::warning('Audit persistence failed', [
                'request_id' => $context['request_id'],
                'event' => $event,
                'exception' => get_class($error),
            ]);

            return null;
        }
    }

    public function afterCommit(
        string $event,
        string $action,
        ?Model $model = null,
        array $oldValues = [],
        array $newValues = [],
        ?string $description = null,
        array $metadata = []
    ): void {
        DB::afterCommit(fn () => $this->log(
            $event,
            $action,
            $model,
            $oldValues,
            $newValues,
            $description,
            $metadata
        ));
    }
}

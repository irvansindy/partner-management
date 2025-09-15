<?php

namespace App\Traits;

use App\Helpers\UserActivityLogger;
use Illuminate\Database\Eloquent\Model;

trait HasInsertWithLog
{
    /**
     * Insert massal + logging manual.
     *
     * @param array $records
     * @param bool $logMultiple Jika true → log semua record. Jika false → log sekali saja.
     * @return bool
     */
    public static function insertWithLog(array $records, bool $logMultiple = false): bool
    {
        if (empty($records)) {
            return false;
        }

        $inserted = static::insert($records);

        if ($inserted) {
            if ($logMultiple) {
                foreach ($records as $record) {
                    /** @var Model $model */
                    $model = new static($record);

                    UserActivityLogger::log(
                        'create',
                        'Membuat data ' . class_basename($model),
                        $model,
                        ['new' => $model->toArray()]
                    );
                }
            } else {
                $first = $records[0];
                $model = new static($first);

                UserActivityLogger::log(
                    'create',
                    'Membuat data ' . class_basename($model),
                    $model,
                    ['new' => $model->toArray()]
                );
            }
        }

        return $inserted;
    }
}

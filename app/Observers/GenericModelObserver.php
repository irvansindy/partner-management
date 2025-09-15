<?php

namespace App\Observers;

use App\Helpers\UserActivityLogger;
class GenericModelObserver
{
    public function created($model)
    {
        UserActivityLogger::log(
            'create',
            'Membuat data ' . class_basename($model),
            $model,
            ['new' => $model->toArray()]
        );
    }
    public function updated($model)
    {
        UserActivityLogger::log(
            'update',
            'Mengupdate data ' . class_basename($model),
            $model,
            [
                'old' => $model->getOriginal(),
                'new' => $model->getChanges(),
            ]
        );
    }
    public function deleted($model)
    {
        UserActivityLogger::log(
            'delete',
            'Menghapus data ' . class_basename($model),
            $model,
            ['old' => $model->getOriginal()]
        );
    }
}

<?php
namespace App\Helpers;

use App\Models\MasterActivityLog;
use App\Models\ActivityLogs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
class UserActivityLogger {
    protected static ?MasterActivityLog $currentMaster = null;

    /**
     * Menyimpan semua aktivitas untuk master yang sama
     */
    protected static array $actions = [];
    protected static function master(): MasterActivityLog
    {
        if (!static::$currentMaster) {
            static::$currentMaster = MasterActivityLog::create([
                'user_id' => Auth::id(),
                'action'  => [], // awalnya kosong
            ]);
        }

        return static::$currentMaster;
    }
    /**
     * Reset manual jika diperlukan (misal di akhir request)
     */
    public static function reset(): void
    {
        static::$currentMaster = null;
        static::$actions = [];
    }
    /**
     * Catat aktivitas user
     */
    public static function log(
        string $action,
        string $description = null,
        $model = null,
        array $data = null
    ): void {
        $master = static::master();
        // 1. Tambahkan ringkasan aktivitas ke array actions
        $entry = [
            'type'        => $action,                   // create / update / delete
            'model'       => $model ? class_basename($model) : null,
            'description' => $description,
            'timestamp'   => now()->toDateTimeString(),
        ];
        static::$actions[] = $entry;
        // 2. Simpan detail (child record)
        ActivityLogs::create([
            'master_activity_log_id' => $master->id,
            'user_id'    => Auth::id(),
            'action'     => $action,
            'description'=> $description,
            'model_type' => $model ? get_class($model) : null,
            'model_id'   => $model?->id,
            'data'       => $data,
            'ip_address' => Request::ip(),
        ]);
        // 3. Update kolom action di master menjadi JSON array seluruh aktivitas
        $master->update([
            'action' => static::$actions,
        ]);
    }
}
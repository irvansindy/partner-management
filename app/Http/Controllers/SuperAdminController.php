<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Helpers\FormatResponseJson;
class SuperAdminController extends Controller
{
    public function clearOptimize()
    {
        try {
            Artisan::call('optimize:clear');
            return FormatResponseJson::success(true, 'Optimize clear command executed successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(false, 'Failed to execute optimize clear command: ' . $e->getMessage(), 500);
        }
    }
}

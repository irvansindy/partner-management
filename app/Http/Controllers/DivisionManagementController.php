<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class DivisionManagementController extends Controller
{
    public function index()
    {
        return view('admin.division.index');
    }
    public function fetchDivision()
    {
        try {
            $divisions = DB::table('master_divisions')->get();
            return FormatResponseJson::success($divisions);
        } catch (\Exception $e) {
            return FormatResponseJson::error($e->getMessage());
        }
    }
}

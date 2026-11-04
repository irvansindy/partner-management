<?php

namespace App\Http\Controllers;

use App\Helpers\FormatResponseJson;
use App\Http\Requests\StoreDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use App\Models\MasterDivision;
use App\Services\DivisionService;

class DivisionManagementController extends Controller
{
    private $divisionService;

    public function __construct(DivisionService $divisionService)
    {
        $this->divisionService = $divisionService;
    }

    public function index()
    {
        return view('admin.division.index', $this->divisionService->getFormOptions());
    }

    public function fetchDivision()
    {
        try {
            return FormatResponseJson::success($this->divisionService->getDivisions());
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }

    public function store(StoreDivisionRequest $request)
    {
        try {
            $division = $this->divisionService->create($request->validated());

            return FormatResponseJson::success($division, 'Division created successfully.');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }

    public function update(UpdateDivisionRequest $request, MasterDivision $division)
    {
        try {
            $division = $this->divisionService->update($division, $request->validated());

            return FormatResponseJson::success($division, 'Division updated successfully.');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }

    public function destroy(MasterDivision $division)
    {
        try {
            if (!$this->divisionService->delete($division)) {
                return FormatResponseJson::error(
                    null,
                    'This division cannot be deleted while departments are assigned to it.',
                    409
                );
            }

            return FormatResponseJson::success(null, 'Division deleted successfully.');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
}

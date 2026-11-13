<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SystemMaintenanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SystemMaintenanceController extends Controller
{
    private SystemMaintenanceService $maintenance;

    public function __construct(SystemMaintenanceService $maintenance)
    {
        $this->maintenance = $maintenance;
    }

    public function index(Request $request): View
    {
        $result = $request->session()->get('maintenance_result');
        if ($result) {
            $commands = $this->maintenance->commands();
            $result['label'] = $commands[$result['key'] ?? '']['label'] ?? $result['label'];
            if (in_array($result['output_key'] ?? null, ['failed', 'busy', 'no_output'], true)) {
                $result['output'] = __('maintenance.' . $result['output_key']);
            }
        }

        return view('admin.system_maintenance.index', [
            'commandGroups' => $this->maintenance->groupedCommands(),
            'result' => $result,
        ]);
    }

    public function execute(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'command' => [
                'required',
                'string',
                Rule::in(array_keys($this->maintenance->commands())),
            ],
        ], __('maintenance.validation'), __('maintenance.attributes'));

        $result = $this->maintenance->run($validated['command'], (int) $request->user()->id);

        return redirect()
            ->route('admin.system-maintenance.index')
            ->with('maintenance_result', $result);
    }
}

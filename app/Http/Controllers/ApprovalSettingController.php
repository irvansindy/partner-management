<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\ApprovalTemplate;
use App\Models\ApprovalTemplateDetail;
use App\Models\User;
use App\Models\MasterOffice;
use App\Models\MasterDepartment;
use App\Models\ApprovalProcess;

class ApprovalSettingController extends Controller
{
    public function index()
    {
        return view('admin.approval_setting.index');
    }

    public function fetchApproval()
    {
        try {
            $templates = ApprovalTemplate::with(['user', 'department', 'office', 'details.user'])
                ->latest()
                ->get()
                ->map(function ($template) {
                    return [
                        'id' => $template->id,
                        'name' => $template->name,
                        'office_id' => $template->location_id,
                        'department_id' => $template->department_id,
                        'office' => $template->office->name ?? '-',
                        'department' => $template->department->name ?? '-',
                        'total_steps' => $template->details->pluck('step_ordering')->unique()->count(),
                        'is_active' => $template->isActive(),
                        'status' => $template->isActive() ? __('approval.Active') : __('approval.Inactive'),
                        'created_by' => $template->user->name ?? '-',
                        'created_at' => $template->created_at->locale(app()->getLocale())->translatedFormat('d M Y'),
                    ];
                });

            return FormatResponseJson::success($templates, __('approval.Approval templates fetched successfully'));
        } catch (\Exception $e) {
            \Log::error('Fetch Approval Template Error', ['exception' => $e]);
            return FormatResponseJson::error(null, __('approval.Data template approval tidak dapat dimuat.'), 500);
        }
    }

    public function fetchUserApproval(Request $request)
    {
        $request->validate([
            'master_approval_id' => 'required|exists:approval_templates,id'
        ], __('approval_validation.messages'), __('approval_validation.attributes'));

        try {
            $template = ApprovalTemplate::with(['office:id,name', 'department:id,name'])
                ->findOrFail($request->master_approval_id);

            $users = User::select(['id', 'name', 'email'])
                ->with('roles:id,name')
                ->whereHas('roles', function($q) {
                    $q->whereIn('name', ['admin', 'super-user']);
                })
                // ->where('status', 1) // Hanya user aktif
                ->get();
                // dd($users);
            // Ambil existing approvers
            $existingStages = ApprovalTemplateDetail::forTemplate($request->master_approval_id)
                ->with('user:id,name,email')
                ->get()
                ->groupBy('step_ordering')
                ->map(function ($details, $stepOrdering) {
                    return [
                        'step_ordering' => (int) $stepOrdering,
                        'user_ids' => $details->pluck('user_id')->values()->all(),
                        'approvers' => $details->map(function ($detail) {
                            return [
                                'id' => $detail->id,
                                'user_id' => $detail->user_id,
                                'user_name' => optional($detail->user)->name ?? __('approval.User tidak tersedia'),
                                'user_email' => optional($detail->user)->email ?? '-',
                                'status' => $detail->status,
                            ];
                        })->values()->all(),
                    ];
                })
                ->values();

            return FormatResponseJson::success([
                'users' => $users,
                'existing_stages' => $existingStages,
                'template' => [
                    'id' => $template->id,
                    'name' => $template->name,
                    'office' => optional($template->office)->name ?? '-',
                    'department' => optional($template->department)->name ?? '-',
                ],
            ], __('approval.Users fetched successfully'));

        } catch (\Exception $e) {
            \Log::error('Fetch Approval User Error', ['exception' => $e]);
            return FormatResponseJson::error(null, __('approval.Data approver tidak dapat dimuat.'), 500);
        }
    }

    public function fetchOfficeAndDepartment()
    {
        try {
            $offices = MasterOffice::get(['id', 'name'])
                ->map(fn($office) => [
                    'id' => $office->id,
                    'name' => $office->name
                ]);

            $departments = MasterDepartment::get(['id', 'name'])
                ->map(fn($dept) => [
                    'id' => $dept->id,
                    'name' => $dept->name
                ]);

            return FormatResponseJson::success([
                'offices' => $offices,
                'departments' => $departments
            ], __('approval.Office and department fetched successfully'));

        } catch (\Exception $e) {
            \Log::error('Fetch Approval Master Data Error', ['exception' => $e]);
            return FormatResponseJson::error(null, __('approval.Data office dan department tidak dapat dimuat.'), 500);
        }
    }

    /**
     * ========== VALIDATION TERBAIK ==========
     */
    public function storeApprovalTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'approval_master_name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[\pL\pN\s_-]+$/u'],
            'stagging_approval_office' => ['required', 'integer', 'exists:master_offices,id'],
            'stagging_approval_department' => ['required', 'integer', 'exists:master_departments,id'],
            'status' => 'nullable|integer|in:0,1',
        ], array_merge(__('approval_validation.messages'), [
            'approval_master_name.required' => __('approval.Nama template approval wajib diisi'),
            'approval_master_name.min' => __('approval.Nama template minimal 3 karakter'),
            'approval_master_name.max' => __('approval.Nama template maksimal 255 karakter'),
            'approval_master_name.regex' => __('approval.Nama template hanya boleh berisi huruf, angka, spasi, dan tanda hubung'),
            'stagging_approval_office.required' => __('approval.Office wajib dipilih'),
            'stagging_approval_office.exists' => __('approval.Office yang dipilih tidak valid'),
            'stagging_approval_department.required' => __('approval.Department wajib dipilih'),
            'stagging_approval_department.exists' => __('approval.Department yang dipilih tidak valid'),
        ]), __('approval_validation.attributes'));
        if ($validator->fails()) {
            return FormatResponseJson::error(null, ['errors' => $validator->errors()], 422);
        }

        try {
            $template = DB::transaction(function () use ($request) {
                $duplicate = ApprovalTemplate::where('location_id', $request->stagging_approval_office)
                    ->where('department_id', $request->stagging_approval_department)
                    ->lockForUpdate()
                    ->exists();
                if ($duplicate) {
                    throw new \DomainException(__('approval.Template approval untuk kombinasi Office dan Department ini sudah ada.'));
                }
                return ApprovalTemplate::create([
                    'name' => trim($request->approval_master_name),
                    'user_id' => auth()->id(),
                    'location_id' => $request->stagging_approval_office,
                    'department_id' => $request->stagging_approval_department,
                    'step_ordering' => 1,
                    'status' => $request->input('status', 1),
                ]);
            }, 3);

            return FormatResponseJson::success([
                'template_id' => $template->id,
                'name' => $template->name,
            ], __('approval.Template approval berhasil dibuat'));

        } catch (\DomainException $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 409);
        } catch (\Exception $e) {
            \Log::error('Store Approval Template Error', ['exception' => $e]);
            return FormatResponseJson::error(null, __('approval.Terjadi kesalahan saat membuat template approval.'), 500);
        }
    }

    /**
     * ========== VALIDASI SUBMIT DETAIL ==========
     */
    public function submitApprovalDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'master_approval_id' => [
                    'required',
                    'integer',
                    'exists:approval_templates,id'
                ],
                'approval_stages' => [
                    'required',
                    'array',
                    'min:1',
                    'max:10',
                ],
                'approval_stages.*.user_ids' => [
                    'required',
                    'array',
                    'min:1',
                    'max:10',
                ],
                'approval_stages.*.user_ids.*' => [
                    'required',
                    'integer',
                    'distinct',
                    'exists:users,id',
                ],
            ], array_merge(__('approval_validation.messages'), [
                'master_approval_id.required' => __('approval.Master Approval ID wajib diisi'),
                'master_approval_id.exists' => __('approval.Master Approval tidak ditemukan'),

                'approval_stages.required' => __('approval.Minimal satu tahap approval harus dibuat'),
                'approval_stages.min' => __('approval.Minimal satu tahap approval harus dibuat'),
                'approval_stages.max' => __('approval.Maksimal 10 tahap approval'),
                'approval_stages.*.user_ids.required' => __('approval.Setiap tahap harus memiliki minimal satu PIC'),
                'approval_stages.*.user_ids.min' => __('approval.Setiap tahap harus memiliki minimal satu PIC'),
                'approval_stages.*.user_ids.max' => __('approval.Maksimal 10 PIC dalam satu tahap'),
                'approval_stages.*.user_ids.*.distinct' => __('approval.PIC yang sama tidak boleh dipilih lebih dari sekali'),
                'approval_stages.*.user_ids.*.exists' => __('approval.Salah satu PIC yang dipilih tidak valid'),
            ]), __('approval_validation.attributes'));

        if ($validator->fails()) {
            return FormatResponseJson::error(null, ['errors' => $validator->errors()], 422);
        }

        $allUserIds = collect($request->approval_stages)
            ->flatMap(fn ($stage) => $stage['user_ids'] ?? [])
            ->map(fn ($userId) => (int) $userId)
            ->all();

        if (count($allUserIds) !== count(array_unique($allUserIds))) {
            return FormatResponseJson::error(null, [
                'errors' => ['approval_stages' => [__('approval.PIC yang sama tidak boleh digunakan pada tahap berbeda.')]],
            ], 422);
        }

        $invalidUsers = User::whereIn('id', $allUserIds)
            ->whereDoesntHave('roles', function($q) {
                $q->whereIn('name', ['admin', 'super-user']);
            })
            ->pluck('name');

        if ($invalidUsers->isNotEmpty()) {
            return FormatResponseJson::error(null,
                __('approval.invalid_roles', ['names' => $invalidUsers->implode(', ')]),
                400
            );
        }

        try {
            $details = DB::transaction(function () use ($request) {
                ApprovalTemplate::whereKey($request->master_approval_id)->lockForUpdate()->firstOrFail();
                ApprovalTemplateDetail::where('approval_id', $request->master_approval_id)->delete();
                $details = [];
                foreach ($request->approval_stages as $stageIndex => $stage) {
                    foreach ($stage['user_ids'] as $userId) {
                        $details[] = [
                            'approval_id' => $request->master_approval_id,
                            'user_id' => $userId,
                            'step_ordering' => $stageIndex + 1,
                            'status' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                ApprovalTemplateDetail::insert($details);
                return $details;
            }, 3);
            return FormatResponseJson::success([
                'total_approvers' => count($details),
                'total_stages' => count($request->approval_stages),
            ], __('approval.Detail approver berhasil disimpan'));
        } catch (\Exception $e) {
            \Log::error('Submit Approval Detail Error', ['exception' => $e]);
            return FormatResponseJson::error(null, __('approval.Terjadi kesalahan saat menyimpan detail approver.'), 500);
        }
    }

    /**
     * ========== DELETE TEMPLATE ==========
     */
    public function deleteApprovalTemplate(Request $request)
    {
        $validated = $request->validate([
            'template_id' => 'required|integer|exists:approval_templates,id',
        ], __('approval_validation.messages'), __('approval_validation.attributes'));

        try {
            DB::transaction(function () use ($validated) {
                $template = ApprovalTemplate::whereKey($validated['template_id'])->lockForUpdate()->firstOrFail();
                $isUsed = ApprovalProcess::where('location_id', $template->location_id)
                    ->where('department_id', $template->department_id)
                    ->whereIn('status', [ApprovalProcess::STATUS_PENDING, ApprovalProcess::STATUS_IN_PROGRESS])
                    ->exists();
                if ($isUsed) {
                    throw new \DomainException(__('approval.Template tidak dapat dihapus karena sedang digunakan dalam proses approval.'));
                }
                $template->details()->delete();
                $template->delete();
            }, 3);
            return FormatResponseJson::success(null, __('approval.Template berhasil dihapus'));
        } catch (\DomainException $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 409);
        } catch (\Exception $e) {
            \Log::error('Delete Approval Template Error', ['exception' => $e]);
            return FormatResponseJson::error(null, __('approval.Terjadi kesalahan saat menghapus template approval.'), 500);
        }
    }
}

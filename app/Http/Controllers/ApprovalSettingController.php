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
                        'total_steps' => $template->details->count(),
                        'status' => $template->isActive() ? 'Active' : 'Inactive',
                        'created_by' => $template->user->name ?? '-',
                        'created_at' => $template->created_at->format('d M Y'),
                    ];
                });

            return FormatResponseJson::success($templates, 'Approval templates fetched successfully');
        } catch (\Exception $e) {
            \Log::error('Fetch Approval Template Error', ['exception' => $e]);
            return FormatResponseJson::error(null, 'Data template approval tidak dapat dimuat.', 500);
        }
    }

    public function fetchUserApproval(Request $request)
    {
        try {
            $request->validate([
                'master_approval_id' => 'required|exists:approval_templates,id'
            ]);

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
            $existingApprovers = ApprovalTemplateDetail::forTemplate($request->master_approval_id)
                ->with('user:id,name,email')
                ->get()
                ->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'user_id' => $detail->user_id,
                        'user_name' => optional($detail->user)->name ?? 'User tidak tersedia',
                        'user_email' => optional($detail->user)->email ?? '-',
                        'step_ordering' => $detail->step_ordering,
                        'status' => $detail->status,
                    ];
                });

            return FormatResponseJson::success([
                'users' => $users,
                'existing_approvers' => $existingApprovers,
                'template' => [
                    'id' => $template->id,
                    'name' => $template->name,
                    'office' => optional($template->office)->name ?? '-',
                    'department' => optional($template->department)->name ?? '-',
                ],
            ], 'Users fetched successfully');

        } catch (\Exception $e) {
            \Log::error('Fetch Approval User Error', ['exception' => $e]);
            return FormatResponseJson::error(null, 'Data approver tidak dapat dimuat.', 500);
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
            ], 'Office and department fetched successfully');

        } catch (\Exception $e) {
            \Log::error('Fetch Approval Master Data Error', ['exception' => $e]);
            return FormatResponseJson::error(null, 'Data office dan department tidak dapat dimuat.', 500);
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
        ], [
            'approval_master_name.required' => 'Nama template approval wajib diisi',
            'approval_master_name.min' => 'Nama template minimal 3 karakter',
            'approval_master_name.max' => 'Nama template maksimal 255 karakter',
            'approval_master_name.regex' => 'Nama template hanya boleh berisi huruf, angka, spasi, dan tanda hubung',
            'stagging_approval_office.required' => 'Office wajib dipilih',
            'stagging_approval_office.exists' => 'Office yang dipilih tidak valid',
            'stagging_approval_department.required' => 'Department wajib dipilih',
            'stagging_approval_department.exists' => 'Department yang dipilih tidak valid',
        ]);
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
                    throw new \DomainException('Template approval untuk kombinasi Office dan Department ini sudah ada.');
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
            ], 'Template approval berhasil dibuat');

        } catch (\DomainException $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 409);
        } catch (\Exception $e) {
            \Log::error('Store Approval Template Error', ['exception' => $e]);
            return FormatResponseJson::error(null, 'Terjadi kesalahan saat membuat template approval.', 500);
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
                'stagging_approval_name' => [
                    'required',
                    'array',
                    'min:1', // Minimal 1 approver
                    'max:10', // Maksimal 10 approver
                ],
                'stagging_approval_name.*' => [
                    'required',
                    'integer',
                    'distinct', // Tidak boleh ada user yang sama
                    'exists:users,id',
                ],
            ], [
                'master_approval_id.required' => 'Master Approval ID wajib diisi',
                'master_approval_id.exists' => 'Master Approval tidak ditemukan',

                'stagging_approval_name.required' => 'Minimal satu approver harus dipilih',
                'stagging_approval_name.array' => 'Format approver tidak valid',
                'stagging_approval_name.min' => 'Minimal harus ada 1 approver',
                'stagging_approval_name.max' => 'Maksimal 10 approver',

                'stagging_approval_name.*.distinct' => 'Tidak boleh ada approver yang sama',
                'stagging_approval_name.*.exists' => 'Salah satu user yang dipilih tidak valid',
            ]);

        if ($validator->fails()) {
            return FormatResponseJson::error(null, ['errors' => $validator->errors()], 422);
        }

        $invalidUsers = User::whereIn('id', $request->stagging_approval_name)
            ->whereDoesntHave('roles', function($q) {
                $q->whereIn('name', ['admin', 'super-user']);
            })
            ->pluck('name');

        if ($invalidUsers->isNotEmpty()) {
            return FormatResponseJson::error(null,
                'User berikut tidak memiliki role yang sesuai: ' . $invalidUsers->implode(', '),
                400
            );
        }

        try {
            $details = DB::transaction(function () use ($request) {
                ApprovalTemplate::whereKey($request->master_approval_id)->lockForUpdate()->firstOrFail();
                ApprovalTemplateDetail::where('approval_id', $request->master_approval_id)->delete();
                $details = [];
                foreach ($request->stagging_approval_name as $index => $userId) {
                    $details[] = [
                        'approval_id' => $request->master_approval_id,
                        'user_id' => $userId,
                        'step_ordering' => $index + 1,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                ApprovalTemplateDetail::insert($details);
                return $details;
            }, 3);
            return FormatResponseJson::success([
                'total_approvers' => count($details),
            ], 'Detail approver berhasil disimpan');
        } catch (\Exception $e) {
            \Log::error('Submit Approval Detail Error', ['exception' => $e]);
            return FormatResponseJson::error(null, 'Terjadi kesalahan saat menyimpan detail approver.', 500);
        }
    }

    /**
     * ========== DELETE TEMPLATE ==========
     */
    public function deleteApprovalTemplate(Request $request)
    {
        $validated = $request->validate([
            'template_id' => 'required|integer|exists:approval_templates,id',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $template = ApprovalTemplate::whereKey($validated['template_id'])->lockForUpdate()->firstOrFail();
                $isUsed = ApprovalProcess::where('location_id', $template->location_id)
                    ->where('department_id', $template->department_id)
                    ->whereIn('status', [ApprovalProcess::STATUS_PENDING, ApprovalProcess::STATUS_IN_PROGRESS])
                    ->exists();
                if ($isUsed) {
                    throw new \DomainException('Template tidak dapat dihapus karena sedang digunakan dalam proses approval.');
                }
                $template->details()->delete();
                $template->delete();
            }, 3);
            return FormatResponseJson::success(null, 'Template berhasil dihapus');
        } catch (\DomainException $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 409);
        } catch (\Exception $e) {
            \Log::error('Delete Approval Template Error', ['exception' => $e]);
            return FormatResponseJson::error(null, 'Terjadi kesalahan saat menghapus template approval.', 500);
        }
    }
}

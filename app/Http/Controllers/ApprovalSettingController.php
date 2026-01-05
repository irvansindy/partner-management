<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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
                ->get()
                ->map(function ($template) {
                    return [
                        'id' => $template->id,
                        'name' => $template->name,
                        'office_id' => $template->location_id,
                        'department_id' => $template->department_id,
                        'office' => $template->office->name ?? '-',
                        'department' => $template->department->name ?? '-',
                        'total_steps' => $template->getTotalSteps(),
                        'status' => $template->isActive() ? 'Active' : 'Inactive',
                        'created_by' => $template->user->name ?? '-',
                        'created_at' => $template->created_at->format('d M Y'),
                    ];
                });

            return FormatResponseJson::success($templates, 'Approval templates fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }

    public function fetchUserApproval(Request $request)
    {
        try {
            $request->validate([
                'master_approval_id' => 'required|exists:approval_templates,id'
            ]);

            // Ambil user yang eligible sebagai approver
            $users = User::with('roles')
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
                        'user_name' => $detail->user->name,
                        'user_email' => $detail->user->email,
                        'step_ordering' => $detail->step_ordering,
                        'status' => $detail->status,
                    ];
                });

            return FormatResponseJson::success([
                'users' => $users,
                'existing_approvers' => $existingApprovers,
            ], 'Users fetched successfully');

        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
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
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }

    /**
     * ========== VALIDATION TERBAIK ==========
     */
    public function storeApprovalTemplate(Request $request)
    {
        try {
            DB::beginTransaction();

            // ✅ VALIDASI LENGKAP
            $validator = Validator::make($request->all(), [
                // Required fields
                'approval_master_name' => [
                    'required',
                    'string',
                    'min:3',
                    'max:255',
                    'regex:/^[a-zA-Z0-9\s\-\_]+$/', // Hanya alphanumeric, space, dash, underscore
                ],
                'stagging_approval_office' => [
                    'required',
                    'integer',
                    'exists:master_offices,id',
                ],
                'stagging_approval_department' => [
                    'required',
                    'integer',
                    'exists:master_departments,id',
                ],

                // Optional fields
                'status' => 'nullable|in:0,1',

            ], [
                // Custom error messages (Bahasa Indonesia)
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
                throw new ValidationException($validator);
            }

            // ✅ CEK DUPLIKASI (office + department sudah ada?)
            $existingTemplate = ApprovalTemplate::where([
                'location_id' => $request->stagging_approval_office,
                'department_id' => $request->stagging_approval_department,
            ])->first();

            if ($existingTemplate) {
                return FormatResponseJson::error(null,
                    'Template approval untuk kombinasi Office dan Department ini sudah ada. Silakan edit template yang sudah ada atau pilih kombinasi lain.',
                    409 // 409 Conflict
                );
            }

            // ✅ VALIDASI OFFICE & DEPARTMENT AKTIF
            $office = MasterOffice::where('id', $request->stagging_approval_office)->first();

            if (!$office) {
                return FormatResponseJson::error(null, 'Office yang dipilih tidak aktif', 400);
            }

            $department = MasterDepartment::where('id', $request->stagging_approval_department)->first();

            if (!$department) {
                return FormatResponseJson::error(null, 'Department yang dipilih tidak aktif', 400);
            }

            // ✅ CREATE TEMPLATE
            $template = ApprovalTemplate::create([
                'name' => trim($request->approval_master_name),
                'user_id' => auth()->id(),
                'location_id' => $request->stagging_approval_office,
                'department_id' => $request->stagging_approval_department,
                'step_ordering' => 1,
                'status' => $request->status ?? 1, // Default active
            ]);

            DB::commit();

            return FormatResponseJson::success([
                'template_id' => $template->id,
                'name' => $template->name,
            ], 'Template approval berhasil dibuat');

        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Store Approval Template Error: ' . $e->getMessage());
            return FormatResponseJson::error(null, 'Terjadi kesalahan saat membuat template: ' . $e->getMessage(), 500);
        }
    }

    /**
     * ========== VALIDASI SUBMIT DETAIL ==========
     */
    public function submitApprovalDetail(Request $request)
    {
        try {
            DB::beginTransaction();

            // ✅ VALIDASI LENGKAP
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
                throw new ValidationException($validator);
            }

            // ✅ VALIDASI APPROVER ADALAH ADMIN/SUPER-USER
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

            // ✅ VALIDASI USER AKTIF
            // $inactiveUsers = User::whereIn('id', $request->stagging_approval_name)
            //     ->where('status', '!=', 1)
            //     ->pluck('name');
            // dd($inactiveUsers);
            // if ($inactiveUsers->isNotEmpty()) {
            //     return FormatResponseJson::error(null,
            //         'User berikut tidak aktif: ' . $inactiveUsers->implode(', '),
            //         400
            //     );
            // }

            // ✅ DELETE EXISTING & INSERT NEW
            ApprovalTemplateDetail::where('approval_id', $request->master_approval_id)->delete();

            $details = [];
            foreach ($request->stagging_approval_name as $index => $userId) {
                $details[] = [
                    'approval_id' => $request->master_approval_id,
                    'user_id' => $userId,
                    'step_ordering' => $index + 1,
                    'status' => 0, // Active
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            ApprovalTemplateDetail::insert($details);

            DB::commit();

            return FormatResponseJson::success([
                'total_approvers' => count($details),
            ], 'Detail approver berhasil disimpan');

        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Submit Approval Detail Error: ' . $e->getMessage());
            return FormatResponseJson::error(null, 'Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    /**
     * ========== DELETE TEMPLATE ==========
     */
    public function deleteApprovalTemplate(Request $request)
    {
        try {
            DB::beginTransaction();

            $template = ApprovalTemplate::findOrFail($request->template_id);

            // ✅ CEK APAKAH TEMPLATE SEDANG DIGUNAKAN
            $isUsed = ApprovalProcess::where([
                'location_id' => $template->location_id,
                'department_id' => $template->department_id,
            ])->whereIn('status', [0, 1])->exists();

            if ($isUsed) {
                return FormatResponseJson::error(null,
                    'Template tidak dapat dihapus karena sedang digunakan dalam proses approval',
                    409
                );
            }

            // Delete details first (foreign key)
            $template->details()->delete();

            // Delete template
            $template->delete();

            DB::commit();

            return FormatResponseJson::success(null, 'Template berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
}

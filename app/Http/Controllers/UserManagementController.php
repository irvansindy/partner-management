<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MasterOffice;
use App\Models\MasterDivision;
use App\Models\MasterDepartment;
use App\Models\MasterJobTitles;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('users.index');
    }
    public function fetchUser()
    {
        try {
            $user = User::with('roles')
            ->whereHas('roles', function ($q) {
                $q->where('name','!=','user');
            })
            ->get();
            // $user = User::with('roles')->get();
            return FormatResponseJson::success($user, 'user fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function storeUser(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|unique:users,email',
                'nik' => 'required|unique:users,nik',
                'employee_id' => 'required|unique:users,employee_id',
                'role' => 'required',
                'office' => 'required',
                'department'=> 'required',
                'job_title' => 'nullable|exists:master_job_titles,id',
                'division' => 'required',
            ], [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'role.required' => 'Role is required',
                'office.required' => 'Office is required',
                'department.required' => 'Department is required',
                'job_title.exists' => 'Job title is invalid',
                'division.required' => 'Division is required',
                'nik.required' => 'NIK is required',
                'nik.unique' => 'NIK must be unique',
                'employee_id.required' => 'Employee ID is required',
                'employee_id.unique' => 'Employee ID must be unique',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'nik' => $request->nik,
                'employee_id' => $request->employee_id,
                'password' => Hash::make('pass12345'),
                'office_id' => $request->office,
                'parent_user_id' => $request->parent,
                'division_id' => $request->division,
                'department_id' => $request->department,
                'job_title_id' => $request->job_title,
            ]);

            $user->assignRole($request->role);
            DB::commit();
            return FormatResponseJson::success($user, 'user created successfully');
        } catch (ValidationException $e) {
            // Return validation errors as JSON response
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null,$e->getMessage(), 400);
        }
    }
    public function fetchRoleOfficeDepartment()
    {
        try {
            $roles = Role::where('name', '!=', 'user')->get();
            $offices = MasterOffice::all(['id', 'name']);
            $departments = MasterDepartment::all(['id', 'name']);
            $divisions = MasterDivision::get(['id', 'name']);
            $jobTitles = MasterJobTitles::with('level')->get();
            $parents = User::whereHas('roles', function ($q) {
                $q->where('name', '!=', 'user');
            })->get(['id', 'name']);
            $data = [
                'roles'=> $roles,
                'offices'=> $offices,
                'departments'=> $departments,
                'divisions'=> $divisions,
                'job_titles'=> $jobTitles,
                'parents'=> $parents,
            ];
            return FormatResponseJson::success($data, 'supporting data fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function detailUser(Request $request)
    {
        try {
            $user = User::with(['roles', 'permissions', 'office', 'division', 'dept', 'jobTitle', 'level'])->where('users.id', $request->id)->first();
            $roles = Role::get(['id', 'name']);
            $offices = MasterOffice::get(['id', 'name']);
            $departments = MasterDepartment::get(['id', 'name']);
            $divisions = MasterDivision::all(['id', 'name']);
            $jobTitles = MasterJobTitles::with('level')->get();
            $parents = User::whereHas('roles', function ($q) {
                $q->where('name', '!=', 'user');
            })->get(['id', 'name']);
            $data = [
                'user' => $user,
                'roles' => $roles,
                'offices' => $offices,
                'departments'=> $departments,
                'divisions'=> $divisions,
                'job_titles'=> $jobTitles,
                'parents'=> $parents,
            ];
            return FormatResponseJson::success($data, 'user fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function updateUser(Request $request)
    {
        try {
            DB::beginTransaction();

            // ✅ Validasi request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'role' => 'required',
                'office' => 'required|integer|exists:master_offices,id',
                'department' => 'required|integer|exists:master_departments,id',
                'division' => 'nullable|integer|exists:master_divisions,id',
                'job_title' => 'nullable|integer|exists:master_job_titles,id',
                'parent_user' => 'nullable|integer|exists:users,id',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // ✅ Siapkan data update
            $data = [
                'name'           => $request->name,
                'email'          => $request->email,
                'office_id'      => $request->office,
                'department_id'  => $request->department,
                'division_id'    => $request->division,
                'job_title_id'   => $request->job_title,
                'parent_user_id' => $request->parent_user,
            ];

            // ✅ Update User
            $user = User::findOrFail($request->id);
            $user->update($data);

            // ✅ Update Role jika berubah
            if ($request->current_role !== $request->role) {
                $user->syncRoles([$request->role]);
            }

            DB::commit();

            return FormatResponseJson::success($user->load('roles', 'office', 'division', 'dept', 'jobTitle', 'parent'), 'User updated successfully');
        } 
        catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } 
        catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function updateUserOld(Request $request)
    {
        try {
            DB::beginTransaction();
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email',
                'role' => 'required',
                'office' => 'required',
                'department'=> 'required',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $data = [
                'name'=> $request->name,
                'email'=> $request->email,
                'office_id'=> $request->office,
                'department_id' => $request->department,
            ];

            $user = User::findOrFail($request->id);
            $user->removeRole($request->current_role);
            $user->update($data);
            $user->assignRole($request->role);
            DB::commit();
            return FormatResponseJson::success($user, 'user updated successfully');
        } catch (ValidationException $e) {
            // Return validation errors as JSON response
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null,$e->getMessage(), 400);
        }
    }
    public function deleteUser(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($request->id);
            $user->removeRole($request->current_role);
            $user->delete();
            DB::commit();
            return FormatResponseJson::success($user, 'user deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null,$e->getMessage(), 400);
        }
    }
}

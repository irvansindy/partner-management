<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MasterOffice;
use App\Models\MasterDepartment;
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
                'role' => 'required',
                'office' => 'required',
                // 'parent_user'=> 'required',
                'department'=> 'required',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('pass12345'),
                'office_id' => $request->office,
                'parent_user_id' => 1,
                'department_id' => $request->department,
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
            $offices = MasterOffice::all();
            $departments = MasterDepartment::all();
            $data = [
                'roles'=> $roles,
                'offices'=> $offices,
                'departments'=> $departments,
            ];
            return FormatResponseJson::success($data, 'role, office, departement fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function detailUser(Request $request)
    {
        try {
            $user = User::with(['roles', 'permissions', 'office', 'dept'])->where('users.id', $request->id)->first();
            $roles = Role::get(['id', 'name']);
            $offices = MasterOffice::get(['id', 'name']);
            $departments = MasterDepartment::get(['id', 'name']);
            $data = [
                $user,
                'roles' => $roles,
                'offices' => $offices,
                'departments'=> $departments,
                
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

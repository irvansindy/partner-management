<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MasterOffice;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
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
            $user = User::with('roles')->get();
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
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|unique:users,email',
                'role' => 'required',
                'office' => 'required',
                'parent_user'=> 'required',
                'department'=> 'required',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('pass12345'),
                'office_id' => $request->office,
                'parent_user_id' => $request->parent_user,
                'department_id' => $request->department,
            ]);

            $user->assignRole($request->role);
            DB::commit();
            return FormatResponseJson::success($user, 'user created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null,$e->getMessage(), 400);
        }
    }
    public function detailUser(Request $request)
    {
        try {
            $user = User::with(['roles', 'permissions', 'office'])->where('users.id', $request->id)->first();
            $roles = Role::all();
            $offices = MasterOffice::all();
            $data = [
                $user,
                'roles' => $roles,
                'offices' => $offices
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
            // dd($request->current_role);
            $request->validate([
                'name' => 'required|string',
                'email' => 'required',
                'role' => 'required',
                'office' => 'required',
            ]);

            $data = [
                'name'=> $request->name,
                'email'=> $request->email,
                'office_id'=> $request->office,
            ];

            $user = User::findOrFail($request->id);
            $user->removeRole($request->current_role);
            $user->update($data);
            $user->assignRole($request->role);
            DB::commit();
            return FormatResponseJson::success($user, 'user updated successfully');
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

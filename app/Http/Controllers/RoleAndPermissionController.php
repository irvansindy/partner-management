<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\SubMenu;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Helpers\FormatResponseJson;
class RoleAndPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view("role_and_permission.index");
    }
    public function fetchRole()
    {
        try {
            $fetch_role = Role::all();
            return FormatResponseJson::success($fetch_role, 'role fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function fetchPermission()
    {
        try {
            $fetch_permission = Permission::all();
            return FormatResponseJson::success($fetch_permission, 'permission fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function storeRole(Request $request)
    {
        try {
            $request->validate([
                'role_name'=> 'required|string',
            ]);
            $data = [
                'name' => $request->role_name,
                'guard_name'=>'web',
            ];
            $role = Role::create($data);
            return FormatResponseJson::success($role, 'role created successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 500);
        }
    }
    public function detailRole(Request $request)
    {
        try {
            $detail_role = Role::findOrFail($request->role_id);
            return FormatResponseJson::success($detail_role,'role fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function updateRole(Request $request)
    {
        try {
            $request->validate([
                'role_name'=> 'required|string',
            ]);
            $data = [
                'name' => $request->role_name,
                'guard_name'=>'web',
            ];
            $update_role = Role::findOrFail($request->role_id)->update($data);
            return FormatResponseJson::success($update_role,'role updated successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 500);
        }
    }
    public function deleteRole(Request $request)
    {
        try {
            $delete_role = Role::findOrFail($request->role_id)->delete();
            return FormatResponseJson::success($delete_role,'role deleted successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 500);
        }
    }
    public function storePermission(Request $request)
    {
        try {
            $request->validate([
                'permission_name'=> 'required|string|unique:permissions,name',
            ]);
            $data = [
                'name' => $request->permission_name,
                'guard_name'=>'web',
            ];
            $permission = Permission::create($data);
            return FormatResponseJson::success($permission,'permission created successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 500);
        }
    }
    public function detailPermission(Request $request)
    {
        try {
            $detail_permission = Permission::findOrFail($request->permission_id);
            return FormatResponseJson::success($detail_permission,'permission fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function updatePermission(Request $request)
    {
        try {
            $request->validate([
                'permission_name'=> 'required|string|unique:permissions,name',
            ]);
            $data = [
                'name' => $request->permission_name,
                'guard_name'=>'web',
            ];
            $update_permission = Permission::findOrFail($request->permission_id)->update($data);
            return FormatResponseJson::success($update_permission,'permission updated successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 500);
        }
    }
    public function deletePermission(Request $request)
    {
        try {
            $delete_permission = Permission::findOrFail($request->permission_id)->delete();
            return FormatResponseJson::success($delete_permission,'permission deleted successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 500);
        }
    }
}

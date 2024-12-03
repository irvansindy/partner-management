<?php

namespace App\Http\Controllers;

use App\Models\MasterOffice;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\SubMenu;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
class RoleAndPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view("admin.role_and_permission.index");
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
    public function fetchPermissionView()
    {
        try {
            $fetch_permission = Permission::where('name', 'LIKE', '%view_%')
            ->get(['id','name', 'guard_name']);
            return FormatResponseJson::success($fetch_permission, 'permission fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function menu()
    {
        
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
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'permission_name'=> 'required|string|unique:permissions,name',
            ], [
                'permission_name.required' => 'Permission tidak boleh kosong',
                'permission_name.unique' => 'Permission tersebut sudah tersedia'
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $data = [
                'name' => $request->permission_name,
                'guard_name'=>'web',
            ];
            $permission = Permission::create($data);
            DB::commit();
            return FormatResponseJson::success($permission,'permission created successfully');
        } catch (ValidationException $e) {
            // Return validation errors as JSON response
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
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
    public function fetchPermissionInRole(Request $request)
    {
        try {
            $permissions = Permission::all();
            $role = Role::findOrFail($request->role_id);
            $permission_by_role = $role->getAllPermissions();
            $data = [
                'permissions' => $permissions,
                'permission_by_role' => $permission_by_role
            ];
            return FormatResponseJson::success($data,'permission fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function addOrRemovePermissionToRole(Request $request)
    {
        try {
            DB::beginTransaction();
            // dd($request->all());
            $role_id = $request->role_id_for_permission;
            
            // get data role user and new permission
            $existing_role = Role::findOrFail($role_id);
            $list_new_permissions = $request->permissions;
            
            // get permission relation with role user 
            $permission_from_role = $existing_role->permissions;
            if (count($permission_from_role) != false) {
                // clear permission 
                // dd($permission_from_role);
                $existing_role->syncPermissions([]);
            }
            // dd('hahahahah');
            $existing_role->givePermissionTo($list_new_permissions);
            $existing_role->syncPermissions($list_new_permissions);
            DB::commit();
            return FormatResponseJson::success($list_new_permissions,'Berhasil mengsinkronkan permission.');
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }

    public function fetchPermissionInRoleV2()
    {
        try {
            $role = Role::findOrFail(4);

            // Dapatkan semua permissions
            $permissions = Permission::all();

            // Tandai permission yang sudah dimiliki oleh role
            $permissions = $permissions->map(function ($permission) use ($role) {
                $permission->assigned = $role->hasPermissionTo($permission);
                return $permission;
            });

            $data = [
                'permissions' => $permissions,
                'role' => $role
            ];

            return FormatResponseJson::success($data, 'Permissions fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
}

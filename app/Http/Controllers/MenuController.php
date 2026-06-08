<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class MenuController extends Controller
{
    public function index()
    {
        return view('admin.menus.index');
    }
    public function fetchMenu()
    {
        try {
            $menus = Menu::with(['permission'])->where('type', 1)->get();
            return FormatResponseJson::success($menus, 'menu berhasil diambil');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function fetchParentMenu()
    {
        try {
            $menus = Menu::with(['permission'])->where('type', 1)->get();
            return FormatResponseJson::success($menus, 'menu berhasil diambil');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function fetchMenuById(Request $request)
    {
        try {
            $menu = Menu::with(['permission.roles'])->find($request->id);
            return FormatResponseJson::success($menu, 'menu berhasil diambil');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function fetchChildrenMenu(Request $request)    {
        try {
            $menus = Menu::with(['permission'])
            ->where('parent_id', $request->id)
            ->where('type', 2)->get();
            return FormatResponseJson::success($menus, 'menu berhasil diambil');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function storeMenu(Request $request)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'menu_name' => 'required|string',
                'menu_url' => 'required|string',
                'menu_icon' => 'required|string',
                'menu_type' => 'required',
                'parent_id' => 'nullable|exists:menus,id',
                // 'can_permission' => 'nullable|string',
                'roles' => 'nullable|array',
            ], [
                'name.required' => 'Nama tidak boleh kosong',
                'url.required' => 'Link / Url tidak boleh kosong',
                'icon.required' => 'Icon tidak boleh kosong',
                'menu_type.required' => 'Type tidak boleh kosong',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Buat permission jika diisi dan belum ada
            if ($request->menu_url && !Permission::where('name', $request->menu_url)->exists()) {
                // dd($request->menu_url);
                Permission::create(['name' => $request->menu_url, 'guard_name' => 'web']);
            }
            $result_type = $request->menu_type == 'parent' ? 1 : 2;
            $order_menu = Menu::count();
            $menu = Menu::create([
                'name_text'=> $request->menu_name,
                'url_name'=> $request->menu_url,
                'can_permission' =>  $request->menu_url,
                'icon'=> $request->menu_icon,
                'type'=> $result_type,
                'order' => $order_menu + 1,
                'parent_id' => $request->parent_id,
                'is_active' => 1,
            ]);

            // Assign permission ke role
            if ($request->menu_url && $request->roles) {
                // dd($request->parent_id);
                foreach ($request->roles as $roleId) {
                    $role = Role::findById($roleId);
                    $role->givePermissionTo($request->menu_url);
                }
            }

            DB::commit();
            return FormatResponseJson::success($menu, 'menu berhasil dibuat');
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 400);}
        catch (\Throwable $th) {
            DB::rollback();
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }

    public function updateMenu(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'menu_id' => 'required|exists:menus,id',
                'menu_name' => 'required|string',
                'menu_url' => 'required|string',
                'menu_icon' => 'required|string',
                'menu_type' => 'required',
                'parent_id' => 'nullable|exists:menus,id',
                'roles' => 'nullable|array',
            ], [
                'name.required' => 'Nama tidak boleh kosong',
                'url.required' => 'Link / Url tidak boleh kosong',
                'icon.required' => 'Icon tidak boleh kosong',
                'menu_type.required' => 'Type tidak boleh kosong',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $menu = Menu::findOrFail($request->menu_id);

            $oldPermissionName = $menu->can_permission;
            $newPermissionName = $request->menu_url;

            // create new permission if needed
            if ($newPermissionName && !Permission::where('name', $newPermissionName)->exists()) {
                Permission::create(['name' => $newPermissionName, 'guard_name' => 'web']);
            }

            // revoke old permission from all roles if changed
            if ($oldPermissionName && $oldPermissionName !== $newPermissionName) {
                $oldPerm = Permission::where('name', $oldPermissionName)->first();
                if ($oldPerm) {
                    $rolesAll = Role::all();
                    foreach ($rolesAll as $role) {
                        if ($role->hasPermissionTo($oldPerm)) {
                            $role->revokePermissionTo($oldPerm);
                        }
                    }
                    // delete old permission if exists
                    try {
                        $oldPerm->delete();
                    } catch (\Throwable $e) {
                        // ignore deletion errors
                    }
                }
            }

            $result_type = $request->menu_type == 'parent' ? 1 : 2;

            $menu->update([
                'name_text' => $request->menu_name,
                'url_name' => $request->menu_url,
                'can_permission' => $request->menu_url,
                'icon' => $request->menu_icon,
                'type' => $result_type,
                'parent_id' => $request->parent_id,
            ]);

            // assign permission to roles (revoke everywhere first to avoid duplicates)
            if ($newPermissionName) {
                $perm = Permission::where('name', $newPermissionName)->first();
                if ($perm) {
                    $rolesAll = Role::all();
                    foreach ($rolesAll as $role) {
                        if ($role->hasPermissionTo($perm)) {
                            $role->revokePermissionTo($perm);
                        }
                    }
                }
                if ($request->roles) {
                    foreach ($request->roles as $roleId) {
                        $role = Role::findById($roleId);
                        $role->givePermissionTo($newPermissionName);
                    }
                }
            }

            DB::commit();
            return FormatResponseJson::success($menu, 'menu berhasil diupdate');
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 400);
        } catch (\Throwable $th) {
            DB::rollback();
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }

    public function deleteMenu(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:menus,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $menu = Menu::findOrFail($request->id);

            // gather permissions to remove (this menu and its children)
            $permissionsToRemove = [];
            if ($menu->can_permission) {
                $permissionsToRemove[] = $menu->can_permission;
            }
            $children = Menu::where('parent_id', $menu->id)->get();
            foreach ($children as $child) {
                if ($child->can_permission) $permissionsToRemove[] = $child->can_permission;
            }

            // revoke and delete permissions
            foreach (array_unique($permissionsToRemove) as $pname) {
                $perm = Permission::where('name', $pname)->first();
                if ($perm) {
                    $rolesAll = Role::all();
                    foreach ($rolesAll as $role) {
                        if ($role->hasPermissionTo($perm)) {
                            $role->revokePermissionTo($perm);
                        }
                    }
                    try {
                        $perm->delete();
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }
            }

            // delete children
            foreach ($children as $child) {
                $child->delete();
            }

            $menu->delete();

            DB::commit();
            return FormatResponseJson::success(null, 'menu berhasil dihapus');
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 400);
        } catch (\Throwable $th) {
            DB::rollback();
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }

}

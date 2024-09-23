<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use App\Models\Menu;
use App\Models\SubMenu;
use app\Models\User;
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
            $menus = Menu::all();
            return FormatResponseJson::success($menus, 'menu berhasil diambil');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function storeMenu(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'url' => 'required|string',
                'icon' => 'required|string',
            ], [
                'name.required' => 'Nama tidak boleh kosong',
                'url.required' => 'Link / Url tidak boleh kosong',
                'icon.required' => 'Icon tidak boleh kosong',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $slug_name = Str::slug($request->name, '-');
            $menu = Menu::create([
                'name_text'=> $request->name,
                'url_name'=> $request->url,
                'can_permission' => null,
                'icon'=> $request->icon,
            ]);

            DB::commit();
            return FormatResponseJson::success($menu, 'menu berhasil dibuat');
        } catch (ValidationException $e) {
            // Return validation errors as JSON response
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 400);}
        catch (\Throwable $th) {
            DB::rollback();
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
}

<?php

namespace App\Http\Controllers\API\V1\Client;

use App\Http\Controllers\Controller;
use App\Services\MenuService;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Auth;
use Exception;

class MenuController extends Controller
{
    protected $menuService;
    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }
    public function index()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return FormatResponseJson::error(null, 'Unauthorized', 401);
            }

            $menus = $this->menuService->getAccessibleMenus();

            return FormatResponseJson::success(
                $menus->values(),
                'Menu fetched successfully'
            );

        } catch (Exception $e) {

            return FormatResponseJson::error(
                null,
                $e->getMessage(),
                500
            );
        }
    }
}

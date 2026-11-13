<?php

namespace Tests\Feature;

use App\Http\Controllers\DepartmentManagementController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OfficeManagementController;
use App\Http\Controllers\RoleAndPermissionController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\API\V1\Admin\APIWhiteListManageController;
use App\Http\Controllers\DivisionManagementController;
use App\Http\Requests\StoreDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use App\Models\User;
use App\Services\DivisionService;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\TestCase;

class SettingsLocalizationTest extends TestCase
{
    public function test_dictionaries_and_placeholders_match_in_both_languages(): void
    {
        foreach (['settings', 'settings_validation'] as $group) {
            $id = Arr::dot(require resource_path("lang/id/{$group}.php"));
            $en = Arr::dot(require resource_path("lang/en/{$group}.php"));
            $this->assertEqualsCanonicalizing(array_keys($id), array_keys($en));
            foreach ($id as $key => $text) {
                preg_match_all('/:[a-z_]+|_[A-Z]+_/', $text, $idTokens);
                preg_match_all('/:[a-z_]+|_[A-Z]+_/', $en[$key], $enTokens);
                $this->assertEqualsCanonicalizing($idTokens[0], $enTokens[0], "{$group}.{$key}");
            }
        }
    }

    public function test_settings_pages_and_modals_render_in_id_and_en(): void
    {
        $this->app->instance(MenuService::class, Mockery::mock(MenuService::class, function ($mock) {
            $mock->shouldReceive('getAccessibleMenus')->andReturn(collect());
        }));
        $user = (new User())->forceFill(['id' => 1, 'name' => 'Reviewer', 'email' => 'reviewer@example.test']);
        $user->setRelation('roles', collect([(object) ['name' => 'super-admin']]));
        $this->actingAs($user);
        $connection = new class(null) extends \Illuminate\Database\Connection {
            public function select($query, $bindings = [], $useReadPdo = true)
            {
                return [];
            }
        };
        $originalResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
        $resolver = new \Illuminate\Database\ConnectionResolver(['testing' => $connection]);
        $resolver->setDefaultConnection('testing');
        \Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);
        $this->beforeApplicationDestroyed(function () use ($originalResolver) {
            \Illuminate\Database\Eloquent\Model::setConnectionResolver($originalResolver);
        });
        \Illuminate\Support\Facades\Gate::shouldReceive('check')->andReturn(true);
        view()->share('errors', new \Illuminate\Support\ViewErrorBag());
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            foreach (['users.index', 'admin.menus.index', 'admin.role_and_permission.index', 'admin.office.index', 'admin.department.index', 'admin.division.index', 'admin.api_white_list.index'] as $view) {
                $html = view($view, ['generalManagers' => collect(), 'offices' => collect()])->render();
                $this->assertDoesNotMatchRegularExpression('/\b(?:settings\.[A-Za-z]|settings_validation\.[A-Za-z]|messages\.(?:User Management|Office Setting|Menu Setting|Role Permission))/', $html, $view);
                $this->assertStringContainsString('modal', $html, $view);
                if ($view === 'admin.division.index') {
                    $this->assertStringContainsString(__('settings.Division Management'), $html);
                    $this->assertStringContainsString(__('settings.Refresh divisions'), $html);
                }
                if ($view === 'admin.department.index') {
                    $this->assertStringContainsString(__('settings.Department Management'), $html);
                }
            }
        }
    }

    public function test_empty_crud_requests_return_localized_field_errors(): void
    {
        DB::shouldReceive('beginTransaction')->andReturnNull();
        DB::shouldReceive('rollback')->andReturnNull();
        $cases = [
            [new UserManagementController(), 'storeUser', 'name', 'settings.Name is required'],
            [new MenuController(), 'storeMenu', 'menu_name', 'settings.Nama tidak boleh kosong'],
            [new DepartmentManagementController(), 'CreateOrUpdateDepartment', 'department_name', 'settings.Nama Department tidak boleh kosong'],
            [new OfficeManagementController(), 'CreateOrUpdateOffice', 'office_name', 'settings.Nama Office tidak boleh kosong'],
            [new RoleAndPermissionController(), 'storePermission', 'permission_name', 'settings.Permission tidak boleh kosong'],
            [new APIWhiteListManageController(), 'createOrUpdate', 'ip_address', 'settings.IP address is required'],
        ];
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            foreach ($cases as [$controller, $method, $field, $key]) {
                $response = $controller->$method(new Request());
                $this->assertContains($response->getStatusCode(), [400, 422], $method);
                $this->assertSame(__($key), $response->getData(true)['meta']['message']['errors'][$field][0], $method);
            }
            $response = (new RoleAndPermissionController())->storeRole(new Request());
            $this->assertSame(422, $response->getStatusCode());
            $this->assertSame(str_replace(':attribute', __('settings_validation.attributes.role_name'), __('settings_validation.messages.required')), $response->getData(true)['meta']['message']['errors']['role_name'][0]);
        }
    }

    public function test_division_form_requests_and_backend_errors_use_locale(): void
    {
        $service = Mockery::mock(DivisionService::class);
        $service->shouldReceive('getDivisions')->twice()->andThrow(new \RuntimeException('SQLSTATE private database error'));
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            foreach ([new StoreDivisionRequest(), new UpdateDivisionRequest()] as $request) {
                $validator = Validator::make([], $request->rules(), $request->messages(), $request->attributes());
                $this->assertSame(__('settings.Division name is required.'), $validator->errors()->first('name'));
                $validator = Validator::make(['name' => str_repeat('x', 256)], ['name' => 'string|max:255'], $request->messages(), $request->attributes());
                $this->assertSame(strtr(__('settings_validation.messages.max.string'), [':attribute' => __('settings_validation.attributes.name'), ':max' => 255]), $validator->errors()->first('name'));
            }
            $response = (new DivisionManagementController($service))->fetchDivision();
            $this->assertSame(__('settings.operation_failed'), $response->getData(true)['meta']['message']);
            $this->assertStringNotContainsString('SQLSTATE', $response->getContent());
        }
    }

    public function test_sidebar_translates_known_names_and_preserves_custom_names_and_urls(): void
    {
        $menu = ['name' => 'Setting', 'url' => null, 'icon' => '', 'children' => [
            ['name' => 'Users', 'url' => 'user-manage', 'icon' => '', 'children' => []],
            ['name' => 'Custom Business Menu', 'url' => 'custom-business', 'icon' => '', 'children' => []],
        ]];
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $html = view('components.layout.sidebar-menu-item', [
                'menu' => $menu, 'depth' => 0, 'isActive' => fn () => false, 'isMenuActive' => fn () => false,
            ])->render();
            $this->assertStringContainsString(__('settings.menus.Setting'), $html);
            $this->assertStringContainsString(__('settings.menus.Users'), $html);
            $this->assertStringContainsString('Custom Business Menu', $html);
            $this->assertStringContainsString(url('user-manage'), $html);
        }
    }
}

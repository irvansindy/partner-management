<?php

namespace Tests\Feature;

use App\Exports\PartnerExport;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PartnerManagementController;
use App\Models\User;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class DashboardPartnerLocalizationTest extends TestCase
{
    public function test_partner_dictionary_has_matching_keys_and_placeholders(): void
    {
        $id = Arr::dot(require resource_path('lang/id/partner.php'));
        $en = Arr::dot(require resource_path('lang/en/partner.php'));
        $this->assertEqualsCanonicalizing(array_keys($id), array_keys($en));
        foreach ($id as $key => $text) {
            preg_match_all('/:[a-z_]+/', $text, $idTokens);
            preg_match_all('/:[a-z_]+/', $en[$key], $enTokens);
            $this->assertEqualsCanonicalizing($idTokens[0], $enTokens[0], $key);
        }
        $sources = file_get_contents(resource_path('views/admin/dashboard/index_live.blade.php'))
            .file_get_contents(resource_path('views/admin/dashboard/live_js.blade.php'));
        preg_match_all("/__\('messages\.(Dashboard [^']+)'/", $sources, $matches);
        foreach (array_unique($matches[1]) as $key) {
            $idText = trans('messages.'.$key, [], 'id');
            $enText = trans('messages.'.$key, [], 'en');
            $this->assertNotSame('messages.'.$key, $idText);
            $this->assertNotSame('messages.'.$key, $enText);
            preg_match_all('/:[a-z_]+|_[A-Z]+_/', $idText, $idTokens);
            preg_match_all('/:[a-z_]+|_[A-Z]+_/', $enText, $enTokens);
            $this->assertEqualsCanonicalizing($idTokens[0], $enTokens[0], $key);
        }
    }

    public function test_dashboard_partner_list_modal_and_exports_render_in_both_languages(): void
    {
        $this->app->instance(MenuService::class, Mockery::mock(MenuService::class, function ($mock) {
            $mock->shouldReceive('getAccessibleMenus')->andReturn(collect());
        }));
        $user = (new User())->forceFill(['id' => 1, 'name' => 'Reviewer', 'email' => 'reviewer@example.test']);
        $user->setRelation('roles', collect([(object) ['name' => 'super-admin']]));
        $this->actingAs($user);
        \Illuminate\Support\Facades\Gate::shouldReceive('check')->andReturn(true);
        $partner = (object) [
            'name' => '<Example Partner>', 'group_name' => 'Example Group', 'type' => 'customer',
            'established_year' => 2000, 'total_employee' => 50, 'owner_name' => null,
            'email_address' => 'partner@example.test', 'website_address' => 'https://example.test',
        ];
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $dashboard = view('admin.dashboard.index_live', ['user' => $user, 'isSuperAdmin' => true])->render();
            $page = view('partner.index')->render();
            $pdf = view('partner.export_pdf', ['partners' => collect([$partner])])->render();
            $this->assertStringContainsString(__('messages.Dashboard Title'), $dashboard);
            $this->assertStringContainsString(__('partner.Zoom in'), $dashboard);
            $this->assertStringContainsString(__('partner.Partner Management'), $page);
            $this->assertStringContainsString(__('partner.Detail Partner'), $page);
            $this->assertStringContainsString(__('partner.Tutup'), $page);
            $this->assertStringContainsString(__('partner.Website'), $pdf);
            $this->assertStringContainsString('&lt;Example Partner&gt;', $pdf);
            $this->assertStringContainsString(__('messages.Dashboard Customer'), $pdf);
            $this->assertSame(__('partner.Nama Perusahaan'), (new PartnerExport())->headings()[0]);
            $this->assertSame(__('partner.Website'), (new PartnerExport())->headings()[7]);
            $emptyPdf = view('partner.export_pdf', ['partners' => collect()])->render();
            $this->assertStringContainsString(__('partner.No partners found'), $emptyPdf);
            $this->assertDoesNotMatchRegularExpression('/\b(?:partner\.[A-Za-z]|messages\.Dashboard|form_links\.[A-Za-z])/', $page.$dashboard.$pdf);
        }
    }

    public function test_cached_dashboard_messages_follow_current_locale(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->forceFill(['id' => 1, 'department_id' => 2, 'office_id' => 3]);
        $user->shouldReceive('getRoleNames')->andReturn(collect(['super-admin']));
        $controller = new DashboardController();
        foreach (['user' => $user, 'isSuperAdmin' => true] as $property => $value) {
            $reflection = new \ReflectionProperty(DashboardController::class, $property);
            $reflection->setAccessible(true);
            $reflection->setValue($controller, $value);
        }
        Cache::shouldReceive('remember')->times(4)->andReturn(collect());
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $response = $controller->fetchRecentCustomer()->getData(true);
            $this->assertSame(__('partner.No recent customers found'), $response['meta']['message']);
            $response = $controller->fetchRecentVendor()->getData(true);
            $this->assertSame(__('partner.No recent vendors found'), $response['meta']['message']);
            $response = $controller->fetchDataCountByRegency(new Request())->getData(true);
            $this->assertSame(__('partner.Province parameter is required'), $response['meta']['message']);
        }
    }

    public function test_excel_type_labels_follow_locale_without_changing_the_query(): void
    {
        DB::shouldReceive('select')->twice()->with(Mockery::on(function ($sql) {
            return str_contains($sql, 'FROM company_informations');
        }))->andReturnUsing(function () {
            return [(object) ['name' => 'Example Partner', 'type' => 'customer']];
        });
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $rows = (new PartnerExport())->collection();
            $this->assertSame('Example Partner', $rows->first()->name);
            $this->assertSame(__('messages.Dashboard Customer'), $rows->first()->type);
        }
    }

    public function test_partner_query_errors_are_localized_without_database_details(): void
    {
        DB::shouldReceive('connection')->andThrow(new \RuntimeException('SQLSTATE confidential database details'));
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $controller = new PartnerManagementController();
            $response = $controller->fetchPartner();
            $this->assertSame(500, $response->getStatusCode());
            $this->assertSame(__('partner.Unable to load partner data.'), $response->getData(true)['meta']['message']);
            $response = $controller->detailPartner(new Request(['partner_id' => 1]));
            $this->assertSame(__('partner.Unable to load partner details.'), $response->getData(true)['meta']['message']);
            $this->assertStringNotContainsString('SQLSTATE', $response->getContent());
        }
    }
}

<?php

namespace Tests\Feature;

use App\Http\Controllers\EndUserLicenseAgreementController;
use App\Http\Controllers\TenderForVendorController;
use App\Http\Controllers\PartnerController;
use App\Models\User;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\TestCase;

class SupportingLocalizationTest extends TestCase
{
    public function test_success_response_does_not_keep_metadata_from_an_earlier_error(): void
    {
        \App\Helpers\FormatResponseJson::error(null, 'Error', 500, 'previous-request');
        $response = \App\Helpers\FormatResponseJson::success(['id' => 1], 'Saved');
        $this->assertSame(200, $response->status());
        $this->assertSame('success', $response->getData(true)['meta']['status']);
        $this->assertArrayNotHasKey('request_id', $response->getData(true)['meta']);
    }

    public function test_keys_and_validation_attributes_match_in_both_languages(): void
    {
        foreach (['supporting', 'supporting_validation'] as $group) {
            $id = Arr::dot(require resource_path("lang/id/{$group}.php"));
            $en = Arr::dot(require resource_path("lang/en/{$group}.php"));
            $this->assertEqualsCanonicalizing(array_keys($id), array_keys($en));
        }
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $validator = Validator::make([], ['create_tender_name' => 'required', 'file_supporting_document' => 'required'], __('public_validation.messages'), __('supporting_validation'));
            $this->assertStringContainsString(__('supporting.Tender Name'), $validator->errors()->first('create_tender_name'));
            $this->assertStringContainsString(__('supporting.Document File'), $validator->errors()->first('file_supporting_document'));
            $this->assertStringNotContainsString('create_tender_name', $validator->errors()->first());
        }
    }

    public function test_document_validation_response_uses_active_language(): void
    {
        DB::shouldReceive('rollback')->twice();
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $response = (new PartnerController())->storeAttachment(new Request());
            $this->assertSame(422, $response->status());
            $this->assertStringContainsString(__('supporting.Document File'), $response->getData(true)['meta']['message']['errors']['file_supporting_document'][0]);
        }
    }

    public function test_tender_validation_response_uses_active_language(): void
    {
        DB::shouldReceive('beginTransaction')->twice();
        DB::shouldReceive('rollback')->twice();
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $response = (new TenderForVendorController())->storeTenderVendor(new Request());
            $this->assertSame(422, $response->status());
            $this->assertStringContainsString(__('supporting.Tender Name'), $response->getData(true)['meta']['message']['errors']['create_tender_name'][0]);
        }
    }

    public function test_eula_messages_change_language_without_translating_business_content(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        \Illuminate\Support\Facades\Schema::create('end_user_license_agreements', function ($table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name');
            $table->text('content');
            $table->integer('year');
            $table->boolean('is_active');
            $table->timestamps();
        });
        $this->actingAs((new User())->forceFill(['id' => 1, 'name' => 'Reviewer']));
        $controller = new EndUserLicenseAgreementController();
        $content = '<p>Ketentuan bisnis asli — unchanged contractual content.</p>';
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $response = $controller->submitEula(new Request([
                'data_eula' => $content, 'data_eula_name' => 'Original Agreement',
                'data_eula_year' => 2026, 'eula_is_active' => 1,
            ]))->getData(true);
            $this->assertSame(__('supporting.Agreement saved successfully'), $response['meta']['message']);
            $this->assertSame($content, $response['data']['content']);
            $this->assertSame('Original Agreement', $response['data']['name']);
            $this->assertSame(__('supporting.Agreement fetched successfully'), $controller->fetchEula()->getData(true)['meta']['message']);
        }
    }

    public function test_pages_and_inline_scripts_render_without_missing_keys(): void
    {
        $connection = new class(null) extends \Illuminate\Database\Connection {
            public function select($query, $bindings = [], $useReadPdo = true) { return []; }
        };
        $original = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
        $resolver = new \Illuminate\Database\ConnectionResolver(['testing' => $connection]);
        $resolver->setDefaultConnection('testing');
        \Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);
        $this->beforeApplicationDestroyed(fn () => \Illuminate\Database\Eloquent\Model::setConnectionResolver($original));
        $this->app->instance(MenuService::class, Mockery::mock(MenuService::class, function ($mock) {
            $mock->shouldReceive('getAccessibleMenus')->andReturn(collect());
        }));
        $this->actingAs((new User())->forceFill(['id' => 1, 'name' => 'Reviewer', 'email' => 'reviewer@example.test']));
        view()->share('errors', new \Illuminate\Support\ViewErrorBag());
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            foreach (['home', 'cs_vendor.attachment', 'company.detail_company', 'admin.eula.index', 'admin.tenders.vendor.index'] as $view) {
                $html = view($view)->render();
                $this->assertDoesNotMatchRegularExpression('/\b(?:supporting|supporting_validation|public_form|settings)\.[A-Za-z]/', $html, $view);
                preg_match_all('/<script\b[^>]*>(.*?)<\/script>/s', $html, $scripts);
                foreach ($scripts[1] as $index => $script) {
                    if (trim($script) === '') continue;
                    $path = tempnam(sys_get_temp_dir(), 'task9-js-');
                    file_put_contents($path, $script);
                    exec('node --check '.escapeshellarg($path).' 2>&1', $output, $status);
                    unlink($path);
                    $this->assertSame(0, $status, "{$locale}:{$view}:{$index}\n".implode("\n", $output));
                    $output = [];
                }
            }
        }
    }
}

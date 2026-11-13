<?php

namespace Tests\Feature;

use App\Http\Controllers\PublicFormController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Models\FormLink;
use App\Models\User;
use App\Services\ApprovalService;
use App\Services\CompanyAttachmentService;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class PublicAuthLocalizationTest extends TestCase
{
    public function test_locale_keys_and_all_placeholder_types_match(): void
    {
        foreach (['public_form', 'public_validation', 'auth', 'passwords'] as $group) {
            $id = Arr::dot(require resource_path("lang/id/{$group}.php"));
            $en = Arr::dot(require resource_path("lang/en/{$group}.php"));
            $this->assertEqualsCanonicalizing(array_keys($id), array_keys($en), $group);
            foreach ($id as $key => $text) {
                preg_match_all('/:[a-zA-Z_]+|\{[a-zA-Z_]+\}/', $text, $idTokens);
                preg_match_all('/:[a-zA-Z_]+|\{[a-zA-Z_]+\}/', $en[$key], $enTokens);
                $this->assertEqualsCanonicalizing($idTokens[0], $enTokens[0], "{$group}.{$key}");
            }
        }
    }

    public function test_public_and_internal_backend_errors_use_one_language(): void
    {
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $method = new \ReflectionMethod(PublicFormController::class, 'validateSubmission');
            $method->setAccessible(true);
            try {
                $method->invoke(new PublicFormController(new ApprovalService()), new Request(), 'customer');
                $this->fail('Empty submission must fail validation.');
            } catch (ValidationException $e) {
                $this->assertSame(__('public_validation.custom')['company_name.required'], $e->errors()['company_name'][0]);
                $this->assertStringNotContainsString(' | ', implode(' ', Arr::flatten($e->errors())));
                $this->assertStringNotContainsString('validation.', implode(' ', Arr::flatten($e->errors())));
            }
            $method = new \ReflectionMethod(PartnerController::class, 'validateRequest');
            $method->setAccessible(true);
            $validator = $method->invoke(new PartnerController(), new Request());
            $this->assertSame(__('public_validation.internal')['company_name.required'], $validator->errors()->first('company_name'));
            try {
                (new CompanyAttachmentService())->validate(UploadedFile::fake()->createWithContent('invalid.pdf', 'invalid file contents'));
                $this->fail('Invalid attachment must be rejected.');
            } catch (ValidationException $e) {
                $this->assertSame(__('public_form.Isi file harus berupa PDF, JPEG, atau PNG yang valid.'), $e->errors()['input-multiple-file'][0]);
            }
            $this->assertStringNotContainsString(' | ', (new \App\Rules\PublicHttpsUrl())->message());
            $this->assertStringNotContainsString(' | ', (new \App\Rules\ReachablePublicHttpsUrl())->message());
        }
    }

    public function test_auth_field_validation_and_broker_messages_follow_locale(): void
    {
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            foreach ([[new LoginController(), 'validateLogin', 'password'], [new ForgotPasswordController(), 'validateEmail', 'email']] as [$controller, $name, $field]) {
                $method = new \ReflectionMethod($controller, $name);
                $method->setAccessible(true);
                try {
                    $method->invoke($controller, new Request());
                    $this->fail('Empty credentials must fail.');
                } catch (ValidationException $e) {
                    $this->assertSame(__('public_validation.auth')[$field.'.required'], $e->errors()[$field][0]);
                }
            }
            $controller = new RegisterController();
            $method = new \ReflectionMethod($controller, 'validator');
            $method->setAccessible(true);
            $validator = $method->invoke($controller, []);
            $this->assertSame(__('public_form.Kata sandi tidak boleh kosong'), $validator->errors()->first('password'));
            foreach ([new ResetPasswordController(), new ConfirmPasswordController()] as $controller) {
                $rules = new \ReflectionMethod($controller, 'rules');
                $rules->setAccessible(true);
                $messages = new \ReflectionMethod($controller, 'validationErrorMessages');
                $messages->setAccessible(true);
                $validator = \Illuminate\Support\Facades\Validator::make([], $rules->invoke($controller), $messages->invoke($controller));
                $this->assertSame(__('public_validation.auth')['password.required'], $validator->errors()->first('password'));
            }
            $this->assertNotSame('auth.failed', __('auth.failed'));
            $this->assertNotSame('passwords.token', __('passwords.token'));
            $this->assertSame($locale === 'id' ? 'Pemberitahuan Reset Kata Sandi' : 'Reset Password Notification', __('Reset Password Notification'));
        }
    }

    public function test_public_json_validation_envelope_follows_locale(): void
    {
        $route = new \Illuminate\Routing\Route('POST', 'f/{token}', fn () => null);
        $route->name('public.form.submit');
        $request = Request::create('/f/test-token', 'POST');
        $request->setRouteResolver(fn () => $route);
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $validator = \Illuminate\Support\Facades\Validator::make([], ['company_name' => 'required'], __('public_validation.messages'), __('public_validation.attributes'));
            $exception = new ValidationException($validator);
            $handler = app(\App\Exceptions\Handler::class);
            $method = new \ReflectionMethod($handler, 'invalidJson');
            $method->setAccessible(true);
            $response = $method->invoke($handler, $request, $exception);
            $this->assertSame(__('messages.Validation Error'), $response->getData(true)['message']);
            $this->assertSame(422, $response->getStatusCode());
        }
    }

    public function test_public_internal_and_auth_pages_render_in_both_languages(): void
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
        $link = (new FormLink())->forceFill(['id' => 1, 'token' => 'test-token', 'title' => 'Example Form', 'form_type' => 'customer']);
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            foreach (['public.form_index', 'public.form_success', 'public.form_closed', 'cs_vendor.index', 'auth.login', 'auth.verify'] as $view) {
                $html = view($view, ['formLink' => $link, 'message' => __('messages.Public Form Inactive'), 'token' => 'reset-token', 'email' => 'reviewer@example.test'])->render();
                $this->assertDoesNotMatchRegularExpression('/\b(?:public_form|public_validation)\.[A-Za-z]/', $html, $view);
                if ($view === 'auth.login') $this->assertStringContainsString(__('public_form.Sign In'), $html);
                if ($view === 'public.form_index') {
                    $this->assertStringContainsString(__('public_form.fileinput.msgNoFilesSelected'), $html);
                    $this->assertStringContainsString(__('messages.Public Hide Survey'), $html);
                }
            }
        }
    }
}

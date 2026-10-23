<?php

namespace Tests\Unit;

use App\Http\Controllers\PublicFormController;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mockery;
use ReflectionMethod;
use Tests\TestCase;

class PublicFormBusinessClassificationOtherTest extends TestCase
{
    /** @test */
    public function other_business_classification_requires_a_description(): void
    {
        try {
            $this->validateSubmission([
                'business_classification' => 'Other',
                'business_classification_other_detail' => '',
            ]);
            $this->fail('The Other business classification description should be required.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('business_classification_other_detail', $exception->errors());
        }
    }

    /** @test */
    public function other_business_classification_markup_supports_inline_validation(): void
    {
        $form = file_get_contents(resource_path('views/cs_vendor/form/master_information.blade.php'));
        $script = file_get_contents(resource_path('views/public/form/js.blade.php'));

        $this->assertStringContainsString('name="business_classification_other_detail"', $form);
        $this->assertStringContainsString('id="message_business_classification_other_detail"', $form);
        $this->assertStringNotContainsString('placeholder="Other"', $form);
        $this->assertStringContainsString("businessClassification === 'Other'", $script);
        $this->assertStringContainsString(".prop('required', isOther)", $script);
        $this->assertStringContainsString("'other_business' => \$validated['business_classification_other_detail'] ?? null", file_get_contents(app_path('Http/Controllers/PublicFormController.php')));
    }

    private function validateSubmission(array $overrides): array
    {
        $request = Request::create('/', 'POST', array_replace([
            'idempotency_key' => '11111111-1111-4111-8111-111111111111',
            'company_type' => 'vendor',
            'company_name' => 'Pralon',
            'liable_person' => ['Budi'],
            'liable_position' => ['Director'],
            'nik' => ['1234567890123456'],
            'business_classification' => 'Supplier',
            'register_number_as_in_tax_invoice' => '123',
            'contact_department' => ['Sales'],
            'contact_position' => ['Manager'],
            'contact_name' => ['Tasya'],
            'contact_email' => ['a@example.com'],
            'contact_telephone' => ['+6512345678'],
            'address_role' => ['legal'],
            'address' => ['One Main Street'],
            'country_code' => ['SG'],
            'country' => ['Singapore'],
            'province' => ['Central Region'],
            'city' => ['Singapore'],
            'zip_code' => ['018956'],
            'latitude' => [null],
            'longitude' => [null],
            'telephone' => ['+6512345678'],
            'fax' => ['+6512345678'],
            'bank_name' => ['Bank ABC'],
            'account_name' => ['Tasya'],
            'account_number' => ['123456'],
        ], $overrides));

        $controller = new PublicFormController(Mockery::mock(ApprovalService::class));
        $method = new ReflectionMethod($controller, 'validateSubmission');
        $method->setAccessible(true);

        return $method->invoke($controller, $request, 'vendor');
    }
}

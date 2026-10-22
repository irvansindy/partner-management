<?php

namespace Tests\Feature\PublicForm;

use App\Http\Controllers\PublicFormController;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mockery;
use ReflectionMethod;
use Tests\TestCase;

class PublicFormResourceLimitTest extends TestCase
{
    /** @test */
    public function repeated_form_sections_are_limited_to_ten_rows(): void
    {
        $groups = [
            ['liable_person', 'liable_position', 'nik'],
            ['contact_department', 'contact_position', 'contact_name', 'contact_email', 'contact_telephone'],
            ['address_role', 'address', 'country_code', 'country', 'province', 'city', 'zip_code', 'latitude', 'longitude', 'telephone', 'fax'],
            ['bank_name', 'account_name', 'account_number'],
        ];

        foreach ($groups as $fields) {
            $payload = $this->validPayload();
            foreach ($fields as $field) {
                $payload[$field] = array_fill(0, 11, $payload[$field][0]);
            }
            if (in_array('address_role', $fields, true)) {
                $payload['address_role'] = array_merge(['legal'], array_fill(0, 10, 'branch'));
            }

            try {
                $this->validate($payload);
                $this->fail($fields[0].' should reject more than ten rows.');
            } catch (ValidationException $exception) {
                $this->assertArrayHasKey($fields[0], $exception->errors());
            }
        }
    }

    /** @test */
    public function long_text_and_numeric_limits_are_enforced(): void
    {
        foreach ([
            ['address', [str_repeat('a', 1001)]],
            ['business_classification_detail', str_repeat('a', 1001)],
            ['total_employee', 1000001],
        ] as [$field, $value]) {
            $payload = $this->validPayload();
            $payload[$field] = $value;

            try {
                $this->validate($payload);
                $this->fail($field.' should enforce its maximum.');
            } catch (ValidationException $exception) {
                $this->assertNotEmpty($exception->errors());
            }
        }
    }

    private function validate(array $payload): array
    {
        $controller = new PublicFormController(Mockery::mock(ApprovalService::class));
        $method = new ReflectionMethod($controller, 'validateSubmission');
        $method->setAccessible(true);

        return $method->invoke($controller, Request::create('/', 'POST', $payload), 'vendor');
    }

    private function validPayload(): array
    {
        return [
            'idempotency_key'=>'11111111-1111-4111-8111-111111111111',
            'company_type'=>'vendor','company_name'=>'Pralon','established_year'=>'2000',
            'liable_person'=>['Budi'],'liable_position'=>['Director'],'nik'=>['1234567890123456'],
            'business_classification'=>'Supplier','register_number_as_in_tax_invoice'=>'123',
            'contact_department'=>['Sales'],'contact_position'=>['Manager'],'contact_name'=>['Tasya'],
            'contact_email'=>['a@example.com'],'contact_telephone'=>['+6512345678'],
            'address_role'=>['legal'],'address'=>['One Main Street'],'country_code'=>['SG'],
            'country'=>['Singapore'],'province'=>['Central Region'],'city'=>['Singapore'],'zip_code'=>['018956'],
            'latitude'=>[null],'longitude'=>[null],'telephone'=>['+6512345678'],'fax'=>['+6512345678'],
            'bank_name'=>['Bank ABC 2'],'account_name'=>['Tasya'],'account_number'=>['123456'],
        ];
    }
}

<?php

namespace Tests\Unit;

use App\Http\Controllers\PublicFormController;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Mockery;
use ReflectionMethod;
use Tests\TestCase;

class PublicFormPhoneValidationTest extends TestCase
{
    /** @test */
    public function it_accepts_country_code_with_up_to_fourteen_local_digits(): void
    {
        $validated = $this->validateSubmission([
            'contact_telephone' => ['+62289654356786543'],
            'telephone' => ['+6287654567898658'],
            'fax' => ['+6289876543456764'],
        ]);

        $this->assertSame('+62289654356786543', $validated['contact_telephone'][0]);
        $this->assertSame('+6287654567898658', $validated['telephone'][0]);
        $this->assertSame('+6289876543456764', $validated['fax'][0]);
    }

    private function validateSubmission(array $phones): array
    {
        $request = Request::create('/', 'POST', array_merge([
            'company_type' => 'vendor',
            'company_name' => 'Pralon',
            'liable_person' => ['Person'],
            'liable_position' => ['Director'],
            'nik' => ['1234567890123456'],
            'business_classification' => 'Supplier',
            'register_number_as_in_tax_invoice' => '123',
            'contact_department' => ['ICT'],
            'contact_position' => ['Assistant Manager'],
            'contact_name' => ['Tasya'],
            'contact_email' => ['tasya@example.com'],
            'address' => ['Address'],
            'address_role' => ['legal'],
            'country_code' => ['SG'],
            'country' => ['Singapore'],
            'province' => ['Central Region'],
            'city' => ['Singapore'],
            'zip_code' => ['018956'],
            'latitude' => [null],
            'longitude' => [null],
            'bank_name' => ['OCBC'],
            'account_name' => ['Tasya'],
            'account_number' => ['9876456654675765'],
        ], $phones));

        $controller = new PublicFormController(Mockery::mock(ApprovalService::class));
        $method = new ReflectionMethod($controller, 'validateSubmission');
        $method->setAccessible(true);

        return $method->invoke($controller, $request, 'vendor');
    }
}

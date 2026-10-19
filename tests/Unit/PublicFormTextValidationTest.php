<?php

namespace Tests\Unit;

use App\Http\Controllers\PublicFormController;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mockery;
use ReflectionMethod;
use Tests\TestCase;

class PublicFormTextValidationTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidTextProvider
     */
    public function it_rejects_years_outside_the_allowed_range_and_non_alphabetic_names(
        string $field,
        $value
    ): void {
        try {
            $this->validateSubmission([$field => $value]);
            $this->fail("Validation should reject {$field}.");
        } catch (ValidationException $exception) {
            $errors = $exception->errors();
            $this->assertTrue(
                array_key_exists($field, $errors) || array_key_exists($field.'.0', $errors),
                "Validation errors should contain {$field}."
            );
        }
    }

    public function invalidTextProvider(): array
    {
        return [
            'year before 1900' => ['established_year', '1899'],
            'future year' => ['established_year', (string) (date('Y') + 1)],
            'company name with symbol' => ['company_name', 'PT. Pralon'],
            'company group with number' => ['company_group_name', 'Pralon 2'],
            'liable person with symbol' => ['liable_person', ['Budi@Santoso']],
            'department with number' => ['contact_department', ['ICT 2']],
            'position with symbol' => ['contact_position', ['Manager!']],
            'contact name with number' => ['contact_name', ['Tasya 2']],
            'bank name with unsafe symbol' => ['bank_name', ['Bank <ABC>']],
            'account name with number' => ['account_name', ['Tasya 2']],
        ];
    }

    /** @test */
    public function it_accepts_international_bank_names_with_common_numbers_and_symbols(): void
    {
        foreach (["Bank 24/7", "S&P Global Bank", "O'Bank (Europe), N.A.", 'Bank A+B-C'] as $bankName) {
            $validated = $this->validateSubmission(['bank_name' => [$bankName]]);
            $this->assertSame($bankName, $validated['bank_name'][0]);
        }
    }

    private function validateSubmission(array $overrides): array
    {
        $request = Request::create('/', 'POST', array_replace([
            'company_type' => 'vendor',
            'company_name' => 'PT Pralon',
            'company_group_name' => 'Pralon Group',
            'established_year' => '2000',
            'liable_person' => ['Budi Santoso'],
            'liable_position' => ['Director'],
            'nik' => ['1234567890123456'],
            'business_classification' => 'Supplier',
            'register_number_as_in_tax_invoice' => '123',
            'contact_department' => ['Information Technology'],
            'contact_position' => ['Assistant Manager'],
            'contact_name' => ['Tasya'],
            'contact_email' => ['tasya@example.com'],
            'contact_telephone' => ['+628123456789'],
            'address' => ['Address'],
            'country' => ['Indonesia'],
            'province' => ['1'],
            'city' => ['1'],
            'zip_code' => ['12122'],
            'telephone' => ['+628123456789'],
            'fax' => ['+628123456789'],
            'latitude' => '-6.2244004',
            'longitude' => '106.6530741',
            'bank_name' => ['Bank Central Asia'],
            'account_name' => ['Tasya'],
            'account_number' => ['9876456654675765'],
        ], $overrides));

        $controller = new PublicFormController(Mockery::mock(ApprovalService::class));
        $method = new ReflectionMethod($controller, 'validateSubmission');
        $method->setAccessible(true);

        return $method->invoke($controller, $request, 'vendor');
    }
}

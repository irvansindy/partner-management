<?php

namespace Tests\Unit;

use App\Support\EmployeeRange;
use Tests\TestCase;

class PublicFormEmployeeRangeTest extends TestCase
{
    /** @test */
    public function it_exposes_the_supported_employee_ranges_in_order(): void
    {
        $this->assertSame([
            '1-100',
            '101-500',
            '501-1000',
            '1001-5000',
            '5001-10000',
            '10000+',
        ], EmployeeRange::values());
    }

    /** @test */
    public function the_partner_form_uses_a_select2_range_control_and_server_allowlists_it(): void
    {
        $view = file_get_contents(resource_path('views/cs_vendor/form/master_information.blade.php'));
        $script = file_get_contents(resource_path('views/cs_vendor/partner_js.blade.php'));
        $publicController = file_get_contents(app_path('Http/Controllers/PublicFormController.php'));
        $partnerController = file_get_contents(app_path('Http/Controllers/PartnerController.php'));

        $this->assertStringContainsString('<select name="total_employee"', $view);
        $this->assertStringContainsString('@foreach (\App\Support\EmployeeRange::values() as $employeeRange)', $view);
        $this->assertStringContainsString("$('#total_employee').select2", $script);
        $this->assertStringContainsString("Rule::in(EmployeeRange::values())", $publicController);
        $this->assertStringContainsString("Rule::in(EmployeeRange::values())", $partnerController);
    }

    /** @test */
    public function internal_partner_validation_finishes_before_the_write_transaction_starts(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/PartnerController.php'));
        $storeMethod = strstr($controller, 'public function store(Request $request)');
        $storeMethod = strstr($storeMethod, 'private function checkIfUserAlreadyRegistered', true);

        $this->assertLessThan(
            strpos($storeMethod, 'DB::beginTransaction()'),
            strpos($storeMethod, '$this->validateRequest($request)')
        );
    }
}

<?php

namespace Tests\Unit;

use App\Models\MasterDepartment;
use App\Models\MasterDivision;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class ApprovalFinancePolicyTest extends TestCase
{
    /** @test */
    public function fat_finance_membership_requires_both_matching_relations(): void
    {
        $fatFinance = new User();
        $fatDivision = (new MasterDivision())->setRawAttributes(['name' => ' FAT ']);
        $financeDepartment = (new MasterDepartment())->setRawAttributes(['name' => 'finance']);
        $fatFinance->setRelation('division', $fatDivision);
        $fatFinance->setRelation('dept', $financeDepartment);

        $wrongDepartment = new User();
        $wrongDepartment->setRelation('division', (new MasterDivision())->setRawAttributes(['name' => 'FAT']));
        $wrongDepartment->setRelation('dept', (new MasterDepartment())->setRawAttributes(['name' => 'ICT']));

        $this->assertTrue($fatFinance->isFatFinanceApprover());
        $this->assertFalse($wrongDepartment->isFatFinanceApprover());
    }

    /** @test */
    public function fat_finance_master_data_is_created_idempotently(): void
    {
        $root = dirname(__DIR__, 2);
        $migrationFiles = glob($root.'/database/migrations/*_seed_fat_finance_organization.php');
        $divisionSeeder = file_get_contents($root.'/database/seeders/MasterDivisionSeeder.php');
        $departmentSeeder = file_get_contents($root.'/database/seeders/MasterDepartements.php');

        $this->assertCount(1, $migrationFiles);
        $migration = file_get_contents($migrationFiles[0]);
        $this->assertStringContainsString("updateOrInsert(['name' => 'FAT']", $migration);
        $this->assertStringContainsString("updateOrInsert(['name' => 'Finance']", $migration);
        $this->assertStringContainsString("'division_id' => \$fatDivisionId", $migration);
        $this->assertStringContainsString('MasterDivision::updateOrCreate', $divisionSeeder);
        $this->assertStringContainsString("'name' => 'FAT'", $divisionSeeder);
        $this->assertStringContainsString("updateOrInsert(['name' => \$department['name']]", $departmentSeeder);
        $this->assertStringContainsString("['name' => 'Finance', 'division_id' => \$fatDivision->id]", $departmentSeeder);
    }

    /** @test */
    public function approval_service_enforces_customer_financial_terms_inside_the_transaction(): void
    {
        $root = dirname(__DIR__, 2);
        $service = file_get_contents($root.'/app/Services/ApprovalService.php');
        $legacyCaller = file_get_contents($root.'/app/Services/ApprovalProcessService.php');

        $this->assertStringContainsString("\$company->type === 'customer'", $service);
        $this->assertStringContainsString('$actor->isFatFinanceApprover()', $service);
        $this->assertStringContainsString('Financial terms are required for FAT Finance approval.', $service);
        $this->assertStringContainsString("\$companyUpdates['term_of_payment'] = \$normalizedTermOfPayment", $service);
        $this->assertStringContainsString("\$companyUpdates['credit_limit'] = \$normalizedCreditLimit", $service);
        $this->assertStringContainsString('Auth::id()', $legacyCaller);
    }

    /** @test */
    public function approval_action_collects_financial_terms_only_for_fat_finance_customers(): void
    {
        $root = dirname(__DIR__, 2);
        $controller = file_get_contents($root.'/app/Http/Controllers/Admin/ApprovalProcessController.php');
        $view = file_get_contents($root.'/resources/views/admin/approvals/action.blade.php');

        $this->assertStringContainsString('$requiresFinancialTerms', $controller);
        $this->assertStringContainsString('Rule::requiredIf', $controller);
        $this->assertStringContainsString("'term_of_payment'", $controller);
        $this->assertStringContainsString("'credit_limit'", $controller);
        $this->assertStringContainsString('@if($requiresFinancialTerms)', $view);
        $this->assertStringContainsString('name="term_of_payment"', $view);
        $this->assertStringContainsString('name="credit_limit"', $view);
        $this->assertStringContainsString('Nilai ini akan menggantikan data customer', $view);
        $this->assertStringContainsString("\$financialFields.prop('required', requiresFinancialTerms && action === 'approve')", $view);
    }

    /** @test */
    public function an_assigned_cross_department_approver_can_access_the_process(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/Admin/ApprovalProcessController.php');

        $this->assertStringContainsString("steps()->where('user_id', \$user->id)->exists()", $controller);
    }
}

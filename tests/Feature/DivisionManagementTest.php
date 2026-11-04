<?php

namespace Tests\Feature;

use App\Http\Controllers\DivisionManagementController;
use App\Models\MasterDivision;
use App\Services\DivisionService;
use Mockery;
use Tests\TestCase;

class DivisionManagementTest extends TestCase
{
    public function test_division_crud_routes_target_the_division_controller(): void
    {
        $routes = app('router')->getRoutes();
        $expectedActions = [
            'fetch-division' => 'fetchDivision',
            'division.store' => 'store',
            'division.update' => 'update',
            'division.destroy' => 'destroy',
        ];

        foreach ($expectedActions as $routeName => $method) {
            $route = $routes->getByName($routeName);

            $this->assertNotNull($route, $routeName . ' route should exist');
            $this->assertSame(
                DivisionManagementController::class . '@' . $method,
                $route->getActionName(),
                $routeName . ' should target the correct controller action'
            );
        }
    }

    public function test_division_cannot_be_deleted_while_departments_are_assigned(): void
    {
        $departments = Mockery::mock();
        $departments->shouldReceive('exists')->once()->andReturnTrue();

        $division = Mockery::mock(MasterDivision::class)->makePartial();
        $division->shouldReceive('departments')->once()->andReturn($departments);

        $this->assertFalse((new DivisionService())->delete($division));
    }
}

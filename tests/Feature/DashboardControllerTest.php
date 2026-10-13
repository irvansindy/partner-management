<?php

namespace Tests\Feature;

use App\Http\Controllers\DashboardController;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Mockery;
use ReflectionProperty;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    public function test_dashboard_cache_key_isolated_by_user_and_access_scope(): void
    {
        $cacheKeys = [];

        Cache::shouldReceive('remember')
            ->times(9)
            ->andReturnUsing(function ($key, $expiration, $callback) use (&$cacheKeys) {
                $cacheKeys[] = $key;

                return collect();
            });

        foreach ([
            $this->dashboardUser(41, ['super-user'], 5, 2),
            $this->dashboardUser(42, ['user'], 5, 2),
            $this->dashboardUser(41, ['user'], 8, 9),
        ] as $user) {
            $controller = $this->dashboardControllerFor($user);
            $controller->fetchDataCount();
            $controller->fetchRecentCustomer();
            $controller->fetchRecentVendor();
        }

        $this->assertCount(9, array_unique($cacheKeys));
    }

    public function test_map_point_route_targets_existing_controller_method(): void
    {
        $route = app('router')->getRoutes()->getByName('fetch-map-point');

        $this->assertNotNull($route);
        $this->assertSame(DashboardController::class . '@mapPoint', $route->getActionName());
    }

    public function test_province_distribution_returns_allowed_series_for_each_role_scope(): void
    {
        $controller = new class extends DashboardController {
            public function formatDistribution($provinces, $vendorTotals, $customerTotals, $isSuper, $isAdminOrSuperUser, $isSales, $isPurchasing): array
            {
                return $this->formatProvinceDistribution(
                    $provinces,
                    $vendorTotals,
                    $customerTotals,
                    $isSuper,
                    $isAdminOrSuperUser,
                    $isSales,
                    $isPurchasing
                );
            }
        };

        $provinces = collect([(object) ['name' => 'Banten']]);
        $vendorTotals = collect(['Banten' => 3]);
        $customerTotals = collect(['Banten' => 5]);
        $scopes = [
            ['super-admin', true, false, false, false, ['vendor', 'customer']],
            ['super-user', true, true, true, true, ['vendor', 'customer']],
            ['sales admin', false, true, true, false, ['customer']],
            ['purchasing admin', false, true, false, true, ['vendor']],
            ['other department admin', false, true, false, false, ['vendor', 'customer']],
            ['regular user', false, false, false, false, ['vendor', 'customer']],
        ];

        foreach ($scopes as [$name, $isSuper, $isAdminOrSuperUser, $isSales, $isPurchasing, $expectedKeys]) {
            $distribution = $controller->formatDistribution(
                $provinces,
                $vendorTotals,
                $customerTotals,
                $isSuper,
                $isAdminOrSuperUser,
                $isSales,
                $isPurchasing
            );

            $this->assertSame($expectedKeys, array_keys($distribution), $name);
            if (isset($distribution['vendor'])) {
                $this->assertSame(3, $distribution['vendor']->first()['total'], $name);
            }
            if (isset($distribution['customer'])) {
                $this->assertSame(5, $distribution['customer']->first()['total'], $name);
            }
        }
    }

    private function dashboardUser($id, array $roles, $departmentId, $officeId): User
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->setAttribute('id', $id);
        $user->setAttribute('department_id', $departmentId);
        $user->setAttribute('office_id', $officeId);
        $user->shouldReceive('getRoleNames')->times(3)->andReturn(collect($roles));

        return $user;
    }

    private function dashboardControllerFor(User $user): DashboardController
    {
        $controller = new DashboardController();

        $userProperty = new ReflectionProperty(DashboardController::class, 'user');
        $userProperty->setAccessible(true);
        $userProperty->setValue($controller, $user);

        $superAdminProperty = new ReflectionProperty(DashboardController::class, 'isSuperAdmin');
        $superAdminProperty->setAccessible(true);
        $superAdminProperty->setValue($controller, false);

        return $controller;
    }
}

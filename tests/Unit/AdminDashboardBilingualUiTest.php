<?php

namespace Tests\Unit;

use Tests\TestCase;

class AdminDashboardBilingualUiTest extends TestCase
{
    /** @test */
    public function live_dashboard_uses_complete_indonesian_and_english_copy(): void
    {
        $view = file_get_contents(resource_path('views/admin/dashboard/index_live.blade.php'));
        $script = file_get_contents(resource_path('views/admin/dashboard/live_js.blade.php'));
        $indonesian = require resource_path('lang/id/messages.php');
        $english = require resource_path('lang/en/messages.php');

        $keys = [
            'Dashboard Title',
            'Dashboard Subtitle',
            'Dashboard Approved Partners',
            'Dashboard Approved Customers',
            'Dashboard Approved Vendors',
            'Dashboard Pending Approvals',
            'Dashboard Partners By Province',
            'Dashboard Approved Partner Types',
            'Dashboard Recent Customers',
            'Dashboard Recent Vendors',
            'Dashboard Partner Locations',
            'Dashboard Distribution In Province',
            'Dashboard Summary Load Failed',
            'Dashboard Table Search',
            'Dashboard Table Previous',
            'Dashboard Table Next',
        ];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $indonesian);
            $this->assertArrayHasKey($key, $english);
            $this->assertNotSame($indonesian[$key], $english[$key]);
        }

        $this->assertStringContainsString("__('messages.Dashboard Title')", $view);
        $this->assertStringContainsString("@json(__('messages.Dashboard Customer'))", $script);
        $this->assertStringContainsString('dashboardText.', $script);

        foreach ([
            'Partner Dashboard',
            'Approved Partners by Province',
            'Recent Customers',
            'Partner Locations',
        ] as $literal) {
            $this->assertStringNotContainsString('>'.$literal.'<', $view);
        }

        foreach ([
            "'No partner data'",
            "'Unable to load city distribution.'",
            "'No recent partners found'",
            "'Loading partners...'",
            "'Dashboard summary could not be loaded. Refresh the page to try again.'",
        ] as $literal) {
            $this->assertStringNotContainsString($literal, $script);
        }
    }
}

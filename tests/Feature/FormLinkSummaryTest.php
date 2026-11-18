<?php

namespace Tests\Feature;

use App\Models\{FormLink, User};
use App\Services\MenuService;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FormLinkSummaryTest extends TestCase
{
    public function test_summary_counts_match_visible_form_statuses_without_double_counting_expired_forms(): void
    {
        $this->mock(MenuService::class, fn ($mock) => $mock->shouldReceive('getAccessibleMenus')->andReturn(collect()));
        $user = (new User())->forceFill(['id' => 1, 'name' => 'Test', 'email' => 'test@example.test']);
        $user->setRelation('roles', collect([(new Role())->forceFill(['name' => 'super-user'])]));
        $this->actingAs($user);
        view()->share('errors', new \Illuminate\Support\ViewErrorBag());
        $links = collect([[true, null], [false, null], [true, now()->subDay()], [false, now()->subDay()]])
            ->map(function ($state, $index) {
                $link = (new FormLink())->forceFill([
                    'id' => $index + 1, 'title' => 'Test form', 'token' => 'test-token', 'form_type' => 'vendor',
                    'is_active' => $state[0], 'expires_at' => $state[1], 'created_at' => now(), 'submission_count' => 0,
                ]);
                return $link->setRelation('creator', null);
            });
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            foreach ([[$links, ['4', '1', '1', '2']], [collect(), ['0', '0', '0', '0']]] as [$visibleLinks, $expected]) {
                $html = view('admin.form_links.index', ['formLinks' => $visibleLinks])->render();
                $document = new \DOMDocument();
                @$document->loadHTML('<?xml encoding="utf-8" ?>'.$html);
                $nodes = (new \DOMXPath($document))->query('//div[contains(concat(" ", normalize-space(@class), " "), " form-link-stats ")]//h3');
                $actual = [];
                foreach ($nodes as $node) $actual[] = trim($node->textContent);
                $this->assertSame($expected, $actual);
                $this->assertStringContainsString(__('form_links.summary_total'), $html);
            }
        }
    }
}

<?php

namespace Tests\Feature;

use App\Models\{ApprovalProcess, ApprovalProcessStep, User};
use Tests\TestCase;

class ApprovalTimelineGroupingTest extends TestCase
{
    public function test_approvers_at_the_same_stage_share_one_timeline_row(): void
    {
        $user = (new User())->forceFill(['id' => 2, 'name' => 'Approver 2', 'email' => 'second@example.test']);
        $this->actingAs($user);
        $steps = collect([[1, 1, 2], [2, 2, 1], [3, 3, 0], [4, 3, 0]])->map(function ($data) {
            [$id, $stage, $status] = $data;
            return (new ApprovalProcessStep())->forceFill([
                'id' => $id, 'user_id' => $id, 'step_ordering' => $stage, 'status' => $status,
                'created_at' => now(), 'notes' => 'Notes '.$id,
            ])->setRelation('approver', (new User())->forceFill(['name' => 'Approver '.$id, 'email' => 'person'.$id.'@example.test']));
        });
        $approval = (new ApprovalProcess())->setRelation('steps', $steps->reverse());
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $html = view('admin.form_links._approval_timeline', compact('approval'))->render();
            $document = new \DOMDocument();
            @$document->loadHTML($html);
            $xpath = new \DOMXPath($document);
            $rows = $xpath->query('//div[@data-stage-order]');
            $this->assertSame(3, $rows->length);
            $this->assertSame('1', $rows->item(0)->getAttribute('data-stage-order'));
            $parallel = $xpath->query('//div[@data-stage-order="3"]//div[@class="approval-step-card"]');
            $this->assertSame(2, $parallel->length);
            $this->assertStringContainsString('Approver 3', $parallel->item(0)->textContent.$parallel->item(1)->textContent);
            $this->assertStringContainsString('Approver 4', $parallel->item(0)->textContent.$parallel->item(1)->textContent);
            $this->assertStringContainsString(route('admin.approvals.step.action', 2), $html);
            $this->assertSame(4, $xpath->query('//div[@class="approval-step-card"]')->length);
        }
    }
}

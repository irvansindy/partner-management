<?php

namespace Tests\Feature;

use App\Models\{User, FormLink, MasterOffice, MasterDepartment};
use App\Services\{MenuService, FormLinkService};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema, Route};
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FormLinkManagementRoleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('form_links', function (Blueprint $table) {
            $table->id(); $table->string('token'); $table->string('title'); $table->string('form_type');
            $table->string('description')->nullable(); $table->integer('department_id'); $table->integer('office_id');
            $table->integer('created_by'); $table->boolean('is_active')->default(true);
            $table->integer('submission_count')->default(0); $table->integer('max_submissions')->nullable();
            $table->timestamp('expires_at')->nullable(); $table->timestamps();
        });
        $this->mock(MenuService::class, fn ($mock) => $mock->shouldReceive('getAccessibleMenus')->andReturn(collect()));
    }

    private function actor(string $role): User
    {
        $user = (new User())->forceFill(['id' => 1, 'name' => 'Test', 'email' => 'test@example.test']);
        $user->setRelation('roles', collect([(new Role())->forceFill(['name' => $role, 'guard_name' => 'web'])]));
        $user->setRelation('office', (new MasterOffice())->forceFill(['id' => 1]));
        $user->setRelation('dept', (new MasterDepartment())->forceFill(['id' => 1]));
        return $user;
    }

    private function link(): FormLink
    {
        return FormLink::create(['title' => 'Original', 'form_type' => 'vendor', 'department_id' => 1, 'office_id' => 1, 'created_by' => 1, 'is_active' => true]);
    }

    public function test_other_roles_cannot_access_management_endpoints_or_change_records(): void
    {
        $link = $this->link();
        foreach (['admin', 'super-admin', 'user'] as $role) {
            $this->actingAs($this->actor($role));
            $this->get(route('admin.form-links.create'))->assertForbidden();
            $this->post(route('admin.form-links.store'), ['title' => 'New', 'form_type' => 'vendor'])->assertForbidden();
            $this->get(route('admin.form-links.edit', $link))->assertForbidden();
            $this->put(route('admin.form-links.update', $link), ['title' => 'Changed'])->assertForbidden();
            $this->post(route('admin.form-links.toggle-status', $link))->assertForbidden();
            $this->delete(route('admin.form-links.destroy', $link))->assertForbidden();
            $this->assertSame('Original', $link->fresh()->title);
            $this->assertTrue($link->fresh()->is_active);
            $this->assertSame(1, FormLink::count());
        }
    }

    public function test_super_user_can_create_edit_and_toggle_in_scope_but_cannot_edit_another_scope(): void
    {
        $this->actingAs($this->actor('super-user'));
        $this->post(route('admin.form-links.store'), ['title' => 'New', 'form_type' => 'vendor'])->assertRedirect();
        $link = FormLink::firstOrFail();
        $this->get(route('admin.form-links.edit', $link))->assertOk();
        $this->put(route('admin.form-links.update', $link), ['title' => 'Updated'])->assertRedirect();
        $this->assertSame('Updated', $link->fresh()->title);
        $this->post(route('admin.form-links.toggle-status', $link))->assertRedirect();
        $this->assertFalse($link->fresh()->is_active);
        $link->update(['office_id' => 2]);
        $this->put(route('admin.form-links.update', $link), ['title' => 'Unauthorized'])->assertForbidden();
        $this->post(route('admin.form-links.toggle-status', $link))->assertForbidden();
        $this->assertSame('Updated', $link->fresh()->title);
    }

    public function test_read_routes_do_not_receive_the_management_role_gate(): void
    {
        foreach (['index', 'show', 'submissions'] as $action) {
            $this->assertNotContains('role:super-user', Route::getRoutes()->getByName('admin.form-links.'.$action)->gatherMiddleware());
        }
    }
}

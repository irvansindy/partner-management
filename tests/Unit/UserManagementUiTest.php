<?php

namespace Tests\Unit;

use Tests\TestCase;

class UserManagementUiTest extends TestCase
{
    /** @test */
    public function user_management_ui_uses_matching_indonesian_and_english_copy(): void
    {
        $indonesian = require resource_path('lang/id/messages.php');
        $english = require resource_path('lang/en/messages.php');
        $index = file_get_contents(resource_path('views/users/index.blade.php'));
        $create = file_get_contents(resource_path('views/users/create_user.blade.php'));
        $detail = file_get_contents(resource_path('views/users/detail_user.blade.php'));
        $delete = file_get_contents(resource_path('views/users/delete_user.blade.php'));
        $script = file_get_contents(resource_path('views/users/user_js.blade.php'));

        foreach ([
            'User Management Title',
            'User Management Subtitle',
            'User Management New User',
            'User Management Create User',
            'User Management Update User',
            'User Management Delete User',
            'User Management Select Role',
            'User Management Create Success',
            'User Management Delete Success',
            'User Management Table Search',
        ] as $key) {
            $this->assertArrayHasKey($key, $indonesian);
            $this->assertArrayHasKey($key, $english);
            $this->assertNotSame($indonesian[$key], $english[$key]);
        }

        $this->assertStringContainsString("__('messages.User Management Title')", $index);
        $this->assertStringContainsString("__('messages.User Management Create User')", $create);
        $this->assertStringContainsString("__('messages.User Management Update User')", $detail);
        $this->assertStringContainsString("__('messages.User Management Delete User')", $delete);
        $this->assertStringContainsString("@json(__('messages.User Management Select Role'))", $script);
        $this->assertStringContainsString('userText.', $script);

        $this->assertStringNotContainsString('>User Management<', $index);
        $this->assertStringNotContainsString('>Create User<', $create);
        $this->assertStringNotContainsString('>Update User<', $detail);
        $this->assertStringNotContainsString('>Delete User<', $delete);
        $this->assertStringNotContainsString("'User berhasil dibuat.'", $script);
        $this->assertStringNotContainsString("'Gagal mengambil detail user. Periksa koneksi server.'", $script);
    }

    /** @test */
    public function successful_update_and_delete_use_the_same_deterministic_modal_close_helper(): void
    {
        $script = file_get_contents(resource_path('views/users/user_js.blade.php'));

        $this->assertStringContainsString("closeUserModal('#formUpdateUser')", $script);
        $this->assertStringContainsString("closeUserModal('#confirmDeleteUser')", $script);
        $this->assertStringContainsString(".one('hidden.bs.modal'", $script);
        $this->assertStringNotContainsString("$('#confirmDeleteUser').modal('toggle')", $script);
    }

    /** @test */
    public function user_forms_expose_consistent_modal_and_field_structure(): void
    {
        foreach (['create_user.blade.php', 'detail_user.blade.php'] as $view) {
            $contents = file_get_contents(resource_path('views/users/'.$view));

            $this->assertStringContainsString('user-management-modal', $contents);
            $this->assertStringContainsString('user-form-modal', $contents);
            $this->assertStringContainsString('user-form-grid', $contents);
            $this->assertStringContainsString('user-field-error', $contents);
        }

        $index = file_get_contents(resource_path('views/users/index.blade.php'));
        $this->assertStringContainsString('.user-management-modal .form-control', $index);
        $this->assertStringContainsString('.user-management-modal .select2-container', $index);
        $this->assertStringContainsString('.user-form-modal .modal-content > form', $index);
        $this->assertStringContainsString('height: calc(100vh - 32px)', $index);
        $this->assertStringContainsString('overflow-y: scroll', $index);
    }

    /** @test */
    public function update_persists_the_identity_fields_exposed_by_the_form(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/UserManagementController.php'));

        $this->assertStringContainsString('\'nik\' => $request->nik', $controller);
        $this->assertStringContainsString('\'employee_id\' => $request->employee_id', $controller);
        $this->assertStringContainsString("Rule::unique('users', 'email')->ignore(".'$request->id'.')', $controller);
    }

    /** @test */
    public function delete_does_not_depend_on_a_role_name_sent_by_the_browser(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/UserManagementController.php'));
        $deleteMethod = strstr($controller, 'public function deleteUser');

        $this->assertIsString($deleteMethod);
        $this->assertStringContainsString('$user->syncRoles([])', $deleteMethod);
        $this->assertStringNotContainsString('$user->removeRole($request->current_role)', $deleteMethod);
    }

    /** @test */
    public function user_table_actions_use_the_same_icon_pattern_as_generate_form(): void
    {
        $script = file_get_contents(resource_path('views/users/user_js.blade.php'));

        $this->assertStringContainsString("class: 'table-action table-action--view detail_user'", $script);
        $this->assertStringContainsString("class: 'table-action table-action--delete delete_user'", $script);
        $this->assertStringContainsString("html: '<i class=\"fas fa-eye\" aria-hidden=\"true\"></i>'", $script);
        $this->assertStringContainsString("html: '<i class=\"fas fa-trash\" aria-hidden=\"true\"></i>'", $script);
        $this->assertStringNotContainsString("text: 'View'", $script);
        $this->assertStringNotContainsString("text: 'Delete'", $script);
    }
}

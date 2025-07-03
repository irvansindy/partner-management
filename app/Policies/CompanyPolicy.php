<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function viewCustomer(User $user): bool
    {
        return $user->hasRole('super-admin') || ($user->hasAnyRole(['admin', 'super-user']) && in_array($user->dept->name, ['Sales Project', 'Sales Retail 1', 'Sales Retail 2']));
    }
    public function viewVendor(User $user): bool
    {
        return $user->hasRole('super-admin') ||
            ($user->hasAnyRole(['admin', 'super-user']) && $user->dept->name === 'Purchasing');
    }
    public function viewApproval(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'super-user']);
    }
}

<?php

namespace App\Policies;

use App\Models\Account;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    public function viewAny(Account $user): bool
    {
        return $user->can('platform.permissions.view');
    }

    public function view(Account $user, Permission $permission): bool
    {
        return $user->can('platform.permissions.view');
    }

    public function create(Account $user): bool
    {
        return false;
    }

    public function update(Account $user, Permission $permission): bool
    {
        return false;
    }

    public function delete(Account $user, Permission $permission): bool
    {
        return false;
    }

    public function deleteAny(Account $user): bool
    {
        return false;
    }
}
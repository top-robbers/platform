<?php

namespace App\Policies;

use App\Models\Account;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function viewAny(Account $user): bool
    {
        return $user->can('platform.roles.view');
    }

    public function view(Account $user, Role $role): bool
    {
        return $user->can('platform.roles.view');
    }

    public function create(Account $user): bool
    {
        return $user->can('platform.roles.create');
    }

    public function update(Account $user, Role $role): bool
    {
        if (! $user->can('platform.roles.update')) {
            return false;
        }

        if ($role->name === 'owner' && ! $user->hasRole('owner')) {
            return false;
        }

        return true;
    }

    public function delete(Account $user, Role $role): bool
    {
        if (! $user->can('platform.roles.delete')) {
            return false;
        }

        if (in_array($role->name, ['owner', 'admin', 'moderator', 'helper', 'player'], true)) {
            return false;
        }

        return true;
    }

    public function deleteAny(Account $user): bool
    {
        return false;
    }
}
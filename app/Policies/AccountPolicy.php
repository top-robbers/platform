<?php

namespace App\Policies;

use App\Models\Account;

class AccountPolicy
{
    public function viewAny(Account $account): bool
    {
        return $account->can('platform.accounts.view');
    }

    public function view(Account $account, Account $target): bool
    {
        return $account->can('platform.accounts.view');
    }

    public function create(Account $user): bool
    {
        return $user->can('platform.accounts.create');
    }

    public function update(Account $user, Account $account): bool
    {
        if (! $user->can('platform.accounts.update')) {
            return false;
        }

        // todo
        if ($account->hasRole('owner') && ! $user->hasRole('owner')) {
            return false;
        }

        return true;
    }

    public function delete(Account $user, Account $account): bool
    {
        if ($user->is($account)) {
            return false;
        }

        if (! $user->can('platform.accounts.delete')) {
            return false;
        }

        // todo
        if ($account->hasRole('owner') && ! $user->hasRole('owner')) {
            return false;
        }

        return true;
    }

    public function deleteAny(Account $user): bool
    {
        return $user->can('platform.accounts.delete');
    }

    public function restore(Account $user, Account $account): bool
    {
        return false;
    }

    public function forceDelete(Account $user, Account $account): bool
    {
        return false;
    }
}
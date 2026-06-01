<?php

namespace App\Observers;

use App\Models\Account;

class AccountObserver
{
    public function updated(Account $account): void
    {
        if (! $account->wasChanged('active')) {
            return;
        }

        if ($account->active === true) {
            return;
        }

        $account->tokens()->delete();
    }
}
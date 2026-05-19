<?php

namespace App\Concerns;

use App\Models\Account;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    /**
     * Get the validation rules used to validate account profiles.
     *
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function profileRules(?int $accountId = null): array
    {
        return [
            'name' => $this->nameRules(),
            'email' => $this->emailRules($accountId),
        ];
    }

    /**
     * Get the validation rules used to validate account names.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate account emails.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function emailRules(?int $accountId = null): array
    {
        return [
            'required',
            'string',
            'email',
            'max:255',
            $accountId === null
                ? Rule::unique(Account::class)
                : Rule::unique(Account::class)->ignore($accountId),
        ];
    }
}

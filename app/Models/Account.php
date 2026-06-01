<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class Account extends Authenticatable implements FilamentUser
{
    use Notifiable, TwoFactorAuthenticatable, HasApiTokens, HasRoles;

    public function isActive(): bool
    {
        return $this->active === true;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        return match ($panel->getId()) {
            'admin' => $this->can('platform.access_admin_panel'),
            default => false,
        };
    }
    
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
}

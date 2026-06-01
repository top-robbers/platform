<?php

namespace App\Filament\Resources\Accounts\Schemas;

use App\Models\Account;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class AccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identity')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        DateTimePicker::make('email_verified_at')
                            ->label('Email verified at'),
                    ])
                    ->columns(2),

                Section::make('Security')
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->formatStateUsing(fn (): ?string => null)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->helperText('Leave empty to keep the current password.'),
                    ]),

                Section::make('Status')
                    ->schema([
                        Toggle::make('active')
                            ->label('Active')
                            ->default(true)
                            ->required()
                            ->helperText('Disabled accounts cannot access the platform, launcher, API, or game server.'),
                    ]),

                Section::make('Authorization')
                    ->schema([
                        Select::make('roles')
                            ->label('Roles')
                            ->relationship(
                                name: 'roles',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query): Builder => auth()->user()?->hasRole('owner')
                                    ? $query->where('guard_name', 'web')
                                    : $query->where('guard_name', 'web')->where('name', '!=', 'owner'),
                            )
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required()
                            ->disabled(fn (?Account $record): bool => $record?->is(auth()->user()))
                            ->helperText('You cannot edit your own roles. Only owners can assign the owner role.'),
                    ]),
            ]);
    }
}
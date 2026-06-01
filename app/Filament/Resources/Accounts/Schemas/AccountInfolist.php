<?php

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AccountInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identity')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Name'),

                        TextEntry::make('email')
                            ->label('Email address'),

                        IconEntry::make('active')
                            ->label('Active')
                            ->boolean(),
                    ])
                    ->columns(3),

                Section::make('Authorization')
                    ->schema([
                        TextEntry::make('roles.name')
                            ->label('Roles')
                            ->badge()
                            ->separator(', ')
                            ->placeholder('-'),
                    ]),

                Section::make('Security')
                    ->schema([
                        TextEntry::make('email_verified_at')
                            ->label('Email verified at')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('two_factor_confirmed_at')
                            ->label('2FA confirmed at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2),

                Section::make('Timestamps')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created at')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Updated at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2),
            ]);
    }
}
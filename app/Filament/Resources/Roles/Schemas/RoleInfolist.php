<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Name')
                            ->badge(),

                        TextEntry::make('guard_name')
                            ->label('Guard'),
                    ])
                    ->columns(2),

                Section::make('Permissions')
                    ->schema([
                        TextEntry::make('permissions.name')
                            ->label('Permissions')
                            ->badge()
                            ->separator(', ')
                            ->placeholder('-'),
                    ]),

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
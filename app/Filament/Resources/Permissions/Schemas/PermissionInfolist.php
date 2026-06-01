<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Permission')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Name')
                            ->badge(),

                        TextEntry::make('guard_name')
                            ->label('Guard'),
                    ])
                    ->columns(2),

                Section::make('Roles')
                    ->schema([
                        TextEntry::make('roles.name')
                            ->label('Roles')
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
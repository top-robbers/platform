<?php

namespace App\Filament\Resources\Permissions\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PermissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->separator(', ')
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
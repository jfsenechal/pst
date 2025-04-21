<?php

namespace App\Tables;

use App\Filament\Resources\ActionResource;
use App\Models\Action;
use Filament\Tables;
use Filament\Tables\Table;

class ActionTables
{
    public static function actionsInline(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->defaultPaginationPageOption(50)
            ->recordUrl(fn(Action $record) => ActionResource::getUrl('view', [$record]))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Intitulé')
                    ->limit(120)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(
                        fn(Action $record): string => ActionResource::getUrl(
                            'view',
                            ['record' => $record]
                        )
                    ),
            ]);
    }
}

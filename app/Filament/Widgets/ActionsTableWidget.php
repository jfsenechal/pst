<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ActionResource;
use App\Models\Action as ActionPst;
use App\Repository\ActionRepository;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ActionsTableWidget extends BaseWidget
{
    public function table(Table $table): Table
    {
        $user = auth()->user();

        return $table
            ->query(
                ActionRepository::findByUser($user->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make('show')
                    ->url(fn(ActionPst $record) => ActionResource::getUrl('view', ['record' => $record->id])),
            ]);
    }
}

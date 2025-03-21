<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ActionResource;
use App\Models\Action as ActionPst;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ActionsTable extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                ActionPst::query()->published()
            )
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->actions([
                Tables\Actions\Action::make('takeQuiz')
                    ->url(fn (ActionPst $record) => ActionResource::getUrl('view',['record' => $record->id]))
            ]);
    }
}

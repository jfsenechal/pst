<?php

namespace App\Tables;

use App\Filament\Resources\StrategicObjectiveResource;
use App\Models\StrategicObjective;
use App\Repository\UserRepository;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class StrategicObjectiveTables
{
    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->modifyQueryUsing(
                fn(Builder $query) => $query->where('department', '=', UserRepository::departmentSelected())
            )
            ->recordTitleAttribute('name')
            ->recordUrl(fn(StrategicObjective $record) => StrategicObjectiveResource::getUrl('view', [$record]))
            ->defaultSort('position')
            ->columns([
                Tables\Columns\TextColumn::make('position')
                    ->label('Numéro')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Intitulé')
                    ->limit(90)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('oos_count')
                    ->label('Objectifs Opérationnels (OO)')
                    ->tooltip('Objectif Opérationnel')
                    ->counts('oos'),
                Tables\Columns\TextColumn::make('department')
                    ->label('Département')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

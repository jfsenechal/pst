<?php

namespace App\Tables;

use App\Filament\Resources\OperationalObjectiveResource;
use App\Models\OperationalObjective;
use App\Repository\UserRepository;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OperationalObjectiveTables
{
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->defaultPaginationPageOption(50)
            ->modifyQueryUsing(
                fn(Builder $query) => $query->where('department', '=', UserRepository::departmentSelected())
            )
            ->recordUrl(fn(OperationalObjective $record) => OperationalObjectiveResource::getUrl('view', [$record]))
            ->columns([
                Tables\Columns\TextColumn::make('position')
                    ->label('Numéro')
                    ->state(
                        fn(OperationalObjective $objective): string => $objective->strategicObjective?->position.'.'.' '.$objective->position
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('os')
                    ->label('Os')
                    ->state(fn() => 'Os')
                    ->tooltip(function (TextColumn $column): ?string {
                        $record = $column->getRecord();

                        return $record->strategicObjective?->name;
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(90)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        return $state;
                    }),
                Tables\Columns\TextColumn::make('actions_count')
                    ->label('Actions')
                    ->counts('actions')
                    ->sortable(),
                Tables\Columns\TextColumn::make('department')
                    ->label('Département')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('tabler-edit'),
                Tables\Actions\DeleteAction::make()
                    ->icon('tabler-trash'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

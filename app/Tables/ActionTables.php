<?php

namespace App\Tables;

use App\Constant\ActionStateEnum;
use App\Constant\ActionTypeEnum;
use App\Filament\Resources\ActionResource;
use App\Form\ActionForm;
use App\Models\Action;
use App\Models\OperationalObjective;
use App\Repository\UserRepository;
use Filament\Schemas\Components\Form;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ActionTables
{
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->defaultPaginationPageOption(50)
            ->modifyQueryUsing(
                fn(Builder $query) => $query->where('department', '=', UserRepository::departmentSelected())
            )
            ->recordUrl(fn(Action $record) => ActionResource::getUrl('view', [$record]))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->label('Numéro'),
                Tables\Columns\TextColumn::make('oo')
                    ->label('Oo')
                    ->state(fn() => 'Oo')
                    ->tooltip(function (TextColumn $column): ?string {
                        $record = $column->getRecord();

                        return $record->operationalObjective?->name;
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Intitulé')
                    ->limit(110)
                    ->url(fn(Action $record) => ActionResource::getUrl('view', ['record' => $record->id]))
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        return $state;
                    }),
                Tables\Columns\TextColumn::make('state')
                    ->formatStateUsing(fn(ActionStateEnum $state) => $state->getLabel() ?? 'Unknown'),
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(fn(ActionTypeEnum $state) => $state->getLabel() ?? 'Unknown')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('department')
                    ->label('Département')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Date échéance')
                    ->date()
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
                SelectFilter::make('operational_objectives')
                    ->label('Objectif opérationel')
                    ->relationship('operationalObjective', 'name')
                    ->searchable(['name']),
                SelectFilter::make('state')
                    ->label('Etat')
                    ->options(
                        collect(ActionStateEnum::cases())
                            ->mapWithKeys(fn(ActionStateEnum $action) => [$action->value => $action->getLabel()])
                            ->toArray()
                    ),
                SelectFilter::make('type')
                    ->label('Type')
                    ->options(
                        collect(ActionTypeEnum::cases())
                            ->mapWithKeys(fn(ActionTypeEnum $action) => [$action->value => $action->getLabel()])
                            ->toArray()
                    ),
                SelectFilter::make('users')
                    ->label('Agents')
                    ->relationship('users', 'first_name'),

            ])
            ->filtersFormColumns(3)
            ->filtersFormWidth(MaxWidth::ThreeExtraLarge)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('tabler-edit'),
            ])
            ->headerActions(
                [

                ]
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function actionsInline(Table $table, int $limit = 120): Table
    {
        return $table
            ->defaultSort('name')
            ->defaultPaginationPageOption(50)
            ->recordUrl(fn(Action $record) => ActionResource::getUrl('view', [$record]))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Intitulé')
                    ->limit($limit)
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


    public static function tableRelation(Table $table, Model|OperationalObjective $owner): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->defaultSort('name')
            ->recordUrl(fn(Action $record) => ActionResource::getUrl('view', [$record]))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Intitulé')
                    ->limit(120)
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Ajouter une action')
                    ->icon('tabler-plus')
                    ->form(fn(Form $form): Form => ActionForm::createForm($form, $owner))
                    ->before(function (array $data) use ($owner): array {
                        //va pas
                        $department = $owner->department;
                        $data['department'] = $department;

                        return $data;
                    }),
            ])
            ->actions([

            ]);
    }
}

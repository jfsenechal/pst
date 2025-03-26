<?php

namespace App\Filament\Resources;

use App\Constant\ActionPriorityEnum;
use App\Constant\ActionStateEnum;
use App\Filament\Resources\ActionResource\Pages;
use App\Form\ActionForm;
use App\Models\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class ActionResource extends Resource
{
    protected static ?string $model = Action::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Liste des actions';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return ActionForm::createForm($form, null);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->defaultPaginationPageOption(50)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Intitulé')
                    ->limit(120)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state')
                    ->formatStateUsing(fn($state) => ActionStateEnum::tryFrom($state)?->getLabel() ?? 'Unknown')
                    ->icon(
                        fn($state) => ActionStateEnum::tryFrom($state)?->getIcon(
                        ) ?? 'heroicon-m-question-mark-circle'
                    )
                    ->color(fn($state) => ActionStateEnum::tryFrom($state)?->getColor() ?? 'gray'),
                Tables\Columns\TextColumn::make('priority')
                    ->formatStateUsing(fn($state) => ActionPriorityEnum::tryFrom($state)?->getLabel() ?? 'Unknown')
                    ->sortable()
                    ->badge()
                    ->icon(
                        fn($state) => ActionPriorityEnum::tryFrom($state)?->getIcon(
                        ) ?? 'heroicon-m-question-mark-circle'
                    )
                    ->color(fn($state) => ActionPriorityEnum::tryFrom($state)?->getColor() ?? 'gray'),
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
                SelectFilter::make('priority')
                    ->label('Priorité')
                    ->options(
                        collect(ActionPriorityEnum::cases())
                            ->mapWithKeys(fn(ActionPriorityEnum $action) => [$action->value => $action->getLabel()])
                            ->toArray()
                    ),
                SelectFilter::make('users')
                    ->label('Agents')
                    ->relationship('users', 'first_name'),
                SelectFilter::make('services')
                    ->relationship('services', 'name'),
            ])
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActions::route('/'),
            'create' => Pages\CreateAction::route('/create'),
            'view' => Pages\ViewAction::route('/{record}'),
            'edit' => Pages\EditAction::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->name;
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return ActionResource::getUrl('view', ['record' => $record]);
    }
}

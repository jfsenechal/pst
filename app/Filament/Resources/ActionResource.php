<?php

namespace App\Filament\Resources;

use App\Constant\ActionStateEnum;
use App\Filament\Resources\ActionResource\Pages;
use App\Filament\Resources\ActionResource\RelationManagers\FollowUpsRelationManager;
use App\Filament\Resources\ActionResource\RelationManagers\MediasRelationManager;
use App\Form\ActionForm;
use App\Models\Action;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

//https://www.youtube.com/watch?v=85uRvsUvwJQ&list=PLqDySLfPKRn6fgrrdg4_SmsSxWzVlUQJo&index=23
//https://filamentphp.com/content/leandrocfe-navigating-filament-pages-with-previous-and-next-buttons
class ActionResource extends Resource
{
    protected static ?string $model = Action::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 3;
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
                    ->url(fn(Action $record) => ActionResource::getUrl('view', ['record' => $record->id]))
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        return $state;
                    }),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state')
                    ->formatStateUsing(fn(ActionStateEnum $state) => $state->getLabel() ?? 'Unknown'),
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
                SelectFilter::make('users')
                    ->label('Agents')
                    ->relationship('users', 'first_name'),

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
            RelationGroup::make('group', [
                MediasRelationManager::class,
                FollowUpsRelationManager::class,
            ]),

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

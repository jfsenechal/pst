<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActionResource\Pages;
use App\Filament\Resources\ActionResource\RelationManagers\FollowUpsRelationManager;
use App\Filament\Resources\ActionResource\RelationManagers\HistoriesRelationManager;
use App\Filament\Resources\ActionResource\RelationManagers\MediasRelationManager;
use App\Form\ActionForm;
use App\Models\Action;
use App\Tables\ActionTables;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

//https://www.youtube.com/watch?v=85uRvsUvwJQ&list=PLqDySLfPKRn6fgrrdg4_SmsSxWzVlUQJo&index=23
//https://filamentphp.com/content/leandrocfe-navigating-filament-pages-with-previous-and-next-buttons
class ActionResource extends Resource
{
    protected static ?string $model = Action::class;

    protected static ?string $navigationIcon = 'tabler-bolt';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Liste des actions';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return ActionForm::createForm($form, null);
    }

    public static function table22(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->defaultPaginationPageOption(50)
            ->modifyQueryUsing(fn(Builder $query) => $query->where('department', '=', 'VILLE'))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->label('Numéro'),
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
                Tables\Columns\TextColumn::make('oo')
                    ->label('Oo')
                    ->state(fn() => 'Oo')
                    ->tooltip(function (TextColumn $column): ?string {
                        $record = $column->getRecord();

                        return $record->operationalObjective?->name;
                    }),
                Tables\Columns\TextColumn::make('department')
                    ->searchable()
                    ->sortable()
                    ->label('Département'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return ActionTables::table($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('group', [
                MediasRelationManager::class,
                FollowUpsRelationManager::class,
                HistoriesRelationManager::class,
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

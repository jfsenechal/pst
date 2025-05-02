<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrategicObjectiveResource\Pages;
use App\Filament\Resources\StrategicObjectiveResource\RelationManagers\OosRelationManager;
use App\Form\StrategicObjectiveForm;
use App\Models\StrategicObjective;
use App\Tables\StrategicObjectiveTables;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class StrategicObjectiveResource extends Resource
{
    protected static ?string $model = StrategicObjective::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return 'Objectif Stratégique (OS)';
    }

    public static function form(Form $form): Form
    {
        return StrategicObjectiveForm::createForm($form);
    }

    public static function table(Table $table): Table
    {
        return StrategicObjectiveTables::table($table);
    }

    public static function getRelations(): array
    {
        return [
            OosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOs::route('/'),
            'create' => Pages\CreateStrategicObjective::route('/create'),
            'view' => Pages\ViewStrategicObjective::route('/{record}'),
            'edit' => Pages\EditStrategicObjective::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->name;
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OperationalObjectiveResource\Pages;
use App\Form\OperationalObjectiveForm;
use App\Models\OperationalObjective;
use App\Tables\OperationalObjectiveTables;
use Filament\Schemas\Components\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class OperationalObjectiveResource extends Resource
{
    protected static ?string $model = OperationalObjective::class;

    protected static string|null|\BackedEnum $navigationIcon = 'tabler-target';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return 'Objectif Opérationnel (OO)';
    }

    public static function table(Table $table): Table
    {
        return OperationalObjectiveTables::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOperationalObjectives::route('/'),
            'create' => Pages\CreateOperationalObjective::route('/create'),
            'view' => Pages\ViewOperationalObjective::route('/{record}'),
            'edit' => Pages\EditOperationalObjective::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->name;
    }
}

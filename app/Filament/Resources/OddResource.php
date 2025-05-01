<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OddResource\Pages;
use App\Form\OddForm;
use App\Models\Odd;
use App\Tables\OddTables;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class OddResource extends Resource
{
    protected static ?string $model = Odd::class;

    protected static ?string $navigationIcon = 'tabler-trees';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Développement durable (ODD)';

    public static function getModelLabel(): string
    {
        return 'Objectif de développement durable (ODD)';
    }

    public static function form(Form $form): Form
    {
        return OddForm::createForm($form);
    }

    public static function table(Table $table): Table
    {
        return OddTables::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOdds::route('/'),
            'create' => Pages\CreateOdd::route('/create'),
            'view' => Pages\ViewOdd::route('/{record}'),
            'edit' => Pages\EditOdd::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->name;
    }
}

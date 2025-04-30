<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OddResource\Pages;
use App\Form\OddForm;
use App\Models\Odd;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

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
        return $table
            ->defaultSort('name')
            ->defaultPaginationPageOption(50)
            ->recordUrl(fn(Odd $record) => self::getUrl('view', [$record]))
            ->columns([
                Tables\Columns\Layout\Grid::make()
                    ->columns(1)
                    ->schema([
                        Tables\Columns\Layout\Split::make([
                            Tables\Columns\Layout\Grid::make()
                                ->columns(1)
                                ->schema([
                                    Tables\Columns\ImageColumn::make('icon')
                                        ->height(150)
                                        ->width(120)
                                        ->disk('public')
                                        ->extraImgAttributes([
                                            'class' => 'rounde44d-md',
                                        ]),
                                ])->grow(false),
                            Tables\Columns\TextColumn::make('actions_count')
                                ->label('Actions')
                                ->counts('actions'),
                            Tables\Columns\Layout\Stack::make([
                                Tables\Columns\TextColumn::make('name')
                                    ->limit(120)
                                    ->searchable()
                                    ->color(fn(Odd $odd) => $odd->color)
                                    ->weight(FontWeight::Medium),
                            ])->extraAttributes(['class' => 'space-y-2'])
                                ->grow(),
                        ]),
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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

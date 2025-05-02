<?php

namespace App\Tables;

use App\Filament\Resources\OddResource;
use App\Models\Odd;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class OddTables
{
    //https://github.com/LaravelDaily/FilamentExamples-Projects/blob/main/tables/table-as-grid-with-cards/app/Filament/Resources/UserResource.php
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->defaultPaginationPageOption(50)
            ->recordUrl(fn(Odd $record) => OddResource::getUrl('view', [$record]))
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
}

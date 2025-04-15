<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FollowUpResource\Pages;
use App\Form\FollowUpForm;
use App\Models\FollowUp;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FollowUpResource extends Resource
{
    protected static ?string $model = FollowUp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return FollowUpForm::createForm($form);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property')
                    ->label('Champ'),
                Tables\Columns\TextColumn::make('old_value')
                    ->label('Ancienne valeur'),
                Tables\Columns\TextColumn::make('new_value')
                    ->label('Nouvelle valeur'),
                Tables\Columns\TextColumn::make('user_add')
                    ->label('Par'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
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
            'index' => Pages\ListFollowUps::route('/'),
            'create' => Pages\CreateFollowUp::route('/create'),
            'view' => Pages\ViewFollowUp::route('/{record}'),
            'edit' => Pages\EditFollowUp::route('/{record}/edit'),
        ];
    }
}

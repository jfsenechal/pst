<?php

namespace App\Tables;

use App\Form\PartnerForm;
use App\Models\Partner;
use Filament\Schemas\Components\Form;
use Filament\Tables;
use Filament\Tables\Table;

class PartnerTables
{
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->defaultPaginationPageOption(50)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('initials')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Téléphone'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
             /*   Tables\Actions\Action::make('edit_modal')
                    ->label('Modifier rapide')
                    ->modal(true)
                    ->form(fn(Form $form, Partner $record) => PartnerForm::createForm($form, $record))
                    ->action(function (array $data, Partner $record): void {
                        $record->save();
                    }),*/
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

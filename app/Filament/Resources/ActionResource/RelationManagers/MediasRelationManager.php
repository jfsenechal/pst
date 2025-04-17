<?php

namespace App\Filament\Resources\ActionResource\RelationManagers;

use App\Form\ActionForm;
use App\Models\Media;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Storage;

class MediasRelationManager extends RelationManager
{
    protected static string $relationship = 'medias';

    public function form(Form $form): Form
    {
        return $form
            ->schema(ActionForm::fieldsAttachment());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('download')
                    ->label('Téléchargement')
                    ->state('Télécharger')
                    ->icon('tabler-download')
                    ->action(fn(Media $media) => Storage::disk('public')->download($media->file_name)),
                Tables\Columns\TextColumn::make('size'),
                Tables\Columns\TextColumn::make('mime_type'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\ActionResource\RelationManagers;

use App\Tables\FollowupTables;
use Filament\Forms;
use Filament\Schemas\Components\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class FollowUpsRelationManager extends RelationManager
{
    protected static string $relationship = 'followups';
    protected static ?string $title = 'Suivis';
    protected static ?string $label = 'Suivi';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\RichEditor::make('content')
                    ->label('Contenu')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return FollowupTables::table($table);
    }
}

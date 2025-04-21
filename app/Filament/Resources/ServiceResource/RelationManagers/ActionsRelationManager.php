<?php

namespace App\Filament\Resources\ServiceResource\RelationManagers;

use App\Tables\ActionTables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ActionsRelationManager extends RelationManager
{
    protected static string $relationship = 'leadingActions';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return ' Actions';
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return ActionTables::actionsInline($table);
    }
}

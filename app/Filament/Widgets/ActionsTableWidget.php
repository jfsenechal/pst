<?php

namespace App\Filament\Widgets;

use App\Repository\ActionRepository;
use App\Tables\ActionTables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ActionsTableWidget extends BaseWidget
{
    public function table(Table $table): Table
    {
        $user = auth()->user();
        $table
            ->query(
                ActionRepository::findByUser($user->id)
            );

        return ActionTables::actionsInline($table, limit: 60);
    }
}

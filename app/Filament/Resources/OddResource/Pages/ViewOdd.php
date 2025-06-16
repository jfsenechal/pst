<?php

namespace App\Filament\Resources\OddResource\Pages;

use App\Filament\Resources\OddResource;
use App\Filament\Resources\OddResource\RelationManagers\ActionsRelationManager;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewOdd extends ViewRecord
{
    protected static string $resource = OddResource::class;

    public function getTitle(): string
    {
        return $this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->icon('tabler-edit'),
            Actions\DeleteAction::make()
                ->icon('tabler-trash'),
        ];
    }

    public function infolist(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                TextEntry::make('descripton')
                    ->label(false)
                    ->columnSpanFull(),
            ]);
    }

    protected function getAllRelationManagers(): array
    {
        $relations = $this->getResource()::getRelations();
        $relations[] = ActionsRelationManager::class;

        return $relations;
    }
}

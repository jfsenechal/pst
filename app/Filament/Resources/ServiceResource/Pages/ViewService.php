<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use App\Filament\Resources\ServiceResource\RelationManagers\ActionsRelationManager;
use App\Models\User;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class ViewService extends ViewRecord
{
    protected static string $resource = ServiceResource::class;

    public function getTitle(): string
    {
        return $this->record->name ?? 'Empty name';
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
        return $infolist->schema([
            Fieldset::make('users_tab')
                ->label('Agents')
                ->schema([
                    TextEntry::make('users')
                        ->label(false)
                        ->badge()
                        ->formatStateUsing(fn(User $state): string => $state->last_name.' '.$state->first_name),
                ]),
        ]);
    }

    protected function getAllRelationManagers(): array
    {
        $relations = $this->getResource()::getRelations();
        $relations[] = ActionsRelationManager::class;

        return $relations;
    }
}

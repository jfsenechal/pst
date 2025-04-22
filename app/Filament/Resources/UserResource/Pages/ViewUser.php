<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\OddResource\RelationManagers\ActionsRelationManager;
use App\Filament\Resources\UserResource;
use App\Models\Action;
use Filament\Actions;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use STS\FilamentImpersonate\Pages\Actions\Impersonate;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return $this->record->name();
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('email')
                ->label('Email')
                ->icon('tabler-mail'),
            TextEntry::make('phone')
                ->label('Téléphone')
                ->icon('tabler-phone'),
            TextEntry::make('mobile')
                ->label('Mobile')
                ->icon('tabler-device-mobile'),
            TextEntry::make('extension')
                ->label('Extension')
                ->icon('tabler-device-landline-phone'),
            TextEntry::make('departments')
                ->label('Départements')
                ->icon('tabler-device-mobile'),
            TextEntry::make('roles.name')
                ->label('Rôles')
                ->icon('tabler-user-shield'),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->icon('tabler-edit'),
            Impersonate::make(),
        ];
    }

    protected function getAllRelationManagers(): array
    {
        $relations = $this->getResource()::getRelations();
        $relations[] = ActionsRelationManager::class;

        return $relations;
    }
}

<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string|Htmlable
    {
        return $this->getAllTableRecordsCount().' agents';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('roles_help')
                ->label('Les rôles')
                ->icon('tabler-user-heart')
                ->modal()
                ->modalHeading('Explications des différents rôles')
                ->modalContent(view('filament.resources.user-resource.pages.roles-help')),
        ];
    }
}

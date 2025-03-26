<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Filament\Resources\ActionResource;
use Filament\Actions;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Resources\Pages\ListRecords;

class ListActions extends ListRecords
{
    protected static string $resource = ActionResource::class;

    public function getTitle(): string|Htmlable
    {
        return $this->getAllTableRecordsCount().' actions';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Ajouter une action')
                ->icon('tabler-plus'),
        ];
    }
}

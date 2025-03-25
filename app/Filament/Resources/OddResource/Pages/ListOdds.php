<?php

namespace App\Filament\Resources\OddResource\Pages;

use App\Filament\Resources\OddResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOdds extends ListRecords
{
    protected static string $resource = OddResource::class;

    public function getModelLabel(): string
    {
        return 'Objectif de développement durable (ODD)';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

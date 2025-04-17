<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Models\Media;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAction extends EditRecord
{
    protected static string $resource = ActionResource::class;

    protected function getRedirectUrl(): ?string
    {
        $resource = static::getResource();

        return $resource::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->icon('tabler-eye'),
        ];
    }
}

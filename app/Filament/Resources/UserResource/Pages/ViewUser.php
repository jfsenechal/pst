<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return $this->record->name() ?? 'Empty name';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

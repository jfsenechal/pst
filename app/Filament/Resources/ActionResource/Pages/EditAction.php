<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\RedirectTrait;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAction extends EditRecord
{
    use RedirectTrait;

    protected static string $resource = ActionResource::class;

    /**
     * to remove word "editer"
     */
    public  function getTitle(): string
    {
        return $this->getRecord()->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->icon('tabler-eye'),
        ];
    }
}

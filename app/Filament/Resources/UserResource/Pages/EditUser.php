<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\RedirectTrait;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditUser extends EditRecord
{
    use RedirectTrait;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return $this->getRecord()->last_name.' '.$this->getRecord()->first_name;
    }
}

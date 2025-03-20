<?php

namespace App\Filament\Resources\StrategicObjectiveResource\Pages;

use App\Filament\Resources\StrategicObjectiveResource;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;

class ViewStrategicObjective extends ViewRecord
{
    protected static string $resource = StrategicObjectiveResource::class;

    public function getTitle(): string
    {
        return $this->record->name ?? 'Empty name';
    }

    /**
     * no form in view
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\StrategicObjectiveResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\StrategicObjectiveResource;
use App\Models\Action;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewStrategicObjective extends ViewRecord
{
    protected static string $resource = StrategicObjectiveResource::class;

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

    public function getBreadcrumbs(): array
    {
        return [
            StrategicObjectiveResource::getUrl('index') => 'Objectifs Stratégiques',
            'OS',
            //$this->getBreadcrumb(),
        ];
    }

    /**
     * no form in view
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

}

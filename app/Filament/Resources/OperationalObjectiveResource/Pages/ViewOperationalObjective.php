<?php

namespace App\Filament\Resources\OperationalObjectiveResource\Pages;

use App\Filament\Resources\OperationalObjectiveResource;
use App\Filament\Resources\StrategicObjectiveResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Form;

class ViewOperationalObjective extends ViewRecord
{
    protected static string $resource = OperationalObjectiveResource::class;

    public function getTitle(): string
    {
        return $this->record->name ?? 'Empty name';

    }

    public function getBreadcrumbs(): array
    {
        $parent = $this->record->strategicObjective()->first();

        return [
            StrategicObjectiveResource::getUrl('index') => 'Objectifs Stratégiques',
            StrategicObjectiveResource::getUrl('view', ['record' => $parent]) => $parent->name,
            'OO',
            // $this->getBreadcrumb(),
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

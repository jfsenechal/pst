<?php

namespace App\Filament\Resources\StrategicObjectiveResource\Pages;

use App\Filament\Exports\StrategicObjectiveExporter;
use App\Filament\Resources\StrategicObjectiveResource;
use App\Models\StrategicObjective;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;

class ListOs extends ListRecords
{
    protected static string $resource = StrategicObjectiveResource::class;

    protected static string $view = 'filament.resources.strategic-objective-list';
    /**
     * @var Collection|StrategicObjective[] $oss
     */
    private Collection $oss;

    public function getTitle(): string|Htmlable
    {
        return $this->getAllTableRecordsCount().' objectifs stratégiques (OS)';
    }

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Exporter en Xlsx')
                ->icon('tabler-download')
                ->color('secondary')
                ->exporter(StrategicObjectiveExporter::class)
                ->formats([
                    ExportFormat::Xlsx,
                ]),
            Actions\CreateAction::make()
                ->label('Ajouter un OS')
                ->icon('tabler-plus'),
        ];
    }

    public function mount(): void
    {
        parent::mount();
        $this->oss = StrategicObjective::with('oos')->get();
    }
}

<?php

namespace App\Filament\Exports;

use App\Models\StrategicObjective;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Collection;

class StrategicObjectiveExporter extends Exporter
{
    protected static ?string $model = StrategicObjective::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('type')
                ->label('Type'),
            ExportColumn::make('strategic_name')
                ->label('Strategic Objective'),
            ExportColumn::make('operational_name')
                ->label('Operational Objective'),
        ];
    }

    public function getExportData(Collection $records): array
    {
        $rows = [];

        $strategicObjectives = StrategicObjective::with('operationalObjectives')->orderBy('position')->get();

        foreach ($strategicObjectives as $strategic) {
            $rows[] = [
                'type' => 'Strategic Objective',
                'strategic_name' => $strategic->name,
                'operational_name' => '',
            ];

            foreach ($strategic->operationalObjectives()->orderBy('position')->get() as $operational) {
                $rows[] = [
                    'type' => '',
                    'strategic_name' => '',
                    'operational_name' => $operational->name,
                ];
            }
        }

        return $rows;
    }

    public function query(): \Generator
    {
        $strategicObjectives = StrategicObjective::with('operationalObjectives')
            ->orderBy('position')->get();

        foreach ($strategicObjectives as $strategic) {
            yield [
                'type' => 'Strategic Objective',
                'strategic_name' => $strategic->name,
                'operational_name' => '',
            ];

            foreach ($strategic->operationalObjectives()->orderBy('position')->get() as $operational) {
                yield [
                    'type' => '',
                    'strategic_name' => '',
                    'operational_name' => $operational->name,
                ];
            }
        }
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your strategic objective export has completed and '.number_format($export->successful_rows).' '.str(
                'row'
            )->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}

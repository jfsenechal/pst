<?php

namespace App\Filament\Exports;

use App\Models\StrategicObjective;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class StrategicObjectiveExport implements FromCollection
{
    protected static ?string $model = StrategicObjective::class;

    public function collection(): Collection
    {
        $data = collect();
        $strategicObjectives = StrategicObjective::with('oos.actions')
            ->orderBy('position')
            ->get();

        foreach ($strategicObjectives as $strategic) {
            // Strategic Objective row
            $data->push([
                "O.S ".$strategic->position,
                null,
                $strategic->name,
                null,
            ]);

            foreach ($strategic->oos as $operational) {
                // Operational Objective row
                $data->push([
                    null,
                    "O.O ".$strategic->position.'.'.$operational->position,
                    $operational->name,
                    null,
                ]);

                foreach ($operational->actions as $action) {
                    // Action row
                    $data->push([
                        null,
                        "Action ".$action->id,
                        $action->name,
                        null,
                    ]);
                }
            }
        }

        return $data;
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

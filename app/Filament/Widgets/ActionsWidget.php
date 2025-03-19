<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActionsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Actions finies', 5)
                ->icon('tabler-mood-smile')
                ->description('Géniale :-)')
                ->color('success'),

            Stat::make('Actions en cours', 25)
                ->description('Courage')
                ->icon('tabler-mood-smile-beam')
                ->color('warning'),

        ];
    }
}

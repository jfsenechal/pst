<?php

namespace App\Tables\Columns;

use Filament\Tables\Columns\Column;

/**
 * https://laraveldaily.com/post/filament-custom-table-column-progress-bar
 */
class ProgressColumn extends Column
{
    protected string $view = 'tables.columns.progress-column';
}

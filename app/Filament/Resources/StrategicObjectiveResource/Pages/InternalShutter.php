<?php

namespace App\Filament\Resources\StrategicObjectiveResource\Pages;

use App\Constant\DepartmentEnum;
use App\Filament\Resources\StrategicObjectiveResource;
use App\Models\StrategicObjective;
use App\Repository\StrategicObjectiveRepository;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;

class InternalShutter extends ListRecords
{
    /**
     * @var Collection|StrategicObjective[] $oss
     */
    private Collection|array $oss = [];
    protected static ?int $navigationSort = 5;
    protected static string $resource = StrategicObjectiveResource::class;

    public static function getNavigationLabel(): string
    {
        return 'Volet interne';
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'tabler-list';
    }

    protected static string $view = 'filament.resources.strategic-objective-list';

    public function getTitle(): string|Htmlable
    {
        return ' Volet interne';
    }

    public function mount(): void
    {
        parent::mount();
        $this->oss = StrategicObjectiveRepository::findByDepartmentWithOosAndActions( DepartmentEnum::COMMON->value);
    }
}

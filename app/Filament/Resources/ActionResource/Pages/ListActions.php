<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Constant\ActionStateEnum;
use App\Filament\Resources\ActionResource;
use App\Repository\ActionRepository;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class ListActions extends ListRecords
{
    protected static string $resource = ActionResource::class;

    public function getTitle(): string|Htmlable
    {
        return $this->getAllTableRecordsCount().' actions';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Ajouter une action')
                ->icon('tabler-plus'),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            0 => Tab::make('All')
                ->label('Toutes')
                ->badge(function (): int {
                    return ActionRepository::all();
                }),
        ];
        foreach (ActionStateEnum::cases() as $actionStateEnum) {
            $tabs[] =
                Tab::make($actionStateEnum->value)
                    ->badge(function () use ($actionStateEnum): int {
                        return ActionRepository::actionByStateCount($actionStateEnum);
                    })
                    ->label($actionStateEnum->getLabel())
                    ->badgeColor($actionStateEnum->getColor())
                    ->icon($actionStateEnum->getIcon())
                    ->modifyQueryUsing(function (Builder $query) use ($actionStateEnum) {
                        return $query->where('state', $actionStateEnum->value);
                    });
        }

        return $tabs;
    }
}

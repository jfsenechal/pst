<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\ServiceResource;
use App\Models\Action;
use Filament\Actions;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewService extends ViewRecord
{
    protected static string $resource = ServiceResource::class;

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

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('name')
                ->label('Nom'),
            Fieldset::make('actions')
                ->label('Actions liés')
                ->schema([
                    RepeatableEntry::make('actions')
                        ->label(false)
                        ->columnSpanFull()
                        ->schema([
                            TextEntry::make('name')
                                ->label('Nom')
                                ->columnSpanFull()
                                ->url(
                                    fn(Action $record): string => ActionResource::getUrl(
                                        'view',
                                        ['record' => $record]
                                    )
                                ),
                        ]),
                ]),
        ]);
    }
}

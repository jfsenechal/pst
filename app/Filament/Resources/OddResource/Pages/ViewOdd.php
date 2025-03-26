<?php

namespace App\Filament\Resources\OddResource\Pages;

use App\Filament\Resources\OddResource;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;

class ViewOdd extends ViewRecord
{
    protected static string $resource = OddResource::class;

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
        return $infolist
            ->schema([
                TextEntry::make('name')
                    ->label('Nom')
                    ->columnSpanFull(),
                TextEntry::make('descripton')
                    ->label('Description')
                    ->columnSpanFull(),
                TextEntry::make('justification')
                    ->label('Justification')
                    ->columnSpanFull(),
            ]);
    }
}

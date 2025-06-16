<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\PartnerResource;
use App\Models\Action;
use Filament\Actions;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class ViewPartner extends ViewRecord
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->icon('tabler-edit'),
            Actions\DeleteAction::make()
                ->icon('tabler-trash'),
        ];
    }

    public function getTitle(): string
    {
        return $this->record->name.' '.$this->record->initials ?? 'Empty name';
    }

    public function infolist(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                TextEntry::make('email')
                    ->icon('tabler-mail'),
                TextEntry::make('phone')
                    ->icon('tabler-phone'),
                TextEntry::make('description')
                    ->label(false)
                    ->html()
                    ->columnSpanFull()
                    ->prose(),
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

<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\UserResource;
use App\Models\Action;
use Filament\Actions;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return $this->record->name() ?? 'Empty name';
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('first_name')
                ->label('Prénom'),
            TextEntry::make('last_name')
                ->label('Nom'),
            TextEntry::make('email')
                ->label('Email')
                ->icon('tabler-mail'),
            TextEntry::make('phone')
                ->label('Téléphone')
                ->icon('tabler-phone'),
            TextEntry::make('mobile')
                ->label('Mobile')
                ->icon('tabler-device-mobile'),
            TextEntry::make('extension')
                ->label('Extension')
                ->icon('tabler-device-landline-phone'),
            TextEntry::make('departments')
                ->label('Départements')
                ->icon('tabler-device-mobile'),
            Fieldset::make('actions')
                ->label('Actions liés')
                ->schema([
                    RepeatableEntry::make('action_user')
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

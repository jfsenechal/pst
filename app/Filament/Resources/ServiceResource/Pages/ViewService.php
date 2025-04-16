<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\ServiceResource;
use App\Filament\Resources\UserResource;
use App\Models\Action;
use App\Models\User;
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
            Fieldset::make('users_tab')
                ->label('Agents')
                ->schema([
                    TextEntry::make('users')
                        ->label(false)
                        ->badge()
                        ->formatStateUsing(fn(User $state): string => $state->last_name.' '.$state->first_name)
                ]),
            Fieldset::make('actions')
                ->label('Actions liés')
                ->schema([
                    RepeatableEntry::make('action_service_leader')
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

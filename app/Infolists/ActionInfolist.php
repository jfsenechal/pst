<?php

namespace App\Infolists;

use App\Constant\ActionStateEnum;
use App\Filament\Resources\OddResource;
use App\Infolists\Components\ProgressEntry;
use App\Models\Odd;
use App\Models\Partner;
use App\Models\Service;
use App\Models\User;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

class ActionInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Split::make([
                    Section::make([
                        TextEntry::make('description')
                            ->label(false)
                            ->html()
                            ->prose(),
                        TextEntry::make('work_plan')
                            ->label('Plan de travail')
                            ->html()
                            ->prose(),
                        TextEntry::make('evaluation_indicator')
                            ->label('Indicateur d\'évaluation')
                            ->html()
                            ->prose(),
                        Fieldset::make('team')
                            ->label('Team')
                            ->schema([
                                TextEntry::make('users')
                                    ->label('Agents pilotes')
                                    ->badge()
                                    ->formatStateUsing(
                                        fn(User $state): string => $state->last_name.' '.$state->first_name
                                    ),
                                TextEntry::make('mandataries')
                                    ->label('Mandataires')
                                    ->badge()
                                    ->formatStateUsing(
                                        fn(User $state): string => $state->last_name.' '.$state->first_name
                                    ),
                                TextEntry::make('leaderServices')
                                    ->label('Services porteurs')
                                    ->badge()
                                    ->formatStateUsing(fn(Service $state): string => $state->name),
                                TextEntry::make('partnerServices')
                                    ->label('Services partenaires')
                                    ->badge()
                                    ->formatStateUsing(fn(Service $state): string => $state->name),
                                TextEntry::make('partners')
                                    ->label('Partenaires')
                                    ->badge()
                                    ->formatStateUsing(fn(Partner $state): string => $state->name),
                            ]),
                    ]),
                    Section::make('Etat')->schema([
                        TextEntry::make('state')
                            ->label('Etat d\'avancement')
                            ->formatStateUsing(fn(ActionStateEnum $state) => $state->getLabel() ?? 'Unknown')
                            ->icon(fn(ActionStateEnum $state) => $state->getIcon())
                            ->color(fn(ActionStateEnum $state) => $state->getColor()),
                        ProgressEntry::make('state_percentage')
                            ->label('Pourcentage d\'avancement'),
                        TextEntry::make('due_date')
                            ->label('Date d\'échéance')
                            ->visible(fn(?\DateTime $date) => $date instanceof \DateTime)
                            ->dateTime(),
                        TextEntry::make('created_at')
                            ->label('Créé le')
                            ->dateTime(),
                        TextEntry::make('user_add')
                            ->label('Créé par'),
                        TextEntry::make('department')
                            ->label('Département'),
                    ])->grow(false),
                ])
                    ->columnSpanFull()
                    ->from('md'),
                Fieldset::make('odd_tab')
                    ->label('Objectifs de développement durable')
                    ->schema([
                        RepeatableEntry::make('odds')
                            ->label(false)
                            ->columnSpanFull()
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nom')
                                    ->color('secondary')
                                    ->columnSpanFull()
                                    ->url(
                                        fn(Odd $record): string => OddResource::getUrl(
                                            'view',
                                            ['record' => $record]
                                        )
                                    ),
                            ]),
                    ]),
                Fieldset::make('budget')
                    ->label('Financement')
                    ->schema([
                        TextEntry::make('budget_estimate')
                            ->markdown()
                            ->label('Budget estimé')
                            ->prose(),
                        TextEntry::make('financing_mode')
                            ->markdown()
                            ->label('Mode de financement')
                            ->prose(),
                    ]),
            ]);
    }
}

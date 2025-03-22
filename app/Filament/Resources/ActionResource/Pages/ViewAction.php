<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Constant\ActionStateEnum;
use App\Filament\Resources\ActionResource;
use App\Filament\Resources\OperationalObjectiveResource;
use App\Filament\Resources\StrategicObjectiveResource;
use App\Models\Action;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ViewEntry;
use Filament\Support\Colors\Color;
use App\Models\OperationalObjective;
use Filament\Actions;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;

class ViewAction extends ViewRecord
{
    protected static string $resource = ActionResource::class;

    public function getTitle(): string
    {
        return $this->record->name ?? 'Empty name';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        $oo = $this->record->operationalObjective()->first();
        $os = $oo->strategicObjective()->first();

        return [
            StrategicObjectiveResource::getUrl('index') => 'Objectifs Stratégiques',
            StrategicObjectiveResource::getUrl('view', ['record' => $os]) => $os->name,
            OperationalObjectiveResource::getUrl('view', ['record' => $oo]) => $oo->name,
            'Action',
            //$this->getBreadcrumb(),
        ];
    }


    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Split::make([
                Section::make([
                    TextEntry::make('name')
                        ->weight(FontWeight::Bold),
                    TextEntry::make('description')
                        ->markdown()
                        ->prose(),
                ]),
                Section::make([
                    TextEntry::make('progress_indicator')
                        ->label('Statut')
                        ->label('Indicateur d\'avancement')
                        ->formatStateUsing(fn ($state) => ActionStateEnum::tryFrom($state)?->getLabel() ?? 'Unknown')
                        ->icon(
                            fn($state) => ActionStateEnum::tryFrom($state)?->getIcon(
                            ) ?? 'heroicon-m-question-mark-circle'
                        )
                        ->color(fn($state) => ActionStateEnum::tryFrom($state)?->getColor() ?? 'gray'),
                    TextEntry::make('created_at')
                        ->label('Créé le')
                        ->dateTime(),
                    TextEntry::make('due_date')
                        ->label('Date d\'échéance')
                        ->dateTime(),
                ])->grow(false),
            ])
                ->columnSpanFull()
                ->from('md'),
        ]);
    }

    public function infolist22(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Split::make([
                    Section::make([
                        TextEntry::make('title')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('content')
                            ->markdown()
                            ->prose(),
                    ])->grow(true),
                    Section::make([
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('published_at')
                            ->dateTime(),
                    ])
                        ->grow(false),
                ]),
            ]);
    }

    private function tableft(): array
    {
        return [
            Tabs::make('Tabs')
                ->tabs([
                    Tabs\Tab::make('Tab 1')
                        ->schema([

                        ]),
                    Tabs\Tab::make('Tab 2')
                        ->schema([
                            // ...
                        ]),
                    Tabs\Tab::make('Budget')
                        ->schema([
                            TextEntry::make('budget_estimate'),
                            TextEntry::make('financing_mode'),
                        ]),
                ]),

            Section::make('Rate limiting')
                ->description('Prevent abuse by limiting the number of requests per period')
                ->schema([

                    RepeatableEntry::make('services')
                        ->schema([
                            TextEntry::make('name'),
                        ])
                        ->columns(2),
                ]),
            TextEntry::make('description')->columnSpanFull(2),
            TextEntry::make('due_date')->dateTime(),
            TextEntry::make('evaluation_indicator'),
            TextEntry::make('work_plan'),
            TextEntry::make('progress_indicator'),
            TextEntry::make('users'),
            TextEntry::make('partners'),
        ];
    }
}

<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Filament\Resources\ActionResource;
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
     return   $infolist
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

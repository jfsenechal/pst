<?php

namespace App\Filament\Resources\StrategicObjectiveResource\Pages;

use App\Events\OoProcessed;
use App\Filament\Resources\StrategicObjectiveResource;
use App\Filament\Resources\StrategicObjectiveResource\RelationManagers\OosRelationManager;
use App\Models\OperationalObjective;
use App\Models\StrategicObjective;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables;
use Filament\Tables\Table;

class ViewStrategicObjective extends ViewRecord
{
    protected static string $resource = StrategicObjectiveResource::class;

    public function getTitle(): string
    {
        return $this->record->name ?? 'Empty name';
    }

    public static function getRelations22(): array
    {
        return [
            //OosRelationManager::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('create_oo')
                ->label('Ajouter une Oo')
                ->form([
                    Forms\Components\TextInput::make('name')
                        ->label('Intitulé')
                        ->required(),
                ])
                ->modal()
                ->action(function (array $data, StrategicObjective $record): void {

                    $this->saveOo($data, $record);

                    Notification::make()
                        ->success()
                        ->title(__('messages.form.registration.notification.finish.title'))
                        ->body(__('messages.form.registration.notification.finish.body'))
                        ->send();

                    $this->getSavedNotification()?->send();
                    //$redirectUrl = $this->getRedirectUrl();

                    //$this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
                }),
        ];
    }

    private function saveOo(array $data, StrategicObjective $record): void
    {
        OperationalObjective::create([
            'name' => $data['name'],
            'strategic_objective_id' => $record->id,
        ]);

        OoProcessed::dispatch($record);
    }

    public static function table22(Table $table): Table
    {
        return $table
            ->query(OperationalObjective::query()->where('operational_objectives.strategic_objective_id', 1))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Intitulé')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function infolist2(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                RepeatableEntry::make('oos')
                    ->label('Objectifs Opérationnels (OO)')
                    ->schema([
                        Infolists\Components\Grid::make()
                            ->inlineLabel(true)
                            ->columns(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Intitule'),
                            ]),
                    ]),
            ]);
    }
}

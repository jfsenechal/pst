<?php

namespace App\Filament\Resources\StrategicObjectiveResource\RelationManagers;

use App\Form\OperationalObjectiveForm;
use App\Tables\OperationalObjectiveTables;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OosRelationManager extends RelationManager
{
    protected static string $relationship = 'oos';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return $ownerRecord->oos()->count().' Objectifs Opérationnels (OO)';
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return OperationalObjectiveForm::formRelation($form, $this->ownerRecord);
    }

    public function table(Table $table): Table
    {
        return OperationalObjectiveTables::tableInline($table);
    }
}

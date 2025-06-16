<?php

namespace App\Filament\Resources\StrategicObjectiveResource\RelationManagers;

use App\Form\OperationalObjectiveForm;
use App\Tables\OperationalObjectiveTables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
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

    public function form(Schema $schema): Schema
    {
        return OperationalObjectiveForm::formRelation($schema, $this->ownerRecord);
    }

    public function table(Table $table): Table
    {
        return OperationalObjectiveTables::tableInline($table);
    }
}

<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Models\Media;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAction extends EditRecord
{
    protected static string $resource = ActionResource::class;

    protected function getRedirectUrl(): ?string
    {
        $resource = static::getResource();

        return $resource::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->icon('tabler-eye'),
        ];
    }

    public function beforeSave22(): void
    {
        foreach ($this->data['attachments'] ?? [] as $attachment) {
            Media::create([
                'action_id' => $this->data['action_id'], // Adjust as needed
                'model_type' => self::class,
                'model_id' => $this->record->id ?? null,
                'name' => $this->file->getClientOriginalName(),
                'file_name' => $this->file->hashName(),
                'mime_type' => $this->file->getMimeType(),
                'disk' => 'public',
                'size' => $this->file->getSize(),
            ]);
        }
    }
}

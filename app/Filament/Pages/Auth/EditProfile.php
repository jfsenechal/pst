<?php

namespace App\Filament\Pages\Auth;

use App\Repository\FilamentColorRepository;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Contracts\Support\Htmlable;

class EditProfile extends BaseEditProfile
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public function getTitle(): string|Htmlable
    {
        return $this->getUser()->first_name.' '.$this->getUser()->first_name;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                Select::make('color_primary')
                    ->options(FilamentColorRepository::colors())
                    ->label('Couleur principale'),
                Select::make('color_secondary')
                    ->options(FilamentColorRepository::colors())
                    ->allowHtml()
                    ->label('Couleur secondaire'),
            ])
            ->columns(2);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/edit-profile.form.email.label'))
            ->email()
            ->readOnly()
            ->required()
            ->maxLength(255)
            ->unique(ignoreRecord: true)
            ->columnSpanFull();
    }

    protected function afterSave(): void
    {
        $this->redirect(request()->header('referer'));
    }
}

<?php

namespace App\Filament\Pages\Auth;

use App\Auth\LdapAuthService;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Pages\Auth\Login as BasePage;

class Login extends BasePage
{
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        if (!$user = LdapAuthService::checkPassword($data['email'], $data['password'])) {
            $this->throwFailureValidationException();
        } else {
            Filament::auth()->login($user, true);
        }

        /*  if (!Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
              $this->throwFailureValidationException();
          }*/

        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (!$user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    public function mount(): void
    {
        parent::mount();

        if (app()->environment('local')) {
            $this->form->fill([
                'email' => 'jf@marche.be',
                'password' => 'marge',
                'remember' => true,
            ]);
        }
    }
}

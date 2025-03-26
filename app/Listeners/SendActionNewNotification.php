<?php

namespace App\Listeners;

use App\Events\ActionProcessed;
use App\Mail\ActionNew;
use App\Models\Action;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Address;

class SendActionNewNotification
{
    public function handle(ActionProcessed $event): void
    {
        $action = $event->action();
        $this->sendMail($action);
    }

    private function sendMail(Action $action): void
    {
        try {
            Mail::to(new Address('jf@marche.be'))
                ->send(new ActionNew($action));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}

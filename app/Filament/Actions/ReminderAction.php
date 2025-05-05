<?php

namespace App\Filament\Actions;

use App\Form\ActionForm;
use App\Mail\ActionReminderMail;
use App\Models\Action as ActionModel;
use App\Repository\ActionRepository;
use Filament\Actions\Action;
use Filament\Actions\Action as ActionAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class ReminderAction
{
    public static function createAction(Model|ActionModel $action): Action
    {
        return ActionAction::make('reminder')
            ->label('Houspiller')
            ->icon('tabler-school-bell')
            ->modal()
            ->modalDescription('Envoyer un mail aux agents')
            ->modalHeading('Où en sommes-nous actuellement ?')
            ->modalContentFooter(new HtmlString('Un lien vers l\'action sera automatiquement ajouté'))
            ->modalContent(
                view('filament.resources.action-resource.reminder-modal-description', [
                    'emails' => ActionRepository::findByActionEmailAgents($action->id),
                ])
            )
            ->form(
                ActionForm::fieldsReminder()
            )
            ->action(function (array $data, ActionModel $action) {
                $emails = ActionRepository::findByActionEmailAgents($action->id);
                if ($emails->count() == 0) {
                    $emails = ['jf@marche.be'];
                }
                try {
                    Mail::to($emails)
                        ->send(new ActionReminderMail($action, $data));
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            });
    }
}

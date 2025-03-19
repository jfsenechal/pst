<?php

namespace App\Console\Commands;

use App\Constant\RoleEnum;
use App\Ldap\User as UserLdap;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as SfCommand;

class SyncUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pst:sync-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users with ldap';

    private Role $agentRole;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->agentRole = Role::where('name', RoleEnum::AGENT->value)->first();

        foreach (UserLdap::all() as $userLdap) {
            if (!$userLdap->getAttributeValue('mail')) {
                continue;
            }
            $username = $userLdap->getAttributeValue('samaccountname')[0];
            if (!$user = User::where('username', $username)->first()) {
                $this->addUser($username, $userLdap);
            } else {
                $this->updateUser($user, $userLdap);
            }
        }

        return SfCommand::SUCCESS;
    }

    private function addUser(string $username, UserLdap $userLdap): void
    {
        $user = User::create([
            'first_name' => $userLdap->getAttributeValue('givenname')[0],
            'last_name' => $userLdap->getAttributeValue('sn')[0],
            'email' => $userLdap->getAttributeValue('mail')[0],
            'username' => $username,
            'password' => \Str::password(),
        ]);
        $user->addRole($this->agentRole);
        $this->info('Add '.$user->first_name.' '.$user->last_name);
    }

    private function updateUser(User $user, mixed $userLdap): void
    {
        $user->update([
                'first_name' => $userLdap->getAttributeValue('givenname')[0],
                'last_name' => $userLdap->getAttributeValue('sn')[0],
                'email' => $userLdap->getAttributeValue('mail')[0],
            ]
        );
    }

}

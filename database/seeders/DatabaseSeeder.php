<?php

namespace Database\Seeders;

use App\Constant\RoleEnum;
use App\Models\OperationalObjective;
use App\Models\Partner;
use App\Models\Role;
use App\Models\Service;
use App\Models\StrategicObjective;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    protected static ?string $password;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::factory()->create([
            'name' => RoleEnum::ADMIN->value,
        ]);

        foreach (RoleEnum::cases() as $role) {
            if ($role !== RoleEnum::ADMIN) {
                Role::factory()->create([
                    'name' => $role->value,
                ]);
            }
        }

        User::factory()
            ->hasAttached($adminRole)
            ->create([
                'first_name' => 'Jf',
                'last_name' => 'Sénéchal',
                'email' => 'jf@marche.be',
                'username' => config('app.pst.user_login_test'),
                'password' => static::$password ??= Hash::make('marge'),
            ]);

        $services = ['Tiers-lieu e-Square', 'Population', 'Service Juridique', 'Educateurs de rue'];
        foreach ($services as $service) {
            Service::factory()->create(['name' => $service]);
        }

        $partners = ['Infor jeunes', 'Région Wallonne', 'Wallonia ASBL'];
        foreach ($partners as $partner) {
            Partner::factory()->create(['name' => $partner]);
        }

        $so1 = StrategicObjective::factory()->create([
            'name' =>
                'Être une Commune Solidaire et Inclusive',
        ]);
        $sos = [
            'Être une Commune Dynamique, Innovante et Prospère',
            'Être une Commune Verte et Résiliente',
        ];
        foreach ($sos as $so) {
            StrategicObjective::factory()->create([
                'name' => $so,
            ]);
        }
        $oos = [
            'Renforcer les services de prévention',
            'Renforcer l\'éclairage LED aux traversées piétonnes',
            'Mutation Conseil consultatif de promotion de l’hôpital vers un Conseil consultatif de la santé',
            'Sensibilisation aux méfaits du tabagisme',
        ];
        foreach ($oos as $oo) {
            OperationalObjective::factory()
                ->create([
                    'strategic_objective_id' => $so1->id,
                    'name' => $oo,
                ]);
        }
    }
}

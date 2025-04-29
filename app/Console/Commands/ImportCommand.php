<?php

namespace App\Console\Commands;

use App\Constant\ActionStateEnum;
use App\Constant\ActionTypeEnum;
use App\Constant\DepartmentEnum;
use App\Models\Action;
use App\Models\Odd;
use App\Models\OperationalObjective;
use App\Models\Partner;
use App\Models\Service;
use App\Models\StrategicObjective;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command as SfCommand;

class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pst:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test command';

    protected string $dir = __DIR__.'/../../../output/';
    private int $lastOs = 0;
    private int $lastOo = 0;

    public function handle(): int
    {
        $csvFile = $this->dir.'pst.csv';
        //$this->importPartners();
        //$this->importServices();
        //$this->importOdd();
        $this->importCsv($csvFile);
        $this->info('Update');

        return SfCommand::SUCCESS;
    }

    public function importCsv($csvFile, $delimiter = '|'): void
    {
        $file_handle = fopen($csvFile, 'r');
        while ($row = fgetcsv($file_handle, null, $delimiter)) {
            $osNum = $row[0];
            $ooNum = $row[1];
            $actionNum = $row[2];
            /**
             *
             */
            if ($actionNum) {
                $this->addAction($row);
                continue;
            }
            if ($osNum) {
                $this->addOs($row);
                continue;
            }
            if ($ooNum) {
                $this->addOo($row);
            }
        }
        fclose($file_handle);
    }

    private function addOs(array $row): void
    {
        $number = preg_replace('/\D/', '', $row[0]);
        $name = $row[3];

        //   $this->info($name);
        $os = StrategicObjective::create([
            'name' => $name,
            'department' => DepartmentEnum::VILLE->value,
            'position' => $number,
            //'synergy' => $synergy,
            // 'description' => $row["Fiche_compl_te_PST_Nom_du_projet"],
        ]);
        $this->lastOs = $os->id;
    }

    private function addOo(array $row): void
    {
        $name = $row[3];
        $number = substr(trim($row[1]), -1);
        //  $this->info('-- '.$name);

        $oo = OperationalObjective::create([
            'strategic_objective_id' => $this->lastOs,
            'name' => $name,
            'department' => DepartmentEnum::VILLE->value,
            'position' => $number,
            // 'synergy' => $synergy,
            // 'description' => $row["Fiche_compl_te_PST_Nom_du_projet"],
        ]);
        $this->lastOo = $oo->id;
    }

    private function addAction(array $row): void
    {
        $name = $row[3];
        $actionNum = $row[2];
        $actionName = $row[3];
        $typeAction = $row[4];
        $mandataires = $row[5];
        $agents = $row[6];
        $servicesPorteur = $row[7];
        $servicesPartenaires = $row[8];
        $etat = $row[9];
        $odds = $row[10];
        $road = $row[11];
        $synergy = $row[12];
        $cleanedString = "";

        if (!$name) {
            return;
        }

        $this->info("---- Action ".$actionNum." ".$name);
        $state = null;
        if ($etat) {
            $state = $this->findState($etat);
            if (!$state) {
                $this->warn('state not found'.$etat);
            }
        }

        if (!$state) {
            $state = ActionStateEnum::TO_VALIDATE->value;
        }

        $type = null;
        if ($typeAction) {
            $type = ActionTypeEnum::findByName($typeAction);
        }

        $action = Action::create([
            'name' => $name,
            'department' => DepartmentEnum::VILLE->value,
            'state' => $state,
            'type' => $type?->value,
            'user_add' => 'import',
            'operational_objective_id' => $this->lastOo,
        ]);

        foreach ($this->findMandatary($mandataires) as $mandatary) {
            $action->mandataries()->attach($mandatary->id);
        }
        foreach ($this->findOdd($odds) as $odd) {
            $action->odds()->attach($odd->id);
        }
        if ($servicesPorteur) {
            if (str_contains($servicesPorteur, '/')) {
                $services = explode('/', $servicesPorteur);
                foreach ($services as $service) {
                    $service = $this->findService(trim($service));
                    if (!$service) {
                        $this->warn("ERROR service not found ".$servicesPorteur);
                    } else {
                        $action->leaderServices()->attach($service->id);
                    }
                }
            } else {
                $service = $this->findService(trim($servicesPorteur));
                if (!$service) {
                    $this->warn("ERROR service not found ".$servicesPorteur);
                } else {
                    $action->leaderServices()->attach($service->id);
                }
            }
        }
        if ($servicesPartenaires) {
            if (str_contains($servicesPartenaires, '/')) {
                $services = explode('/', $servicesPartenaires);
                foreach ($services as $service) {
                    $service = $this->findService(trim($service));
                    if (!$service) {
                        $this->warn("ERROR service not found ".$servicesPartenaires);
                    } else {
                        $action->partnerServices()->attach($service->id);
                    }
                }
            } else {
                $service = $this->findService(trim($servicesPartenaires));
                if (!$service) {
                    $this->warn("ERROR service not found ".$servicesPartenaires);
                } else {
                    $action->partnerServices()->attach($service->id);
                }
            }
        }
    }

    private function importPartners(): void
    {
        $json = File::get($this->dir.'Partenaires externes - Liste.json');
        $data = json_decode($json, true);
        foreach ($data['Partenaires_externes_Liste'] as $row) {
            Partner::create([
                'name' => $row["D_nomination"],
                'description' => $row["Fiche_compl_te_PST_Nom_du_projet"],
            ]);
        }
    }

    private function importServices(): void
    {
        $json = File::get($this->dir.'Services - Liste.json');
        $data = json_decode($json, true);

        foreach ($data['Services_Liste'] as $row) {
            $emails = Str::matchAll('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $row["Acteurs"]);
            $emails = $emails->toArray();

            $department = $this->findSynergy($row["Synergies"]);
            if (!$department) {
                $department = DepartmentEnum::VILLE->value;
            }

            $service = Service::create([
                'name' => $row["Service_interne"],
            ]);
            foreach ($emails as $email) {
                if ($user = User::where('email', $email)->first()) {
                    $service->users()->attach($user->id);
                } else {
                    $this->warn('User not found: '.$email);
                }
            }
        }
    }

    private function importOdd(): void
    {
        $json = File::get($this->dir.'odd.json');
        $data = json_decode($json, true);
        foreach ($data['data'] as $row) {
            Odd::create([
                'name' => $row["name"],
            ]);
        }
    }

    private function findSynergy(string $name): ?string
    {
        return match ($name) {
            "Commune" => DepartmentEnum::VILLE->value,
            "Cpas" => DepartmentEnum::CPAS->value,
            default => null,
        };
    }

    private function findState(string $name): ?string
    {
        return match ($name) {
            "Suspendu" => ActionStateEnum::SUSPENDED->value,
            "En cours" => ActionStateEnum::PENDING->value,
            "Terminé" => ActionStateEnum::FINISHED->value,
            "A démarrer" => ActionStateEnum::START->value,
            default => null,
        };
    }

    private function findMandatary(string $name): array
    {
        $users = [];
        if (!$name) {
            return [];
        }

        if (str_contains($name, ',')) {
            $items = explode(',', $name);
            foreach ($items as $item) {
                list($prenom, $nom) = explode(' ', $item);
                $user = User::where('last_name', trim($nom))->first();
                if (!$user) {
                    $this->warn('ERROR User not found: '.$nom);
                } else {
                    $users[] = $user;
                }
            }
        } else {
            $items = explode(' ', $name);
            if (isset($items[1])) {
                $nom = $items[1];
                $user = User::where('last_name', trim($nom))->first();
                if (!$user) {
                    $this->warn('ERROR User not found: '.$nom);
                } else {
                    $users[] = $user;
                }
            }
        }

        return $users;
    }

    private function findOdd(string $name): array
    {
        $odds = [];
        if (!$name) {
            return [];
        }

        if (str_contains($name, ',')) {
            $items = explode(',', $name);
            foreach ($items as $nom) {
                $odd = Odd::where('name', trim($nom))->first();
                if (!$odd) {
                    $this->warn('ERROR Odd not found: '.$nom);
                } else {
                    $odds[] = $odd;
                }
            }
        } else {
            $odd = Odd::where('name', trim($name))->first();
            if (!$odd) {
                $this->warn('ERROR Odd not found: '.$name);
            } else {
                $odds[] = $odd;
            }
        }

        return $odds;
    }

    private function findService(string $service): ?Service
    {
        return Service::where('name', $service)->orWhere('initials', $service)->first();
    }
}

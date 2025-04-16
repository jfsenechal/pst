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
        $csvFileName = "test.csv";
        $csvFile = $this->dir.'pst.csv';
        $this->readCSV($csvFile);

        //  $this->importPartners();
        //  $this->importServices();
        //  $this->importOdd();
        $this->info('Update');

        return SfCommand::SUCCESS;
    }

    public function readCSV($csvFile, $delimiter = ',')
    {
        $file_handle = fopen($csvFile, 'r');
        while ($row = fgetcsv($file_handle, null, $delimiter)) {
            $osNum = $row[0];
            $ooNum = $row[1];
            $actionNum = $row[2];
            $actionName = $row[3];
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

    private function addOs(array $row)
    {
        $number = preg_replace('/\D/', '', $row[0]);
        $name = $row[3];

        $this->info($name);
        $os = StrategicObjective::create([
            'name' => $name,
            'department' => DepartmentEnum::VILLE->value,
            'position' => $number,
            //'synergy' => $synergy,
            // 'description' => $row["Fiche_compl_te_PST_Nom_du_projet"],
        ]);
        $this->lastOs = $os->id;
    }

    private function addOo(array $row)
    {
        $name = $row[3];
        preg_match_all('/\d+(\.\d+)?/', $row[1], $matches);
        $number = end($matches[0]);

        $this->info('-- '.$name);

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

    private function addAction(array $row)
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

        $this->info("---- ".$name);
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

        Action::create([
            'name' => $name,
            'department' => DepartmentEnum::VILLE->value,
            'state' => $state,
            'type' => $type?->value,
            'user_add' => 'jfsenechal',
            'operational_objective_id' => $this->lastOo,
        ]);
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
                'department' => $department,
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
                'department' => DepartmentEnum::VILLE->value,
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
}

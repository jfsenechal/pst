<?php

namespace App\Console\Commands;

use App\Constant\SynergyEnum;
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

    public function handle(): int
    {
        $this->importPartners();
        $this->importServices();
        $this->importOdd();
        $this->importOs();
        $this->importOo();
        $this->importActions();
        $this->info('Update');

        return SfCommand::SUCCESS;
    }

    private function importPartners(): void
    {
        $json = File::get($this->dir.'Partenaires externes - Liste.json');
        $data = json_decode($json, true);
        foreach ($data['Partenaires_externes_Liste'] as $row) {
            Partner::create([
                'name' => $row["D_nomination"],
                'description' => $row["Fiche_compl_te_PST_Nom_du_projet"],
                'idImport' => $row["ID"],
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

            $synergy = $this->findSynergy($row["Synergies"]);

            $service = Service::create([
                'name' => $row["Service_interne"],
                'synergy' => $synergy,
                'idImport' => $row["ID"],
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
        $json = File::get($this->dir.'Objectifs Développement Durable (ODD) - Liste.json');
        $data = json_decode($json, true);
        foreach ($data['Objectifs_D_veloppement_Durable_ODD_Liste'] as $row) {
            Odd::create([
                'action_id' => null,
                'name' => $row["Objectif_de_D_veloppement_Durable_ODD"],
                'position' => $row["Ordre"],
                'justification' => $row["Justifications_commentaires"],
                'description' => $row["Fiche_projet_compl_te_Nom_du_projet"],
                'idImport' => $row["ID"],
            ]);
        }
    }

    private function importOs(): void
    {
        $json = File::get($this->dir.'Objectifs stratégiques (OS) - Liste.json');
        $data = json_decode($json, true);
        foreach ($data['Objectifs_strat_giques_OS_Liste'] as $row) {

            $synergy = $this->findSynergy($row["Synergies"]);

            StrategicObjective::create([
                'name' => $row["Enjeu_strat_gique1"],
                'position' => $row["Ordre"],
                'synergy' => $synergy,
                'description' => $row["Fiche_compl_te_PST_Nom_du_projet"],
                'idImport' => $row["ID"],
            ]);
        }
    }

    private function importOo(): void
    {
        $json = File::get($this->dir.'Objectifs opérationnels (OO) - Liste.json');
        $data = json_decode($json, true);
        foreach ($data['Objectifs_op_rationnels_OO_Liste'] as $row) {

            $cleanedString = preg_replace('/^\d\s-\s/', '', $row["Objectifs_strat_giques_tre"]);

            $strategicObjective = StrategicObjective::query()->where(
                'name',
                '=',
                $cleanedString
            )->first();
            if (!$strategicObjective) {
                $this->warn('not found'.$cleanedString);

                continue;
            }

            $synergy = $this->findSynergy($row["Synergies"]);

            OperationalObjective::create([
                'strategic_objective_id' => $strategicObjective->id,
                'name' => $row["Enjeu_strat_gique1"],
                'position' => $row["Ordre"],
                'synergy' => $synergy,
                'description' => $row["Fiche_compl_te_PST_Nom_du_projet"],
                'idImport' => $row["ID"],
            ]);
        }
    }

    private function importActions(): void
    {
        $json = File::get($this->dir.'Projets - Liste.json');
        $data = json_decode($json, true);
        foreach ($data['Projets1'] as $row) {

            $cleanedString = preg_replace('/^\d{1,3}\s-\s/', '', $row["Objectifs_op_rationnels_OO"]);

            $operationalObjective = OperationalObjective::query()->where(
                'name',
                '=',
                $cleanedString
            )->first();
            if (!$operationalObjective) {
                $this->warn('not found'.$cleanedString);

                continue;
            }

            Action::create([
                'name' => $row["Nom_du_projet"],
                'operational_objective_id' => $operationalObjective->id,
                'idImport' => $row["ID"],
            ]);
        }
    }

    private function findSynergy(string $name): ?string
    {
        return match ($name) {
            "Commune" => SynergyEnum::VILLE->value,
            "Cpas" => SynergyEnum::CPAS->value,
            "Commune et CPAS" => SynergyEnum::COMMON->value,
            default => null,
        };
    }
}

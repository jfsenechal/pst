<?php

namespace Database\Seeders;

use App\Constant\RoleEnum;
use App\Models\Action;
use App\Models\OperationalObjective;
use App\Models\Partner;
use App\Models\Role;
use App\Models\Service;
use App\Models\StrategicObjective;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        foreach ($this->getSos() as $os) {
            StrategicObjective::factory()->create([
                'name' =>
                    $os,
            ]);
        }

        foreach ($this->getOos() as $soId => $data) {
            foreach ($data as $row) {
                OperationalObjective::factory()
                    ->create([
                        'strategic_objective_id' => $soId,
                        'name' => $row,
                    ]);
            }
        }
        foreach ($this->getAtions() as $ooId => $data) {
            foreach ($data as $action) {
                Action::factory()
                    ->create([
                        'operational_objective_id' => $ooId,
                        'name' => $action,
                    ]);
            }
        }
    }

    public function getSos(): array
    {
        return [
            'Être une Commune Solidaire et Inclusive',
            'Être une Commune Dynamique, Innovante et Prospère',
            'Être une Commune Verte et Résiliente',
            'Une Commune au cadre de vie durable et sécurisant ou attrayant',
            'Être une Commune active dans l\'éducation et la Citoyenneté',
            'Être une Administration Performante et Proactive',
        ];
    }

    public function getOos(): array
    {
        return [
            1 => [
                'Renforcer la cohésion sociale et l\'accès aux services de proximité ',
                'Lutter contre les discriminations et en faveur de l\'égalité des chances',
                'Soutenir l\'économie locale, les commerces et l\'emploi',
            ],
            2 => [
                'Soutenir l\'économie locale, les commerces et l\'emploi ',
                'Renforcer l\'attractivité touristique et culturelle',
                'Dynamiser la vie associative grâce à des évènements qui font rayonner la Ville et renforcent la convivialité ',
                'Développer la culture du numérique et de la dématérialisation',
            ],
            3 => [
                'Réduire l’empreinte carbone et promouvoir les énergies renouvelables',
                'Protéger la biodiversité et restaurer les espaces naturels',
                'Accompagner et soutenir le monde agricole local',
            ],
            4 => [
                'Favoriser la mobilité douce et l\'intermodalité',
                'Améliorer la sécurité et la prévention',
                'Développer la culture du risque',
                'Améliorer l\'offre de logement accessible',
                'Favoriser un cadre de vie agréable',
            ],
            5 => [
                'Renforcer l\'offre d’accueil et le soutien pédagogique',
                'Développer l’implication citoyenne et la dimension participative',
                'Développer l’offre des loisirs socialisants',
            ],
            6 => [
                'Améliorer la gestion communale et l\'accès aux services ',
            ],

        ];
    }

    public function getAtions(): array
    {
        return [
            1 => [
                "Soutenir les associations sociales et d'aide aux personnes vulnérables",
                "Développer des projets intergénérationnels et innovants (école des devoirs à la maison de repos…)",
                "Répondre au mieux aux attentes des Ainés via une analyse constante des services existants",
                "Défense des acteurs actifs dans la prise en charge et la prévention des assuétudes",
                "Dynamiser la plateforme du volontariat",
                "Mise en place d’un guide pratique destiné aux aînés",
                "Faire respecter les places de parkings pour les PMR et en augmenter le nombre",
                "Soutenir la création de clubs handisport",
                "Mutation Conseil consultatif de promotion de l’hôpital vers un Conseil consultatif de la santé",
                "Aide à la jeunesse, soutien au décrochage scolaire, santé mentale",
                "Attention particulière portée à une alimentation saine, qui passe par une valorisation des circuits courts, et à l’exercice physique adapté à chacun, dans le cadre de la prévention des maladies et la promotion de la santé",
                "Soutien aux initiatives et dépistages en cas de maladie grave (cancer)",
                "Mise en place de mesures pour garantir l’accessibilité de toutes les infrastructures publiques (routes, bâtiments, transports, espaces verts, etc.) aux personnes en situation de handicap",
                "Augmentation de la capacité d'accueil du Centre de Soins de Jour",
                "Ouverture d'une Maison d'accueil pour les femmes victimes de violences conjugales",
                "Projet « Naitre et grandir dans une famille en pleine forme »",
                "Soutien à la santé mentale et à la prévention des assuétudes",
                "Promotion de la prévention des maladies et d'une alimentation saine",
                "Sensibilisation aux méfaits du tabagisme",
            ],
            2 => [

                "Création d'un plan d'actions « égalité des chances »",
                "Assurer la transversalité de la thématique pour lutter contre l’exclusion sociale",
                "Collaborer avec les plannings familiaux",
                "Réduction des inégalités dans le sport et la culture",
                "Accorder une attention spécifique à la réduction des inégalités dans le sport et la culture (genre, handicap…)",
                "Soutenir des politiques de tarifs adaptés (article 27…)",
                "Former des agents communaux et des enseignants à la non-discrimination et l'inclusion",
                "Généraliser les cours de français, langue étrangère",
                "Soutenir le projet du CPAS de Maison des Femmes",
                "Inclusion des jeunes en situation de handicap dans les associations",
                "Demander une collaboration avec l’association des commerçants, « CAP sur Marche » pour l’accueil des PMR dans nos commerces",
                "Augmentation des places de parking PMR",
                "Mise en conformité des infrastructures pour les PMR (trottoirs, rampes)",
                "Soutenir la création de clubs handisport",
                "Développement du logement inclusif",
                "Ouverture d’une Maison d’accueil pour femmes victimes de violences conjugales (2026, 35 places)",
                "Mise en place d’un nouveau frigo partagé (proximité service enfance?)",
            ],
            3 => [
                "Organiser des Assises du commerce de proximité",
                "Renforcer les primes et aides aux entreprises locales",
                "Finaliser le Schéma de développement commercial et l’intégrer dans le Schéma de Développement communal",
                "Soutenir l’économie sociale dans les marchés publics",
                "Insertion de critères sociaux et environnementaux dans les marchés publics",
                "Soutenir le dynamisme des parcs d'activités économiques",
                "Renforcer l'ancrage marchois des services publics",
                "Poursuivre la gestion dynamique des parkings et création de nouvelles places Shop&Go",
                "Optimiser le 7e parc d’activités économiques avec IDELUX",
                "Publicité sur la gratuité des parkings de 12h à 14h",
                "Soutien aux commerçants pendant les travaux de la Place aux Foires (début 2025)",
                "Développement de partenariats avec le secteur associatif et privé pour la réinsertion",
                "Créer de nouvelles poches de parking en profitant des opportunités qui se dégageraient",
            ],
            4 => [
                "Soutenir les événements locaux et régionaux",
                "Développer un jumelage avec une commune du nord du pays",
                "Créer une auberge de jeunesse et promouvoir des packs culturels",
                "Organiser des expositions itinérantes et des manifestations culturelles",
                "Développer le tourisme vert et sportif",
                "Intensifier l’insertion socio-professionnelle via les Articles 60§7 et 61",
                "Soutien au développement de projets au Sud et initiative de jumelage avec une commune du Nord du pays",
                "Gaming…",
            ],
            5 => [

            ],
            6 => [
                "Soutenir les événements locaux et régionaux",
                "Développer un jumelage avec une commune du nord du pays",
                "Créer une auberge de jeunesse et promouvoir des packs culturels",
                "Organiser des expositions itinérantes et des manifestations culturelles",
                "Développer le tourisme vert et sportif",
                "Intensifier l’insertion socio-professionnelle via les Articles 60§7 et 61",
                "Soutien au développement de projets au Sud et initiative de jumelage avec une commune du Nord du pays",
                "Développer le secteur du gaming comme levier culturel et économique",
            ],
            7 => [
                "Création d’une communauté d’énergie renouvelable (CER)",
                "Réaliser une cartographie des espaces pour les énergies renouvelables",
                "Installer des ombrières photovoltaïques sur les parkings",
                "Monitorer les consommations énergétiques des bâtiments communaux",
                "Organisation d’actions de sensibilisation sur l’échange d’énergie de pair à pair",
                "Diffuser sur des panneaux d’informations les données relatives aux mesures réalisées sur la qualité de l’air en milieu urbain",
                "Maintenir/adapter les primes communales pour l’isolation des habitations",
                "Sensibiliser les citoyens à l’échange d’énergie pair-à-pair entre particuliers",
                "Organiser des conférences sur l’énergie (URE, primes, systèmes de chauffage)",
                "Accompagnement des plus vulnérables dans la transition énergétique (guidances, tuteur énergie)",
                "Amélioration énergétique et sécuritaire du Centre culturel et sportif",
                "Promouvoir et développer le service de voitures partagées mis en place en 2024",
            ],
            8 => [
                "Mettre en place un plan de replantation d’arbres et haies",
                "Lutter contre les incivilités et infractions environnementales",
                "Avec l’aide et la compétence du DNF, entreprendre une politique raisonnée et pluriannuelle de replantation et de régénération de nos forêts soumises",
            ],
            9 => [
                "Activation du fonds agricole pour assurer la pérennité des exploitations familiales et soutenir l’accès des jeunes à la terre en acquérant des terres qui leur seront ensuite mises à disposition dans le cadre d’un bail à ferme",
                "Création d’une commission technique d’accompagnement qui réunira les services communaux, divers intervenants extérieurs éventuels et des représentants du monde agricole",
            ],
            10 => [
                "Réseau de voies lentes - Développer de nouveaux itinéraires cyclables et notamment la liaison Hargimont - On et la liaison entre la chaussée de l’Ourthe et le rond-point du camp militaire",
                "Étudier la mise en place de navettes autonomes",
                "Poursuivre la mise en œuvre du plan communal de mobilité en étant attentifs aux déplacements domicile-travail et l’accès vers les écoles (Vélo-bus, parkings de délestage, …)",
                "Mettre en place une structure pour gérer le pôle vélo créé à la Maison du Tourisme",
                "Aménager deux mobipôles à l’entrée nord-est et à côté de la N4 en mettant tout en œuvre pour récupérer le terrain de la SOFICO",
                "Élaborer un plan trottoir",
                "Regrouper les Conseils consultatifs en Mobilité douce et sentiers et Sécurité routière en un conseil consultatif de la mobilité",
            ],
            11 => [
                "Étudier l’extension du réseau de caméras connectées",
                "Renforcer les services de prévention",
                "Lancer des Assises de la sécurité routière",
                "Mettre en place une mobilité sécurisée aux abords des écoles",
                "Renforcer l'éclairage LED aux traversées piétonnes",
                "Installation de défibrillateurs supplémentaires",
                "Aménager et sécuriser les entrées de villages (effet de porte, identité villageoise)",
            ],
            12 => [
                "Étudier l’extension du réseau de caméras connectées",
                "Renforcer les services de prévention",
                "Lancer des Assises de la sécurité routière",
                "Mettre en place une mobilité sécurisée aux abords des écoles",
                "Renforcer l'éclairage LED aux traversées piétonnes",
                "Installation de défibrillateurs supplémentaires",
                "Aménager et sécuriser les entrées de villages (effet de porte, identité villageoise)",
            ],
            13 => [
                "Étudier la possibilité d’imposer un pourcentage de logements réservés aux revenus modérés dans les futures constructions et les promotions",
                "Proposer aux jeunes, aux familles monoparentales et aux primo-acquérants des terrains à bâtir à prix abordable",
                "Réactiver la « cellule logement » et établir la Déclaration de Politique du Logement fixant les priorités de la mandature en y associant la « Filière Bois Wallonie »",
                "Soutenir et défendre les possibilités de colocations sur le territoire de la Commune",
                "Mettre en œuvre des formules innovantes de mise à disposition de biens publics pour les particuliers et les professionnels (emphytéose, Community Land Trust, …) lors du développement de nouveaux quartiers",
                "Soutenir la création de logements aux étages des commerces",
            ],
            14 => [
                "Relier les espaces verts et créer des coulées vertes",
                "Végétalisation des allées et voiries et création de squares",
                "Réaménagement du parc Saint-François et du parc de la Marm'Aye",
                "Préservation du Fond des Vaulx",
                "Finaliser le Guide communal d'urbanisme (GCU) et le Schéma de Développement communal (SDC)",
                "Réaménager l'avenue du Monument",
                "Entretien des voiries de la commune",
                "Promotion et soutien des campagnes « commune propre »",
                "Rénovation des églises et cimetières",
                "Équipement des villages en aires de jeux",
                "Créer un parc canin en zone urbaine",
                "Poursuivre la politique de développement urbain sur base de l’opération de rénovation urbaine et la mise en œuvre du Plan d’actions triennal opérationnel",
            ],
            15 => [
                "Transformer la ferme Sépul en crèche de 28 places",
                "Transformer le co-accueil Libert en crèche de 14 places",
                "Promouvoir le métier d’accueillante salariée à domicile pour augmenter le nombre d’accueillantes",
            ],
            16 => [
                "Relancer le Conseil Communal des Enfants",
                "Développer le projet 'Apprendre en s'amusant'",
                "Mettre en place un projet pédagogique autour d’un parcours mémorial historique (Verdenne 44)",
            ],
            17 => [
                "Finaliser le skate-park",
                "Promouvoir le logement abordable pour jeunes",
            ],
            18 => [
                "Déployer un e-guichet performant",
                "Digitaliser les archives et documents",
                "Implémenter l'IA dans l'administration",
                "Création d’une bibliothèque virtuelle interne sur l’intranet communal",
                "Accélération de la numérisation des archives et documents",
            ],
        ];
    }
}

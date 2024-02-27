<?php

namespace App\Factory;

use App\DataFixtures\EtatFixtures;
use App\DataFixtures\PlaceFixtures;
use App\Entity\Etat;
use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Service\DateService;
use Faker\Factory;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Event>
 *
 * @method        Event|Proxy                     create(array|callable $attributes = [])
 * @method static Event|Proxy                     createOne(array $attributes = [])
 * @method static Event|Proxy                     find(object|array|mixed $criteria)
 * @method static Event|Proxy                     findOrCreate(array $attributes)
 * @method static Event|Proxy                     first(string $sortedField = 'id')
 * @method static Event|Proxy                     last(string $sortedField = 'id')
 * @method static Event|Proxy                     random(array $attributes = [])
 * @method static Event|Proxy                     randomOrCreate(array $attributes = [])
 * @method static EventRepository|RepositoryProxy repository()
 * @method static Event[]|Proxy[]                 all()
 * @method static Event[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Event[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Event[]|Proxy[]                 findBy(array $attributes)
 * @method static Event[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Event[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class EventFactory extends ModelFactory
{

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(private DateService $dateService)
    {
        parent::__construct();
        $this->dateService = $dateService;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        $faker = Factory::create('fr_FR');
        $now = new \DateTime();
        $activityNames = [
            'Randonnée en montagne',
            'Séance de méditation',
            'Atelier d\'art floral',
            'Cours de poterie',
            'Soirée jeux de société',
            'Conférence sur l\'histoire de l\'art',
            'Tour en montgolfière',
            'Atelier de dégustation de vin',
            'Visite guidée du vieux quartier',
            'Tour en kayak sur la rivière',
            'Cours de danse salsa',
            'Balade à cheval',
            'Projection de film en plein air',
            'Excursion en bateau',
            'Initiation à la cuisine italienne',
            'Séance de peinture en plein air',
            'Tour en hélicoptère',
            'Journée découverte de la nature',
            'Concert acoustique',
            'Atelier de création de bijoux',
        ];
        // Générer une date de début aléatoire
        $startDatetime = $faker->dateTimeBetween('2023-01-01', '+1 years');

        // Générer une date de clôture un mois avant la date de début
        $limitRegisterDate = clone $startDatetime;
        $limitRegisterDate->modify('-1 month');


        // Vérifier si la date de clôture est dans le passé
        $isClosed = ($limitRegisterDate < $now);

        // Définir l'état en fonction de la probabilité
//        $etat = $faker->randomElement([
//            Etat::CREATED,
//            Etat::OPEN,
//            Etat::CLOSED,
//            Etat::IN_PROGRESS,
//            Etat::PAST,
//            Etat::CANCELLED,
//        ]);

        // Générer une durée aléatoire
        $duration = $faker->numberBetween(10, 600);// entre 10 minutes et 10 heures en minutes

        // Maximum de participants
        $maxRegisterQty = $faker->numberBetween(1, 21);

        if ($this->dateService->isDatePast($startDatetime)){
            $etat = Etat::PAST;
        } elseif ($this->dateService->isDateInProgress($startDatetime, $duration)){
            $etat = Etat::IN_PROGRESS;
        } else {
            $etat = Etat::OPEN or Etat::CREATED or Etat::CANCELLED;
        }

        return [
            'limitRegisterDate' => $limitRegisterDate,
            'maxRegisterQty' => $maxRegisterQty,
            'name' => $faker->randomElement($activityNames),
            'duration'  => $duration,
            'startDatetime' => $startDatetime,
            'etat' => EtatFactory::random(['libelle' => $etat]),
            'place' => PlaceFactory::random(),
            'site' => SiteFactory::random(),
            'organiser' => UserFactory::random(),
            'registeredUser' => UserFactory::randomRange(0, $maxRegisterQty),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            ->afterInstantiate(function(Event $event): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Event::class;
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Entity\Beneficiaire;
use App\Entity\Intervention;
use App\Entity\InterventionType;
use App\Entity\Personnel;
use App\Repository\BeneficiaireRepository;
use App\Repository\InterventionRepository;
use App\Repository\InterventionTypeRepository;
use App\Repository\PersonnelRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Intervention>
 *
 * @method        Intervention|Proxy<Intervention>     create(array|callable $attributes = [])
 * @method static Intervention|Proxy<Intervention>     createOne(array $attributes = [])
 * @method static Intervention|Proxy<Intervention>     find(object|array|mixed $criteria)
 * @method static Intervention|Proxy<Intervention>     findOrCreate(array $attributes)
 * @method static Intervention|Proxy<Intervention>     first(string $sortedField = 'id')
 * @method static Intervention|Proxy<Intervention>     last(string $sortedField = 'id')
 * @method static Intervention|Proxy<Intervention>     random(array $attributes = [])
 * @method static Intervention|Proxy<Intervention>     randomOrCreate(array $attributes = [])
 * @method static InterventionRepository|ProxyRepositoryDecorator repository()
 * @method static Intervention[]|Proxy<Intervention>[] all()
 * @method static Intervention[]|Proxy<Intervention>[] createMany(int $number, array|callable $attributes = [])
 * @method static Intervention[]|Proxy<Intervention>[] createSequence(iterable|callable $sequence)
 * @method static Intervention[]|Proxy<Intervention>[] findBy(array $attributes)
 * @method static Intervention[]|Proxy<Intervention>[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Intervention[]|Proxy<Intervention>[] randomSet(int $number, array $attributes = [])
 */
final class InterventionFactory extends PersistentProxyObjectFactory
{
    public function __construct(
        private readonly InterventionTypeRepository $interventionTypeRepository,
        private readonly PersonnelRepository $personnelRepository,
        private readonly BeneficiaireRepository $beneficiaireRepository,
    )
    {
    }

    public static function class(): string
    {
        return Intervention::class;
    }

    protected function defaults(): array|callable
    {
        $now = new \DateTime();
        $dateFacture = self::faker()->dateTimeBetween(new \DateTime('2012-01-01'), $now);

        $types = $this->interventionTypeRepository->findAll();
        $personnel = $this->personnelRepository->findAll();
        $beneficiaires = $this->beneficiaireRepository->findAll();

        return [
            'beneficiaire' => self::faker()->randomElement($beneficiaires),
            'dateFacture' => $dateFacture,
            'dateRealise' => min($now, self::faker()->dateTimeBetween($dateFacture, new \DateTime('now'))),
            'divers' => self::faker()->text(200),
            'extraitNo' => self::faker()->text(20),
            'type' => self::faker()->randomElement($types),
            'montantPaye' => self::faker()->numberBetween(1, 500),
            'montantRealise' => self::faker()->boolean(90),
            'personnel' => self::faker()->randomElement($personnel),
            'pieceNo' => self::faker()->text(20),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Intervention $intervention): void {})
        ;
    }
}

<?php

namespace App\Tests\Factory;

use App\Entity\Beneficiaire;
use App\Entity\Personnel;
use App\Repository\BeneficiaireRepository;
use App\Repository\PersonnelRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Beneficiaire>
 *
 * @method        Beneficiaire|Proxy<Beneficiaire>                               create(array|callable $attributes = [])
 * @method static Beneficiaire|Proxy<Beneficiaire>                               createOne(array $attributes = [])
 * @method static Beneficiaire|Proxy<Beneficiaire>                               find(object|array|mixed $criteria)
 * @method static Beneficiaire|Proxy<Beneficiaire>                               findOrCreate(array $attributes)
 * @method static Beneficiaire|Proxy<Beneficiaire>                               first(string $sortedField = 'id')
 * @method static Beneficiaire|Proxy<Beneficiaire>                               last(string $sortedField = 'id')
 * @method static Beneficiaire|Proxy<Beneficiaire>                               random(array $attributes = [])
 * @method static Beneficiaire|Proxy<Beneficiaire>                               randomOrCreate(array $attributes = [])
 * @method static BeneficiaireRepository|ProxyRepositoryDecorator repository()
 * @method static Beneficiaire[]|Proxy<Beneficiaire> []                          all()
 * @method static Beneficiaire[]|Proxy<Beneficiaire> [] createMany(int $number, array|callable $attributes = [])
 * @method static Beneficiaire[]|Proxy<Beneficiaire> [] createSequence(iterable|callable $sequence)
 * @method static Beneficiaire[]|Proxy<Beneficiaire> [] findBy(array $attributes)
 * @method static Beneficiaire[]|Proxy<Beneficiaire> [] randomRange(int $min, int $max, array $attributes = [])
 * @method static Beneficiaire[]|Proxy<Beneficiaire> [] randomSet(int $number, array $attributes = [])
 */
final class BeneficiaireFactory extends PersistentProxyObjectFactory
{
    public function __construct(private readonly PersonnelRepository $personnelRepository)
    {
    }

    public static function class(): string
    {
        return Beneficiaire::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $personnel = $this->personnelRepository->findAll();

        return [
            'nomPrenom' => self::faker()->name(),
            'personnel' => self::faker()->randomElement($personnel),
            'dateNaissance' => self::faker()->dateTimeBetween('-67 years', '-5 days'),
            'lien' => self::faker()->randomElement($this->getLiens()),
            'dateLunette' => null,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this// ->afterInstantiate(function(Beneficiaire $beneficiaire): void {})
            ;
    }

    private function getLiens()
    {
        return [
            'Cohabitant légal',
            'Veuve',
            'compagne',
            'compagnon',
            'conjoint',
            'conjointe',
            'enfant',
            'femme',
            'fille',
            'fils',
            'mari',
            'pensionné',
            'pensionnée',
            'soeur agent décédé',
            'veuf agent',
            'épouse',
            'époux',
        ];
    }
}

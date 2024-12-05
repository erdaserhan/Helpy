<?php

namespace App\Tests\Factory;

use App\Entity\Personnel;
use App\Repository\PersonnelRepository;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Personnel>
 *
 * @method        Personnel|Proxy<Personnel>     create(array|callable $attributes = [])
 * @method static Personnel|Proxy<Personnel>     createOne(array $attributes = [])
 * @method static Personnel|Proxy<Personnel>     find(object|array|mixed $criteria)
 * @method static Personnel|Proxy<Personnel>     findOrCreate(array $attributes)
 * @method static Personnel|Proxy<Personnel>     first(string $sortedField = 'id')
 * @method static Personnel|Proxy<Personnel>     last(string $sortedField = 'id')
 * @method static Personnel|Proxy<Personnel>     random(array $attributes = [])
 * @method static Personnel|Proxy<Personnel>     randomOrCreate(array $attributes = [])
 * @method static PersonnelRepository|ProxyRepositoryDecorator repository()
 * @method static Personnel[]|Proxy<Personnel>[] all()
 * @method static Personnel[]|Proxy<Personnel>[] createMany(int $number, array|callable $attributes = [])
 * @method static Personnel[]|Proxy<Personnel>[] createSequence(iterable|callable $sequence)
 * @method static Personnel[]|Proxy<Personnel>[] findBy(array $attributes)
 * @method static Personnel[]|Proxy<Personnel>[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Personnel[]|Proxy<Personnel>[] randomSet(int $number, array $attributes = [])
 */
final class PersonnelFactory extends PersistentProxyObjectFactory
{
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Personnel::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'compteBanque' => self::faker()->iban('BE'),
            'fournisseur' => self::faker()->boolean(),
            'nom' => self::faker()->lastName(),
            'prenom' => self::faker()->firstName(),
            'soldeSmap' => self::faker()->randomNumber(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Personnel $personnel): void {})
        ;
    }
}

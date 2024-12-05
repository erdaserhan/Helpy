<?php

namespace App\DataFixtures;

use App\Entity\InterventionType;
use App\Entity\User;
use App\Tests\Factory\BeneficiaireFactory;
use App\Tests\Factory\InterventionFactory;
use App\Tests\Factory\PersonnelFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Zenstruck\Foundry\Test\Factories;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getUsers() as $username => $isAdmin) {
            $roles = ['ROLE_USER'];

            if ($isAdmin) {
                $roles[] = 'ROLE_ADMIN';
            }

            $manager->persist((new User())->setUsername($username)->setRoles($roles));
        }

        foreach ($this->getInterventionTypes() as $type) {
            $manager->persist((new InterventionType())->setName($type));
        }


        PersonnelFactory::createMany(117);
        BeneficiaireFactory::createMany(186);
        InterventionFactory::createMany(1165);
    }

    /**
     * @return string[]
     */
    private function getInterventionTypes(): array
    {
        return [
            'Culture',
            'Dépistage',
            'Divers',
            'Fête des enfants',
            'Lunettes - Lentilles',
            'Lunettes - Montures',
            'Lunettes - Verres',
            'Pack Santé',
            'Pack Sport et Culture',
            'Vacances',
            'Vaccin',
        ];
    }

    /**
     * @return array<string, bool>
     */
    private function getUsers(): array
    {
        return [
            'daras' => true,
            'delhougne' => true,
            'manderlier' => true,
            'amouhzi' => true,
            'dodion' => true,
            'laenen' => false,
            'pardo' => false,
            'e.erdas' => true,
        ];
    }
}

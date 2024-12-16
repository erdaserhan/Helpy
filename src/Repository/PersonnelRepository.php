<?php

namespace App\Repository;

use App\Entity\Personnel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<Personnel>
 */
class PersonnelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personnel::class);
    }

    /** @return array<int, Personnel> */
    public function searchByName(string $search): array
    {
        $results = $this->createQueryBuilder('p')
            ->where('p.nom LIKE :search')
            ->orWhere('p.prenom LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult(Query::HYDRATE_OBJECT);

        return $results instanceof \Traversable ? iterator_to_array($results) : (array) $results;
    }
}

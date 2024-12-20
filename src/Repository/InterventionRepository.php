<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Beneficiaire;
use App\Entity\Intervention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Intervention>
 */
class InterventionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intervention::class);
    }

    /**
     * @param array<string, mixed> $queryParameters
     */
    public function getQueryBuilderFromSearch(array $queryParameters): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('i');

        foreach ($queryParameters as $name => $value) {
            $queryBuilder->andWhere(sprintf('i.%s = :%s', $name, $name))
                ->setParameter($name, $value);
        }

        return $queryBuilder;
    }

    /**
     * @return array{'minDateFacture': string, 'totalRemboursements': float}
     */
    public function getInterventionsByLunettes(int|Beneficiaire $beneficiaire): array
    {
        $threeYearsAgo = (new \DateTime())->sub(new \DateInterval('P3Y'))->setTime(0, 0);

        /** @var array{'minDateFacture': string, 'totalRemboursements': float} $data */
        $data = $this->createQueryBuilder('i')
            ->select(
                'min(i.dateFacture) as minDateFacture',
                'sum(i.montantPaye) AS totalRemboursements'
            )
            ->join('i.type', 'interventionType')
            ->join('i.beneficiaire', 'beneficiaire')
            ->andWhere('i.beneficiaire = :beneficiaire')
            ->andWhere("interventionType.name LIKE :lunette")
            ->andWhere('i.dateFacture >= :threeYearsAgo')
            ->setParameter('beneficiaire', $beneficiaire)
            ->setParameter('lunette', 'Lunette%')
            ->setParameter('threeYearsAgo', $threeYearsAgo)
            ->getQuery()
            ->getSingleResult()
            ;

        return $data;
    }


    public function getTotalRemboursementsVacancesByYear(int|Beneficiaire $beneficiaire, int $year): float
    {
        /** @var float $sum */
        $sum = $this->createQueryBuilder('i')
            ->select(
                'sum(i.montantPaye)'
            )
            ->join('i.type', 'interventionType')
            ->join('i.beneficiaire', 'beneficiaire')
            ->andWhere('i.beneficiaire = :beneficiaire')
            ->andWhere('i.dateFacture BETWEEN :yearStart AND :yearEnd')
            ->andWhere("interventionType.name = :vacances")
            ->andWhere("beneficiaire.lien IN (:liens)")
            ->setParameter('beneficiaire', $beneficiaire)
            ->setParameter('vacances', 'Vacances')
            ->setParameter('liens', ['fils', 'fille'])
            ->setParameter('yearStart', $year.'-01-01')
            ->setParameter('yearEnd', $year.'-12-31 23:59:59')
            ->getQuery()
            ->getSingleScalarResult() ?? 0
            ;

        return $sum;
    }

//    public function getInterventionsByYear(mixed $personnel): array
//    {
//        return $this->createQueryBuilder('i')
//            ->select(
//                'interventionType.name',
//                'min(i.dateFacture) as annee',)
//            ->join('i.type', 'interventionType')
//            ->join('i.beneficiaire', 'beneficiaire')
//            ->andWhere('i.personnel = :personnel')
//            ->setParameter('personnel', $personnel)
//            ->groupBy('i.dateFacture')
//            ->orderBy('i.dateFacture', 'DESC')
//            ->getQuery()
//            ->getResult()
//            ;
//    }
    /**
     * @return array{'Annee': int, 'InterventionType': string, 'NomPrenom': string, 'Total': float}
     */
    public function getInterventionsByYear(int $personnelId): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT
                YEAR(i.InterventionDateFacture) AS Annee,
                i.InterventionType AS InterventionType,
                b.BénéficiaireNomPrénom AS NomPrenom,
                SUM(InterventionMontantPayé) AS Total
            FROM
                HelpyInterventions i
            INNER JOIN HelpyBénéficiaires b ON
                b.BénéficiaireNo = i.InterventionBénéficiaireNo
            WHERE
                b.BénéficiairePersonnelNo = :personnel
            GROUP BY
                Annee,
                InterventionType,
                NomPrenom
            ORDER BY
                Annee DESC,
                InterventionType;
            ';

        $resultSet = $conn->executeQuery($sql, ['personnel' => $personnelId]);

        /** @var array{'Annee': int, 'InterventionType': string, 'NomPrenom': string, 'Total': float} $data */
        $data = $resultSet->fetchAllAssociative();

        return $data;
    }

    //    /**
    //     * @return Intervention[] Returns an array of Intervention objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Intervention
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

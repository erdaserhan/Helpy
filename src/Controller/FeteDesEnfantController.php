<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Beneficiaire;
use App\Repository\BeneficiaireRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/fetedesenfants')]
class FeteDesEnfantController extends AbstractController
{
    #[Route(('/'), name: 'app_fetedesenfants_index', methods: ['GET'])]
    // public function __construct(private BeneficiaireRepository $beneficiaireRepository)
    // {
    // }

    public function index(int $annee, EntityManagerInterface $entityManager): Response
    {
        $enfants = $entityManager->beneficiaireRepository->findBy(['lien' => ['fils', 'fille']]);

        $enfants = array_filter($enfants, function (Beneficiaire $enfant) use ($annee) {
            $age = $enfant->getAge($annee . '-01-01');

            return $age !== null && $age >= 0 && $age <= 15;
        });

        return $this->render('feteDesEnfant/index.html.twig', [
            'annee' => $annee,
            'dateDeReference' => $annee . '-01-01',
            'enfants' => $enfants,
        ]);
    }
}

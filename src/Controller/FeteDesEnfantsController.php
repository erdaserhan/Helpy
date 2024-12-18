<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Form\FeteDesEnfantsDateType;
use App\Repository\BeneficiaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/fetedesenfants')]
final class FeteDesEnfantsController extends AbstractController
{
    #[Route(name: 'app_fetedesenfants_index', methods: ['GET'])]
    public function index(BeneficiaireRepository $beneficiaireRepository, Request $request): Response
    {
        $annee = $request->query->get('annee');

        $form = $this->createForm(FeteDesEnfantsDateType::class, options: [
            'method' => 'GET',
        ]);

        $form->handleRequest($request);

        $enfants = $beneficiaireRepository->findBy(['lien' => ['fils', 'fille']]);

        $enfants = array_filter($enfants, function (Beneficiaire $enfant) use ($annee) {
            $age = $enfant->getAge($annee . '-01-01');

            return $age !== null && $age >= 0 && $age <= 15;
        });

        return $this->render('fete_des_enfants/index.html.twig', [
            'annee' => $annee,
            'dateDeReference' => $annee . '-01-01',
            'enfants' => $enfants,
            'form' => $form,
        ]);
    }
}

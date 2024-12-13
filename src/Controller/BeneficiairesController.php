<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Form\BeneficiaireType;
use App\Form\PersonnelType;
use App\Repository\BeneficiaireRepository;
use App\Repository\PersonnelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/beneficiaires')]
final class BeneficiairesController extends AbstractController
{
    #[Route(name: 'app_beneficiaires_index', methods: ['GET'])]
    public function index(
        BeneficiaireRepository $beneficiaireRepository,
        Request $request,
    ): Response {

        $personnel = $request->query->get('personnel');

        $form = $this->createForm(PersonnelType::class, options: [
            'method' => 'GET',
        ]);

        $form->handleRequest($request);

        $beneficiaires = $beneficiaireRepository->findBy(['personnel' => $personnel], ['nomPrenom' => 'ASC']);

        return $this->render('beneficiaires/index.html.twig', [
            'beneficiaires' => $beneficiaires,
            'form' => $form->createView(),
            'personnel' => $personnel,
        ]);
    }

    #[Route('/new', name: 'app_beneficiaires_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        PersonnelRepository $personnelRepository
    ): Response {

        $personnel = $request->query->get('personnel');

        $beneficiaire = new Beneficiaire();
        if ($personnel !== null) {
            $beneficiaire->setPersonnel($personnelRepository->find($personnel));
        }
        $form = $this->createForm(BeneficiaireType::class, $beneficiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($beneficiaire);
            $entityManager->flush();
            $this->addFlash('success', 'Le bénéficiaire a été créé.');

            return $this->redirectToRoute('app_beneficiaires_index', [
                'personnel' => $beneficiaire->getPersonnel()?->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('beneficiaires/new.html.twig', [
            'beneficiaire' => $beneficiaire,
            'form' => $form,
            'personnel' => $beneficiaire->getPersonnel(),
        ]);
    }

    #[Route('/{id}', name: 'app_beneficiaires_show', methods: ['GET'])]
    public function show(Beneficiaire $beneficiaire): Response
    {
        return $this->render('beneficiaires/show.html.twig', [
            'beneficiaire' => $beneficiaire,
            'personnel' => $beneficiaire->getPersonnel(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_beneficiaires_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Beneficiaire $beneficiaire,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(BeneficiaireType::class, $beneficiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le bénéficiaire a été modifié.');

            return $this->redirectToRoute('app_beneficiaires_index', [
                'personnel' => $beneficiaire->getPersonnel()?->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('beneficiaires/edit.html.twig', [
            'beneficiaire' => $beneficiaire,
            'form' => $form,
            'personnel' => $beneficiaire->getPersonnel(),
        ]);
    }

    #[Route('/{id}', name: 'app_beneficiaires_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Beneficiaire $beneficiaire,
        EntityManagerInterface $entityManager
    ): Response {

        if ($this->isCsrfTokenValid('delete' . $beneficiaire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($beneficiaire);
            $entityManager->flush();
            $this->addFlash('success', 'Le bénéficiaire a été supprimé.');
        } else {
            $this->addFlash('failure', "Le bénéficiaire n'a pas été supprimé.");
        }

        return $this->redirectToRoute('app_beneficiaires_index', [
            'personnel' => $beneficiaire->getPersonnel()?->getId(),
        ], Response::HTTP_SEE_OTHER);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Form\BeneficiaireType;
use App\Form\SelectPersonnelType;
use App\Repository\BeneficiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/beneficiaires')]
final class BeneficiairesController extends AbstractController
{
    #[Route(name: 'app_beneficiaires_index', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] ?int $personnel,
        BeneficiaireRepository $beneficiaireRepository,
        Request $request,
    ): Response {

        $form = $this->createForm(SelectPersonnelType::class, options: [
            'method' => 'GET',
        ]);

        $form->handleRequest($request);

        // $selectedPersonnel = $form->getData();
        // if (is_array($selectedPersonnel) && array_key_exists('personnel', $selectedPersonnel)) {
        //     $selectedPersonnel = $selectedPersonnel['personnel'];
        // } else {
        //     $selectedPersonnel = null;
        // }

        $beneficiaires = $beneficiaireRepository->findBy(['personnel' => $personnel], ['nomPrenom' => 'ASC']);

        return $this->render('beneficiaires/index.html.twig', [
            'beneficiaires' => $beneficiaires,
            'form' => $form->createView(),
            'personnel' => $personnel,
        ]);
    }

    #[Route('/new', name: 'app_beneficiaires_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $beneficiaire = new Beneficiaire();
        $form = $this->createForm(BeneficiaireType::class, $beneficiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($beneficiaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_beneficiaires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('beneficiaires/new.html.twig', [
            'beneficiaire' => $beneficiaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_beneficiaires_show', methods: ['GET'])]
    public function show(Beneficiaire $beneficiaire): Response
    {
        return $this->render('beneficiaires/show.html.twig', [
            'beneficiaire' => $beneficiaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_beneficiaires_edit', methods: ['GET', 'POST'])]
    public function edit(
        #[MapQueryParameter] ?int $personnel,
        Request $request,
        Beneficiaire $beneficiaire,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(BeneficiaireType::class, $beneficiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_beneficiaires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('beneficiaires/edit.html.twig', [
            'beneficiaire' => $beneficiaire,
            'form' => $form,
            'personnel' => $personnel,
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
        }

        return $this->redirectToRoute('app_beneficiaires_index', [], Response::HTTP_SEE_OTHER);
    }
}
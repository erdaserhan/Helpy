<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Personnel;
use App\Form\PersonnelType;
use App\Form\SelectPersonnelType;
use App\Repository\PersonnelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/personnels')]
final class PersonnelsController extends AbstractController
{
    #[Route(name: 'app_personnels_index', methods: ['GET'])]
    public function index(
        PersonnelRepository $personnelRepository,
        Request $request
    ): Response {
        $personnel = $request->query->get('personnel');

        $form = $this->createForm(SelectPersonnelType::class, options: [
            'method' => 'GET',
        ]);

        $form->handleRequest($request);

        $personnels = $personnelRepository->findBy(['id' => $personnel], ['nom' => 'ASC']);
        $toutPersonnel = $personnelRepository->findAll();

        return $this->render('personnels/index.html.twig', [
            'personnels' => $personnels,
            'personnel' => $personnel,
            'form' => $form->createView(),
            'toutPersonnel' => $toutPersonnel,
        ]);
    }

    #[Route('/new', name: 'app_personnels_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {

        $personnel = new Personnel();
        $form = $this->createForm(PersonnelType::class, $personnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($personnel);
            $entityManager->flush();
            $this->addFlash('success', "L'agent a été crée.");

            return $this->redirectToRoute('app_personnels_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('personnels/new.html.twig', [
            'personnel' => $personnel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personnels_show', methods: ['GET'])]
    public function show(Personnel $personnel): Response
    {
        return $this->render('personnels/show.html.twig', [
            'personnel' => $personnel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_personnels_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Personnel $personnel,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(PersonnelType::class, $personnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "L'agent a été modifiée.");

            return $this->redirectToRoute('app_personnels_index', [
                'personnel' => $personnel->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('personnels/edit.html.twig', [
            'personnel' => $personnel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personnels_delete', methods: ['POST'])]
    public function delete(Request $request, Personnel $personnel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $personnel->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($personnel);
            $entityManager->flush();
            $this->addFlash('success', 'L\'agent a été supprimée.');
        } else {
            $this->addFlash('failure', "L\agent n'a pas été supprimée.");
        }

        return $this->redirectToRoute('app_personnels_index', [], Response::HTTP_SEE_OTHER);
    }
}

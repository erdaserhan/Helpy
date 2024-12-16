<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Personnel;
use App\Form\PersonnelsType;
use App\Form\PersonnelType;
use App\Repository\PersonnelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
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

        $form = $this->createForm(PersonnelType::class, options: [
            'method' => 'GET',
        ]);

        $form->handleRequest($request);


        $getAllPersonnels =  $personnelRepository->findBy([], ['nom' => 'ASC']);
        $adapter = new ArrayAdapter($getAllPersonnels);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            (int)$request->query->get('page', 1),
            5
        );

        $personnels = $personnelRepository->findBy(['id' => $personnel], ['nom' => 'ASC']);

        return $this->render('personnels/index.html.twig', [
            'personnels' => $personnels,
            'personnel' => $request->query->get('personnel'),
            'form' => $form->createView(),
            'pager' => $pagerfanta,
        ]);
    }

    #[Route('/new', name: 'app_personnels_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {

        $personnel = new Personnel();
        $form = $this->createForm(PersonnelsType::class, $personnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($personnel);
            $entityManager->flush();
            $this->addFlash('success', "L'agent a été créé.");

            return $this->redirectToRoute('app_personnels_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('personnels/new.html.twig', [
            'personnel' => $request->query->get('personnel'),
            'form' => $form
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

        $form = $this->createForm(PersonnelsType::class, $personnel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "L'agent a été modifié.");

            return $this->redirectToRoute('app_personnels_index', [
                'personnel' => $personnel->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('personnels/edit.html.twig', [
            'personnel' => $personnel->getId(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personnels_delete', methods: ['POST'])]
    public function delete(Request $request, Personnel $personnel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $personnel->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($personnel);
            $entityManager->flush();
            $this->addFlash('success', 'L\'agent a été supprimé.');
        } else {
            $this->addFlash('failure', "L\agent n'a pas été supprimé.");
        }

        return $this->redirectToRoute('app_personnels_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Intervention;
use App\Form\InterventionsType;
use App\Form\InterventionFilterType;
use App\Repository\InterventionRepository;
use App\Repository\PersonnelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/interventions')]
final class InterventionsController extends AbstractController
{
    #[Route(name: 'app_interventions_index', methods: ['GET'])]
    public function index(
        InterventionRepository $interventionRepository,
        Request $request,
    ): Response {
        $queryParams = $request->query->all();
        $queryParams = array_intersect_key(
            $queryParams,
            array_flip(['personnel', 'type', 'montantRealise'])
        );
        $queryParams = array_filter($queryParams, static fn (mixed $paramValue): bool => ($paramValue ?? '') !== '');

        $form = $this->createForm(InterventionFilterType::class, options: [
            'method' => 'GET',
        ]);

        $form->handleRequest($request);

        $queryBuilder =  $interventionRepository->getQueryBuilderFromSearch($queryParams);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            (int)$request->query->get('page', 1),
            2
        );

        return $this->render('interventions/index.html.twig', [
            'personnel' => $request->query->get('personnel'),
            'form' => $form->createView(),
            'pager' => $pagerfanta,
        ]);
    }

    #[Route('/new', name: 'app_interventions_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        PersonnelRepository $personnelRepository
    ): Response {

        $personnel = $request->query->get('personnel');

        $intervention = new Intervention();
        if ($personnel !== null) {
            $intervention->setPersonnel($personnelRepository->find($personnel));
        }
        $form = $this->createForm(InterventionsType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($intervention);
            $entityManager->flush();
            $this->addFlash('success', "L'intervention a été créée.");

            return $this->redirectToRoute('app_interventions_index', [
                'personnel' => $intervention->getPersonnel()?->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('interventions/new.html.twig', [
            'intervention' => $intervention,
            'form' => $form,
            'personnel' => $intervention->getPersonnel(),
        ]);
    }

    #[Route('/{id}', name: 'app_interventions_show', methods: ['GET'])]
    public function show(Intervention $intervention): Response
    {
        return $this->render('interventions/show.html.twig', [
            'intervention' => $intervention,
            'personnel' => $intervention->getPersonnel(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_interventions_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Intervention $intervention,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(InterventionsType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "L'intervention a été modifiée.");

            return $this->redirectToRoute('app_interventions_index', [
                'personnel' => $intervention->getPersonnel()?->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('interventions/edit.html.twig', [
            'intervention' => $intervention,
            'form' => $form,
            'personnel' => $intervention->getPersonnel(),
        ]);
    }

    #[Route('/{id}', name: 'app_interventions_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Intervention $intervention,
        EntityManagerInterface $entityManager
    ): Response {

        if ($this->isCsrfTokenValid('delete' . $intervention->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($intervention);
            $entityManager->flush();
            $this->addFlash('success', 'L\'intervention a été supprimée.');
        } else {
            $this->addFlash('failure', "L\intervention n'a pas été supprimée.");
        }

        return $this->redirectToRoute('app_interventions_index', [
            'personnel' => $intervention->getPersonnel()?->getId(),
        ], Response::HTTP_SEE_OTHER);
    }
}

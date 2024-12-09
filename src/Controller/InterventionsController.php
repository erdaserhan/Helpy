<?php

namespace App\Controller;

use App\Entity\Intervention;
use App\Form\InterventionsType;
use App\Form\SelectPersonnelType;
use App\Repository\InterventionRepository;
use App\Repository\PersonnelRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        Request $request
    ): Response
    {

        $personnel = $request->query->get('personnel');

        $form = $this->createForm(SelectPersonnelType::class, options: [
            'method' => 'GET',
        ]);

        $form->handleRequest($request);

        $interventions = $interventionRepository->findBy(['personnel' => $personnel], ['id' => 'ASC']);

        return $this->render('interventions/index.html.twig', [
            'interventions' => $interventions,
            'personnel' => $personnel,
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'app_interventions_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        PersonnelRepository $personnelRepository
        ): Response
    {

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
            $this->addFlash('success', "L'intervention a été crée.");

            return $this->redirectToRoute('app_interventions_index', [], Response::HTTP_SEE_OTHER);
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
        ]);
    }

    #[Route('/{id}/edit', name: 'app_interventions_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Intervention $intervention, 
        EntityManagerInterface $entityManager
        ): Response
    {
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
    public function delete(Request $request, Intervention $intervention, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$intervention->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($intervention);
            $entityManager->flush();
            $this->addFlash('success', 'L\'intervention a été supprimée.');
        }else {
            $this->addFlash('failure', "L\intervention n'a pas été supprimée.");
        }

        return $this->redirectToRoute('app_interventions_index', [], Response::HTTP_SEE_OTHER);
    }
}

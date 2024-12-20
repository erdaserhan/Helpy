<?php

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Entity\Intervention;
use App\Form\ConsultationFilterType;
use App\Repository\BeneficiaireRepository;
use App\Repository\InterventionRepository;
use App\Repository\PersonnelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConsultationController extends AbstractController
{
    #[Route('/consultation', name: 'app_consultation')]
    public function index(
        Request $request,
        InterventionRepository $interventionRepository,
        BeneficiaireRepository $beneficiaireRepository,
        PersonnelRepository $personnelRepository,
    ): Response {

        $personnelId = $request->query->get('personnel');
        $annee = $request->query->get('annee', date('Y'));

        $form = $this->createForm(ConsultationFilterType::class, options: [
            'method' => 'GET',
        ]);

        $form->handleRequest($request);

        $personnel = $personnelRepository->find($personnelId);

        $soldesLunettes = $personnel?->getBeneficiaires()->map(
            function (Beneficiaire $beneficiaire) use ($interventionRepository) {
                $donneesLunettes = $interventionRepository->getInterventionsByLunettes($beneficiaire);
                return [
                    'beneficiaire' => $beneficiaire,
                    'totalRemboursements' => $donneesLunettes['totalRemboursements'],
                    'dateFinValidite' => (
                        new \DateTime($donneesLunettes['minDateFacture']))
                            ->add(new \DateInterval('P3Y'))->setTime(0, 0),
                ];
            }
        ) ?? new ArrayCollection();

        $soldesVacances = $personnel?->getBeneficiaires()->filter(
            function (Beneficiaire $beneficiaire) use ($annee): bool {
                if (!in_array($beneficiaire->getLien(), ['fils', 'fille'], true)) {
                    return false;
                }
                if ($beneficiaire->getDateNaissance() === null) {
                    return true;
                }
                return $beneficiaire->getAge($annee.'-01-01') <= 25
                    && $beneficiaire->getDateNaissance()->format('Y') <= $annee;
            }
        )->map(
            function (Beneficiaire $beneficiaire) use ($interventionRepository, $annee): array {
                $age = $beneficiaire->getAge($annee.'-01-01');
                $max = ($age < 14) ? Intervention::MAX_REMBOURSEMENT_VACANCES_ENFANT : Intervention::MAX_REMBOURSEMENT_VACANCES_ADO;
                return [
                    'beneficiaire' => $beneficiaire,
                    'totalRemboursements' => $interventionRepository->getTotalRemboursementsVacancesByYear($beneficiaire, (int)$annee),
                    'max' => $max,
                ];
            }
        ) ?? new ArrayCollection();

        $interventionsAnnuelles = $interventionRepository->getInterventionsByYear((int) $personnelId);

        return $this->render('consultation/index.html.twig', [
            'form' => $form->createView(),
            'personnel' => $personnelId,
            'maxRemboursementLunettes' => Intervention::MAX_REMBOURSEMENT_LUNETTES,
            'soldesLunettes' => $soldesLunettes,
            'soldesVacances' => $soldesVacances,
            'interventionsAnnuelles' => $interventionsAnnuelles,
            'annee' => $annee
        ]);
    }
}

<?php

namespace App\Twig\Runtime;

use App\Repository\InterventionRepository;
use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private readonly InterventionRepository $interventionRepository)
    {
    }

    public function sumInterventionsLunettes(int $beneficiaire): ?float
    {
        return $this->interventionRepository->getInterventionsByLunettes($beneficiaire);
    }

    public function sumInterventionsVacances(int $beneficiaire): ?float
    {
        return $this->interventionRepository->getInterventionsByVacances($beneficiaire);
    }
}
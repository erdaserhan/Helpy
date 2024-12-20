<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\AppExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('sum_interventions_lunettes', [AppExtensionRuntime::class, 'sumInterventionsLunettes']),

            new TwigFunction('sum_interventions_vacances', [AppExtensionRuntime::class, 'sumInterventionsVacances']),
        ];
    }
}
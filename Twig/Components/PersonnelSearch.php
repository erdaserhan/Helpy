<?php

namespace App\Twig\Components;

use App\Entity\Personnel;
use App\Repository\PersonnelRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class PersonnelSearch
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    #[LiveProp]
    public string $test = 'test prop';

    public function __construct(private PersonnelRepository $personnelRepository)
    {
    }

    #[LiveAction]
    public function testAction(): void
    {
        dd($this->query);
    }

    /**
     * @return array<Personnel>
     */
    public function getPersonnels(): array
    {
        if ($this->query === '') {
            return [];
        }

        return $this->personnelRepository->searchByName($this->query);
    }
}

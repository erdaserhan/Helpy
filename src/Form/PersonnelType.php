<?php

namespace App\Form;

use App\Entity\Personnel;
use App\Repository\PersonnelRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonnelType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Personnel::class,
            'placeholder' => "Chercher un membre du personnel",
            'autocomplete' => true,
            'required' => false,
            'choice_label' => function (Personnel $personnel) {
                return $personnel->getNom() . ' ' . $personnel->getPrenom();
            },
            'query_builder' => function (PersonnelRepository $pr) {
                return $pr->createQueryBuilder('p')
                    ->orderBy('p.nom', 'ASC');
            },
            'attr' => [
                'aria-label' => 'Chercher un membre du personnel',
            ],
            'row_attr' => [
                'class' => 'input-group',
            ],
            'csrf_protection' => false
        ]);
    }

    public function getParent(): ?string
    {
        return EntityType::class;
    }
}

<?php

namespace App\Form;

use App\Entity\Personnel;
use App\Repository\PersonnelRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectPersonnelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('personnel', EntityType::class, [
                'class' => Personnel::class,
                'placeholder' => "Choix d'une personne",
                'autocomplete' => true,
                'choice_label' => function (Personnel $personnel) {
                    return $personnel->getNom() . ' ' . $personnel->getPrenom();
                },
                'query_builder' => function (PersonnelRepository $pr) {
                    return $pr->createQueryBuilder('p')
                        ->orderBy('p.nom', 'ASC');
                },
                'attr' => [
                    'aria-label' => 'Chercher pour un personnel',
                ],
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'csrf_field_name' => 'test',
            'csrf_token_id'   => 'post_item',
        ]);
    }
}
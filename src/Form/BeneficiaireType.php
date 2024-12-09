<?php

namespace App\Form;

use App\Entity\Beneficiaire;
use App\Entity\Personnel;
use App\Repository\PersonnelRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BeneficiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPrenom')
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
            ])
            ->add('lien')
            ->add('dateLunette', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('personnel', EntityType::class, [
                'class' => Personnel::class,
                'choice_label' => function (Personnel $personnel) {
                    return $personnel->getNom() . ' ' . $personnel->getPrenom();
                },
                'query_builder' => function (PersonnelRepository $pr) {
                    return $pr->createQueryBuilder('p')
                        ->orderBy('p.nom', 'ASC');
                },
                'autocomplete' => true,
                'placeholder' => "Choix d'une personne",
                'attr' => [
                    'aria-label' => 'Chercher un personnel',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Beneficiaire::class,
        ]);
    }
}

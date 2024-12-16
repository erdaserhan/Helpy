<?php

namespace App\Form;

use App\Entity\Beneficiaire;
use App\Entity\Intervention;
use App\Entity\InterventionType;
use App\Entity\Personnel;
use App\Repository\BeneficiaireRepository;
use App\Repository\PersonnelRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                'placeholder' => "Chercher un membre du personnel",
                'attr' => [
                    'aria-label' => 'Chercher un membre du personnel',
                ],
            ])
            ->add('beneficiaire', EntityType::class, [
                'class' => Beneficiaire::class,
                'choice_label' => 'nomPrenom',
                'query_builder' => function (BeneficiaireRepository $br) {
                    return $br->createQueryBuilder('b')
                        ->orderBy('b.nomPrenom', 'ASC');
                },
            ])
            ->add('type', EntityType::class, [
                'class' => InterventionType::class,
                'choice_label' => 'name',
            ])
            ->add('dateRealise', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('montantPaye')
            ->add('dateFacture', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
            ])
            ->add('montantRealise')
            ->add('divers')
            ->add('pieceNo')
            ->add('extraitNo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class,
        ]);
    }
}

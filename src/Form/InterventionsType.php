<?php

namespace App\Form;

use App\Entity\Beneficiaire;
use App\Entity\Intervention;
use App\Entity\InterventionType;
use App\Entity\Personnel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montantPaye')
            ->add('dateFacture', null, [
                'widget' => 'single_text',
            ])
            ->add('montantRealise')
            ->add('dateRealise', null, [
                'widget' => 'single_text',
            ])
            ->add('divers')
            ->add('pieceNo')
            ->add('extraitNo')
            ->add('personnel', EntityType::class, [
                'class' => Personnel::class,
                'choice_label' => 'id',
            ])
            ->add('type', EntityType::class, [
                'class' => InterventionType::class,
                'choice_label' => 'name',
            ])
            ->add('beneficiaire', EntityType::class, [
                'class' => Beneficiaire::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class,
        ]);
    }
}

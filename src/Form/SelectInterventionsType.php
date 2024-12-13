<?php

namespace App\Form;

use App\Entity\InterventionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectInterventionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('personnel', PersonnelType::class)
            ->add('type', EntityType::class, [
                'class' => InterventionType::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => "Tous",
                'row_attr' => [
                    'class' => 'input-group',
                ],
            ])
            ->add('montantRealise', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'placeholder' => "Tous",
                'label' => 'Payement réalisé',
                'row_attr' => [
                    'class' => 'input-group',
                ],
                'required' => false
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

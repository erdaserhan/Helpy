<?php

namespace App\Form;

use App\Entity\Personnel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Iban;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class PersonnelsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Cette valeur ne doit pas être vide.'
                    ]),
            ]])
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Cette valeur ne doit pas être vide.'
                    ]),
                ]
            ])
            ->add('fournisseur')
            ->add('compteBanque', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un numéro IBAN.'
                    ]),
                    new Iban([
                        'message' => 'L\'IBAN que vous avez saisi n\'est pas valide.'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Entrez le numéro IBAN'
                    ]
                ])
            ->add('soldeSmap', NumberType::class, [
                'constraints' => [
                    new Type('numeric'),
                    new GreaterThanOrEqual(0),
                    new LessThanOrEqual(1000000)
                ],
                'scale' => 2,
                'attr' => [
                    'min' => 0,
                    'step' => 0.01
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personnel::class,
        ]);
    }
}

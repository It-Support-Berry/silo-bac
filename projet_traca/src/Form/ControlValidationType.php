<?php

namespace App\Form;

use App\Entity\Controle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControlValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('silo' , TextType::class, [
                'attr' => [
                    'readonly' => true,
                ]
            ])

            ->add('cuve', TextType::class, [
                'attr' => [
                    'readonly' => true,
                ]
            ])

            ->add('codejde', TextType::class, [
                'attr' => [
                    'readonly' => true,
                ]
            ])

            ->add('matiere', TextType::class, [
                'attr' => [
                    'readonly' => true,
                ]
            ])

            ->add('lot', TextType::class, [
                'attr' => [
                    'readonly' => true,
                ]
            ])

            
            ->add('atelier', TextType::class, [
                'attr' => [
                    'readonly' => true,
                ]
            ])

            
            ->add('etatdebut' , CheckboxType::class,[
                'required'=>false, 
                'label'     => 'Débuter',
            ])

            ->add('etatfin' , CheckboxType::class,[
                'required'=>false, 
                'label'     => 'Clôturer',
            ])
           
            ->add('date', DateType::class, [
                'attr' => [
                    'readonly' => true,
                ],
                'widget' => 'single_text', 
                'format' => 'yyyy-MM-dd', 
                'required'=>false, 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Controle::class,
        ]);
    }
}

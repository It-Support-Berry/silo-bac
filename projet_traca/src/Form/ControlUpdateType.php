<?php

namespace App\Form;

use App\Entity\Controle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControlUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('silo', TextType::class, [
                'attr' => [
                    'readonly' => true,
                ]
            ])

            ->add('cuve', TextType::class, [
                'attr' => [
                    'readonly' => true,
                ]
            ])

            ->add('codejde' , TextType::class, [
                'attr' => [
                    'readonly' => true,
                ]
            ])

            ->add('matiere' , TextType::class, [
                'attr' => [
                    'readonly' => true,
                ]
            ])

            ->add('lot')

            ->add('etatfin' , CheckboxType::class,[
                'data' => false,
                'required'=>false, 
                'label'     => 'Clôturer',
            ])
           
            ->add('date', DateType::class, array(
                'widget' => 'single_text', 
                'format' => 'yyyy-MM-dd', 
                'required'=>false
            ))
            ->add('atelier', TextType::class, [
                'attr' => [
                    'readonly' => true,
                ]
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

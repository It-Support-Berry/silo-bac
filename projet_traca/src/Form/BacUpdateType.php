<?php

namespace App\Form;

use App\Entity\Bac;
use App\Requete\ConfigRequete;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BacUpdateType extends ConfigRequete
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('numerobac', TextType::class, ['attr' => ['readonly' => true]])

            ->add('localisation', ChoiceType::class, [
                'placeholder' =>'Selecionnez une localisation',
                'choices' => $this->getLocalisation()
            ])

            ->add('matiere', TextType::class, ['attr' => ['readonly' => true]])

            ->add('codejde', TextType::class, ['attr' => ['readonly' => true,]])

            ->add('lot1')
            
            ->add('quantite1')

            ->add('lot2', TextType::class,['required'=>false])
          
            ->add('quantite2')

            ->add('date', DateType::class, array(
                'widget' => 'single_text', 
                'format' => 'yyyy-MM-dd'
            ))

            ->add('cariste', TextType::class)

            ->add('commentaire')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bac::class,
        ]);
    }
}

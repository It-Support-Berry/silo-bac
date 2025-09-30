<?php

namespace App\Form;

use App\Entity\Archives;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArchiveBacType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numerobac')
            ->add('atelier')
            ->add('matiere')
            ->add('codejde')
            ->add('lot1')
            ->add('quantite1')
            ->add('lot2')
            ->add('quantite2')
            ->add('nettoyage')
            ->add('date')
            ->add('etat')
            ->add('commentaire')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Archives::class,
        ]);
    }
}

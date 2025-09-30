<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('user', EntityType::class,[
                'class' => User::class, 
                'choice_label' => function (User $user) {
                    return $user->getPrenom() . ' ' . $user->getNom();
                },
                'choice_value' => 'id',
                'label'     => 'Utilisateurs',
                'placeholder' => 'Sélectionner un utilisateur'
            ])

            
            ->add('roles', ChoiceType::class,
            [ 
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Chef d\'équipe'    =>'ROLE_CHEF',
                    'Réceptionniste' => 'ROLE_RECEPTIONNISTE',
                    'Autre' => 'ROLE_AUTRE',
                ],
                'label'     => 'Rôles',
                'placeholder' => 'Sélectionner un rôle',
                'multiple' => false, 
                'expanded' => false,
                'required' => false
            ])

            ->add('active' , ChoiceType::class,[
                'choices' => [
                    'Activer le compte' => true,
                    'Désactiver le compte'    => false,
                ],
                'placeholder' => null,
                'multiple' => false, 
                'expanded' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            //'data_class' => User::class,
        ]);
    }
}

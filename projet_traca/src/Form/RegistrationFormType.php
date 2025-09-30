<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')

            ->add('prenom',  TextType::class ,[
                'label' => 'Prénom',
            ])

            ->add('username' , TextType::class ,[
                'label' => 'Matricule',
            ])

            ->add('email')

            ->add('roles', ChoiceType::class,
            [   'label' => 'Rôle',
                'choices' => [
                    'Chef d\'equipe'    =>'ROLE_CHEF',
                    'Réceptionniste' => 'ROLE_RECEPTIONNISTE',
                    'Autre' => 'ROLE_AUTRE',
                ],
                'multiple' => false, 
                'expanded' => false,
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'Nouveau mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Répéter le mot de passe',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractère',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],


                'first_options'  => ['label' => 'Mot de Passe'],
                'second_options' => ['label' => 'Confirmer Mot de passe'],
            ])
        ;

        $builder->get('roles')->addModelTransformer(new CallbackTransformer(
            function($rolesArray){
                return count($rolesArray)? $rolesArray[0]: null; 
            },
            function($rolesString){
                return [$rolesString];
            }));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

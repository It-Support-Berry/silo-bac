<?php

namespace App\Form;

use App\Entity\Bac;
use App\Entity\CodejdeBac;
use App\Entity\Localisation;
use App\Entity\MatiereBac;
use App\Repository\CodejdeBacRepository;
use App\Repository\MatiereBacRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;

class BacCreateType extends AbstractType
{

    private $codejdeRepository;
    private $matiereSacRepository;

    public function __construct( CodejdeBacRepository $codejdeRepository, MatiereBacRepository $matiereSacRepository)
    {
        $this->codejdeRepository = $codejdeRepository; 
        $this->matiereSacRepository = $matiereSacRepository; 
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('numerobac', IntegerType::class, [
                'constraints' => [new Positive()],
                'attr' => ['min' => 1]
            ])

            ->add('localisation', EntityType::class, [
                'class'         => Localisation::class,
                'choice_label' =>'localisation',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                    ->orderBy('b.localisation', 'ASC');
                }
            ])
            
            ->add('cariste', TextType::class)
            
            ->add('lot1')

            ->add('quantite1', IntegerType::class, [
                'constraints' => [new Positive()],
                'attr' => ['min' => 1]
            ])

            ->add('lot2', TextType::class,['required'=>false])

            ->add('quantite2', IntegerType::class, [
                'constraints' => [new Positive()],
                'attr' => ['min' => 1],
                'required'=>false
            ])

            ->add('date', DateType::class, array(
                'widget' => 'single_text', 
                'format' => 'yyyy-MM-dd'
            ))

            ->add('matiere', EntityType::class,[
                'class' => MatiereBac::class, 
                'choice_label' => 'nom', 
                'placeholder' => 'Sélectionner une machine'
            ])

            ->add('codejde', ChoiceType::class,[
                'placeholder' =>'Sélectionner une machine avant'
            ] )
            
        ;
       
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    protected function addElementsByCodeJDESac(FormInterface $form,  MatiereBac $matiere = null) {

        $form->add('matiere', EntityType::class, array(
            'required' => true,
            'data' => $matiere,
            'placeholder' => 'Select une matière...',
            'class' => MatiereBac::class
        ));
        
        $codejde = array();

        if ($matiere) {
            
            $codejde = $this->codejdeRepository->createQueryBuilder("c")
                ->where("c.matiere = :matiereId")
                ->setParameter("matiereId", $matiere->getId())
                ->getQuery()
                ->getResult()
            ;
        }

        $form->add('codejde', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Sélectionner une matiere avant ...',
            'class' => CodejdeBac::class,
            'choices' => $codejde
        ));
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        
        $matiere = $this->matiereSacRepository->findOneById($data['matiere']);
        $this->addElementsByCodeJDESac($form, $matiere);
    }

    function onPreSetData(FormEvent $event) {
        $codejde = $event->getData();
        $form = $event->getForm();

        $matiere = $codejde->getMatiere() ? $codejde->getMatiere() : null;
        $this->addElementsByCodeJDESac($form, $matiere);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bac::class,
        ]);
    }
}

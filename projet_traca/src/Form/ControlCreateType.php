<?php

namespace App\Form;

use App\Entity\Codejde;
use App\Entity\Controle;
use App\Entity\Cuve;
use App\Entity\Matiere;
use App\Entity\Silo;
use App\Repository\CodejdeRepository;
use App\Repository\CuveRepository;
use App\Repository\MatiereRepository;
use App\Repository\SiloRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControlCreateType extends AbstractType
{
    private $siloRepository;
    private $cuveRepository;
    private $codejdeRepository;
    private $matiereRepository;

    public function __construct(SiloRepository $siloRepository, CuveRepository $cuveRepository, CodejdeRepository $codejdeRepository, MatiereRepository $matiereRepository)
    {
        $this->siloRepository = $siloRepository;
        $this->cuveRepository = $cuveRepository;
        $this->codejdeRepository = $codejdeRepository; 
        $this->matiereRepository = $matiereRepository; 


    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('atelier' , TextType::class, ['attr' => ['readonly' => true]])

            ->add('lot')
           
            ->add('date', DateType::class, array(
                'widget' => 'single_text', 
                'format' => 'yyyy-MM-dd', 
            ))
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));

    }

    protected function addElementsBySilo(FormInterface $form, Silo $silo = null) {
       
        $form->add('silo', EntityType::class, array(
            'required' => true,
            'data' => $silo,
            'placeholder' => 'Sélectionner un silo...',
            'class' => Silo::class
        ));
        
        $cuves = array();
        
        if ($silo) {
            
            $cuves = $this->cuveRepository->createQueryBuilder("c")
                ->where("c.silo = :siloId")
                ->setParameter("siloId", $silo->getId())
                ->getQuery()
                ->getResult()
            ;
        }
        // Add the Neighborhoods field with the properly data
        $form->add('cuve', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Sélectionner un silo avant ...',
            'class' => Cuve::class,
            'choices' => $cuves
        ));
    }


    protected function addElementsByCodejde(FormInterface $form,  Matiere $matiereSilo = null) {

        $form->add('matiere', EntityType::class, array(
            'required' => true,
            'data' => $matiereSilo,
            'placeholder' => 'Select une matière...',
            'class' => Matiere::class
        ));
        
        $codejdes = array();

        if ($matiereSilo) {
            
            $codejdes = $this->codejdeRepository->createQueryBuilder("c")
                ->where("c.matiere = :matiereId")
                ->setParameter("matiereId", $matiereSilo->getId())
                ->getQuery()
                ->getResult()
            ;
        }

        $form->add('codejde', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Sélectionner une matiere avant ...',
            'class' => Codejde::class,
            'choices' => $codejdes
        ));
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        
        // Search for selected City and convert it into an Entity
        $silo = $this->siloRepository->findOneById($data['silo']);
        $matiereSilo = $this->matiereRepository->findOneById($data['matiere']);

        $this->addElementsBySilo($form, $silo);
        $this->addElementsByCodejde($form, $matiereSilo);
    }

    function onPreSetData(FormEvent $event) {
        $cuve = $event->getData();
        $codejde = $event->getData();
        $form = $event->getForm();

        $silo = $cuve->getSilo() ? $cuve->getSilo() : null;
        $matiereSilo = $codejde->getMatiere() ? $codejde->getMatiere() : null;

        $this->addElementsBySilo($form, $silo);
        $this->addElementsByCodejde($form, $matiereSilo);
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Controle::class,
        ]);
    }
}

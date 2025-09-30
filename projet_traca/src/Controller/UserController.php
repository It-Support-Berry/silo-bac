<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserUpdateType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    /**
     * Méthode pour modifier le role d'un utilisateur
     * @Route("/user/update", name="user_update")
    */
    public function user_update(Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(UserUpdateType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $selectedUser = $data['user'];
            $newRole = $data['roles'];
            $disabled = $data['active'];

            if($newRole === null && $disabled === null){
                $this->addFlash('error', 'Veuillez sélectionner un nouveau rôle ou changer l\'état d\'activation/désactivation avant de continuer.');
                return $this->redirectToRoute("user_update"); 
            }

            if($newRole){
                $selectedUser->setRoles([$newRole]);
            }

            if($disabled === true){
                $selectedUser->setActive(true);
            }
            elseif($disabled === false){
                $selectedUser->setActive(false);
            }
            
            $manager->flush();
            $this->addFlash('success', 'Les informations de ' . $selectedUser->getPrenom(). " ". $selectedUser->getNom() . ' ont été mis à jour');
            return $this->redirectToRoute("user_update");
        }
    
        return $this->render('user/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

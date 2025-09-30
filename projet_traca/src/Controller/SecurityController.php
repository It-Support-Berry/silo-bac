<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityController extends AbstractController
{
    /**
     * Méthode pour ce connecter
     * @Route("/login", name="app_login")
    */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // récupère l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Méthode pour se déconnecter
     * @Route("/logout", name="app_logout")
    */
    public function logout()
    {
        return $this->render('security/login.html.twig');
    }


    /**
     * Méthode pour informer l'utilsateur que sont compte a été désactiver
     * @Route("/verify", name="app_verify")
    */
    public function verify(TokenStorageInterface $tokenStorage)
    {
        $tokenStorage->setToken(null);
        return $this->render('security/verify.html.twig');
    }

}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityContoller extends AbstractController
{
    /**
     * @Route("/nosamislesbetes/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        # Grace a authenticationUtils on peut recuperer le dernier username tapé
        $lastUsername = $authenticationUtils->getLastUsername();

        # Toujours grace a authenticationUtils on peut recuperer les erreurs
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/nosamislesbetes/logout", name="logout")
     */
    public function logout()
    {
        
    }
}

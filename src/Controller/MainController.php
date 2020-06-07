<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class MainController extends AbstractController
{
    public function index(Session $session) {
        if ($session->has('nbreFois'))
            $session->set('nbreFois', $session->get('nbreFois')+1); 
        else
            $session->set('nbreFois', 1);
        return $this->render('nosamislesbetes/index.html.twig',array('nbreFois' => $session->get('nbreFois')));
    }

    public function navigation() {
        $user = $this->getUser(); 
        
        if ($user) {
            $logname = $user->getUsername(); 
            $chaineRoles = '';
            foreach ($user->getRoles() as $role) $chaineRoles .= ' '.$role;

            return $this->render('nosamislesbetes/navigation.html.twig', [
                'logname'=>$logname, 
                'roles' => $chaineRoles
            ]);
        } 
        
        return $this->render('nosamislesbetes/navigation.html.twig', [
            'logname'=> null, 
            'roles' => null
        ]);
    }
}

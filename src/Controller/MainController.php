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
        return $this->render('nosamislesbetes/navigation.html.twig');
    }
    
}

<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneNewType;
use App\Form\PersonneEditType;
use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonneController extends AbstractController
{   
    public function index(PersonneRepository $personneRepository): Response
    {
        return $this->render('personne/index.html.twig', [
            'personnes' => $personneRepository->findAll(),
            'nbPersonnes' => sizeof($personneRepository->findAll()),
        ]);
    }

    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SECRETAIRE');
        $personne = new Personne();
        $form = $this->createForm(PersonneNewType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($personne);
            $entityManager->flush();
            $this->addFlash('success', 'Personne créée !');
            return $this->redirectToRoute('personne_index');
        }

        return $this->render('personne/new.html.twig', [
            'personne' => $personne,
            'form' => $form->createView(),
        ]);
    }

    public function show(Personne $personne): Response
    {
        return $this->render('personne/show.html.twig', [
            'personne' => $personne,
        ]);
    }

    public function edit(Request $request, Personne $personne): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SECRETAIRE');
        $form = $this->createForm(PersonneEditType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Personne modifiée !');
            return $this->redirectToRoute('personne_index');
        }

        return $this->render('personne/edit.html.twig', [
            'personne' => $personne,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request, Personne $personne): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$personne->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($personne);
            $entityManager->flush();
            $this->addFlash('success', 'Personne supprimée !');
        }

        return $this->redirectToRoute('personne_index');
    }
}

<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdresseController extends AbstractController
{
    public function index(AdresseRepository $adresseRepository): Response
    {
        return $this->render('adresse/index.html.twig', [
            'adresses' => $adresseRepository->findAll(),
            'nbAdresses' => sizeof($adresseRepository->findAll()),
        ]);
    }

    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SECRETAIRE');
        $adresse = new Adresse();
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adresse);
            $entityManager->flush();
            $this->addFlash('success', 'Adresse créée !');
            return $this->redirectToRoute('adresse_index');
        }

        return $this->render('adresse/new.html.twig', [
            'adresse' => $adresse,
            'form' => $form->createView(),
        ]);
    }

    public function show(Adresse $adresse, AnimalRepository $animalRepository): Response
    {
        return $this->render('adresse/show.html.twig', [
            'adresse' => $adresse,
            'moyenneAge' => $animalRepository->getMoyenneAge($adresse)
        ]);
    }

    public function edit(Request $request, Adresse $adresse): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SECRETAIRE');
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Adresse modifiée !');
            return $this->redirectToRoute('adresse_index');
        }

        return $this->render('adresse/edit.html.twig', [
            'adresse' => $adresse,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request, Adresse $adresse): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (count($adresse->getPersonnes()) === 0) {
            if ($this->isCsrfTokenValid('delete'.$adresse->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($adresse);
                $entityManager->flush();
                $this->addFlash('success', 'Adresse supprimée !');
            }
        } else {
            $this->addFlash('warning', 'Vous ne pouvez pas supprimer cette adresse car elle est occupée !');
        }
        
        return $this->redirectToRoute('adresse_index');
    }
}

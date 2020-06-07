<?php

namespace App\Controller;

use App\Entity\Espece;
use App\Form\EspeceType;
use App\Repository\EspeceRepository;
use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspeceController extends AbstractController
{

    public function index(EspeceRepository $especeRepository): Response
    {
        return $this->render('espece/index.html.twig', [
            'especes' => $especeRepository->findAll(),
            'nbEspeces' => sizeof($especeRepository->findAll()),
        ]);
    }

    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SECRETAIRE');
        $espece = new Espece();
        $form = $this->createForm(EspeceType::class, $espece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($espece);
            $entityManager->flush();
            $this->addFlash('success', 'Espece créée !');
            return $this->redirectToRoute('espece_index');
        }

        return $this->render('espece/new.html.twig', [
            'espece' => $espece,
            'form' => $form->createView(),
        ]);
    }

    public function show(Espece $espece, PersonneRepository $personneRepository): Response
    {
        return $this->render('espece/show.html.twig', ['espece' => $espece, 'parite' => $personneRepository->getPariteEspece($espece)]);
    }

    public function edit(Request $request, Espece $espece): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SECRETAIRE');
        $form = $this->createForm(EspeceType::class, $espece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Espece modifiée !');
            return $this->redirectToRoute('espece_index');
        }

        return $this->render('espece/edit.html.twig', [
            'espece' => $espece,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request, Espece $espece): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (count($espece->getAnimals()) === 0) {
            if ($this->isCsrfTokenValid('delete'.$espece->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($espece);
                $entityManager->flush();
                $this->addFlash('sucess', 'Espece supprimée');
            }
        } else {
            $this->addFlash('warning', 'Cette espèce n\'est pas encore en voie de disparition !');
        }

        return $this->redirectToRoute('espece_index');
    }
}

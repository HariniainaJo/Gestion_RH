<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Repository\StagiaireRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stagiaires')]
class StagiaireController extends AbstractController
{
    #[Route('/', name: 'app_stagiaire_index')]
    public function index(StagiaireRepository $stagiaireRepository): Response
    {
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stagiaire_new', methods: ['GET', 'POST'])]
    public function new(\Symfony\Component\HttpFoundation\Request $request, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $stagiaire = new Stagiaire();
        $form = $this->createForm(\App\Form\StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stagiaire);
            $entityManager->flush();
            return $this->redirectToRoute('app_stagiaire_index');
        }

        return $this->render('stagiaire/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}/edit', name: 'app_stagiaire_edit', methods: ['GET', 'POST'])]
    public function edit(\Symfony\Component\HttpFoundation\Request $request, Stagiaire $stagiaire, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(\App\Form\StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_stagiaire_index');
        }

        return $this->render('stagiaire/edit.html.twig', [
            'stagiaire' => $stagiaire,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_stagiaire_delete', methods: ['POST'])]
    public function delete(\Symfony\Component\HttpFoundation\Request $request, Stagiaire $stagiaire, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stagiaire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($stagiaire);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_stagiaire_index');
    }
}


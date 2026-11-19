<?php

namespace App\Controller;

use App\Entity\Conge;
use App\Repository\CongeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/conges')]
class CongeController extends AbstractController
{
    #[Route('/', name: 'app_conge_index')]
    public function index(CongeRepository $congeRepository): Response
    {
        return $this->render('conge/index.html.twig', [
            'conges' => $congeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_conge_new', methods: ['GET', 'POST'])]
    public function new(\Symfony\Component\HttpFoundation\Request $request, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $conge = new Conge();
        $form = $this->createForm(\App\Form\CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($conge);
            $entityManager->flush();
            return $this->redirectToRoute('app_conge_index');
        }

        return $this->render('conge/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/approve', name: 'app_conge_approve', methods: ['POST'])]
    public function approve(Conge $conge, \Doctrine\ORM\EntityManagerInterface $entityManager, \App\Service\NotificationService $notifService): Response
    {
        $conge->setStatut('APPROUVE');
        $conge->setDateValidation(new \DateTime());
        $entityManager->flush();
        
        if ($conge->getEmploye()->getEmail()) {
            $notifService->sendLeaveNotification($conge->getEmploye()->getEmail(), $conge->getEmploye()->getNom(), 'APPROUVÉE');
        }

        return $this->redirectToRoute('app_conge_index');
    }

    #[Route('/{id}/refuse', name: 'app_conge_refuse', methods: ['POST'])]
    public function refuse(Conge $conge, \Doctrine\ORM\EntityManagerInterface $entityManager, \App\Service\NotificationService $notifService): Response
    {
        $conge->setStatut('REFUSE');
        $conge->setDateValidation(new \DateTime());
        $entityManager->flush();

        if ($conge->getEmploye()->getEmail()) {
            $notifService->sendLeaveNotification($conge->getEmploye()->getEmail(), $conge->getEmploye()->getNom(), 'REFUSÉE');
        }

        return $this->redirectToRoute('app_conge_index');
    }

    #[Route('/{id}/edit', name: 'app_conge_edit', methods: ['GET', 'POST'])]
    public function edit(\Symfony\Component\HttpFoundation\Request $request, Conge $conge, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(\App\Form\CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_conge_index');
        }

        return $this->render('conge/edit.html.twig', [
            'conge' => $conge,
            'form' => $form->createView(),
        ]);
    }
}


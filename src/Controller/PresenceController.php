<?php

namespace App\Controller;

use App\Entity\PresenceRh;
use App\Form\PresenceRhType;
use App\Repository\PresenceRhRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/presences')]
class PresenceController extends AbstractController
{
    #[Route('/', name: 'app_presence_index', methods: ['GET'])]
    public function index(PresenceRhRepository $presenceRhRepository): Response
    {
        return $this->render('presence/index.html.twig', [
            'presences' => $presenceRhRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_presence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $presence = new PresenceRh();
        $form = $this->createForm(PresenceRhType::class, $presence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($presence);
            $entityManager->flush();

            return $this->redirectToRoute('app_presence_index');
        }

        return $this->render('presence/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_presence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PresenceRh $presence, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PresenceRhType::class, $presence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_presence_index');
        }

        return $this->render('presence/edit.html.twig', [
            'presence' => $presence,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/justify', name: 'app_presence_justify', methods: ['POST'])]
    public function justify(PresenceRh $presence, EntityManagerInterface $entityManager): Response
    {
        $presence->setJustifie(true);
        $entityManager->flush();
        return $this->redirectToRoute('app_presence_index');
    }
}

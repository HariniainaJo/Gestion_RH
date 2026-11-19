<?php

namespace App\Controller;

use App\Repository\DirectionRepository;
use App\Repository\ServiceRepository;
use App\Repository\PosteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

#[Route('/structure')]
class StructureController extends AbstractController
{
    #[Route('/', name: 'app_structure_index')]
    public function index(DirectionRepository $directionRepo, ServiceRepository $serviceRepo, PosteRepository $posteRepo): Response
    {
        return $this->render('structure/index.html.twig', [
            'directions' => $directionRepo->findAll(),
            'services' => $serviceRepo->findAll(),
            'postes' => $posteRepo->findAll(),
        ]);
    }

    #[Route('/direction/new', name: 'app_direction_new', methods: ['GET', 'POST'])]
    public function newDirection(Request $request, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $direction = new \App\Entity\Direction();
        $form = $this->createForm(\App\Form\DirectionType::class, $direction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($direction);
            $entityManager->flush();
            return $this->redirectToRoute('app_structure_index');
        }

        return $this->render('structure/new_direction.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/service/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function newService(Request $request, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $service = new \App\Entity\Service();
        $form = $this->createForm(\App\Form\ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($service);
            $entityManager->flush();
            return $this->redirectToRoute('app_structure_index');
        }

        return $this->render('structure/new_service.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/poste/new', name: 'app_poste_new', methods: ['GET', 'POST'])]
    public function newPoste(Request $request, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $poste = new \App\Entity\Poste();
        $form = $this->createForm(\App\Form\PosteType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($poste);
            $entityManager->flush();
            return $this->redirectToRoute('app_structure_index');
        }

        return $this->render('structure/new_poste.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/direction/{id}/edit', name: 'app_direction_edit', methods: ['GET', 'POST'])]
    public function editDirection(Request $request, \App\Entity\Direction $direction, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(\App\Form\DirectionType::class, $direction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_structure_index');
        }

        return $this->render('structure/new_direction.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true
        ]);
    }

    #[Route('/service/{id}/edit', name: 'app_service_edit', methods: ['GET', 'POST'])]
    public function editService(Request $request, \App\Entity\Service $service, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(\App\Form\ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_structure_index');
        }

        return $this->render('structure/new_service.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true
        ]);
    }

    #[Route('/poste/{id}/edit', name: 'app_poste_edit', methods: ['GET', 'POST'])]
    public function editPoste(Request $request, \App\Entity\Poste $poste, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(\App\Form\PosteType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_structure_index');
        }

        return $this->render('structure/new_poste.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true
        ]);
    }
}


<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Form\GroupeType;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/groupes')]
class GroupeController extends AbstractController
{
    #[Route('/', name: 'app_groupe_index', methods: ['GET'])]
    public function index(GroupeRepository $groupeRepository): Response
    {
        return $this->render('groupe/index.html.twig', [
            'groupes' => $groupeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_groupe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $groupe = new Groupe();
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($groupe);
            $entityManager->flush();

            return $this->redirectToRoute('app_groupe_index');
        }

        return $this->render('groupe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_groupe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Groupe $groupe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_groupe_index');
        }

        return $this->render('groupe/edit.html.twig', [
            'groupe' => $groupe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_groupe_show', methods: ['GET'])]
    public function show(Groupe $groupe, \App\Repository\EmployeRepository $employeRepo): Response
    {
        return $this->render('groupe/show.html.twig', [
            'groupe' => $groupe,
            'all_employes' => $employeRepo->findBy(['statut' => 'Actif']),
        ]);
    }

    #[Route('/{id}/add-member', name: 'app_groupe_add_member', methods: ['POST'])]
    public function addMember(Request $request, Groupe $groupe, \App\Repository\EmployeRepository $employeRepo, EntityManagerInterface $entityManager): Response
    {
        $employeId = $request->request->get('employe_id');
        $employe = $employeRepo->find($employeId);

        if ($employe) {
            $groupe->addEmploye($employe);
            $entityManager->flush();
            $this->addFlash('success', 'Membre ajouté au groupe.');
        }

        return $this->redirectToRoute('app_groupe_show', ['id' => $groupe->getId()]);
    }

    #[Route('/{id}/remove-member/{employe_id}', name: 'app_groupe_remove_member', methods: ['POST'])]
    public function removeMember(Groupe $groupe, int $employe_id, \App\Repository\EmployeRepository $employeRepo, EntityManagerInterface $entityManager): Response
    {
        $employe = $employeRepo->find($employe_id);

        if ($employe) {
            $groupe->removeEmploye($employe);
            $entityManager->flush();
            $this->addFlash('success', 'Membre retiré du groupe.');
        }

        return $this->redirectToRoute('app_groupe_show', ['id' => $groupe->getId()]);
    }
}


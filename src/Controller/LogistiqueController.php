<?php

namespace App\Controller;

use App\Repository\NavetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/logistique')]
class LogistiqueController extends AbstractController
{
    #[Route('/navettes', name: 'app_logistique_navettes')]
    public function navettes(NavetteRepository $navetteRepository): Response
    {
        return $this->render('logistique/navettes.html.twig', [
            'navettes' => $navetteRepository->findAll(),
        ]);
    }

    #[Route('/navette/new', name: 'app_navette_new', methods: ['GET', 'POST'])]
    public function newNavette(\Symfony\Component\HttpFoundation\Request $request, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $navette = new \App\Entity\Navette();
        $form = $this->createForm(\App\Form\NavetteType::class, $navette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($navette);
            $entityManager->flush();
            return $this->redirectToRoute('app_logistique_navettes');
        }

        return $this->render('logistique/new_navette.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/axe/new', name: 'app_axe_new', methods: ['GET', 'POST'])]
    public function newAxe(\Symfony\Component\HttpFoundation\Request $request, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $axe = new \App\Entity\AxeNavette();
        $form = $this->createForm(\App\Form\AxeNavetteType::class, $axe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($axe);
            $entityManager->flush();
            return $this->redirectToRoute('app_logistique_navettes');
        }

        return $this->render('logistique/new_axe.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/navette/{id}', name: 'app_navette_show', methods: ['GET'])]
    public function showNavette(\App\Entity\Navette $navette): Response
    {
        return $this->render('logistique/show_navette.html.twig', [
            'navette' => $navette,
        ]);
    }
    #[Route('/navette/{id}/add-employe', name: 'app_navette_add_employe', methods: ['POST'])]
    public function addEmployeToNavette(\Symfony\Component\HttpFoundation\Request $request, \App\Entity\Navette $navette, \Doctrine\ORM\EntityManagerInterface $entityManager, \App\Repository\EmployeRepository $employeRepo): Response
    {
        $employeId = $request->request->get('employe_id');
        $employe = $employeRepo->find($employeId);

        if ($employe && !$navette->getEmployes()->contains($employe)) {
            if (count($navette->getEmployes()) < $navette->getCapacite()) {
                $navette->addEmploye($employe);
                $entityManager->flush();
                $this->addFlash('success', 'Employé ajouté à la navette avec succès.');
            } else {
                $this->addFlash('error', 'La navette est pleine.');
            }
        } else {
             $this->addFlash('error', 'Employé introuvable ou déjà inscrit.');
        }

        return $this->redirectToRoute('app_navette_show', ['id' => $navette->getId()]);
    }

    #[Route('/navette/{id}/remove-employe/{employeId}', name: 'app_navette_remove_employe', methods: ['POST'])]
    public function removeEmployeFromNavette(\App\Entity\Navette $navette, int $employeId, \Doctrine\ORM\EntityManagerInterface $entityManager, \App\Repository\EmployeRepository $employeRepo): Response
    {
        $employe = $employeRepo->find($employeId);

        if ($employe && $navette->getEmployes()->contains($employe)) {
            $navette->removeEmploye($employe);
            $entityManager->flush();
            $this->addFlash('success', 'Employé retiré de la navette.');
        }

        return $this->redirectToRoute('app_navette_show', ['id' => $navette->getId()]);
    }

    #[Route('/api/search-employe', name: 'api_search_employe', methods: ['GET'])]
    public function searchEmploye(\Symfony\Component\HttpFoundation\Request $request, \App\Repository\EmployeRepository $employeRepo): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $query = $request->query->get('q');
        $employes = $employeRepo->createQueryBuilder('e')
            ->where('e.nom LIKE :query OR e.prenom LIKE :query OR e.id LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        $results = array_map(function($employe) {
            return [
                'id' => $employe->getId(),
                'text' => $employe->getNom() . ' ' . $employe->getPrenom() . ' (#' . $employe->getId() . ')'
            ];
        }, $employes);

        return new \Symfony\Component\HttpFoundation\JsonResponse(['results' => $results]);
    }

}




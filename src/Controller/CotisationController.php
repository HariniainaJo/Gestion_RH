<?php

namespace App\Controller;

use App\Entity\Cotisation;
use App\Form\CotisationType;
use App\Repository\CotisationRepository;
use App\Repository\IrsaBaremeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/finance/cotisations')]
class CotisationController extends AbstractController
{
    #[Route('/', name: 'app_cotisation_index', methods: ['GET'])]
    public function index(CotisationRepository $cotisationRepository, IrsaBaremeRepository $irsaBaremeRepository): Response
    {
        return $this->render('finance/cotisation/index.html.twig', [
            'cotisations' => $cotisationRepository->findAll(),
            'tranches' => $irsaBaremeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cotisation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cotisation = new Cotisation();
        $form = $this->createForm(CotisationType::class, $cotisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cotisation);
            $entityManager->flush();

            $this->addFlash('success', 'Cotisation créée avec succès.');

            return $this->redirectToRoute('app_cotisation_index');
        }

        return $this->render('finance/cotisation/form.html.twig', [
            'form' => $form->createView(),
            'is_edit' => false,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cotisation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cotisation $cotisation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CotisationType::class, $cotisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Cotisation mise à jour.');

            return $this->redirectToRoute('app_cotisation_index');
        }

        return $this->render('finance/cotisation/form.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true,
        ]);
    }

    #[Route('/{id}', name: 'app_cotisation_delete', methods: ['POST'])]
    public function delete(Request $request, Cotisation $cotisation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_cotisation_'.$cotisation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cotisation);
            $entityManager->flush();

            $this->addFlash('success', 'Cotisation supprimée.');
        }

        return $this->redirectToRoute('app_cotisation_index');
    }
}


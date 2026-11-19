<?php

namespace App\Controller;

use App\Entity\IrsaBareme;
use App\Form\IrsaBaremeType;
use App\Repository\IrsaBaremeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/finance/irsa')]
class IrsaBaremeController extends AbstractController
{
    #[Route('/', name: 'app_irsa_index', methods: ['GET'])]
    public function index(IrsaBaremeRepository $repository): Response
    {
        return $this->render('finance/irsa/index.html.twig', [
            'tranches' => $repository->findBy([], ['salaireMin' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'app_irsa_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tranche = new IrsaBareme();
        $form = $this->createForm(IrsaBaremeType::class, $tranche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tranche);
            $entityManager->flush();

            $this->addFlash('success', 'Tranche IRSA créée avec succès.');

            return $this->redirectToRoute('app_irsa_index');
        }

        return $this->render('finance/irsa/form.html.twig', [
            'form' => $form->createView(),
            'is_edit' => false,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_irsa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IrsaBareme $tranche, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IrsaBaremeType::class, $tranche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Tranche IRSA mise à jour.');

            return $this->redirectToRoute('app_irsa_index');
        }

        return $this->render('finance/irsa/form.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true,
        ]);
    }

    #[Route('/{id}', name: 'app_irsa_delete', methods: ['POST'])]
    public function delete(Request $request, IrsaBareme $tranche, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_irsa_'.$tranche->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tranche);
            $entityManager->flush();

            $this->addFlash('success', 'Tranche IRSA supprimée.');
        }

        return $this->redirectToRoute('app_irsa_index');
    }
}


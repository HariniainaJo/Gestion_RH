<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Employe;
use App\Entity\Recrutement;
use App\Form\CandidatType;
use App\Form\RecrutementType;
use App\Repository\CandidatRepository;
use App\Repository\RecrutementRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recrutement')]
class RecrutementController extends AbstractController
{
    #[Route('/', name: 'app_recrutement_index', methods: ['GET'])]
    public function index(RecrutementRepository $recrutementRepository): Response
    {
        return $this->render('recrutement/index.html.twig', [
            'recrutements' => $recrutementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recrutement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recrutement = new Recrutement();
        $form = $this->createForm(RecrutementType::class, $recrutement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recrutement);
            $entityManager->flush();

            return $this->redirectToRoute('app_recrutement_index');
        }

        return $this->render('recrutement/new.html.twig', [
            'recrutement' => $recrutement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_recrutement_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Recrutement $recrutement, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $candidat = new Candidat();
        $candidat->setRecrutement($recrutement);
        $candidat->setStatut('En attente'); // Force status on creation
        $form = $this->createForm(CandidatType::class, $candidat);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $cvFile = $form->get('cvFile')->getData();
                if ($cvFile) {
                    $candidat->setCvPath($fileUploader->upload($cvFile));
                }

                // Final check before database write
                if (!$candidat->getStatut()) {
                    $candidat->setStatut('En attente');
                }
                
                // Convert empty string to null for situationMatrimoniale (ENUM compatibility)
                if ($candidat->getSituationMatrimoniale() === '') {
                    $candidat->setSituationMatrimoniale(null);
                }

                $entityManager->persist($candidat);
                $entityManager->flush();

                $this->addFlash('success', "La candidature de " . strtoupper($candidat->getNom()) . " a été enregistrée.");
                return $this->redirectToRoute('app_recrutement_show', ['id' => $recrutement->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('error', "Erreur : " . $e->getMessage());
            }
        }




        return $this->render('recrutement/show.html.twig', [
            'recrutement' => $recrutement,
            'form' => $form->createView(),
            'candidats' => $recrutement->getCandidats(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recrutement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recrutement $recrutement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecrutementType::class, $recrutement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recrutement_index');
        }

        return $this->render('recrutement/edit.html.twig', [
            'recrutement' => $recrutement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/candidat/{id}/status/{status}', name: 'app_candidat_status', methods: ['POST'])]
    public function changeCandidatStatus(Request $request, Candidat $candidat, string $status, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('change-status' . $candidat->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', "Jeton de sécurité invalide.");
            return $this->redirectToRoute('app_recrutement_show', ['id' => $candidat->getRecrutement()->getId()]);
        }

        $validStatuses = ['En attente', 'Présélectionné', 'Accepté', 'Refusé', 'Embauché'];
        if (in_array($status, $validStatuses)) {
            try {
                $candidat->setStatut($status);
                $entityManager->flush();
                $this->addFlash('success', "Le statut du candidat {$candidat->getNom()} a été mis à jour : {$status}");
            } catch (\Exception $e) {
                $this->addFlash('error', "Erreur lors de la mise à jour : " . $e->getMessage());
            }
        } else {
            $this->addFlash('error', "Statut invalide : " . $status);
        }

        return $this->redirectToRoute('app_recrutement_show', ['id' => $candidat->getRecrutement()->getId()]);
    }

    #[Route('/candidat/{id}/voir', name: 'app_candidat_show_detail', methods: ['GET'])]
    public function candidateShow(Candidat $candidat): Response
    {
        return $this->render('recrutement/candidate_show.html.twig', [
            'candidat' => $candidat,
        ]);
    }

    #[Route('/candidat/{id}/embaucher', name: 'app_candidat_hire', methods: ['GET', 'POST'])]
    public function hireCandidate(Request $request, Candidat $candidat, EntityManagerInterface $entityManager): Response
    {
        if ($candidat->getStatut() !== 'Accepté') {
            $this->addFlash('error', "Seuls les candidats acceptés peuvent être embauchés.");
            return $this->redirectToRoute('app_candidat_show_detail', ['id' => $candidat->getId()]);
        }

        if ($request->isMethod('POST')) {
            try {
                $employe = new Employe();
                $employe->setNom($candidat->getNom());
                $employe->setPrenom($candidat->getPrenom());
                $employe->setEmail($candidat->getEmail());
                $employe->setContact($candidat->getContact());
                $employe->setAdresse($candidat->getAdresse());
                $employe->setDateNaissance($candidat->getDateNaissance());
                $employe->setSituationMatrimoniale($candidat->getSituationMatrimoniale());
                $employe->setPoste($candidat->getRecrutement()->getPoste());
                
                // Set hire date from form
                $hireDate = new \DateTime($request->request->get('dateEmbauche', 'now'));
                $employe->setDateEmbauche($hireDate);
                $employe->setStatut('Actif');
                
                // Copy CV path
                $employe->setCvFichier($candidat->getCvPath());

                $entityManager->persist($employe);
                
                // Update candidate status to say they are hired? 
                // Or just keep it as Accepted. Let's mark as hired in a way.
                // For now, let's just create the employee.
                
                $entityManager->flush();
                
                // Mark candidate as hired
                $candidat->setStatut('Embauché');
                $entityManager->flush();

                $this->addFlash('success', "Le candidat a été transformé en employé avec succès !");
                return $this->redirectToRoute('app_employe_index');
            } catch (\Exception $e) {
                $this->addFlash('error', "Erreur lors de l'embauche : " . $e->getMessage());
            }
        }

        return $this->render('recrutement/candidate_hire.html.twig', [
            'candidat' => $candidat,
        ]);
    }
}

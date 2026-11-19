<?php

namespace App\Controller;

use App\Entity\AvanceSalaire;
use App\Form\AvanceSalaireType;
use App\Repository\AvanceSalaireRepository;
use App\Repository\EmployeRepository;
use App\Repository\KpiRepository;
use App\Repository\PayeRepository;
use App\Repository\PrimeRepository;
use Symfony\Component\Form\FormError;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/finance')]
class FinanceController extends AbstractController
{
    #[Route('/bulletin/{id}', name: 'app_finance_bulletin')]
    public function showBulletin(\App\Entity\Paye $paye, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $mois = $paye->getMois();
        $annee = $paye->getAnnee();

        // Récupérer le total des avances qui ont été déduites pour cet employé et cette période
        $avances = $entityManager->getRepository(AvanceSalaire::class)->findBy([
            'employe' => $paye->getEmploye(),
            'moisDeduction' => $mois,
            'anneeDeduction' => $annee,
            'statut' => 'APPROUVE',
        ]);

        $totalAvance = 0;
        foreach ($avances as $av) {
            $totalAvance += (float) $av->getMontant();
        }

        // Recalcul des cotisations à partir des paramètres actuels (même logique que RhCalculationService)
        $salaireBrut = (float) $paye->getSalaireBrut();
        $cnaps = 0;
        $ostie = 0;
        $irsa = 0;

        $cotisationRepo = $entityManager->getRepository(\App\Entity\Cotisation::class);

        $cnapsConfig = $cotisationRepo->findOneBy(['code' => 'CNAPS']);
        if ($cnapsConfig) {
            $taux = (float) $cnapsConfig->getTaux();
            $plafond = $cnapsConfig->getPlafond() !== null ? (float) $cnapsConfig->getPlafond() : null;
            $cnaps = $salaireBrut * $taux;
            if ($plafond !== null) {
                $cnaps = min($cnaps, $plafond);
            }
        }

        $ostieConfig = $cotisationRepo->findOneBy(['code' => 'OSTIE']);
        if ($ostieConfig) {
            $taux = (float) $ostieConfig->getTaux();
            $ostie = $salaireBrut * $taux;
        }

        // IRSA via barème
        $irsaBaremeRepo = $entityManager->getRepository(\App\Entity\IrsaBareme::class);
        $trancheIrsa = $irsaBaremeRepo->findTrancheForSalaire($salaireBrut);
        if ($trancheIrsa) {
            $tauxIrsa = (float) $trancheIrsa->getTaux();
            $irsa = $salaireBrut * $tauxIrsa;
        }

        return $this->render('finance/bulletin.html.twig', [
            'paye' => $paye,
            'totalAvance' => $totalAvance,
            'cnaps' => $cnaps,
            'ostie' => $ostie,
            'irsa' => $irsa,
        ]);
    }

    #[Route('/paie', name: 'app_finance_paie')]

    public function paie(PayeRepository $payeRepository): Response
    {
        return $this->render('finance/paie.html.twig', [
            'payes' => $payeRepository->findAll(),
        ]);
    }

    #[Route('/paie/generate', name: 'app_finance_paie_gen', methods: ['POST'])]
    public function generatePaie(\App\Service\RhCalculationService $rhService, PayeRepository $payeRepository): Response
    {
        $mois = (int) date('m');
        $annee = (int) date('Y');

        // Empêcher la génération de la paie plus d'une fois pour le même mois/année
        $existingPaye = $payeRepository->findOneBy([
            'mois' => $mois,
            'annee' => $annee,
        ]);

        if ($existingPaye) {
            $this->addFlash('error', 'La paie pour ce mois a déjà été générée.');
            return $this->redirectToRoute('app_finance_paie');
        }

        $rhService->calculateMonthlyPayroll($mois, $annee);

        $this->addFlash('success', 'La paie du mois a été générée avec succès.');
        return $this->redirectToRoute('app_finance_paie');
    }

    #[Route('/paie/preview', name: 'app_finance_paie_preview', methods: ['GET'])]
    public function previewPrime(\App\Repository\EmployeRepository $employeRepo, \App\Repository\PrimeRepository $primeRepo, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $mois = (int) date('m');
        $annee = (int) date('Y');
        $employes = $employeRepo->findBy(['statut' => 'Actif']);
        
        $previews = [];
        
        foreach ($employes as $employe) {
            $totalPrime = 0;
            $primes = $primeRepo->findAll();
            
            foreach ($primes as $prime) {
                if ($prime->isCollectif()) continue;
                $pKpis = $prime->getKpis();
                if ($pKpis->isEmpty()) continue;

                $sumS = 0; $countS = 0;
                foreach ($pKpis as $kpi) {
                    $eval = $entityManager->getRepository(\App\Entity\EmployeKpi::class)->findOneBy([
                        'employe' => $employe, 'kpi' => $kpi, 'mois' => $mois, 'annee' => $annee
                    ]);
                    if ($eval) { $sumS += (float)$eval->getValeur(); $countS++; }
                }

                if ($countS > 0) {
                    $avg = $sumS / $countS;
                    $calc = str_ireplace('Score', (string)$avg, (string)$prime->getFormule());
                    $calc = preg_replace('/[^0-9\.\+\-\*\/\(\)]/', '', $calc);
                    $valP = 0;
                    if (!empty($calc)) { @eval("\$valP = $calc;"); }
                    $totalPrime += min($valP, (float)$prime->getMontantMax());
                }
            }

            // Fetch advances
            $avances = $entityManager->getRepository(\App\Entity\AvanceSalaire::class)->findBy([
                'employe' => $employe,
                'moisDeduction' => $mois,
                'anneeDeduction' => $annee,
                'statut' => 'APPROUVE'
            ]);
            
            $totalAvance = 0;
            foreach ($avances as $av) {
                $totalAvance += (float) $av->getMontant();
            }
            
            $previews[] = [
                'employe' => $employe,
                'salaire' => (float) $employe->getPoste()->getSalaireBase(),
                'primes' => $totalPrime,
                'avances' => $totalAvance
            ];
        }


        return $this->render('finance/paie_preview.html.twig', [
            'previews' => $previews,
            'mois' => $mois,
            'annee' => $annee
        ]);
    }




    #[Route('/performance', name: 'app_finance_perf', methods: ['GET'])]
    public function performance(KpiRepository $kpiRepository, PrimeRepository $primeRepository): Response

    {
        return $this->render('finance/performance.html.twig', [
            'kpis' => $kpiRepository->findAll(),
            'primes' => $primeRepository->findAll(),
        ]);
    }

    #[Route('/kpi/new', name: 'app_kpi_new', methods: ['GET', 'POST'])]
    public function newKpi(\Symfony\Component\HttpFoundation\Request $request, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $kpi = new \App\Entity\Kpi();
        $form = $this->createForm(\App\Form\KpiType::class, $kpi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($kpi);
            $entityManager->flush();
            return $this->redirectToRoute('app_finance_perf');
        }

        return $this->render('finance/new_kpi.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/kpi/{id}/edit', name: 'app_kpi_edit', methods: ['GET', 'POST'])]
    public function editKpi(\Symfony\Component\HttpFoundation\Request $request, \App\Entity\Kpi $kpi, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(\App\Form\KpiType::class, $kpi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_finance_perf');
        }

        return $this->render('finance/new_kpi.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true
        ]);
    }

    #[Route('/prime/new', name: 'app_prime_new', methods: ['GET', 'POST'])]
    public function newPrime(\Symfony\Component\HttpFoundation\Request $request, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $prime = new \App\Entity\Prime();
        $form = $this->createForm(\App\Form\PrimeType::class, $prime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prime);
            $entityManager->flush();
            return $this->redirectToRoute('app_finance_perf');
        }

        return $this->render('finance/new_prime.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/prime/{id}/edit', name: 'app_prime_edit', methods: ['GET', 'POST'])]
    public function editPrime(\Symfony\Component\HttpFoundation\Request $request, \App\Entity\Prime $prime, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(\App\Form\PrimeType::class, $prime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_finance_perf');
        }

        return $this->render('finance/new_prime.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true
        ]);
    }

    #[Route('/evaluation/new', name: 'app_finance_evaluation', methods: ['GET', 'POST'])]

    public function evaluation(\Symfony\Component\HttpFoundation\Request $request, \Doctrine\ORM\EntityManagerInterface $entityManager, \App\Repository\EmployeRepository $employeRepo, KpiRepository $kpiRepo): Response
    {
        $mois = (int) date('m');
        $annee = (int) date('Y');
        
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            
            if (isset($data['evaluations'])) {
                foreach ($data['evaluations'] as $employeId => $kpis) {
                    $employe = $employeRepo->find($employeId);
                    if (!$employe) continue;

                    foreach ($kpis as $kpiId => $valeur) {
                        if ($valeur === '') continue;
                        
                        $kpi = $kpiRepo->find($kpiId);
                        if (!$kpi) continue;

                        $existingEval = $entityManager->getRepository(\App\Entity\EmployeKpi::class)->findOneBy([
                            'employe' => $employe,
                            'kpi' => $kpi,
                            'mois' => $mois,
                            'annee' => $annee
                        ]);

                        if ($existingEval) {
                            $existingEval->setValeur($valeur);
                        } else {
                            $eval = new \App\Entity\EmployeKpi();
                            $eval->setEmploye($employe);
                            $eval->setKpi($kpi);
                            $eval->setMois($mois);
                            $eval->setAnnee($annee);
                            $eval->setValeur($valeur);
                            $entityManager->persist($eval);
                        }
                    }
                }
                $entityManager->flush();
                $this->addFlash('success', 'Évaluations enregistrées avec succès.');
            }
            return $this->redirectToRoute('app_finance_perf');
        }

        return $this->render('finance/evaluation.html.twig', [
            'employes' => $employeRepo->findBy(['statut' => 'Actif']),
            'kpis' => $kpiRepo->findAll(),
            'mois' => $mois,
            'annee' => $annee
        ]);
    }



    #[Route('/avances', name: 'app_finance_avances')]
    public function avanceIndex(AvanceSalaireRepository $avanceRepo): Response
    {
        return $this->render('finance/avances.html.twig', [
            'avances' => $avanceRepo->findAll(),
        ]);
    }

    #[Route('/avances/new', name: 'app_finance_avance_new', methods: ['GET', 'POST'])]
    public function avanceNew(\Symfony\Component\HttpFoundation\Request $request, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $avance = new AvanceSalaire();
        $form = $this->createForm(AvanceSalaireType::class, $avance);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $employe = $avance->getEmploye();
                $moisDeduction = $avance->getMoisDeduction();
                $anneeDeduction = $avance->getAnneeDeduction();

                // Empêcher plus d'une avance pour le même mois/année (EN_ATTENTE ou APPROUVE)
                $existingAvance = $entityManager->getRepository(AvanceSalaire::class)
                    ->createQueryBuilder('a')
                    ->andWhere('a.employe = :employe')
                    ->andWhere('a.moisDeduction = :mois')
                    ->andWhere('a.anneeDeduction = :annee')
                    ->andWhere('a.statut IN (:statuts)')
                    ->setParameter('employe', $employe)
                    ->setParameter('mois', $moisDeduction)
                    ->setParameter('annee', $anneeDeduction)
                    ->setParameter('statuts', ['EN_ATTENTE', 'APPROUVE'])
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getOneOrNullResult();

                if ($existingAvance) {
                    $form->addError(new FormError('Une avance a déjà été demandée pour ce mois pour cet employé.'));
                } else {
                    // Vérifier que le montant ne dépasse pas 50% du salaire de base
                    $salaireBase = $employe && $employe->getPoste() ? (float) $employe->getPoste()->getSalaireBase() : 0;
                    $montant = (float) $avance->getMontant();

                    if ($salaireBase > 0 && $montant > ($salaireBase * 0.5)) {
                        $form->get('montant')->addError(new FormError('Le montant de l\'avance ne peut pas dépasser 50% du salaire de base.'));
                    } else {
                        $entityManager->persist($avance);
                        $entityManager->flush();
                        $this->addFlash('success', 'Demande d\'avance enregistrée.');
                        return $this->redirectToRoute('app_finance_avances');
                    }
                }
            }

            // Formulaire soumis mais avec des erreurs (validation ou règles métier) :
            // renvoyer un statut 422 pour que Turbo traite la réponse comme une réaffichage de formulaire.
            return $this->render('finance/new_avance.html.twig', [
                'form' => $form->createView(),
            ], new Response(null, 422));
        }

        // Affichage initial (GET)
        return $this->render('finance/new_avance.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/avances/{id}/statut/{status}', name: 'app_finance_avance_statut', methods: ['POST'])]
    public function avanceStatut(AvanceSalaire $avance, string $status, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        if (in_array($status, ['APPROUVE', 'REFUSE'])) {
            $avance->setStatut($status);
            $entityManager->flush();
            $this->addFlash('success', 'Statut de l\'avance mis à jour.');
        }

        return $this->redirectToRoute('app_finance_avances');
    }
    #[Route('/avances/{id}/edit', name: 'app_finance_avance_edit', methods: ['GET', 'POST'])]
    public function editAvance(\Symfony\Component\HttpFoundation\Request $request, AvanceSalaire $avance, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvanceSalaireType::class, $avance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Avance mise à jour.');
            return $this->redirectToRoute('app_finance_avances');
        }

        return $this->render('finance/edit_avance.html.twig', [
            'avance' => $avance,
            'form' => $form->createView(),
        ]);
    }
}


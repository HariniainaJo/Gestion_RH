<?php

namespace App\Service;

use App\Entity\Employe;
use App\Entity\EmployePrime;
use App\Entity\Paye;
use App\Repository\EmployeKpiRepository;
use App\Repository\EmployeRepository;
use App\Repository\PrimeKpiRepository;
use App\Repository\PrimeRepository;
use Doctrine\ORM\EntityManagerInterface;

class RhCalculationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmployeRepository $employeRepository,
        private PrimeRepository $primeRepository
    ) {}

    /**
     * Calcule la paie pour un mois donné pour tous les employés actifs
     */
    public function calculateMonthlyPayroll(int $mois, int $annee): void
    {
        $employes = $this->employeRepository->findBy(['statut' => 'Actif']);

        foreach ($employes as $employe) {
            $this->calculateEmployePayroll($employe, $mois, $annee);
        }
    }

    public function calculateEmployePayroll(Employe $employe, int $mois, int $annee): void
    {
        $salaireBase = (float) $employe->getPoste()->getSalaireBase();
        
        // Calcul réel des primes basées sur les KPI et formules
        $totalPrimes = 0;
        $primes = $this->primeRepository->findAll();
        
        foreach ($primes as $prime) {
            if ($prime->isCollectif()) continue; // Skip collective primes for this individual calculation

            $primeKpis = $prime->getKpis();
            if ($primeKpis->isEmpty()) continue;

            $sumScores = 0;
            $countKpis = 0;

            foreach ($primeKpis as $kpi) {
                $eval = $this->entityManager->getRepository(\App\Entity\EmployeKpi::class)->findOneBy([
                    'employe' => $employe,
                    'kpi' => $kpi,
                    'mois' => $mois,
                    'annee' => $annee
                ]);

                if ($eval) {
                    $sumScores += (float) $eval->getValeur();
                    $countKpis++;
                }
            }

            if ($countKpis > 0) {
                $averageScore = $sumScores / $countKpis;
                $formule = $prime->getFormule(); // ex: "Score * 1000"
                
                if ($formule) {
                    // Évaluation très basique de la formule
                    $calcul = str_ireplace('Score', (string)$averageScore, $formule);
                    
                    // Utilisation de eval() sécurisé avec précaution ou simple parser
                    // Pour la démo, on supporte uniquement multiplication/addition simple
                    // On va essayer d'évaluer le résultat numériquement
                    try {
                        // Nettoyage pour sécurité basique
                        $calcul = preg_replace('/[^0-9\.\+\-\*\/\(\)]/', '', $calcul);
                        $valeurPrime = 0;
                        if (!empty($calcul)) {
                            // PHP eval est risqué mais ici on a mergé le score
                            @eval("\$valeurPrime = $calcul;");
                        }
                        
                        // Plafonnement au montant max
                        $valeurPrime = min($valeurPrime, (float)$prime->getMontantMax());
                        $totalPrimes += $valeurPrime;
                    } catch (\Exception $e) {
                        // Erreur de calcul, on ignore cette prime
                    }
                }
            }
        }

        $salaireBrut = $salaireBase + $totalPrimes;

        // --- Calculs des cotisations à partir des paramètres en base ---
        $totalRetenues = 0;
        $cnaps = 0;
        $ostie = 0;
        $irsa = 0;

        $cotisationRepo = $this->entityManager->getRepository(\App\Entity\Cotisation::class);

        // CNAPS
        $cnapsConfig = $cotisationRepo->findOneBy(['code' => 'CNAPS']);
        if ($cnapsConfig) {
            $taux = (float) $cnapsConfig->getTaux();
            $plafond = $cnapsConfig->getPlafond() !== null ? (float) $cnapsConfig->getPlafond() : null;
            $cnaps = $salaireBrut * $taux;
            if ($plafond !== null) {
                $cnaps = min($cnaps, $plafond);
            }
            $totalRetenues += $cnaps;
        }

        // OSTIE / Assurance santé
        $ostieConfig = $cotisationRepo->findOneBy(['code' => 'OSTIE']);
        if ($ostieConfig) {
            $taux = (float) $ostieConfig->getTaux();
            $ostie = $salaireBrut * $taux;
            $totalRetenues += $ostie;
        }

        // IRSA : utilisation du barème par tranches
        $irsaBaremeRepo = $this->entityManager->getRepository(\App\Entity\IrsaBareme::class);
        $trancheIrsa = $irsaBaremeRepo->findTrancheForSalaire($salaireBrut);
        if ($trancheIrsa) {
            $tauxIrsa = (float) $trancheIrsa->getTaux();
            // Choix : le taux s'applique à l'ensemble du salaire brut de la tranche
            $irsa = $salaireBrut * $tauxIrsa;
            $totalRetenues += $irsa;
        }

        // 4. Avances sur salaire
        $avances = $this->entityManager->getRepository(\App\Entity\AvanceSalaire::class)->findBy([
            'employe' => $employe,
            'moisDeduction' => $mois,
            'anneeDeduction' => $annee,
            'statut' => 'APPROUVE'
        ]);
        
        $totalAvance = 0;
        foreach ($avances as $av) {
            $totalAvance += (float) $av->getMontant();
        }
        
        $totalRetenues += $totalAvance;

        $paye = new Paye();
        $paye->setEmploye($employe);
        $paye->setMois($mois);
        $paye->setAnnee($annee);
        $paye->setSalaireBrut((string) $salaireBrut);
        $paye->setTotalPrime((string) $totalPrimes);
        $paye->setRetenue((string) $totalRetenues);
        $paye->setSalaireNet((string) ($salaireBrut - $totalRetenues));
        $paye->setDatePaiement(new \DateTime());

        $this->entityManager->persist($paye);
        $this->entityManager->flush();
    }
}



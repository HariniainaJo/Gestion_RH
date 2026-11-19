<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        \App\Repository\EmployeRepository $employeRepo,
        \App\Repository\CongeRepository $congeRepo,
        \App\Repository\StagiaireRepository $stagiaireRepo,
        \App\Repository\PresenceRhRepository $presenceRepo,
        \App\Repository\DirectionRepository $directionRepo,
        \App\Repository\AvanceSalaireRepository $avanceRepo,
        \App\Repository\PayeRepository $payeRepo,
        EntityManagerInterface $em
    ): Response {
        $today = new \DateTime();
        
        // Activités récentes
        $recent_activities = $em->getRepository(\App\Entity\AuditLog::class)->findBy([], ['dateAction' => 'DESC'], 5);
        
        // Statistique de répartition (services par direction)
        $directions = $directionRepo->findAll();
        $distribution = [];
        foreach ($directions as $dir) {
            $distribution[] = [
                'nom' => $dir->getNomDirection(),
                'count' => count($dir->getServices())
            ];
        }

        // Données pour le graphique d'évolution de la paie (6 derniers mois)
        $payroll_stats = $em->createQuery('
            SELECT p.annee, p.mois, SUM(p.salaireNet) as total
            FROM App\Entity\Paye p
            GROUP BY p.annee, p.mois
            ORDER BY p.annee DESC, p.mois DESC
        ')->setMaxResults(6)->getResult();
        
        // Données pour le graphique des congés par type
        $conge_stats = $em->createQuery('
            SELECT c.typeConge as type, COUNT(c.id) as count
            FROM App\Entity\Conge c
            GROUP BY c.typeConge
        ')->getResult();

        return $this->render('dashboard/index.html.twig', [
            'total_employes' => $employeRepo->count([]),
            'conges_en_attente' => $congeRepo->count(['statut' => 'EN_ATTENTE']),
            'total_stagiaires' => $stagiaireRepo->count([]),
            'absences_today' => $presenceRepo->count(['dateEvenement' => $today, 'typeEvenement' => 'ABSENCE']),
            'retards_today' => $presenceRepo->count(['dateEvenement' => $today, 'typeEvenement' => 'RETARD']),
            'avances_en_attente' => $avanceRepo->count(['statut' => 'EN_ATTENTE']),
            'recent_activities' => $recent_activities,
            'distribution' => $distribution,
            'payroll_stats' => array_reverse($payroll_stats),
            'conge_stats' => $conge_stats
        ]);
    }


}

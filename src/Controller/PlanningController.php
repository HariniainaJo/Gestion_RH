<?php

namespace App\Controller;

use App\Repository\CongeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlanningController extends AbstractController
{
    #[Route('/planning', name: 'app_planning')]
    public function index(): Response
    {
        return $this->render('planning/index.html.twig');
    }

    #[Route('/planning/events', name: 'app_planning_events', methods: ['GET'])]
    public function events(\App\Repository\CongeRepository $congeRepo, \App\Repository\PresenceRhRepository $presenceRepo): JsonResponse
    {
        $conges = $congeRepo->findBy(['statut' => 'APPROUVE']);
        $presences = $presenceRepo->findAll();
        $events = [];

        // Ajout des Congés
        foreach ($conges as $conge) {
            $events[] = [
                'id' => 'conge_' . $conge->getId(),
                'title' => '🏖️ ' . $conge->getEmploye()->getNom() . ' (' . $conge->getTypeConge() . ')',
                'start' => $conge->getDateDebut()->format('Y-m-d'),
                'end' => $conge->getDateFin()->modify('+1 day')->format('Y-m-d'),
                'color' => '#6366f1', // Indigo
                'extendedProps' => ['type' => 'Congé']
            ];
        }

        // Ajout des Absences et Retards
        foreach ($presences as $presence) {
            $isAbsence = $presence->getTypeEvenement() === 'ABSENCE';
            $icon = $isAbsence ? '🔴' : '🕒';
            $color = $isAbsence ? '#ef4444' : '#f59e0b'; // Rouge pour absence, Orange pour retard

            $events[] = [
                'id' => 'presence_' . $presence->getId(),
                'title' => $icon . ' ' . $presence->getEmploye()->getNom() . ' (' . $presence->getTypeEvenement() . ')',
                'start' => $presence->getDateEvenement()->format('Y-m-d'),
                'color' => $color,
                'extendedProps' => ['type' => $presence->getTypeEvenement()]
            ];
        }

        return new JsonResponse($events);
    }
}

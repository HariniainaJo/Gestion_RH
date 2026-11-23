<?php

namespace App\Twig;

use App\Repository\AvanceSalaireRepository;
use App\Repository\CongeRepository;
use App\Repository\PresenceRhRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NotificationExtension extends AbstractExtension
{
    public function __construct(
        private CongeRepository $congeRepo,
        private AvanceSalaireRepository $avanceRepo,
        private PresenceRhRepository $presenceRepo,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('header_notifications', [$this, 'getNotifications']),
        ];
    }

    public function getNotifications(): array
    {
        $items = [];

        foreach ($this->congeRepo->findBy(['statut' => 'EN_ATTENTE'], ['dateDemande' => 'DESC'], 3) as $conge) {
            $employe = $conge->getEmploye();
            $items[] = [
                'type' => 'warning',
                'text' => sprintf('Congé de %s %s en attente de validation', $employe?->getPrenom(), $employe?->getNom()),
                'time' => $this->relativeTime($conge->getDateDemande()),
            ];
        }

        foreach ($this->avanceRepo->findBy(['statut' => 'EN_ATTENTE'], ['dateDemande' => 'DESC'], 3) as $avance) {
            $employe = $avance->getEmploye();
            $items[] = [
                'type' => 'info',
                'text' => sprintf("Demande d'avance de %s %s", $employe?->getPrenom(), $employe?->getNom()),
                'time' => $this->relativeTime($avance->getDateDemande()),
            ];
        }

        foreach ($this->presenceRepo->findBy(['dateEvenement' => new \DateTime(), 'typeEvenement' => 'ABSENCE', 'justifie' => false]) as $presence) {
            $employe = $presence->getEmploye();
            $items[] = [
                'type' => 'danger',
                'text' => sprintf('Absence non justifiée : %s %s', $employe?->getPrenom(), $employe?->getNom()),
                'time' => "Aujourd'hui",
            ];
        }

        return [
            'items' => array_slice($items, 0, 6),
            'count' => count($items),
        ];
    }

    private function relativeTime(?\DateTimeInterface $date): string
    {
        if (!$date) {
            return '';
        }

        $diff = (new \DateTime())->getTimestamp() - $date->getTimestamp();

        if ($diff < 3600) {
            return 'Il y a ' . max(1, intdiv($diff, 60)) . ' min';
        }

        if ($diff < 86400) {
            return 'Il y a ' . intdiv($diff, 3600) . ' h';
        }

        return 'Il y a ' . intdiv($diff, 86400) . ' j';
    }
}

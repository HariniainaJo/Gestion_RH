<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;

class NotificationService
{
    public function __construct(
        private MailerInterface $mailer,
        private LoggerInterface $logger
    ) {}

    public function sendLeaveNotification(string $recipientEmail, string $employeeName, string $status): void
    {
        $this->logger->info("Tentative d'envoi d'email de congé à $recipientEmail pour $employeeName (Statut: $status)");

        $subject = 'Mise à jour de votre demande de congé - Zanova GRH';
        
        $statusColor = $status === 'APPROUVÉE' ? '#10b981' : '#ef4444';
        
        $htmlContent = "
            <div style='font-family: sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px;'>
                <h2 style='color: #6366f1;'>Zanova GRH</h2>
                <p>Bonjour <strong>$employeeName</strong>,</p>
                <p>Votre demande de congé a été traitée par le service RH.</p>
                <div style='background: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                    Statut actuel : <span style='color: $statusColor; font-weight: bold;'>$status</span>
                </div>
                <p>Vous pouvez consulter les détails sur votre tableau de bord.</p>
                <hr style='border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
                <p style='font-size: 0.8rem; color: #64748b;'>Ceci est un message automatique, merci de ne pas y répondre.</p>
            </div>
        ";

        $email = (new Email())
            ->from('djibrilmamitiana@gmail.com')
            ->to($recipientEmail)
            ->subject($subject)
            ->html($htmlContent);

        try {
            $this->mailer->send($email);
            $this->logger->info("Email envoyé avec succès à $recipientEmail");
        } catch (\Exception $e) {
            $this->logger->error("Échec de l'envoi d'email à $recipientEmail. Erreur : " . $e->getMessage());
        }
    }

}

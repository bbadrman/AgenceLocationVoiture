<?php
namespace App\Service;

use App\Entity\Booking;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private string $fromEmail = 'noreply@carlocation.com'
    ) {}

    /**
     * Envoie l'email de confirmation de réservation
     */
    public function sendBookingConfirmation(Booking $booking): void
    {
        $email = (new Email())
            ->from($this->fromEmail)
            ->to($booking->getCustomer()->getEmail())
            ->subject('Confirmation de réservation - ' . $booking->getBookingNumber())
            ->html($this->twig->render('emails/booking_confirmation.html.twig', [
                'booking' => $booking
            ]));

        $this->mailer->send($email);
    }

    /**
     * Envoie l'email d'annulation de réservation
     */
    public function sendBookingCancellation(Booking $booking): void
    {
        $email = (new Email())
            ->from($this->fromEmail)
            ->to($booking->getCustomer()->getEmail())
            ->subject('Annulation de réservation - ' . $booking->getBookingNumber())
            ->html($this->twig->render('emails/booking_cancellation.html.twig', [
                'booking' => $booking
            ]));

        $this->mailer->send($email);
    }
}
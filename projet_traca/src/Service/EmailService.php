<?php

namespace App\Service;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use App\Repository\EmailRepository;

/**
 * Cette classe  est un service qui va gérer l'envoi de mails.
 * MailerInterface  est une interface fournie par symfony pour envoyer des emails, il permet d'utiliser différents transports (SMTP, Sendmail...)
 * EmailRepository  $emailRepository : pour récupérer la liste des emails 
 */
class EmailService 
{
    private $mailer;
    private $emailRepository;

    public function __construct(MailerInterface $mailer, EmailRepository $emailRepository)
    {
        $this->mailer = $mailer;
        $this->emailRepository = $emailRepository;
    }

    /**
     * cette méthode  envoie un mail à tous les utilisateurs concernés
     * @param string $toAdress : l'adresse email du destinataire
     * @param TemplatedEmail|string $template : le template ou le contenu du mail
     * @return void
     */
    public function sendEmail(
        string $subject,
        string $htmlTemplate,
        array $context = [],
    ): void {

        $emailList = $this->emailRepository->findAll();
        $to = array_map(function ($emailEntity) {
            return $emailEntity->getEmail();
        }, $emailList);

        $email = (new TemplatedEmail())
            ->from('noreply@berryglobal.com')
            ->to(...$to)
            ->subject($subject)
            ->htmlTemplate($htmlTemplate)
            ->context($context);

        $this->mailer->send($email);
    }
}

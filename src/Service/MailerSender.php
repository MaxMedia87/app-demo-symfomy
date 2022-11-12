<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerSender
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param User $user
     *
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendWelcomeEmail(User $user): void
    {
        $this->send('email/welcome.html.twig', 'Добро пожаловать на CatCasCar', $user);
    }

    /**
     * @param User $user
     * @param Article[] $articles
     *
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendWeeklyNewsLetterEmail(User $user, array $articles): void
    {
        $this->send(
            'email/weekly-newsletter.html.twig',
            'Еженедельная рассылка статей CatCasCar',
            $user,
            function (TemplatedEmail $templatedEmail) use ($articles) {
                $templatedEmail
                    ->context(['articles' => $articles])
                    ->attach('Опубликовано статей ' . count($articles), 'report_'. date('Y-m-d') . '.txt');
            }
        );
    }

    /**
     * @param string $template
     * @param string $subject
     * @param User $user
     * @param \Closure|null $callback
     *
     * @return void
     * @throws TransportExceptionInterface
     */
    private function send(string $template, string $subject, User $user, \Closure $callback = null): void
    {
        $message = (new TemplatedEmail())
            ->from(new Address('max.sakharov.kos@gmail.com', 'Cat-Cas-Car'))
            ->to(new Address('maksim_saharov@mail.ru', $user->getFirstName()))
            ->subject($subject)
            ->htmlTemplate($template)
        ;

        if (null !== $callback) {
            $callback($message);
        }

        $this->mailer->send($message);
    }
}

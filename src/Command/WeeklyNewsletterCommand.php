<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class WeeklyNewsletterCommand extends Command
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ArticleRepository
     */
    private $articleRepository;
    /**
     * @var MailerInterface
     */
    private $mailer;

    protected function configure(): void
    {
        $this
            ->setName('app:weekly-newsletter')
            ->setDescription('Еженедельная рассылка новостей.')
        ;
    }

    public function __construct(
        string $name = null,
        UserRepository $userRepository,
        ArticleRepository $articleRepository,
        MailerInterface $mailer
    ) {
        parent::__construct($name);

        $this->userRepository = $userRepository;
        $this->articleRepository = $articleRepository;
        $this->mailer = $mailer;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findAllSubscribedUsers();

        $articles = $this->articleRepository->findAllPublishedLastWeek();

        $io = new SymfonyStyle($input, $output);

        if (0 === count($articles)) {
            $io->warning('За последнюю неделю не было новых статей.');

            return Command::SUCCESS;
        }

        $io->progressStart(count($users));

        foreach ($users as $user) {
            $email = (new TemplatedEmail())
                ->from(new Address('max.sakharov.kos@gmail.com', 'Cat-Cas-Car'))
                ->to(new Address('maksim_saharov@mail.ru', $user->getFirstName()))
                ->subject('Еженедельная рассылка статей CatCasCar')
                ->htmlTemplate('email/weekly-newsletter.html.twig')
                ->context(['articles' => $articles])
                ->attach('Опубликовано статей ' . count($articles), 'report_'. date('Y-m-d') . '.txt')
            ;

            $this->mailer->send($email);

            $io->progressAdvance();
            break;
        }

        $io->progressFinish();

        return Command::SUCCESS;
    }
}

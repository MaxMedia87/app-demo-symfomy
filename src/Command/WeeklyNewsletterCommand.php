<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Service\MailerSender;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
     * @var MailerSender
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
        MailerSender $mailer
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
            $this->mailer->sendWeeklyNewsLetterEmail($user, $articles);

            $io->progressAdvance();
            break;
        }

        $io->progressFinish();

        return Command::SUCCESS;
    }
}

<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppUserDeactivateCommand extends Command
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    protected function configure()
    {
        $this
            ->setName('app:user:deactivate')
            ->setDescription('Команда активации/деактивации пользователя')
            ->addArgument('id', InputArgument::REQUIRED, 'ID пользователя')
            ->addOption('reverse', null, InputOption::VALUE_OPTIONAL, 'Флаг активации', true)
        ;
    }

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        parent::__construct(null);

        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = (int) $input->getArgument('id');

        $isActivate = true;

        if (null === $input->getOption('reverse')) {
            $isActivate = false;
        }

        $user = $this->userRepository->find($userId);

        if (null === $user) {
            $output->writeln('Пользователь не найден.');
            exit;
        }

        $user->setIsActive($isActivate);

        $this->em->persist($user);
        $this->em->flush();

        return Command::SUCCESS;
    }
}

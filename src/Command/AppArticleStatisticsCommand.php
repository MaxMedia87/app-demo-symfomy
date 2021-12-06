<?php

declare(strict_types=1);

namespace App\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppArticleStatisticsCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('app:article-statistics')
            ->setDescription('Команда для вывода статистики лайков')
            ->addArgument('slug', InputArgument::REQUIRED, 'Символьный код')
            ->addOption('format', null, InputOption::VALUE_REQUIRED, 'Формат вывода', 'text')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $slug = $input->getArgument('slug');

        $data = [
            'slug' => $slug,
            'title' => ucfirst(str_replace('-', ' ', $slug)),
            'like' => rand(10, 100)
        ];

        switch ($input->getOption('format')) {
            case 'text':
                $io->table(array_keys($data), [$data]);
                break;
            case 'json':
                $io->text(json_encode($data));
                break;
            default;
                throw new Exception('Формат данных не найден');
        }

        return Command::SUCCESS;
    }
}

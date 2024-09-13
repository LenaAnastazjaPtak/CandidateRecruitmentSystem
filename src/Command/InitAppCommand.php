<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:init-app',
    description: 'Initialize the application',
)]
class InitAppCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $commands = [
            ['php', 'bin/console', 'doctrine:migrations:migrate', '--no-interaction'],
            ['php', 'bin/console', 'fos:elastica:populate'],
            ['npm', 'install'],
            ['npm', 'run', 'build'],
            ['php', 'bin/console', 'app:init-db-data'],
        ];

        foreach ($commands as $command) {
            $io->section('Running command: ' . implode(' ', $command));
            $process = new Process($command);
            $process->setTimeout(null);
            $process->run();

            if (!$process->isSuccessful()) {
                $io->error('Command failed: ' . implode(' ', $command));
                return Command::FAILURE;
            }

            $io->success('Command finished successfully: ' . implode(' ', $command));
        }

        $io->success('The application is ready to run!');
        return Command::SUCCESS;
    }
}

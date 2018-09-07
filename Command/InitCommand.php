<?php

namespace Protacon\Bundle\TestToolsBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InitCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();

        // TODO
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        // TODO
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output);

        // TODO
        $io->error('not implemented');

        return null;
    }
}

<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Command;

use Crockerio\SearchEngine\Engine\Statistics;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class StatsCommand extends Command
{
    protected static $defaultName = 'stats';
    
    protected function configure()
    {
        $this
            ->setDescription('Displays statistics about the system.')
            ->setHelp('Displays statistics about the system.');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stats = new Statistics();
        
        $io = new SymfonyStyle($input, $output);
        $io->title('Search Engine Statistics');
        
        $io->table(
            ['Statistic', 'Count'],
            $stats->getStats()
        );
        
        return Command::SUCCESS;
    }
}

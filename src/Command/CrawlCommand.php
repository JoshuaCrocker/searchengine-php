<?php


namespace Crockerio\SearchEngine\Command;


use Crockerio\SearchEngine\Engine\Indexer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlCommand extends Command
{
    protected static $defaultName = 'index';
    
    protected function configure()
    {
        $this
            ->setDescription('Boots the indexer.')
            ->setHelp('Boots the indexer.');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $indexer = new Indexer();
        $indexer->startIndexing();
        
        return Command::SUCCESS;
    }
}

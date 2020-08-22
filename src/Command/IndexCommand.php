<?php


namespace Crockerio\SearchEngine\Command;


use Crockerio\SearchEngine\Crawler\Crawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexCommand extends Command
{
    protected static $defaultName = 'crawl';
    
    protected function configure()
    {
        $this
            ->setDescription('Boots the crawler.')
            ->setHelp('Boots the crawler.');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $crawler = new Crawler();
        $crawler->startCrawling();
        
        return Command::SUCCESS;
    }
}

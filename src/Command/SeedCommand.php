<?php


namespace Crockerio\SearchEngine\Command;


use Crockerio\SearchEngine\Database\Models\Domain;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedCommand extends Command
{
    protected static $defaultName = 'db:seed';
    
    protected function configure()
    {
        $this
            ->setDescription('Inserts example domains into the database.')
            ->setHelp('Inserts example domains into the database.');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $domains = [
            'https://youtube.com',
            'https://facebook.com',
            'https://amazon.com',
            'https://live.com',
            'https://reddit.com',
            'https://zoom.us',
            'https://blogspot.com',
            'https://office.com',
            'https://instagram.com',
            'https://twitch.tv',
            'https://twitter.com',
            'https://microsoft.com',
            'https://worldometers.info',
            'https://stackoverflow.com',
        ];
        
        foreach ($domains as $domain) {
            $d = new Domain();
            $d->domain = $domain;
            $d->last_crawl_time = null;
            $d->last_index_time = null;
            $d->save();
        }
        
        return Command::SUCCESS;
    }
}

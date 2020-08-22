<?php


namespace Crockerio\SearchEngine\Command;


use Crockerio\SearchEngine\Database\Models\Document;
use Crockerio\SearchEngine\Database\Models\Domain;
use Crockerio\SearchEngine\Database\Models\Index;
use Crockerio\SearchEngine\Database\Models\Word;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetCommand extends Command
{
    protected static $defaultName = 'db:reset';
    
    protected function configure()
    {
        $this
            ->setDescription('Empties the database.')
            ->setHelp('Empties the database.');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach (Index::all() as $index) {
            $index->delete();
        }
        
        foreach (Word::all() as $index) {
            $index->delete();
        }
        
        foreach (Document::all() as $index) {
            $index->delete();
        }
        
        foreach (Domain::all() as $index) {
            $index->delete();
        }
        
        return Command::SUCCESS;
    }
}

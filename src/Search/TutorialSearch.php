<?php


namespace Crockerio\SearchEngine\Search;


use Crockerio\SearchEngine\DocumentStore\TutorialDocumentStore;
use Crockerio\SearchEngine\Index\TutorialIndex;
use Crockerio\SearchEngine\Ranker\TutorialRanker;

class TutorialSearch implements ISearch
{
    private $index;
    
    private $store;
    
    private $ranker;
    
    public function __construct()
    {
        $this->index = new TutorialIndex();
        $this->store = new TutorialDocumentStore();
        $this->ranker = new TutorialRanker();
    }
    
    public function search($terms)
    {
        $documents = [];
        
        $arrTerms = $this->extractTerms($terms);
        
        foreach ($arrTerms as $term) {
            $index = $this->index->getDocuments($term);
            
            if ($index != null) {
                usort($index, [$this->ranker, 'rank']);
                
                foreach ($index as $ind) {
                    $documents[] = $this->store->getDocument($ind[0]);
                }
            }
        }
        
        return $documents;
    }
    
    private function extractTerms($terms)
    {
        $terms = strtolower($terms);
        $terms = preg_replace('/\W/i', ' ', $terms);
        $terms = preg_replace('/\s\s+/', ' ', $terms);
        $terms = trim($terms);
        return explode(' ', $terms);
    }
}

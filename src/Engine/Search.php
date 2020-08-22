<?php


namespace Crockerio\SearchEngine\Engine;


use Crockerio\SearchEngine\Database\Models\Word;

class Search
{
    private function _extractTerms($terms)
    {
        $terms = strtolower($terms);
        $terms = preg_replace('/\W/i', ' ', $terms);
        $terms = preg_replace('/\s\s+/', ' ', $terms);
        $terms = trim($terms);
        return explode(' ', $terms);
    }
    
    public function search($terms)
    {
        $allIndices = [];
        
        $arrTerms = $this->_extractTerms($terms);
        
        foreach ($arrTerms as $term) {
            $word = Word::where('word', '=', $term);
            
            if (!$word->count()) {
                continue;
            }
            
            $indices = $word->first()->indices;
            
            if ($indices->count() > 0) {
//                usort($index, [$this->ranker, 'rank']);
                
                foreach ($indices as $ind) {
                    $allIndices[] = $ind;
                }
            }
        }
        
        return $allIndices;
    }
}

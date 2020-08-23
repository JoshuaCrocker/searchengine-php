<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Engine;

use Crockerio\SearchEngine\Database\Models\Word;
use Crockerio\SearchEngine\Engine\Filter\DuplicateFilter;
use Crockerio\SearchEngine\Engine\Filter\OccurrencesRankingFilter;

/**
 * Class Search
 *
 * @author Joshua Crocker
 * @package Crockerio\SearchEngine\Engine
 */
class Search
{
    /**
     * Ranker Instance.
     *
     * @var OccurrencesRankingFilter
     */
    private $ranker;
    
    private $filters;
    
    /**
     * Search constructor.
     */
    public function __construct()
    {
        $this->filters = [
            new DuplicateFilter(),
            new OccurrencesRankingFilter()
        ];
    }
    
    /**
     * Split the search terms into an array of terms.
     *
     * @param $terms string Search terms.
     * @return array Array of Search terms.
     */
    private function _extractTerms($terms)
    {
        $terms = strtolower($terms);
        $terms = preg_replace('/\W/i', ' ', $terms);
        $terms = preg_replace('/\s\s+/', ' ', $terms);
        $terms = trim($terms);
        return explode(' ', $terms);
    }
    
    /**
     * Search the index.
     *
     * @param $terms string Search terms.
     * @return array Search results.
     */
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
                foreach ($indices as $ind) {
                    $allIndices[] = $ind;
                }
            }
        }
    
        foreach ($this->filters as $filter) {
            $allIndices = $filter->filter($allIndices);
        }
    
    
        return $allIndices;
    }
}

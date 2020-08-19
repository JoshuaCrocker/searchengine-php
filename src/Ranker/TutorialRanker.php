<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Ranker;

class TutorialRanker implements IRanker
{
    public function rank($document1, $document2)
    {
        if (!is_array($document1) || !is_array($document2)) {
            throw new Exception('Document(s) not array!');
        }
        
        if (count($document1) != 3 || count($document2) != 3) {
            throw new Exception('Document not correct format!');
        }
        
        if ($document1[1] == $document2[1]) {
            return 0;
        }
        
        if ($document1[1] <= $document2[1]) {
            return 1;
        }
        
        return -1;
    }
}

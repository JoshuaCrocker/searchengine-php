<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Index;

use Crockerio\SearchEngine\DocumentStore\IDocumentStore;

class TutorialIndexer implements IIndexer
{
    /**
     * @var IDocumentStore
     */
    private $store;
    
    private $index;
    
    /**
     * TutorialIndexer constructor.
     * @param IDocumentStore $store
     * @param IIndex $index
     */
    public function __construct(IDocumentStore $store, IIndex $index)
    {
        $this->store = $store;
        $this->index = $index;
    }
    
    /**
     * Retrieve an alphabetical list of the words present in the document text.
     *
     * @param $document
     */
    private function getConcordance($document)
    {
        $words = explode(' ', $document);
        
        $wordCount = [];
        
        foreach ($words as $word) {
            $word = trim($word);
            
            $validUTF8 = mb_check_encoding($word, 'UTF-8');
            
            // For now, skip non-UTF-8 words
            if (!$validUTF8) {
                continue;
            }
            
            if (!isset($wordCount[$word])) {
                $wordCount[$word] = 0;
            }
            
            $wordCount[$word]++;
        }
        
        return $wordCount;
    }
    
    private function cleanDocument($document)
    {
        // Remove HTML from the document.
        $document = strip_tags($document);
        
        // Remove punctuation from the document.
        $document = preg_replace('/\p{P}/', ' ', $document);
        
        // Replace all multi-spaces with one space
        $document = preg_replace('/\s\s+/', ' ', $document);
        
        // Remove non-word characters
        $document = preg_replace('/\W/', ' ', $document);
        
        // Make the document lower case
        $document = strtolower($document);
        
        return $document;
    }
    
    public function index(array $documents)
    {
        if (!is_array($documents)) {
            return false;
        }
        
        foreach ($documents as $document) {
            $id = $this->store->storeDocument($document);
            
            foreach ($document as $subdoc)
            {
                $concordance = $this->getConcordance($this->cleanDocument($subdoc));
    
                foreach ($concordance as $word => $count) {
                    $ind = $this->index->getDocuments($word);
        
                    if (count($ind) == 0) {
                        $this->index->storeDocuments($word, [[$id, $count, 0]]);
                    } else {
                        $ind[] = [$id, $count, 0];
                        $this->index->storeDocuments($word, $ind);
                    }
                }
            }
        }
        
        return true;
    }
}

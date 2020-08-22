<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Index;

use Crockerio\SearchEngine\Database\Models\Index;
use Crockerio\SearchEngine\DocumentStore\IDocumentStore;

class DatabaseIndexer implements IIndexer
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
    
    public function index(array $documents)
    {
        if (!is_array($documents)) {
            return false;
        }
        
        foreach ($documents as $document) {
            $documentId = $this->store->storeDocument($document);
            
            foreach ($document as $subdoc) {
                $concordance = $this->getConcordance($this->cleanDocument($subdoc));
                
                foreach ($concordance as $word => $count) {
                    $index = $this->index->getDocuments($word);
                    
                    if (count($index) == 0) {
                        $ind = new Index();
                    }
                }
            }
        }
    }
}

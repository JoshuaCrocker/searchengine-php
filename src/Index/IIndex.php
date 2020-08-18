<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Index;

interface IIndex
{
    /**
     * Store documents in the index.
     *
     * @param $name
     * @param array $documents
     * @return bool
     */
    public function storeDocuments($name, array $documents): bool;
    
    /**
     * Get documents from the index.
     *
     * @param $name
     * @return array
     */
    public function getDocuments($name): array;
    
    /**
     * Empty the index.
     *
     * @return void
     */
    public function clearIndex();
    
    /**
     * Validate a document from the index.
     *
     * @param array $document
     * @return bool
     */
    public function validateDocument(array $document): bool;
}

<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\DocumentStore;

interface IDocumentStore
{
    public function storeDocument(array $document);
    
    public function getDocument($id);
    
    public function clearDocuments();
}

<?php


namespace Crockerio\SearchEngine\DocumentStore;


interface IDocumentStore
{
    public function storeDocument(array $document);
    
    public function getDocument($id);
    
    public function clearDocuments();
}

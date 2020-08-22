<?php


namespace Crockerio\SearchEngine\Index;


class DatabaseIndex implements IIndex
{
    
    /**
     * @inheritDoc
     */
    public function storeDocuments($name, array $documents): bool
    {
        // TODO: Implement storeDocuments() method.
    }
    
    /**
     * @inheritDoc
     */
    public function getDocuments($name): array
    {
        // TODO: Implement getDocuments() method.
    }
    
    /**
     * @inheritDoc
     */
    public function clearIndex()
    {
        // TODO: Implement clearIndex() method.
    }
    
    /**
     * @inheritDoc
     */
    public function validateDocument(array $document): bool
    {
        // TODO: Implement validateDocument() method.
    }
}

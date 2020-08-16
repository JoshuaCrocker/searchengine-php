<?php


namespace Crockerio\SearchEngine\DocumentStore;


class TutorialDocumentStore implements IDocumentStore
{
    public function __construct()
    {
        if (!defined('DOCUMENT_STORE_PATH')) {
            echo 'DOCUMENT_STORE_PATH must be defined';
            die();
        }
    }
    
    private function getPath($id)
    {
        return DOCUMENT_STORE_PATH . '/' . $id;
    }
    
    private function getNextId()
    {
        $filecount = 0;
        
        $files = glob(DOCUMENT_STORE_PATH . "/*");
        if ($files) {
            $filecount = count($files);
        }
        
        return $filecount;
    }
    
    public function storeDocument(array $document)
    {
        $id = $this->getNextId();
        $serialised = serialize($document);
        
        $fh = fopen($this->getPath($id), 'a');
        fwrite($fh, $serialised);
        fclose($fh);
        
        return $id;
    }
    
    public function getDocument($id)
    {
        $path = $this->getPath($id);
        
        $handle = fopen($path, 'r');
        $contents = fread($handle, filesize($path));
        fclose($handle);
        $unserialised = unserialize($contents);
        return $unserialised;
    }
    
    public function clearDocuments()
    {
        $fp = opendir(DOCUMENT_STORE_PATH);
        
        while (false !== ($file = readdir($fp))) {
            if (is_file(DOCUMENT_STORE_PATH . '/' . $file)) {
                unlink(DOCUMENT_STORE_PATH . '/' . $file);
            }
        }
    }
}

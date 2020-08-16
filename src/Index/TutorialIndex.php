<?php


namespace Crockerio\SearchEngine\Index;

class TutorialIndex implements IIndex
{
    public const DOCUMENT_ENTRY_COUNT = 3;
    
    public function __construct()
    {
        if (!defined('INDEX_PATH')) {
            echo 'INDEX_PATH must be defined';
            die();
        }
    }
    
    private function getIndexFileName($name)
    {
        return INDEX_PATH . '/' . $name;
    }
    
    /**
     * @inheritDoc
     */
    public function storeDocuments($name, array $documents): bool
    {
        // TODO validate
        
        foreach ($documents as $document) {
            if (!$this->validateDocument($document)) {
                return false;
            }
        }
        
        $path = $this->getIndexFileName($name);
        
        $fh = fopen($path, 'w');
        foreach ($documents as $document) {
            $bindata1 = pack('i', intval($document[0]));
            $bindata2 = pack('i', intval($document[1]));
            $bindata3 = pack('i', intval($document[2]));
            
            fwrite($fh, $bindata1);
            fwrite($fh, $bindata2);
            fwrite($fh, $bindata3);
        }
        fclose($fh);
        
        return true;
    }
    
    /**
     * @inheritDoc
     */
    public function getDocuments($name): array
    {
        if (!file_exists($this->getIndexFileName($name))) {
            return [];
        }
        
        $fh = fopen($this->getIndexFileName($name), 'r');
        $filesize = filesize($this->getIndexFileName($name));
        
        if ($filesize % PHP_INT_SIZE != 0) {
            throw new \Exception('Corrupt Index Document!');
        }
        
        $documents = [];
        
        for ($i = 0; $i < $filesize / PHP_INT_SIZE; $i++) {
            $bindata1 = fread($fh, PHP_INT_SIZE);
            $bindata2 = fread($fh, PHP_INT_SIZE);
            $bindata3 = fread($fh, PHP_INT_SIZE);
            
            $data1 = unpack('i', $bindata1);
            $data2 = unpack('i', $bindata2);
            $data3 = unpack('i', $bindata3);
            
            $documents[] = [$data1[1], $data2[1], $data3[1]];
        }
        fclose($fh);
        
        return $documents;
    }
    
    /**
     * @inheritDoc
     */
    public function clearIndex()
    {
        $fp = opendir(INDEXLOCATION);
        
        while (false !== ($file = readdir($fp))) {
            if (is_file(INDEX_PATH . '/' . $file)) {
                unlink(INDEX_PATH . '/' . $file);
            }
        }
    }
    
    /**
     * @inheritDoc
     */
    public function validateDocument(array $document = null): bool
    {
        // Check the given document is an array
        if (!is_array($document)) {
            return false;
        }
        
        // Check the given document has the correct number of entries
        if (count($document) != self::DOCUMENT_ENTRY_COUNT) {
            return false;
        }
        
        // Check each entry is valid
        for ($i = 0; $i < self::DOCUMENT_ENTRY_COUNT; $i++) {
            if (!is_int($document[$i]) || $document[$i] < 0) {
                return false;
            }
        }
        
        return true;
    }
}

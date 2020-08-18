<?php


namespace Crockerio\SearchEngine\Index;

use Crockerio\SearchEngine\Utils\IntegerUtil;

class TutorialIndex implements IIndex
{
    public const DOCUMENT_ENTRY_COUNT = 3;
    
    public function __construct()
    {
        if (!defined('INDEX_DIR')) {
            echo 'INDEX_PATH must be defined';
            die();
        }
    }
    
    private function getIndexFileName($name)
    {
        return INDEX_DIR . '/' . $name . '.bin';
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
            $bindata1 = IntegerUtil::int8(intval($document[0]));
            $bindata2 = IntegerUtil::int8(intval($document[1]));
            $bindata3 = IntegerUtil::int8(intval($document[2]));
            
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
        if (!$fh) {
            return [];
        }
        
        $filesize = filesize($this->getIndexFileName($name));
        
        if ($filesize % 1 != 0) {
            throw new \Exception('Corrupt Index Document!');
        }
        
        $documents = [];
        
        for ($i = 0; $i < $filesize / 3; $i++) {
            $bindata1 = fread($fh, 1);
            $bindata2 = fread($fh, 1);
            $bindata3 = fread($fh, 1);
            
            $data1 = IntegerUtil::int8($bindata1);
            $data2 = IntegerUtil::int8($bindata2);
            $data3 = IntegerUtil::int8($bindata3);
            
            if ($data1 === false || $data2 === false || $data3 === false) {
                print '------- ' . $name . ' -------' . "\n";
                
                var_dump($data1);
                var_dump($data2);
                var_dump($data3);
                
                print '------- END -------' . "\n";
            }
            
            $documents[] = [$data1, $data2, $data3];
        }
        fclose($fh);
        
        return $documents;
    }
    
    /**
     * @inheritDoc
     */
    public function clearIndex()
    {
        $fp = opendir(INDEX_DIR);
        
        while (false !== ($file = readdir($fp))) {
            if (is_file(INDEX_DIR . '/' . $file)) {
                unlink(INDEX_DIR . '/' . $file);
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

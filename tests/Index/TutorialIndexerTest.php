<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Tests\Index;

use Crockerio\SearchEngine\DocumentStore\IDocumentStore;
use Crockerio\SearchEngine\Index\IIndex;
use Crockerio\SearchEngine\Index\TutorialIndexer;
use PHPUnit\Framework\TestCase;

class TestIndex implements IIndex
{
    private $index = [];
    
    public function storeDocuments($name, array $documents): bool
    {
        if (!isset($this->index[$name])) {
            $this->index[$name] = [];
        }
        
        foreach ($documents as $document) {
            $bindata1 = pack('i', intval($document[0]));
            $bindata2 = pack('i', intval($document[1]));
            $bindata3 = pack('i', intval($document[2]));
            
            $this->index[$name][] = $bindata1;
            $this->index[$name][] = $bindata2;
            $this->index[$name][] = $bindata3;
        }
        
        return true;
    }
    
    public function getDocuments($name): array
    {
        if (!isset($this->index[$name])) {
            return [];
        }
        
        $theIndex = $this->index[$name];
        $indexSize = count($theIndex);
        
        if ($indexSize % 3 != 0) {
            throw new \Exception('Corrupt Index Document!');
        }
        
        $documents = [];
        
        for ($i = 0; $i < $indexSize; $i += 3) {
            $documents = [
                unpack('i', $theIndex[$i]),
                unpack('i', $theIndex[$i + 1]),
                unpack('i', $theIndex[$i + 2]),
            ];
        }
        
        return $documents;
    }
    
    public function clearIndex()
    {
        $this->index = [];
    }
    
    public function validateDocument(array $document): bool
    {
        // TODO: Implement validateDocument() method.
    }
}

class TestDocumentStore implements IDocumentStore
{
    private $nextId = 0;
    private $documents = [];
    
    public function storeDocument(array $document)
    {
        $id = $this->nextId++;
        $this->documents[$id] = $document;
        return $id;
    }
    
    public function getDocument($id)
    {
        return $this->documents[$id];
    }
    
    public function clearDocuments()
    {
        $this->nextId = 0;
        $this->documents = [];
    }
}

class TutorialIndexerTest extends TestCase
{
    public function testIndex()
    {
        $documentStore = new TestDocumentStore();
        $index = new TestIndex();
        $indexer = new TutorialIndexer($documentStore, $index);
        
        $string = 'The quick brown fox jumps over the lazy dog.';
        
        $expected = [
            'the' => 2,
            'quick' => 1,
            'brown' => 1,
            'fox' => 1,
            'jumps' => 1,
            'over' => 1,
            'lazy' => 1,
            'dog' => 1,
        ];
        
        $indexer->index([$string]);
        
        foreach ($expected as $word => $count) {
            $indexDocumentList = $index->getDocuments($word);
            
            $this->assertEquals($count, $indexDocumentList[1][1], $word . ' should occur ' . $count . ' time(s)');
        }
    }
    
    
    public function testIndexStripsHtmlTags()
    {
        $documentStore = new TestDocumentStore();
        $index = new TestIndex();
        $indexer = new TutorialIndexer($documentStore, $index);
    
        $string = 'The <strong>quick</strong> brown fox jumps over the lazy dog.';
    
        $expected = [
            'the' => 2,
            'quick' => 1,
            'brown' => 1,
            'fox' => 1,
            'jumps' => 1,
            'over' => 1,
            'lazy' => 1,
            'dog' => 1,
        ];
    
        $indexer->index([$string]);
    
        foreach ($expected as $word => $count) {
            $indexDocumentList = $index->getDocuments($word);
        
            $this->assertEquals($count, $indexDocumentList[1][1], $word . ' should occur ' . $count . ' time(s)');
        }
    }
}

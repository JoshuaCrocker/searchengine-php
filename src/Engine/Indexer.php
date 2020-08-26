<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Engine;

use Carbon\Carbon;
use Crockerio\SearchEngine\Database\Models\Document;
use Crockerio\SearchEngine\Database\Models\Domain;
use Crockerio\SearchEngine\Database\Models\Index;
use Crockerio\SearchEngine\Database\Models\Word;
use Crockerio\SearchEngine\Logger\Logger;
use Crockerio\SearchEngine\Utils\FileUtils;
use GuzzleHttp\Exception\InvalidArgumentException;
use PHPHtmlParser\Dom;

use function GuzzleHttp\json_encode;

/**
 * Class Indexer
 *
 * @author Joshua Crocker
 * @package Crockerio\SearchEngine\Engine
 */
class Indexer
{
    /**
     * Retrieve the next website from the database, or null if there are no more domains.
     *
     * @return Domain|null
     */
    private function _getNextIndexableWebsite()
    {
        $next = Domain::where('last_crawl_time', '<>', null)
            ->where(function ($query) {
                $query->where('last_index_time', '=', null)
                    ->orWhereRaw('last_index_time < last_crawl_time');
            });
        
        if ($next->count() == 0) {
            return null;
        }
        
        return $next->first();
    }
    
    /**
     * Get the contents of the page from the archive.
     */
    private function _getPageContents(Domain $domain)
    {
        $path = FileUtils::getArchivePath($domain);
        if (!file_exists($path)) {
            Logger::getLogger()->debug('Archive doesn\'t exist ' . $path);
            return;
        }
        return file_get_contents($path);
    }
    
    /**
     * Retrieve an alphabetical list of the words present in the document text.
     *
     * @param $document array The document
     * @return array Array of words and occurence counts.
     */
    private function _getConcordance($document)
    {
        $wordCount = [];
        
        foreach ($document as $key => $value) {
            $words = explode(' ', $value);
            
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
        }
        
        return $wordCount;
    }
    
    /**
     * Strip unneeded markup from the document.
     *
     * @param $document array The raw document.
     * @return array The tidied document.
     */
    private function _cleanDocument($document)
    {
        foreach ($document as $key => $value) {
            // Remove HTML from the document.
            $value = strip_tags($value);
            
            // Remove punctuation from the document.
            $value = preg_replace('/\p{P}/', ' ', $value);
            
            // Replace all multi-spaces with one space
            $value = preg_replace('/\s\s+/', ' ', $value);
            
            // Remove non-word characters
            $value = preg_replace('/\W/', ' ', $value);
            
            // Make the document lower case
            $value = strtolower($value);
            
            $document[$key] = $value;
        }
        
        return $document;
    }
    
    /**
     * Store the document in the database.
     *
     * @param $document string The document.
     * @return string The document ID.
     */
    private function _storeDocument($document)
    {
        $d = new Document();
        $d->document = $document;
        $d->save();
        
        return $d->id;
    }
    
    /**
     * Retrieve a word record from the database, or create it if it doesn't exist.
     *
     * @param string $word The word to find.
     * @return Word The word.
     */
    private function _retrieveWord(string $word)
    {
        return Word::firstOrCreate(['word' => $word])->id;
    }
    
    /**
     * Retrieve an Index record from the database, or instantiate it if it doesn't.
     *
     * @param string $word The word.
     * @param Domain $domain The domain.
     * @return Index The index.
     */
    private function _retrieveIndex(string $word, Domain $domain)
    {
        $wordId = $this->_retrieveWord($word);
        
        return Index::firstOrNew([
            'word_id' => $wordId,
            'domain_id' => $domain->id,
        ]);
    }
    
    private function _getDocumentTitle($document)
    {
        $title = '';
        
        $dom = new Dom();
        $dom->loadStr($document);
        $eTitle = $dom->find('title')[0];
        
        if (null != $eTitle) {
            $title = $eTitle->innerHtml;
        }
        
        return $title;
    }
    
    private function _getDocumentMetaTags($document)
    {
        $output = [];
        
        $dom = new Dom();
        $dom->loadStr($document);
        $tags = $dom->find('meta');
        
        foreach ($tags as $tag) {
            $output[$tag->getAttribute('name')] = $tag->getAttribute('content');
        }
        
        return $output;
    }
    
    private function _extractDocumentData($document)
    {
        $output = [];
        
        $output['title'] = $this->_getDocumentTitle($document);
        $meta = $this->_getDocumentMetaTags($document);
        
        foreach ($meta as $type => $value) {
            if (trim($type) != '') {
                $output['meta_' . $type] = $value;
            }
        }
        
        return $output;
    }
    
    /**
     * Index a domain.
     *
     * @param Domain $domain The domain to index.
     * @throws InvalidArgumentException
     */
    private function _indexDomain(Domain $domain)
    {
        Logger::getLogger()->info('Index ' . $domain->domain);
        
        // Set the last indexed time
        $domain->last_index_time = Carbon::now();
        $domain->save();
        
        // Get the contents of the page
        $html = $this->_getPageContents($domain);
        
        if ($html == null || $html == '') {
            return;
        }
        
        // Store the document
        $document = $this->_extractDocumentData($html);
        $document['url'] = $domain->domain;
        $documentId = $this->_storeDocument(json_encode($document));
        
        // Generate the keywords list
        $html = $this->_cleanDocument($document);
        $concordance = $this->_getConcordance($document);
        
        // Save to the index
        foreach ($concordance as $word => $occurrences) {
            $index = $this->_retrieveIndex($word, $domain);
            $index->occurrences = $occurrences;
            $index->document_id = $documentId;
            $index->save();
        }
    }
    
    /**
     * Begin indexing websites until there are no more websites available to index.
     */
    public function startIndexing()
    {
        $next = $this->_getNextIndexableWebsite();
        while (null != $next) {
            $this->_indexDomain($next);
            $next = $this->_getNextIndexableWebsite();
        }
    }
}

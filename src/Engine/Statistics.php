<?php


namespace Crockerio\SearchEngine\Engine;


use Crockerio\SearchEngine\Database\Models\Document;
use Crockerio\SearchEngine\Database\Models\Domain;
use Crockerio\SearchEngine\Database\Models\Index;
use Crockerio\SearchEngine\Database\Models\Word;

class Statistics
{
    private function _getBlankRow()
    {
        return ['', ''];
    }
    
    private function _getDomainCount()
    {
        return [
            'Domains',
            Domain::count(),
        ];
    }
    
    private function _getIndexCount()
    {
        return [
            'Indices',
            Index::count(),
        ];
    }
    
    private function _getDocumentCount()
    {
        return [
            'Documents',
            Document::count(),
        ];
    }
    
    private function _getWordCount()
    {
        return [
            'Words',
            Word::count(),
        ];
    }
    
    private function _getCrawledDomainCount()
    {
        return [
            'of which Crawled',
            Domain::where('last_crawl_time', '<>', null)->count(),
        ];
    }
    
    private function _getIndexedDomainCount()
    {
        return [
            'of which Indexed',
            Domain::where('last_index_time', '<>', null)->count(),
        ];
    }
    
    public function getStats()
    {
        return [
            $this->_getDomainCount(),
            $this->_getCrawledDomainCount(),
            $this->_getIndexedDomainCount(),
            $this->_getBlankRow(),
            $this->_getIndexCount(),
            $this->_getDocumentCount(),
            $this->_getWordCount(),
        ];
    }
}

<?php


namespace Crockerio\SearchEngine\Crawler;

use Carbon\Carbon;
use Crockerio\SearchEngine\Database\Models\Domain;
use Crockerio\SearchEngine\Http\UrlParser;
use Crockerio\SearchEngine\Utils\FileUtils;
use PHPHtmlParser\Dom;

/**
 * Search Engine Crawler Class.
 *
 * @author Joshua Crocker
 * @package Crockerio\SearchEngine\Crawler
 */
class Crawler
{
    /**
     * Crawler constructor.
     */
    public function __construct()
    {
        if (!defined('DATA_DIR')) {
            \crocker_err('DATA_DIR is not defined');
        }
        
        if (!defined('CRAWLER_DIR')) {
            \crocker_err('CRAWLER_DIR is not defined');
        }
        
        FileUtils::createDirectoryIfNotExists(DATA_DIR);
        FileUtils::createDirectoryIfNotExists(CRAWLER_DIR);
    }
    
    /**
     * Retrieve the next website from the database, or null if there are no more domains.
     *
     * @return Domain|null
     */
    private function _getNextCrawlableWebsite()
    {
        $next = \Crockerio\SearchEngine\Database\Models\Domain::where('last_crawl_time',
            null)->orWhere('last_crawl_time', '<', Carbon::now()->subDay());
        
        if ($next->count() == 0) {
            return null;
        }
        
        return $next->first();
    }
    
    /**
     * Get the path to the website archive file.
     *
     * @param Domain $domain Domain Model
     * @return string Path to Archive
     */
    private function _getArchivePath(Domain $domain)
    {
        $dataDir = CRAWLER_DIR . '/' . $domain->getDomainStorageKey();
        $archivePath = $dataDir . '/' . $domain->getDomainHash() . '.html';
        FileUtils::createDirectoryIfNotExists($dataDir);
        
        return $archivePath;
    }
    
    /**
     * Process the website.
     * Set the last crawled time to now and save the webpage.
     *
     * @param Domain $domain Domain Model
     */
    private function _processDomain(Domain $domain)
    {
        crocker_log('Processing ' . $domain->domain);
        
        // Mark the domain as crawled
        // This has to be done first to stop other crawlers picking up this website
        $domain->last_crawl_time = Carbon::now();
        $domain->save();
        
        // Access the website
        $contents = @file_get_contents($domain->domain);
        
        if (!$contents) {
            crocker_log('Error accessing ' . $domain->domain);
            return;
        }
        
        $path = $this->_getArchivePath($domain);
        
        $fh = fopen($path, 'w');
        fwrite($fh, $contents);
        fclose($fh);
        
        unset($contents);
    }
    
    /**
     * Extract new domains from the crawled website.
     *
     * @param Domain $domain Domain Model
     */
    private function _extractDomains(Domain $domain)
    {
        $path = $this->_getArchivePath($domain);
        
        if (!file_exists($path)) {
            crocker_log('Error accessing ' . $path);
            return;
        }
        
        $dom = new Dom();
        $dom->loadFromFile($path);
        $links = $dom->find('a');
        
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            $parser = new UrlParser($href, $domain->domain);
            
            $domainExists = Domain::where('domain', $parser->getFullUrl())->count() > 0;
            
            if ($parser->getType() != UrlParser::TYPE_INVALID && !$domainExists) {
                $domain = new Domain();
                $domain->domain = $parser->getFullUrl();
                $domain->save();
            }
        }
    }
    
    /**
     * Begin crawling websites until there are no more websites available to crawl.
     */
    public function startCrawling()
    {
        $next = $this->_getNextCrawlableWebsite();
        while (null != $next) {
            $this->_processDomain($next);
//            $this->_extractDomains($next);
            $next = $this->_getNextCrawlableWebsite();
        }
    }
}

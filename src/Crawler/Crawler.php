<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Crawler;

use Carbon\Carbon;
use Crockerio\SearchEngine\Database\Models\Domain;
use Crockerio\SearchEngine\Http\UrlParser;
use Crockerio\SearchEngine\Utils\FileUtils;
use PHPHtmlParser\Dom;

class Crawler
{
    /**
     * Crawler constructor.
     */
    public function __construct()
    {
        //
    }
    
    public function processDomain(Domain $domain)
    {
        \write_to_console("Indexing {$domain->domain}\t{$domain->getDomainStorageKey()}\t{$domain->getDomainHash()}");
        $domain->last_crawl_time = Carbon::now();
        $domain->save();
        
        $this->crawlDomain($domain);
//        $this->extractDomainsFromArchive($domain);
    }
    
    private function crawlDomain(Domain $domain)
    {
        $path_to_archive = $this->getDomainArchivePath($domain);
        $web_page = file_get_contents($domain->domain);
        $data = serialize([
            $domain->domain,
            $web_page
        ]);
        $fh = fopen($path_to_archive, 'w');
        fwrite($fh, $data);
        fclose($fh);
        unset($web_page);
    }
    
    /**
     * @param \Crockerio\SearchEngine\Domain $domain
     * @return string
     */
    private function getDomainArchivePath(Domain $domain): string
    {
        $data_directory = CRAWLER_DIR . '/' . $domain->getDomainStorageKey();
        $path_to_archive = $data_directory . '/' . $domain->getDomainHash() . '.html';
        FileUtils::createDirectoryIfNotExists($data_directory);
        
        return $path_to_archive;
    }
    
    private function extractDomainsFromArchive(Domain $domain)
    {
        $path_to_archive = $this->getDomainArchivePath($domain);
        
        $dom = new Dom();
        $dom->loadFromFile($path_to_archive);
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
}

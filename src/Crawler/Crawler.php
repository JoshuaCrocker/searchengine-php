<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Crawler;

use Crockerio\SearchEngine\Database\DAO\DomainDAO;
use Crockerio\SearchEngine\Domain;
use Crockerio\SearchEngine\Http\UrlParser;
use Crockerio\SearchEngine\Utils\FileUtils;
use PHPHtmlParser\Dom;

class Crawler
{
    private $domainDao;

    /**
     * Crawler constructor.
     */
    public function __construct()
    {
        $this->domainDao = new DomainDAO();
    }

    public function processDomain(Domain $domain)
    {
        \write_to_console("Indexing {$domain->getDomain()}\t{$domain->getDomainStorageKey()}\t{$domain->getDomainHash()}");
        $this->domainDao->updateCrawlTime($domain->getDomain());
        $this->crawlDomain($domain);
        $this->extractDomainsFromArchive($domain);
    }

    private function crawlDomain(Domain $domain)
    {
        $path_to_archive = $this->getDomainArchivePath($domain);
        $web_page = file_get_contents($domain->getDomain());
        file_put_contents($path_to_archive, $web_page);
        unset($web_page);
    }

    /**
     * @param \Crockerio\SearchEngine\Domain $domain
     * @return string
     */
    private function getDomainArchivePath(Domain $domain): string
    {
        $data_directory = DATA_DIR . '/' . $domain->getDomainStorageKey();
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
            $parser = new UrlParser($href, $domain->getDomain());
            if ($parser->getType() != UrlParser::TYPE_INVALID && ! $this->domainDao->domainExistsInIndex($parser->getFullUrl())) {
                $this->domainDao->insertDomain($parser->getFullUrl());
            }
        }
    }
}

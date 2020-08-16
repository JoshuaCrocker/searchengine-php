<?php

namespace Crockerio\SearchEngine\Crawler;

use Crockerio\SearchEngine\Database\Database;
use Crockerio\SearchEngine\Domain;
use Crockerio\SearchEngine\Http\UrlParser;
use Crockerio\SearchEngine\Utils\FileUtils;
use PHPHtmlParser\Dom;

class Crawler
{
    private $db;

    private $stmt_update_crawl_time;

    private $stmt_insert_domain;

    public function __construct()
    {
        $this->db = Database::getInstance('db')->getConnection();
        $this->stmt_update_crawl_time = $this->db->prepare('UPDATE raw_site_list SET last_crawl_time = CURRENT_TIMESTAMP() WHERE `domain`=?');
        $this->stmt_insert_domain = $this->db->prepare('INSERT INTO raw_site_list VALUES(null, ?, null)');
    }

    public function getNextCrawlableDomain()
    {
        $stmt = $this->db->query('SELECT * FROM site_list WHERE last_crawl_time is NULL or last_crawl_time < CURRENT_TIMESTAMP()-86400 ORDER BY last_crawl_time ASC, id ASC LIMIT 0,1');
        $result = $stmt->fetch();
        if (! $result) {
            return null;
        }
        $domain = new Domain($result);

        return $domain;
    }

    public function processDomain(Domain $domain)
    {
        \write_to_console("Indexing {$domain->getDomain()}\t{$domain->getDomainStorageKey()}\t{$domain->getDomainHash()}");
        $this->markDomainAsCrawled($domain);
        $this->crawlDomain($domain);
        $this->extractDomainsFromArchive($domain);
    }

    private function markDomainAsCrawled(Domain $domain)
    {
        $this->stmt_update_crawl_time->execute([$domain->getDomain()]);
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
        $data_directory = DATA_DIR.'/'.$domain->getDomainStorageKey();
        $path_to_archive = $data_directory.'/'.$domain->getDomainHash().'.html';
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
            // For now, just accept urls beginning with 'http'
            $href = $link->getAttribute('href');
            $parser = new UrlParser($href, $domain->getDomain());
            if ($parser->getType() != UrlParser::TYPE_INVALID) {
                $this->stmt_insert_domain->execute([$parser->getFullUrl()]);
            }
        }
    }
}

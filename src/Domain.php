<?php

/*
 * PHP Search Engine Project
 *
 * @copyright 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine;

class Domain
{
    private $domain;

    private $last_crawl_time;

    private $domain_hash;

    private $domain_storage_key;

    /**
     * Domain constructor.
     *
     * @param $arr array PDO array
     */
    public function __construct($arr)
    {
        $this->setDomain($arr['domain']);
        $this->setDomainHash($arr['domain_hash']);
        $this->setDomainStorageKey($arr['domain_storage_key']);
        $this->setLastCrawlTime($arr['last_crawl_time']);
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return mixed
     */
    public function getLastCrawlTime()
    {
        return $this->last_crawl_time;
    }

    /**
     * @param mixed $last_crawl_time
     */
    public function setLastCrawlTime($last_crawl_time)
    {
        $this->last_crawl_time = $last_crawl_time;
    }

    /**
     * @return mixed
     */
    public function getDomainHash()
    {
        return $this->domain_hash;
    }

    /**
     * @param mixed $domain_hash
     */
    public function setDomainHash($domain_hash)
    {
        $this->domain_hash = $domain_hash;
    }

    /**
     * @return mixed
     */
    public function getDomainStorageKey()
    {
        return $this->domain_storage_key;
    }

    /**
     * @param mixed $domain_storage_key
     */
    public function setDomainStorageKey($domain_storage_key)
    {
        $this->domain_storage_key = $domain_storage_key;
    }
}

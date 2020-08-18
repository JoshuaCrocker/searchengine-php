<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Database\DAO;

use Crockerio\SearchEngine\Database\Database;
use Crockerio\SearchEngine\Domain;

class DomainDAO
{
    private $database;
    
    private $stmt_update_crawl_time;
    
    private $stmt_update_index_time;
    
    private $stmt_insert_domain;
    
    private $stmt_domain_exists;
    
    /**
     * DomainDAO constructor.
     */
    public function __construct()
    {
        $this->database = Database::getInstance('db')->getConnection();
        $this->stmt_update_crawl_time = $this->database->prepare(
            'UPDATE raw_site_list SET last_crawl_time = CURRENT_TIMESTAMP() WHERE `domain`=?'
        );
        $this->stmt_update_index_time = $this->database->prepare(
            'UPDATE raw_site_list SET last_index_time = CURRENT_TIMESTAMP() WHERE `domain`=?'
        );
        $this->stmt_insert_domain = $this->database->prepare('INSERT INTO raw_site_list (`domain`) VALUES(?)');
        $this->stmt_domain_exists = $this->database->prepare(
            'SELECT COUNT(id) as `count` FROM raw_site_list WHERE `domain`=?'
        );
    }
    
    public function insertDomain($domain)
    {
        $this->stmt_insert_domain->execute([$domain]);
    }
    
    public function getNextCrawlableDomain()
    {
        $stmt = $this->database->query(
            'SELECT * FROM site_list WHERE last_crawl_time is NULL or last_crawl_time < CURRENT_TIMESTAMP()-86400 ORDER BY last_crawl_time ASC, id ASC LIMIT 0,1'
        );
        $result = $stmt->fetch();
        if (!$result) {
            return null;
        }
        
        return new Domain($result);
    }
    
    public function getNextIndexableDomain()
    {
        $stmt = $this->database->query(
            'SELECT * FROM site_list WHERE last_crawl_time is not null and (last_index_time is NULL or last_index_time < last_crawl_time) ORDER BY last_index_time ASC, id ASC LIMIT 0,1'
        );
        $result = $stmt->fetch();
        if (!$result) {
            return null;
        }
        
        return new Domain($result);
    }
    
    public function updateCrawlTime($domain)
    {
        $this->stmt_update_crawl_time->execute([$domain]);
    }
    
    public function updateIndexTime($domain)
    {
        $this->stmt_update_index_time->execute([$domain]);
    }
    
    public function domainExistsInIndex($url)
    {
        $this->stmt_domain_exists->execute([$url]);
        $count = $this->stmt_domain_exists->fetch()['count'];
        
        return ((int)$count) > 0;
    }
}

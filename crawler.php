<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

use Crockerio\SearchEngine\Utils\FileUtils;

require_once 'vendor/autoload.php';

$host = '127.0.0.1:8889';
$db = 'searchengine';
$user = 'root';
$pass = 'root';

$database = \Crockerio\SearchEngine\Database\Database::getInstance('db', $host, $user, $pass, $db);

define('DATA_DIR', __DIR__ . '/data');

function write_to_console($text)
{
    echo "[*] $text\n";
}

// Check if the data directory exists
FileUtils::createDirectoryIfNotExists(DATA_DIR);

// Begin crawling
$crawler = new \Crockerio\SearchEngine\Crawler\Crawler();

$next_website = $crawler->getNextCrawlableDomain();

while (null != $next_website) {
    $crawler->processDomain($next_website);
    $next_website = $crawler->getNextCrawlableDomain();
}

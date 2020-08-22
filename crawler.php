<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

use Carbon\Carbon;
use Crockerio\SearchEngine\Utils\FileUtils;

require_once __DIR__ . '/bootstrap.php';

function write_to_console($text)
{
    echo "[*] $text\n";
}

// Check if the data directory exists
FileUtils::createDirectoryIfNotExists(DATA_DIR);
FileUtils::createDirectoryIfNotExists(CRAWLER_DIR);

// Begin crawling
$crawler = new \Crockerio\SearchEngine\Crawler\Crawler();
$next_website = \Crockerio\SearchEngine\Database\Models\Domain::where(
    'last_crawl_time',
    null
)->orWhere('last_crawl_time', '<', Carbon::now()->subDay())->first();

while (null != $next_website) {
    $crawler->processDomain($next_website);
    $next_website = \Crockerio\SearchEngine\Database\Models\Domain::where(
        'last_crawl_time',
        null
    )->orWhere('last_crawl_time', '<', Carbon::now()->subDay())->first();
}

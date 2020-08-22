<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

require_once __DIR__ . '/bootstrap.php';

// Begin crawling
$crawler = new \Crockerio\SearchEngine\Crawler\Crawler();
$crawler->startCrawling();

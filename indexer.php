<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

use Crockerio\SearchEngine\Engine\Indexer;

require_once __DIR__ . '/bootstrap.php';

$indexer = new Indexer();
$indexer->startIndexing();

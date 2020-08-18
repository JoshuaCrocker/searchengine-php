<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

use Crockerio\SearchEngine\Index\TutorialIndexer;

require_once 'vendor/autoload.php';

$host = '127.0.0.1:8889';
$db = 'searchengine';
$user = 'root';
$pass = 'root';

$database = \Crockerio\SearchEngine\Database\Database::getInstance('db', $host, $user, $pass, $db);
$domainDao = new \Crockerio\SearchEngine\Database\DAO\DomainDAO();

\Crockerio\SearchEngine\Utils\FileUtils::createDirectoryIfNotExists(DATA_DIR);
\Crockerio\SearchEngine\Utils\FileUtils::createDirectoryIfNotExists(INDEX_DIR);
\Crockerio\SearchEngine\Utils\FileUtils::createDirectoryIfNotExists(DOCUMENT_STORE_DIR);

$documentStore = new \Crockerio\SearchEngine\DocumentStore\TutorialDocumentStore();
$index = new \Crockerio\SearchEngine\Index\TutorialIndex();
$indexer = new TutorialIndexer($documentStore, $index);

$next_website = $domainDao->getNextIndexableDomain();

while (null != $next_website) {
    $data_directory = CRAWLER_DIR . '/' . $next_website->getDomainStorageKey();
    $path_to_archive = $data_directory . '/' . $next_website->getDomainHash() . '.html';
    $content = file_get_contents($path_to_archive);
    $indexer->index([$content]);
    $next_website = $domainDao->getNextIndexableDomain();
    unset($content);
    $domainDao->updateIndexTime($next_website->getDomain());
}

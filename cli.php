<?php

require_once __DIR__ . '/bootstrap.php';

use Crockerio\SearchEngine\Command\CrawlCommand;
use Crockerio\SearchEngine\Command\IndexCommand;
use Crockerio\SearchEngine\Command\MigrateCommand;
use Crockerio\SearchEngine\Command\ResetCommand;
use Crockerio\SearchEngine\Command\SeedCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new CrawlCommand());
$application->add(new IndexCommand());
$application->add(new MigrateCommand());
$application->add(new SeedCommand());
$application->add(new ResetCommand());

$application->run();

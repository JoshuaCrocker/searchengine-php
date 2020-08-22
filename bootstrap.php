<?php

require "vendor/autoload.php";

use Crockerio\SearchEngine\Utils\FileUtils;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

$capsule = new Capsule;
$capsule->addConnection([
    "driver" => "mysql",
    "host" => "127.0.0.1:8889",
    "database" => "searchengine",
    "username" => "root",
    "password" => "root",
//    'charset' => 'utf8',
//    'collation' => 'utf8_unicode_ci',
]);

// Set the event dispatcher used by Eloquent models... (optional)

$capsule->setEventDispatcher(new Dispatcher(new Container));

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Setup file storage
FileUtils::setStorageRoot(__DIR__);

FileUtils::createDirectoryIfNotExists(FileUtils::getDataDirectoryPath());
FileUtils::createDirectoryIfNotExists(FileUtils::getCrawlerDirectoryPath());

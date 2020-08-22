<?php


namespace Crockerio\SearchEngine\Logger;

use Crockerio\SearchEngine\Utils\FileUtils;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;

class Logger
{
    private static $logger = null;
    
    public static function getLogger()
    {
        if (self::$logger == null) {
            // Create a handler
            $fileStream = new StreamHandler(FileUtils::getDataDirectoryPath() . '/searchengine.log', Monolog::DEBUG);
            $consoleStream = new StreamHandler('php://stdout', Monolog::DEBUG);
            
            // bind it to a logger object
            self::$logger = new Monolog('searchengine');
            self::$logger->pushHandler($fileStream);
            self::$logger->pushHandler($consoleStream);
        }
        
        return self::$logger;
    }
}

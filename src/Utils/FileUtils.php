<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Utils;

use Crockerio\SearchEngine\Database\Models\Domain;

class FileUtils
{
    private static $storageRoot = '';
    
    public static function setStorageRoot($root)
    {
        self::$storageRoot = $root;
    }
    
    public static function getDataDirectoryPath()
    {
        return self::$storageRoot . '/data';
    }
    
    public static function getCrawlerDirectoryPath()
    {
        return self::getDataDirectoryPath() . '/crawler';
    }
    
    /**
     * Get the path to the website archive file.
     *
     * @param Domain $domain Domain Model
     * @return string Path to Archive
     */
    public static function getArchivePath(Domain $domain)
    {
        $dataDir = self::getCrawlerDirectoryPath() . '/' . $domain->getDomainStorageKey();
        $archivePath = $dataDir . '/' . $domain->getDomainHash() . '.html';
        self::createDirectoryIfNotExists($dataDir);
        
        return $archivePath;
    }
    
    public static function createDirectoryIfNotExists($directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    }
}

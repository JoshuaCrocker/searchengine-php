<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Utils;

use Crockerio\SearchEngine\Database\Models\Domain;

/**
 * Class FileUtils
 *
 * @author Joshua Crocker
 * @package Crockerio\SearchEngine\Utils
 */
class FileUtils
{
    /**
     * Storage Path Root.
     *
     * @var string
     */
    private static $storageRoot = '';
    
    /**
     * Set the Storage Root.
     *
     * @param $root string The root dir.
     */
    public static function setStorageRoot($root)
    {
        self::$storageRoot = $root;
    }
    
    /**
     * Get the path to the Data directory.
     *
     * @return string
     */
    public static function getDataDirectoryPath()
    {
        return self::$storageRoot . '/data';
    }
    
    /**
     * Get the path to the Crawler directory.
     *
     * @return string
     */
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
    
    /**
     * Create the given directory if it doesn't exist.
     *
     * @param $directory string The directory path.
     */
    public static function createDirectoryIfNotExists($directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    }
}

<?php

/*
 * PHP Search Engine Project
 *
 * @copyright 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Database;

use PDO;
use PDOException;

class Database
{
    private static $instances = [];

    private $host;

    private $db;

    private $user;

    private $pass;

    private $charset = 'utf8mb4';

    private $connection = null;

    private $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    private function __construct($host, $user, $pass, $db, $charset = null, $options = [])
    {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;

        if (null != $charset) {
            $this->charset = $charset;
        }

        $this->setOptions($options);
        $this->connect();
    }

    private function setOptions(array $options)
    {
        foreach ($options as $key => $option) {
            $this->options[$key] = $option;
        }
    }

    private function getDSN()
    {
        return "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
    }

    private function connect()
    {
        try {
            $this->connection = new PDO($this->getDSN(), $this->user, $this->pass, $this->options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public static function getInstance(
        $key,
        $host = null,
        $user = null,
        $pass = null,
        $db = null,
        $charset = null,
        $options = []
    ) {
        if (! isset(self::$instances[$key])) {
            self::$instances[$key] = new Database($host, $user, $pass, $db, $charset, $options);
        }

        return self::$instances[$key];
    }
}

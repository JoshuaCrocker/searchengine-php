<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

define('DATA_DIR', __DIR__ . '/data');
define('CRAWLER_DIR', DATA_DIR . '/crawler');
define('INDEX_DIR', DATA_DIR . '/index');
define('DOCUMENT_STORE_DIR', DATA_DIR . '/documents');

function crocker_log($text, $prefix = '*')
{
    echo '[' . $prefix . '] ' . $text . "\n";
}

function crocker_err($text)
{
    crocker_log($text, '!');
    die('Exited.');
}

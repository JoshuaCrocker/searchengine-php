<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

use Crockerio\SearchEngine\Database\Models\Document;
use Crockerio\SearchEngine\Database\Models\Domain;
use Crockerio\SearchEngine\Database\Models\Index;
use Crockerio\SearchEngine\Database\Models\Word;

require_once __DIR__ . '/bootstrap.php';

foreach (Index::all() as $index) {
    $index->delete();
}

foreach (Word::all() as $index) {
    $index->delete();
}

foreach (Document::all() as $index) {
    $index->delete();
}

foreach (Domain::all() as $index) {
    $index->delete();
}

$domains = [
    'https://youtube.com',
    'https://facebook.com',
    'https://amazon.com',
    'https://live.com',
    'https://reddit.com',
    'https://zoom.us',
    'https://blogspot.com',
    'https://office.com',
    'https://instagram.com',
    'https://twitch.tv',
    'https://twitter.com',
    'https://microsoft.com',
    'https://worldometers.info',
    'https://stackoverflow.com',
];

foreach ($domains as $domain) {
    $d = new Domain();
    $d->domain = $domain;
    $d->last_crawl_time = null;
    $d->last_index_time = null;
    $d->save();
}

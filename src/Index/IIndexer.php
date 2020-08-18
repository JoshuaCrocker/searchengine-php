<?php


namespace Crockerio\SearchEngine\Index;


interface IIndexer
{
    public function index(array $documents);
}

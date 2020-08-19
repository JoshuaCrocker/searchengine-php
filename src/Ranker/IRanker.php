<?php


namespace Crockerio\SearchEngine\Ranker;


interface IRanker
{
    public function rank($document1, $document2);
}

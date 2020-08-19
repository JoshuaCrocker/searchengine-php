<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Ranker;

interface IRanker
{
    public function rank($document1, $document2);
}

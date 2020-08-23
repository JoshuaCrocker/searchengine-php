<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Engine\Filter;

use Illuminate\Support\Collection;

/**
 * Class OccurrencesRanker
 *
 * @author Joshua Crocker
 * @package Crockerio\SearchEngine\Engine\Ranking
 */
class OccurrencesRankingFilter implements IFilter
{
    /**
     * Rank the indices by the number of occurrences.
     *
     * @param Collection $results The indices to sort.
     * @return Collection The sorted indices.
     */
    public function filter($results)
    {
        return $results->sortBy('occurrences');
    }
}

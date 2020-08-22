<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Engine\Ranking;

use Illuminate\Support\Collection;

/**
 * Class OccurrencesRanker
 *
 * @author Joshua Crocker
 * @package Crockerio\SearchEngine\Engine\Ranking
 */
class OccurrencesRanker implements IRanker
{
    /**
     * Rank the indices by the number of occurrences.
     *
     * @param Collection $indices The indices to sort.
     * @return Collection The sorted indices.
     */
    public function rank($indices)
    {
        return $indices->sortBy('occurrences');
    }
}

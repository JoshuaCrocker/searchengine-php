<?php


namespace Crockerio\SearchEngine\Engine\Ranking;


use Crockerio\SearchEngine\Database\Models\Index;
use Illuminate\Support\Collection;

/**
 * Interface IRanker
 *
 * @author Joshua Crocker
 * @package Crockerio\SearchEngine\Engine\Ranking
 */
interface IRanker
{
    /**
     * Rank the given indices.
     *
     * @param $indices Collection The indices to sort.
     * @return Collection The sorted indices.
     */
    public function rank($indices);
}

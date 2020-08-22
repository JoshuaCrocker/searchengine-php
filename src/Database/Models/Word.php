<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Database\Models;

use Crockerio\SearchEngine\Database\IndexGuid;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Word
 *
 * @author Joshua Crocker
 * @package Crockerio\SearchEngine\Database\Models
 */
class Word extends \Illuminate\Database\Eloquent\Model
{
    use IndexGuid;
    
    protected $fillable = ['word'];
    
    /**
     * Get the associated Indices.
     *
     * @return HasMany
     */
    public function indices()
    {
        return $this->hasMany(Index::class);
    }
}

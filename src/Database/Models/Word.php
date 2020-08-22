<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Database\Models;

use Crockerio\SearchEngine\Database\IndexGuid;

class Word extends \Illuminate\Database\Eloquent\Model
{
    use IndexGuid;
}

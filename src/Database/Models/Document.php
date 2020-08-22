<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Database\Models;

use Crockerio\SearchEngine\Database\IndexGuid;

/**
 * Class Document
 *
 * @author Joshua Crocker
 * @package Crockerio\SearchEngine\Database\Models
 */
class Document extends \Illuminate\Database\Eloquent\Model
{
    use IndexGuid;
}

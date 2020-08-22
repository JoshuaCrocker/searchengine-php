<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Database\Models;

use Crockerio\SearchEngine\Database\IndexGuid;

class Domain extends \Illuminate\Database\Eloquent\Model
{
    use IndexGuid;
    
    public function getDomainStorageKey()
    {
        return substr($this->getDomainHash(), 0, 4);
    }
    
    public function getDomainHash()
    {
        return md5($this->domain);
    }
}

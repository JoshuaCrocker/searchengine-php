<?php


namespace Crockerio\SearchEngine\Database\Models;


use Crockerio\SearchEngine\Database\IndexGuid;

class Word extends \Illuminate\Database\Eloquent\Model
{
    use IndexGuid;
    
    protected $fillable = ['word'];
}

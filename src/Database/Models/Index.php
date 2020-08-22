<?php


namespace Crockerio\SearchEngine\Database\Models;


use Crockerio\SearchEngine\Database\IndexGuid;

class Index extends \Illuminate\Database\Eloquent\Model
{
    use IndexGuid;
    
    protected $table = 'indexes';
    
    protected $fillable = ['domain_id', 'word_id'];
}

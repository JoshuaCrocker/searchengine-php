<?php


namespace Crockerio\SearchEngine\Database\Models;


use Crockerio\SearchEngine\Database\IndexGuid;

class Index extends \Illuminate\Database\Eloquent\Model
{
    use IndexGuid;
    
    protected $table = 'indexes';
    
    protected $fillable = ['domain_id', 'word_id'];
    
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    
    public function word()
    {
        return $this->belongsTo(Word::class);
    }
    
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}

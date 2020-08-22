<?php


namespace Crockerio\SearchEngine\Database\Models;


use Crockerio\SearchEngine\Database\IndexGuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Index
 *
 * @author Joshua Crocker
 * @package Crockerio\SearchEngine\Database\Models
 */
class Index extends \Illuminate\Database\Eloquent\Model
{
    use IndexGuid;
    
    protected $table = 'indexes';
    
    protected $fillable = ['domain_id', 'word_id'];
    
    /**
     * Get the associated Document.
     *
     * @return BelongsTo
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    
    /**
     * Get the associated Word.
     *
     * @return BelongsTo
     */
    public function word()
    {
        return $this->belongsTo(Word::class);
    }
    
    /**
     * Get the associated Domain.
     *
     * @return BelongsTo
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}

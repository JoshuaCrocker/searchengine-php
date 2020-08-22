<?php


namespace Crockerio\SearchEngine\Database\Models;


use Crockerio\SearchEngine\Database\IndexGuid;

/**
 * Class Domain
 *
 * @author Joshua Crocker
 * @package Crockerio\SearchEngine\Database\Models
 */
class Domain extends \Illuminate\Database\Eloquent\Model
{
    use IndexGuid;
    
    /**
     * Get the storage key.
     *
     * @return string
     */
    public function getDomainStorageKey()
    {
        return substr($this->getDomainHash(), 0, 4);
    }
    
    /**
     * Get the domain hash.
     *
     * @return string
     */
    public function getDomainHash()
    {
        return md5($this->domain);
    }
}

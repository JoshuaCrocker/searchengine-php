<?php


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

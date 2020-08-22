<?php


namespace Crockerio\SearchEngine\Database;

/**
 * Trait IndexGuid
 *
 * @author Joshua Crocker
 * @package Crockerio\SearchEngine\Database
 */
trait IndexGuid
{
    /**
     * Generate a new UUID.
     *
     * @return string
     * @link https://www.php.net/manual/en/function.uniqid.php#94959
     * @author Andrew Moore
     */
    private static function genUuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    
    /**
     * Trait Boot Method.
     *
     * Auto-populate the ID field.
     */
    public static function bootIndexGuid()
    {
        static::creating(function ($model) {
            $model->id = (string)self::genUuid();
        });
    }
    
    /**
     * @return false
     */
    public function getIncrementing()
    {
        return false;
    }
    
    /**
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }
}

<?php


namespace Crockerio\SearchEngine\Utils;


use Crockerio\SearchEngine\Http\UrlParser;

class UrlUtils
{
    /**
     * @param string $url
     * @return mixed
     */
    public static function isValidUrl($url)
    {
        return $url !== '' && filter_var($url, FILTER_VALIDATE_URL);
    }
}

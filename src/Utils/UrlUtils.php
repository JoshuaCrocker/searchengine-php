<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Utils;

/**
 * Class UrlUtils
 *
 * @author Joshua Crocker
 * @package Crockerio\SearchEngine\Utils
 */
class UrlUtils
{
    /**
     * Determine if the URL is valid.
     *
     * @param string $url The URL.
     * @return bool
     */
    public static function isValidUrl($url)
    {
        return $url !== '' && filter_var($url, FILTER_VALIDATE_URL);
    }
}

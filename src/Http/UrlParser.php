<?php

/*
 * PHP Search Engine Project
 *
 * Copyright (C) 2020 Joshua Crocker
 */

namespace Crockerio\SearchEngine\Http;

class UrlParser
{
    public const TYPE_FULL = 1;

    public const TYPE_RELATIVE = 2;

    public const TYPE_DOMAIN_RELATIVE = 3;

    public const TYPE_INVALID = -1;

    private $url;

    private $referer;

    private $type = self::TYPE_INVALID;

    public function __construct($url, $referer = null)
    {
        $this->url = trim($url);
        $this->referer = trim($referer);
        $this->parseUrl();
    }

    public function getType()
    {
        return $this->type;
    }

    public function getFullUrl()
    {
        if ($this->type == self::TYPE_FULL) {
            return $this->url;
        }
        if ($this->type == self::TYPE_RELATIVE) {
            $baseUrl = $this->getBaseUrlAndPathFromReferer();

            return $baseUrl.$this->url;
        }
        if ($this->type == self::TYPE_DOMAIN_RELATIVE) {
            $baseUrl = $this->getBaseUrlFromReferer();

            return $baseUrl.$this->url;
        }
    }

    private function getBaseUrlAndPathFromReferer()
    {
        $parts = parse_url($this->referer);
        $baseUrl = $this->getBaseUrlFromReferer();
        $path = '';
        if (isset($parts['path'])) {
            $path = explode('/', $parts['path']);
            array_pop($path);
            $path = join('/', $path);
        }

        return $baseUrl.$path.'/';
    }

    private function getBaseUrlFromReferer()
    {
        $parts = parse_url($this->referer);
        $baseUrl = $parts['scheme'].'://';
        if (isset($parts['user'])) {
            $baseUrl .= $parts['user'];
            if (isset($parts['pass'])) {
                $baseUrl .= ':'.$parts['pass'];
            }
            $baseUrl .= '@';
        }
        $baseUrl .= $parts['host'];
        if (isset($parts['port'])) {
            $baseUrl .= ':'.$parts['port'];
        }

        return $baseUrl;
    }

    private function parseUrl()
    {
        // Check if we have a full URL
        if ($this->beginsWithValidScheme()) {
            if ($this->isValidUrl()) {
                $this->type = self::TYPE_FULL;
            }

            return;
        }
        if (! $this->isValidUrl() && $this->url !== '' && substr($this->url, 0, 1) !== '/') {
            // Check if we have a relative URL
            $this->type = self::TYPE_RELATIVE;

            return;
        }
        if (! $this->isValidUrl() && $this->url !== '' && substr($this->url, 0, 1) === '/') {
            // Check if we have a relative URL
            $this->type = self::TYPE_DOMAIN_RELATIVE;

            return;
        }
    }

    private function beginsWithValidScheme()
    {
        // The scheme must be HTTP or HTTPS
        $parts = parse_url($this->url);
        if (! isset($parts['scheme'])) {
            return false;
        }
        $scheme = strtolower($parts['scheme']);

        return $scheme == 'http' || $scheme == 'https';
    }

    /**
     * @return mixed
     */
    private function isValidUrl()
    {
        return filter_var($this->url, FILTER_VALIDATE_URL);
    }
}

<?php

namespace Crockerio\SearchEngine\Tests\Http;

use Crockerio\SearchEngine\Http\UrlParser;
use PHPUnit\Framework\TestCase;

class UrlParserTest extends TestCase
{
    public function urlTypeProvider()
    {
        return [
            ['', UrlParser::TYPE_INVALID],
            // Full URL
            ['https://example.com', UrlParser::TYPE_FULL],
            ['http://example.com', UrlParser::TYPE_FULL],
            ['ftp://example.com', UrlParser::TYPE_INVALID],
            ['HTTPS://EXAMPLE.COM', UrlParser::TYPE_FULL],
            ['https://user@example.com', UrlParser::TYPE_FULL],
            ['https://user:password@example.com', UrlParser::TYPE_FULL],
            ['https://example.com:1234', UrlParser::TYPE_FULL],
            ['https://example.com/path/to/file.php', UrlParser::TYPE_FULL],
            ['https://example.com?query=string&second=parameter', UrlParser::TYPE_FULL],
            ['https://example.com/path/to/file.php?query=string&second=parameter', UrlParser::TYPE_FULL],
            ['https://example.com#fragment', UrlParser::TYPE_FULL],
            ['https://example.com?query=string&second=parameter#fragment', UrlParser::TYPE_FULL],
            ['https://example.com/path/to/file.php?query=string&second=parameter#fragment', UrlParser::TYPE_FULL],
            // Relative URL
            ['file.php', UrlParser::TYPE_RELATIVE],
            ['folder', UrlParser::TYPE_RELATIVE],
            ['file/in/folder.php', UrlParser::TYPE_RELATIVE],
            ['file.php?query=string&second=parameter', UrlParser::TYPE_RELATIVE],
            ['file.php#fragment', UrlParser::TYPE_RELATIVE],
            ['file.php?query=string&second=parameter#fragment', UrlParser::TYPE_RELATIVE],
            ['file.php?query=string&second=parameter#fragment', UrlParser::TYPE_RELATIVE],
            // Domain-Level Relative URL
            ['/', UrlParser::TYPE_DOMAIN_RELATIVE],
            ['/file.php', UrlParser::TYPE_DOMAIN_RELATIVE],
            ['/folder', UrlParser::TYPE_DOMAIN_RELATIVE],
            ['/file/in/folder.php', UrlParser::TYPE_DOMAIN_RELATIVE],
            ['/file.php?query=string&second=parameter', UrlParser::TYPE_DOMAIN_RELATIVE],
            ['/file.php#fragment', UrlParser::TYPE_DOMAIN_RELATIVE],
            ['/file.php?query=string&second=parameter#fragment', UrlParser::TYPE_DOMAIN_RELATIVE],
            ['/file.php?query=string&second=parameter#fragment', UrlParser::TYPE_DOMAIN_RELATIVE],
        ];
    }

    public function urlTransformProvider()
    {
        // All URLs have the referrer "https://example.com/subdir/file.php"
        return [
// Full URL
            ['https://example.com', 'https://example.com'],
            ['http://example.com', 'http://example.com'],
            ['HTTPS://EXAMPLE.COM', 'HTTPS://EXAMPLE.COM'],
            ['https://user@example.com', 'https://user@example.com'],
            ['https://user:password@example.com', 'https://user:password@example.com'],
            ['https://example.com:1234', 'https://example.com:1234'],
            ['https://example.com/path/to/file.php', 'https://example.com/path/to/file.php'],
            ['https://example.com?query=string&second=parameter', 'https://example.com?query=string&second=parameter'],
            [
                'https://example.com/path/to/file.php?query=string&second=parameter',
                'https://example.com/path/to/file.php?query=string&second=parameter',
            ],
            ['https://example.com#fragment', 'https://example.com#fragment'],
            [
                'https://example.com?query=string&second=parameter#fragment',
                'https://example.com?query=string&second=parameter#fragment',
            ],
            [
                'https://example.com/path/to/file.php?query=string&second=parameter#fragment',
                'https://example.com/path/to/file.php?query=string&second=parameter#fragment',
            ],
            // Relative URL
            ['other.php', 'https://example.com/subdir/other.php'],
            ['otherdir/other.php', 'https://example.com/subdir/otherdir/other.php'],
            ['other.php?query=string', 'https://example.com/subdir/other.php?query=string'],
            ['other.php#fragment', 'https://example.com/subdir/other.php#fragment'],
            // Domain-Level Relative URL
            ['/', 'https://example.com/'],
            ['/other.php', 'https://example.com/other.php'],
            ['/otherdir/other.php', 'https://example.com/otherdir/other.php'],
            ['/other.php?query=string', 'https://example.com/other.php?query=string'],
            ['/other.php#fragment', 'https://example.com/other.php#fragment'],
        ];
    }

    /**
     * The UrlParser class is capable of determining the type of the given URL.
     *
     * There are three types of URL which can be categorised by the UrlParser:
     * - Full URL
     * - Relative URL
     * - Domain-Level Relative URL
     *
     * A full URL provides at a minimum the scheme and the hostname.
     *
     * A relative URL provides at a minimum a path, which is not prepended with a forward slash.
     * This indicates the URL should be navigated from the current directory.
     *
     * A domain-level relative URL provides at a minimum a path, which is prepended with a forward slash.
     * This indicates the URL should be navigated from the domain root.
     *
     * @dataProvider urlTypeProvider
     * @test
     */
    public function it_determines_the_type_of_a_url($url, $expectedType)
    {
        $parser = new UrlParser($url);
        $this->assertEquals($expectedType, $parser->getType());
    }

    /**
     * The UrlParser class is capable of transforming relative URLs into full URls, given the referring URL.
     *
     * For example, if a link with the target "/about" is found on "https://example.com/some/page.php", the transformed
     * URL would be "https://example.com/about"
     *
     * TODO Query Strings and Fragments shouldn't remove the last entry from the path.
     *
     * @dataProvider urlTransformProvider
     * @test
     */
    public function it_transforms_relative_urls_into_full_urls($url, $expectedUrl)
    {
        $parser = new UrlParser($url, 'https://example.com/subdir/file.php');
        $this->assertEquals($expectedUrl, $parser->getFullUrl());
    }

    /** @test */
    public function it_can_rebuild_a_full_url_from_a_complex_referer()
    {
        $referer = 'https://user:pass@example.com:8080/some/referer.php';
        $parser_domain_relative = new UrlParser('/', $referer);
        $this->assertEquals('https://user:pass@example.com:8080/', $parser_domain_relative->getFullUrl());
        $parser_relative = new UrlParser('file.php', $referer);
        $this->assertEquals('https://user:pass@example.com:8080/some/file.php', $parser_relative->getFullUrl());
    }
}

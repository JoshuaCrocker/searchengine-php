<?php

namespace Crockerio\SearchEngine\Tests\Utils;

use Crockerio\SearchEngine\Utils\UrlUtils;
use PHPUnit\Framework\TestCase;

class UrlUtilsTest extends TestCase
{
    public function urlProvider()
    {
        return [
            ['', false],
            ['https://example.com', true],
            ['https://example.com/file.php', true],
            ['https://example.com/path/to/file.php', true],
            ['https://example.com:2344', true],
            ['https://user@example.com', true],
            ['https://user:pass@example.com', true],
            ['http://example.com', true],
            ['https://example.com/', true],
            ['https://example.com/file.php?query=string', true],
            ['https://example.com/file.php#fragment', true],
            ['https://example.com/file.php?query=string#fragment', true],
            ['//example.com', false],
            ['//example.com/file.php', false],
            ['//example.com/path/to/file.php', false],
            ['//example.com:2344', false],
            ['//user@example.com', false],
            ['//user:pass@example.com', false],
            ['//example.com', false],
            ['//example.com/', false],
            ['//example.com/file.php?query=string', false],
            ['//example.com/file.php#fragment', false],
            ['//example.com/file.php?query=string#fragment', false],
        ];
    }
    
    /**
     * @dataProvider urlProvider
     * @test
     */
    public function test_is_valid_url($url, $isValid)
    {
        $this->assertEquals($isValid, UrlUtils::isValidUrl($url));
    }
}

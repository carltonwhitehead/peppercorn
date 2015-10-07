<?php

namespace Peppercorn\UnitTest\DataType\UnderscoreDelimitedKeyValuePair;

use Peppercorn\DataType\UnderscoreDelimitedKeyValuePair\PlainReader;
use PHPUnit_Framework_TestCase;

class PlainReaderUnitTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var PlainReader the PlainReader under test
     */
    private $plainReader;

    const SAMPLE_BASIC = '_foo_bar_baz_bax';
    const SAMPLE_EMPTY = '';

    public function setUp()
    {
       $this->plainReader = new PlainReader();
    }

    public function testGetValidDataWithExistingKeyPair()
    {
        $key = 'foo';
        $expected = 'bar';

        $actual = $this->plainReader->get(self::SAMPLE_BASIC, $key);

        $this->assertEquals($expected, $actual);
    }

    public function testGetValidDataWithMissingKeyPair()
    {
        $key = 'no';
        $expected = '';

        $actual = $this->plainReader->get(self::SAMPLE_BASIC, $key);

        $this->assertEquals($expected, $actual);
    }

}

<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\Line;
use Peppercorn\St1\File;
use Peppercorn\St1\Category;
use Peppercorn\St1\SortDriverNameAscending;
class SortProvidersTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param array $getSort
     * @param Line $a
     * @param Line $b
     * @param numeric $expected
     *
     * @dataProvider providerSort
     */
    public function testSort(array $getSort, Line $a, Line $b, $expected)
    {
        $sort = $getSort();
        $this->assertInternalType('callable', $sort);
        $actual = $sort($a, $b);
        $this->assertInternalType('integer', $actual);
        $this->assertEquals($expected, $actual);
    }

    public function providerSort()
    {
        $file = $this->getValidFile();
        $driverNameAscending = array('Peppercorn\\St1\\SortDriverNameAscending', 'getSort');
        return array(
            array($driverNameAscending, $file->getLine(0), $file->getLine(1), 1), // zach, carlton
            array($driverNameAscending, $file->getLine(2), $file->getLine(4), 0), // zach, zach
            array($driverNameAscending, $file->getLine(5), $file->getLine(9), -1), // carlton, zach
        );
    }

    public function getValidFile()
    {
        $content = file_get_contents(__DIR__ . '/assets/SortProvidersTest/ValidContent.st1');
        return new File($content, array(
            new Category(''),
            new Category('RT'),
            new Category('TIR')
        ));
    }
}
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
        $this->assertCount(2, $getSort);
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
        $timePaxAscending = array('Peppercorn\\St1\\SortTimePaxAscending', 'getSort');
        $timeRawAscending = array('Peppercorn\\St1\\SortTimeRawAscending', 'getSort');
        return array(
            // driver name ascending
            array($driverNameAscending, $file->getLine(0), $file->getLine(1), 1), // zach, carlton
            array($driverNameAscending, $file->getLine(2), $file->getLine(4), 0), // zach, zach
            array($driverNameAscending, $file->getLine(5), $file->getLine(9), -1), // carlton, zach
            // time pax ascending
            array($timePaxAscending, $file->getLine(0), $file->getLine(1), -1), // 53.336, 60.063
            array($timePaxAscending, $file->getLine(6), $file->getLine(6), 0), // 55.674, 55.674
            array($timePaxAscending, $file->getLine(12), $file->getLine(7), 1), // DNF, 60.717
            // time raw ascending
            array($timeRawAscending, $file->getLine(0), $file->getLine(1), -1), // 63.572, 71.589
            array($timeRawAscending, $file->getLine(6), $file->getLine(6), 0), // 66.358, 66.358
            array($timeRawAscending, $file->getLine(10), $file->getLine(13), 1), // 60.713, 59.970
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
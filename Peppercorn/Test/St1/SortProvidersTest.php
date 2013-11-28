<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\Line;
use Peppercorn\St1\File;
use Peppercorn\St1\Category;
use Peppercorn\St1\SortDriverNameAscending;
class SortProvidersTest extends \PHPUnit_Framework_TestCase
{
    const LT = -1;
    const EQ = 0;
    const GT = 1;

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
        switch ($expected) {
        	case self::LT:
        	    $this->assertLessThanOrEqual(self::LT, $actual);
        	    break;
        	case self::EQ:
        	    $this->assertEquals(self::EQ, $actual);
        	    break;
        	case self::GT:
        	    $this->assertGreaterThanOrEqual(self::GT, $actual);
        	    break;
        	default:
        	    $this->fail("Invalid \$expected: $expected");
        }
    }

    public function providerSort()
    {
        $file = $this->getValidFile();
        $driverNameAscending = array('Peppercorn\\St1\\SortDriverNameAscending', 'getSort');
        $timePaxAscending = array('Peppercorn\\St1\\SortTimePaxAscending', 'getSort');
        $timeRawAscending = array('Peppercorn\\St1\\SortTimeRawAscending', 'getSort');
        $driverCategoryAscending = array('Peppercorn\\St1\\SortDriverCategoryAscending', 'getSort');
        $driverClassAscending = array('Peppercorn\\St1\\SortDriverClassAscending', 'getSort');
        return array(
            // driver name ascending
            array($driverNameAscending, $file->getLine(0), $file->getLine(1), self::GT), // zach, carlton
            array($driverNameAscending, $file->getLine(2), $file->getLine(4), self::EQ), // zach, zach
            array($driverNameAscending, $file->getLine(5), $file->getLine(9), self::LT), // carlton, zach
            // time pax ascending
            array($timePaxAscending, $file->getLine(0), $file->getLine(1), self::LT), // 53.336, 60.063
            array($timePaxAscending, $file->getLine(6), $file->getLine(6), self::EQ), // 55.674, 55.674
            array($timePaxAscending, $file->getLine(12), $file->getLine(7), self::GT), // DNF, 60.717
            // time raw ascending
            array($timeRawAscending, $file->getLine(0), $file->getLine(1), self::LT), // 63.572, 71.589
            array($timeRawAscending, $file->getLine(6), $file->getLine(6), self::EQ), // 66.358, 66.358
            array($timeRawAscending, $file->getLine(10), $file->getLine(13), self::GT), // 60.713, 59.970
            // driver category ascending
            array($driverCategoryAscending, $file->getLine(15), $file->getLine(20), self::EQ), // open STC, open STC
            array($driverCategoryAscending, $file->getLine(0), $file->getLine(15), self::EQ), // open STR, open STC
            array($driverCategoryAscending, $file->getLine(15), $file->getLine(24), self::LT), // open STC, TIR BS
            array($driverCategoryAscending, $file->getLine(24), $file->getLine(15), self::GT), // TIR BS, open STC
            // driver class ascending
            array($driverClassAscending, $file->getLine(0), $file->getLine(1), self::EQ), // open STR, open STR
            array($driverClassAscending, $file->getLine(0), $file->getLine(15), self::GT), // open STR, open STC
            array($driverClassAscending, $file->getLine(15), $file->getLine(21), self::GT), // open STC, TIR BS
            array($driverClassAscending, $file->getLine(21), $file->getLine(15), self::LT), // TIR BS, open STC
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
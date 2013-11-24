<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\Line;
use Peppercorn\St1\LineException;
use Peppercorn\St1\File;
use Peppercorn\St1\Category;

use Phava\Exception\IllegalArgumentException;

class LineTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $validContent
     *
     * @dataProvider providerGetRunNumber
     */
    public function testGetRunNumber($validContent, $lineNumber, $expectedRunNumber)
    {
        $file = new File($validContent, array(new Category('')));
        $line = $file->getLine($lineNumber);
        $actualRunNumber = $line->getRunNumber();
        $this->assertInternalType('string', $actualRunNumber);
        $this->assertEquals($expectedRunNumber, $actualRunNumber);
    }

    public function providerGetRunNumber()
    {
        return array(
            array($this->getValidContent(), 0, 1),
            array($this->getValidContent(), 1, 2),
            array($this->getValidContent(), 8, 3),
            array($this->getValidContent(), 11, 6)
        );
    }

    /**
     * @param File $file
     * @param int $lineNumber
     * @param string $expectedCategoryPrefix
     *
     * @dataProvider providerGetCategory
     */
    public function testGetCategory(Line $line, $expectedCategoryPrefix)
    {
        /* @var $driverCategory Category */
        $driverCategory = $line->getDriverCategory();
        $this->assertNotNull($driverCategory);
        $this->assertInstanceOf(get_class(new Category('')), $driverCategory);
        $actualCategoryPrefix = $driverCategory->getPrefix();
        $this->assertInternalType('string', $actualCategoryPrefix);
        $this->assertEquals($expectedCategoryPrefix, $actualCategoryPrefix);
    }

    public function providerGetCategory()
    {
        $file = $this->getValidFile();
        return array(
        	array($file->getLine(0), ''),
            array($file->getLine(7), ''),
            array($file->getLine(12), 'TIR'),
            array($file->getLine(16), 'TIR')
        );
    }

    /**
     * @param Line $line
     * @param int $expectedDriverNumber
     *
     * @dataProvider providerGetDriverNumber
     */
    public function testGetDriverNumber(Line $line, $expectedDriverNumber)
    {
        $actualDriverNumber = $line->getDriverNumber();
        $this->assertInternalType('string', $actualDriverNumber);
        $this->assertEquals($expectedDriverNumber, $actualDriverNumber);
    }

    public function providerGetDriverNumber()
    {
        $file = $this->getValidFile();
        return array(
            array($file->getLine(0), 8),
            array($file->getLine(6), 8),
            array($file->getLine(12), 1)
        );
    }

    /**
     * @param Line $line
     *
     * @dataProvider providerGetDriverNumberWithBadContent
     */
    public function testGetDriverNumberWithBadContent(Line $line)
    {
        $this->setExpectedException(get_class(new LineException()));
        $line->getDriverNumber();
    }

    public function providerGetDriverNumberWithBadContent()
    {
        $file = $this->getBadFile();
        return array(
            array($file->getLine(7)),
            array($file->getLine(8))
        );
    }

    /**
     * @param Line $line
     * @param string $expectedTimeRaw
     *
     * @dataProvider providerGetTimeRaw
     */
    public function testGetTimeRaw(Line $line, $expectedTimeRaw)
    {
        $actualTimeRaw = $line->getTimeRaw();
        $this->assertInternalType('string', $actualTimeRaw);
        $this->assertEquals($expectedTimeRaw, $actualTimeRaw);
    }

    public function providerGetTimeRaw()
    {
        $file = $this->getValidFile();
        return array(
        	array($file->getLine(0), '52.444'),
            array($file->getLine(6), '43.071'),
            array($file->getLine(8), '42.432'),
            array($file->getLine(16), '67.648')
        );
    }

    /**
     * @param Line $line
     *
     * @dataProvider providerGetTimeRawWithBadContent
     */
    public function testGetTimeRawWithBadContent(Line $line)
    {
        $this->setExpectedException(get_class(new LineException()));
        $line->getTimeRaw();
    }

    public function providerGetTimeRawWithBadContent()
    {
        $file = $this->getBadFile();
        return array(
            array($file->getLine(0)),
            array($file->getLine(1))
        );
    }

    /**
     * @param Line $line
     * @param string $expectedPenalty
     *
     * @dataProvider providerGetPenalty
     */
    public function testGetPenalty(Line $line, $expectedPenalty)
    {
        $actualPenalty = $line->getPenalty();
        $this->assertInternalType('string', $actualPenalty);
        $this->assertEquals($expectedPenalty, $actualPenalty);
    }

    public function providerGetPenalty()
    {
        $file = $this->getValidFile();
        return array(
            array($file->getLine(0), '1'),
            array($file->getLine(1), ''),
            array($file->getLine(8), 'DNF'),
            array($file->getLine(16), 'RRN')
        );
    }

    /**
     * @param Line $line
     * @param string $expectedDriverName
     *
     * @dataProvider providerGetDriverName
     */
    public function testGetDriverName(Line $line, $expectedDriverName)
    {
        $actualDriverName = $line->getDriverName();
        $this->assertInternalType('string', $actualDriverName);
        $this->assertEquals($expectedDriverName, $actualDriverName);
    }

    public function providerGetDriverName()
    {
        $file = $this->getValidFile();
        $name = 'Carlton Whitehead';
        return array(
            array($file->getLine(0), $name),
            array($file->getLine(18), $name)
        );
    }

    /**
     * @param Line $line
     *
     * @dataProvider providerGetDriverNameWithBadContent
     */
    public function testGetDriverNameWithBadContent(Line $line)
    {
        $this->setExpectedException(get_class(new LineException()));
        $line->getDriverName();
    }

    public function providerGetDriverNameWithBadContent()
    {
        $file = $this->getBadFile();
        return array(
            array($file->getLine(2)),
            array($file->getLine(3))
        );
    }

    /**
     * @param Line $line
     * @param string $expectedCar
     *
     * @dataProvider providerGetCar
     */
    public function testGetCar(Line $line, $expectedCar)
    {
        $actualCar = $line->getCar();
        $this->assertInternalType('string', $actualCar);
        $this->assertEquals($expectedCar, $actualCar);
    }

    public function providerGetCar()
    {
        $file = $this->getValidFile();
        return array(
            array($file->getLine(0), '2002 Honda S2000'),
            array($file->getLine(6), 'Altima'),
            array($file->getLine(18), '2002 Honda S2000')
        );
    }

    /**
     * @param Line $line
     * @param string $expectedCarColor
     *
     * @dataProvider providerGetCarColor
     */
    public function testGetCarColor(Line $line, $expectedCarColor)
    {
        $actualCarColor = $line->getCarColor();
        $this->assertInternalType('string', $actualCarColor);
        $this->assertEquals($expectedCarColor, $actualCarColor);
    }

    public function providerGetCarColor()
    {
        $file = $this->getValidFile();
        return array(
            array($file->getLine(0), 'Silver'),
            array($file->getLine(6), 'Red'),
            array($file->getLine(12), '')
        );
    }

    /**
     * @param Line $line
     * @param string $expectedTimePax
     *
     * @dataProvider providerGetTimePax
     */
    public function testGetTimePax(Line $line, $expectedTimePax)
    {
        $actualTimePax = $line->getTimePax();
        $this->assertInternalType('string', $actualTimePax);
        $this->assertEquals($expectedTimePax, $actualTimePax);
    }

    public function providerGetTimePax()
    {
        $file = $this->getValidFile();
        return array(
            array($file->getLine(0), '45.678'),
            array($file->getLine(8), 'DNF'),
            array($file->getLine(16), '57.027')
        );
    }

    /**
     * @param Line $line
     *
     * @dataProvider providerGetTimePaxWithBadContent
     */
    public function testGetTimePaxWithBadContent(Line $line)
    {
        $this->setExpectedException(get_class(new LineException()));
        $line->getTimePax();
    }

    public function providerGetTimePaxWithBadContent()
    {
        $file = $this->getBadFile();
        return array(
            array($file->getLine(9)),
            array($file->getLine(10))
        );
    }

    /**
     * @param Line $line
     * @param int $expectedTimestamp
     *
     * @dataProvider providerGetTimestamp
     */
    public function testGetTimestamp(Line $line, $expectedTimestamp)
    {
        $actualTimestamp = $line->getTimestamp();
        $this->assertInternalType('int', $actualTimestamp);
        $this->assertEquals($expectedTimestamp, $actualTimestamp);
    }

    public function providerGetTimestamp()
    {
        $file = $this->getValidFile();
        return array(
            array($file->getLine(0), 1361128824),
            array($file->getLine(18), 1354479006)
        );
    }

    /**
     * @param Line $line
     * @param string $expectedDiff
     *
     * @dataProvider providerGetDiff
     */
    public function testGetDiff(Line $line, $expectedDiff)
    {
        $actualDiff = $line->getDiff();
        $this->assertInternalType('string', $actualDiff);
        $this->assertEquals($expectedDiff, $actualDiff);
    }

    public function providerGetDiff()
    {
        $file = $this->getValidFile();
        return array(
            array($file->getLine(0), '+0.638'),
            array($file->getLine(6), '-'),
            array($file->getLine(9), '[-]0.647')
        );
    }

    /**
     * @param Line $line
     * @param string $expectedDiffFromFirst
     *
     * @dataProvider providerGetDiffFromFirst
     */
    public function testGetDiffFromFirst(Line $line, $expectedDiffFromFirst)
    {
        $actualDiffFromFirst = $line->getDiffFromFirst();
        $this->assertInternalType('string', $actualDiffFromFirst);
        $this->assertEquals($expectedDiffFromFirst, $actualDiffFromFirst);
    }

    public function providerGetDiffFromFirst()
    {
        $file = $this->getValidFile();
        return array(
            array($file->getLine(0), '+4.620'),
            array($file->getLine(5), '+0.880'),
            array($file->getLine(6), '-')
        );
    }

    /**
     * @param Line $line
     * @param string $expectedDriverClass
     *
     * @dataProvider providerGetDriverClass
     */
    public function testGetDriverClass(Line $line, $expectedDriverClass)
    {
        $actualDriverClass = $line->getDriverClass();
        $this->assertInternalType('string', $actualDriverClass);
        $this->assertEquals($expectedDriverClass, $actualDriverClass);
    }

    public function providerGetDriverClass()
    {
        $file = $this->getValidFile();
        return array(
        	array($file->getLine(0), 'STR'),
            array($file->getLine(5), 'STR'),
            array($file->getLine(8), 'STC'),
            array($file->getLine(16), 'BS')
        );
    }

    /**
     * @param Line $line
     *
     * @dataProvider providerGetDriverClassWithBadContent
     */
    public function testGetDriverClassWithBadContent(Line $line)
    {
        $this->setExpectedException(get_class(new LineException()));
        $line->getDriverClass();
    }

    public function providerGetDriverClassWithBadContent()
    {
        $file = $this->getBadFile();
        return array(
            array($file->getLine(4)),
            array($file->getLine(5)),
            array($file->getLine(6))
        );
    }

    /**
     * @param Line $line
     * @param string $expected
     *
     * @dataProvider providerGetTimeRawWithPenalty
     */
    public function testGetTimeRawWithPenalty(Line $line, $expected)
    {
        $actualTimeRawWithPenalty = $line->getTimeRawWithPenalty();
        $this->assertInternalType('string', $actualTimeRawWithPenalty);
        $this->assertTrue($actualTimeRawWithPenalty === $expected);
    }

    public function providerGetTimeRawWithPenalty()
    {
        $file = $this->getValidFile();
        return array(
        	array($file->getLine(0), '54.444'), // has +1
            array($file->getLine(1), '51.490'), // no penalty, time with trailing zeros
            array($file->getLine(8), '42.432'), // has DNF
            array($file->getLine(16), '67.648'), // has RRN
            array($file->getLine(19), '52.700') // has +1, time with trailing zeros
        );
    }

    /**
     * @param Line $Line
     * @param boolean $expected
     *
     * @dataProvider providerHasPenalty
     */
    public function testHasPenalty(Line $line, $expected)
    {
        $actualHasPenalty = $line->hasPenalty();
        $this->assertInternalType('boolean', $actualHasPenalty);
        $this->assertEquals($expected, $actualHasPenalty);
    }

    public function providerHasPenalty()
    {
        $file = $this->getValidFile();
        return array(
        	array($file->getLine(0), true), // +1
            array($file->getLine(1), false), // no penalty
            array($file->getLine(8), true), // DNF
            array($file->getLine(16), true) // RRN
        );
    }

    /**
     * @param Line $line
     * @param boolean $expected
     *
     * @dataProvider providerIsDnf
     */
    public function testIsDnf(Line $line, $expected)
    {
        $actualIsDnf = $line->isDnf();
        $this->assertInternalType('boolean', $actualIsDnf);
        $this->assertEquals($expected, $actualIsDnf);
    }

    public function providerIsDnf()
    {
        $file = $this->getValidFile();
        return array(
        	array($file->getLine(0), false), // +1
        	array($file->getLine(1), false), // clean
        	array($file->getLine(8), true), // DNF
        	array($file->getLine(16), false) // RRN
        );
    }

    /**
     * @param Line $line
     * @param boolean $expected
     *
     * @dataProvider providerIsRerun
     */
    public function testIsRerun(Line $line, $expected)
    {
        $actual = $line->isRerun();
        $this->assertInternalType('boolean', $actual);
        $this->assertEquals($expected, $actual);
    }

    public function providerIsRerun()
    {
        $file = $this->getValidFile();
        return array(
        	array($file->getLine(0), false), // +1
        	array($file->getLine(1), false), // clean
        	array($file->getLine(8), false), // DNF
        	array($file->getLine(16), true), // RRN
        );
    }

    /**
     * @param Line $line
     * @param float $expected
     *
     * @dataProvider providerGetTimeRawForSort
     */
    public function testGetTimeRawForSort(Line $line, $expected)
    {
        $actual = $line->getTimeRawForSort($line);
        $this->assertEquals($expected, $actual);
    }

    public function providerGetTimeRawForSort()
    {
        $file = $this->getValidFile();
        return array(
            array($file->getLine(0), 54.444), // 52.444 +1
            array($file->getLine(1), 51.490), // 51.490 clean
            array($file->getLine(8), PHP_INT_MAX), // 42.432 +DNF
            array($file->getLine(16), PHP_INT_MAX), // 67.648 +RRN
        );
    }

    /**
     * @param Line $line
     * @param boolean $expected
     *
     * @dataProvider providerIsClean
     */
    public function testIsClean(Line $line, $expected)
    {
        $actual = $line->isClean();
        $this->assertEquals($expected, $actual);
    }

    public function providerIsClean()
    {
        $file = $this->getValidFile();
        return array(
        	array($file->getLine(0), false), // not clean because +1
        	array($file->getLine(1), true), // clean
        	array($file->getLine(8), false), // not clean because DNF
        	array($file->getLine(16), false) // not clean because RRN
        );
    }

    private function getValidFile()
    {
        return new File($this->getValidContent(), $this->getMockCategories());
    }

    private function getBadFile()
    {
        return new File($this->getBadContent(), $this->getMockCategories());
    }

    private function getMockCategories()
    {
        return array(new Category(''), new Category('TIR'));
    }

    private function getValidContent()
    {
        return $this->loadAsset('ValidContent.st1');
    }

    private function getBadContent()
    {
        return $this->loadAsset('BadContent.st1');
    }

    private function loadAsset($name)
    {
        return file_get_contents(__DIR__ . '/assets/LineTest/' . $name);
    }

}
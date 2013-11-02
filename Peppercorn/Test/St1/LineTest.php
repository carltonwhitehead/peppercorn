<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\Line;
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

    private function getValidFile()
    {
        return new File($this->getValidContent(), array(new Category(''), new Category('TIR')));
    }

    private function getValidContent()
    {
        return $this->loadAsset('ValidContent.st1');
    }

    private function loadAsset($name)
    {
        return file_get_contents(__DIR__ . '/assets/LineTest/' . $name);
    }

}
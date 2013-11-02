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
        $this->assertEquals($expectedRunNumber, $line->getRunNumber());
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
     * @param string $expectedDriverClass
     *
     * @dataProvider providerGetDriverClass
     */
    public function testGetDriverClass(Line $line, $expectedDriverClass)
    {
        $driverClass = $line->getDriverClass();
        $this->assertNotNull($driverClass);
        $this->assertEquals($expectedDriverClass, $line->getDriverClass());
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
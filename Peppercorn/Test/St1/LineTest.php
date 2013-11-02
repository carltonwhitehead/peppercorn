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
        $this->assertEquals($expectedCategoryPrefix, $line->getDriverCategory()->getPrefix());
    }

    public function providerGetCategory()
    {
        $file = new File($this->getValidContent(), array(new Category(''), new Category('TIR')));
        return array(
        	array($file->getLine(0), ''),
            array($file->getLine(7), ''),
            array($file->getLine(12), 'TIR'),
            array($file->getLine(16), 'TIR')
        );
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
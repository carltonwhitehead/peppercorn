<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\File;
use Peppercorn\St1\Category;
use Phava\Exception\IllegalArgumentException;

class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $content
     *
     * @dataProvider providerValidContent
     */
    public function testInstantiateFile($content)
    {
        try {
            new File($content, array(new Category('')));
        } catch (\Exception $unexpected) {
            $this->fail("File threw unexpectedly with message:" . $unexpected->getMessage());
        }
    }

    public function providerValidContent()
    {
        $files = glob(__DIR__ . '/assets/FileTest/ValidContent_*.st1');
        $validContent = array();
        foreach ($files as $file)
        {
            $validContent[] = array(file_get_contents($file));
        }
        return $validContent;
    }

    /**
     * @param mixed $content
     *
     * @dataProvider providerInvalidContent
     */
    public function testInstantiateFileWithInvalidContent($content)
    {
        $this->setExpectedException(get_class(new IllegalArgumentException()));
        new File($content, array(new Category('')));
    }

    public function providerInvalidContent()
    {
        return array(
            array(null),
            array(0),
            array(0.5),
            array(array()),
            array(new \stdClass())
        );
    }

    /**
     * @param mixed $var
     *
     * @dataProvider providerInvalidCategories
     */
    public function testInstantiateFileWithInvalidCategories($var)
    {
        $this->setExpectedException(get_class(new IllegalArgumentException()));
        new File('', $var);
    }

    public function providerInvalidCategories()
    {
        return array(
            array(''),
            array(array()),
            array(0)
        );
    }

    public function testGetLineCount()
    {
        $expected = 3;
        $lines = $this->getLineMockData();
        $file = new File($lines, array(new Category('')));
        $this->assertEquals($expected, $file->getLineCount());
    }

    public function testGetLine()
    {
        $lines = $this->getLineMockData();
        $lineClass = '\\Peppercorn\\St1\Line';
        try {
            $file = new File($lines, array(new Category('')));
            $this->assertInstanceOf($lineClass, $file->getLine(0));
            $this->assertInstanceOf($lineClass, $file->getLine(1));
            $this->assertInstanceOf($lineClass, $file->getLine(2));
        } catch (Exception $unexpected) {
            $this->fail("File threw unexpectedly. Message: " . $unexpected->getMessage());
        }
    }

    private function getLineMockData()
    {
        return
        	'_run_1_' . PHP_EOL .
            '_run_2_' . PHP_EOL .
            '_run_3_' . PHP_EOL
        ;
    }

    /**
     * @param mixed $var
     *
     * @dataProvider providerGetLineWithInvalidArgument
     */
    public function testGetLineWithInvalidArgument($var)
    {
        $this->setExpectedException(get_class(new IllegalArgumentException()));
        $file = new File('', array(new Category('')));
        $file->getLine($var);
    }

    public function providerGetLineWithInvalidArgument()
    {
        return array(
            array(-1),
            array(null),
            array("0"),
            array(3),
            array(1.0)
        );
    }

    public function testGetCategoryPrefixes()
    {
        $categoryPrefixes = $this->getCategoryPrefixMockData();
        $categories = array();
        foreach ($categoryPrefixes as $categoryPrefix) {
            $categories[] = new Category($categoryPrefix);
        }
        $file = new File($this->getLineMockData(), $categories);
        $this->assertEquals($categoryPrefixes, $file->getCategoryPrefixes());
    }

    public function testGetCategoryByPrefix()
    {
        $categoryPrefixes = $this->getCategoryPrefixMockData();
        $categories = array();
        foreach ($categoryPrefixes as $categoryPrefix) {
            $categories[] = new Category($categoryPrefix);
        }
        $file = new File($this->getLineMockData(), $categories);
        $categoryClass = '\\Peppercorn\\St1\\Category';
        foreach ($categories as $category) {
            $testCategory = $file->getCategoryByPrefix($category->getPrefix());
            $this->assertInstanceOf($categoryClass, $testCategory);
            $this->assertEquals($category->getPrefix(), $testCategory->getPrefix());
        }
    }

    private function getCategoryPrefixMockData()
    {
        return array('', 'NOV', 'LAD', 'X');
    }

}
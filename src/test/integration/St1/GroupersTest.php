<?php
namespace Peppercorn\IntegrationTest\St1;

use Peppercorn\St1\Grouper;
use Peppercorn\St1\Line;
use Peppercorn\St1\GroupByDriver;
use Peppercorn\St1\Category;
use Peppercorn\St1\File;
class GroupersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 
     * @param Grouper $grouper
     * @param Line $line
     * @param unknown $expected
     * 
     * @dataProvider providerGetGroupKey
     */
    public function testGetGroupKey(Grouper $grouper, Line $line, $expected)
    {
        $actual = $grouper->getGroupKey($line);
        $this->assertEquals($expected, $actual);
    }
    
    public function providerGetGroupKey()
    {
        $file = $this->getValidFile();
        $groupByDriver = new GroupByDriver();
        return array(
        	array($groupByDriver, $file->getLine(0), '_class_STR_number_3'), // STR 3 Zach
        	array($groupByDriver, $file->getLine(6), '_class_STR_number_8'), // STR 8 Carlton
        	array($groupByDriver, $file->getLine(22), '_class_TIRBS_number_1'), // TIRBS 1 Carlton
        	array($groupByDriver, $file->getLine(28), '_class_BS_number_1'), // BS 1 Carlton
        );
    }
    
    /**
     * @return \Peppercorn\St1\File
     */
    private function getValidFile()
    {
        $content = file_get_contents(__DIR__ . '/assets/GroupersTest/ValidContent.st1');
        $categories = array(
        	new Category(''),
            new Category('TIR')
        );
        return new File($content, $categories);
    }
}
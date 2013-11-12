<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\Category;
use Peppercorn\St1\File;
use Peppercorn\St1\Line;
use Peppercorn\St1\WhereDriverIs;

/**
 * @todo test Peppercorn\St1\WhereDriverIs
 */
class WhereDriverIsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param Line $line
     *
     * @dataProvider providerTestWithValidContent
     */
    public function testTest(Line $line, WhereDriverIs $whereDriverIs)
    {
        $this->assertTrue($whereDriverIs->test($line));
    }

    public function providerTestWithValidContent()
    {
        $validFile = $this->getValidFile();
        $data = array();
        for ($i = 0; $i < $validFile->getLineCount(); $i++)
        {
            $line = $validFile->getLine($i);
            $whereDriverIs = new WhereDriverIs(
                $line->getDriverCategory(),
                $line->getDriverClass(),
                $line->getDriverNumber()
            );
            $data[] = array($line, $whereDriverIs);
        }
        return $data;
    }

    /**
     *
     * @return \Peppercorn\St1\File
     */
    private function getValidFile()
    {
        return new File($this->loadValidContent(), $this->getMockCategories());
    }

    private function loadValidContent()
    {
        return $this->loadAsset('ValidContent.st1');
    }

    private function getMockCategories()
    {
        return array(new Category(''), new Category('RT'));
    }


    private function loadAsset($name)
    {
        return file_get_contents(__DIR__ . '/assets/WhereDriverIsTest/' . $name);
    }
}
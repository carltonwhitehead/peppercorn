<?php
namespace Peppercorn\IntegrationTest\St1;

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
     * @throws Exception
     *
     * @dataProvider providerConstructor
     */
    public function testConstructor(Line $line)
    {
        try {
            $whereDriverIs = new WhereDriverIs($line->getDriverCategory(), $line->getDriverClass(), $line->getDriverNumber());
            $this->assertAttributeEquals($line->getDriverCategory(), 'driverCategory', $whereDriverIs);
            $this->assertAttributeEquals($line->getDriverClass(), 'driverClass', $whereDriverIs);
            $this->assertAttributeEquals($line->getDriverNumber(), 'driverNumber', $whereDriverIs);
        } catch (Exception $unexpected) {
            $this->fail('Something threw unexpectedly!');
            throw $unexpected;
        }
    }

    public function providerConstructor()
    {
        $validFile = $this->getValidFile();
        $dataSet = array();
        for ($i = 0; $i < $validFile->getLineCount(); $i++)
        {
            $line = $validFile->getLine($i);
            $dataSet[] = array($line);
        }
        return $dataSet;
    }

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
     * @param Line $line
     * @param WhereDriverIs $whereDriverIs
     *
     * @dataProvider providerTestWithInvalidContent
     */
    public function testTestWithInvalidContent(Line $line, WhereDriverIs $whereDriverIs)
    {
        $this->assertFalse($whereDriverIs->test($line));
    }

    public function providerTestWithInvalidContent()
    {
        $invalidFile = $this->getInvalidFile();
        $lineWithStrCarlton = $invalidFile->getLine(0);
        $lineWithTirCarlton = $invalidFile->getLine(1);
        $openCategory = $lineWithStrCarlton->getDriverCategory();
        $tirCategory = $lineWithTirCarlton->getDriverCategory();
        return array(
        	array($lineWithStrCarlton, new WhereDriverIs($openCategory, 'STR', '3')), // test wrong driver number
            array($lineWithStrCarlton, new WhereDriverIs($openCategory, 'BS', '8')), // test wrong driver class
            array($lineWithStrCarlton, new WhereDriverIs($tirCategory, 'STR', '8')), // test wrong driver category
            array($lineWithTirCarlton, new WhereDriverIs($tirCategory, 'BS', '9')), // test wrong driver number in category driver
            array($lineWithTirCarlton, new WhereDriverIs($tirCategory, 'BSP', '52')), // test wrong driver class in category driver
            array($lineWithTirCarlton, new WhereDriverIs($openCategory, 'BS', '52')) // test wrong driver category in catgory driver
        );
    }

    /**
     *
     * @return \Peppercorn\St1\File
     */
    private function getValidFile()
    {
        return new File($this->loadValidContent(), $this->getMockCategories());
    }

    /**
     *
     * @return \Peppercorn\St1\File
     */
    private function getInvalidFile()
    {
        return new File($this->loadInvalidContent(), $this->getMockCategories());
    }

    private function loadValidContent()
    {
        return $this->loadAsset('ValidContent.st1');
    }

    private function loadInvalidContent()
    {
        return $this->loadAsset('InvalidContent.st1');
    }

    private function getMockCategories()
    {
        return array(
            new Category(''),
            new Category('RT'),
            new Category('TIR')
        );
    }


    private function loadAsset($name)
    {
        return file_get_contents(__DIR__ . '/assets/WhereDriverIsTest/' . $name);
    }
}
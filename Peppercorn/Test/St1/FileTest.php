<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\File;
use Peppercorn\St1\Category;

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
        $files = glob(__DIR__ . '/../assets/valid_*.st1');
        $validContent = array();
        foreach ($files as $file)
        {
            $validContent[] = array(file_get_contents($file));
        }
        return $validContent;
    }

}
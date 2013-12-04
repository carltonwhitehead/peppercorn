<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\Category;
use Peppercorn\St1\File;
abstract class ResultsTest extends \PHPUnit_Framework_TestCase
{
    protected function getCategories()
    {
        return array(
            new Category(''),
            new Category('NOV'),
            new Category('LAD'),
            new Category('OF'),
            new Category('RT'),
            new Category('X'),
        );
    }
    
    protected function getFiles()
    {
        $categories = $this->getCategories();
        $paths = glob(__DIR__ . '/assets/ResultsTest/*.st1');
        $files = array();
        foreach($paths as $path) {
            $content = file_get_contents($path);
            $files[] = new File($content, $categories);
        }
        return $files;
    }
    
}
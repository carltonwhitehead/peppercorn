<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\Category;

class CategoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateAndGetPrefix()
    {
        $expectedPrefix = "RT";
        $category = new Category($expectedPrefix);
        $this->assertEquals($expectedPrefix, $category->getPrefix());
    }

    /**
     * @param mixed $var
     *
     * @dataProvider providerGarbagePrefixes
     */
    public function testInstantiageAndGetPrefixWithGarbage($var)
    {
        $this->setExpectedException(get_class(new \Phava\Exception\IllegalArgumentException()));
        $category = new Category($var);
    }

    public function providerGarbagePrefixes()
    {
        return array(
            array(0),
            array(null),
            array(array()),
            array(new \stdClass())
        );
    }
}
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

    /**
     * @param Category $one
     * @param Category $other
     * @param boolean $expectedValue
     *
     * @dataProvider providerEquals
     */
    public function testEquals(Category $one, Category $other, $expectedValue)
    {
        $this->assertEquals($expectedValue, $one->equals($other));
    }

    public function providerEquals()
    {
        return array(
        	array(new Category(''), new Category(''), true),
            array(new Category('RT', new Category('')), false),
            array(new Category(''), new Category('RT'), false)
        );
    }
}
<?php

namespace Peppercorn\IntegrationTest\Util;

class ComposableMultiSortIntegrationTest extends \PHPUnit_Framework_TestCase
{

    private $composableMultiSort;

    public function setUp()
    {
        $this->composableMultiSort = new \Peppercorn\Util\ComposableMultiSort();
    }

    public function testBasicSort()
    {
        $data = array(
            array('type' => 'fruit', 'name' => 'orange'),
            array('type' => 'vegetable', 'name' => 'carrot'),
            array('type' => 'meat', 'name' => 'beef'),
            array('type' => 'fruit', 'name' => 'banana'),
            array('type' => 'vegetable', 'name' => 'cabbage'),
            array('type' => 'vegetable', 'name' => 'arugula')
        );
        $this->composableMultiSort
                ->addSort(function($a, $b) {
                    return strnatcasecmp($a['type'], $b['type']);
                })
                ->addSort(function($a, $b) {
                    return strnatcasecmp($a['name'], $b['name']);
                });

        usort($data, $this->composableMultiSort);

        $this->assertEquals('fruit', $data[0]['type']);
        $this->assertEquals('banana', $data[0]['name']);
        $this->assertEquals('fruit', $data[1]['type']);
        $this->assertEquals('orange', $data[1]['name']);
        $this->assertEquals('meat', $data[2]['type']);
        $this->assertEquals('beef', $data[2]['name']);
        $this->assertEquals('vegetable', $data[3]['type']);
        $this->assertEquals('arugula', $data[3]['name']);
        $this->assertEquals('vegetable', $data[4]['type']);
        $this->assertEquals('cabbage', $data[4]['name']);
        $this->assertEquals('vegetable', $data[5]['type']);
        $this->assertEquals('carrot', $data[5]['name']);
    }

}

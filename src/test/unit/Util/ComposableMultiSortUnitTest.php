<?php

namespace Peppercorn\UnitTest\Util;

use Peppercorn\Util\ComposableMultiSort;

class ComposableMultiSortUnitTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var \Peppercorn\Util\ComposableMultiSort
     */
    private $composableMultiSort;

    public function setUp()
    {
        $this->composableMultiSort = new ComposableMultiSort();
    }

    public function testWhenSortZeroOfZeroReturnsPositiveOneItShouldReturnIt()
    {
        $a = 'a';
        $b = 'b';
        $sortMock = $this->getMock('\\Peppercorn\\Test\\Mock\\Sort');
        $sortMock->expects($this->once())
                ->method('sort')
                ->with($this->equalTo($a), $this->equalTo($b))
                ->willReturn(1);
        $this->composableMultiSort->addSort(array($sortMock, 'sort'));
        $expected = 1;

        $actual = $this->composableMultiSort->compare($a, $b);

        $this->assertEquals($expected, $actual);
    }

    public function testWhenSortZeroOfZeroReturnsNegativeOneItShouldReturnIt()
    {
        $a = 'a';
        $b = 'b';
        $sortMock = $this->getMock('\\Peppercorn\\Test\\Mock\\Sort');
        $sortMock->expects($this->once())
                ->method('sort')
                ->with($this->equalTo($a), $this->equalTo($b))
                ->willReturn(-1);
        $this->composableMultiSort->addSort(array($sortMock, 'sort'));
        $expected = -1;

        $actual = $this->composableMultiSort->compare($a, $b);

        $this->assertEquals($expected, $actual);
    }

    public function testWhenSortZeroOfOneReturnsZeroItShouldReturnSortOneOfOne()
    {
        $a = 'a';
        $b = 'b';
        $sortMock0 = $this->getMock('\\Peppercorn\\Test\\Mock\\Sort');
        $sortMock1 = $this->getMock('\\Peppercorn\\Test\\Mock\\Sort');
        $sortMock0->expects($this->once())
                ->method('sort')
                ->with($this->equalTo($a), $this->equalTo($b))
                ->willReturn(0);
        $sortMock1->expects($this->once())
                ->method('sort')
                ->with($this->equalTo($a), $this->equalTo($b))
                ->willReturn(1);
        $this->composableMultiSort
                ->addSort(array($sortMock0, 'sort'))
                ->addSort(array($sortMock1, 'sort'));
        $expected = 1;

        $actual = $this->composableMultiSort->compare($a, $b);

        $this->assertEquals($expected, $actual);
    }

    public function testWhenNoSortsAddedItShouldAlwaysCompareAsEquals()
    {
        $a = 'asdf';
        $b = 'jkl';
        $expected = 0;

        $actual = $this->composableMultiSort->compare($a, $b);

        $this->assertEquals($expected, $actual);
    }

}

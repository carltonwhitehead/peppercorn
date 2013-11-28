<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\Category;
use Peppercorn\St1\File;
use Peppercorn\St1\Line;
use Peppercorn\St1\Query;
use Peppercorn\St1\WhereDriverIs;
use Peppercorn\St1\Grouper;
use Peppercorn\St1\GroupByDriver;

class QueryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param File $file
     *
     * @dataProvider providerFileInstance
     */
    public function testInstantiate(File $file)
    {
        $query = new Query($file);
        $this->assertAttributeInternalType('array', 'wheres', $query);
        $this->assertAttributeCount(0, 'wheres', $query);
        $this->assertAttributeInstanceOf(get_class($file), 'file', $query);
    }

    public function providerFileInstance()
    {
        $file = $this->getValidFile();
        return array(
            array($file)
        );
    }

    /**
     * @param Query $query
     *
     * @dataProvider providerQueryOfValidFile
     */
    public function testSetTestLinesAscending(Query $query)
    {
        $query->setTestLinesAscending();
        $this->assertAttributeEquals('ascending', 'testLinesDirection', $query);
    }

    /**
     * @param Query $query
     *
     * @dataProvider providerQueryOfValidFile
     */
    public function testSetTestLinesDescending(Query $query)
    {
        $query->setTestLinesDescending();
        $this->assertAttributeEquals('descending', 'testLinesDirection', $query);
    }

    /**
     * @param Query $query
     *
     * @dataProvider providerQueryOfValidFileWithWhere
     */
    public function testWhere(Query $query, WhereDriverIs $where)
    {
        $actualReturnFromWhere = $query->where($where);
        $expectedReturnFromWhere = get_class($query);
        $this->assertInstanceOf($expectedReturnFromWhere, $actualReturnFromWhere);
        $this->assertAttributeCount(1, 'wheres', $query);
        $this->assertAttributeContains($where, 'wheres', $query);
    }

    public function providerQueryOfValidFileWithWhere()
    {
        $file = $this->getValidFile();
        return array(
        	array(new Query($file), new WhereDriverIs(new Category(''), 'STR', '8')),
            array(new Query($file), new WhereDriverIs(new Category(''), 'STR', '3'))
        );
    }

    /**
     * @param File $file
     * @param Query $query
     *
     * @dataProvider providerValidFileAndDefaultQuery
     */
    public function testExecuteWithValidFileAndDefaultQuery(File $file, Query $query)
    {
        $result = $query->execute();
        $this->assertOnResultFromDefaultQueryInAscendingOrder($file, $result);
    }

    private function assertOnResultFromDefaultQueryInAscendingOrder(File $file, array $result)
    {
        $this->assertInternalType('array', $result);
        $this->assertCount($file->getLineCount(), $result);
        $result0 = $result[0]; /* @var $result0 Line */
        $this->assertEquals("1", $result0->getRunNumber());
        $result14 = $result[14]; /* @var $result15 Line */
        $this->assertEquals("7", $result14->getRunNumber());
    }

    /**
     * @param File $file
     * @param Query $query
     *
     * @dataProvider providerValidFileAndDefaultQuery
     */
    public function testExecuteWithValidFileAndTestLinesAscendingQuery(File $file, Query $query)
    {
        $query->setTestLinesAscending();
        $result = $query->execute();
        $this->assertOnResultFromDefaultQueryInAscendingOrder($file, $result);
    }

    /**
     * @param File $file
     * @param Query $query
     *
     * @dataProvider providerValidFileAndDefaultQuery
     */
    public function testExecuteWithValidFileAndTestLinesDescendingQuery(File $file, Query $query)
    {
        $query->setTestLinesDescending();
        $result = $query->execute();
        $this->assertInternalType('array', $result);
        $this->assertCount($file->getLineCount(), $result);
        $result0 = $result[0]; /* @var $result0 Line */
        $this->assertEquals("7", $result0->getRunNumber());
        $result14 = $result[14]; /* @var $result15 Line */
        $this->assertEquals("1", $result14->getRunNumber());
    }

    /**
     * @param Query $query
     *
     * @dataProvider providerWhereTestReturnsFalse
     */
    public function testWhereTestReturnsFalse(Query $query)
    {
        $query->where(new WhereFalse());
        $result = $query->execute();
        $this->assertInternalType('array', $result);
        $this->assertCount(0, $result);
    }

    public function providerWhereTestReturnsFalse()
    {
        $file = $this->getValidFile();
        $testLinesAscendingQuery = new Query($file);
        $testLinesDescendingQuery = new Query($file);
        return array(
            array(new Query($file)),
            array($testLinesAscendingQuery->setTestLinesAscending()),
            array($testLinesDescendingQuery->setTestLinesDescending())
        );
    }

    public function providerQueryOfValidFile()
    {
        return array(
            array($this->getQueryOfValidFile())
        );
    }

    public function providerValidFileAndDefaultQuery()
    {
        $file = $this->getValidFile();
        $query = new Query($file);
        return array(
        	array($file, $query)
        );
    }

    public function testOrderBy()
    {
        $query = $this->getQueryOfValidFile();
        $result = $query->orderBy(function (Line $a, Line $b) {
            return 0;
        });
        $this->assertAttributeInternalType('callable', 'sort', $query);
        $this->assertInstanceOf(get_class($query), $result);
    }

    public function testSortedQueryResults()
    {
        // an absurd sort by raw time descending (slowest first, fastest last)
        $sortByRawTimeDesc = function(Line $a, Line $b) {
            $aTime = $a->getTimeRawForSort();
            $bTime = $b->getTimeRawForSort();
            if ($aTime === $bTime) {
                return 0;
            }
            return $a->getTimeRawForSort() > $b->getTimeRawForSort()
                ? -1 : 1;
        };
        $file = $this->getValidFile();
        $query = new Query($file);
        $query->orderBy($sortByRawTimeDesc);
        $results = $query->execute();
        $this->assertCount($file->getLineCount(), $results);
        $this->assertEquals(PHP_INT_MAX, $results[0]->getTimeRawForSort()); // DNF
        $this->assertEquals(PHP_INT_MAX, $results[1]->getTimeRawForSort()); // DNF
        $this->assertEquals(60.713, $results[13]->getTimeRawForSort()); // 60.713 clean
        $this->assertEquals(59.970, $results[14]->getTimeRawForSort()); // 59.970 clean
    }
    
    /**
     * @param Query $query
     * @param Grouper $distinct
     * 
     * @dataProvider providerDistinct
     */
    public function testDistinct(Query $query, Grouper $distinct)
    {
        $this->assertAttributeEmpty('distinct', $query);
        $actual = $query->distinct($distinct);
        $this->assertAttributeEquals($distinct, 'distinct', $query);
        $this->assertInstanceOf(get_class($query), $actual);
    }
    
    public function providerDistinct()
    {
        $validFile = $this->getQueryOfValidFile();
        $groupByDriver = new GroupByDriver();
        return array(
        	array($validFile, $groupByDriver)
        );
    }

    private function getQueryOfValidFile()
    {
        $file = $this->getValidFile();
        return new Query($file);
    }

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
        return file_get_contents(__DIR__ . '/assets/QueryTest/' . $name);
    }
}
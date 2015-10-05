<?php

namespace Peppercorn\IntegrationTest\St1;

use Peppercorn\St1\Category;
use Peppercorn\St1\File;
use Peppercorn\St1\GroupByDriver;
use Peppercorn\St1\Grouper;
use Peppercorn\St1\Line;
use Peppercorn\St1\Query;
use Peppercorn\St1\ResultSetSimple;
use Peppercorn\St1\WhereDriverIs;
use Peppercorn\Test\Query\WhereFalse;
use PHPUnit_Framework_TestCase;

class QueryTest extends PHPUnit_Framework_TestCase {

    /**
     * @param File $file
     *
     * @dataProvider providerFileInstance
     */
    public function testInstantiate(File $file) {
        $query = new Query($file);
        $this->assertAttributeInternalType('array', 'wheres', $query);
        $this->assertAttributeCount(0, 'wheres', $query);
        $this->assertAttributeInstanceOf(get_class($file), 'file', $query);
    }

    public function providerFileInstance() {
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
    public function testSetTestLinesAscending(Query $query) {
        $actual = $query->setTestLinesAscending();
        $this->assertTrue($query === $actual);
        $this->assertAttributeEquals('ascending', 'testLinesDirection', $query);
    }

    /**
     * @param Query $query
     *
     * @dataProvider providerQueryOfValidFile
     */
    public function testSetTestLinesDescending(Query $query) {
        $actual = $query->setTestLinesDescending();
        $this->assertTrue($query === $actual);
        $this->assertAttributeEquals('descending', 'testLinesDirection', $query);
    }

    /**
     * @param Query $query
     *
     * @dataProvider providerQueryOfValidFileWithWhere
     */
    public function testWhere(Query $query, WhereDriverIs $where) {
        $actual = $query->where($where);
        $this->assertTrue($query === $actual);
        $this->assertAttributeCount(1, 'wheres', $query);
        $this->assertAttributeContains($where, 'wheres', $query);
    }

    public function providerQueryOfValidFileWithWhere() {
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
    public function testExecuteWithValidFileAndDefaultQuery(File $file, Query $query) {
        $result = $query->executeSimple();
        $this->assertOnResultFromDefaultQueryInAscendingOrder($file, $result);
    }

    private function assertOnResultFromDefaultQueryInAscendingOrder(File $file, $result) {
        $this->assertInstanceOf('\\Peppercorn\\St1\\ResultSetSimple', $result); /* @var $result ResultSetSimple */
        $this->assertCount($file->getLineCount(), $result);
        $result0 = $result->getLine(0);
        $this->assertEquals("1", $result0->getRunNumber());
        $result14 = $result->getLine(14);
        $this->assertEquals("7", $result14->getRunNumber());
    }

    /**
     * @param File $file
     * @param Query $query
     *
     * @dataProvider providerValidFileAndDefaultQuery
     */
    public function testExecuteWithValidFileAndTestLinesAscendingQuery(File $file, Query $query) {
        $query->setTestLinesAscending();
        $result = $query->executeSimple();
        $this->assertOnResultFromDefaultQueryInAscendingOrder($file, $result);
    }

    /**
     * @param File $file
     * @param Query $query
     *
     * @dataProvider providerValidFileAndDefaultQuery
     */
    public function testExecuteWithValidFileAndTestLinesDescendingQuery(File $file, Query $query) {
        $query->setTestLinesDescending();
        $result = $query->executeSimple();
        $this->assertInstanceOf('\\Peppercorn\\St1\\ResultSetSimple', $result);
        $this->assertCount($file->getLineCount(), $result);
        $result0 = $result->getLine(0);
        $this->assertEquals("7", $result0->getRunNumber());
        $result14 = $result->getLine(14);
        $this->assertEquals("1", $result14->getRunNumber());
    }

    /**
     * @param Query $query
     *
     * @dataProvider providerWhereTestReturnsFalse
     */
    public function testWhereTestReturnsFalse(Query $query) {
        $query->where(new WhereFalse());
        $result = $query->executeSimple();
        $this->assertInstanceOf('\\Peppercorn\\St1\\ResultSetSimple', $result);
        $this->assertCount(0, $result);
    }

    public function providerWhereTestReturnsFalse() {
        $file = $this->getValidFile();
        $testLinesAscendingQuery = new Query($file);
        $testLinesDescendingQuery = new Query($file);
        return array(
            array(new Query($file)),
            array($testLinesAscendingQuery->setTestLinesAscending()),
            array($testLinesDescendingQuery->setTestLinesDescending())
        );
    }

    public function providerQueryOfValidFile() {
        return array(
            array($this->getQueryOfValidFile())
        );
    }

    public function providerValidFileAndDefaultQuery() {
        $file = $this->getValidFile();
        $query = new Query($file);
        return array(
            array($file, $query)
        );
    }

    public function testOrderBy() {
        $query = $this->getQueryOfValidFile();
        $actual = $query->orderBy(function (Line $a, Line $b) {
            return 0;
        });
        $this->assertAttributeInternalType('callable', 'sort', $query);
        $this->assertTrue($query === $actual);
    }

    public function testSortedQueryResults() {
        // an absurd sort by raw time descending (slowest first, fastest last)
        $sortByRawTimeDesc = function(Line $a, Line $b) {
            $aTime = $a->getTimeRawForSort();
            $bTime = $b->getTimeRawForSort();
            if ($aTime === $bTime) {
                return 0;
            }
            return $a->getTimeRawForSort() > $b->getTimeRawForSort() ? -1 : 1;
        };
        $file = $this->getValidFile();
        $query = new Query($file);
        $query->orderBy($sortByRawTimeDesc);
        $results = $query->executeSimple();
        $this->assertEquals($file->getLineCount(), $results->getCount());
        $this->assertEquals(Line::getPenaltyRrn(), $results->getLine(0)->getTimeRawForSort()); // DNF
        $this->assertEquals(Line::getPenaltyDnf(), $results->getLine(1)->getTimeRawForSort()); // DNF
        $this->assertEquals(60.713, $results->getLine(13)->getTimeRawForSort()); // 60.713 clean
        $this->assertEquals(59.970, $results->getLine(14)->getTimeRawForSort()); // 59.970 clean
    }

    /**
     * @param Query $query
     * @param Grouper $distinct
     * 
     * @dataProvider providerDistinct
     */
    public function testDistinct(Query $query, Grouper $distinct) {
        $this->assertAttributeEmpty('distinct', $query);
        $actual = $query->distinct($distinct);
        $this->assertAttributeEquals($distinct, 'distinct', $query);
        $this->assertTrue($query === $actual);
    }

    public function providerDistinct() {
        $validFile = $this->getQueryOfValidFile();
        $groupByDriver = new GroupByDriver();
        return array(
            array($validFile, $groupByDriver)
        );
    }

    /**
     * @param Query $query
     * @param Grouper $groupBy
     * 
     * @dataProvider providerGroupBy
     */
    public function testGroupBy(Query $query, Grouper $groupBy) {
        $this->assertAttributeEmpty('groupBy', $query);
        $actual = $query->groupBy($groupBy);
        $this->assertAttributeEquals($groupBy, 'groupBy', $query);
        $this->assertTrue($query === $actual);
    }

    public function providerGroupBy() {
        return array(
            array($this->getQueryOfValidFile(), new GroupByDriver())
        );
    }

    /**
     * @param Query $query a query already set up to execute with distinct drivers
     * 
     * @dataProvider providerExecuteWithDistinctDrivers
     */
    public function testExecuteWithDistinctDrivers(Query $query) {
        $actual = $query->executeSimple();
        $this->assertEquals(2, $actual->getCount());
        $this->assertEquals('Zach Hill', $actual->getLine(0)->getDriverName());
        $this->assertEquals('Carlton Whitehead', $actual->getLine(1)->getDriverName());
    }

    public function providerExecuteWithDistinctDrivers() {
        $query = $this->getQueryOfValidFile();
        return array(array($query->distinct(new GroupByDriver())));
    }

    /**
     * @param Query $query
     * 
     * @dataProvider providerExecuteGroupedWithGroupByDrivers
     */
    public function testExecuteGroupedWithGroupByDrivers(Query $query, $expectedKey, $expectedCount) {
        $actual = $query->executeGrouped();
        $this->assertCount(2, $actual); // two drivers, Zach and Carlton
        $actualResultsGroupKeys = $actual->getResultsGroupKeys();
        $this->assertContains($expectedKey, $actualResultsGroupKeys);
        $actualResultsGroup = $actual->getResultsGroup($expectedKey);
        $this->assertCount($expectedCount, $actualResultsGroup);
    }

    public function providerExecuteGroupedWithGroupByDrivers() {
        $query = $this->getQueryOfValidFile();

        return [
            [
                $this->getQueryOfValidFile()->groupBy(new GroupByDriver()),
                '_class_STR_number_3',
                8
            ],
            [
                $this->getQueryOfValidFile()->groupBy(new GroupByDriver()),
                '_class_STR_number_8',
                7
            ]
        ];

        return array(array($query->groupBy(new GroupByDriver())));
    }

    private function getQueryOfValidFile() {
        $file = $this->getValidFile();
        return new Query($file);
    }

    private function getValidFile() {
        return new File($this->loadValidContent(), $this->getMockCategories());
    }

    private function loadValidContent() {
        return $this->loadAsset('ValidContent.st1');
    }

    private function getMockCategories() {
        return array(new Category(''), new Category('RT'));
    }

    private function loadAsset($name) {
        return file_get_contents(__DIR__ . '/assets/QueryTest/' . $name);
    }

}

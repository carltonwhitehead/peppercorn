<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\File;
use Peppercorn\St1\Line;
use Peppercorn\St1\Query;
use Peppercorn\St1\SortTimeRawAscending;
use Peppercorn\St1\GroupByDriver;
use Peppercorn\St1\LineException;
use Peppercorn\St1\SortTieBreakerByNextFastestTimeRaw;
use Peppercorn\St1\Result;
use Peppercorn\St1\ResultSetSimple;

class PaxResultsTest extends ResultsTest
{
    /**
     * 
     * @param ResultSetSimple $resultSet
     * @param int $resultIndex
     * @param string $class
     * @param string $number
     * @param string $time
     * 
     * @dataProvider providerPaxResults
     */
    public function testPaxResults(ResultSetSimple $resultSet, $resultIndex, $class, $number, $time)
    {
        $this->assertGreaterThan($resultIndex, $resultSet->getCount());
        $result = $resultSet->getIndex($resultIndex);
        $this->assertEquals($resultIndex + 1, $result->getPlace());
        $line = $resultSet->getLine($resultIndex); /* @var $line Line */
        $this->assertEquals($class, $line->getDriverClassRaw());
        $this->assertEquals($number, $line->getDriverNumber());
        $this->assertEquals($time, $line->getTimePax());
    }
    
    public function providerPaxResults()
    {
        $provider = array();
        $results = array();
        $files = $this->getFiles();
        foreach ($files as $file /* @var $file File */) {
            $results[] = Query::paxResults($file);
        }
        // points1
        $result = $results[0];
        $provider[] = array($result, 0 , 'XSTS', '155', '40.892');
        $provider[] = array($result, 1, 'RTDSRT', '111', '41.010');
        $provider[] = array($result, 2, 'STR', '164', '41.802');
        $provider[] = array($result, 3, 'STR', '8', '42.540');
        $provider[] = array($result, 4, 'XSTX', '4', '42.614');
        $provider[] = array($result, 9, 'STR', '0', '43.675');
        $provider[] = array($result, 19, 'STR', '86', '44.482');
        $provider[] = array($result, 29, 'SM', '1', '46.040');
        $provider[] = array($result, 39, 'SM', '11', '47.122');
        $provider[] = array($result, 49, 'NOVSTX', '11', '48.344');
        $provider[] = array($result, 54, 'FS', '1', '50.503');
        $provider[] = array($result, 55, 'CSP', '24', '52.701');
        $provider[] = array($result, 56, 'RTFPRT', '61', '52.747');
        
        return $provider;
    }
}
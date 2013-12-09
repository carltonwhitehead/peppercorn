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
        $provider[] = array($result, 0, 'XSTS', '155', '40.892');
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
        // points2
        $result = $results[1];
        $provider[] = array($result, 0, 'XSTU', '37', '38.577');
        $provider[] = array($result, 1, 'XSTS', '155', '38.699');
        $provider[] = array($result, 2, 'XDS', '86', '38.975');
        $provider[] = array($result, 3, 'XDS', '186', '39.007');
        $provider[] = array($result, 4, 'RTFSRT', '6', '39.171');
        $provider[] = array($result, 5, 'STR', '8', '39.181');
        $provider[] = array($result, 6, 'XSTC', '59', '39.255');
        $provider[] = array($result, 7, 'STR', '3', '39.420');
        $provider[] = array($result, 8, 'STR', '0', '39.442');
        $provider[] = array($result, 9, 'XSS', '66', '39.504');
        $provider[] = array($result, 19, 'STR', '1', '40.282');
        $provider[] = array($result, 29, 'STX', '41', '41.575');
        $provider[] = array($result, 39, 'CS', '73', '42.691');
        $provider[] = array($result, 49, 'STU', '7', '43.618');
        $provider[] = array($result, 59, 'BSP', '1', '44.189');
        $provider[] = array($result, 69, 'ES', '1', '45.138');
        $provider[] = array($result, 79, 'NOVSTU', '10', '46.399');
        $provider[] = array($result, 89, 'NOVDSP', '4', '49.595');
        $provider[] = array($result, 92, 'SM', '10', '52.333');
        $provider[] = array($result, 93, 'SM', '9', '53.710');
        $provider[] = array($result, 94, 'XSTS', '15', 'DSQ');
        
        return $provider;
    }
}
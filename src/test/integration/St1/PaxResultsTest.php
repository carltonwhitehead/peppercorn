<?php
namespace Peppercorn\IntegrationTest\St1;

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
        // points3
        $result = $results[2];
        $provider[] = array($result, 0, 'RTFSRT', '6', '37.798');
        $provider[] = array($result, 1, 'STR', '0', '38.246');
        $provider[] = array($result, 2, 'DS', '78', '38.551');
        $provider[] = array($result, 3, 'DS', '15', '38.568');
        $provider[] = array($result, 4, 'STX', '44', '38.911');
        $provider[] = array($result, 5, 'RTCSRT', '5', '38.934');
        $provider[] = array($result, 6, 'RTHSRT', '7', '38.971');
        $provider[] = array($result, 7, 'SS', '1', '39.317');
        $provider[] = array($result, 8, 'STR', '80', '39.536');
        $provider[] = array($result, 9, 'RTCSRT', '14', '39.543');
        $provider[] = array($result, 19, 'DM', '14', '40.404');
        $provider[] = array($result, 29, 'RTGSRT', '247', '41.602');
        $provider[] = array($result, 39, 'SSM', '2', '42.426');
        $provider[] = array($result, 49, 'NOVFS', '88', '43.796');
        $provider[] = array($result, 59, 'RTFPRT', '16', '44.714');
        $provider[] = array($result, 69, 'NOVDS', '221', '45.538');
        $provider[] = array($result, 79, 'RTHSRT', '4', '46.570');
        $provider[] = array($result, 86, 'NOVFP', '61', '48.861');
        $provider[] = array($result, 87, 'STC', '87', '49.154');
        $provider[] = array($result, 88, 'NOVDM', '44', '49.192');
        // points4
        $result = $results[3];
        $provider[] = array($result, 0, 'XSTU', '37', '32.219');
        $provider[] = array($result, 1, 'STR', '3', '32.446');
        $provider[] = array($result, 2, 'XSTC', '59', '32.528');
        $provider[] = array($result, 3, 'STR', '0', '32.620');
        $provider[] = array($result, 4, 'RTCS', '5', '32.633');
        $provider[] = array($result, 5, 'STR', '80', '32.688');
        $provider[] = array($result, 6, 'STR', '4', '32.799');
        $provider[] = array($result, 7, 'STX', '44', '32.809');
        $provider[] = array($result, 8, 'SS', '1', '32.834');
        $provider[] = array($result, 9, 'STR', '1', '33.296');
        $provider[] = array($result, 19, 'XSS', '166', '34.072');
        $provider[] = array($result, 29, 'RTDSP', '26', '34.924');
        $provider[] = array($result, 39, 'RTBSP', '28', '35.279');
        $provider[] = array($result, 49, 'BSP', '3', '36.458');
        $provider[] = array($result, 59, 'NOVSTR', '33', '37.036');
        $provider[] = array($result, 69, 'SSM', '6', '38.249');
        $provider[] = array($result, 73, 'NOVHS', '19', '39.922');
        $provider[] = array($result, 74, 'ESP', '8', '40.732');
        $provider[] = array($result, 75, 'ES', '77', '41.053');
        // points5
        $result = $results[4];
        $provider[] = array($result, 0, 'STR', '0', '43.060');
        $provider[] = array($result, 1, 'STR', '80', '43.203');
        $provider[] = array($result, 2, 'RTCS', '5', '43.549');
        $provider[] = array($result, 3, 'STX', '44', '43.595');
        $provider[] = array($result, 4, 'CSP', '46', '43.595');
        $provider[] = array($result, 5, 'SS', '1', '43.649');
        $provider[] = array($result, 6, 'STR', '3', '43.701');
        $provider[] = array($result, 7, 'RTHS', '7', '43.825');
        $provider[] = array($result, 8, 'STR', '8', '43.865');
        $provider[] = array($result, 9, 'RTESP', '8', '43.872');
        $provider[] = array($result, 19, 'STR', '48', '45.099');
        $provider[] = array($result, 29, 'STR', '42', '46.424');
        $provider[] = array($result, 39, 'RTDSP', '26', '47.756');
        $provider[] = array($result, 49, 'SSM', '6', '50.564');
        $provider[] = array($result, 58, 'ES', '77', '54.503');
        $provider[] = array($result, 59, 'NOVSM', '99', '55.260');
        $provider[] = array($result, 60, 'NOVSTF', '2', '60.415');
        // points6
        $result = $results[5];
        $provider[] = array($result, 0, 'RTDS', '97', '27.192');
        $provider[] = array($result, 1, 'RTDS', '197', '27.379');
        $provider[] = array($result, 2, 'XSTU', '37', '27.587');
        $provider[] = array($result, 3, 'STR', '3', '27.733');
        $provider[] = array($result, 4, 'RTDS', '79', '27.743');
        $provider[] = array($result, 5, 'SS', '19', '27.866');
        $provider[] = array($result, 6, 'STR', '0', '27.918');
        $provider[] = array($result, 7, 'RTFS', '6', '27.941');
        $provider[] = array($result, 8, 'CM', '85', '28.000');
        $provider[] = array($result, 9, 'STR', '80', '28.166');
        $provider[] = array($result, 19, 'RTHS', '0', '28.835');
        $provider[] = array($result, 29, 'STR', '42', '29.457');
        $provider[] = array($result, 39, 'STR', '32', '30.202');
        $provider[] = array($result, 49, 'NOVES', '2', '30.965');
        $provider[] = array($result, 59, 'RTBSP', '37', '31.809');
        $provider[] = array($result, 69, 'NOVSTF', '82', '33.131');
        $provider[] = array($result, 79, 'SSM', '00', '34.476');
        $provider[] = array($result, 83, 'DM', '14', '35.874');
        $provider[] = array($result, 84, 'NOVAS', '7', '36.039');
        $provider[] = array($result, 85, 'NOVSM', '15', '41.446');
        // points7
        $result = $results[6];
        $provider[] = array($result, 0, 'SS', '1', '27.465');
        $provider[] = array($result, 1, 'RTCS', '5', '27.506');
        $provider[] = array($result, 2, 'XSTU', '37', '27.649');
        $provider[] = array($result, 3, 'STR', '3', '27.676');
        $provider[] = array($result, 4, 'STR', '0', '27.770');
        $provider[] = array($result, 5, 'RTCS', '14', '28.094');
        $provider[] = array($result, 6, 'STR', '8', '28.266');
        $provider[] = array($result, 7, 'STX', '17', '28.282');
        $provider[] = array($result, 8, 'STX', '1', '28.304');
        $provider[] = array($result, 9, 'STR', '4', '28.445');
        $provider[] = array($result, 19, 'RTCS', '3', '29.314');
        $provider[] = array($result, 29, 'STC', '78', '29.673');
        $provider[] = array($result, 39, 'SSM', '1', '30.436');
        $provider[] = array($result, 49, 'SM', '83', '31.112');
        $provider[] = array($result, 59, 'NOVSTX', '10', '32.201');
        $provider[] = array($result, 69, 'NOVDM', '44', '35.035');
        $provider[] = array($result, 71, 'NOVSM', '76', '37.105');
        $provider[] = array($result, 72, 'NOVDSP', '6', '37.778');
        $provider[] = array($result, 73, 'DS', '5', 'DNF');
        // points 8
        $result = $results[7];
        $provider[] = array($result, 0, 'XDS', '97', '36.598');
        $provider[] = array($result, 1, 'XDS', '78', '38.035');
        $provider[] = array($result, 2, 'XASP', '9', '38.063');
        $provider[] = array($result, 3, 'XSTS', '28', '38.076');
        $provider[] = array($result, 4, 'STR', '3', '38.077');
        $provider[] = array($result, 5, 'STR', '180', '38.484');
        $provider[] = array($result, 6, 'STR', '80', '38.557');
        $provider[] = array($result, 7, 'STR', '8', '38.630');
        $provider[] = array($result, 8, 'STR', '44', '38.635');
        $provider[] = array($result, 9, 'STR', '144', '38.698');
        $provider[] = array($result, 18, 'RTCS', '79', '39.312');
        $provider[] = array($result, 19, 'RTCS', '1', '39.312');
        $provider[] = array($result, 29, 'RTCS', '73', '39.854');
        $provider[] = array($result, 39, 'SMF', '20', '40.432');
        $provider[] = array($result, 49, 'RTCS', '86', '41.120');
        $provider[] = array($result, 59, 'STF', '1', '41.952');
        $provider[] = array($result, 69, 'NOVES', '2', '42.584');
        $provider[] = array($result, 79, 'STR', '1', '43.050');
        $provider[] = array($result, 89, 'NOVBS', '94', '44.081');
        $provider[] = array($result, 99, 'HS', '86', '44.925');
        $provider[] = array($result, 109, 'STX', '2', '46.168');
        $provider[] = array($result, 119, 'NOVFS', '91', '47.416');
        $provider[] = array($result, 129, 'LADSTR', '88', '50.677');
        $provider[] = array($result, 133, 'NOVBS', '121', '52.786');
        $provider[] = array($result, 134, 'NOVCS', '29', '53.326');
        $provider[] = array($result, 135, 'STR', '24', 'DNF');
        $provider[] = array($result, 136, 'LADDSP', '1', 'DNF');
        // points 9
        $result = $results[8];
        $provider[] = array($result, 0, 'XKM', '50', '49.432');
        $provider[] = array($result, 1, 'STR', '80', '50.298');
        $provider[] = array($result, 2, 'STR', '3', '50.314');
        $provider[] = array($result, 3, 'STR', '0', '51.047');
        $provider[] = array($result, 4, 'RTCS', '5', '51.101');
        $provider[] = array($result, 5, 'RTDS', '66', '51.472');
        $provider[] = array($result, 6, 'RTHS', '7', '51.886');
        $provider[] = array($result, 7, 'DS', '78', '51.958');
        $provider[] = array($result, 8, 'RTCS', '14', '52.075');
        $provider[] = array($result, 9, 'STC', '78', '52.608');
        $provider[] = array($result, 19, 'RTHS', '0', '54.585');
        $provider[] = array($result, 29, 'RTCS', '89', '56.523');
        $provider[] = array($result, 39, 'STU', '11', '59.903');
        $provider[] = array($result, 49, 'NOVSS', '256', '67.660');
        $provider[] = array($result, 50, 'NOVCS', '56', '71.239');
        $provider[] = array($result, 51, 'XP', '1', '71.725');
        
        return $provider;
    }
}
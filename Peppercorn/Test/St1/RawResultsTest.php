<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\File;
use Peppercorn\St1\Line;
use Peppercorn\St1\Query;
use Peppercorn\St1\LineException;
use Peppercorn\St1\Result;
use Peppercorn\St1\ResultSetSimple;
class RawResultsTest extends ResultsTest
{
    /**
     * @param array $result
     * @param int $resultIndex
     * @param string $class
     * @param string $number
     * @param mixed $time
     * 
     * @dataProvider providerRawResults
     */
    public function testRawResults(ResultSetSimple $resultSet, $resultIndex, $class, $number, $time)
    {
        $this->assertGreaterThan($resultIndex, $resultSet->getCount());
        $result = $resultSet->getIndex($resultIndex);
        $this->assertEquals($resultIndex + 1, $result->getPlace());
        $line = $resultSet->getLine($resultIndex);
        $this->assertEquals($class, $line->getDriverClassRaw());
        $this->assertEquals($number, $line->getDriverNumber());
        $this->assertEquals($time, $line->getTimeRawWithPenalty());
    }
    
    public function providerRawResults()
    {
        $provider = array();
        $results = array();
        $files = $this->getFiles();
        foreach ($files as $file /* @var $file File */) {
            $results[] = Query::rawResults($file);
        }
        // points1
        $result = $results[0];
        $provider[] = array($result, 0, 'XSTS', '155', '49.327');
        $provider[] = array($result, 1, 'STR', '164', '49.824');
        $provider[] = array($result, 2, 'RTDSRT', '111', '50.693');
        $provider[] = array($result, 9, 'SSM', '21', '52.113');
        $provider[] = array($result, 19, 'XSTS', '55', '53.116');
        $provider[] = array($result, 29, 'STU', '15', '55.147');
        $provider[] = array($result, 39, 'OFBS', '6', '56.733');
        $provider[] = array($result, 49, 'STF', '87', '58.160');
        $provider[] = array($result, 55, 'CSP', '24', '61.210');
        $provider[] = array($result, 56, 'RTFPRT', '61', '61.406'); 
        // points2
        $result = $results[1];
        $provider[] = array($result, 0, 'XSTU', '37', '45.600');
        $provider[] = array($result, 1, 'XSS', '66', '45.989');
        $provider[] = array($result, 2, 'XSTS', '155', '46.682');
        $provider[] = array($result, 9, 'STR', '18', '47.238');
        $provider[] = array($result, 19, 'RTFSRT', '6', '48.181');
        $provider[] = array($result, 29, 'DS', '5', '49.680');
        $provider[] = array($result, 39, 'RTCSRT', '14', '50.610');
        $provider[] = array($result, 49, 'RTESPRT', '89', '51.606');
        $provider[] = array($result, 59, 'NOVCS', '12', '53.191');
        $provider[] = array($result, 62, 'NOVCSP', '64', '53.681');
        $provider[] = array($result, 63, 'RTCSRT', '4', '53.681');
        $provider[] = array($result, 69, 'RTDSRT', '98', '54.291');
        $provider[] = array($result, 79, 'NOVCS', '0', '55.530');
        $provider[] = array($result, 89, 'STC', '47', '57.422');
        $provider[] = array($result, 92, 'SM', '10', '60.361');
        $provider[] = array($result, 93, 'SM', '9', '61.950');
        // points3
        $result = $results[2];
        $provider[] = array($result, 0, 'DM', '14', '44.014');
        $provider[] = array($result, 1, 'STR', '0', '45.586');
        $provider[] = array($result, 2, 'SS', '1', '45.771');
        $provider[] = array($result, 9, 'STR', '8', '47.155');
        $provider[] = array($result, 10, 'SS', '31', '47.155');
        $provider[] = array($result, 19, 'RTCSRT', '14', '48.401');
        $provider[] = array($result, 29, 'NOVDM', '14', '49.678');
        $provider[] = array($result, 39, 'RTHSRT', '0', '50.917');
        $provider[] = array($result, 49, 'CSP', '64', '51.925');
        $provider[] = array($result, 59, 'NOVBS', '69', '53.047');
        $provider[] = array($result, 69, 'NOVES', '2', '54.031');
        $provider[] = array($result, 79, 'STC', '1', '55.412');
        $provider[] = array($result, 87, 'NOVDS', '19', '59.104');
        $provider[] = array($result, 88, 'STC', '87', '59.653');
        // points4
        $result = $results[3];
        $provider[] = array($result, 0, 'KM', '11', '36.958');
        $provider[] = array($result, 1, 'XSTU', '37', '38.084');
        $provider[] = array($result, 2, 'SS', '1', '38.224');
        $provider[] = array($result, 9, 'SS', '11', '39.488');
        $provider[] = array($result, 19, 'RTESP', '89', '40.607');
        $provider[] = array($result, 29, 'RTBSP', '8', '41.554');
        $provider[] = array($result, 31, 'RTDSP', '26', '41.681');
        $provider[] = array($result, 32, 'RTDS', '22', '41.681');
        $provider[] = array($result, 39, 'STR', '32', '42.015');
        $provider[] = array($result, 49, 'STC', '79', '42.562');
        $provider[] = array($result, 59, 'NOVSS', '9', '43.808');
        $provider[] = array($result, 69, 'NOVSTF', '55', '45.910');
        $provider[] = array($result, 74, 'ES', '77', '49.581');
        $provider[] = array($result, 75, 'NOVHS', '19', '49.655');
        // points5
        $result = $results[4];
        $provider[] = array($result, 0, 'KM', '388', '48.991');
        $provider[] = array($result, 1, 'CSP', '46', '50.634');
        $provider[] = array($result, 2, 'SS', '1', '50.814');
        $provider[] = array($result, 9, 'STR', '8', '52.283');
        $provider[] = array($result, 19, 'STR', '84', '53.850');
        $provider[] = array($result, 29, 'STR', '25', '55.244');
        $provider[] = array($result, 39, 'OFBSP', '8', '57.372');
        $provider[] = array($result, 49, 'STR', '7', '60.043');
        $provider[] = array($result, 58, 'NOVSTU', '30', '64.270');
        $provider[] = array($result, 59, 'ES', '77', '65.826');
        $provider[] = array($result, 60, 'NOVSTF', '2', '75.994');
        // points6
        $result = $results[5];
        $provider[] = array($result, 0, 'CM', '85', '30.770');
        $provider[] = array($result, 1, 'XKM', '177', '32.300');
        $provider[] = array($result, 2, 'SS', '19', '32.441');
        $provider[] = array($result, 9, 'STR', '80', '33.572');
        $provider[] = array($result, 19, 'RTESP', '8', '34.897');
        $provider[] = array($result, 29, 'RTCSP', '46', '35.511');
        $provider[] = array($result, 39, 'ASP', '1', '36.094');
        $provider[] = array($result, 49, 'RTHS', '0', '36.597');
        $provider[] = array($result, 59, 'SM', '90', '37.676');
        $provider[] = array($result, 69, 'NOVSM', '38', '39.054');
        $provider[] = array($result, 76, 'NOVES', '22', '40.532');
        $provider[] = array($result, 77, 'ES', '5', '40.532');
        $provider[] = array($result, 79, 'ES', '77', '40.935');
        $provider[] = array($result, 84, 'NOVAS', '7', '42.549');
        $provider[] = array($result, 85, 'NOVSM', '15', '47.804');
        
        // points7
        $result = $results[6];
        $provider[] = array($result, 0, 'CM', '85', '31.327');
        $provider[] = array($result, 1, 'SS', '1', '31.974');
        $provider[] = array($result, 2, 'XSTU', '37', '32.682');
        $provider[] = array($result, 9, 'STR', '80', '33.996');
        $provider[] = array($result, 19, 'SSM', '2', '34.760');
        $provider[] = array($result, 29, 'OFBS', '6', '35.678');
        $provider[] = array($result, 39, 'STS', '65', '36.256');
        $provider[] = array($result, 49, 'RTCS', '11', '36.929');
        $provider[] = array($result, 59, 'NOVDM', '44', '38.165');
        $provider[] = array($result, 69, 'STC', '87', '41.459');
        $provider[] = array($result, 71, 'NOVSM', '76', '42.797');
        $provider[] = array($result, 72, 'NOVDSP', '6', '44.185');
        $provider[] = array($result, 73, 'DS', '5', PHP_INT_MAX);
        // points8
        $result = $results[7];
        $provider[] = array($result, 0, 'XASP', '9', '43.953');
        $provider[] = array($result, 1, 'XDS', '97', '44.308');
        $provider[] = array($result, 2, 'SSP', '40', '44.526');
        $provider[] = array($result, 9, 'XSTS', '28', '45.930');
        $provider[] = array($result, 19, 'SM', '2', '46.730');
        $provider[] = array($result, 29, 'SM', '3', '47.830');
        $provider[] = array($result, 33, 'RTCS', '79', '48.099');
        $provider[] = array($result, 34, 'RTCS', '1', '48.099');
        $provider[] = array($result, 39, 'STS', '50', '48.528');
        $provider[] = array($result, 49, 'RTSS', '6', '49.186');
        $provider[] = array($result, 59, 'NOVBS', '45', '50.386');
        $provider[] = array($result, 69, 'STS', '12', '51.195');
        $provider[] = array($result, 79, 'DS', '115', '52.120');
        $provider[] = array($result, 89, 'STF', '127', '52.867');
        $provider[] = array($result, 99, 'RTCS', '27', '53.987');
        $provider[] = array($result, 109, 'STU', '25', '55.168');
        $provider[] = array($result, 119, 'GS', '11', '56.944');
        $provider[] = array($result, 129, 'NOVFS', '44', '61.017');
        $provider[] = array($result, 133, 'NOVBS', '121', '62.469');
        $provider[] = array($result, 134, 'NOVCS', '29', '63.940');
        $provider[] = array($result, 135, 'LADDSP', '1', PHP_INT_MAX);
        $provider[] = array($result, 136, 'STR', '24', PHP_INT_MAX);
        // points9
        $result = $results[8];
        $provider[] = array($result, 0, 'XKM', '50', '51.762');
        $provider[] = array($result, 1, 'STR', '80', '59.950');
        $provider[] = array($result, 2, 'STR', '3', '59.970');
        $provider[] = array($result, 9, 'DS', '78', '62.904');
        $provider[] = array($result, 19, 'STU', '7', '64.632');
        $provider[] = array($result, 29, 'OFBSP', '8', '68.425');
        $provider[] = array($result, 34, 'NOVCSP', '7', '70.002');
        $provider[] = array($result, 35, 'NOVFS', '10', '70.002');
        $provider[] = array($result, 39, 'NOVCS', '19', '71.487');
        $provider[] = array($result, 49, 'XP', '1', '79.606');
        $provider[] = array($result, 50, 'HS', '4', '79.782');
        $provider[] = array($result, 51, 'NOVCS', '56', '85.419');
        return $provider;
    }
}
<?php
namespace Peppercorn\Test\St1;

use Peppercorn\St1\File;
use Peppercorn\St1\Line;
use Peppercorn\St1\Query;
use Peppercorn\St1\SortTimeRawAscending;
use Peppercorn\St1\GroupByDriver;
use Peppercorn\St1\LineException;
class RawResultsTest extends ResultsTest
{
    const KEY_CLASS = 'driverClassRaw';
    const KEY_NUMBER = 'driverNumber';
    const KEY_TIME = 'timeRaw';
    
    /**
     * @param Query $query
     * @param array $expected
     * 
     * @dataProvider providerRawResults
     */
    public function testRawResults(Query $query, array $expected)
    {
        try {
            $actual = $query->execute();
        } catch (LineException $le) {
            $this->fail(
                '$query->execute() should never throw LineException. '
                . 'Message: ' . $le->getMessage()
            );
        }
        foreach ($expected as $index => $values) {
            $line = $actual[$index]; /* @var $line Line */
            try {
                $this->assertEquals($values[self::KEY_CLASS], $line->getDriverClassRaw());
                $this->assertEquals($values[self::KEY_NUMBER], $line->getDriverNumber());
                $this->assertEquals($values[self::KEY_TIME], $line->getTimeRawWithPenalty());
            } catch (LineException $le) {
                $this->fail(
                    'Invalid lines should never be returned from $query->execute()'
                    . 'Message: ' . $le->getMessage()
                );
            }
        }
    }
    
    public function providerRawResults()
    {
        $provider = array();
        $files = $this->getFiles();
        foreach ($files as $file /* @var $file File */) {
            $query = new Query($file);
            $query
                ->orderBy(SortTimeRawAscending::getSort())
                ->distinct(new GroupByDriver());
            $provider[] = array($query);
        }
        // points1
        $provider[0][] = array(
            0 => array( // 1st
                self::KEY_CLASS => 'XSTS',
                self::KEY_NUMBER => '155',
                self::KEY_TIME => '49.327'
            ),
            1 => array( // 2nd
                self::KEY_CLASS => 'STR',
                self::KEY_NUMBER => '164',
                self::KEY_TIME => '49.824'
            ),
            2 => array( // 3rd
                self::KEY_CLASS => 'RTDSRT',
                self::KEY_NUMBER => '111',
                self::KEY_TIME => '50.693'
            ),
            9 => array( // 10th
                self::KEY_CLASS => 'SSM',
                self::KEY_NUMBER => '21',
                self::KEY_TIME => '52.113'
            ),
            19 => array( // 20th
                self::KEY_CLASS => 'XSTS',
                self::KEY_NUMBER => '55',
                self::KEY_TIME => '53.116'
            ),
            29 => array( // 30th
                self::KEY_CLASS => 'STU',
                self::KEY_NUMBER => '15',
                self::KEY_TIME => '55.147'
            ),
            39 => array( // 40th
                self::KEY_CLASS => 'OFBS',
                self::KEY_NUMBER => '6',
                self::KEY_TIME => '56.733'
            ),
            49 => array( // 50th
                self::KEY_CLASS => 'STF',
                self::KEY_NUMBER => '87',
                self::KEY_TIME => '58.160'
            ),
            55 => array( // 56th
                self::KEY_CLASS => 'CSP',
                self::KEY_NUMBER => '24',
                self::KEY_TIME => '61.210'
            ),
            56 => array( // 57th
                self::KEY_CLASS => 'RTFPRT',
                self::KEY_NUMBER => '61',
                self::KEY_TIME => '61.406'
            )
        );
        // points2
        $provider[1][] = array(
        	0 => array( // 1st
        	   self::KEY_CLASS => 'XSTU',
    	       self::KEY_NUMBER => '37',
        	    self::KEY_TIME => '45.600'
        	),
            1 => array( // 2nd
                self::KEY_CLASS => 'XSS',
                self::KEY_NUMBER => '66',
                self::KEY_TIME => '45.989'
            ),
            2 => array( // 3rd
                self::KEY_CLASS => 'XSTS',
                self::KEY_NUMBER => '155',
                self::KEY_TIME => '46.682'
            ),
            9 => array( // 10th
                self::KEY_CLASS => 'STR',
                self::KEY_NUMBER => '18',
                self::KEY_TIME => '47.238'
            ),
            19 => array( // 20th
                self::KEY_CLASS => 'RTFSRT',
                self::KEY_NUMBER => '6',
                self::KEY_TIME => '48.181'
            ),
            29 => array( // 30th
                self::KEY_CLASS => 'DS',
                self::KEY_NUMBER => '5',
                self::KEY_TIME => '49.680'
            ),
            39 => array( // 40th
                self::KEY_CLASS => 'RTCSRT', // known discrepency between .st1 file and official results
                self::KEY_NUMBER => '14',
                self::KEY_TIME => '50.610'
            ),
            49 => array( // 50th
                self::KEY_CLASS => 'RTESPRT',
                self::KEY_NUMBER => '89',
                self::KEY_TIME => '51.606'
            ),
            59 => array( // 60th
                self::KEY_CLASS => 'NOVCS',
                self::KEY_NUMBER => '12',
                self::KEY_TIME => '53.191'
            ),
            69 => array( // 70th
                self::KEY_CLASS => 'RTDSRT',
                self::KEY_NUMBER => '98',
                self::KEY_TIME => '54.291'
            ),
            79 => array( // 80th
                self::KEY_CLASS => 'NOVCS',
                self::KEY_NUMBER => '0',
                self::KEY_TIME => '55.530'
            ),
            89 => array( // 90th
                self::KEY_CLASS => 'STC',
                self::KEY_NUMBER => '47',
                self::KEY_TIME => '57.422'
            ),
            92 => array( // 93rd
                self::KEY_CLASS => 'SM',
                self::KEY_NUMBER => '10',
                self::KEY_TIME => '60.361'
            ),
            93 => array( // 94th
                self::KEY_CLASS => 'SM',
                self::KEY_NUMBER => '9',
                self::KEY_TIME => '61.950'
            )
        );
        // points3
        $provider[2][] = array(
            0 => array( // 1st
                self::KEY_CLASS => 'DM',
                self::KEY_NUMBER => '14',
                self::KEY_TIME => '44.014'
            ),
            1 => array( // 2nd
                self::KEY_CLASS => 'STR',
                self::KEY_NUMBER => '0',
                self::KEY_TIME => '45.586'
            ),
            2 => array( // 3rd
                self::KEY_CLASS => 'SS',
                self::KEY_NUMBER => '1',
                self::KEY_TIME => '45.771'
            ),
            9 => array( // 10th
                self::KEY_CLASS => 'SS',
                self::KEY_NUMBER => '31',
                self::KEY_TIME => '47.155'
            ),
            19 => array( // 20th
                self::KEY_CLASS => 'RTCSRT',
                self::KEY_NUMBER => '14',
                self::KEY_TIME => '48.401'
            ),
            29 => array( // 30th
                self::KEY_CLASS => 'NOVDM',
                self::KEY_NUMBER => '14',
                self::KEY_TIME => '49.678'
            ),
            39 => array( // 40th
            	self::KEY_CLASS => 'RTHSRT',
            	self::KEY_NUMBER => '0',
            	self::KEY_TIME => '50.917'
            ),
            49 => array( // 50th
                self::KEY_CLASS => 'CSP',
                self::KEY_NUMBER => '64',
                self::KEY_TIME => '51.925'
            ),
            59 => array( // 60th
                self::KEY_CLASS => 'NOVBS',
                self::KEY_NUMBER => '69',
                self::KEY_TIME => '53.047'
            ),
            69 => array( // 70th
            	self::KEY_CLASS => 'NOVES',
            	self::KEY_NUMBER => '2',
            	self::KEY_TIME => '54.031'
            ),
            79 => array( // 80th
                self::KEY_CLASS => 'STC',
                self::KEY_NUMBER => '1',
                self::KEY_TIME => '55.412'
            ),
            87 => array( // 88th
                self::KEY_CLASS => 'NOVDS',
                self::KEY_NUMBER => '19',
                self::KEY_TIME => '59.104'
            ),
            88 => array( // 89th
                self::KEY_CLASS => 'STC',
                self::KEY_NUMBER => '87',
                self::KEY_TIME => '59.653'
            )
        );
        // points4
        $provider[3][] = array(
            0 => array( // 1st
                self::KEY_CLASS => 'KM',
                self::KEY_NUMBER => '11',
                self::KEY_TIME => '36.958'
            ),
            1 => array( // 2nd
                self::KEY_CLASS => 'XSTU',
                self::KEY_NUMBER => '37',
                self::KEY_TIME => '38.084',
            ),
            2 => array( // 3rd
                self::KEY_CLASS => 'SS',
                self::KEY_NUMBER => '1',
                self::KEY_TIME => '38.224'
            ),
            9 => array( // 10th
                self::KEY_CLASS => 'SS',
                self::KEY_NUMBER => '11',
                self::KEY_TIME => '39.488'
            ),
            19 => array( // 20th
                self::KEY_CLASS => 'RTESP',
                self::KEY_NUMBER => '89',
                self::KEY_TIME => '40.607'
            ),
            29 => array( // 30th
                self::KEY_CLASS => 'RTBSP',
                self::KEY_NUMBER => '8',
                self::KEY_TIME => '41.554'
            ),
            39 => array( // 40th
                self::KEY_CLASS => 'STR',
                self::KEY_NUMBER => '32',
                self::KEY_TIME => '42.015'
            ),
            49 => array( // 50th
                self::KEY_CLASS => 'STC',
                self::KEY_NUMBER => '79',
                self::KEY_TIME => '42.562'
            ),
            59 => array( // 60th
                self::KEY_CLASS => 'NOVSS',
                self::KEY_NUMBER => '9',
                self::KEY_TIME => '43.808',
            ),
            69 => array( // 70th
                self::KEY_CLASS => 'NOVSTF',
                self::KEY_NUMBER => '55',
                self::KEY_TIME => '45.910'
            ),
            74 => array( // 75th
                self::KEY_CLASS => 'ES',
                self::KEY_NUMBER => '77',
                self::KEY_TIME => '49.581'
            ),
            75 => array( // 76th
                self::KEY_CLASS => 'NOVHS',
                self::KEY_NUMBER => '19',
                self::KEY_TIME => '49.655'
            )
        );
        // points5
        $provider[4][] = array(
        	0 => array( // 1st
                self::KEY_CLASS => 'KM',
                self::KEY_NUMBER => '388',
                self::KEY_TIME => '48.991'
            ),
            1 => array( // 2nd
                self::KEY_CLASS => 'CSP',
                self::KEY_NUMBER => '46',
                self::KEY_TIME => '50.634'
            ),
            2 => array( // 3rd
                self::KEY_CLASS => 'SS',
                self::KEY_NUMBER => '1',
                self::KEY_TIME => '50.814'
            ),
            9 => array( // 10th
                self::KEY_CLASS => 'STR',
                self::KEY_NUMBER => '8',
                self::KEY_TIME => '52.283'
            ),
            19 => array( // 20th
                self::KEY_CLASS => 'STR',
                self::KEY_NUMBER => '84',
                self::KEY_TIME => '53.850'
                // TODO: investigate failure
            ),
            29 => array( // 30th
                self::KEY_CLASS => 'STR',
                self::KEY_NUMBER => '25',
                self::KEY_TIME => '55.244'
            ),
            39 => array( // 40th
                self::KEY_CLASS => 'OFBSP',
                self::KEY_NUMBER => '8',
                self::KEY_TIME => '57.372'
            ),
            49 => array( // 50th
                self::KEY_CLASS => 'STR',
                self::KEY_NUMBER => '7',
                self::KEY_TIME => '60.043'
            ),
            58 => array( // 59th
                self::KEY_CLASS => 'NOVSTU',
                self::KEY_NUMBER => '30',
                self::KEY_TIME => '64.270'
            ),
            59 => array( // 60th
                self::KEY_CLASS => 'ES',
                self::KEY_NUMBER => '77',
                self::KEY_TIME => '65.826'
            ),
            60 => array( // 61st
                self::KEY_CLASS => 'NOVSTF',
                self::KEY_NUMBER => '2',
                self::KEY_TIME => '75.994'
            )
        );
        // points6
        $provider[5][] = array(
        	0 => array( // 1st
                self::KEY_CLASS => 'CM',
                self::KEY_NUMBER => '85',
                self::KEY_TIME => '30.770'
            ),
            1 => array( // 2nd
                self::KEY_CLASS => 'XKM',
                self::KEY_NUMBER => '177',
                self::KEY_TIME => '32.300'
            ),
            2 => array( // 3rd
                self::KEY_CLASS => 'SS',
                self::KEY_NUMBER => '19',
                self::KEY_TIME => '32.441'
            ),
            9 => array( // 10th
                self::KEY_CLASS => 'STR',
                self::KEY_NUMBER => '80',
                self::KEY_TIME => '33.572'
            ),
            19 => array( // 20th
                self::KEY_CLASS => 'RTESP',
                self::KEY_NUMBER => '8',
                self::KEY_TIME => '34.897',
            ),
            29 => array( // 30th
                self::KEY_CLASS => 'RTCSP',
                self::KEY_NUMBER => '46',
                self::KEY_TIME => '35.511'
            ),
            39 => array( // 40th
                self::KEY_CLASS => 'ASP',
                self::KEY_NUMBER => '1',
                self::KEY_TIME => '36.094'
            ),
            49 => array( // 50th
                self::KEY_CLASS => 'RTHS',
                self::KEY_NUMBER => '0',
                self::KEY_TIME => '36.597'
            ),
            59 => array( // 60th
                self::KEY_CLASS => 'SM',
                self::KEY_NUMBER => '90',
                self::KEY_TIME => '37.676'
            ),
            69 => array( // 70th
                self::KEY_CLASS => 'NOVSM',
                self::KEY_NUMBER => '38',
                self::KEY_TIME => '39.054'
            ),
            79 => array( // 80th
                self::KEY_CLASS => 'ES',
                self::KEY_NUMBER => '77',
                self::KEY_TIME => '40.935'
            ),
            84 => array( // 85th
                self::KEY_CLASS => 'NOVAS',
                self::KEY_NUMBER => '7',
                self::KEY_TIME => '42.549'
            ),
            85 => array( // 86th
                self::KEY_CLASS => 'NOVSM',
                self::KEY_NUMBER => '15',
                self::KEY_TIME => '47.804'
            )
        );
        // points7
        $provider[6][] = array(
            0 => array( // 1st
                self::KEY_CLASS => 'CM',
                self::KEY_NUMBER => '85',
                self::KEY_TIME => '31.327'
            ),
            1 => array( // 2nd
                self::KEY_CLASS => 'SS',
                self::KEY_NUMBER => '1',
                self::KEY_TIME => '31.974'
            ),
            2 => array( // 3rd
                self::KEY_CLASS => 'XSTU',
                self::KEY_NUMBER => '37',
                self::KEY_TIME => '32.682'
            ),
            9 => array( // 10th
                self::KEY_CLASS => 'STR',
                self::KEY_NUMBER => '80',
                self::KEY_TIME => '33.996'
            ),
            19 => array( // 20th
                self::KEY_CLASS => 'SSM',
                self::KEY_NUMBER => '2',
                self::KEY_TIME => '34.760'
            ),
            29 => array( // 30th
                self::KEY_CLASS => 'OFBS',
                self::KEY_NUMBER => '6',
                self::KEY_TIME => '35.678'
            ),
            39 => array( // 40th
                self::KEY_CLASS => 'STS',
                self::KEY_NUMBER => '65',
                self::KEY_TIME => '36.256'
            ),
            49 => array( // 50th
                self::KEY_CLASS => 'RTCS',
                self::KEY_NUMBER => '11',
                self::KEY_TIME => '36.929'
            ),
            59 => array( // 60th
                self::KEY_CLASS => 'NOVDM',
                self::KEY_NUMBER => '44',
                self::KEY_TIME => '38.165'
            ),
            69 => array( // 70th
                self::KEY_CLASS => 'STC',
                self::KEY_NUMBER => '87',
                self::KEY_TIME => '41.459'
            ),
            71 => array( // 72nd
                self::KEY_CLASS => 'NOVSM',
                self::KEY_NUMBER => '76',
                self::KEY_TIME => '42.797',
            ),
            72 => array( // 73rd
                self::KEY_CLASS => 'NOVDSP',
                self::KEY_NUMBER => '6',
                self::KEY_TIME => '44.185'
            ),
            73 => array( // 74th
                self::KEY_CLASS => 'DS',
                self::KEY_NUMBER => '5',
                self::KEY_TIME => PHP_INT_MAX
                // TODO: address failure here
            )
        );
        // points8
        $provider[7][] = array();
        // points9
        $provider[8][] = array();
        // nonpoints
        $provider[9][] = array();
        return $provider;
    }
}
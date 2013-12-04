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
        $provider[2][] = array();
        // points4
        $provider[3][] = array();
        // points5
        $provider[4][] = array();
        // points6
        $provider[5][] = array();
        // points7
        $provider[6][] = array();
        // points8
        $provider[7][] = array();
        // points9
        $provider[8][] = array();
        // nonpoints
        $provider[9][] = array();
        return $provider;
    }
}
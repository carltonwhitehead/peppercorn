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
        $provider[1][] = array();
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
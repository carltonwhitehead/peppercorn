<?php
namespace Peppercorn\St1;

/**
 * @todo Refactor $lines and $results to private properties instead of passing references to methods
 */
class Query
{

    /**
     * The file whose lines will be queried
     *
     * @var File
     */
    private $file;

    /**
     * An array of Where objects to test for the inclusion of each line in the Result
     *
     * @var array
     */
    private $wheres = array();

    private static $TEST_WHERES_ASCENDING = 'ascending';
    private static $TEST_WHERES_DESCENDING = 'descending';

    /**
     * Controls the direction of Line evaluation against the where tests
     * @var string
     */
    private $testLinesDirection;

    /**
     * A callable to pass into usort when sorting the results of the query
     * @var callable
     */
    private $sort;
    
    /**
     * A SortTieBreaker to use for breaking ties in the results of a simple query
     * @var SortTieBreaker
     */
    private $tieBreaker;
    
    /**
     * A Grouper to use for narrowing the query to distinct Lines.
     * @var Grouper
     */
    private $distinct;
    
    /**
     * A Grouper to use for grouping Line results
     * @var Grouper
     */
    private $groupBy;
    
    /**
     * Whether or not the Query has been executed already
     * @var boolean
     */
    private $executed = false;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Add a Where condition
     * @param Where $where
     * @return \Peppercorn\St1\Query
     */
    public function where(Where $where)
    {
        $this->wheres[] = $where;
        return $this;
    }

    /**
     * Set the execution of Where tests against each Line in ascending order
     *
     * @return \Peppercorn\St1\Query
     */
    public function setTestLinesAscending()
    {
        $this->testLinesDirection = self::$TEST_WHERES_ASCENDING;
        return $this;
    }

    /**
     * Set the execution of Where tests against each Line in descending order.
     * This is useful for finding only the last run of a given driver, which would contain their final personal best time and finishing positions within their class.
     *
     * @return \Peppercorn\St1\Query
     */
    public function setTestLinesDescending()
    {
        $this->testLinesDirection = self::$TEST_WHERES_DESCENDING;
        return $this;
    }

    /**
     * Specify the callable to sort the results
     * @param callable $sort
     * @return \Peppercorn\St1\Query
     */
    public function orderBy(callable $sort)
    {
        $this->sort = $sort;
        return $this;
    }
    
    /**
     * Specify the SortTieBreaker to use in case of a tie
     * @param SortTieBreaker $sortTieBreaker
     * @return \Peppercorn\St1\Query
     */
    public function breakTiesWith(SortTieBreaker $sortTieBreaker)
    {
        $this->tieBreaker = $sortTieBreaker;
        return $this;
    }
    
    /**
     * Specify a Grouper to use for narrowing the results to distinct lines.
     * For example, if you wanted the fastest runs for each driver, you would pass a grouper that keys by driver (category/class/number)
     * @param Grouper $distinct
     * @return \Peppercorn\St1\Query
     */
    public function distinct(Grouper $distinct)
    {
        $this->distinct = $distinct;
        return $this;
    }
    
    /**
     * Specify a Grouper to use for grouping the Line resuts.
     * When results are grouped, they will be returned in a multidimensional array, as follows:
     * $result = array(
     *      0 => array('lines' => array({Line objects of first group})), 
     *      1 => array('lines' => array({Line objects of second group}))
     * );
     * The array of Line objects is assigned to the 'lines' key to allow for easy addition of 
     * metadata (count of clean runs vs dirty, sum of cones, etc) into the group at other keys.
     * @param Grouper $groupBy
     * @return \Peppercorn\St1\Query
     */
    public function groupBy(Grouper $groupBy)
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /**
     * Execute a simple query
     * Do not use this method with a Grouper set with groupBy(Grouper). It is OK to use with distinct(Grouper)
     * @return array Line objects
     */
    public function executeSimple()
    {
        assert('$this->groupBy === null', 'executeSimple() cannot be used with groupBy()');
        $lines = $this->executeCommon();
        return new ResultSetSimple($this->file, $lines);
    }
    
    /**
     * Execute a grouped query
     * @return ResultSetGrouped
     */
    public function executeGrouped()
    {
        assert('$this->groupBy !== null', 'must call groupBy(Grouper) before calling executeGrouped()');
        
        $lines = $this->executeCommon();
        
        $result = $this->groupLines($lines);
        
        // TODO: support for aggregate processing of Line objects in group (ie sum of cones, etc)
        // TODO: support for having()
        $result->lock();
        return $result;
    }
    
    private function &executeCommon()
    {
        assert('!$this->executed', 'A Query can only be executed once');
        $this->executed = true;
        $lines = $this->testLines();
        $this->sort($lines);
        $lines = $this->filterDistinct($lines);
        $this->breakTies($lines);
        return $lines;
    }
    
    /**
     * Query raw results from the passed file
     * @param File $file
     * @return \Peppercorn\St1\ResultSetSimple
     */
    public static function rawResults(File $file)
    {
        $query = new Query($file);
        $query
            ->orderBy(SortTimeRawAscending::getSort())
            ->breakTiesWith(new SortTieBreakerByNextFastestTimeRaw())
            ->distinct(new GroupByDriver());
        return $query->executeSimple();
    }
    
    /**
     * Query pax results from the passed file
     * @param File $file
     * @return \Peppercorn\St1\ResultSetSimple
     */
    public static function paxResults(File $file)
    {
        $query = new Query($file);
        $query
            ->orderBy(SortTimePaxAscending::getSort())
            ->breakTiesWith(new SortTieBreakerByNextFastestTimePax())
            ->distinct(new GroupByDriver());
        return $query->executeSimple();
    }
    
    private function &testLines()
    {
        switch ($this->testLinesDirection) {
        	case self::$TEST_WHERES_DESCENDING:
        	    return $this->testLinesDescending();
        	case self::$TEST_WHERES_ASCENDING:
        	default:
        	    return $this->testLinesAscending();
        }
    }

    /**
     * Test each line in ascending order
     * @return array only Line objects which passed all Where tests
     */
    private function &testLinesAscending()
    {
        $result = array();
        for ($i = 0; $i < $this->file->getLineCount(); $i++) {
            $line = $this->file->getLine($i);
            try {
                if (!$this->testLine($line)) {
                    continue;
                }
            } catch (LineException $le) {
                continue;
            }
            $result[] = $line;
        }
        return $result;
    }

    /**
     * Test each line in descending order
     * @return array only Line objects which passed all Where tests
     */
    private function &testLinesDescending()
    {
        $result = array();
        for ($i = $this->file->getLineCount() - 1; $i >= 0; $i--) {
            $line = $this->file->getLine($i);
            try {
                if (!$this->testLine($line)) {
                    continue;
                }
            } catch (LineException $le) {
                continue;
            }
            $result[] = $line;
        }
        return $result;
    }

    /**
     * Test the Line with each Where from $this->wheres
     * @param Line $line
     * @return boolean false if any test failed, true if all tests passed
     */
    private function testLine(Line $line)
    {
        if ($line->isValid() === false) {
            return false;
        }
        foreach ($this->wheres as /* @var $where Where */ $where) {
            if ($where->test($line) === false) {
                return false;
            }
        }
        return true;
    }
    
    private function sort(array &$lines)
    {
        if ($this->sort !== null) {
            usort($lines, $this->sort);
        }
    }
    
    /**
     * Filter the Line objects into an array containing only distinct Line objects according to the $distinct Grouper.
     * @param array $lines
     */
    private function &filterDistinct(array &$lines)
    {
        if ($this->distinct !== null) {
            $result = array();
            foreach ($lines as /* @var $line Line */ $line) {
                $key = $this->distinct->getGroupKey($line);
                if (!array_key_exists($key, $result)) {
                    $result[$key] = $line;
                }
            }
            $result = array_values($result);
        } else {
            $result = $lines;
        }
        return $result;
    }
    
    /**
     * Group the lines into a multidimensional array using the $groupBy Grouper
     * @param array $lines
     * @return ResultSetGrouped an unlocked ResultSetGrouped containing the passed lines
     */
    private function groupLines(array $lines)
    {
        assert('$this->groupBy !== null');
        $result = new ResultSetGrouped($this->file);
        foreach ($lines as /* @var $line Line */ $line) {
            $groupKey = $this->groupBy->getGroupKey($line);
            $result->addLine($groupKey, $line);
        }
        return $result;
    }
    
    /**
     * 
     * @param array $lines
     * @return array an array of TieBreak objects indicating any ties broken
     */
    private function breakTies(array &$lines)
    {
        if ($this->tieBreaker === null) {
            return null;
        }
        return $this->tieBreaker->findAndBreakTies($this->sort, $lines);
    }

}
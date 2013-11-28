<?php
namespace Peppercorn\St1;

use Phava\Base\Preconditions;
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
     * A Grouper to use for narrowing the query to distinct Lines.
     * @var Grouper
     */
    private $distinct;

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
     * @return array Line objects
     */
    public function execute()
    {
        // filter results using Where tests
        switch ($this->testLinesDirection) {
        	case self::$TEST_WHERES_DESCENDING:
        	    $result = $this->testLinesDescending();
        	    break;
        	case self::$TEST_WHERES_ASCENDING:
        	default:
        	    $result = $this->testLinesAscending();
        	    break;
        }
        // order results by sort
        if ($this->sort !== null) {
            usort($result, $this->sort);
        }
        if ($this->distinct !== null) {
            $result = $this->filterDistinct($result);
        }
        return $result;
    }

    /**
     * Test each line in ascending order
     * @return array only Line objects which passed all Where tests
     */
    private function testLinesAscending()
    {
        $result = array();
        for ($i = 0; $i < $this->file->getLineCount(); $i++) {
            $line = $this->file->getLine($i);
            if (!$this->testLine($line)) {
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
    private function testLinesDescending()
    {
        $result = array();
        for ($i = $this->file->getLineCount() - 1; $i >= 0; $i--) {
            $line = $this->file->getLine($i);
            if (!$this->testLine($line)) {
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
        foreach ($this->wheres as /* @var $where Where */ $where) {
            if ($where->test($line) === false) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Filter the Line objects into an array containing only distinct Line objects according to the $distinct Grouper.
     * @param array $lines
     */
    private function filterDistinct(array $lines)
    {
        Preconditions::checkState($this->distinct instanceof Grouper);
        $result = array();
        foreach ($lines as /* @var $line Line */ $line) {
            $key = $this->distinct->getGroupKey($line);
            if (!array_key_exists($key, $result)) {
                $result[$key] = $line;
            }
        }
        return array_values($result);
    }

}